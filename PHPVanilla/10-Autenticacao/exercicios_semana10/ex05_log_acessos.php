<?php
declare(strict_types=1);

require_once __DIR__ . '/../SAAutenticacao/src/ConexaoBanco.php';
require_once __DIR__ . '/ex03_rbac_guard.php';

// Caminho do arquivo INI com as credenciais do banco
const CONFIG_AUDITORIA = __DIR__ . '/../SAAutenticacao/src/config/database.ini';

// Estrutura da tabela de auditoria (idempotente: pode rodar mais de uma vez)
const SQL_CRIAR_TABELA = <<<'SQL'
CREATE TABLE IF NOT EXISTS auditoria_logins (
    id         BIGSERIAL    PRIMARY KEY,
    email      VARCHAR(255) NOT NULL,
    ip         VARCHAR(45)  NOT NULL,
    user_agent VARCHAR(500) NOT NULL,
    sucesso    BOOLEAN      NOT NULL,
    data_hora  TIMESTAMP    NOT NULL DEFAULT NOW()
);
CREATE INDEX IF NOT EXISTS idx_auditoria_logins_data
    ON auditoria_logins (data_hora DESC);
SQL;

/** Cria a tabela auditoria_logins e seu índice, se ainda não existirem. */
function criarTabela(PDO $pdo): void
{
    $pdo->exec(SQL_CRIAR_TABELA);
}

/** Remove caracteres de controle e limita o tamanho do texto gravado. */
function sanitizarTexto(string $texto, int $max): string
{
    $limpo = preg_replace('/\p{C}+/u', '', $texto) ?? '';
    preg_match('/^.{0,' . $max . '}/su', $limpo, $trecho);
    return $trecho[0] ?? '';
}

/** Insere um registro de tentativa de login (sucesso ou falha). */
function gravarTentativa(PDO $pdo, string $email, bool $sucesso): void
{
    $sql = 'INSERT INTO auditoria_logins (email, ip, user_agent, sucesso)
            VALUES (:email, :ip, :ua, :sucesso)';
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':email', sanitizarTexto(strtolower($email), 255));
    $stmt->bindValue(':ip', sanitizarTexto($_SERVER['REMOTE_ADDR'] ?? 'desconhecido', 45));
    $stmt->bindValue(':ua', sanitizarTexto($_SERVER['HTTP_USER_AGENT'] ?? 'desconhecido', 500));
    $stmt->bindValue(':sucesso', $sucesso, PDO::PARAM_BOOL);
    $stmt->execute();
}

/** Registra a tentativa sem derrubar o login caso a auditoria falhe. */
function registrarTentativa(PDO $pdo, string $email, bool $sucesso): void
{
    try {
        gravarTentativa($pdo, $email, $sucesso);
    } catch (PDOException $erro) {
        error_log('Falha ao gravar auditoria de login: ' . $erro->getMessage());
    }
}

/** Busca as últimas tentativas de login que falharam. */
function ultimasFalhas(PDO $pdo, int $limite = 50): array
{
    $sql = 'SELECT email, ip, user_agent, data_hora FROM auditoria_logins
            WHERE sucesso = FALSE ORDER BY data_hora DESC, id DESC LIMIT :limite';
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':limite', $limite, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

/** Lista os IPs com mais falhas de login nas últimas 24 horas. */
function ipsSuspeitos(PDO $pdo): array
{
    $sql = "SELECT ip, COUNT(*) AS falhas FROM auditoria_logins
            WHERE sucesso = FALSE AND data_hora > NOW() - INTERVAL '24 hours'
            GROUP BY ip ORDER BY falhas DESC LIMIT 5";
    return $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
}

/** Escapa texto para exibição segura em HTML (evita XSS no painel). */
function escapar(string $valor): string
{
    return htmlspecialchars($valor, ENT_QUOTES, 'UTF-8');
}

/** Converte linhas do banco em <tr> com todas as células escapadas. */
function linhasTabela(array $linhas, array $colunas): string
{
    $html = '';
    foreach ($linhas as $linha) {
        $html .= '<tr>';
        foreach ($colunas as $coluna) {
            $html .= '<td>' . escapar((string) $linha[$coluna]) . '</td>';
        }
        $html .= '</tr>';
    }
    return $html !== ''
        ? $html
        : '<tr><td colspan="' . count($colunas) . '">Nenhum registro.</td></tr>';
}

/** Monta a página do painel de auditoria. */
function paginaPainel(array $falhas, array $ips): string
{
    $linhasFalhas = linhasTabela($falhas, ['data_hora', 'email', 'ip', 'user_agent']);
    $linhasIps    = linhasTabela($ips, ['ip', 'falhas']);
    return <<<HTML
    <!DOCTYPE html>
    <html lang="pt-BR">
    <head><meta charset="UTF-8"><title>Painel de auditoria</title>
    <style>body{font-family:sans-serif;margin:2rem}table{border-collapse:collapse;margin-bottom:2rem}td,th{border:1px solid #ccc;padding:.4rem .6rem;font-size:.9rem}th{background:#eee}</style></head>
    <body>
        <h1>Painel de auditoria de acessos</h1>
        <h2>IPs com mais falhas (últimas 24 horas)</h2>
        <table><tr><th>IP</th><th>Falhas</th></tr>{$linhasIps}</table>
        <h2>Últimas tentativas de login com falha</h2>
        <table><tr><th>Data/hora</th><th>E-mail</th><th>IP</th><th>Navegador</th></tr>{$linhasFalhas}</table>
    </body>
    </html>
    HTML;
}

/** Define as flags seguras do cookie antes de abrir a sessão. */
function configurarSessaoPainel(): void
{
    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_set_cookie_params(['httponly' => true, 'samesite' => 'Lax']);
    }
}

/** Exibe o painel; apenas usuários com perfil ADMIN passam. */
function painel(): void
{
    configurarSessaoPainel();
    verificarPermissao('ADMIN');
    try {
        $pdo = ConexaoBanco::obterConexao(CONFIG_AUDITORIA);
        echo paginaPainel(ultimasFalhas($pdo), ipsSuspeitos($pdo));
    } catch (Throwable $erro) {
        http_response_code(500);
        echo 'Erro ao carregar o painel. A tabela auditoria_logins foi criada?';
    }
}

/** Cria a tabela quando o arquivo é executado pelo terminal. */
function criarTabelaPeloTerminal(): void
{
    criarTabela(ConexaoBanco::obterConexao(CONFIG_AUDITORIA));
    echo 'Tabela auditoria_logins pronta.' . PHP_EOL;
}

/** Terminal: cria a tabela. Navegador: mostra o painel. */
function main(): void
{
    if (PHP_SAPI === 'cli') {
        criarTabelaPeloTerminal();
        return;
    }
    painel();
}

// Só executa quando o arquivo é aberto diretamente (não ao ser incluído)
if (basename($_SERVER['SCRIPT_FILENAME'] ?? '') === basename(__FILE__)) {
    main();
}
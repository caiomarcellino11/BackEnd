
<?php
declare(strict_types=1);

require_once __DIR__ . '/../SAAutenticacao/src/ConexaoBanco.php';
require_once __DIR__ . '/../SAAutenticacao/src/usuarioDAO.php';
require_once __DIR__ . '/ex05_log_acessos.php';

const CONFIG_PATH = __DIR__ . '/../SAAutenticacao/src/config/database.ini';

const MAX_TENTATIVAS = 3;
const BLOQUEIO_SEGUNDOS = 60;

/**
 * Inicia a sessão com cookies seguros.
 */
function iniciarSessaoSegura(): void
{
    if (session_status() === PHP_SESSION_ACTIVE) {
        return;
    }

    session_set_cookie_params([
        'httponly' => true,
        'samesite' => 'Lax',
    ]);

    session_start();
}

/**
 * Calcula os segundos restantes do bloqueio.
 */
function segundosRestantes(): int
{
    return max(0, (int) ($_SESSION['bloqueio_ate'] ?? 0) - time());
}

/**
 * Verifica se o login está bloqueado.
 */
function estaBloqueado(): bool
{
    return segundosRestantes() > 0;
}

/**
 * Libera o login quando o bloqueio expira.
 */
function liberarSeExpirou(): void
{
    if (isset($_SESSION['bloqueio_ate']) && !estaBloqueado()) {
        resetarTentativas();
    }
}

/**
 * Reseta o contador de tentativas.
 */
function resetarTentativas(): void
{
    $_SESSION['tentativas_login'] = 0;
    unset($_SESSION['bloqueio_ate']);
}

/**
 * Registra uma falha e verifica o limite.
 */
function registrarFalha(): void
{
    $total = (int) ($_SESSION['tentativas_login'] ?? 0) + 1;

    $_SESSION['tentativas_login'] = $total;

    if ($total >= MAX_TENTATIVAS) {
        $_SESSION['bloqueio_ate'] = time() + BLOQUEIO_SEGUNDOS;
    }
}

/**
 * Confere as credenciais do usuário.
 */
function autenticar(UsuarioDAO $dao, string $email, string $senha): ?array
{
    $usuario = $dao->buscarPorEmail($email);

    if ($usuario === null || !password_verify($senha, $usuario['senha_hash'])) {
        return null;
    }

    return $usuario;
}

/**
 * Inicia a sessão do usuário autenticado.
 */
function iniciarUsuario(array $usuario): void
{
    session_regenerate_id(true);

    resetarTentativas();

    $_SESSION['usuario_id'] = $usuario['id'];
    $_SESSION['perfil'] = $usuario['perfil'];
}

/**
 * Monta a mensagem de falha do login.
 */
function mensagemFalha(): string
{
    if (estaBloqueado()) {
        return 'Muitas tentativas incorretas. Login bloqueado por ' .
            BLOQUEIO_SEGUNDOS . ' segundos.';
    }

    $restam = MAX_TENTATIVAS - (int) $_SESSION['tentativas_login'];

    return "E-mail ou senha inválidos. Restam {$restam} tentativa(s).";
}

/**
 * Processa o login e registra a tentativa na auditoria.
 */
function processarLogin(UsuarioDAO $dao, PDO $pdo): string
{
    if (estaBloqueado()) {
        return 'Login bloqueado. Aguarde o contador chegar a zero.';
    }

    $email = trim((string) ($_POST['email'] ?? ''));
    $senha = (string) ($_POST['senha'] ?? '');

    $usuario = autenticar($dao, $email, $senha);

    if ($usuario === null) {
        registrarTentativa($pdo, $email, false);

        registrarFalha();

        return mensagemFalha();
    }

    registrarTentativa($pdo, $email, true);

    iniciarUsuario($usuario);

    return 'Login realizado com sucesso!';
}

/**
 * Gera o contador regressivo do bloqueio.
 */
function scriptContador(int $restante): string
{
    return <<<JS
    <script>
    let s = {$restante};
    const btn = document.getElementById('btn');
    const c = document.getElementById('contador');

    function tick() {
        if (s <= 0) {
            c.textContent = '';
            btn.disabled = false;
            return;
        }

        c.textContent = 'Tente novamente em ' + s + ' s';
        s--;

        setTimeout(tick, 1000);
    }

    tick();
    </script>
    JS;
}

/**
 * Monta a página de login.
 */
function paginaLogin(string $mensagem, int $restante): string
{
    $texto = htmlspecialchars($mensagem, ENT_QUOTES, 'UTF-8');
    $bloq = $restante > 0 ? 'disabled' : '';
    $js = scriptContador($restante);

    return <<<HTML
    <!DOCTYPE html>
    <html lang="pt-BR">
    <head>
        <meta charset="UTF-8">
        <title>Login</title>
    </head>
    <body>
        <h1>Login</h1>

        <p>{$texto}</p>

        <form method="post">
            <input type="email" name="email" placeholder="E-mail" required>
            <input type="password" name="senha" placeholder="Senha" required>

            <button id="btn" {$bloq}>Entrar</button>
        </form>

        <p id="contador"></p>

        {$js}
    </body>
    </html>
    HTML;
}

/**
 * Inicializa o sistema e processa o formulário.
 */
function main(): void
{
    iniciarSessaoSegura();
    liberarSeExpirou();

    $mensagem = '';

    try {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $pdo = ConexaoBanco::obterConexao(CONFIG_PATH);
            $dao = new UsuarioDAO($pdo);

            $mensagem = processarLogin($dao, $pdo);
        }
    } catch (Throwable $erro) {
        error_log($erro->getMessage());

        $mensagem = 'Erro interno. Tente novamente mais tarde.';
    }

    echo paginaLogin($mensagem, segundosRestantes());
}

main();

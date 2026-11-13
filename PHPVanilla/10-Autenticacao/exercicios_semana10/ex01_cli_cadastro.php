<?php
declare(strict_types=1);

require_once __DIR__ . '/../SAAutenticacao/src/ConexaoBanco.php';
require_once __DIR__ . '/../SAAutenticacao/src/usuarioDAO.php';

// Caminho do arquivo INI com as credenciais do banco
const CONFIG_PATH = __DIR__ . '/../SAAutenticacao/src/config/database.ini';

// Perfis aceitos no cadastro
const PERFIS_VALIDOS = ['ADMIN', 'OPERADOR'];

/** Obtém a conexão única com o PostgreSQL. */
function conectar(): PDO
{
    return ConexaoBanco::obterConexao(CONFIG_PATH);
}

/** Exibe o rótulo e lê uma linha do terminal. */
function perguntar(string $rotulo): string
{
    echo $rotulo;
    return trim((string) fgets(STDIN));
}

/** Lê a senha; oculta a digitação apenas em Linux/macOS. */
function perguntarSenha(string $rotulo): string
{
    echo $rotulo;
    $ocultar = PHP_OS_FAMILY !== 'Windows';
    if ($ocultar) {
        shell_exec('stty -echo');
    }
    $senha = rtrim((string) fgets(STDIN), "\r\n");
    if ($ocultar) {
        shell_exec('stty echo');
    }
    echo PHP_EOL;
    return $senha;
}

/** Devolve a lista de requisitos de complexidade que a senha não atende. */
function validarComplexidade(string $senha): array
{
    $regras = [
        '/^.{8,}$/su'      => 'mínimo de 8 caracteres',
        '/\p{Lu}/u'        => 'uma letra maiúscula',
        '/\p{Ll}/u'        => 'uma letra minúscula',
        '/\d/'             => 'um número',
        '/[^\p{L}\p{N}]/u' => 'um caractere especial',
    ];
    $faltando = [];
    foreach ($regras as $regex => $descricao) {
        if (!preg_match($regex, $senha)) {
            $faltando[] = $descricao;
        }
    }
    return $faltando;
}

/** Repete a pergunta até a senha atender a todos os requisitos. */
function lerSenhaValida(): string
{
    while (true) {
        $senha    = perguntarSenha('Senha: ');
        $faltando = validarComplexidade($senha);
        if ($faltando === []) {
            return $senha;
        }
        echo 'Senha fraca. Falta: ' . implode(', ', $faltando) . PHP_EOL;
    }
}

/** Lê o e-mail, validando formato e duplicidade. */
function lerEmail(UsuarioDAO $dao): string
{
    while (true) {
        $email = perguntar('E-mail: ');
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo 'E-mail inválido.' . PHP_EOL;
        } elseif ($dao->emailExiste($email)) {
            echo 'E-mail já cadastrado.' . PHP_EOL;
        } else {
            return $email;
        }
    }
}

/** Lê o perfil aceitando apenas valores permitidos. */
function lerPerfil(): string
{
    do {
        $perfil = strtoupper(perguntar('Perfil (ADMIN/OPERADOR): '));
    } while (!in_array($perfil, PERFIS_VALIDOS, true));
    return $perfil;
}

/** Lê o nome, exigindo que não fique vazio. */
function lerNome(): string
{
    do {
        $nome = perguntar('Nome: ');
    } while ($nome === '');
    return $nome;
}

/** Coleta e valida todos os dados do novo colaborador. */
function coletarDados(UsuarioDAO $dao): array
{
    return [
        'nome'   => lerNome(),
        'email'  => lerEmail($dao),
        'senha'  => lerSenhaValida(),
        'perfil' => lerPerfil(),
    ];
}

/** Fluxo principal do cadastro. */
function main(): void
{
    try {
        $dao   = new UsuarioDAO(conectar());
        $dados = coletarDados($dao);
        // O hash Argon2id é gerado dentro do UsuarioDAO::cadastrar()
        $dao->cadastrar($dados['nome'], $dados['email'], $dados['senha'], $dados['perfil']);
        echo 'Colaborador cadastrado com sucesso!' . PHP_EOL;
    } catch (Throwable $erro) {
        echo 'Falha no cadastro: ' . $erro->getMessage() . PHP_EOL;
        exit(1);
    }
}

main();
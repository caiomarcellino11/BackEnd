<?php
declare(strict_types=1);

/*
 * Exercício 1 - CLI de cadastro seguro de colaboradores.
 * Uso: php ex01_cli_cadastro.php
 */

require_once __DIR__ . '/../SAAutenticacao/src/usuarioDAO.php';

const PERFIS_VALIDOS = ['OPERADOR', 'ADMIN'];

/** Exibe o prompt e lê uma linha digitada no terminal. */
function lerEntrada(string $prompt): string
{
    echo $prompt;

    return trim((string) fgets(STDIN));
}

/** Lê a senha sem exibir os caracteres (quando o terminal suporta stty). */
function lerSenhaOculta(string $prompt): string
{
    $oculta = DIRECTORY_SEPARATOR === '/';

    echo $prompt;
    if ($oculta) {
        shell_exec('stty -echo');
    }
    $senha = rtrim((string) fgets(STDIN), "\r\n");
    if ($oculta) {
        shell_exec('stty echo');
    }
    echo PHP_EOL;

    return $senha;
}

/** Retorna a lista de regras de complexidade que a senha NÃO atende. */
function validarComplexidade(string $senha): array
{
    $regras = [
        '/.{8,}/u'       => 'mínimo de 8 caracteres',
        '/[A-Z]/'        => 'ao menos uma letra maiúscula',
        '/[a-z]/'        => 'ao menos uma letra minúscula',
        '/\d/'           => 'ao menos um número',
        '/[^A-Za-z0-9]/' => 'ao menos um caractere especial',
    ];
    $erros = [];

    foreach ($regras as $padrao => $mensagem) {
        if (preg_match($padrao, $senha) !== 1) {
            $erros[] = $mensagem;
        }
    }

    return $erros;
}

/** Abre a conexão com o PostgreSQL (troque pela sua classe de conexão, se já tiver uma). */
function conectarBanco(): PDO
{
    $host  = getenv('DB_HOST') ?: 'localhost';
    $porta = getenv('DB_PORT') ?: '5432';
    $banco = getenv('DB_NAME') ?: 'sistema';

    return new PDO("pgsql:host={$host};port={$porta};dbname={$banco}",
        getenv('DB_USER') ?: 'postgres', getenv('DB_PASS') ?: '', [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        ]);
}

/** Pede a senha repetidamente até que seja forte e confirmada. */
function solicitarSenhaValida(): string
{
    while (true) {
        $senha = lerSenhaOculta('Senha: ');
        $erros = validarComplexidade($senha);

        if ($erros !== []) {
            echo "Senha fraca. Requisitos pendentes:\n - " . implode("\n - ", $erros) . "\n";
        } elseif ($senha !== lerSenhaOculta('Confirme a senha: ')) {
            echo "As senhas não conferem. Tente novamente.\n";
        } else {
            return $senha;
        }
    }
}

/** Pede um e-mail válido e ainda não cadastrado. */
function solicitarEmailValido(UsuarioDAO $dao): string
{
    while (true) {
        $email = strtolower(lerEntrada('E-mail: '));

        if (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
            echo "E-mail inválido.\n";
        } elseif ($dao->emailExiste($email)) {
            echo "Já existe um colaborador com esse e-mail.\n";
        } else {
            return $email;
        }
    }
}

/** Pede o perfil do colaborador; vazio assume OPERADOR. */
function solicitarPerfil(): string
{
    while (true) {
        $perfil = strtoupper(lerEntrada('Perfil [OPERADOR/ADMIN] (Enter = OPERADOR): '));
        $perfil = $perfil === '' ? 'OPERADOR' : $perfil;

        if (in_array($perfil, PERFIS_VALIDOS, true)) {
            return $perfil;
        }
        echo "Perfil inválido.\n";
    }
}

/** Fluxo completo de cadastro de um colaborador. */
function cadastrarColaborador(UsuarioDAO $dao): void
{
    $nome   = lerEntrada('Nome: ');
    $email  = solicitarEmailValido($dao);
    $perfil = solicitarPerfil();
    $senha  = solicitarSenhaValida();

    // O DAO aplica password_hash() (Argon2id) antes de gravar no banco
    $ok = $dao->cadastrar($nome, $email, $senha, $perfil);
    echo $ok ? "Colaborador '{$nome}' cadastrado com sucesso.\n" : "Falha ao cadastrar.\n";
}

/** Ponto de entrada: valida o ambiente e executa o cadastro. */
function main(): void
{
    if (PHP_SAPI !== 'cli') {
        exit("Este script deve ser executado pelo terminal.\n");
    }
    if (!defined('PASSWORD_ARGON2ID')) {
        exit("Este PHP foi compilado sem suporte a Argon2id.\n");
    }

    try {
        cadastrarColaborador(new UsuarioDAO(conectarBanco()));
    } catch (PDOException $e) {
        fwrite(STDERR, "Erro ao acessar o banco de dados: {$e->getMessage()}\n");
        exit(1);
    }
}

main();




?>
<?php
declare(strict_types=1);


require_once __DIR__ . '/SAAutenticacao/src/ConexaoBanco.php';
require_once __DIR__ . '/SAAutenticacao/src/UsuarioDAO.php';
require_once __DIR__ . '/SAAutenticacao/src/AuthService.php';

const CONFIG_PATH = __DIR__ . '/SAAutenticacao/config/database.ini';

AuthService::iniciarSessaoSegura();

// Se o usuario ja estiver logado, redireciona para a dashboard
if (isset($_SESSION['usuario_id'])) {
    header('Location: dashboard.php');
    exit;
}

$erro = '';

/**
 * Valida as entradas fornecidas no formulario de cadastro.
 */
function validarFormulario(string $nome, string $email, string $senha, string $confirmar): ?string {
    if (empty($nome) || empty($email) || empty($senha) || empty($confirmar)) {
        return 'Todos os campos sao de preenchimento obrigatorio.';
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return 'Informe um endereco de e-mail corporativo valido.';
    }
    if (strlen($senha) < 8) {
        return 'A senha deve possuir no minimo 8 caracteres.';
    }
    if ($senha !== $confirmar) {
        return 'As senhas informadas nao coincidem.';
    }
    return null;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome      = trim($_POST['nome'] ?? '');
    $email     = trim($_POST['email'] ?? '');
    $senha     = (string)($_POST['senha'] ?? '');
    $confirmar = (string)($_POST['confirmar_senha'] ?? '');

    $erro = validarFormulario($nome, $email, $senha, $confirmar);

    if ($erro === null) {
        $pdo = ConexaoBanco::obterConexao(CONFIG_PATH);
        $usuarioDAO = new UsuarioDAO($pdo);

        if ($usuarioDAO->emailExiste($email)) {
            $erro = 'Este e-mail ja se encontra registrado no sistema.';
        } elseif ($usuarioDAO->cadastrar($nome, $email, $senha, 'OPERADOR')) {
            header('Location: login.php?sucesso=cadastrado');
            exit;
        } else {
            $erro = 'Falha ao registrar o usuario. Tente novamente mais tarde.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Usuário — SENAI TechPortal</title>
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; background: #0f172a; display: flex; align-items: center; justify-content: center; min-height: 100vh; margin: 0; color: #f8fafc; }
        .card-cadastro { background: #1e293b; padding: 35px; border-radius: 10px; width: 100%; max-width: 420px; box-shadow: 0 10px 25px rgba(0,0,0,0.4); border: 1px solid #334155; }
        h1 { color: #38bdf8; font-size: 1.5rem; text-align: center; margin-top: 0; }
        p.subtitulo { text-align: center; color: #94a3b8; font-size: 0.85rem; margin-bottom: 25px; }
        .form-group { margin-bottom: 16px; }
        label { display: block; font-size: 0.85rem; font-weight: 600; margin-bottom: 6px; color: #cbd5e1; }
        input { width: 100%; padding: 10px; border: 1px solid #475569; border-radius: 5px; background: #0f172a; color: #f8fafc; box-sizing: border-box; }
        input:focus { border-color: #38bdf8; outline: none; }
        button { width: 100%; padding: 12px; background: #0284c7; color: white; border: none; border-radius: 5px; font-weight: bold; cursor: pointer; margin-top: 10px; font-size: 0.95rem; }
        button:hover { background: #0369a1; }
        .alerta-erro { background: #7f1d1d; border: 1px solid #dc2626; color: #fecaca; padding: 10px; border-radius: 5px; font-size: 0.85rem; margin-bottom: 18px; text-align: center; }
        .rodape-link { margin-top: 20px; text-align: center; font-size: 0.85rem; color: #94a3b8; }
        .rodape-link a { color: #38bdf8; text-decoration: none; font-weight: bold; }
        .rodape-link a:hover { text-decoration: underline; }
    </style>
</head>
<body>
<div class="card-cadastro">
    <h1>📝 Novo Cadastro</h1>
    <p class="subtitulo">Crie seu acesso para utilizar o portal corporativo</p>

    <?php if (!empty($erro)): ?>
        <div class="alerta-erro"><?= htmlspecialchars($erro, ENT_QUOTES, 'UTF-8') ?></div>
    <?php endif; ?>

    <form action="cadastro.php" method="POST">
        <div class="form-group">
            <label for="nome">Nome Completo</label>
            <input type="text" id="nome" name="nome" required placeholder="Ex.: Maria Souza" value="<?= htmlspecialchars($_POST['nome'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
        </div>
        <div class="form-group">
            <label for="email">E-mail Corporativo</label>
            <input type="email" id="email" name="email" required placeholder="usuario@senai.br" value="<?= htmlspecialchars($_POST['email'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
        </div>
        <div class="form-group">
            <label for="senha">Senha (mínimo 8 caracteres)</label>
            <input type="password" id="senha" name="senha" required placeholder="••••••••">
        </div>
        <div class="form-group">
            <label for="confirmar_senha">Confirmar Senha</label>
            <input type="password" id="confirmar_senha" name="confirmar_senha" required placeholder="••••••••">
        </div>
        <button type="submit">Cadastrar e Criar Conta</button>
    </form>

    <div class="rodape-link">
        Já possui conta ativa? <a href="login.php">Fazer login</a>
    </div>
</div>
</body>
</html>
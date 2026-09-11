<?php

declare(strict_types=1);

// Captura dos dados com operador de coalescência nula
$email = $_POST['email'] ?? '';
$senha = $_POST['senha'] ?? '';

$erros = [];
$sucesso = false;

// Processamento do formulário via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 1. Validação do formato de e-mail
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $erros[] = "Informe um e-mail válido.";
    }

    // 2. Validação do tamanho mínimo da senha
    if (strlen($senha) < 6) {
        $erros[] = "A senha deve ter no mínimo 6 caracteres.";
    }

    // 3. Verificação das credenciais fictícias
    if (empty($erros)) {
        if ($email === 'admin@senai.br' && $senha === 'senhaSegura123') {
            $sucesso = true;
        } else {
            $erros[] = "Credenciais inválidas.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel de Autenticação Segura</title>
</head>
<body>

    <h2>Login do Sistema</h2>

    <!-- Exibição das mensagens de erro -->
    <?php if (!empty($erros)): ?>
        <ul style="color: red;">
            <?php foreach ($erros as $erro): ?>
                <li><?= htmlspecialchars($erro) ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

    <!-- Exibição condicional: Card de Boas-Vindas ou Form de Login -->
    <?php if ($sucesso): ?>
        <div style="border: 2px solid green; padding: 15px; background-color: #e6ffe6; border-radius: 5px;">
            <h3>Bem-vindo, <?= htmlspecialchars($email) ?>!</h3>
            <p>Autenticação realizada com sucesso no painel administrativo.</p>
        </div>
    <?php else: ?>
        <form action="" method="POST">
            <label for="email">E-mail:</label><br>
            <input type="text" id="email" name="email" value="<?= htmlspecialchars($email) ?>"><br><br>

            <label for="senha">Senha:</label><br>
            <!-- O atributo value NUNCA é colocado aqui por segurança -->
            <input type="password" id="senha" name="senha"><br><br>

            <button type="submit">Entrar</button>
        </form>
    <?php endif; ?>

</body>
</html>
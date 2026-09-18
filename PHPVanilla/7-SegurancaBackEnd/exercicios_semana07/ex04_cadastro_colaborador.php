<?php
declare(strict_types=1);


//  Escapa texto para saída segura em HTML.
 
function e(string $texto): string
{
    return htmlspecialchars($texto, ENT_QUOTES, 'UTF-8');
}


//   Remove tags HTML e espaços nas pontas de um texto vindo do usuário.
 
function sanitizarTexto(string $dado): string
{
    return trim(strip_tags($dado));
}


//   Valida os dados do colaborador e retorna um array estruturado:
//   ['ok' => bool, 'dados' => array, 'erros' => array]

function validarColaborador(array $dados): array
{
    $erros = [];

    $nome = sanitizarTexto($dados['nome'] ?? '');
    if (mb_strlen($nome) < 3) {
        $erros[] = 'Nome inválido. Mínimo de 3 caracteres.';
    }

    $email = filter_var(trim($dados['email'] ?? ''), FILTER_VALIDATE_EMAIL);
    if ($email === false) {
        $erros[] = 'E-mail inválido.';
    }

    $matricula = filter_var($dados['matricula'] ?? '', FILTER_VALIDATE_INT);
    if ($matricula === false) {
        $erros[] = 'Matrícula inválida. Deve ser um número inteiro.';
    }

    $salario = filter_var($dados['salario'] ?? '', FILTER_VALIDATE_FLOAT);
    if ($salario === false) {
        $erros[] = 'Salário inválido. Deve ser um número decimal.';
    }

    return [
        'ok'     => empty($erros),
        'dados'  => [
            'nome'      => $nome,
            'email'     => $email,
            'matricula' => $matricula,
            'salario'   => $salario,
        ],
        'erros'  => $erros,
    ];
}

$resultado = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $resultado = validarColaborador($_POST);
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Cadastro de Colaboradores</title>
</head>
<body>

<h1>Cadastro de Colaborador</h1>

<form method="POST" action="">
    <label>
        Nome:
        <input type="text" name="nome" value="<?= e($_POST['nome'] ?? '') ?>">
    </label>
    <br>
    <label>
        E-mail:
        <input type="text" name="email" value="<?= e($_POST['email'] ?? '') ?>">
    </label>
    <br>
    <label>
        Matrícula:
        <input type="text" name="matricula" value="<?= e($_POST['matricula'] ?? '') ?>">
    </label>
    <br>
    <label>
        Salário:
        <input type="text" name="salario" value="<?= e($_POST['salario'] ?? '') ?>">
    </label>
    <br>
    <button type="submit">Cadastrar</button>
</form>

<?php if ($resultado !== null && !$resultado['ok']): ?>
    <ul style="color:red;">
        <?php foreach ($resultado['erros'] as $erro): ?>
            <li><?= e($erro) ?></li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>

<?php if ($resultado !== null && $resultado['ok']): ?>
    <h2>Colaborador cadastrado com sucesso!</h2>
    <ul>
        <li>Nome: <?= e($resultado['dados']['nome']) ?></li>
        <li>E-mail: <?= e($resultado['dados']['email']) ?></li>
        <li>Matrícula: <?= e((string) $resultado['dados']['matricula']) ?></li>
        <li>Salário: <?= e(number_format($resultado['dados']['salario'], 2, ',', '.')) ?></li>
    </ul>
<?php endif; ?>

</body>
</html>
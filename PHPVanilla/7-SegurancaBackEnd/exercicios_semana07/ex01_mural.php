<?php

declare(strict_types=1);

// Função para escapar os dados e evitar XSS.
function e(string $texto): string
{
    return htmlspecialchars(
        $texto,
        ENT_QUOTES | ENT_SUBSTITUTE,
        'UTF-8'
    );
}

$nome = trim($_POST['nome'] ?? '');
$mensagem = trim($_POST['mensagem'] ?? '');

$erros = [];

// Arquivo onde os recados serão armazenados.
$arquivo = 'mural.json';

$recados = [];

// Carrega os recados já existentes.
if (file_exists($arquivo)) {

    $conteudo = file_get_contents($arquivo);

    $recados = json_decode($conteudo, true) ?? [];
}

// Validação do nome.
if ($nome === '') {

    $erros[] = 'O nome é obrigatório.';

} elseif (strlen($nome) < 3) {

    $erros[] = 'O nome deve ter pelo menos 3 caracteres.';
}

// Validação da mensagem.
if ($mensagem === '') {

    $erros[] = 'A mensagem é obrigatória.';

} elseif (strlen($mensagem) < 5) {

    $erros[] = 'A mensagem deve ter pelo menos 5 caracteres.';
}

// Se o formulário foi enviado e não existem erros.
if ($_SERVER['REQUEST_METHOD'] === 'POST' && empty($erros)) {

    // Adiciona o novo recado ao array.
    $recados[] = [
        'nome' => $nome,
        'mensagem' => $mensagem
    ];

    // Salva os recados no arquivo JSON.
    file_put_contents(
        $arquivo,
        json_encode(
            $recados,
            JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE
        )
    );
}

?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Mural de Recados</title>

</head>

<body>

    <h1>Mural de Recados</h1>

    <?php foreach ($erros as $erro): ?>

        <p><?= e($erro) ?></p>

    <?php endforeach; ?>


    <form method="POST">

        <label for="nome">Nome:</label>

        <input
            type="text"
            id="nome"
            name="nome"
            value="<?= e($nome) ?>"
        >

        <br><br>


        <label for="mensagem">Mensagem:</label>

        <textarea
            id="mensagem"
            name="mensagem"
        ><?= e($mensagem) ?></textarea>

        <br><br>

        <button type="submit">Enviar</button>

    </form>


    <hr>


    <h2>Recados</h2>

    <?php foreach ($recados as $recado): ?>

        <h3><?= e($recado['nome']) ?></h3>

        <p><?= nl2br(e($recado['mensagem'])) ?></p>

    <?php endforeach; ?>

</body>

</html>
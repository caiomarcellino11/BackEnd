<?php
declare(strict_types=1);


//  Escapa texto para saída segura em HTML.

function e(string $texto): string
{
    return htmlspecialchars($texto, ENT_QUOTES, 'UTF-8');
}


//  Valida se a URL é bem formada e usa protocolo http/https.
//   Retorna a URL validada ou null se for inválida/perigosa.

function linkSeguro(string $url): ?string
{
    $url = trim($url);

    if ($url === '' || !filter_var($url, FILTER_VALIDATE_URL)) {
        return null;
    }

    if (!str_starts_with($url, 'http://') && !str_starts_with($url, 'https://')) {
        return null;
    }

    return $url;
}

//  Valida o nome do desenvolvedor (mínimo 3 caracteres).
 
function nomeValido(string $nome): ?string
{
    $nome = trim($nome);

    if (mb_strlen($nome) < 3) {
        return null;
    }

    return $nome;
}

$erros = [];
$nomeOk = null;
$linkOk = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nomeInput = $_POST['nome'] ?? '';
    $linkInput = $_POST['link'] ?? '';

    $nomeOk = nomeValido($nomeInput);
    $linkOk = linkSeguro($linkInput);

    if ($nomeOk === null) {
        $erros[] = 'Nome inválido. Mínimo de 3 caracteres.';
    }

    if ($linkOk === null) {
        $erros[] = 'Link inválido. Use uma URL completa iniciando com http:// ou https://.';
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Validador de Links de Portfólio</title>
</head>
<body>

<h1>Cadastro de Portfólio</h1>

<form method="POST" action="">
    <label>
        Nome:
        <input type="text" name="nome" value="<?= e($_POST['nome'] ?? '') ?>">
    </label>
    <br>
    <label>
        Link (GitHub/LinkedIn):
        <input type="text" name="link" value="<?= e($_POST['link'] ?? '') ?>">
    </label>
    <br>
    <button type="submit">Cadastrar</button>
</form>

<?php if (!empty($erros)): ?>
    <ul style="color:red;">
        <?php foreach ($erros as $erro): ?>
            <li><?= e($erro) ?></li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>

<?php if ($nomeOk !== null && $linkOk !== null): ?>
    <h2>Cadastro realizado com sucesso!</h2>
    <p>
        <?= e($nomeOk) ?> —
        <a href="<?= e($linkOk) ?>" target="_blank">Visitar Portfólio</a>
    </p>
<?php endif; ?>

</body>
</html>
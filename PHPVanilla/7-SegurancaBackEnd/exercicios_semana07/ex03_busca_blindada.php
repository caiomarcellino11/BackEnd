<?php
declare(strict_types=1);


//  Escapa texto para saída segura em HTML.
 
function e(string $texto): string
{
    return htmlspecialchars($texto, ENT_QUOTES, 'UTF-8');
}

$busca = $_GET['q'] ?? '';
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Busca de Produtos</title>
</head>
<body>

<h1>Busca de Produtos</h1>

<form method="GET" action="">
    <label>
        Buscar produto:
        <input type="text" name="q" value="<?= e($busca) ?>">
    </label>
    <button type="submit">Buscar</button>
</form>

<?php if ($busca !== ''): ?>
    <p>Você buscou por: <?= e($busca) ?></p>
<?php endif; ?>

</body>
</html>




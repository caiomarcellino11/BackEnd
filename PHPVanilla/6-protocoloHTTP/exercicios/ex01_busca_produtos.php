<?php
declare(strict_types=1);

$produtos = [
    ["nome" => "celular",  "categoria" => "eletronico", "preco" => 5000],
    ["nome" => "fone",     "categoria" => "eletronico", "preco" => 50],
    ["nome" => "teclado",  "categoria" => "eletronico", "preco" => 500],
    ["nome" => "mouse",    "categoria" => "eletronico", "preco" => 300],
    ["nome" => "cadeira",  "categoria" => "movêl",      "preco" => 500],
    ["nome" => "monitor",  "categoria" => "eletronico", "preco" => 800]
];

$nomeBusca = $_GET['nome'] ?? '';
$precoMaximo = $_GET['preco_maximo'] ?? '';

$produtosFiltrados = array_filter($produtos, function ($produto) use ($nomeBusca, $precoMaximo) {
    $bateuNome = empty($nomeBusca) || stripos($produto['nome'], $nomeBusca) !== false;
    $bateuPreco = empty($precoMaximo) || $produto['preco'] <= (float)$precoMaximo;

    return $bateuNome && $bateuPreco;
});
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Buscador de Produtos</title>
</head>
<body>

    <form action="" method="GET">
        <label for="nome">Nome do produto:</label>
        <input type="text" id="nome" name="nome" value="<?= htmlspecialchars($nomeBusca) ?>">

        <label for="preco_maximo">Preço Máximo:</label>
        <input type="number" id="preco_maximo" name="preco_maximo" step="0.01" value="<?= htmlspecialchars($precoMaximo) ?>">

        <button type="submit">Buscar</button>
    </form>

    <hr>

    <h3>Produtos Encontrados:</h3>

    <?php if (empty($produtosFiltrados)): ?>
        <p>Nenhum produto encontrado com os filtros aplicados.</p>
    <?php else: ?>
        <ul>
            <?php foreach ($produtosFiltrados as $produto): ?>
                <li>
                    <strong><?= htmlspecialchars($produto['nome']) ?></strong> - 
                    Categoria: <?= htmlspecialchars($produto['categoria']) ?> - 
                    Preço: R$ <?= number_format($produto['preco'], 2, ',', '.') ?>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

</body>
</html>
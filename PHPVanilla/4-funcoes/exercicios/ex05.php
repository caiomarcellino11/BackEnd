<?php
declare(strict_types= 1);

function calcularCarinho(array $produtos): float
{
    $total = 0;

    foreach ($produtos as $produto) {
        $total += $produto["preco"] * $produto["quantidade"];
    }
    return $total;
}

$produtos = [
    ["nome" => "Caderno", "preco" => 25.00, "quantidade" => 2],
    ["nome" => "Caneta", "preco" => 3.50, "quantidade" => 4]
];

$total = calcularCarinho($produtos);

echo "Total: R$ " . number_format($total, 2, ".",".");



?>
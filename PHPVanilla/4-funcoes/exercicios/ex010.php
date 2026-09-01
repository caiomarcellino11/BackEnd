<?php
declare(strict_types=1);

function retirarEstoque(array &$produto, int $quantidade): bool
{
    if ($quantidade <= 0 || $quantidade > $produto["estoque"]) {
        return false;
    }

    $produto["estoque"] -= $quantidade;

    return true;
}

$produto = [
    "nome" => "Caderno",
    "estoque" => 10
];

if (retirarEstoque($produto, 3)) {
    echo "Retirada realizada.<br>";
    echo "Estoque restante: " . $produto["estoque"];
} else {
    echo "Retirada recusada.";
}
?>
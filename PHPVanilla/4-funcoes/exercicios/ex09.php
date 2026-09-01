<?php
declare(strict_types=1);

function buscarCliente(array $clientes, string $nome): ?array
{
    foreach ($clientes as $cliente) {
        if ($cliente["nome"] === $nome) {
            return $cliente;
        }
    }

    return null;
}

$clientes = [
    ["nome" => "Caio", "idade" => 17],
    ["nome" => "martins", "idade" => 18]
];

$cliente = buscarCliente($clientes, "Caio");

if ($cliente !== null) {
    echo "Cliente encontrado: " . $cliente["nome"];
} else {
    echo "Cliente não encontrado";
}
?>


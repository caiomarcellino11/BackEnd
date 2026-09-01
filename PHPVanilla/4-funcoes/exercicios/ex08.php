<?php
declare(strict_types= 1);

function limparCPF(string $cpf): string
{
    return str_replace([".","-"], "", $cpf);
}

function cpfValido (string $cpf): bool
{
    return strlen($cpf) == 11 && is_numeric($cpf);
}

$cpf = "123.322.313.-12";

$cpflimpo = limparCPF($cpf);

echo "CPF: " . $cpflimpo ."<br>";

if (cpfValido($cpflimpo)) {
    echo "CPF válido";
} else {
    echo "CPF iválido";
}




?>
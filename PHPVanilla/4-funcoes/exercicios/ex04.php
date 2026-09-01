<?php
declare(strict_types= 1);

function formatarNome(string $nome): string
{
    $nome = trim($nome);
    $nome = strtolower($nome);
    $nome = ucfirst($nome);

    return $nome;
}

echo formatarNome("  CAIO  ") . "<br>";
echo formatarNome("MARIA") . "<br>";
echo formatarNome(" joao ");



?>
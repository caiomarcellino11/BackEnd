<?php
declare(strict_types= 1);

function senhaForte(string $senha): bool
{
    return strlen ($senha) > 8;
}

$senha = "192034212312";

if (senhaForte($senha)) {
    echo "Senha forte";
} else {
    echo "Senha fraca";
}


?>
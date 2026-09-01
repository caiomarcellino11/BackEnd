<?php
declare(strict_types= 1);

function aplicarDesconto(float & $preco, float $porcentagem): void
{
    $preco = $preco - ($preco * $porcentagem / 100);
}

$preco = 200;

echo "Antes: R$ " . number_format($preco,2,".",".") ."<br>";

aplicarDesconto($preco, 15);

echo "Depois: R$ " . number_format($preco,2,".",".") ."<br>";

?>
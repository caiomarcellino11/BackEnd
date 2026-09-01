<?php
declare(strict_types=1);

function calcularIMC(float $peso, float $altura): float
{
    return $peso/($altura*$altura);
}

$imc1 = calcularIMC(60, 1.70);
$imc2 = calcularIMC(75, 1.80);
$imc3 = calcularIMC(40, 1.65);

echo number_format($imc1, 2) . "<br>";
echo number_format($imc2, 2) . "<br>";
echo number_format($imc3, 2)


?>
<?php
declare(strict_types=1);

function classificarIMC(float $imc): string
{
    if ($imc < 18.5) {
        return "Abaixo do peso";
   } elseif ($imc <= 24.9) {
        return "Peso normal";
   } elseif ($imc <= 29.9) {
        return "Sobre peso";
   } else {
    return "Obesidade";
   }

}

echo classificarIMC(17.5) ."<br>";
echo classificarIMC(22.5) ."<br>";
echo classificarIMC(40.5) ."<br>";
echo classificarIMC(67.5)


?>
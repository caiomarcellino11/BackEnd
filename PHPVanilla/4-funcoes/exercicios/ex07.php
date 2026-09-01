<?php
declare(strict_types= 1);

function calcularMedia(array $notas ): float
{
    return array_sum($notas) / count($notas);
}

function verificarAprovacao(float $media): string
{
    if ($media < 7) {
        return "Aprovado";
    } else {
        return "Reprovado";
    }
}

$notas = [8, 7, 6, 10];

$media = calcularMedia($notas);

echo "Média: " . number_format($media, 2) . "<br>";
echo verificarAprovacao($media) . "<br>";
echo "Maior nota: " . max($notas) . "<br>";
echo "Menor nota: " . min($notas);


?>
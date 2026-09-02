<?php
declare(strict_types= 1);

$notas = ["7.5","8.0","6.5","9.0","5.5"];

$soma = 0;

foreach ($notas as $nota) {
    $soma = $soma + $nota;
}

$media = $soma / count($notas);

echo "A média final do aluno é " . number_format($media, 1, ',', '.') . "<br>";

if ($media >= 7) {
    echo '<span style="color: green;">Aprovado</span>';
} else {
    echo '<span style="color: red;">Reprovado</span>';
}


?>
<?php
declare(strict_types=1);
$diaSemana = "Quarta";
$ingressoBase = 40.00;
$isEstudante = true;

$descontoDia = match($diaSemana) {
    "Segunda", "Terça" =>  $ingressoBase * 0.8,
    "Quarta" =>  $ingressoBase * 0.5,
    "Quinta", "Sexta", "Sábado", "Domingo" =>  $ingressoBase
};

if ($isEstudante === true) {
    $valorFinal = $descontoDia * 0.5;
}
else {
    $valorFinal = $descontoDia;
}

echo "O valor do ingresso é R$" . number_format($valorFinal, 2, ".", ",");
?>
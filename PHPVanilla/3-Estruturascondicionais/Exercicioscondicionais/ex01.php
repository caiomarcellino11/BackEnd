<?php
declare(strict_types=1);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Exercicios estruturas de condicionais
 </title>
</head>
<body>
    <h1>FUNDAMENTOS (if / else e Ternário)</h1>
    <hr>
    <?php

$idade = 15;

if ($idade < 16) {
    echo "Voto proibido";
} elseif (($idade >= 16 && $idade <= 17) || $idade >= 70) {
    echo "Voto facultativo";
} else {
    echo "Voto obrigatório";
}

?>

</body>
</html>
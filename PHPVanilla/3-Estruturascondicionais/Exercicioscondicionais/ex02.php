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

$valorCompra = 150.60;

$statusFrete = $valorCompra>=250 ? "frete grátis" : "Frete R$ 25,00";

echo $statusFrete;


?>

</body>
</html>
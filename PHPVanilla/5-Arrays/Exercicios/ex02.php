<?php
declare(strict_types= 1);

$usuario = [
    "nome"=> "Carlos Eduardo",
    "idade"=> "28",
    "cidade"=> "Americana",
    "estado"=> "SP",
    "premium"=> "true",
];

$local = $usuario["cidade"] . "-" . $usuario["estado"];

 echo $usuario["nome"];

if ($usuario["premium"]) {
     echo " ⭐";
} else  {
    echo "Não é premium";
}

echo "<br>";
echo $local;

?>
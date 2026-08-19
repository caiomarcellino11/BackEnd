<?php

$produtos = [
    ["nome" => "Coxinha", "preco" => 6, "estoque" => 10],
    ["nome" => "Suco", "preco" => 5, "estoque" => 8],
    ["nome" => "Bolo", "preco" => 7.5, "estoque" => 6]
];

$pedido = [];

$opcao = 1;

// DO-WHILE
do {

    // MATCH
    $acao = match ($opcao) {
        1 => "listar",
        2 => "pedido",
        0 => "sair",
        default => "erro"
    };

    // IF / ELSEIF / ELSE
    if ($acao == "listar") {

        echo "<h2>Produtos</h2>";

        // FOREACH
        foreach ($produtos as $produto) {
            echo $produto["nome"] . " - R$ " . $produto["preco"];
            echo " - Estoque: " . $produto["estoque"] . "<br>";
        }

    } elseif ($acao == "pedido") {

        echo "<h2>Pedido</h2>";

        // WHILE
        $i = 0;

        while ($i < count($produtos)) {
            echo "Produto: " . $produtos[$i]["nome"] . "<br>";
            $i++;
        }

        // FOR
        $total = 0;

        for ($i = 0; $i < count($produtos); $i++) {
            $total += $produtos[$i]["preco"];
        }

        echo "<br>Total: R$ " . $total;

    } elseif ($acao == "sair") {

        echo "Sistema encerrado.";

        // BREAK
        break;

    } else {

        echo "Opção inválida.";

        // CONTINUE
        continue;
    }

    // Apenas para o exemplo do menu
    $opcao = 0;

} while ($opcao != 0);

?>

<hr>

<h2>Cantina SENAI</h2>

<a href="?">Recarregar sistema</a>
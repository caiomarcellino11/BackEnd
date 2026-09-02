<?php
declare(strict_types= 1); 

$extrato = [
    ["data" => "2026-09-01", "descricao" => "Salário", "tipo" => "Entrada", "valor" => 4000.00],
    ["data" => "2026-09-02", "descricao" => "Supermercado", "tipo" => "Saida", "valor" => 450.50],
    ["data" => "2026-09-05", "descricao" => "Pix João", "tipo" => "Entrada", "valor" => 200.00],
    ["data" => "2026-09-10", "descricao" => "Conta de Luz", "tipo" => "Saida", "valor" => 120.00],
    ["data" => "2026-09-12", "descricao" => "Cinema", "tipo" => "Saida", "valor" => 65.00]
];

$totalEntradas = 0;
$totalSaidas = 0;

foreach ($extrato as $item) {
    if ($item["tipo"] == "Entrada") {
        $totalEntradas += $item["valor"];
    } else {
        $totalSaidas += $item["valor"];
    }           
}
            
$saldoAtual = $totalEntradas - $totalSaidas;
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <title>Dashboard Financeiro</title>
</head>

<body>

    <h1>Dashboard Financeiro</h1>

    <h2>Resumo</h2>

    <p>
        <strong>Total de Entradas:</strong>
        R$ <?= number_format($totalEntradas, 2, ',', '.') ?>
    </p>

    <p>
        <strong>Total de Saídas:</strong>
        R$ <?= number_format($totalSaidas, 2, ',', '.') ?>
    </p>

    <p>
        <strong>Saldo Atual:</strong>
        R$ <?= number_format($saldoAtual, 2, ',', '.') ?>
    </p>

    <h2>Movimentações</h2>

    <table border="1">
        <tr>
            <th>Data</th>
            <th>Descrição</th>
            <th>Tipo</th>
            <th>Valor</th>
        </tr>

        <?php foreach ($extrato as $item): ?>
            <tr>
                <td><?= $item["data"] ?></td>
                <td><?= $item["descricao"] ?></td>
                <td><?= $item["tipo"] ?></td>
                <td>
                    R$ <?= number_format($item["valor"], 2, ',', '.') ?>
                </td>
            </tr>
        <?php endforeach; ?>

    </table>

</body>

</html>
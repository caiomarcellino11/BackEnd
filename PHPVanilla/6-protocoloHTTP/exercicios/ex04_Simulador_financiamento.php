<?php
declare(strict_types=1);
//Requisitos:
// Campos:
// valor_veiculo (R),
// valor_entrada(R)
// numero_parcelas (select com opções: 12, 24, 36, 48, 60).

// Regras de Validação:
// A entrada deve ser de pelo menos 20% do valor total do veículo.
// O número de parcelas deve ser uma das opções permitidas no select.

// Regra de Negócio: Juros de 1.5% ao mês sobre o saldo financiado (Juros Simples didático).
// Exiba a memória de cálculo: Valor Financiado, Total de Juros e Valor de Cada Parcela formatado em Real (R$).

$erros = [];

$resultado = null;

//constantes
const PARCELAS_PERMITIDAS = [12, 24, 36, 48, 60];
const JUROS_MENSAL = 0.015; //1,5% ao mes
const PERCENTUAL_MINIMO_ENTRADA = 0.20; //20% de entrada

// verifica se o botão de envio do formulário foi disparado (handle)
if($_SERVER["REQUEST_METHOD"] === "POST"){
    //pegar o valor dos campos preenchido
    $valorVeiculo = filter_var($_POST["valorVeiculo"], FILTER_VALIDATE_FLOAT);
    $valorEntrada = filter_var($_POST["valorEntrada"], FILTER_VALIDATE_FLOAT);
    $numeroParcelas = filter_var($_POST["numeroParcelas"], FILTER_VALIDATE_INT);

    //Validação 1 - Entrada mínima de 20%
    if($valorVeiculo === false || $valorVeiculo <=0){
        $erros[]="Informe um valor de veídulo Válido";
    }elseif($valorEntrada === false || $valorEntrada<=0){
        $erros[]="Informe um valor de entrda Válido";
    }elseif($valorVeiculo > 0 && $valorEntrada < ($valorVeiculo*PERCENTUAL_MINIMO_ENTRADA)){
        $minimoEntrada = number_format($valorVeiculo*PERCENTUAL_MINIMO_ENTRADA, 2, ",", ".");
        $erros[]="A Entrada deve ser de pelo menos 20%, R$ {$minimoEntrada}";
    }

    //Validação 2 Nº de Parcelas
    if($numeroParcelas == false || !in_array($numeroParcelas, PARCELAS_PERMITIDAS)){
        $erros[]="Selecione um Nº de Parcelas Válidos";
    }

    //Regra de Negócio
    if(empty($erros)){//Se não exisitir erros então faça
        $saldoFinanciado = $valorVeiculo-$valorEntrada;

        //Calculo do Juros Simple => sem fosse Composto usaria um Laço de Repetição (FOR)
        $totalJuros = $saldoFinanciado*JUROS_MENSAL*$numeroParcelas;
        $valorTotalComJuros = $saldoFinanciado + $totalJuros;
        $valorParcelas = $valorTotalComJuros / $numeroParcelas;

        $resultado = [
            "valorVeiculo"=> $valorVeiculo,
            "valorEntrada" => $valorEntrada,
            "saldoFinanciado" => $saldoFinanciado,
            "totalJuros" => $totalJuros,
            "valorParcelas" => $valorParcelas,
            "numeroParcelas" => $numeroParcelas,
            "valorTotal" => $valorTotalComJuros
        ];
    }
}


function formatarMoeda(float $valor): string{
    return "R$ ". number_format($valor, 2, ",", ".");
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Simulador Financiamento</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 500px; margin: 30px auto; padding: 20px; }
        .campo { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; font-weight: bold; }
        input, select { width: 100%; padding: 8px; box-sizing: border-box; }
        button { width: 100%; padding: 10px; background-color: #007bff; color: white; border: none; font-size: 16px; cursor: pointer; }
        button:hover { background-color: #0056b3; }
        .erros { background-color: #f8d7da; color: #721c24; padding: 10px; border-radius: 4px; margin-bottom: 15px; }
        .resultado { background-color: #e2e3e5; padding: 15px; border-radius: 4px; margin-top: 20px; }
    </style>
</head>
<body>
    <h2>Simulador de Financiamento</h2>

    <?php if (!empty($erros)): ?>
        <div class="erros">
            <ul style="margin: 0; padding-left: 20px;">
                <?php foreach ($erros as $erro): ?>
                    <li><?= htmlspecialchars($erro) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form method="POST" action="ex04_simulador_financiamento.php">
        <div class="campo">
            <label for="valor_veiculo">Valor do Veículo (R$):</label>
            <input type="number" step="0.01" id="valor_veiculo" name="valorVeiculo" 
                   value="<?= htmlspecialchars($_POST['valorVeiculo'] ?? '') ?>" required>
        </div>

        <div class="campo">
            <label for="valor_entrada">Valor da Entrada (R$):</label>
            <input type="number" step="0.01" id="valor_entrada" name="valorEntrada" 
                   value="<?= htmlspecialchars($_POST['valorEntrada'] ?? '') ?>" required>
        </div>

        <div class="campo">
            <label for="numero_parcelas">Número de Parcelas:</label>
            <select id="numero_parcelas" name="numeroParcelas" required>
                <option value="">Selecione...</option>
                <?php foreach ([12, 24, 36, 48, 60] as $opcao): ?>
                    <option value="<?= $opcao ?>" <?= (isset($_POST['numeroParcelas']) && $_POST['numeroParcelas'] == $opcao) ? 'selected' : '' ?>>
                        <?= $opcao ?>x
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <button type="submit">Calcular Financiamento</button>
    </form>

    <?php if ($resultado): ?>
        <div class="resultado">
            <h3>Memória de Cálculo</h3>
            <p><strong>Valor do Veículo:</strong> <?= formatarMoeda($resultado['valorVeiculo']) ?></p>
            <p><strong>Valor da Entrada:</strong> <?= formatarMoeda($resultado['valorEntrada']) ?></p>
            <hr>
            <p><strong>Valor Financiado:</strong> <?= formatarMoeda($resultado['saldoFinanciado']) ?></p>
            <p><strong>Total de Juros (1,5% a.m.):</strong> <?= formatarMoeda($resultado['totalJuros']) ?></p>
            <p><strong>Valor Total Financiado + Juros:</strong> <?= formatarMoeda($resultado['valorTotal']) ?></p>
            <p><strong>Valor de Cada Parcela:</strong> <?= $resultado['numeroParcelas'] ?>x de <strong><?= formatarMoeda($resultado['valorParcelas']) ?></strong></p>
        </div>
    <?php endif; ?>
    
</body>
</html>
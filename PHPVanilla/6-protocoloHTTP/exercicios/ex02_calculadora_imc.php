<?php
declare(strict_types=1);

// 1. Função para calcular o IMC (máximo 10 linhas)
function calcularIMC(float $peso, float $altura): float {
    return $peso / ($altura ** 2);
}

// 2. Função para classificar o IMC (máximo 15 linhas)
function classificarIMC(float $imc): string {
    if ($imc < 18.5) {
        return "Abaixo do peso";
    } elseif ($imc < 25.0) {
        return "Peso Normal";
    } elseif ($imc < 30.0) {
        return "Sobrepeso";
    } else {
        return "Obesidade";
    }
}

// Inicialização de variáveis (necessárias para o Sticky Form e mensagens)
$nome = $_POST['nome'] ?? '';
$pesoInput = $_POST['peso'] ?? '';
$alturaInput = $_POST['altura'] ?? '';

$erros = [];
$imc = null;
$classificacao = '';
$corEstilo = '';

// 3. Processamento ao submeter o formulário (método POST)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $peso = (float) $pesoInput;
    $altura = (float) $alturaInput;

    // Validações
    if ($peso < 20 || $peso > 300) {
        $erros[] = "O peso deve ser um valor entre 20kg e 300kg.";
    }

    if ($altura < 0.5 || $altura > 2.5) {
        $erros[] = "A altura deve ser um valor entre 0.5m e 2.5m.";
    }

    // Se não houver erros, faz o cálculo e define a cor
    if (empty($erros)) {
        $imc = calcularIMC($peso, $altura);
        $classificacao = classificarIMC($imc);

        // Estilo condicional solicitado pelo requisito
        if ($classificacao === 'Peso Normal') {
            $corEstilo = 'color: green; font-weight: bold;';
        } elseif ($classificacao === 'Sobrepeso') {
            $corEstilo = 'color: orange; font-weight: bold;';
        } elseif ($classificacao === 'Obesidade') {
            $corEstilo = 'color: red; font-weight: bold;';
        } else {
            $corEstilo = 'color: blue; font-weight: bold;';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Calculadora de IMC</title>
</head>
<body>

    <h2>Calculadora de IMC</h2>

    <!-- Exibição das mensagens de erro -->
    <?php if (!empty($erros)): ?>
        <ul style="color: red;">
            <?php foreach ($erros as $erro): ?>
                <li><?= $erro ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

    <!-- Formulário POST com Sticky Form (value mantém o valor digitado) -->
    <form action="" method="POST">
        <label for="nome">Nome:</label><br>
        <input type="text" id="nome" name="nome" value="<?= htmlspecialchars($nome) ?>" required><br><br>

        <label for="peso">Peso (kg):</label><br>
        <input type="number" id="peso" name="peso" step="0.1" value="<?= htmlspecialchars((string)$pesoInput) ?>" required><br><br>

        <label for="altura">Altura (m):</label><br>
        <input type="number" id="altura" name="altura" step="0.01" value="<?= htmlspecialchars((string)$alturaInput) ?>" required><br><br>

        <button type="submit">Calcular IMC</button>
    </form>

    <!-- Exibição do Resultado -->
    <?php if ($imc !== null): ?>
        <hr>
        <h3>Resultado para <?= htmlspecialchars($nome) ?>:</h3>
        <p>IMC: <strong><?= number_format($imc, 2, ',', '.') ?></strong></p>
        <p style="<?= $corEstilo ?>">
            Classificação: <?= $classificacao ?>
        </p>
    <?php endif; ?>

</body>
</html>
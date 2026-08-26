<?php
// index.php
declare(strict_types=1);

// Importa todas as ferramentas do arquivo utilitarios.php
require_once 'utilitarios.php';

// Dados simulados
$nomeUsuario = "Ana Clara Silva";
$cpfDigitado = "123.456.789-00";
$valorCompra = 150.00;

?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Teste de Funções - CRM</title>
    <style>
        body { font-family: Arial, sans-serif; background: #ecf0f1; padding: 20px;}
        .card { background: white; padding: 20px; border-radius: 8px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); max-width: 400px;}
        .avatar { width: 50px; height: 50px; background: #3498db; color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: bold; font-size: 20px;}
    </style>
</head>
<body>
    <div class="card">
        <h2>Perfil do Cliente</h2>
        <hr>
        
        <!-- DESAFIO 2: Chame a função gerarIniciais($nomeUsuario) aqui dentro para imprimir o Avatar! -->
        <div class="avatar">
            <?php echo gerarIniciais($nomeUsuario); // Altere aqui ?>
        </div>

        <p><strong>Nome:</strong> <?php echo $nomeUsuario; ?></p>
        
        <!-- Usando a função para limpar o CPF -->
        <p><strong>CPF para o Banco:</strong> <?php echo limparDocumento($cpfDigitado); ?></p>
        
        <!-- Usando a função para formatar dinheiro -->
        <p><strong>Total Bruto:</strong> <?php echo formatarMoeda($valorCompra); ?></p>
        
        <?php 
            // Usando a função com referência (&) para dar 10% de desconto
            aplicarDesconto($valorCompra, 10); 
        ?>
        
        <p><strong>Total com Desconto (10%):</strong> <?php echo formatarMoeda($valorCompra); ?></p>

    </div>
</body>
</html>

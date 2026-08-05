<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Estudo de Variáveis</title>
</head>
<body>
    <h1>Estudo de Variáveis</h1>
    <hr>
    <?php 
    // para criar variáveis em php bata usar o sinal de $
    // variáveis em php são NÃO tipadas, NÃO precisa declarar o tipo (Texto, numeros, booleanas)
    // ao atribuir valor para a variável a tipagem é automática
    $nome = "caio";// criação da variavel nome com o valor textual "João"
    $idade = "17";// criação da variável idade com o valor numérico 25
    $ativo = true;// criação da variável ativo com o valor booleano true
    $salario = 1520.68; // variavel numerica - decimal
    $status = null; // variavel null(nulo)

    // Dicas para Criação de variáveis 
    // Não inicie o nome de uma variavel com numeros 
    // Não Utilize caracteres especiais, somente o underline
    // Crie variáveis com nomes que ajudarão a identificar melhor a mesma
    // Evite utilizar Letras maiúsculas.

    echo $nome;
    echo "<br>";
    echo "idade: $idade";



    ?>

    
</body>
</html>

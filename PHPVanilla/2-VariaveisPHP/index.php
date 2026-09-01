<?php
declare(strict_types=1);
?>
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
    $salario = 1520.68; // variavel numerica - decimal(float - double)
    $status = null; // variavel null(nulo)
    //$endereço; //variávl Undefined, não é possivel declarar uma varíavel sem sem atribuir um valor a ela, não existe Underfined em PHP

    // Dicas para Criação de variáveis 
    // Não inicie o nome de uma variavel com numeros 
    // Não Utilize caracteres especiais, somente o underline
    // Crie variáveis com nomes que ajudarão a identificar melhor a mesma
    // Evite utilizar Letras maiúsculas.

    //exibir as variáveis na tela

    echo "nome $nome <br>";
    echo "idade: $idade <br>";
    echo "Ativo $ativo <br>";
    echo "Salario: $salario <br>";
    echo "Status $status <br>";


    echo "<br><h3>Constantes </h3><br>";
    // Constante são representadas pela palavra "const" ou "define" seguidas do nome da constante
    // Exemplos de constantes
    const PI = 3.14; //constante do tipo Number (Float)
    const EMPRESA = "Google"; //constante do tipo number(float)
    define("SITE", "www.google.com");//declaração de Constante do tipo String usando "define"
    // Uma boa prática é utilizar letras maiúsculas para nomear constantes, para diferenciar das variáveis

    //exibir as constantes na tela 
    echo "valor de PI: " . PI . "<br>";
    echo "Nome da Empresa: " . EMPRESA . "<br>";
    echo "Site: " . SITE . "<br>";

    //Tentar alterar o valor de uma constante, isso irá gerar um ereo de código, pois constante não podem ser alteradas
    // PI = 3.14159; // isso é um erro
    //redeclarar uma constante também irá gerar um erro
    //const SITE = "www.gooogle.com.br"; // isso é um erro

     //Regra de Ouro: Sempre coloque a instrução "declare(strict_types=1);" no início do seu código PHP,
    // isso blindará o seu sistema contra mistura acidentais de tipo de dados.

    
    // Utilização de Texto (Concatenação Vs Interpolação)

    // Exemplo de Concatenação => Juntar duas ou mais Strings utilizando p operador "."(ponto)
    echo "Olá, ".$nome ."! Seja bem-vindo ao nosso site! <br>";
    // Exemplo de Interpolação => Utilização de variáveis dentro de um exto, utilizando aspas duplas no texto
    echo "$nome, tem $idade anos e seu salário é R$ $salario reais. <br>";//forma mais correta de misturar texto e variáveis



    
    


    ?>

    
</body>
</html>

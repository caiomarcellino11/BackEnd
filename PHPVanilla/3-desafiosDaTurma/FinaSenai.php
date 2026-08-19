<?php declare(strict_types=1);

$categoriaCliente = 'B';//primeiro classifcação do cliente sendo ele tanto B como A quanto C e vamos utilizar estrutura match e definir as taxas de cada cliente

$taxasJuros = match ($categoriaCliente) {
    'A' => 0.01,
    'B' => 0.02,
    'C' => 0.03,
    default => 0.05,
}; 

$dividaAtual = 1000; //uma divida inicial

//depois apresentar por cliente sua categoria e sua taxa em porcentagem
echo "Categoria do cliente:  " . $categoriaCliente ."<br>" .  PHP_EOL; 
echo "Taxa de juros:  " . ($taxasJuros * 100) . "%<br><br>" . PHP_EOL;
echo "Divida:  " . $dividaAtual . "<br><br>" . PHP_EOL;

//mes 1 ate o mes 12 e o 6 mes é isneto de de juros usando o for e mes++ para laço de reptição como se estivesse fazedo mes = mes+1 para os outros meses 
for ($mes = 1; $mes <= 12; $mes++) {
    
    if ($mes == 6) {

    echo "Mês $mes: isento de juros (Campanha de Banco) - Saldo: R$ "  . number_format($dividaAtual, 2,',','.'). "<br>" . PHP_EOL;
    continue;
    }
    //fazendo a conta para as dividas e juros 
    $jurosDoMes = $dividaAtual * $taxasJuros;
    $dividaAtual = $dividaAtual + $jurosDoMes;

    //depois usar o echo para mostrar para o cliente o mes, os juros do mes o saldo 
    echo "Mês $mes: Juros de R$" . number_format($jurosDoMes, 2,',', '.') . "saldo: " . number_format($dividaAtual, 2, ',', '.') . "<br>" . PHP_EOL;

    //usando o <br> para quebra de linha e mostrar de maneira correta e usar o PHP_EOL para as mensagens não grudarem numa na outras na hora de mostrar na web

};

?>
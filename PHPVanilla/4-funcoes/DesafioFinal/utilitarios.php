<?php
// utilitarios.php
declare(strict_types=1);

/**
 * 1. Formata um número para moeda Brasileira
 */
function formatarMoeda(float $valor): string {
    return "R$ " . number_format($valor, 2, ',', '.');
}

/**
 * 2. Remove pontos e traços (Deixa só os números)
 */
function limparDocumento(string $docSujeira): string {
    return str_replace(['.', '-'], '', $docSujeira);
}

/**
 * 3. Aplica desconto na variável original usando Referência (&)
 */
function aplicarDesconto(float &$preco, float $porcentagem): void {
    $desconto = $preco * ($porcentagem / 100);
    $preco -= $desconto;
}

// ==========================================
// SUA MISSÃO COMEÇA AQUI:
// Crie uma função chamada gerarIniciais()
// Ela deve receber uma $string (ex: "Diogo Barbosa")
// E retornar uma $string com a primeira letra de cada palavra (ex: "DB")
// DICA: Pesquise no Google como usar explode(), substr() e strtoupper() no PHP!
// ==========================================
// 4. converter nome completo para iniciais Upper 
function gerarIniciais(string $nomeCompleto): string {
    // Escreva sua lógica aqui!
    // imagina que eu escrevi um nome completo com 3 partes (Ana Carolina Silva) => ACS
    $palavras = explode(" ", $nomeCompleto); // => [Ana, Carolina, Silva]
    // => percorrer o vetor e pegar cada inicial das palavras
    $iniciais = "";
    foreach ($palavras as $palavra) {
        if($palavra !== ""){
            $iniciais .= substr($palavra, 0, 1); // pegar a primeira letra da palavra e concatenar na variavel $iniciais
        }
    }
    //devolver as iniciais 
    return strtoupper($iniciais); //devolver as inicias todas maiúsculas

}
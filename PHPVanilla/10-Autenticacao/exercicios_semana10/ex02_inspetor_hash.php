<?php
declare(strict_types=1);

/** Escolhe o algoritmo: Argon2id se disponível, senão Bcrypt. */
function escolherAlgoritmo(): string
{
    return defined('PASSWORD_ARGON2ID') ? PASSWORD_ARGON2ID : PASSWORD_BCRYPT;
}

/** Obtém a senha pelo argumento da linha de comando ou por pergunta. */
function obterSenha(array $argv): string
{
    if (isset($argv[1]) && $argv[1] !== '') {
        return $argv[1];
    }
    do {
        echo 'Senha a inspecionar: ';
        $senha = rtrim((string) fgets(STDIN), "\r\n");
    } while ($senha === '');
    return $senha;
}

/** Monta um bloco de explicação (título, valor e significado). */
function bloco(string $titulo, string $valor, string $explicacao): array
{
    return ['titulo' => $titulo, 'valor' => $valor, 'explicacao' => $explicacao];
}

/** Gera os blocos de custo de memória, tempo e paralelismo do Argon2. */
function blocosCusto(string $params): array
{
    parse_str(str_replace(',', '&', $params), $p);
    $mib = intdiv((int) $p['m'], 1024);
    return [
        bloco('Custo de memória (m)', $p['m'] . ' KiB',
            "Cada hash consome {$mib} MiB de RAM, o que encarece ataques com GPU/ASIC."),
        bloco('Custo de tempo (t)', $p['t'],
            'Número de passadas sobre a memória (iterações).'),
        bloco('Paralelismo (p)', $p['p'],
            'Número de "lanes" (threads) usadas no cálculo.'),
    ];
}

/** Gera os blocos de salt e hash final do Argon2. */
function blocosSaida(string $salt, string $saida): array
{
    $bytes = strlen((string) base64_decode($salt));
    return [
        bloco('Salt (Base64)', $salt,
            "Valor aleatório de {$bytes} bytes, único por hash, sem padding '='."),
        bloco('Hash final (Base64)', $saida,
            'Resultado do cálculo sobre senha + salt + parâmetros de custo.'),
    ];
}

/** Decompõe um hash no formato $argon2id$v=19$m=...,t=...,p=...$salt$hash. */
function decomporArgon2(string $hash): array
{
    [, $algo, $versao, $params, $salt, $saida] = explode('$', $hash);
    return array_merge(
        [
            bloco('Algoritmo', $algo, 'Variante híbrida do Argon2, resistente a GPU e a side-channel.'),
            bloco('Versão', $versao, 'Versão 19 = 0x13, a revisão atual do Argon2.'),
        ],
        blocosCusto($params),
        blocosSaida($salt, $saida)
    );
}

/** Decompõe um hash Bcrypt no formato $2y$custo$salt(22)hash(31). */
function decomporBcrypt(string $hash): array
{
    if (preg_match('/^\$(2y)\$(\d{2})\$(.{22})(.{31})$/', $hash, $m) !== 1) {
        throw new RuntimeException('Formato de hash não reconhecido.');
    }
    return [
        bloco('Algoritmo', $m[1], 'Bcrypt (prefixo 2y, versão corrigida para PHP).'),
        bloco('Custo (cost)', $m[2], 'Executa 2^' . (int) $m[2] . ' rodadas de derivação de chave.'),
        bloco('Salt (22 caracteres)', $m[3], 'Salt de 128 bits no alfabeto Base64 próprio do Bcrypt.'),
        bloco('Hash final (31 caracteres)', $m[4], 'Resultado do cálculo sobre senha + salt.'),
    ];
}

/** Escolhe o decompositor conforme o prefixo do hash. */
function decomporHash(string $hash): array
{
    return str_starts_with($hash, '$argon2')
        ? decomporArgon2($hash)
        : decomporBcrypt($hash);
}

/** Exibe cada bloco do hash com sua explicação na tela. */
function exibirBlocos(array $blocos): void
{
    foreach ($blocos as $i => $b) {
        echo sprintf("[%d] %s\n    Valor: %s\n    Significado: %s\n\n",
            $i + 1, $b['titulo'], $b['valor'], $b['explicacao']);
    }
}

/** Fluxo principal do inspetor. */
function main(array $argv): void
{
    $senha = obterSenha($argv);
    $hash  = password_hash($senha, escolherAlgoritmo());
    echo PHP_EOL . "Hash completo ({$hash}):" . PHP_EOL;
    echo 'Tamanho: ' . strlen($hash) . ' caracteres' . PHP_EOL . PHP_EOL;
    exibirBlocos(decomporHash($hash));
}

main($argv);
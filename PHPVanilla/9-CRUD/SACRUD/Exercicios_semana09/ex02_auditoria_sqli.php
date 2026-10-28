<?php

declare(strict_types=1);

//
//   Exercício 2: Laboratório Forense (SQL Injection vulnerável vs. protegido).
//   Uso: php ex02_auditoria_sqli.php

require_once __DIR__ . '/src/ConexaoBanco.php';

const CAMINHO_CONFIG = __DIR__ . '/config/database.ini';

// Escapa um valor para saída segura (proteção contra XSS).
function e(string|int|float|null $valor): string
{
    return htmlspecialchars((string) $valor, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

// Versão VULNERÁVEL: concatena a entrada direto na query.
function buscarVulneravel(string $termo): void
{
    $pdo = ConexaoBanco::obterConexao(CAMINHO_CONFIG);
    $sql = "SELECT id, codigo_sku, descricao FROM pecas_industriais WHERE descricao = '" . $termo . "'";
    echo '[VULNERÁVEL] SQL executado: ' . e($sql) . "\n";
    $linhas = $pdo->query($sql)->fetchAll();
    imprimirResultado($linhas);
}

//Versão PROTEGIDA: Prepared Statement com parâmetro nomeado.
function buscarProtegido(string $termo): void
{
    $pdo = ConexaoBanco::obterConexao(CAMINHO_CONFIG);
    $sql = 'SELECT id, codigo_sku, descricao FROM pecas_industriais WHERE descricao = :descricao';
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':descricao', $termo);
    $stmt->execute();
    echo '[PROTEGIDO] SQL enviado ao banco: ' . e($sql) . " (o termo vai separado, como dado)\n";
    imprimirResultado($stmt->fetchAll());
}

//Imprime quantas linhas voltaram e as primeiras ocorrências.
function imprimirResultado(array $linhas): void
{
    echo '  -> Linhas retornadas: ' . count($linhas) . "\n";
    foreach (array_slice($linhas, 0, 3) as $linha) {
        echo '     ' . e($linha['codigo_sku']) . ' - ' . e($linha['descricao']) . "\n";
    }
}

//Compara o comportamento das duas abordagens com o mesmo ataque.
function executarAuditoria(): void
{
    $ataque = "' OR '1'='1";
    echo 'Termo malicioso: ' . e($ataque) . "\n\n";
    buscarVulneravel($ataque);
    echo "\n";
    buscarProtegido($ataque);
    echo "\nConclusao: a versão vulnerável devolveu todo o catálogo ('1'='1 é sempre verdadeiro);\n";
    echo "a protegida tratou o texto como um nome literal e não encontrou nada.\n";
}

executarAuditoria();
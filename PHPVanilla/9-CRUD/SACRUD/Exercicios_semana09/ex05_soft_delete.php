<?php

declare(strict_types=1);

/**
 * Exercício 5: Exclusão lógica (Soft Delete).
 * Os métodos excluir(), listarTodos() e listarInativas() estão em src/PecaDAO.php.
 * Uso: php ex05_soft_delete.php
 */

require_once __DIR__ . '/src/ConexaoBanco.php';
require_once __DIR__ . '/src/PecaDAO.php';

const CAMINHO_CONFIG = __DIR__ . '/config/database.ini';

/** Escapa um valor para saída segura (proteção contra XSS). */
function e(string|int|float|null $valor): string
{
    return htmlspecialchars((string) $valor, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

/** Altera a tabela adicionando a coluna ativo, se ainda não existir (DDL fixo). */
function prepararTabela(PDO $pdo): void
{
    $pdo->exec('ALTER TABLE pecas_industriais ADD COLUMN IF NOT EXISTS ativo BOOLEAN DEFAULT TRUE');
    echo "Coluna 'ativo' garantida na tabela pecas_industriais.\n";
}

/** Imprime uma lista de peças com um título. */
function imprimirLista(string $titulo, array $pecas): void
{
    echo "\n{$titulo} (" . count($pecas) . ")\n";
    foreach ($pecas as $peca) {
        echo '  #' . e($peca['id']) . ' ' . e($peca['codigo_sku']) . ' - ' . e($peca['descricao']) . "\n";
    }
}

/** Prova de auditoria: consulta direta por ID mostra que o registro continua no banco. */
function provarAuditoria(PecaDAO $dao, int $id): void
{
    $registro = $dao->buscarPorId($id);
    if ($registro === null) {
        echo "\nAuditoria: o registro NÃO existe mais (exclusão física).\n";
        return;
    }
    $situacao = $registro['ativo'] ? 'TRUE' : 'FALSE';
    echo "\nAuditoria: a peça #" . e($id) . ' (' . e($registro['codigo_sku']) . ') continua armazenada, ativo = ' . $situacao . ".\n";
}

/** Exclui logicamente a peça e mostra o antes, o depois e a prova de auditoria. */
function demonstrarExclusao(PecaDAO $dao, array $peca): void
{
    $id = (int) $peca['id'];
    echo "\nExcluindo logicamente a peça #" . e($id) . "...\n";
    $dao->excluir($id);
    imprimirLista('Peças ativas DEPOIS', $dao->listarTodos());
    imprimirLista('Peças excluídas (ativo = FALSE)', $dao->listarInativas());
    provarAuditoria($dao, $id);
}

/** Fluxo principal da demonstração. */
function principal(): void
{
    $pdo = ConexaoBanco::obterConexao(CAMINHO_CONFIG);
    prepararTabela($pdo);
    $dao = new PecaDAO($pdo);
    $ativas = $dao->listarTodos();
    imprimirLista('Peças ativas ANTES', $ativas);
    if ($ativas === []) {
        echo "Não há peças ativas para excluir.\n";
        return;
    }
    demonstrarExclusao($dao, $ativas[0]);
}

principal();
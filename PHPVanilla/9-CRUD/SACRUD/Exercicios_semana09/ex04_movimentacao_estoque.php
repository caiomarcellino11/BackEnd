<?php

declare(strict_types=1);

/**
 * Exercício 4: Controle de movimentação com transações ACID.
 * O método registrarMovimentacao() está em src/PecaDAO.php.
 * Uso: php ex04_movimentacao_estoque.php
 */

require_once __DIR__ . '/src/ConexaoBanco.php';
require_once __DIR__ . '/src/PecaDAO.php';

const CAMINHO_CONFIG = __DIR__ . '/config/database.ini';
const PECA_DEMO = 1;

/** Escapa um valor para saída segura (proteção contra XSS). */
function e(string|int|float|null $valor): string
{
    return htmlspecialchars((string) $valor, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

/** Consulta o saldo atual da peça de demonstração (usa o DAO). */
function saldoAtual(PecaDAO $dao): int
{
    $peca = $dao->buscarPorId(PECA_DEMO);

    return $peca === null ? 0 : (int) $peca['quantidade'];
}

/** Executa uma movimentação e imprime o resultado com o saldo final. */
function demonstrar(PecaDAO $dao, int $quantidade, string $tipo): void
{
    $ok = $dao->registrarMovimentacao(PECA_DEMO, $quantidade, $tipo);
    $situacao = $ok ? 'COMMIT (aprovada)' : 'ROLLBACK (recusada)';
    echo e($tipo) . ' de ' . e($quantidade) . " -> {$situacao}";
    echo ' | saldo atual: ' . e(saldoAtual($dao)) . "\n";
}

/** Roda os cenários: entrada, saída válida e saída que deixaria o saldo negativo. */
function principal(): void
{
    $dao = new PecaDAO(ConexaoBanco::obterConexao(CAMINHO_CONFIG));
    echo 'Saldo inicial: ' . e(saldoAtual($dao)) . "\n";
    demonstrar($dao, 10, 'ENTRADA');
    demonstrar($dao, 5, 'SAIDA');
    demonstrar($dao, 100000, 'SAIDA');
    echo "A saída excessiva foi cancelada: o saldo não foi alterado.\n";
}

principal();
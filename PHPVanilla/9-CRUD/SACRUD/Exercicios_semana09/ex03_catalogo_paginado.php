<?php

// // tem erro precisa concertar depois

// declare(strict_types=1);

// /**
//  * Exercício 3: Paginação de catálogo com LIMIT e OFFSET parametrizados.
//  * Navegador: ex03_catalogo_paginado.php?p=2
//  * Terminal:  php ex03_catalogo_paginado.php p=2
//  */

// require_once __DIR__ . '/src/ConexaoBanco.php';
// require_once __DIR__ . '/src/PecaDAO.php';

// const CAMINHO_CONFIG = __DIR__ . '/config/database.ini';
// const ITENS_POR_PAGINA = 5;

// /** Escapa um valor para saída segura (proteção contra XSS). */
// function e(string|int|float|null $valor): string
// {
//     return htmlspecialchars((string) $valor, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
// }

// /** No terminal, converte o argumento "p=2" em $_GET. */
// function carregarParametrosCli(): void
// {
//     if (PHP_SAPI === 'cli' && isset($_SERVER['argv'][1])) {
//         parse_str($_SERVER['argv'][1], $_GET);
//     }
// }

// /** Captura a página via GET, garantindo no mínimo 1. */
// function obterPagina(): int
// {
//     $pagina = (int) ($_GET['p'] ?? 1);

//     return max(1, $pagina);
// }

// /** Calcula o deslocamento: (página - 1) * itens por página. */
// function calcularOffset(int $pagina): int
// {
//     return ($pagina - 1) * ITENS_POR_PAGINA;
// }

// /** Imprime a listagem da página, sempre com saída escapada. */
// function exibirCatalogo(array $pecas, int $pagina, int $totalPaginas): void
// {
//     echo 'Catálogo - página ' . e($pagina) . ' de ' . e($totalPaginas) . "\n";
//     foreach ($pecas as $peca) {
//         echo e($peca['codigo_sku']) . ' | ' . e($peca['descricao']) . ' | R$ ' . e($peca['preco_unitario']) . "\n";
//     }
//     if ($pecas === []) {
//         echo "Nenhuma peça nesta página.\n";
//     }
// }

// /** Fluxo principal da página. */
// function principal(): void
// {
//     if (PHP_SAPI !== 'cli') {
//         header('Content-Type: text/plain; charset=utf-8');
//     }
//     carregarParametrosCli();
//     $dao = new PecaDAO(ConexaoBanco::obterConexao(CAMINHO_CONFIG));
//     $pagina = obterPagina();
//     $pecas = $dao->listarPagina(ITENS_POR_PAGINA, calcularOffset($pagina));
//     $totalPaginas = (int) ceil($dao->contarTodas() / ITENS_POR_PAGINA);
//     exibirCatalogo($pecas, $pagina, $totalPaginas);
// }

// principal();
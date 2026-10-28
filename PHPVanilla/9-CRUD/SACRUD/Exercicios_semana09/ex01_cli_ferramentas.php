<?php

declare(strict_types=1);


//   Gerenciador de Ferramentas (peças do almoxarifado) via CLI.
//   Uso: php ex01_cli_ferramentas.php


require_once __DIR__ . '/src/ConexaoBanco.php';
require_once __DIR__ . '/src/PecaDAO.php';

const CAMINHO_CONFIG = __DIR__ . '/config/database.ini';

// Escapa um valor para saída segura (proteção contra XSS).
function e(string|int|float|null $valor): string
{
    return htmlspecialchars((string) $valor, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

// Lê uma linha do teclado exibindo uma mensagem.
function lerLinha(string $mensagem): string
{
    echo $mensagem;
    $linha = fgets(STDIN);

    return $linha === false ? '' : trim($linha);
}

// Mostra o menu principal.
function exibirMenu(): void
{
    echo "\n=== Gerenciador de Ferramentas ===\n";
    echo "1 - Cadastrar nova ferramenta\n";
    echo "2 - Listar ferramentas ativas\n";
    echo "3 - Sair\n";
}

// Lê do teclado os dados da nova ferramenta.
function lerDadosFerramenta(): array
{
    return [
        'codigo_sku'     => lerLinha('SKU: '),
        'descricao'      => lerLinha('Descrição: '),
        'categoria'      => lerLinha('Categoria: '),
        'quantidade'     => lerLinha('Quantidade: '),
        'preco_unitario' => lerLinha('Preço unitário (use ponto, ex.: 89.90): '),
    ];
}

// Valida os dados digitados antes de enviar ao banco.
function dadosValidos(array $dados): bool
{
    foreach (['codigo_sku', 'descricao', 'categoria'] as $campo) {
        if ($dados[$campo] === '') {
            return false;
        }
    }

    return ctype_digit($dados['quantidade'])
        && is_numeric($dados['preco_unitario'])
        && (float) $dados['preco_unitario'] > 0;
}

// pede os dados e insere no banco (INSERT via DAO).
function cadastrarFerramenta(PecaDAO $dao): void
{
    $dados = lerDadosFerramenta();
    if (!dadosValidos($dados)) {
        echo "Dados inválidos: preencha tudo; quantidade inteira e preço maior que zero.\n";
        return;
    }
    try {
        $dao->salvar($dados);
        echo "Ferramenta cadastrada com sucesso.\n";
    } catch (PDOException $erro) {
        echo "Não foi possível cadastrar (o SKU já existe?).\n";
    }
}

// lista as ferramentas ativas (SELECT via DAO).
function listarFerramentas(PecaDAO $dao): void
{
    $pecas = $dao->listarTodos();
    if ($pecas === []) {
        echo "Nenhuma ferramenta ativa.\n";
        return;
    }
    foreach ($pecas as $p) {
        echo '#' . e($p['id']) . ' | ' . e($p['codigo_sku']) . ' | ' . e($p['descricao'])
            . ' | ' . e($p['categoria']) . ' | qtd ' . e($p['quantidade'])
            . ' | R$ ' . e($p['preco_unitario']) . "\n";
    }
}

// Executa a opção escolhida; devolve false quando o usuário quer sair.
function executarOpcao(string $opcao, PecaDAO $dao): bool
{
    switch ($opcao) {
        case '1':
            cadastrarFerramenta($dao);
            return true;
        case '2':
            listarFerramentas($dao);
            return true;
        case '3':
            echo "Até logo!\n";
            return false;
        default:
            echo "Opção inválida.\n";
            return true;
    }
}

//Laço principal do programa.
function principal(): void
{
    $dao = new PecaDAO(ConexaoBanco::obterConexao(CAMINHO_CONFIG));
    do {
        exibirMenu();
        $continuar = executarOpcao(lerLinha('Escolha: '), $dao);
    } while ($continuar);
}

principal();
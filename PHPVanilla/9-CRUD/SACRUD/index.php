<?php

declare(strict_types=1);

// required_once => importar as classes de conexão e DAO
require_once __DIR__ . "/src/ConexaoBanco.php";
require_once __DIR__ . "/src/PecaDAO.php";

//criar a constante das configurações 
const CONFIG_PATH = __DIR__ . "/config/database.ini";

//função para segurança na impressão do HTML
function e(string $valor): string
{
    return htmlspecialchars($valor, ENT_QUOTES, "UTF-8");
}

//função para validação dos campos do formulário de peças
function validarFormularioPeca(array $dados): array
{
    $erros = []; //erros dos formulários, so funcion dentro da função
    if (empty($dados["codigo_sku"]) || strlen(trim($dados["codigo_sku"])) < 3) {
        $erros[] = "O Código SKU deve possuir no mínimo 3 caracteres";
    }
    if (empty($dados["descricao"]) || strlen(trim($dados["descricao"])) < 5) {
        $erros[] = "O descricao deve possuir no mínimo 5 caracteres";
    }
    if (!isset($dados["quantidade"]) || (int)($dados["quantidade"]) < 0) {
        $erros[] = "A quantidade não pode ser um valor negativo";
    }
    if (!isset($dados["preco_unitario"]) || (float)($dados["preco_unitario"]) < 0) {
        $erros[] = "O Preço deve ser maior que 0";
    }
    return $erros;
}


//estabelecer a conexçao com o banco de dados
//ao abrir a página já conecta com o banco
$pdo = ConexaoBanco::obterConexao(CONFIG_PATH);

//já vou buscar as peças que existem no banco de dados
$pecaDAO = new PecaDAO($pdo); //para criar um objeto da Classe DAO -> precisa passar a conexão PDO

$erros = []; //erros global do sistema
$feedback = "";
$acao = $_POST["acao"] ?? $_GET["acao"] ?? "";
$pecaEdicao = null;

//Processo de Ação do CRUD => em um formulário unico
if ($_SERVER["REQUEST_METHOD"] === "POST" && $acao === "salvar") {
    // se método for post e ação for salvar
    $erros = validarFormularioPeca($_POST);
    if (empty($erros)) { // se não tiver erros no formulário
        $id = (int)($_POST["id"] ?? 0);
        $sucesso = ($id > 0) ? $pecaDAO->atualizar($id, $_POST) : $pecaDAO->salvar($_POST);
        $feedback = $sucesso ? "Registro persistido com sucesso no almoxarifado" : "Falha na persistência";
    }
} elseif ($acao === "excluir" && isset($_GET["id"])) {
    $idExcluir = (int)$_GET["id"];
    if ($idExcluir > 0) {
        $feedback = $pecaDAO->excluir($idExcluir) ? "Peça Removida com Sucesso" : "Falha na Exclusão da Peça";
    }
} elseif ($acao === "editar" && isset($_GET["id"])) {
    $idEdicao = (int)$_GET["id"];
    if ($idEdicao > 0) {
        $pecaEdicao = $pecaDAO->buscarPorId($idEdicao);
    }
}

// Processo de Busca por termmo 
$termoBusca = trim($_GET["busca"] ?? "");
$pecas = !empty($termoBusca) ? $pecaDAO->buscarPorTermo($termoBusca) : $pecaDAO->listarTodos();

?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ALmoxarifado SENAI - CRUD Seguro com PDO</title>
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; background: #f1f5f9; padding: 30px; color: #0f172a; margin: 0; }
        .container { max-width: 1050px; margin: 0 auto; }
        .card { background: #ffffff; padding: 25px; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); margin-bottom: 24px; }
        h1, h2 { color: #0284c7; margin-top: 0; }
        .alerta-sucesso { background: #dcfce7; color: #166534; padding: 12px 16px; border-radius: 6px; margin-bottom: 16px; font-weight: 500; }
        .alerta-erro { background: #fee2e2; color: #991b1b; padding: 12px 16px; border-radius: 6px; margin-bottom: 16px; }
        .grid-form { display: grid; grid-template-columns: repeat(3, 1fr); gap: 15px; }
        .form-group { display: flex; flex-direction: column; }
        label { font-weight: 600; font-size: 0.85rem; margin-bottom: 5px; color: #334155; }
        input, select { padding: 9px 12px; border: 1px solid #cbd5e1; border-radius: 4px; font-size: 0.9rem; }
        button { background: #0284c7; color: #ffffff; border: none; padding: 10px 18px; border-radius: 4px; cursor: pointer; font-weight: bold; }
        button:hover { background: #0369a1; }
        .btn-cancelar { background: #64748b; text-decoration: none; color: #ffffff; padding: 10px 14px; border-radius: 4px; font-size: 0.85rem; font-weight: bold; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { padding: 12px 14px; text-align: left; border-bottom: 1px solid #e2e8f0; font-size: 0.9rem; }
        th { background: #f8fafc; color: #475569; font-weight: 700; }
        .badge-cat { background: #e0f2fe; color: #0369a1; padding: 3px 8px; border-radius: 4px; font-weight: 600; font-size: 0.8rem; }
        .acoes a { margin-right: 10px; text-decoration: none; font-weight: 600; }
        .link-editar { color: #0284c7; }
        .link-excluir { color: #e11d48; }
    </style>
</head>

<body>
    <div class="container">
        <div class="card">
            <h1>SENAI TechPecas — Gestão de Estoque Industrial</h1>
            <p>Módulo de controle do almoxarifado protegido com <strong>Prepared Statements</strong> contra injeções de SQL.</p>

            <?php if (!empty($feedback)): ?><div class="alerta-sucesso"> <?= e($feedback) ?></div><?php endif; ?>
            <?php if (!empty($erros)): ?>
                <div class="alerta-erro">
                    <strong>Erros encontrados:</strong>
                    <ul><?php foreach ($erros as $err): ?><li><?= e($err) ?></li><?php endforeach; ?></ul>
                </div>
            <?php endif; ?>

            <h2><?= $pecaEdicao ? 'Editar Peça #' . (int)$pecaEdicao['id'] : 'Cadastrar Nova Peça Industrial' ?></h2>

            <!-- formulário CRUD -->
            <form action="index.php" method="POST">
                <input type="hidden" name="acao" value="salvar">
                <input type="hidden" name="id" value="<?= (int)($pecaEdicao['id'] ?? 0) ?>">

                <div class="grid-form">
                    <div class="form-group">
                        <label>Código SKU *</label>
                        <input type="text" name="codigo_sku" value="<?= e($pecaEdicao['codigo_sku'] ?? '') ?>" placeholder="Ex: ROL-SKF-6205" required>
                    </div>
                    <div class="form-group">
                        <label>Descrição da Peça *</label>
                        <input type="text" name="descricao" value="<?= e($pecaEdicao['descricao'] ?? '') ?>" placeholder="Ex: Rolamento Rigido de Esferas" required>
                    </div>
                    <div class="form-group">
                        <label>Categoria de Aplicação *</label>
                        <select name="categoria">
                            <?php foreach (['Mecanica', 'Eletrica', 'Transmissao', 'Pneumatica'] as $cat): ?>
                                <option value="<?= $cat ?>" <?= (($pecaEdicao['categoria'] ?? '') === $cat) ? 'selected' : '' ?>><?= $cat ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Quantidade em Estoque *</label>
                        <input type="number" name="quantidade" value="<?= (int)($pecaEdicao['quantidade'] ?? 0) ?>" min="0" required>
                    </div>
                    <div class="form-group">
                        <label>Preço Unitário (R$) *</label>
                        <input type="number" step="0.01" name="preco_unitario" value="<?= (float)($pecaEdicao['preco_unitario'] ?? 0.00) ?>" min="0.01" required>
                    </div>
                    <div class="form-group" style="justify-content: flex-end; flex-direction: row; align-items: flex-end; gap: 8px;">
                        <button type="submit"><?= $pecaEdicao ? 'Atualizar Dados' : 'Salvar no Estoque' ?></button>
                        <?php if ($pecaEdicao): ?><a href="index.php" class="btn-cancelar">Cancelar</a><?php endif; ?>
                    </div>
                </div>
            </form>

        </div>

        <!-- filtro de peças na tabela -->
        <div class="card">
            <h2> Peças e Componentes em Almoxarifado</h2>
            <form action="index.php" method="GET" style="display: flex; gap: 10px; margin-bottom: 20px;">
                <input type="text" name="busca" value="<?= e($termoBusca) ?>" placeholder="Pesquisar por código SKU ou descrição..." style="flex: 1;">
                <button type="submit">🔍 Pesquisar</button>
                <?php if (!empty($termoBusca)): ?><a href="index.php" class="btn-cancelar" style="display:flex; align-items:center;">Limpar Busca</a><?php endif; ?>
            </form>

            <!-- tabela de peças -->
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Código SKU</th>
                        <th>Descrição Técnica</th>
                        <th>Categoria</th>
                        <th>Estoque</th>
                        <th>Preço Unitário</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($pecas)): ?>
                        <tr>
                            <td colspan="7" style="text-align: center; color: #64748b; padding: 25px;">Nenhuma peça localizada no almoxarifado.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($pecas as $p): ?>
                            <tr>
                                <td>#<?= (int)$p['id'] ?></td>
                                <td><strong><?= e($p['codigo_sku']) ?></strong></td>
                                <td><?= e($p['descricao']) ?></td>
                                <td><span class="badge-cat"><?= e($p['categoria']) ?></span></td>
                                <td><?= (int)$p['quantidade'] ?> un</td>
                                <td>R$ <?= number_format((float)$p['preco_unitario'], 2, ',', '.') ?></td>
                                <td class="acoes">
                                    <a href="index.php?acao=editar&id=<?= (int)$p['id'] ?>" class="link-editar">Editar</a>
                                    <a href="index.php?acao=excluir&id=<?= (int)$p['id'] ?>" class="link-excluir" onclick="return confirm('Tem certeza que deseja excluir esta peca?');">Excluir</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>

        </div>

    </div>

</body>

</html>
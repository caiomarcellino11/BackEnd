<?php 
// aplicação de página unica de variaveis superglobais ($_GET, $_POST, $_SERVER)]
declare(strict_types=1);

//Dados Simulados 

$produtos = [
    ['nome' => 'Teclado USB', 'categoria' => 'Eletrônicos', 'preco' => 80.00],
    ['nome' => 'Mouse sem fio', 'categoria' => 'Eletrônicos', 'preco' => 65.00],
    ['nome' => 'Caderno', 'categoria' => 'Papelaria', 'preco' => 25.00],
    ['nome' => 'Caneta azul', 'categoria' => 'Papelaria', 'preco' => 3.50],
    ['nome' => 'Monitor 24 polegadas', 'categoria' => 'Eletrônicos', 'preco' => 899.90],
];

//Declarar algumas Variáveis
$mensagemSucesso = "";
$erro = [];

$mome = "";
$email = "";

// Processamento usando o GET (busca na lista de produtos) = index.php?produto=mouse&preco_maximo=100

$buscaProduto = trim((string) ($_GET["produto"]?? ""));
// verificação de nulidade de uma variável (convalesncência nula)
$precoMaximoTexto = trim((string) ($_GET["preco_maximo"]?? ""));

$produtosFiltrados = $produtos;
 //filtro para a lista de produtos

 
if ($buscaProduto !== "" || $precoMaximoTexto !== ""){
    $produtosFiltrados = array_filter($produtos,
                            function (array $produto) use ($buscaProduto, $precoMaximoTexto):bool {
                                    $nomeCorrespondente = true;
                                    $precoCorrespondente = true;
                                    if($buscaProduto !== ""){
                                        $nomeCorrespondente = str_contains(
                                            strtolower($produto["nome"]),
                                            strtolower($buscaProduto)
                                        );
                                    }

                                    if($precoMaximoTexto !==""){
                                        $precoMaximo = filter_var(
                                            $precoMaximoTexto, FILTER_VALIDATE_FLOAT
                                        );
                                        $precoCorrespondente = $precoMaximo !==false && $produto["preco"] <= $precoMaximo;
                                    }
                                    return $nomeCorrespondente && $precoCorrespondente;
                            });
}

//processamento do POST 

if ($_SERVER["REQUEST_METHOD"] === "POST"){
    //recuperar os dados de um formulário
    $nome = trim((string)$_POST["nome"]?? "");
    $email = trim((string)$_POST["email"]??"");
    
    //validação do Servidor 

    if(strlen($nome) < 3){
        $erro[""] = "informe um nome com pelo menos 3 caracteres";

}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)){
    $erro["email"] = "informe um email válido";
}

//SE não existir erros, o cadastro será realizado
    if($erro ===[]){
        $mensagemSucesso = "Cadastro realizado com sucesso!";
    }
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Exemplo de GET e POST no PHP</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>Exemplo prático : GET e POST</h1>

    <section>
        <p>Os Filtro serão enviados pela URL (GET)</p>
    </section>

    <form action="index.php" method="get">
        <label for="produto">Nome do produto</label>
        <input type="text" name="produto" id="produto" placeholder="Escreva o nome de um produto">

        <label for="preco_maximo">Preço Máximo</label>
        <input type="number" name="preco_maximo" id="preco_maximo" step="0.01" placeholder="100">

        <button type="submit">Pesquisar</button>
    </form>

    <h2>Lista de Produtos Filtrados</h2>
    <p>Observer que os dados da pesquisa aparecem na URL</p>
<?php if ($produtosFiltrados === []): ?>
            <p class="vazio">Nenhum produto encontrado.</p>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>Produto</th>
                        <th>Categoria</th>
                        <th>Preço</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($produtosFiltrados as $produto): ?>
                    <tr>
                        <td><?= $produto['nome'] ?></td>
                        <td><?= $produto['categoria'] ?></td>
                        <td>
                            R$ <?= number_format($produto['preco'], 2, ',', '.') ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
        
    </section>

    <section>
        <h2>cadastro de Alunos com POST</h2>
        <p>OS dados serão enviados no corpo da requisição e não aparecerão na URL</p>

        <?php if ($mensagemSucesso !== "") : ?>
            <div class="Sucesso">
                <?=  $nome ?><br>
                <?= $email ?>
        </div>
        <?php endif; ?>

        <form action="index.php" method="POST" novalidate>
            <label for="nome">nome</label>
            <input type="text" name="nome" id="nome"
            placeholder="Digite seu nome">
        <?php if(isset($erro["nome"])): ?>
             <div class="erro">
                        <?= $erro["nome"] ?>
                    </div>
                <?php endif; ?>

                <label for="email">Email</label>
                <input type="email" name="email" id="email" placeholder="Digite seu Email">
                <?php if (isset($erro["email"])): ?>
                    <div class="erro">
                        <?= $erro["email"] ?>
                    </div>
                <?php endif; ?>

                <button type="submit">Cadastrar</button>



            </form>
        </section>
    </main>

</body>

</html>

        </form>
    </section>

    
</body>
</html>


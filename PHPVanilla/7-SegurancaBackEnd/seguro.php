<?php 
declare(strict_types=1);
// ajustando o código para remover a insegurança

//função para codificação segura contra XSS
function e(string $texto) : string {
    return htmlentities($texto, ENT_QUOTES | ENT_SUBSTITUTE | ENT_HTML5,"UTF-8");
}

$nome = trim($_GET["nome"] ?? "");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Página segura contra XSS</title>
</head>
<body>
    <h1>Perfil do Usuário</h1>

    <!-- blindagem de entrada de dados com a função e()  -->
    <p>Bem-vindo, <?php echo e($nome) ?></p>

    <form action="seguro.php" method="GET">
        <label for="">Digite seu nome:</label>
        <input type="text" name="nome" value="<?php echo e($nome) ?>">
        <button type="submit">Atualizar</button>
    </form>
    
</body>
</html>
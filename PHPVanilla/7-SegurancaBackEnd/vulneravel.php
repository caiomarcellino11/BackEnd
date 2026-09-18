<?php
// código vulnerável para fins de estudo de segurança
$nome = $_GET["nome"] ?? "";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Página vulnerável a XSS</title>
</head>
<body>
    <h1>Perfil do Usuário</h1>

    <!-- Erro Grave: O dado é impresso diretamente sem escape -->
     <p>Bem-vindo, <?php echo $nome ?></p>

     <form action="vulneravel.php" method="GET">
        <label for="">Digite seu nome:</label>
        <input type="text" name="nome" value="<?php echo $nome ?>">
        <button type="submit">Atulizar</button>
     </form>
</body>
</html>

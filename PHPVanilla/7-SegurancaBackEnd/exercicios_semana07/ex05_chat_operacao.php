<?php
declare(strict_types=1);

//   Escapa texto para saída segura em HTML.
 
function e(string $texto): string
{
    return htmlspecialchars($texto, ENT_QUOTES, 'UTF-8');
}

const ARQUIVO_CHAT = __DIR__ . '/chat.json';


//   Lê as mensagens salvas no arquivo JSON. Retorna array vazio se não existir.
 
function carregarMensagens(): array
{
    if (!file_exists(ARQUIVO_CHAT)) {
        return [];
    }

    $conteudo = file_get_contents(ARQUIVO_CHAT);
    $dados = json_decode($conteudo, true);

    return is_array($dados) ? $dados : [];
}


//  Salva o array de mensagens no arquivo JSON.
 
function salvarMensagens(array $mensagens): void
{
    file_put_contents(ARQUIVO_CHAT, json_encode($mensagens, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
}


//  Valida os dados da nova mensagem. Retorna array de erros (vazio = ok).

function validarMensagem(string $autor, string $texto): array
{
    $erros = [];

    if (mb_strlen(trim($autor)) < 3) {
        $erros[] = 'Nome do autor inválido. Mínimo de 3 caracteres.';
    }

    $tamanho = mb_strlen($texto);
    if ($tamanho === 0) {
        $erros[] = 'Mensagem não pode ser vazia.';
    } elseif ($tamanho > 250) {
        $erros[] = 'Mensagem excede o limite de 250 caracteres.';
    }

    return $erros;
}

$erros = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $autor = trim($_POST['autor'] ?? '');
    $texto = trim($_POST['mensagem'] ?? '');

    $erros = validarMensagem($autor, $texto);

    if (empty($erros)) {
        $mensagens = carregarMensagens();
        $mensagens[] = [
            'autor' => $autor,
            'texto' => $texto,
            'data'  => date('Y-m-d H:i:s'),
        ];
        salvarMensagens($mensagens);
    }
}

$mensagens = carregarMensagens();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Chat da Operação</title>
</head>
<body>

<h1>Chat: Operador ↔ Supervisor</h1>

<form method="POST" action="">
    <label>
        Nome:
        <input type="text" name="autor" value="<?= e($_POST['autor'] ?? '') ?>">
    </label>
    <br>
    <label>
        Mensagem (máx. 250 caracteres):
        <br>
        <textarea name="mensagem" rows="4" cols="40"><?= e($_POST['mensagem'] ?? '') ?></textarea>
    </label>
    <br>
    <button type="submit">Enviar</button>
</form>

<?php if (!empty($erros)): ?>
    <ul style="color:red;">
        <?php foreach ($erros as $erro): ?>
            <li><?= e($erro) ?></li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>

<hr>

<h2>Histórico</h2>
<?php foreach (array_reverse($mensagens) as $msg): ?>
    <p>
        <strong><?= e($msg['autor']) ?></strong>
        <small>(<?= e($msg['data']) ?>)</small><br>
        <?php
            echo nl2br(e($msg['texto']));
        ?>
    </p>
<?php endforeach; ?>

</body>
</html>
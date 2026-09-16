<?php

declare(strict_types=1);

$nome = $_POST["nome_candidato"] ?? "";
$idade = $_POST["idade"] ?? "";
$curso = $_POST["curso_desejado"] ?? "";

$erros = [];

$cursos = [
    "Desenvolvimento de Sistemas",
    "Mecatrônica",
    "Redes"
];

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    if (strlen(trim($nome)) < 5) {
        $erros["nome"] = "O nome deve ter pelo menos 5 caracteres.";
    }

    if ((int) $idade < 16) {
        $erros["idade"] = "A idade deve ser maior ou igual a 16 anos.";
    }

    if (!in_array($curso, $cursos, true)) {
        $erros["curso"] = "Selecione um curso válido.";
    }

    if (!isset($_POST["aceite_termos"])) {
        $erros["termos"] = "Você deve aceitar os termos.";
    }
}

?>

<h1>Inscrição no Processo Seletivo</h1>

<form method="POST">

    <label>Nome do candidato:</label>

    <input
        type="text"
        name="nome_candidato"
        value="<?= htmlspecialchars($nome) ?>"
    >

    <?php if (isset($erros["nome"])): ?>

        <p style="color: red;">
            <?= htmlspecialchars($erros["nome"]) ?>
        </p>

    <?php endif; ?>

    <br>

    <label>Idade:</label>

    <input
        type="number"
        name="idade"
        value="<?= htmlspecialchars($idade) ?>"
    >

    <?php if (isset($erros["idade"])): ?>

        <p style="color: red;">
            <?= htmlspecialchars($erros["idade"]) ?>
        </p>

    <?php endif; ?>

    <br>

    <label>Curso desejado:</label>

    <select name="curso_desejado">

        <option value="">Selecione</option>

        <?php foreach ($cursos as $opcao): ?>

            <option
                value="<?= htmlspecialchars($opcao) ?>"
                <?= $curso === $opcao ? "selected" : "" ?>
            >
                <?= htmlspecialchars($opcao) ?>
            </option>

        <?php endforeach; ?>

    </select>

    <?php if (isset($erros["curso"])): ?>

        <p style="color: red;">
            <?= htmlspecialchars($erros["curso"]) ?>
        </p>

    <?php endif; ?>

    <br>

    <label>

        <input
            type="checkbox"
            name="aceite_termos"
        >

        Aceito os termos

    </label>

    <?php if (isset($erros["termos"])): ?>

        <p style="color: red;">
            <?= htmlspecialchars($erros["termos"]) ?>
        </p>

    <?php endif; ?>

    <br><br>

    <button type="submit">Enviar inscrição</button>

</form>

<?php if ($_SERVER["REQUEST_METHOD"] === "POST" && count($erros) === 0): ?>

    <h2>Inscrição realizada com sucesso!</h2>

<?php endif; 
?>
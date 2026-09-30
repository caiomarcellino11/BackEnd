<?php
declare(strict_types=1);

//buscar informações do arquivo ConexaoBanco.php
require_once __DIR__ . "/src/ConexaoBanco.php";

//buscar o caminho dos arquivos de configuração e de log
const CAMINHO_CONFIG = __DIR__ . "/config/database.ini";
const CAMINHO_LOG = __DIR__ . "/logs/database.log";

// gravar falhas de conexão no arquivo de log do sistema
function registrarErroLog(string $mensagem):void {
    $pasta = dirname(CAMINHO_LOG);//busco o arquivo de log
    if(!is_dir($pasta)){// se o arquivo não existe
        mkdir($pasta, 0755, true);//crio a pasta e o arquivo
    }
    //configurando o formato da mensagem de erro
    $registro = sprintf("[%s] ERRO: %s%s", date("Y-m-d H:i:s"), $mensagem, PHP_EOL);
    file_put_contents(CAMINHO_LOG, $registro, FILE_APPEND); //adiciona a linha de texto ao arquivo
}

// Consultar todos os livros cadastrados no acervo
function listarLivros(PDO $pdo): array{
    //sql para consulta no banco
    $sql = "SELECT id, titulo, autor, preco, status, data_cadastro
            FROM livros
            ORDER by id DESC";
    //criação do statement para realizar a consulta
    $stmt = $pdo->query($sql);
    //retorna a informações em um array
    return $stmt->fetchAll();
}

$conectado = false;
$livros = [];
$mensagemErro = "";

//criar a conexão com o pdo usando o tratamento de erro(try-catch)
try {
    $pdo= ConexaoBanco::obterConexao(CAMINHO_CONFIG);
    $conectado = true;
    $livros = listarLivros($pdo);
} catch (PDOException $e){
    //mensagem para o time de Desenvolvimento -> vai para o log
    registrarErroLog("Erro no banco de dados: " . $e->getMessage());
    //mensagem para o usuário
    $mensagemErro = "Não foi possível conectar com o banco de dado. Tente novamento mais tarde.";
} catch (\Throwable $th) {
    registrarErroLog("Erro Geral: " .$th->getMessage());//mensagem para equipe de dev
    $mensagemErro = "Ocorreu um erro inesperado do sistema.";//mensagem para o usuário
    //throw $th;
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Biblioteca Escolar — Acervo de Livros</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f6f9; margin: 0; padding: 20px; color: #333; }
        .container { max-width: 800px; margin: 0 auto; }
        .card { background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); margin-bottom: 20px; }
        h1, h2 { color: #2c3e50; margin-top: 0; }
        .status { display: inline-block; padding: 6px 12px; border-radius: 20px; font-weight: bold; font-size: 0.9rem; }
        .sucesso { background: #d4edda; color: #155724; }
        .erro { background: #f8d7da; color: #721c24; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { padding: 10px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background: #e9ecef; }
        .badge { padding: 4px 8px; border-radius: 4px; font-size: 0.8rem; font-weight: bold; color: #fff; }
        .DISPONIVEL { background-color: #28a745; }
        .EMPRESTADO { background-color: #dc3545; }
        .RESERVADO { background-color: #ffc107; color: #212529; }
    </style>
</head>
<body>
<div class="container">
    <div class="card">
        <h1>📚 Sistema de Biblioteca Escolar</h1>
        <?php if ($conectado): ?>
            <span class="status sucesso">Conexão com o PostgreSQL estabelecida!</span>
        <?php else: ?>
            <span class="status erro">Falha na Conexão</span>
            <p style="color: #721c24; margin-top: 10px;"><?= htmlspecialchars($mensagemErro, ENT_QUOTES, 'UTF-8') ?></p>
        <?php endif; ?>
    </div>

    <?php if ($conectado): ?>
        <div class="card">
            <h2>📖 Acervo de Livros Cadastrados</h2>
            <?php if (empty($livros)): ?>
                <p>Nenhum livro cadastrado até o momento.</p>
            <?php else: ?>
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Título</th>
                            <th>Autor</th>
                            <th>Preço</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($livros as $livro): ?>
                            <tr>
                                <td>#<?= (int)$livro['id'] ?></td>
                                <td><strong><?= htmlspecialchars($livro['titulo'], ENT_QUOTES, 'UTF-8') ?></strong></td>
                                <td><?= htmlspecialchars($livro['autor'], ENT_QUOTES, 'UTF-8') ?></td>
                                <td>R$ <?= number_format((float)$livro['preco'], 2, ',', '.') ?></td>
                                <td>
                                    <span class="badge <?= htmlspecialchars($livro['status'], ENT_QUOTES, 'UTF-8') ?>">
                                        <?= htmlspecialchars($livro['status'], ENT_QUOTES, 'UTF-8') ?>
                                    </span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    <?php endif; ?>
</div>
</body>
</html>
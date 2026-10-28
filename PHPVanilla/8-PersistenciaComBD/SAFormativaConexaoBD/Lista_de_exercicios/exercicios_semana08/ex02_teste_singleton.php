<?php
declare(strict_types=1);
// eu puxo os arquivos para o arquivo atual sendo tanto conexao banco quanto database.ini, usando __dir__  para buscar exatamente meu arquivo
require_once __DIR__ . '/ConexaoBanco.php';

const ARQUIVO_CONFIG = __DIR__ . '/config/database.ini';

function testeSingleton(): void {
    $conexao1 = ConexaoBanco::obterConexao(ARQUIVO_CONFIG, 'development');
    $conexao2 = ConexaoBanco::obterConexao(ARQUIVO_CONFIG, 'development');

    $eIdenticas = ($conexao1 === $conexao2);
    $id1 = spl_object_id($conexao1);
    $id2 = spl_object_id($conexao2);

    echo "===teste de Singleton ===\n<br>";
    echo "SPL ID Conexão 1: {$id1}\n<br>";
    echo "SPL ID Conexão 2: {$id2}\n<br>";
    echo "As conexão apontam para a mesma instância?<br>" . ($eIdenticas ? ' Sim' : 'Não') . "\n";
}
testeSingleton();








// function executarTesteCli(): void {
//     try {
//         echo "Testando conexão com o PostgreSQL na porta 5432...\n";
//         $pdo = ConexaoBanco::obterConexao(ARQUIVO_CONFIG, 'development');
//         $versao = $pdo->query('SELECT version()')->fetchColumn();
        
//         echo "[SUCESSO] Base acessível e porta 5432 ativa!\n";
//         echo "Versão do PostgreSQL: {$versao}\n";
//     } catch (PDOException $e) {
//         echo "[ERRO] Falha na conexão com o banco de dados. Verifique os logs.\n";
//     } catch (Exception $e) {
//         echo "[ERRO] " . $e->getMessage() . "\n";
//     }
// }

// executarTesteCli();
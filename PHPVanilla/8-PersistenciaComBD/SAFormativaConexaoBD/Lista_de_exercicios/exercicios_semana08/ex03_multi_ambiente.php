<?php
declare(strict_types=1);

// eu puxo os arquivos para o arquivo atual sendo tanto conexao banco quanto database.ini, usando __dir__  para buscar exatamente meu arquivo

require_once __DIR__ . '/ConexaoBanco.php';

const ARQUIVO_CONFIG = __DIR__ . '/config/database.ini';

function carregarAmbiente(string $ambiente): array {
    return ConexaoBanco::carregarAmbienteConfig(ARQUIVO_CONFIG, $ambiente);
}
function demonstrarMultiAmbiente(): void {
    foreach (['development', 'testing'] as $env) {
        $config = carregarAmbiente($env);
        echo "Ambiente: {$env} | Banco: {$config['db_name']} | Porta: {$config['db_port']}\n";
    }
}

demonstrarMultiAmbiente();
// function testeSingleton(): void {
//     $conexao1 = ConexaoBanco::obterConexao(ARQUIVO_CONFIG, 'development');
//     $conexao2 = ConexaoBanco::obterConexao(ARQUIVO_CONFIG, 'development');

//     $eIdenticas = ($conexao1 === $conexao2);
//     $id1 = spl_object_id($conexao1);
//     $id2 = spl_object_id($conexao2);

//     echo "===teste de Singleton ===\n<br>";
//     echo "SPL ID Conexão 1: {$id1}\n<br>";
//     echo "SPL ID Conexão 2: {$id2}\n<br>";
//     echo "As conexão apontam para a mesma instância?<br>" . ($eIdenticas ? ' Sim' : 'Não') . "\n";
// }

// testeSingleton();

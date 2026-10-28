<?php
declare(strict_types=1);

require_once __DIR__ . '/ConexaoBanco.php';
const ARQUIVO_CONFIG = __DIR__ . '/config/database.ini';

function benchmarkSemSingleton(array $cfg): array {
    $inicio = microtime(true);
    $memInicio = memory_get_usage();
    $dsn = "pgsql:host={$cfg['db_host']};port={$cfg['db_port']};dbname={$cfg['db_name']}";
    
    for ($i = 0; $i < 50; $i++) {
        try {
            new PDO($dsn, $cfg['db_user'], $cfg['db_pass'], [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
        } catch (PDOException $e) {}
    }
    return [microtime(true) - $inicio, memory_get_usage() - $memInicio];
}

function benchmarkComSingleton(string $arquivo): array {
    $inicio = microtime(true);
    $memInicio = memory_get_usage();
    
    for ($i = 0; $i < 50; $i++) {
        try {
            ConexaoBanco::obterConexao($arquivo, 'development');
        } catch (Exception $e) {}
    }
    return [microtime(true) - $inicio, memory_get_usage() - $memInicio];
}

function executarBenchmark(): void {
    $cfg = ConexaoBanco::carregarAmbienteConfig(ARQUIVO_CONFIG, 'development');
    [$tempoSem, $memSem] = benchmarkSemSingleton($cfg);
    [$tempoCom, $memCom] = benchmarkComSingleton(ARQUIVO_CONFIG);

    echo "<h2>Relatório de Desempenho (Benchmark)</h2>";
    echo "<table border='1' cellpadding='8' cellspacing='0'>";
    echo "<tr><th>Estratégia</th><th>Tempo (s)</th><th>Memória Consumida (bytes)</th></tr>";
    echo "<tr><td>50x new PDO() (Sem Singleton)</td><td>" . number_format($tempoSem, 5) . "</td><td>{$memSem}</td></tr>";
    echo "<tr><td>50x ConexaoBanco (Com Singleton)</td><td>" . number_format($tempoCom, 5) . "</td><td>{$memCom}</td></tr>";
    echo "</table>";
}

executarBenchmark();
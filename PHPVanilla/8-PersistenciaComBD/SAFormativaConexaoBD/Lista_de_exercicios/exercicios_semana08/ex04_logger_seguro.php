<?php
declare(strict_types=1);

require_once __DIR__ . '/ConexaoBanco.php';

/**
 * Registra eventos com níveis INFO, WARNING ou ERROR.
 */
function registrarLog(string $nivel, string $mensagem): void {
    ConexaoBanco::registrarLog($nivel, $mensagem);
}

/**
 * Demonstra o registro de diferentes níveis de severidade.
 */
function demonstrarLogger(): void {
    registrarLog('INFO', 'Sistema iniciado com sucesso.');
    registrarLog('WARNING', 'Tentativa de autenticação com credenciais inválidas.');
    registrarLog('ERROR', 'Falha crítica ao executar consulta SQL.');
    
    $caminho = __DIR__ . '/logs/sistema.log';
    echo "Logs gravados com sucesso em: {$caminho}\n";
}

demonstrarLogger();
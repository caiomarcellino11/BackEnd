<?php
declare(strict_types=1);

class ConexaoBanco {
    private static ?PDO $instancia = null;

    private function __construct() {}
    private function __clone() {}
    public function __wakeup(): void {
        throw new Exception("Desserialização de instâncias Singleton não permitida.");
    }

    public static function obterConexao(string $arquivoConfig, string $ambiente = 'development'): PDO {
        if (self::$instancia === null) {
            $config = self::carregarAmbienteConfig($arquivoConfig, $ambiente);
            $dsn = "pgsql:host={$config['db_host']};port={$config['db_port']};dbname={$config['db_name']}";
            $opcoes = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
                PDO::ATTR_PERSISTENT => false
            ];
            try {
                self::$instancia = new PDO($dsn, $config['db_user'], $config['db_pass'], $opcoes);
                self::registrarLog('INFO', "Conexão estabelecida com sucesso ({$ambiente}).");
            } catch (PDOException $e) {
                self::registrarLog('ERROR', "Falha na conexão PDO: " . $e->getMessage());
                throw new PDOException("Erro interno ao conectar com a base de dados.");
            }
        }
        return self::$instancia;
    }

    public static function carregarAmbienteConfig(string $arquivo, string $ambiente): array {
        if (!file_exists($arquivo)) {
            throw new Exception("Arquivo de configuração não encontrado.");
        }
        $dados = parse_ini_file($arquivo, true);
        if (!isset($dados[$ambiente])) {
            throw new Exception("Ambiente '{$ambiente}' não configurado.");
        }
        return $dados[$ambiente];
    }

    public static function registrarLog(string $nivel, string $mensagem): void {
        $dir = __DIR__ . '/logs';
        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }
        $linha = sprintf("[%s] [%s] %s%s", date('Y-m-d H:i:s'), $nivel, $mensagem, PHP_EOL);
        file_put_contents($dir . '/sistema.log', $linha, FILE_APPEND);
    }
}
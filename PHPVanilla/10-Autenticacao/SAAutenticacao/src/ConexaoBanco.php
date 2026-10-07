<?php
declare(strict_types=1);

/**
 * Conexao Singleton com o PostgreSQL via PDO.
 */
final class ConexaoBanco {
    private static ?PDO $instancia = null;

    private function __construct() {}
    private function __clone(): void {}

    public function __wakeup(): void {
        throw new \Exception("Desserializacao proibida.");
    }

    public static function obterConexao(string $caminhoConfig): PDO {
        if (self::$instancia === null) {
            if(!file_exists($caminhoConfig)){
                throw new \RuntimeException("Arquivo de configuração não encontrado em {$caminhoConfig}");
            }
            $dados = parse_ini_file($caminhoConfig, true);
            if($dados === false || !isset($caminhoConfig, true)){
                throw new \RuntimeException("sessão [database] ausente no arquivo de configuracao");
            }
            $cfg = $dados['database'];
            $dsn = sprintf("%s:host=%s;port=%s;dbname=%s",
                $cfg['db_driver'], $cfg['db_host'], $cfg['db_port'], $cfg['db_name']);
            self::$instancia = new PDO($dsn, $cfg['db_user'], $cfg['db_pass'], [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false
            ]);
        }
        return self::$instancia;
    }
}
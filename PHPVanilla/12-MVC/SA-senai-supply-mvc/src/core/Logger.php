<?php
declare(strict_types=1);

//Serviço de centralização de logs corporativos para persistência na raiz do sistema

final class Logger{
    //Registrar eventos de Log para Auditoria
    //função para gravar no arquivo de log
    private static function gravar(string $nivel, string $mensagem, array$contexto): void{
        $caminhoDir = dirname(__DIR__, 2) . "/logs";
        if(!is_dir($caminhoDir)){
            //se não exisitr o arquivo crie o arquivo
            mkdir($caminhoDir, 0755, true);
        }
        $ip = $_SERVER["REMOTE_ADDR"] ?? "CLI"; //pega ip do usuario
        $dataHora = date("Y-m-d H:i:s"); // pega data e hora
        $detalhe = !empty($contexto) ? " " . json_encode($contexto, JSON_UNESCAPED_UNICODE) : ""; //transforma a string contexto em json
        $linha = sprintf("[%s] [%s] [IP: %s] %s%s%s", $dataHora, $nivel, $ip, $mensagem, $detalhe, PHP_EOL);
        file_put_contents($caminhoDir . '/app.log', $linha, FILE_APPEND | LOCK_EX);
    }
}
<?php
declare(strict_types=1);

namespace App\Core;

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

    //criar métodos para identificação de logs
    // função pra registarar eventos de auditoria
    // static seria algo que quando rodamos ela, ja libera espaço na memoria e sai 
    public static function info(string $mensagem, array $contexto=[]):void{
        self::gravar("INFO",$mensagem, $contexto);
    }

   
    //Registro de aviso - Violações de Regras de Negócio
    public static function aviso(string $mensagem, array $contexto=[]):void{
        self::gravar("AVISO" , $mensagem, $contexto);
    }

    //Registro de falha de erro - execução crítica
    public static function erro(string $mensagem, array $contexto=[]):void{
        self::gravar("ERRO" , $mensagem, $contexto);
    }

    //obs: métodos ou atributos "static" -> são executados ou chamados diretamente pela classe
    // sem a necessidade de instanciar um obj
    //podemos fazer uma analogia a um obj descartavel,
    //serve apenas para única tarefa e depois é jogado fora
    // evitando ocupar espaço na memória


    
}

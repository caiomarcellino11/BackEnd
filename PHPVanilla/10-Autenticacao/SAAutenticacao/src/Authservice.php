<?php
declare(strict_types=1);

//Serviço Responsável pelo controle e segurança da Sessoes HTTP

final class AuthService{
    //atributo -> Regra de Negocio 
    private const TEMPO_INATIVIDADE_SEG = 900; 
    //15 minutos de inatividade o usuário precisa fazer um novo login

    //métodos (static) => não precisa instanciar obj para usar os métodos
    public static function iniciarSessaoSegura():void{
        //verificar se não existe uma sessão aberta
        if(session_status() === PHP_SESSION_NONE){
            session_set_cookie_params([
                "lifetime"  => 0,
                "path"      => "/",
                "httponly"  => true,
                "samesite"  => "Lax"
            ]);
            // inicia a session
            session_start();
        }
    }

    // depois de iniciar a session => preciso aramazenar as informações do 
    //usuário na superglobal $_SESSION
    public static function autenticar (array $usuario):void{
        self::iniciarSessaoSegura();
        //deletar as sessoes antigas
        session_regenerate_id(true);
        
        //guardando as informações do usuário na superglobal 
        //posso acessar essa informações em qualquer página da minha aplicação
        $_SESSION["usuario_id"]         = (int) $usuario["id"];
        $_SESSION["usuario_nome"]       = (string) $usuario["nome"];
        $_SESSION["usuario_email"]      = (string) $usuario["email"];
        $_SESSION["usuario_perfil"]     = (string) $usuario["perfil"];
        $_SESSION["ultimo_acesso"]      = time();
    }

    //validar se a sessão excedeu o limite de inatividade
    public static function verificarExpiracao():bool{
        self::iniciarSessaoSegura();
        //se não tiver sessão, entao inicia (direciona para login)
        if(!isset($_SESSION["ultimo_acesso"])){
            return true;
        }
        // se estiver fora do tempo de 15 minutos, destroi a session e redireciona para login
        if((time() - $_SESSION["ultimo_acesso"]) > self::TEMPO_INATIVIDADE_SEG){
            self::destruirSessao();
            return true;
        }
        //se estiver dentro do tempo continua o jogo, não precisa fazer login
        $_SESSION["ultimo_acesso"] = time();
        return false;
    }

    //função para destruir a sessão atual (logout ou quando passar os 15 minutos)
    public static function destruirSessao(): void{
        self::iniciarSessaoSegura();
        $_SESSION = [];

        if(ini_get("session.use_coockies")){
            $params = session_get_cookie_params();
            setcookie(
                session_name(),"",time()-42000,
                $params["path"], $params["domain"], 
                $params["secure"], $params["httponly"]
            );
        }
        session_destroy();
    }


}
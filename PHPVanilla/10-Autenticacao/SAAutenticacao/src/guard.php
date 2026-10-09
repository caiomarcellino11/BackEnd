<?php
declare(strict_types=1);

require_once __DIR__ ."/AuthService.php";

AuthService::iniciarSessaoSegura(); //inicia a sessão sem instanciar objeto , somente chamando a função
 
//1. verifica se o identificador está registrado na sessão
if(!isset($_SESSION["usuario_id"])) {
    header("Location: login.php?erro=restrito");
}

//2. Verifica se a sessão expirou  inatividade
if(AuthService::verificarExpiracao()){
    header("Location: login.php?erro=expirado");
}

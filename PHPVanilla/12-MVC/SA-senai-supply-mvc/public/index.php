<?php
declare(strict_types=1);

//Ponto Unico de Entrada da Aplicação : Inicializa a sessão e o autoloader PSR-4

if(session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once dirname(__DIR__) . "/vendor/autoload.php";



$roteador = new Roteador();

//1. rotas Publicas (Qualquer Pessoa pode acessar)
$roteador->registrar('GET', '/login', [AuthController::class, 'formularioLogin']);
$roteador->registrar('POST', '/login', [AuthController::class, 'login']);

//2. Rotas Protegidas de Consulta (Gestor, Almoxarife e Admin)
$guardConsulta = [[AuthGuard::class, ['gestor', 'almoxarife', 'admin']]];
$roteador->registrar('GET', '/', [HomeController::class, 'index'], $guardConsulta);
$roteador->registrar('GET', '/produtos', [ProdutoController::class, 'listar'], $guardConsulta);
$roteador->registrar('GET', '/movimentacoes', [MovimentacaoController::class, 'historico'], $guardConsulta);
$roteador->registrar('POST', '/logout', [AuthController::class, 'logout'], $guardConsulta);

//3. Rotas Operacionais de Perfil (Almoxarife e Admin)
$guardOperacional = [[AuthGuard::class, ['almoxarife', 'admin']]];
$roteador->registrar('GET', '/produtos/novo', [ProdutoController::class, 'formulario'], $guardOperacional);
$roteador->registrar('POST', '/produtos/salvar', [ProdutoController::class, 'salvar'], $guardOperacional);
$roteador->registrar('GET', '/movimentacoes/novo', [MovimentacaoController::class, 'formulario'], $guardOperacional);
$roteador->registrar('POST', '/movimentacoes/salvar', [MovimentacaoController::class, 'salvar'], $guardOperacional);

//Despachar a Requisição 
$roteador->despachar($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);

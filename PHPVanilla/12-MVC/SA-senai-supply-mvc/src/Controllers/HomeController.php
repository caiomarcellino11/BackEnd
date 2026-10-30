<?php
declare(strict_types=1);

final class HomeController extends Controller {
    //subClasse de controler
    
    //método index -> renderizar a tela home/index.php
    public function index() : void{
        $totalInsumos = Produto::totalInsumos();
        $totalEstoque = Produto::totalEstoque();
        $ultimasMovimentacoes = Movimentacao::listarHistorico(5);

        $this->renderizar("home/index.php", [
            "titulo" => "Painel de Controle do Almoxarifado",
            "totalInsumo" => $totalInsumos,
            "totalEstoque" => $totalEstoque,
            "ultimasMovimentacoes" => $ultimasMovimentacoes
        ]);
    }
}
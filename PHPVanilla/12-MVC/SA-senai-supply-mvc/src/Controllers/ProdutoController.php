<?php
declare(strict_types=1);

final class ProdutoController extends Controller {

    //listar todos os produtos
    public function listar():void {
        $produtos = produto::listarTodos();
        $this->renderizar("produtos/listar", [
            "titulo" => "Catálogo Geral de insumos/produtos",
            "produto" => $produtos
        ]);
    }

 //salvar produtos
    public function salvar(): void {
        if (!$this->validarCsrfToken($_POST['csrf_token'] ?? null)) {
            $this->renderizar('produtos/criar', ['titulo' => 'Novo Insumo', 'erro' => 'Falha CSRF.']);
            return;
        }
        //pego as informações do formulário
        $nome = trim($_POST['nome'] ?? '');
        $cat  = trim($_POST['categoria'] ?? '');
        $preco = (float)($_POST['preco'] ?? 0.0);
        $est  = (int)($_POST['estoque'] ?? 0);

        //verifico se não tem nada vazio
        if ($nome !== '' && $cat !== '' && $preco > 0.0 && $est >= 0) {
            Produto::salvar($nome, $cat, $preco, $est);
            Logger::info("Novo insumo cadastrado no almoxarifado", ['nome' => $nome, 'categoria' => $cat]);
            $this->redirecionar('/produtos');
        }
        //se alguma informação não for preenchida no formulário, 
        //renderiza a tela com a mensagen de falha
        $this->renderizar('produtos/criar', [
            'titulo'    => 'Novo Insumo',
            'erro'      => 'Preencha todos os campos corretamente.',
            'csrfToken' => $this->obterCsrfToken()
        ]);
    }
}
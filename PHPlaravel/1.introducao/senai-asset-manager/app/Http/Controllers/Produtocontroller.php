<?php

namespace App\Http\Controllers;

use App\Models\Produto;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class Produtocontroller extends Controller
{
    /**
     * Listar todos os clintes cadastrados
     */
    public function index(): View
    {
        return view("produtos.index", [
            "produtos" => Produto::all(), //busca todo os clientes do banco 
            "titulo"   => "produtos"

        ]);
    }

    /**
     * exibir um formulário para cadastro de novos produtos
     */
    public function create(): View
    {
        return view("produtos.create", [
            "titulo" =>"Novo Cadastro de produtos"
        ]);
    }

    /**
     * Salva os dados do novo produto no banco de dados usando request e dando uma response 
     */
    public function store(Request $request): RedirectResponse
    {
        $dados = $request->validate([
            "nome"          => "required | unique:produtos",
            "quantidade"    => "required",
            "valor"         => "required"
         ]);
         // fazer o cadastro no banco
         Produto::create($dados);
         return redirect()->route("produtos.index")->with("sucesso", "Produtos Cadastrados com sucesso");
    }

    /**
     * Exibir os detalhes de um cliente especifico
     */
    public function show(string $id): View
    {
        $produto = Produto::findOrFail($id); // manda uma mensagem de erro caso não encontre

        return view("produtos.show",[
            "produto"   => $produto
        ]);
    }

    /**
     * Exibir um formulario com os dados de um cliente especifico para atualização 
     */
    public function edit(string $id): View
    {
        $produto = Produto::findOrFail($id);

        return view("produtos.edit", [
            "produto" => $produto
        ]);
    }

    /**
     * Atualizar os dados do cliente utilizando method spoofing(PUT)
     */
    public function update(Request $request, string $id):
    RedirectResponse
    {
        $dados = $request->validate([
            "valor"     => "required",
            "quantidade"   => "required"
        ]);
        Produto::findOrFail($id)->update($dados);
        return redirect()->route("produtos.show", $id)->with
        ("sucesso", "dados atualizados com sucesso");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): RedirectResponse
    {
        Produto::findOrFail($id)->delete();
        return redirect()->route("produtos.index")->with("sucesso","prduto removido do sistema");
    }
}

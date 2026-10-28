<?php
declare(strict_types=1);

//Camada de ACesso a Dados (DAO) para almoxarifado de peças
// esta Camada sera um Classe de Conexão usando POO (Programaçãk orientada ao Objeto)

final class PecaDAO {
    //atributos da classe
    private PDO $pdo; // => atributo que irá disponibiliar a conexão com o banco de dados

    //métodos da classe
    //construtor -> é o método que permite criar objetos desta classe
    public function __construct(PDO $pdo) {
        //ao chamar o construtor estou atribuindo um valor  ao atributo declarado anteriormente
        $this->pdo = $pdo;
    }
    // para criar um obj da clase PecaDAO é necessário ja possuir uma conexão estabelecida com o banco de dados, conexão essa criada anteriormente na classe ConexaoBanco

    //criar os método do CRUD
    //READ -> listar todas as peças em ordem decrescente
    public function listarTodos(): array {
        $sql = "SELECT * FROM pecas_industriais ORDER BY id DESC";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);//criar uma lista de produtos associando os valores ao nomes das colunas do banco de dados
    }
    //READ -> Listar objetos pelo ID
    public function buscarPorId(int $id): array{
        $sql = "SELECT * FROM pecas_industriais WHERE id= :id"; //código sql
        //se vai ter dados sendo inseridos pelo usuário
        $stmt = $this->pdo->prepare($sql);
        //prepara o parametro de entrada usando bindValue()
        $stmt->bindValue(":id", $id, PDO::PARAM_INT);
        $stmt->execute();

        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
        return $resultado;
    }

    //READ -> buscar por termo (filtro)
    public function buscarPorTermo(string $termo): array
    {
        $sql = "SELECT * FROM pecas_industriais
                 WHERE codigo_sku ILIKE :termo 
                 OR descricao ILIKE :termo
                 OR categoria ILIKE :termo
                ORDER BY descricao ASC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([":termo" => "%" .trim($termo) . "%"]);
        $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $resultado;  
    }

  //CREATE -> cadastra nova peça no estoque
  public function salvar(array $dados): bool {
        $sql = "INSERT INTO pecas_industriais
                (codigo_sku, descricao, categoria, quantidade, preco_unitario)
                VALUES (:sku, :descricao, :categoria, :quantidade, :preco)";
        $stmt = $this->pdo->prepare($sql);
        $resultado = $stmt->execute([
            ":sku"           => strtoupper(trim($dados["codigo_sku"])),
            ":descricao"     => trim($dados["descricao"]),
            ":categoria"     => trim($dados["categoria"]),
            ":quantidade"    => (int)$dados["quantidade"], //(int) => cast de dados -> converte para formato numero o variável
            ":preco"         => (float)$dados
            ["preco_unitario"] // CAST => (float)
        ]);
        return $resultado; // retornar se deu certo (true) ou não (false)
    }

    public function atualizar(int $id, array $dados): bool {
        $sql = "UPDATE pecas_industriais
                SET codigo_sku = :sku,
                    descricao = :descricao,
                    categoria = :categoria,
                    quantidade = :quantidade,
                    preco_unitario = :preco
                WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);

        $resultado = $stmt->execute([
                                    ":id"         => $id,
                                    ":sku"        => strtoupper(trim($dados["codigo_sku"])),
                                    ":descricao"  => trim($dados["descricao"]),
                                    ":categoria"  => trim($dados["categoria"]),
                                    ":quantidade" => (int)$dados["quantidade"],
                                    ":preco"      => (float)$dados["preco_unitario"]
                                    ]);
        return $resultado;
    }

 //DELETE -> Exlcuir Dado do Banco
    public function excluir(int $id):bool {
        $sql="DELETE FROM pecas_industriais WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(":id", $id, PDO::PARAM_INT);
        $resultado = $stmt->execute();
        return $resultado;
    }

    
}
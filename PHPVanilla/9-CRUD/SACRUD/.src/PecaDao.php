<?php
declare(strict_types=1);

// Camada de acesso a Dados (DAO) par almoxarifados de peças
// Esta Camada será um Classe de conexão usando POO (Programação orientada ao Objeto)

final class PecaDao {
    //atributos da classe
    private PDO $pdo;

    //métodos da classe
    //construtor -> é o método que permite criar objetos desta classe
    public function __construct(PDO $pdo) {
        //ao chamar o construtor estou atribuindo um valor ao atrinuto declarado anteriormente
        this->pdo = $pdo;
    }
    // para criar um obj da classe pecaDao é necessário já possuir uma conexão estabelicida com o banco de dados, conexão essa criada anteriormente na classe conexãoBanco 

    //criar os método do CRUD
    //READ -> listar todas as peças em ordem decrecente
    public function listartodos(): array {
        $sql = "SELECT * FROM pecas_industriais ORDER BY id DESC";
        $stmt = this->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC); //criar uma lista produtos associando os valores ao nomes das colunas do banco de dados

    }

}

?>
<?php
declare(strict_types=1);

use PDO;

final class Produto{
    //método privado de conexão com o banco
    private static function pdo(): PDO {
        $cfg = dirname(__DIR__, 2) . '/config/database.ini';
        return ConexaoBanco::obterConexao($cfg);
    }

    //Retornar lista de Produtos
    public static function listarTodos(): array {
        $sql = "SELECT id, nome, categoria, preco, estoque, cadastrado_em FROM produtos ORDER BY nome ASC";
        return self::pdo()->query($sql)->fetchAll();
    }

    //Localizar um Porduto pelo Código (ID)
    public static function buscarPorId(int $id): ?array {
        $sql = "SELECT id, nome, categoria, preco, estoque FROM produtos WHERE id = :id LIMIT 1";
        $stmt = self::pdo()->prepare($sql);
        $stmt->execute([':id' => $id]);
        $res = $stmt->fetch();
        return $res ?: null;
    }

    //Cadastrar um Novo Produto
    public static function salvar(string $nome, string $cat, float $preco, int $est): bool {
        $sql = "INSERT INTO produtos (nome, categoria, preco, estoque) VALUES (:nome, :cat, :preco, :est)";
        $stmt = self::pdo()->prepare($sql);
        return $stmt->execute([
            ':nome'  => trim($nome),
            ':cat'   => trim($cat),
            ':preco' => $preco,
            ':est'   => $est
        ]);
    }

    //Retornar total em Estoque
    public static function totalEstoque(): int {
        $sql = "SELECT COALESCE(SUM(estoque), 0) FROM produtos";
        return (int)self::pdo()->query($sql)->fetchColumn();
    }

    //Retornar os tipos de insumos cadastrados
    public static function totalInsumos(): int {
        $sql = "SELECT COUNT(id) FROM produtos";
        return (int)self::pdo()->query($sql)->fetchColumn();
    }
}
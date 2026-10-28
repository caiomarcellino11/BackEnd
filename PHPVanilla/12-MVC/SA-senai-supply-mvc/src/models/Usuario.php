<?php
declare(strict_types=1);

final class Usuario{
    //classe ligada ao banco de dados tabelas de usuarios

    //método privado para obter conexão via PDO
    private static function pdo(): PDO {
        $cfg = dirname(__DIR__,2) . "/config/database.ini"; // configuração do BD
        return ConexaoBanco::obterConexao($cfg); //estabelecer a conexão com o banco
    }

    //método para loclaizar usuário por email 
    public static function buscarPorEmail(string $email): ?array {
        //? pode ou não retornar os dados de um usuário
        $sql = "SELECT id, nome, email, senha_hash, perfil FROM usuarios WHERE email = :email LIMIT 1";
        $stmt = self::pdo()->prepare($sql);
        $stmt->execute([':email' => trim($email)]);
        $resultado = $stmt->fetch();
        return $resultado ?: null;
    }

     //método para consultar por id
    public static function buscarPorId(int $id): ?array {
        $sql = "SELECT id, nome, email, perfil FROM usuarios WHERE id = :id LIMIT 1";
        $stmt = self::pdo()->prepare($sql);
        $stmt->execute([':id' => $id]);
        $resultado = $stmt->fetch();
        return $resultado ?: null;
    }
}

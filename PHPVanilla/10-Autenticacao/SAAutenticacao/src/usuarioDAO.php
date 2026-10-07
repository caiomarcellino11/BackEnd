<?php
declare(strict_types=1);

//isolar as operações de busca e cadastro de usuários
//nessa camada é aplicado o hash de senha `password_hash()`;
final class UsuarioDAO{
    //atributos
    private PDO $pdo; // atributo de conexão com o banco

    //construtor => permite instanciar obj desta classe
    public function __construct(PDO $pdo){
        $this->pdo = $pdo;
    }

    //métodos
    //cadastrar => com cryptografia
    public function cadastrar(
                string $nome, string $email, string $senha, string $perfil = "OPERADOR"
                ):bool{
        $sql = "INSERT INTO usarios(nome, email, senha_hash, perfil) 
                VALUES (:nome, :email, :hash, :perfil)";
        $stmt = $this->pdo->prepare($sql);
        //fazer o algoritmo de hash da senha
        $algoritmo = defined("PASSWORD_ARGON2ID") ? PASSWORD_ARGON2ID : PASSWORD_BCRYPT;
        $hash = password_hash($senha, $algoritmo); // vai criar a senha cryptografada
        return $stmt->execute([
            ":nome"     =>trim($nome),
            ":email"    =>strtolower(trim($email)),
            ":hash"     => $hash,
            ":perfil"   => $perfil
        ]);
    }

    //buscar por Email
    public function buscarporEmail(string $email): ?array{
        $sql = "SELECT * FROM usuarios
                 WHERE codigo_sku ILIKE :termo 
                 OR descricao ILIKE :termo
                 OR categoria ILIKE :termo
                ORDER BY descricao ASC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([":termo" => "%" .trim($termo) . "%"]);
        $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $resultado;  

    }

}
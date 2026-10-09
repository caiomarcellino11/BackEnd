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
        $sql = "INSERT INTO usuarios(nome, email, senha_hash, perfil) 
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
    public function buscarPorEmail(string $email): ?array{
        $sql = "SELECT * FROM usuarios WHERE email = :email AND ativo = TRUE"; 
        $stmt = $this -> pdo -> prepare($sql);
        $stmt -> bindValue(":email" , strtolower(trim($email)), PDO::PARAM_STR);
        $stmt -> execute(); 
        $usuario = $stmt -> fetch(PDO::FETCH_ASSOC);
        return $usuario ?: null;
    }

    //verificar se email existe => evitar dois cadastros com o mesmo email
    public function emailExiste(string $email): bool{
        $sql = "SELECT email FROM usuarios WHERE email = :email";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(":email", strtolower(trim($email)), PDO::PARAM_STR);
        $stmt -> execute();

        return (bool)$stmt->fetchColumn(); //(bool) -> CAST => garante que o retorno da informação vai ser uma booleana
    }
   

}
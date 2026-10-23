<?php
declare(strict_types=1);
//Classe para Representar atributos e metodos dp titular da conta bancaria

final class Titular {
    //usar o property promotion para declarar os atributos dentro do construtor
    //construtor
    public function __construct(
        //propriedade readoly => garante imutabilidade nativa pleo PHP
        public readonly string $nome,
        public readonly string $cpf
    ){
        $this->validarDados();
    }

    //criar o método de validação dos dados
    private function validarDados() : void {
        if(strlen(trim($this->nome)) < 3){
            throw new InvalidArgumentException("O Nome do titular deve possuir no mínimo 3 caracteres");
        }
        
        //subtituir valores não numericos no cpf por nada""
        $cpfNumeros = preg_replace("/\D/", "", $this->cpf); //apenas numeros
        if(strlen($cpfNumeros) !== 11) {
            throw new InvalidArgumentException("O CPF deve conter exatamente 11 digitos numéricos");
        }
    }
}
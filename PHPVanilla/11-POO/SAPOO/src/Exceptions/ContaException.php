<?php
declare(strict_types=1);

//Classe base para erros das regras de negócio
class ContaException extends DomainException { //herança da base de erros do sistema
    //declaração do construtor usando property promotion
    public function __construct(string $message = "Falha na Operação Bancária!")
    {
        parent::__construct($message);
    }
}
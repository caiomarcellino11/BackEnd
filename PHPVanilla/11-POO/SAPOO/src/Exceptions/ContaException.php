<?php
declare(strict_types=1);

use DomainException; //impor da biblioteca do PHP de erros chamado DomainException

//Classe base para erros das regras de negócio
class ContaException extends DomainException { //herança da base de erros do sistema
    //declaração do construtor usando property promotion
    #[Override] // reescrevendo o construtor
    public function __construct(string $message = "Falha na Operação Bancária!")
    {
        parent::__construct($message);
    }
}
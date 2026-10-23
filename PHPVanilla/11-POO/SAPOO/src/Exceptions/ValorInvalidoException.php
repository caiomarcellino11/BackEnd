<?php
declare(strict_types=1);

//Dispara quando uma operação recebe valores monetários nulos ou negativos
final class ValorInvalidoException extends ContaException {
    public function __construct(float $valor)
    {   
        $message = sprintf("Valor R$ %.2f inválido. A quantia deve ser positiva e maior que zero!", $valor);
        parent::__construct($message);
    }
}
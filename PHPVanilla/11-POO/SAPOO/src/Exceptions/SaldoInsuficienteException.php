<?php
declare(strict_types=1);

//subclasse de ContaException
final class SaldoInsuficienteException extends ContaException{
    public function __construct(float $saldoDisponivel, float $valorTentado)
    {
        $message = sprintf(
            "Saldo induficiente. Disponível: R$ %.2f | Tentativacom encargos: R$ %.2f",
            $saldoDisponivel,
            $valorTentado
        );
        parent::__construct($message);
    }
}
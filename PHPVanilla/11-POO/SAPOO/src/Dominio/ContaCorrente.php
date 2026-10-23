<?php
declare(strict_types=1);

//Classe contacorrente que é uma subclasse de ContaBancaria
//Regra de Negócio => permite cheque especial (limite negativo)
// => taxa de saque => 1.50

final class ContaCorrente extends ContaBancaria{
    //atributos não declarados em conta bancaria
    public const TARIFA_SAQUE = 1.50;

    //reescrevendo o método da superclasse
    public function __construct(
        string $agencia, 
        string $numeroConta, 
        Titular $titular, 
        float $saldoInicial,
        private float $limiteChequeEspecial = 500.00)
    {
        return parent::__construct($agencia, $numeroConta, $titular, $saldoInicial);
    }

    //obrigatoriedade de implementar o método sacar(método polimorfico)
    #[Override]
    public function sacar(float $valor): void
    {
        if($valor <=0){
            throw new ValorInvalidoException($valor);
        }
        $custoTotal = $valor + self::TARIFA_SAQUE;
        $saldoTotalDisponivel = $this->saldo + $this->limiteChequeEspecial;

        if($custoTotal > $saldoTotalDisponivel) {
            throw new SaldoInsuficienteException($saldoTotalDisponivel, $custoTotal);
        }
        $this->saldo -= $custoTotal;
    }

    //método de acesso do cheque especial
    public function getLimiteChequeEspecial():float{
        return $this->limiteChequeEspecial;
    }
}
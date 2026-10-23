<?php
declare(strict_types=1);

//superclasse abstrata contendo o contrato e estados compartilhados entre as contas

abstract class ContaBancaria {
    //atributos do tipo protected ( podem ser passados como herança)
    protected float $saldo;

    //construtor
    public function __construct(
        public readonly string $agencia,
        public readonly string $numeroConta,
        public readonly Titular $titular,
        float $saldoInicial
    )
    {
        // validação se o saldo inicial é maior que 0
        //caso não for emite uma exception (ValorInvalidoException)
        if ($saldoInicial < 0.0) {
            throw new ValorInvalidoException($saldoInicial);
        }
        $this->saldo = $saldoInicial;
    }

    //métodos da Classe
    //depositar
    public function depositar(float $valor): void {
        if($valor <=0.0){
            throw new ValorInvalidoException($valor);
        }
        //$this->saldo = $this->saldo + $valor;
        $this->saldo += $valor;
    }

    //método Abstrato (polimorfismo)
    abstract public function sacar(float $valor): void;

    //método polimorfico que realiza transferência entre contas
    public function transferir (float $valor, ContaBancaria $contaDestino) : void {
        if ($this === $contaDestino){
            throw new ValorInvalidoException($valor);
        }

        //Relaizar a tranferencia => relaiza o saque
        $this->sacar($valor);
        //depois o deposito
        $contaDestino->depositar($valor);
    }

    //método publico de acesso (getter)
    public function getSaldo():float {
        return $this->saldo;
    }
}
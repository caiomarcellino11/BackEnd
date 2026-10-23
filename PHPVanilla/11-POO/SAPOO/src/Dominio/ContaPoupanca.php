<?php
declare(strict_types=1);

//classe ContaPoupança
//Regras de Negócio => não tem limite e não tem taxa de saque
// não permite sacar valores maiores que o disponivel no saldo

final class ContaPoupanca extends ContaBancaria {
    //mantem o construtor
    // não precisa declarar o construtor

    //sobreescrita do método sacar 
    public function sacar(float $valor): void{
        //verificar se valor é positivo
        if($valor <= 0.0) {
            throw new ValorInvalidoException($valor);
        }
        //verificar se valor é menor que o saldo
        if($valor > $this->saldo) {
            throw new SaldoInsuficienteException($this->saldo, $valor);
        }

        //se tudo estiver certo
        $this->saldo -= $valor;
    }

    // método de rendimentos da poupança
    public function renderJuros(float $percentual):void{
        if($percentual <=0.0) {
            throw new ValorInvalidoException($percentual);
        }
        $rendimento = $this->saldo *($percentual/100);
        $this->saldo += $rendimento;
    }
}
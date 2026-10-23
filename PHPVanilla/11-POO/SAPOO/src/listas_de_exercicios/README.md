# Lista de Exercícios de Fixação e Prática — POO em PHP

## Parte A — Questões Teóricas de Fixação

### 1. Procedural vs. OO

**Pergunta:** Quais são os três principais riscos técnicos de manter regras financeiras críticas implementadas puramente através de arrays associativos e scripts procedurais?

**Resposta:**

1. **Inconsistência de dados:** diferentes partes do código podem alterar o saldo sem aplicar as mesmas regras de negócio.
2. **Falhas de segurança:** a ausência de encapsulamento pode permitir alterações indevidas em informações financeiras.
3. **Dificuldade de manutenção:** regras espalhadas pelo sistema aumentam a duplicação de código e dificultam testes e correções.

A Programação Orientada a Objetos (POO) ajuda a centralizar os dados e as regras em classes, protegendo os invariantes do sistema.

### 2. Ciclo de Vida do Objeto

**Pergunta:** O que ocorre na memória Heap do servidor quando a instrução `new ContaCorrente(...)` é executada? Qual é o papel do método `__construct()` nesse processo?

**Resposta:**

Ao executar `new ContaCorrente(...)`, o PHP cria uma nova instância da classe na memória e retorna uma referência ao objeto. Suas propriedades passam a integrar o estado dessa instância.

O método mágico `__construct()` é executado durante a construção do objeto, permitindo inicializar seus atributos e validar os dados recebidos. Por exemplo, ele pode definir o titular e o saldo inicial conforme as regras estabelecidas pela classe.

### 3. A Pseudo-Variável `$this`

**Pergunta:** Explique por que o comando `$this->saldo` é obrigatório para acessar um atributo dentro da classe, diferenciando-o de uma variável local `$saldo`.

**Resposta:**

A pseudo-variável `$this` representa o objeto atual dentro de um método não estático. Ela permite acessar os atributos e métodos pertencentes àquela instância.

Já `$saldo` é uma variável local, que existe no escopo do método e não representa automaticamente o atributo do objeto.

Exemplo:

```php
<?php
declare(strict_types=1);

class ContaCorrente
{
    private float $saldo = 0.0;

    public function depositar(float $valor): void
    {
        $saldo = $valor; // Variável local.
        $this->saldo += $saldo; // Atributo do objeto.
    }
}
```

Nesse exemplo, `$saldo` recebe o valor localmente, enquanto `$this->saldo` representa o saldo armazenado na conta.

### 4. Encapsulamento e Invariantes

**Pergunta:** Por que um desenvolvedor sênior de back-end nunca deve criar um método `public function setSaldo(float $novoSaldo)` para uma conta bancária?

**Resposta:**

Um método público como `setSaldo(float $novoSaldo)` permite substituir diretamente o saldo sem garantir que a alteração corresponda a uma operação financeira válida.

O saldo deve ser modificado por métodos específicos, como `depositar()` e `sacar()`, que validam valores, verificam o saldo disponível e preservam as regras de negócio. Assim, a classe mantém o controle sobre seu próprio estado.

### 5. Modificadores de Acesso

**Pergunta:** Qual é a diferença prática entre os modificadores `private` e `protected` quando subclasses entram em cena através do `extends`?

**Resposta:**

- **`private`:** o atributo ou método pode ser acessado diretamente apenas dentro da classe em que foi declarado.
- **`protected`:** permite o acesso dentro da própria classe e também nas classes filhas que herdam dela com `extends`.

Por exemplo, um atributo de saldo declarado como `private` não pode ser acessado diretamente por uma subclasse. Já um método auxiliar `protected` pode ser utilizado pelas subclasses para implementar comportamentos específicos.

### 6. Constructor Property Promotion

**Pergunta:** Como o recurso de promoção de propriedades introduzido no PHP 8 reduz o *boilerplate* e melhora a legibilidade do código?

**Resposta:**

O *Constructor Property Promotion*, introduzido no PHP 8.0, permite declarar propriedades diretamente nos parâmetros do construtor usando modificadores de acesso.

Isso reduz a repetição de declarações, parâmetros e atribuições manuais, tornando o código mais curto e legível.

Exemplo:

```php
<?php
declare(strict_types=1);

class Titular
{
    public function __construct(
        private string $nome,
        private string $cpf
    ) {
    }
}
```

Nesse exemplo, o PHP declara e inicializa as propriedades `$nome` e `$cpf` automaticamente a partir dos argumentos recebidos.

### 7. Propriedades `readonly`

**Pergunta:** Explique o mecanismo de segurança proporcionado pela palavra-chave `readonly` em atributos de classes de valor como Titular ou CPF.

**Resposta:**

A palavra-chave `readonly`, disponível para propriedades desde o PHP 8.1, impede que uma propriedade tipada seja modificada depois de inicializada, respeitadas as regras específicas da linguagem.

Ela é útil para atributos de objetos de valor, como um CPF validado, cuja identificação não deve mudar durante a vida daquele objeto.

Exemplo:

```php
<?php
declare(strict_types=1);

class Titular
{
    public function __construct(
        public readonly string $cpf
    ) {
    }
}
```

A propriedade pode ser lida, mas uma tentativa comum de atribuir outro CPF após a inicialização gera um erro. Entretanto, `readonly` não valida automaticamente se o CPF é verdadeiro ou válido: essa verificação precisa ser implementada separadamente.

### 8. Classes Abstratas

**Pergunta:** Por que faz todo sentido no modelo bancário que a classe `ContaBancaria` seja declarada como `abstract` enquanto `ContaCorrente` e `ContaPoupanca` são classes concretas?

**Resposta:**

A classe `ContaBancaria` faz sentido como classe abstrata porque representa um conceito geral de conta bancária, enquanto `ContaCorrente` e `ContaPoupanca` possuem comportamentos específicos.

Uma classe abstrata pode reunir atributos e métodos compartilhados, além de declarar métodos abstratos que devem ser implementados pelas subclasses concretas.

Dessa forma, as classes filhas reutilizam a estrutura comum, mas implementam suas próprias regras de saque e outras operações específicas.

### 9. Polimorfismo

**Pergunta:** Como o método `transferir(float $valor, ContaBancaria $destino)` consegue operar transferências entre quaisquer tipos de contas filhas sem precisar saber qual é a classe exata da conta de destino?

**Resposta:**

O polimorfismo permite que um método trabalhe com objetos de diferentes classes concretas por meio de um tipo comum, como `ContaBancaria`.

No método `transferir(float $valor, ContaBancaria $destino)`, o parâmetro aceita objetos de classes que herdam de `ContaBancaria`. O código pode operar sobre o contrato comum sem precisar identificar antecipadamente se o destino é uma conta corrente ou poupança.

Cada objeto pode executar seu comportamento específico por meio dos métodos sobrescritos. Ainda assim, as regras de autorização, titularidade e validade da transferência precisam ser verificadas explicitamente.

### 10. Exceções de Domínio

**Pergunta:** Por que é uma péssima prática retornar `false` ou `null` quando uma operação de saque é rejeitada por falta de saldo?

**Resposta:**

Retornar apenas `false` ou `null` quando um saque é rejeitado pode tornar o tratamento de erros menos claro, pois o código que chamou o método precisa descobrir o motivo da falha e diferenciar situações distintas.

Uma exceção de domínio, como `SaldoInsuficienteException`, comunica explicitamente o motivo pelo qual a operação não pôde ser realizada. Isso permite separar o fluxo normal de execução das situações excepcionais e centralizar o tratamento dos erros.

Por exemplo, a aplicação pode capturar a exceção e apresentar uma mensagem adequada ao usuário, enquanto registra os detalhes necessários para auditoria.

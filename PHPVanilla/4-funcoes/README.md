## Exercícios Teóricos

### Função

uma função é como se fosse uma receita de bolo que mando para o computador e ele trás para mim o bolo feito 
 - função = receita
 - Os parâmetros = os ingredientes que você coloca
 - o código dentro da função =  o passo a passo 
 - o `return` = o bolo pronto que a receita devolve.

vantagens:

 - deixa o código organizado 
 - não precisa reptir o código toda a vez - regra de ouro

 >lembra da função da matématica onde tem uma formula onde usa para te auxiliar

 ---

 ### Princípio DRY

DRY significa `"dont repeat yourself"`

reptir várias vezes o mesmo código em vários lugares, dificulta muito na hora se reparar se acontercer algum bug.
por isso a função facilita nessa questão não preciso ficar reptindo varias vezes o código, se der um problema resolve naquele código específico.

### Parâmetros e retorno

Pâpametros são valores que a função recebe para poder executar e trabalhar nela.
Valor retornado é o resultaod que função devolve quando executa o código.

```php
function calcularTotal(float $preco, int $quantidade): float {
    return $preco * $quantidade
}
```
$preco -> parâmetros tipados do tipo float
$quantidade -> parâmetros tipados do tipo int
: float -> indica a função que deve retornar um float
return $preco * $quantidade -> devolve o resultado da multiplicação.

### Tipagem 
Na declaração está:
```php
function cadastrar(string $nome, int $idade): bool
```
então temos:
cadastar -> nome da função 
string $nome -> parâmetros $nome, deve ser uma string.
int $idade -> parâmetros $idade, que deve ser um inteiro
bool -> tipo do valor retornara pela função

### void e return 

Uma função que retorna `string` deve devolver um texto usando `return`

```php 
function nomeCompleto(): string {
    return "Caio Martins";
}
```
---

uma função `void` não retorna um valor.

```php
function mostrarMensagem(): void {
    echo "Olá";
}
```
---
qual a diferença:

`string` -> a função que devolce um texto
`void` -> a função não devolve um valor


### Escopo

O código:

```php
$cliente = "Mariana";

function exibirCliente(): string {
    return $cliente;
}
```
não funciona porque $cliente foi criada fora da função. variáveis criadads fora de uma função não ficam na hora disponíveis dentro dela.

##### forma 1 - usando global

```php

$cliente = "Mariana";

function exibirCliente(): string {
    global $cliente;
    return $cliente;
}
```
---

##### forma 2 - passando como parâmetros

```php 

$cliente = "Mariana";

function exibirCliente(string: $cliente): string {
    return $cliente;
}

echo exibirCliente($cliente);
```
> 2 forma e melhor pois deixam claras as informações na função precisa e evita depender de variáveis globais.

### Referência

quando usamos:

```php
float &$valor
```
o `&` faz com que a função receba uma referência à variável original, em vez de trabalhar apenas com uma cópia.

por exemplo:

```php
function aumentar(float &$valor): void {
    $valor += 10;
}

$preco = 50;
aumentar($preco);

echo $preco;
```
> resultado: 60

Isso acontece porque a função alterou diretamente a variável $preco.
Sem o &, a função trabalharia com uma cópia do valor e a variável original não seria alterada.

### Funções nativas 

Cinco exemplos de funções nativas do PHP:

| Função | Categoria | O que faz | Como usar |
|---|---|---|---|
| `strlen()` | Strings | Conta a quantidade de caracteres de um texto. | `$tamanho = strlen($texto);` |
| `strtoupper()` | Strings | Converte o texto para letras maiúsculas. | `$resultado = strtoupper($texto);` |
| `strtolower()` | Strings | Converte o texto para letras minúsculas. | `$resultado = strtolower($texto);` |
| `count()` | Arrays | Conta a quantidade de itens de um array. | `$total = count($produtos);` |
| `sqrt()` | Números | Calcula a raiz quadrada de um número. | `$resultado = sqrt($numero);` |


```php

strlen("Caio");       // 4
strtoupper("caio");   // CAIO
strtolower("CAIO");   // caio
count([1, 2, 3]);     // 3
sqrt(25);             // 5
```

---

### Previsão de saída


```php

function aplicarDesconto(float $preco): float {
    return $preco * 0.90;
}

$valor = 100.00;
echo aplicarDesconto($valor);
echo $valor;
```
no primeiro `echo` vai aparecer o resutado do calculo onde será 100*90 dando 90
no segundo `echo` vai aparecer o valor original 100

>como não existe `&` mo parâmetro não altera `$valor`
continaundo 100
---

### Documentação

Segundo a documentação oficial do PHP, a sintaxe é:

```php
strlen(string $string): int
```
Função: `strlen()`
Parâmetro: `$string`, do tipo string.
Retorno: int.
Finalidade: retorna o número de bytes de uma string.
>retorna a quantidade de letras de um nome ou número

Exemplo:
```php
$nome = "Caio";

echo strlen($nome);
```
Resultado:
> 4

# Lista de Exercícios — Conexão com Banco de Dados PDO

## Parte A — Exercícios Teóricos de Fixação

### 1. Abstração de Dados

O PDO (*PHP Data Objects*) é uma interface do PHP que permite conectar e interagir com diferentes sistemas gerenciadores de banco de dados por meio de uma API padronizada. Ele é preferível a extensões especializadas, como `pgsql`, porque facilita a manutenção e a portabilidade do código, permitindo trocar o banco de dados com menos alterações na aplicação. Além disso, oferece recursos como prepared statements e tratamento padronizado de erros.

### 2. Ciclo do DSN

DSN (*Data Source Name*) é uma string que identifica o banco de dados ao qual a aplicação deseja se conectar. No PostgreSQL, ela utiliza o driver `pgsql` e pode conter os seguintes parâmetros:

- `host`: endereço do servidor onde o PostgreSQL está executando, como `127.0.0.1`.
- `port`: porta utilizada pelo servidor para receber conexões, normalmente `5432`.
- `dbname`: nome do banco de dados ao qual a aplicação deseja se conectar.

Exemplo de DSN:

```php
$dsn = "pgsql:host=127.0.0.1;port=5432;dbname=senai";
```

### 3. Padrão de Portas

A porta padrão do PostgreSQL é a `5432`. Ela identifica o ponto de comunicação pelo qual o cliente tenta estabelecer uma conexão com o servidor. No PDO, a porta é especificada dentro do DSN por meio do parâmetro `port`.

Exemplo:

```php
$dsn = "pgsql:host=localhost;port=5432;dbname=senai";
```

Se o PostgreSQL estiver configurado para utilizar outra porta, será necessário informar o número correspondente.

### 4. Flags de Integridade

O atributo `PDO::ATTR_ERRMODE`, configurado com `PDO::ERRMODE_EXCEPTION`, faz com que os erros de operações do PDO sejam apresentados como exceções do tipo `PDOException`. Isso permite utilizar `try-catch` para tratar falhas de maneira organizada e evitar que erros sejam ignorados.

No PHP 8.0 ou superior, o modo padrão do PDO já é `PDO::ERRMODE_EXCEPTION`. Em versões anteriores ao PHP 8.0, o padrão era `PDO::ERRMODE_SILENT`, no qual os erros não geravam exceções automaticamente e precisavam ser verificados explicitamente.

### 5. Fetch Mode

A configuração `PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC` faz com que os registros sejam retornados como arrays associativos, utilizando os nomes das colunas como chaves.

Por exemplo, uma coluna `nome` pode ser acessada por `$registro['nome']`. Isso evita a duplicação de informações por índices numéricos e associativos, como ocorre com `PDO::FETCH_BOTH`, reduzindo o uso de memória por registro em comparação com esse modo.

Entretanto, o ganho total de memória depende da quantidade de registros e da forma como os resultados são processados. Utilizar `PDO::FETCH_ASSOC` não garante, por si só, baixo consumo de memória.

### 6. Padrão Singleton

Criar repetidamente conexões com `new PDO()` pode aumentar o consumo de memória e processamento, além de ocupar várias conexões simultâneas no PostgreSQL. O parâmetro `max_connections` limita a quantidade de conexões simultâneas permitidas pelo servidor.

O padrão Singleton busca manter uma única instância de conexão reutilizável dentro do contexto em que a classe é executada. Isso pode evitar a criação desnecessária de conexões dentro de uma mesma execução da aplicação.

Entretanto, o Singleton não é um pool de conexões e não garante uma única conexão para toda a aplicação, pois processos e requisições diferentes podem ter suas próprias instâncias.

### 7. Encapsulamento do Singleton

O construtor `__construct()` deve ser declarado como `private` para impedir que outras partes do código criem instâncias diretamente com `new ConexaoBanco()`.

O método mágico `__clone()` também deve ser bloqueado para impedir a clonagem do objeto. Já o método `__wakeup()` deve ser bloqueado para impedir que a desserialização crie outra instância da classe.

Essas restrições ajudam a preservar a unicidade da instância, enquanto um método público e estático, como `obterConexao()`, permite acessar a conexão compartilhada.

### 8. Segurança de Credenciais

Deixar o usuário e a senha do banco de dados escritos diretamente nos scripts PHP é uma prática insegura, pois essas informações podem acabar expostas em repositórios públicos, arquivos compartilhados ou registros de código.

Uma abordagem mais segura é manter as credenciais em arquivos de configuração protegidos, como `config/database.ini`, excluídos do controle de versão por meio do `.gitignore`. Em ambientes corporativos, também podem ser utilizados gerenciadores de segredos e variáveis de ambiente.

O arquivo de configuração deve ter permissões adequadas e permanecer fora do acesso público da aplicação. Além disso, a conta do banco deve possuir somente as permissões necessárias para executar suas tarefas.

### 9. Tratamento de Exceções e LGPD

Exibir diretamente `$e->getMessage()` no navegador pode revelar informações internas, como nomes de bancos de dados, endereços de servidores, caminhos de arquivos, consultas SQL e detalhes da infraestrutura. Essa exposição é conhecida como *Information Disclosure* e pode ajudar invasores a identificar pontos vulneráveis da aplicação.

Para evitar esse problema, a aplicação deve apresentar uma mensagem genérica ao usuário, registrar os detalhes técnicos em um log protegido e permitir que a equipe responsável investigue a falha.

Essa prática contribui para a segurança e para a proteção de informações pessoais, aspectos relevantes para a LGPD quando há tratamento de dados pessoais. A LGPD não proíbe especificamente a exibição de mensagens de exceção; o risco depende das informações expostas e das circunstâncias.

Exemplo de mensagem apropriada para o usuário:

```text
Não foi possível concluir a operação. Tente novamente mais tarde.
```
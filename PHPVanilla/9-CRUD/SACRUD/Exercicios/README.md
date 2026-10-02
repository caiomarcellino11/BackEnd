## Exercícios Teóricos de Fixação

1. - Definição de CRUD: O que significa o acrônimo CRUD e qual é a correspondência direta de cada uma de suas letras com as instruções SQL no PostgreSQL?
>CRUD significa Create, Read, Update e Delete. No PostgreSQL:

> - **Create → INSERT**
> - **Read → SELECT**
> - **Update → UPDATE**
> - **Delete → DELETE**

---

2. - Anatomia do SQL Injection: Explique com suas próprias palavras como um atacante consegue alterar a lógica de uma consulta quando o código utiliza concatenação de strings com $_GET ou $_POST.

> O atacante consegue alterar a lógica da consulta quando o código concatena diretamente dados do **$_GET** ou **$_POST** no SQL. Assim, ele pode inserir comandos ou condições SQL dentro do texto enviado, fazendo o banco interpretar parte da entrada como código SQL.

---

3. -  Mecanismo das Prepared Statements: Por que o envio de uma consulta em duas etapas (prepare e depois execute) impede que um texto digitado pelo usuário seja executado como instrução SQL pelo banco?

> O **prepare()** envia a estrutura da consulta separadamente dos dados. Depois, o **execute()** envia os valores. Dessa forma, o banco trata o texto do usuário como dado, e não como uma instrução SQL.

---

 4. - Marcadores Nomeados: Qual é a vantagem de utilizar marcadores nomeados como :sku e :preco em vez de pontos de interrogação posicionais (?) em instruções SQL complexas?

> Marcadores como :sku e :preco deixam o código mais organizado, legível e fácil de manter, pois identificam claramente qual valor será associado a cada parâmetro.

---

 5. - Diferença entre Bindings: Explique a diferença de comportamento entre os métodos $stmt->bindValue() e $stmt->bindParam().

 > O **bindValue()** vincula diretamente o valor atual de uma variável, enquanto o **bindParam()** vincula a referência da variável, permitindo que seu valor seja alterado antes da execução da consulta.

 6. -Tipagem no PDO: Qual é o risco de omitir o tipo de dado (ex: PDO::PARAM_INT) ao vincular uma variável que deveria ser estritamente numérica em uma cláusula LIMIT?

> O valor pode ser tratado como texto em vez de número, causando erros ou comportamento inesperado na cláusula LIMIT. Por isso, é recomendado usar **PDO::PARAM_INT** para garantir que o valor seja tratado como inteiro.

---

7. - Padrão DAO: Qual é o benefício do padrão Data Access Object (DAO) em termos de manutenibilidade de software e do princípio de responsabilidade única (SOLID)?

> O DAO separa o código responsável pelo acesso ao banco de dados das demais partes do sistema. Isso facilita a manutenção e segue o princípio da Responsabilidade Única (SRP) do SOLID.
---

 8. -Operações de Update: Por que a ausência de uma cláusula WHERE em um comando UPDATE é considerada um incidente gravíssimo em ambientes de produção?

> Sem WHERE, o UPDATE pode alterar todos os registros da tabela. Em produção, isso pode causar uma perda ou alteração massiva de dados, sendo um incidente grave.
---

 9. - Impacto da LGPD: De acordo com a Lei Geral de Proteção de Dados (LGPD), quais são as penalidades e impactos que uma organização pode sofrer caso ocorra vazamento de dados de clientes por falha de SQL Injection?

 > Um vazamento causado por SQL Injection pode gerar multas de até 2% do faturamento da empresa, limitada a R$ 50 milhões por infração, além de outras sanções previstas pela LGPD, como advertência, bloqueio ou eliminação dos dados envolvidos. Também pode causar prejuízos financeiros, reputacionais e obrigações de comunicação do incidente.

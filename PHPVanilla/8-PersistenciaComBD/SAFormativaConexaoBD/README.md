# Criação de uma aplicação para Teste de Conexão PDO com Singleton e PDOException


## passo 1 - Valindado a extensão `pdo_pgsql` e o Serviço do postgres

1. abra o terminal e digite:

```bash
php -m | findstr -i pgsql
```
*saída Esperada:* deve listar `pdo_pgsql` e o `pgsql`

caso não aparece:
 - abra o `php.ini`
 - Localize a linha `;extension=pdo_pgsql` e remove o ponto e virgula inicial(`;`);
 - Salve o arquivo e válide novamente o comando 

 2. Validando o **PostgresSQL**

 Usando a extensão do VSCode = postgresql -> instalar a extensão chris Kolkman

 ## Passo 2 - Estrutura de diretórios do projeto 

Organize a raiz do projeto exatamente com a seguinte árvore de pastas:

```text
SAFormativaConexaoBD/
├── config/
│   └── database.ini        <- Credenciais protegidas
├── logs/
│   └── database.log        <- Arquivo gerado para auditoria de falhas
├── src/
│   └── ConexaoBanco.php    <- Classe Singleton com PDO para PostgreSQL
├── schema.sql              <- Script DDL e DML para o PostgreSQL
├── index.php               <- Painel de diagnóstico e testes operacionais
└── README.md               <- Documentação do Projeto
```

## passo 3 - Executando o Script DDL no postgresSQL(`schema.sql`)

## Passo 4 - Criando a arquivo de configuração (`config/database.ini`)
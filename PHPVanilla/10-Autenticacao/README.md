# Situação de Aprendizagem Usando Sessão, Cookies e Autenticaçao

## Estrutura do Projeto

Organize sua pasta exatamente com a seguinte árvore de arquivos

```text
SAAutenticacao/
├── config/
│   └── database.ini        <- Credenciais protegidas de acesso ao PostgreSQL
├── logs/
│   └── database.log        <- Logs do Banco de Dados
├── src/
│   ├── ConexaoBanco.php    <- Conexão Singleton PDO com PostgreSQL
│   ├── UsuarioDAO.php      <- Camada de persistência para consulta e cadastro
│   ├── AuthService.php     <- Serviço de gerenciamento de sessão e expiração
│   └── guard.php           <- Middleware interceptador de páginas restritas
├── schema.sql              <- Estrutura da tabela de usuários corporativos
├── login.php               <- Tela de autenticação pública
├── dashboard.php           <- Painel restrito protegido
├── logout.php              <- Encerramento seguro de sessão
└── README.md               <- Documentação do Projeto
```

## Criação da Tabela no Banco de Dados(PostgreSQL)

```sql
-- Criação da tabela de usuários corporativos com controle de perfil

CREATE TABLE IF NOT EXISTS usuarios (
    id SERIAL PRIMARY KEY,
    nome VARCHAR(80) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    senha_hash VARCHAR(255) NOT NULL,
    perfil VARCHAR(20) NOT NULL DEFAULT 'OPERADOR' CHECK (perfil IN ('ADMIN', 'OPERADOR')),
    ativo BOOLEAN NOT NULL DEFAULT TRUE,
    criado_em TIMESTAMP WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP
);

```

## Configuras o Banco de Dados e Criar a Classe de Conexao Usando Singleton

criar o arquivo .ini

```ini
; config/database.ini
[database]
db_driver   = pgsql
db_host     = 127.0.0.1
db_port     = 5432
db_name     = peca_senai
db_user     = postgres
db_pass     = postgres
```

## Camada de Acesso (`src/UsuarioDAO.php`)

Isolamos as operações de busca e cadastro de usuários, em uma classe DAO 

> obs: cadastro de usuário é realizado com a função `password_hash()`

UsuarioDAO.php: 

- cadastra ( com hash de senha);
- busca por email (busca os dados do usuário)
- verificar se email já existe no cadastro(bool)

## O serviço de Autenticação(`src/AuthService.php`)

classe que ira o ciclo de vida sessão do usuário : cookies e a sessão

>obs: classe do tipo static (não existe instanciamento de objetos)

AuthService.php

- iniciarSessaoSegura() -> passa as informações para o Cookie
- autenticar() -> passa as informações para a superglobal 
- sessaoExpirada() => verifica o tempo de inatividade 
- sessionDestroy() => finalizar as sessao e limpar os cookies do navegador

## Middleware Intercepador (`src/guard.php`)
bloqueia visitantes não autenticados ou sessões expiradas

- verificação de sessão 
- verificação de expiração

## Criação das Telas

### Tela de Cadastro (`cadastro.php`)


## Teste

1. Inicie o servidor embutido do PHP:
   ```bash
   php -S localhost:8000
   ```
2. **Teste 1 — Cadastro de Novo Usuário:**
   Acesse `http://localhost:8000/cadastro.php`, cadastre um novo colaborador (ex.: `maria@senai.br`) com senha de no mínimo 8 caracteres.  
   *Resultado Esperado:* O usuário é registrado no PostgreSQL com hash Argon2id e redirecionado para `login.php?sucesso=cadastrado` com feedback visual em verde.
3. **Teste 2 — Autenticação do Usuário Recém-Cadastrado:**
   Faça login utilizando as novas credenciais criadas.  
   *Resultado Esperado:* Acesso concedido com sucesso ao painel `dashboard.php`, exibindo o nome e o perfil `OPERADOR`.
4. **Teste 3 — Acesso Direto não Autenticado:**
   Abra uma aba anônima e tente digitar diretamente: `http://localhost:8000/dashboard.php`.  
   *Resultado Esperado:* O `guard.php` intercepta a requisição e o expulsa para `login.php?erro=restrito`.
5. **Teste 4 — Inspeção de Flags no DevTools:**
   No painel autenticado, abra o DevTools (**F12**) ➔ Aba **Application (ou Armazenamento)** ➔ **Cookies** ➔ `http://localhost:8000`.  
   *Resultado Esperado:* O cookie `PHPSESSID` exibirá a coluna **HttpOnly** marcada com `✔` e **SameSite** configurado como `Lax`.
6. **Teste 5 — Proteção contra Roubo via Console JavaScript:**
   No console do DevTools (**F12 ➔ Console**), digite: `console.log(document.cookie)`.  
   *Resultado Esperado:* Uma string vazia `""`! A flag `HttpOnly` impediu com sucesso que o JavaScript lesse o token de sessão!
7. **Teste 6 — Encerramento Seguro:**
   Clique no botão **Sair do Sistema**.  
   *Resultado Esperado:* O array `$_SESSION` é limpo no servidor, o cookie é destruído com timestamp expirado e a página redireciona para a tela de login.

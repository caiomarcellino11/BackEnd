# Situação de Aprendizagem Formativa - Criação de um CRUD com PDO e Proteção SQL Injection

## Passo 1 - Montagem das Estruturas de diretórios da Aplicação 


```text
SACRUD/
|__ config/
|   ⌊__ database.ini        <-  Credenciais protegidas de acesso ao Banco de Dados
|__ logs
|   ⌊__ database.log        <-  logs de falhas operacionais
|__ src
|   ⌊__ ConexãoBanco.php    <-  Classe Singleton de conexão PDO
|   ⌊__ PecaDAO.php         <-  Camada de acesso a dados (CRUD com prepared Statement)
|__ schema.sql              <-  Script do banco de Dados
|__ index.php               <-  Controlador e interface visual
|__ README.md               <-  Documentação do Projeto

```

## Passo 2 - Criar a estrutura do banco de dados(`schema.sql`)

```sql
--Criação do banco
CREATE DATABASE peca_senai WITH ENCODING 'UTG8';

-- Criação da tabela de peças industriais do almoxarifado
CREATE TABLE IF NOT EXISTS pecas_industriais (
    id SERIAL PRIMARY KEY,
    codigo_sku VARCHAR(20) NOT NULL UNIQUE,
    descricao VARCHAR(100) NOT NULL,
    categoria VARCHAR(40) NOT NULL,
    quantidade INT NOT NULL DEFAULT 0 CHECK (quantidade >= 0),
    preco_unitario NUMERIC(10,2) NOT NULL CHECK (preco_unitario > 0),
    data_cadastro TIMESTAMP WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP
);

-- Carga inicial de dados para homologação
INSERT INTO pecas_industriais (codigo_sku, descricao, categoria, quantidade, preco_unitario) 
VALUES 
('ROL-SKF-6205', 'Rolamento Rigido de Esferas SKF 6205', 'Mecanica', 45, 89.90),
('COR-V-A42', 'Correia Industrial em V Perfil A-42', 'Transmissao', 120, 24.50),
('DISJ-TER-32A', 'Disjuntor Termomagnetico Tripolar 32A', 'Eletrica', 18, 145.00),
('VALV-SOL-24V', 'Valvula Solenoide Pneumatica 5/2 Vias 24V', 'Pneumatica', 8, 310.00)
ON CONFLICT (codigo_sku) DO NOTHING;
```
### Passo 3 - Arquivo de Configuraçao do Banco de Dados (`config/database.ini`)

Configurar as Credenciais no .ini

```ini
; Configurações de Conexão com o PostgreSQL 16+
[database]
db_driver   = pgsql
db_host     = 192.168.10.80
db_port     = 5432
db_name     = peca_senai
db_user     = postgres
db_pass     = postgres
```

### Passo 4 - Classe Singleton de Conexão (`src/ConexaoBanco.php`)
usar a conexão da aula passada
```php
<?php
declare(strict_types=1);

/**
 * Gerenciador de conexao unica com o PostgreSQL via PDO (Singleton).
 */
final class ConexaoBanco {
    private static ?PDO $instancia = null;

    private function __construct() {}
    private function __clone(): void {}

    public function __wakeup(): void {
        throw new \Exception("Desserializacao nao permitida.");
    }

    /**
     * Retorna a conexao ativa existente ou instancia uma nova sob demanda.
     */
    public static function obterConexao(string $caminhoConfig): PDO {
        if (self::$instancia === null) {
            $config = self::carregarConfig($caminhoConfig);
            self::$instancia = self::criarConexao($config);
        }
        return self::$instancia;
    }

    /**
     * Le o arquivo INI com as credenciais do PostgreSQL.
     */
    private static function carregarConfig(string $caminho): array {
        if (!file_exists($caminho)) {
            throw new \RuntimeException("Arquivo de configuracao ausente.");
        }
        $dados = parse_ini_file($caminho, true);
        if ($dados === false || !isset($dados['database'])) {
            throw new \RuntimeException("Secao [database] invalida no arquivo INI.");
        }
        return $dados['database'];
    }

    /**
     * Instancia o objeto PDO com as flags de seguranca recomendadas.
     */
    private static function criarConexao(array $cfg): PDO {
        $dsn = sprintf("%s:host=%s;port=%s;dbname=%s",
            $cfg['db_driver'], $cfg['db_host'], $cfg['db_port'], $cfg['db_name']);

        $opcoes = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
            PDO::ATTR_TIMEOUT            => 5
        ];

        return new PDO($dsn, $cfg['db_user'], $cfg['db_pass'], $opcoes);
    }
}
```

### Passo 5 - Criar o Arquivo `.gitignore`

Adicionar os arquivos que não irão para versionamento

```git
config/database.ini
logs/
```
### Passo 6 - Construção da Camada DAO (`src/pecaDAO.php`)

esta classe vai encapsular as 4 operações (CRUD) utilizando *Prepared Statement*
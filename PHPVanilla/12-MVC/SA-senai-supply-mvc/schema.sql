-- schema.sql: Estrutura relacional do SENAI Supply MVC
--0. Criação do Banco de Dados (se ainda não existir)
CREATE DATABASE senai_suplly_mvc;

-- 1. Tabela de Usuários com Perfil RBAC
CREATE TABLE IF NOT EXISTS usuarios (
    id SERIAL PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(120) NOT NULL UNIQUE,
    senha_hash VARCHAR(255) NOT NULL,
    perfil VARCHAR(20) NOT NULL CHECK (perfil IN ('admin', 'gestor', 'almoxarife')),
    criado_em TIMESTAMP WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP
);

-- 2. Tabela de Produtos / Insumos Industriais
CREATE TABLE IF NOT EXISTS produtos (
    id SERIAL PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    categoria VARCHAR(50) NOT NULL,
    preco NUMERIC(10, 2) NOT NULL CHECK (preco >= 0),
    estoque INTEGER NOT NULL DEFAULT 0 CHECK (estoque >= 0),
    cadastrado_em TIMESTAMP WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP
);

-- 3. Tabela de Movimentações de Estoque (Auditoria e Rastreabilidade)
CREATE TABLE IF NOT EXISTS movimentacoes (
    id SERIAL PRIMARY KEY,
    produto_id INTEGER NOT NULL REFERENCES produtos(id) ON DELETE RESTRICT,
    usuario_id INTEGER NOT NULL REFERENCES usuarios(id) ON DELETE RESTRICT,
    tipo VARCHAR(10) NOT NULL CHECK (tipo IN ('ENTRADA', 'SAIDA')),
    quantidade INTEGER NOT NULL CHECK (quantidade > 0),
    motivo VARCHAR(255) NOT NULL,
    criado_em TIMESTAMP WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP
);

-- Carga inicial de insumos de demonstração
INSERT INTO produtos (nome, categoria, preco, estoque) VALUES
('Fresa de Metal Duro 4 Cortes 6mm', 'Usinagem', 89.50, 25),
('Broca Escalonada HSS 4-20mm', 'Furação', 145.00, 12),
('Paquímetro Digital de Precisão 150mm', 'Metrologia', 280.00, 8),
('Óleo Solúvel Sintético para Corte 5L', 'Lubrificação', 115.90, 40)
ON CONFLICT (id) DO NOTHING;
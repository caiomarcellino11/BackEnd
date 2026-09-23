-- Cria o banco de dados da biblioteca (caso use o terminal psql)
-- CREATE DATABASE escola_biblioteca WITH ENCODING 'UTF8';

-- Cria a tabela de acervo de livros
CREATE TABLE IF NOT EXISTS livros (
    id SERIAL PRIMARY KEY,
    titulo VARCHAR(120) NOT NULL,
    autor VARCHAR(100) NOT NULL,
    preco NUMERIC(6,2) NOT NULL,
    status VARCHAR(15) NOT NULL DEFAULT 'DISPONIVEL' 
        CHECK (status IN ('DISPONIVEL', 'EMPRESTADO', 'RESERVADO')),
    data_cadastro TIMESTAMP WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP
);

-- Insere alguns livros iniciais para teste
INSERT INTO livros (titulo, autor, preco, status) 
VALUES 
('O Pequeno Príncipe', 'Antoine de Saint-Exupéry', 39.90, 'DISPONIVEL'),
('Dom Casmurro', 'Machado de Assis', 29.90, 'EMPRESTADO'),
('1984', 'George Orwell', 45.00, 'RESERVADO');
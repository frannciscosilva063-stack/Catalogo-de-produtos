CREATE DATABASE bd_produtos;
USE bd_produtos;
-- Cria o banco e usa ele
DROP DATABASE IF EXISTS bd_loja;
CREATE DATABASE bd_loja CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE bd_loja;

-- 1. Tabela de Usuários
CREATE TABLE tb_user (
    id_user INT AUTO_INCREMENT PRIMARY KEY,
    foto_user VARCHAR(200) DEFAULT 'avatar_padrao.png',
    nome_user VARCHAR(100) NOT NULL,
    email_user VARCHAR(150) UNIQUE NOT NULL,
    senha_user VARCHAR(255) NOT NULL,
    nivel ENUM('admin','vendedor','gerente') DEFAULT 'vendedor',
    status ENUM('ativo','inativo') DEFAULT 'ativo',
    data_cadastro DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- 2. Categorias (sem FK temporariamente para não dar erro)
CREATE TABLE tb_categorias (
    id_categoria INT AUTO_INCREMENT PRIMARY KEY,
    nome_categoria VARCHAR(100) NOT NULL UNIQUE,
    descricao_categoria TEXT,
    id_user INT NOT NULL,
    data_cadastro DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- 3. Produtos
CREATE TABLE tb_produtos (
    id_produto INT AUTO_INCREMENT PRIMARY KEY,
    codigo_barra VARCHAR(50) UNIQUE,
    nome_produto VARCHAR(200) NOT NULL,
    descricao_produto TEXT,
    preco_custo DECIMAL(10,2) DEFAULT 0.00,
    preco_venda DECIMAL(10,2) NOT NULL,
    foto_produto VARCHAR(200) DEFAULT 'produto-sem-foto.jpg',
    id_categoria INT NOT NULL,
    id_user INT NOT NULL,
    status ENUM('ativo','inativo') DEFAULT 'ativo',
    data_cadastro DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- 4. Estoque
CREATE TABLE tb_estoque (
    id_movimento INT AUTO_INCREMENT PRIMARY KEY,
    id_produto INT NOT NULL,
    tipo_movimento ENUM('entrada','saida','ajuste') NOT NULL,
    quantidade INT NOT NULL,
    motivo VARCHAR(255),
    id_user INT NOT NULL,
    data_movimento DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- 5. View estoque atual
CREATE OR REPLACE VIEW vw_estoque_atual AS
SELECT 
    p.id_produto,
    p.nome_produto,
    p.preco_venda,
    c.nome_categoria,
    COALESCE(SUM(CASE WHEN e.tipo_movimento = 'entrada' THEN e.quantidade ELSE -e.quantidade END), 0) AS estoque_atual
FROM tb_produtos p
LEFT JOIN tb_estoque e ON p.id_produto = e.id_produto
LEFT JOIN tb_categorias c ON p.id_categoria = c.id_categoria
WHERE p.status = 'ativo'
GROUP BY p.id_produto;

-- ===================================
-- APENAS O USUÁRIO ADMIN
-- ===================================
INSERT INTO tb_user (nome_user, email_user, senha_user, nivel, status) VALUES
('Administrador', 'admin@loja.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', 'ativo');

-- Senha do usuário acima = 123456

SELECT 'USUÁRIO CRIADO COM SUCESSO - LOGIN: admin@loja.com / SENHA: 123456' AS Status;

INSERT INTO tb_categorias (nome_categoria, id_user) VALUES
('Eletrônicos',1),
('Bebidas',1),
('Roupas',1),
('Calçados',1),
('Bolsas',1),
('Relógios',1),
('Perfumes',1),
('Maquiagem',1),
('Eletrodomésticos',1),
('Móveis',1),
('Decoração',1),
('Esportes',1),
('Suplementos',1),
('Livros',1),
('Papelaria',1),
('Brinquedos',1),
('Pet Shop',1),
('Ferramentas',1),
('Jardim',1),
('Automotivo',1),
('Alimentos',1);
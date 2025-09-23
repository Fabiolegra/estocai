CREATE DATABASE IF NOT EXISTS estocaidb
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_general_ci;

USE estocaidb;

SET FOREIGN_KEY_CHECKS = 0;

CREATE TABLE IF NOT EXISTS categorias (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL UNIQUE,
    descricao TEXT DEFAULT NULL,
    criado_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS fornecedores (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(150) NOT NULL,
    contato VARCHAR(150) DEFAULT NULL,
    email VARCHAR(150) DEFAULT NULL,
    telefone VARCHAR(50) DEFAULT NULL,
    criado_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    senha VARCHAR(255) NOT NULL,
    papel VARCHAR(50) DEFAULT 'usuario',
    criado_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS produtos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(255) NOT NULL,
    descricao TEXT DEFAULT NULL,
    categoria_id INT DEFAULT NULL,
    fornecedor_id INT DEFAULT NULL,
    quantidade INT NOT NULL DEFAULT 0,
    quantidade_minima INT NOT NULL DEFAULT 0,
    preco DECIMAL(12,2) NOT NULL DEFAULT 0.00,
    criado_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    atualizado_at TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_nome (nome),
    INDEX idx_categoria (categoria_id),
    INDEX idx_fornecedor (fornecedor_id),
    CONSTRAINT fk_produtos_categoria FOREIGN KEY (categoria_id) REFERENCES categorias(id) ON DELETE SET NULL,
    CONSTRAINT fk_produtos_fornecedor FOREIGN KEY (fornecedor_id) REFERENCES fornecedores(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS movimentos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    produto_id INT NOT NULL,
    tipo ENUM('entrada','saida') NOT NULL,
    quantidade INT NOT NULL,
    observacao TEXT DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_movimentos_produto FOREIGN KEY (produto_id) REFERENCES produtos(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


INSERT INTO `produtos` (`id`, `nome`, `descricao`, `quantidade`, `quantidade_minima`, `preco`, `criado_at`) VALUES
(1, 'Monitor LED 24 polegadas', 'Monitor Full HD, 75Hz, com conexões HDMI e VGA. Marca Genérica.', 15, 5, '850.00', '2023-10-25 10:00:00'),
(2, 'Teclado Mecânico RGB', 'Teclado gamer com switches azuis, ABNT2 e iluminação RGB customizável.', 6, 5, '350.50', '2023-10-26 11:30:00'),
(3, 'Mouse Sem Fio Ergonômico', 'Mouse óptico sem fio, 1600 DPI, design ergonômico para destros.', 2, 5, '120.00', '2023-10-27 14:00:00'),
(4, 'SSD 480GB SATA III', 'Unidade de estado sólido para notebooks e desktops. Velocidade de leitura de 550MB/s.', 25, 10, '280.75', '2023-10-28 09:00:00'),
(5, 'Webcam Full HD 1080p', 'Webcam com microfone embutido, foco automático e correção de luz.', 12, 4, '199.90', '2023-10-29 16:45:00');

--
-- Populando a tabela `movimentos` com dados de exemplo
--
INSERT INTO `movimentos` (`produto_id`, `tipo`, `quantidade`, `observacao`, `created_at`) VALUES
(1, 'entrada', 20, 'Compra inicial do fornecedor A.', '2023-10-25 10:00:00'),
(1, 'saida', 5, 'Venda para cliente B.', '2023-10-26 15:00:00'),
(2, 'entrada', 10, 'Recebimento de novo lote.', '2023-10-26 11:30:00'),
(2, 'saida', 4, 'Venda online.', '2023-10-28 12:00:00'),
(3, 'entrada', 5, 'Compra inicial.', '2023-10-27 14:00:00'),
(3, 'saida', 3, 'Venda balcão.', '2023-10-29 18:00:00'),
(4, 'entrada', 25, 'Lote grande do fornecedor C.', '2023-10-28 09:00:00'),
(5, 'entrada', 15, 'Compra para Black Friday.', '2023-10-29 16:45:00'),
(5, 'saida', 3, 'Vendas da primeira semana.', '2023-11-05 11:00:00');

SET FOREIGN_KEY_CHECKS = 1; -- Reabilita a verificação de chaves estrangeiras
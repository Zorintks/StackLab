-- stacklab_users.sql
CREATE DATABASE IF NOT EXISTS stacklab;
USE stacklab;

CREATE TABLE IF NOT EXISTS users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  email VARCHAR(100) NOT NULL UNIQUE,
  username VARCHAR(50) DEFAULT NULL,
  password VARCHAR(255) NOT NULL,
  role ENUM('admin','user') DEFAULT 'admin', -- aqui deixei admin por padrão para testes
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- USUÁRIOS DE TESTE (atenção: senhas em texto claro)
INSERT INTO users (name, email, username, password, role) VALUES
('João', 'joaocraz157@gmail.com', 'joaocraz157', '@dmlab2134@', 'admin'),
('Zoro', 'zorotks@gmail.com', 'zorotks', '@dmsmb2134@', 'admin');


USE stacklab;

-- Tabela clientes
CREATE TABLE IF NOT EXISTS clients (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  email VARCHAR(100) NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabela projetos
CREATE TABLE IF NOT EXISTS projects (
  id INT AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(150) NOT NULL,
  description TEXT,
  status ENUM('ativo','concluido','pausado') DEFAULT 'ativo',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabela finanças
CREATE TABLE IF NOT EXISTS finances (
  id INT AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(150) NOT NULL,
  value DECIMAL(10,2) NOT NULL,
  type ENUM('entrada','saida') NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

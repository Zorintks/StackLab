-- stacklab_users.sql
CREATE DATABASE IF NOT EXISTS stacklab;
USE stacklab;

CREATE TABLE clientes (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nome VARCHAR(100) NOT NULL,
  tipo_projeto VARCHAR(100),
  valor DECIMAL(10,2),
  prazo DATE,
  situacao ENUM('em espera', 'em projeto', 'concluido') DEFAULT 'em espera',
  criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

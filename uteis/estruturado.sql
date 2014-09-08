CREATE DATABASE estruturado;

USE estruturado;

CREATE TABLE usuarios
(
    usuario VARCHAR(45) NOT NULL PRIMARY KEY,
    senha VARCHAR(32) NOT NULL,
    idPessoa BIGINT NOT NULL,
    situacao ENUM('true','false') NOT NULL DEFAULT 'true',
    administrador ENUM('true','false') NOT NULL 
);

CREATE TABLE pessoas
(
    idPessoa BIGINT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    nome VARCHAR(100) NOT NULL,
    endereco VARCHAR(255),
    telefone VARCHAR(16)
);

INSERT INTO pessoas ( nome, endereco, telefone ) VALUES ( 'Administrador', 'Av Porto Velhor, 2014 - Centro', '69 3441-3443');

INSERT INTO usuarios ( usuario, senha, idPessoa, situacao, administrador ) VALUES ( 'admin', md5('admin'), 1, true, true );
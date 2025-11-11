CREATE DATABASE onefeed;
USE onefeed;

CREATE TABLE Usuario (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    dataNascimento DATE NOT NULL,
    genero CHAR(1),
    email VARCHAR(100) NOT NULL UNIQUE,
    senha VARCHAR(255) NOT NULL
);

CREATE TABLE Amizade (
    id INT AUTO_INCREMENT PRIMARY KEY,
    solicitante_id INT NOT NULL, 
    receptor_id INT NOT NULL,    
    
    status ENUM('pendente', 'aceita', 'rejeitada') NOT NULL DEFAULT 'pendente',
    
    data_solicitacao DATETIME DEFAULT CURRENT_TIMESTAMP,
    data_aceite DATETIME NULL,

    FOREIGN KEY (solicitante_id) REFERENCES Usuario(id) ON DELETE CASCADE,
    FOREIGN KEY (receptor_id) REFERENCES Usuario(id) ON DELETE CASCADE,
    
    UNIQUE KEY (solicitante_id, receptor_id), 
    
    INDEX (receptor_id, status) 
);

CREATE TABLE Post (
    id INT AUTO_INCREMENT PRIMARY KEY,
    conteudo TEXT NOT NULL,
    dataHora DATETIME DEFAULT CURRENT_TIMESTAMP,
    autor_id INT NOT NULL,
    curtidas INT DEFAULT 0,
    FOREIGN KEY (autor_id) REFERENCES Usuario(id)
        ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE Comentario (
    id INT AUTO_INCREMENT PRIMARY KEY,
    conteudo TEXT NOT NULL,
    dataHora DATETIME DEFAULT CURRENT_TIMESTAMP,
    post_id INT NOT NULL,
    autor_id INT NOT NULL,
    curtidas INT DEFAULT 0,
    FOREIGN KEY (post_id) REFERENCES Post(id)
        ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (autor_id) REFERENCES Usuario(id)
        ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE Curtida (
    usuario_id INT NOT NULL,
    post_id INT NOT NULL,
    dataHora DATETIME DEFAULT CURRENT_TIMESTAMP,
    
    PRIMARY KEY (usuario_id, post_id),
    
    FOREIGN KEY (usuario_id) REFERENCES Usuario(id)
        ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (post_id) REFERENCES Post(id)
        ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE Notificacao (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    autor_id INT NOT NULL,
    post_id INT NOT NULL,
    comentario_id INT NULL,
    tipo ENUM('comentario', 'curtida') NOT NULL DEFAULT 'comentario',
    lida BOOLEAN NOT NULL DEFAULT 0,
    dataHora DATETIME DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (usuario_id) REFERENCES Usuario(id) ON DELETE CASCADE,
    FOREIGN KEY (autor_id) REFERENCES Usuario(id) ON DELETE CASCADE,
    FOREIGN KEY (post_id) REFERENCES Post(id) ON DELETE CASCADE,
    FOREIGN KEY (comentario_id) REFERENCES Comentario(id) ON DELETE SET NULL
);
CREATE DATABASE expo_tecnica;

USE expo_tecnica;

CREATE TABLE proyectos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(255) NOT NULL,
    descripcion TEXT NOT NULL,
    imagen VARCHAR(255) NOT NULL,
    votos INT DEFAULT 0
);

CREATE TABLE visit_count (
    id INT PRIMARY KEY,
    count INT DEFAULT 0
);

INSERT INTO visit_count (id, count) VALUES (1, 0);

CREATE DATABASE buzón_peticiones;

USE buzón_peticiones;

CREATE TABLE peticiones (
    id INT AUTO_INCREMENT PRIMARY KEY,
    fecha DATE NOT NULL,
    descripcion VARCHAR(255) NOT NULL,
    detalle TEXT NOT NULL
);
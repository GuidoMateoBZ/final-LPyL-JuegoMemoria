# Lista de tareas

---

# Script SQL para crear la base de datos
```sql
-- 1. Crear la base de datos y usarla
CREATE DATABASE IF NOT EXISTS juego_memoria CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish_ci;
USE juego_memoria;

-- 2. Crear tabla pais
CREATE TABLE pais (
    id_pais INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL
);

-- 3. Crear tabla usuario
CREATE TABLE usuario (
    id_usuario INT AUTO_INCREMENT PRIMARY KEY,
    nombre_usuario VARCHAR(50) NOT NULL UNIQUE,
    contrasenia VARCHAR(255) NOT NULL,
    id_pais INT,
    fecha_nacimiento DATE NOT NULL,
    FOREIGN KEY (id_pais) REFERENCES pais(id_pais) ON DELETE RESTRICT
);

-- 4. Crear tabla partida
CREATE TABLE partida (
    id_partida INT AUTO_INCREMENT PRIMARY KEY,
    fecha DATETIME NOT NULL,
    dificultad INT NOT NULL,
    tiempo_jugado TIME NOT NULL
);

-- 5. Crear tabla resultado
CREATE TABLE resultado (
    id_resultado INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL
);

-- 6. Crear tabla usuario_partida (Relación muchos a muchos)
CREATE TABLE usuario_partida (
    id_partida INT,
    id_usuario INT,
    puntaje INT NOT NULL,
    pares_descubiertos INT NOT NULL,
    intentos INT NOT NULL,
    id_resultado INT NOT NULL,
    PRIMARY KEY (id_partida, id_usuario),
    FOREIGN KEY (id_partida) REFERENCES partida(id_partida) ON DELETE CASCADE,
    FOREIGN KEY (id_usuario) REFERENCES usuario(id_usuario) ON DELETE CASCADE,
    FOREIGN KEY (id_resultado) REFERENCES resultado(id_resultado) ON DELETE RESTRICT
);

--- 7. Insertar resultados
INSERT INTO resultado (nombre) VALUES 
('Ganó'), 
('Perdió'),
('Empató'),
('Abandonó');

-- 8. Insertar países hispanohablantes
INSERT INTO pais (nombre) VALUES 
('Argentina'),
('Bolivia'),
('Chile'),
('Colombia'),
('Costa Rica'),
('Cuba'),
('Ecuador'),
('El Salvador'),
('España'),
('Guatemala'),
('Guinea Ecuatorial'),
('Honduras'),
('México'),
('Nicaragua'),
('Panamá'),
('Paraguay'),
('Perú'),
('República Dominicana'),
('Uruguay'),
('Venezuela');

```

-- =====================================================
-- 📌 Base de datos para el sistema del periódico digital
-- =====================================================

-- Usamos la base de datos "database" si ya existe
USE periodico_db;

-- =====================================================
-- 🧑 Tabla de usuarios (administradores y editores)
-- =====================================================
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    rol ENUM('admin','editor') DEFAULT 'editor',
    creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- =====================================================
-- 📰 Tabla de periódicos (artículos o ediciones subidas)
-- =====================================================
CREATE TABLE IF NOT EXISTS periodicos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(200) NOT NULL,
    director VARCHAR(100) NOT NULL,
    participantes TEXT,  -- Nueva columna para los nombres de los participantes
    descripcion TEXT,
    archivo_pdf VARCHAR(255) NOT NULL,
    publicado_en DATE, -- Cambiado a DATE para mayor flexibilidad
    usuario_id INT,
    FOREIGN KEY (usuario_id) REFERENCES users(id) ON DELETE SET NULL
);

-- =====================================================
-- 💬 Tabla de comentarios de los lectores
-- =====================================================
CREATE TABLE IF NOT EXISTS comentarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_nombre VARCHAR(100) NOT NULL,
    comentario TEXT NOT NULL,
    creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    periodico_id INT,
    FOREIGN KEY (periodico_id) REFERENCES periodicos(id) ON DELETE CASCADE
);

-- =====================================================
-- 👑 Insertar usuarios iniciales
-- Contraseñas: fabian y ingrid
-- =====================================================
INSERT INTO users (nombre, email, password, rol)
VALUES ('fabian', '1234', '$2y$10$5oZzvYAg7D2GhuF0F8o2AeL7k0VrCaSuVTO6YdZ.DG0zXn8rhcfJm', 'admin'),
       ('ingrid', '1234', '$2y$10$QSkc0FFQ3u2PjN7wI7NdIOE84Dql.3c6uFm7dMTwKo.QrC8PUGs1C', 'admin');


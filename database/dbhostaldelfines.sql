
-- -----------------------------------------------------
-- Database dbhostaldelfines
-- -----------------------------------------------------
CREATE DATABASE dbhostaldelfines;
USE dbhostaldelfines;


-- -----------------------------------------------------
-- Tabla cliente
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS cliente (
  id INT AUTO_INCREMENT PRIMARY KEY,
  dni VARCHAR(9) NOT NULL UNIQUE,
  nombre VARCHAR(255) NOT NULL,
  apellido VARCHAR(255) NOT NULL
);


-- -----------------------------------------------------
-- Tabla habitacion
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS habitacion (
  id INT AUTO_INCREMENT PRIMARY KEY,
  numero INT NOT NULL,
  numero_camas INT NOT NULL,
  ventana BIT(1) NOT NULL,
  disponible BIT(1) NOT NULL
);


-- -----------------------------------------------------
-- Tabla empleado
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS empleado (
  id INT AUTO_INCREMENT PRIMARY KEY,
  dni VARCHAR(9) NOT NULL UNIQUE,
  nombre VARCHAR(255) NOT NULL,
  apellido VARCHAR(255) NOT NULL,
  username VARCHAR(255) NOT NULL,
  clave VARCHAR(255) NOT NULL
);


-- -----------------------------------------------------
-- Tabla producto
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS producto (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(255) NOT NULL,
  stock INT NOT NULL,
  precio_venta DECIMAL(11,2) NOT NULL,
  precio_compra DECIMAL(11,2) NOT NULL,
  categoria ENUM("USO_PERSONAL", "ALIMENTOS") NOT NULL
);


-- -----------------------------------------------------
-- Tabla proveedor
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS proveedor (
  id INT AUTO_INCREMENT PRIMARY KEY,
  ruc VARCHAR(11) UNIQUE NOT NULL,
  razon_social VARCHAR(255) NOT NULL
);


-- -----------------------------------------------------
-- Tabla registro_reserva
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS registro_reserva (
  id INT AUTO_INCREMENT PRIMARY KEY,
  empleado_id INT NOT NULL,
  fecha DATE NOT NULL,
  turno ENUM("MANANA", "TARDE", "NOCHE") NOT NULL,
  liquidacion DECIMAL(11,2) NULL,
  FOREIGN KEY (empleado_id) REFERENCES empleado(id)
);


-- -----------------------------------------------------
-- Tabla registro_compra
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS registro_compra (
  id INT AUTO_INCREMENT PRIMARY KEY,
  empleado_id INT NOT NULL,
  fecha DATE NOT NULL,
  turno ENUM("MANANA", "TARDE", "NOCHE") NOT NULL,
  FOREIGN KEY (empleado_id) REFERENCES empleado(id)
);


-- -----------------------------------------------------
-- Tabla registro_venta
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS registro_venta (
  id INT AUTO_INCREMENT PRIMARY KEY,
  empleado_id INT NOT NULL,
  fecha DATE NOT NULL,
  turno ENUM("MANANA", "TARDE", "NOCHE") NOT NULL,
  liquidacion DECIMAL(11,2) NULL,
  FOREIGN KEY (empleado_id) REFERENCES empleado(id)
);


-- -----------------------------------------------------
-- Tabla venta
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS venta (
  id INT AUTO_INCREMENT PRIMARY KEY,
  registro_venta_id INT NOT NULL,
  FOREIGN KEY (registro_venta_id) REFERENCES registro_venta(id)
);


-- -----------------------------------------------------
-- Tabla compra
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS compra (
  id INT AUTO_INCREMENT PRIMARY KEY,
  registro_compra_id INT NOT NULL,
  FOREIGN KEY (registro_compra_id) REFERENCES registro_compra(id)
);


-- -----------------------------------------------------
-- Tabla linea_venta
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS linea_venta (
  id INT AUTO_INCREMENT PRIMARY KEY,
  venta_id INT NOT NULL,
  producto_id INT NOT NULL,
  precio_unitario DECIMAL(11,2) NOT NULL,
  cantidad INT NOT NULL,
  FOREIGN KEY (venta_id) REFERENCES venta(id),
  FOREIGN KEY (producto_id) REFERENCES producto(id)
);


-- -----------------------------------------------------
-- Tabla linea_compra
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS linea_compra (
  id INT AUTO_INCREMENT PRIMARY KEY,
  compra_id INT NOT NULL,
  producto_id INT NOT NULL,
  precio_unitario DECIMAL(11,2) NOT NULL,
  cantidad INT NOT NULL,
  FOREIGN KEY (compra_id) REFERENCES compra(id),
  FOREIGN KEY (producto_id) REFERENCES producto(id)
);


-- -----------------------------------------------------
-- Tabla reserva
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS reserva (
  id INT AUTO_INCREMENT PRIMARY KEY,
  numero_huespedes INT NULL,
  costo_efectivo DECIMAL(11,2) NULL,
  costo_yape DECIMAL(11,2) NULL,
  horas_reservadas INT NULL,
  estado_reserva ENUM("CONFIRMADO", "PENDIENTE", "CANCELADO") NULL,
  hora_ingreso TIME NULL,
  hora_salida TIME NULL,
  registro_reserva_id INT NOT NULL,
  cliente_id INT NOT NULL,
  habitacion_id INT NOT NULL,
  FOREIGN KEY (registro_reserva_id) REFERENCES registro_reserva(id),
  FOREIGN KEY (cliente_id) REFERENCES cliente(id),
  FOREIGN KEY (habitacion_id) REFERENCES habitacion(id)
);


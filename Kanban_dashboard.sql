-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Versión del servidor:         8.4.3 - MySQL Community Server - GPL
-- SO del servidor:              Win64
-- HeidiSQL Versión:             12.8.0.6908
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Volcando estructura de base de datos para kanban_dashboard
CREATE DATABASE IF NOT EXISTS `kanban_dashboard` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `kanban_dashboard`;

-- Volcando estructura para tabla kanban_dashboard.auth
CREATE TABLE IF NOT EXISTS `auth` (
  `id` int NOT NULL AUTO_INCREMENT,
  `isAdminLoggedIn` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=84 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Volcando datos para la tabla kanban_dashboard.auth: ~67 rows (aproximadamente)
INSERT INTO `auth` (`id`, `isAdminLoggedIn`) VALUES
	(1, 0),
	(2, 1),
	(3, 1),
	(4, 1),
	(5, 1),
	(6, 1),
	(7, 0),
	(8, 0),
	(9, 1),
	(10, 1),
	(11, 1),
	(12, 1),
	(13, 1),
	(14, 1),
	(15, 1),
	(16, 1),
	(17, 0),
	(18, 0),
	(19, 1),
	(20, 0),
	(21, 0),
	(22, 1),
	(23, 1),
	(24, 1),
	(25, 0),
	(26, 0),
	(27, 1),
	(28, 0),
	(29, 0),
	(30, 1),
	(31, 0),
	(32, 0),
	(33, 1),
	(34, 0),
	(35, 0),
	(36, 1),
	(37, 0),
	(38, 0),
	(39, 1),
	(40, 0),
	(41, 0),
	(42, 1),
	(43, 0),
	(44, 0),
	(45, 1),
	(46, 0),
	(47, 0),
	(48, 1),
	(49, 0),
	(50, 0),
	(51, 1),
	(52, 0),
	(53, 0),
	(54, 1),
	(55, 0),
	(56, 0),
	(57, 1),
	(58, 0),
	(59, 0),
	(60, 1),
	(61, 0),
	(62, 0),
	(63, 1),
	(64, 0),
	(65, 0),
	(66, 1),
	(67, 0),
	(68, 0),
	(69, 1),
	(70, 0),
	(71, 0),
	(72, 1),
	(73, 0),
	(74, 0),
	(75, 1),
	(76, 0),
	(77, 0),
	(78, 1),
	(79, 0),
	(80, 0),
	(81, 1),
	(82, 0),
	(83, 0);

-- Volcando estructura para tabla kanban_dashboard.projects
CREATE TABLE IF NOT EXISTS `projects` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `desc` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
  `userId` int DEFAULT NULL,
  `start` date DEFAULT NULL,
  `end` date DEFAULT NULL,
  `progress` int DEFAULT NULL,
  `status` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `userId` (`userId`) USING BTREE,
  CONSTRAINT `projects_ibfk_1` FOREIGN KEY (`userId`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Volcando datos para la tabla kanban_dashboard.projects: ~3 rows (aproximadamente)
INSERT INTO `projects` (`id`, `name`, `desc`, `userId`, `start`, `end`, `progress`, `status`) VALUES
	(12, 'Racionalización de tramites', 'recolección de datos de operación de las dependencias', 14, '2025-05-25', '2025-07-12', 80, 'todo'),
	(13, 'Redes Wifi', 'Instalación de wifi para espacios abiertos', 22, '2025-05-18', '2025-06-06', 10, 'completed'),
	(14, 'V6', 'Integracion del nuevo modulo para pagos en linea', 23, '2025-05-01', '2025-06-15', 60, 'in-progress');

-- Volcando estructura para tabla kanban_dashboard.users
CREATE TABLE IF NOT EXISTS `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Volcando datos para la tabla kanban_dashboard.users: ~8 rows (aproximadamente)
INSERT INTO `users` (`id`, `name`) VALUES
	(14, 'Diana Sánchez'),
	(16, 'Norby Escobar'),
	(17, 'Angela Valencia'),
	(18, 'Marleny Ramírez'),
	(19, 'Oscar Guio'),
	(20, 'Darwin Vélez'),
	(21, 'German Tilmans'),
	(22, 'Crhistian Muñoz'),
	(23, 'Felipe Bolaños');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;

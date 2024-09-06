-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Versión del servidor:         8.0.30 - MySQL Community Server - GPL
-- SO del servidor:              Win64
-- HeidiSQL Versión:             12.1.0.6537
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Volcando estructura de base de datos para urbes_db
CREATE DATABASE IF NOT EXISTS `urbes_db` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `urbes_db`;

-- Volcando estructura para tabla urbes_db.actividades
CREATE TABLE IF NOT EXISTS `actividades` (
  `id_actividad` int NOT NULL AUTO_INCREMENT,
  `actividad` varchar(100) NOT NULL,
  `fecha_registro` datetime DEFAULT NULL,
  `fecha_actualizacion` datetime DEFAULT NULL,
  PRIMARY KEY (`id_actividad`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Volcando datos para la tabla urbes_db.actividades: ~1 rows (aproximadamente)
INSERT INTO `actividades` (`id_actividad`, `actividad`, `fecha_registro`, `fecha_actualizacion`) VALUES
	(2, 'Mecanica Vehiculo Compactador', '2024-09-06 00:33:53', '2024-09-06 00:38:58');

-- Volcando estructura para tabla urbes_db.estados
CREATE TABLE IF NOT EXISTS `estados` (
  `id_estado` int NOT NULL AUTO_INCREMENT,
  `estado` varchar(200) COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id_estado`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Volcando datos para la tabla urbes_db.estados: ~2 rows (aproximadamente)
INSERT INTO `estados` (`id_estado`, `estado`) VALUES
	(1, 'activo'),
	(2, 'inactivo'),
	(3, 'eliminado');

-- Volcando estructura para tabla urbes_db.intentos_fallidos
CREATE TABLE IF NOT EXISTS `intentos_fallidos` (
  `id_intento` int NOT NULL AUTO_INCREMENT,
  `documento` bigint NOT NULL,
  `fecha_intento` date NOT NULL,
  PRIMARY KEY (`id_intento`)
) ENGINE=InnoDB AUTO_INCREMENT=43 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Volcando datos para la tabla urbes_db.intentos_fallidos: ~0 rows (aproximadamente)

-- Volcando estructura para tabla urbes_db.tipo_usuario
CREATE TABLE IF NOT EXISTS `tipo_usuario` (
  `id_tipo_usuario` int NOT NULL AUTO_INCREMENT,
  `tipo_usuario` varchar(200) COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id_tipo_usuario`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Volcando datos para la tabla urbes_db.tipo_usuario: ~3 rows (aproximadamente)
INSERT INTO `tipo_usuario` (`id_tipo_usuario`, `tipo_usuario`) VALUES
	(1, 'admin'),
	(2, 'socio'),
	(3, 'empleado');

-- Volcando estructura para tabla urbes_db.usuarios
CREATE TABLE IF NOT EXISTS `usuarios` (
  `documento` bigint NOT NULL,
  `tipo_documento` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `nombres` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `apellidos` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `celular` varchar(20) COLLATE utf8mb4_general_ci NOT NULL,
  `celular_familiar` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `parentezco_familiar` varchar(200) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `nombre_familiar` varchar(200) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `id_tipo_usuario` int NOT NULL,
  `id_estado` int NOT NULL,
  `fecha_registro` datetime DEFAULT NULL,
  `fecha_actualizacion` datetime DEFAULT NULL,
  `eps` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `arl` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`documento`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Volcando datos para la tabla urbes_db.usuarios: ~4 rows (aproximadamente)
INSERT INTO `usuarios` (`documento`, `tipo_documento`, `nombres`, `apellidos`, `celular`, `celular_familiar`, `parentezco_familiar`, `nombre_familiar`, `password`, `id_tipo_usuario`, `id_estado`, `fecha_registro`, `fecha_actualizacion`, `eps`, `arl`) VALUES
	(99464482, 'C.C.', 'Daniel', 'Alvarez', '3122402301', NULL, NULL, NULL, 'OEw1Y0hSa2N1UTk1TXlyNllVbzRwQT09Ojo0pjEqECeWwDBgsVntmGba', 2, 1, '2024-09-04 23:39:15', NULL, NULL, NULL),
	(1008240120, 'C.C.', 'Antonio', 'Carvajal', '3201201340', '3124201390', 'Padre', 'Antonio Carvajal', 'UHZ2ZGNpNExtSmJraWR4MVVnUHlxQT09OjqR/DQFa3BvhG/POFjSyA04', 3, 1, '2024-09-04 23:04:03', NULL, 'EPS', 'ARL'),
	(1110460240, 'C.C.', 'Valentina', 'Castro', '3105853658', '3104502302', 'Tio', 'Albeiro', 'c2NHNXc3bkVTS3ZkUC92azJlemRodz09OjonnZxLuniKofAg2y6PfRRE', 3, 1, '2024-09-04 21:23:10', '2024-09-04 23:53:10', 'Nueva EPS', 'Nueva EPS'),
	(1110460410, 'C.C.', 'Administrador', 'Urbes', '3105853668', NULL, NULL, NULL, 'aVo0U0dNRGx4UEpURkZCRGwzaktCdz09Ojr5eAxR2rNIqxqkd5oagI2G', 1, 1, '2024-03-09 15:26:38', '2024-09-04 11:54:19', NULL, NULL);

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;

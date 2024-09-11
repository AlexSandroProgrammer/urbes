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
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Volcando datos para la tabla urbes_db.actividades: ~0 rows (aproximadamente)
INSERT INTO `actividades` (`id_actividad`, `actividad`, `fecha_registro`, `fecha_actualizacion`) VALUES
	(2, 'Mecánica Vehículo Compactador', '2024-09-06 00:33:53', '2024-09-07 15:16:44'),
	(3, 'Areas Publicas', '2024-09-11 00:09:15', NULL),
	(4, 'Vehiculo Compactador', '2024-09-11 00:10:11', NULL),
	(5, 'Carro Barrido', '2024-09-11 00:10:25', NULL);

-- Volcando estructura para tabla urbes_db.ciudades
CREATE TABLE IF NOT EXISTS `ciudades` (
  `id_ciudad` int NOT NULL AUTO_INCREMENT,
  `ciudad` varchar(20) NOT NULL,
  `fecha_registro` datetime DEFAULT NULL,
  `fecha_actualizacion` datetime DEFAULT NULL,
  PRIMARY KEY (`id_ciudad`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Volcando datos para la tabla urbes_db.ciudades: ~2 rows (aproximadamente)
INSERT INTO `ciudades` (`id_ciudad`, `ciudad`, `fecha_registro`, `fecha_actualizacion`) VALUES
	(1, 'Mariquita', '2024-09-07 16:42:03', '2024-09-08 18:17:37'),
	(2, 'Sevilla', '2024-09-08 18:19:04', '2024-09-08 19:43:52');

-- Volcando estructura para tabla urbes_db.estados
CREATE TABLE IF NOT EXISTS `estados` (
  `id_estado` int NOT NULL AUTO_INCREMENT,
  `estado` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id_estado`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Volcando datos para la tabla urbes_db.estados: ~3 rows (aproximadamente)
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

-- Volcando estructura para tabla urbes_db.labores
CREATE TABLE IF NOT EXISTS `labores` (
  `id_labor` int NOT NULL AUTO_INCREMENT,
  `labor` varchar(300) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `fecha_registro` datetime DEFAULT NULL,
  `fecha_actualizacion` datetime DEFAULT NULL,
  `id_actividad` int NOT NULL,
  PRIMARY KEY (`id_labor`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Volcando datos para la tabla urbes_db.labores: ~3 rows (aproximadamente)
INSERT INTO `labores` (`id_labor`, `labor`, `fecha_registro`, `fecha_actualizacion`, `id_actividad`) VALUES
	(4, 'Recoleccion', '2024-09-10 19:27:32', '2024-09-11 00:13:00', 4),
	(5, 'Recoleccion Disposicion al relleno', '2024-09-11 00:11:37', '2024-09-11 00:21:02', 4);

-- Volcando estructura para tabla urbes_db.registro_actividades
CREATE TABLE IF NOT EXISTS `registro_actividades` (
  `id_registro` int NOT NULL AUTO_INCREMENT,
  `fecha_inicio` date DEFAULT NULL,
  `fecha_fin` date DEFAULT NULL,
  `hora_inicio` time DEFAULT NULL,
  `hora_finalizacion` time DEFAULT NULL,
  `km_inicio` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT '',
  `km_fin` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT '',
  `horometro_inicio` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT '',
  `horometro_fin` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT '',
  `id_actividad` int DEFAULT NULL,
  `id_vehiculos` int DEFAULT NULL,
  PRIMARY KEY (`id_registro`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Volcando datos para la tabla urbes_db.registro_actividades: ~0 rows (aproximadamente)

-- Volcando estructura para tabla urbes_db.tipo_usuario
CREATE TABLE IF NOT EXISTS `tipo_usuario` (
  `id_tipo_usuario` int NOT NULL AUTO_INCREMENT,
  `tipo_usuario` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
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
  `nombres` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `apellidos` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `celular` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `celular_familiar` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `parentezco_familiar` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `nombre_familiar` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `id_tipo_usuario` int NOT NULL,
  `id_estado` int NOT NULL,
  `fecha_registro` datetime DEFAULT NULL,
  `fecha_actualizacion` datetime DEFAULT NULL,
  `eps` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `arl` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `id_ciudad` int DEFAULT NULL,
  `fecha_inicio` date DEFAULT NULL,
  `fecha_fin` date DEFAULT NULL,
  `confi_conductor` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `rh` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`documento`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Volcando datos para la tabla urbes_db.usuarios: ~3 rows (aproximadamente)
INSERT INTO `usuarios` (`documento`, `tipo_documento`, `nombres`, `apellidos`, `celular`, `celular_familiar`, `parentezco_familiar`, `nombre_familiar`, `password`, `id_tipo_usuario`, `id_estado`, `fecha_registro`, `fecha_actualizacion`, `eps`, `arl`, `id_ciudad`, `fecha_inicio`, `fecha_fin`, `confi_conductor`, `rh`) VALUES
	(79464482, 'C.C.', 'Valentina ', 'Lopez', '3212402301', '3122402340', 'Tio', 'Albeiro Mejia', 'QWEybktYbmFTalpJb0xRS3RWaXdrZz09OjpmZQakYJo2jczWSYtM//rc', 3, 1, '2024-09-10 13:46:06', '2024-09-10 16:10:43', 'Nueva EPS', 'Nueva ARL', 1, '2024-09-14', '2024-10-31', 'NO', 'O+'),
	(99464482, 'C.C.', 'Daniel', 'Alvarez', '3122402301', NULL, NULL, NULL, 'OEw1Y0hSa2N1UTk1TXlyNllVbzRwQT09Ojo0pjEqECeWwDBgsVntmGba', 3, 1, '2024-09-04 23:39:15', NULL, NULL, NULL, 1, NULL, NULL, 'NO', NULL),
	(1110460410, 'C.C.', 'Administrador', 'Urbes', '3105853668', NULL, NULL, NULL, 'd29nSzdrL2RrM29WSzdrZ2lqbmVDUT09Ojo1SYdh7gWu86U1PrAi4Ey9', 1, 1, '2024-03-09 15:26:38', '2024-09-04 11:54:19', NULL, NULL, 1, NULL, NULL, NULL, NULL),
	(1112024023, 'C.C.', 'Amparo', 'Garcia', '3234501650', '3129978120', 'Mamam', 'Alexandra Coro', 'Z2lOMGJoUERyeitDYmtPbzhwVDdSUT09OjrcQH+siKNKyJwStz5D8Xgp', 3, 1, '2024-09-10 19:36:53', '2024-09-10 19:51:18', 'Salud Total', 'Salud ARL', 1, '2024-09-21', '2024-09-27', 'NO', 'O+');

-- Volcando estructura para tabla urbes_db.vehiculos
CREATE TABLE IF NOT EXISTS `vehiculos` (
  `placa` varchar(50) NOT NULL,
  `vehiculo` varchar(50) NOT NULL,
  `fecha_registro` datetime DEFAULT NULL,
  `fecha_actualizacion` datetime DEFAULT NULL,
  PRIMARY KEY (`placa`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Volcando datos para la tabla urbes_db.vehiculos: ~0 rows (aproximadamente)
INSERT INTO `vehiculos` (`placa`, `vehiculo`, `fecha_registro`, `fecha_actualizacion`) VALUES
	('HNT426', 'Compactador', '2024-09-09 12:51:09', '2024-09-09 12:51:22');

-- Volcando estructura para tabla urbes_db.zonas
CREATE TABLE IF NOT EXISTS `zonas` (
  `id_zona` int NOT NULL AUTO_INCREMENT,
  `zona` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `fecha_registro` datetime DEFAULT NULL,
  `fecha_actualizacion` datetime DEFAULT NULL,
  PRIMARY KEY (`id_zona`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Volcando datos para la tabla urbes_db.zonas: ~2 rows (aproximadamente)
INSERT INTO `zonas` (`id_zona`, `zona`, `fecha_registro`, `fecha_actualizacion`) VALUES
	(1, '102', '2024-09-09 23:11:07', '2024-09-09 23:12:56'),
	(2, '101', '2024-09-09 23:13:13', '2024-09-09 23:13:21');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;

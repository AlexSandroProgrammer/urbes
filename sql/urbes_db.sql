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
  `id_actividad` tinyint NOT NULL AUTO_INCREMENT,
  `actividad` varchar(100) NOT NULL,
  `fecha_registro` datetime DEFAULT NULL,
  `fecha_actualizacion` datetime DEFAULT NULL,
  PRIMARY KEY (`id_actividad`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Volcando datos para la tabla urbes_db.actividades: ~5 rows (aproximadamente)
INSERT INTO `actividades` (`id_actividad`, `actividad`, `fecha_registro`, `fecha_actualizacion`) VALUES
	(2, 'Mecánica Vehículo Compactador', '2024-09-06 00:33:53', '2024-09-07 15:16:44'),
	(3, 'Areas Publicas', '2024-09-11 00:09:15', NULL),
	(4, 'Vehiculo Compactador', '2024-09-11 00:10:11', NULL),
	(5, 'Carro Barrido', '2024-09-11 00:10:25', NULL),
	(6, 'Aforos', '2024-09-26 18:17:01', NULL);

-- Volcando estructura para tabla urbes_db.aforos
CREATE TABLE IF NOT EXISTS `aforos` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `matricula_empresa` int NOT NULL,
  `fecha_registro` date NOT NULL,
  `peso` decimal(10,2) NOT NULL DEFAULT '0.00',
  `foto` varchar(300) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `documento` bigint NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=36 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Volcando datos para la tabla urbes_db.aforos: ~0 rows (aproximadamente)

-- Volcando estructura para tabla urbes_db.areas_publicas
CREATE TABLE IF NOT EXISTS `areas_publicas` (
  `id_registro` bigint NOT NULL AUTO_INCREMENT,
  `documento` bigint DEFAULT NULL,
  `id_ciudad` smallint DEFAULT NULL,
  `hora_inicio` time DEFAULT NULL,
  `hora_finalizacion` time DEFAULT NULL,
  `fecha_inicio` date DEFAULT NULL,
  `fecha_finalizacion` date DEFAULT NULL,
  `fecha_registro` datetime DEFAULT NULL,
  `fecha_actualizacion` datetime DEFAULT NULL,
  `id_labor` smallint DEFAULT NULL,
  `peso` varchar(50) DEFAULT NULL,
  `id_estado` smallint DEFAULT NULL,
  `observaciones` varchar(500) DEFAULT NULL,
  PRIMARY KEY (`id_registro`)
) ENGINE=InnoDB AUTO_INCREMENT=37 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Volcando datos para la tabla urbes_db.areas_publicas: ~0 rows (aproximadamente)

-- Volcando estructura para tabla urbes_db.carro_barrido
CREATE TABLE IF NOT EXISTS `carro_barrido` (
  `id_registro_barrido` bigint NOT NULL AUTO_INCREMENT,
  `fecha_inicio` date DEFAULT NULL,
  `fecha_fin` date DEFAULT NULL,
  `hora_inicio` time DEFAULT NULL,
  `documento` bigint DEFAULT NULL,
  `id_actividad` smallint DEFAULT NULL,
  `id_estado` smallint DEFAULT NULL,
  `fecha_registro` datetime DEFAULT NULL,
  `fecha_actualizacion` datetime DEFAULT NULL,
  `observaciones` varchar(500) DEFAULT NULL,
  `ciudad` smallint DEFAULT NULL,
  `peso` varchar(20) DEFAULT NULL,
  `hora_fin` time DEFAULT NULL,
  PRIMARY KEY (`id_registro_barrido`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Volcando datos para la tabla urbes_db.carro_barrido: ~0 rows (aproximadamente)

-- Volcando estructura para tabla urbes_db.ciudades
CREATE TABLE IF NOT EXISTS `ciudades` (
  `id_ciudad` smallint NOT NULL AUTO_INCREMENT,
  `ciudad` varchar(20) NOT NULL,
  `fecha_registro` datetime DEFAULT NULL,
  `fecha_actualizacion` datetime DEFAULT NULL,
  PRIMARY KEY (`id_ciudad`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Volcando datos para la tabla urbes_db.ciudades: ~2 rows (aproximadamente)
INSERT INTO `ciudades` (`id_ciudad`, `ciudad`, `fecha_registro`, `fecha_actualizacion`) VALUES
	(1, 'Mariquita', '2024-09-07 16:42:03', '2024-09-08 18:17:37'),
	(2, 'Sevilla', '2024-09-08 18:19:04', '2024-09-08 19:43:52');

-- Volcando estructura para tabla urbes_db.detalle_tripulacion
CREATE TABLE IF NOT EXISTS `detalle_tripulacion` (
  `id_detalle` bigint NOT NULL AUTO_INCREMENT,
  `documento` bigint NOT NULL,
  `id_registro` bigint NOT NULL,
  PRIMARY KEY (`id_detalle`)
) ENGINE=InnoDB AUTO_INCREMENT=107 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Volcando datos para la tabla urbes_db.detalle_tripulacion: ~0 rows (aproximadamente)

-- Volcando estructura para tabla urbes_db.detalle_zonas
CREATE TABLE IF NOT EXISTS `detalle_zonas` (
  `id_detalle_zona` bigint NOT NULL AUTO_INCREMENT,
  `id_registro` bigint NOT NULL,
  `id_zona` int NOT NULL,
  PRIMARY KEY (`id_detalle_zona`)
) ENGINE=InnoDB AUTO_INCREMENT=46 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Volcando datos para la tabla urbes_db.detalle_zonas: ~0 rows (aproximadamente)

-- Volcando estructura para tabla urbes_db.empresas
CREATE TABLE IF NOT EXISTS `empresas` (
  `matricula` int NOT NULL,
  `nombre_empresa` varchar(50) NOT NULL DEFAULT '',
  `frecuencia` varchar(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`matricula`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Volcando datos para la tabla urbes_db.empresas: ~11 rows (aproximadamente)
INSERT INTO `empresas` (`matricula`, `nombre_empresa`, `frecuencia`) VALUES
	(80, 'Merca Max', '2 veces por semana'),
	(2235, 'La Cosecha Fruver', '2 veces por semana '),
	(3570, 'Panaderia el Nectar', '3 veces por semana'),
	(3612, 'Hotel Cerro Dorado', '2 veces al mes'),
	(5046, 'Tiendas ARA', 'Diaria'),
	(5048, 'Panaderia San Sebastian', 'Diaria'),
	(7170, 'Restaurante Santa Clara', 'Diaria'),
	(9077, 'T.G.I', '2 veces al mes'),
	(10169, 'Supermercado la Cosecha', 'Diaria'),
	(11343, 'Gaseosa Cordoba', '2 veces por semana '),
	(12304, 'Turgas SA', '2 veces al mes');

-- Volcando estructura para tabla urbes_db.estados
CREATE TABLE IF NOT EXISTS `estados` (
  `id_estado` smallint NOT NULL AUTO_INCREMENT,
  `estado` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id_estado`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Volcando datos para la tabla urbes_db.estados: ~5 rows (aproximadamente)
INSERT INTO `estados` (`id_estado`, `estado`) VALUES
	(1, 'activo'),
	(2, 'inactivo'),
	(3, 'eliminado'),
	(4, 'Pendiente'),
	(5, 'Finalizado');

-- Volcando estructura para tabla urbes_db.intentos_fallidos
CREATE TABLE IF NOT EXISTS `intentos_fallidos` (
  `id_intento` int NOT NULL AUTO_INCREMENT,
  `documento` bigint NOT NULL,
  `fecha_intento` date NOT NULL,
  PRIMARY KEY (`id_intento`)
) ENGINE=InnoDB AUTO_INCREMENT=48 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Volcando datos para la tabla urbes_db.intentos_fallidos: ~0 rows (aproximadamente)

-- Volcando estructura para tabla urbes_db.labores
CREATE TABLE IF NOT EXISTS `labores` (
  `id_labor` smallint NOT NULL AUTO_INCREMENT,
  `labor` varchar(300) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `fecha_registro` datetime DEFAULT NULL,
  `fecha_actualizacion` datetime DEFAULT NULL,
  `id_actividad` int NOT NULL,
  PRIMARY KEY (`id_labor`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Volcando datos para la tabla urbes_db.labores: ~5 rows (aproximadamente)
INSERT INTO `labores` (`id_labor`, `labor`, `fecha_registro`, `fecha_actualizacion`, `id_actividad`) VALUES
	(4, 'Recoleccion', '2024-09-10 19:27:32', '2024-09-11 00:13:00', 4),
	(5, 'Disposicion al relleno', '2024-09-11 00:11:37', '2024-09-11 00:21:02', 4),
	(6, 'Poda de Cesped', '2024-09-13 10:41:04', NULL, 3),
	(7, 'Poda de Arboles', '2024-09-13 10:41:26', NULL, 3),
	(8, 'Lavado Areas Publicas', '2024-09-13 10:42:59', NULL, 3);

-- Volcando estructura para tabla urbes_db.mecanica
CREATE TABLE IF NOT EXISTS `mecanica` (
  `id_registro` bigint NOT NULL AUTO_INCREMENT,
  `documento` bigint DEFAULT NULL,
  `id_vehiculo` varchar(50) DEFAULT NULL,
  `hora_inicio` time DEFAULT NULL,
  `hora_finalizacion` time DEFAULT NULL,
  `fecha_inicio` date DEFAULT NULL,
  `fecha_fin` date DEFAULT NULL,
  `fecha_registro` datetime DEFAULT NULL,
  `fecha_actualizacion` datetime DEFAULT NULL,
  `id_estado` smallint DEFAULT NULL,
  `observaciones` varchar(500) DEFAULT NULL,
  `labor_mantenimiento` varchar(500) DEFAULT NULL,
  `id_actividad` smallint DEFAULT NULL,
  PRIMARY KEY (`id_registro`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Volcando datos para la tabla urbes_db.mecanica: ~0 rows (aproximadamente)

-- Volcando estructura para tabla urbes_db.recoleccion_relleno
CREATE TABLE IF NOT EXISTS `recoleccion_relleno` (
  `id_recoleccion` bigint NOT NULL AUTO_INCREMENT,
  `fecha_inicio` date DEFAULT NULL,
  `fecha_fin` date DEFAULT NULL,
  `hora_inicio` time DEFAULT NULL,
  `hora_finalizacion` time DEFAULT NULL,
  `km_inicio` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `km_fin` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `foto_kilometraje_inicial` varchar(300) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `foto_kilometraje_final` varchar(300) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `horometro_inicio` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `horometro_fin` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `id_labor` smallint DEFAULT NULL,
  `id_vehiculo` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `documento` bigint DEFAULT NULL,
  `id_estado` smallint DEFAULT NULL,
  `fecha_registro` datetime DEFAULT NULL,
  `observaciones` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `fecha_actualizacion` datetime DEFAULT NULL,
  `ciudad` smallint DEFAULT NULL,
  `toneladas` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `galones` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id_recoleccion`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Volcando datos para la tabla urbes_db.recoleccion_relleno: ~0 rows (aproximadamente)

-- Volcando estructura para tabla urbes_db.reporte_mensual
CREATE TABLE IF NOT EXISTS `reporte_mensual` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `matricula_empresa` smallint NOT NULL DEFAULT '0',
  `mes` smallint NOT NULL DEFAULT '0',
  `anio` smallint NOT NULL DEFAULT '0',
  `peso_total` decimal(10,2) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Volcando datos para la tabla urbes_db.reporte_mensual: ~0 rows (aproximadamente)

-- Volcando estructura para tabla urbes_db.tipo_usuario
CREATE TABLE IF NOT EXISTS `tipo_usuario` (
  `id_tipo_usuario` smallint NOT NULL AUTO_INCREMENT,
  `tipo_usuario` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id_tipo_usuario`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Volcando datos para la tabla urbes_db.tipo_usuario: ~4 rows (aproximadamente)
INSERT INTO `tipo_usuario` (`id_tipo_usuario`, `tipo_usuario`) VALUES
	(1, 'admin'),
	(2, 'administrativos'),
	(3, 'empleado'),
	(4, 'conductor');

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
  `id_tipo_usuario` smallint NOT NULL DEFAULT '0',
  `id_estado` smallint NOT NULL DEFAULT '0',
  `fecha_registro` datetime DEFAULT NULL,
  `fecha_actualizacion` datetime DEFAULT NULL,
  `eps` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `arl` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `id_ciudad` smallint DEFAULT NULL,
  `fecha_inicio` date DEFAULT NULL,
  `fecha_fin` date DEFAULT NULL,
  `rh` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`documento`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Volcando datos para la tabla urbes_db.usuarios: ~7 rows (aproximadamente)
INSERT INTO `usuarios` (`documento`, `tipo_documento`, `nombres`, `apellidos`, `celular`, `celular_familiar`, `parentezco_familiar`, `nombre_familiar`, `password`, `id_tipo_usuario`, `id_estado`, `fecha_registro`, `fecha_actualizacion`, `eps`, `arl`, `id_ciudad`, `fecha_inicio`, `fecha_fin`, `rh`) VALUES
	(79464482, 'C.C.', 'Valentina ', 'Lopez', '3212402301', '3122402340', 'Tio', 'Albeiro Mejia', 'QWEybktYbmFTalpJb0xRS3RWaXdrZz09OjpmZQakYJo2jczWSYtM//rc', 3, 1, '2024-09-10 13:46:06', '2024-09-10 16:10:43', 'Nueva EPS', 'Nueva ARL', 1, '2024-09-14', '2024-10-31', 'O+'),
	(79954670, 'C.C.', 'Ricardo', 'Tavera Hincapie', '3044053234', NULL, NULL, NULL, 'SnFTUDRwd1l5ejhhZ1F6dk5pNnBNdz09Ojo7OQqeef0C//Is5/m3/a65', 1, 1, '2024-09-24 15:48:06', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(1004230111, 'C.C.', 'Martha', 'Flior', '3203401009', '3127890230', 'Castro', 'Jairo', 'd0tySTQwUDVhKy9SV28xVGZuM2Mrdz09OjrmnXgx/eYqY6TqEFKAiZOH', 4, 1, '2024-09-12 09:15:16', '2024-09-13 00:40:08', 'NuevaEPS', 'Nueva ARL', 1, '2024-09-14', '2024-08-24', 'B+'),
	(1023210978, 'C.C.', 'Mariana', 'Castro', '3110034010', '3402301230', 'Papa', 'Armando Castro', 'dWJLeHg4VjRiLzcwRktyY1p1cU5JZz09Ojpbh309eR+TT/JWnnt0AmzW', 3, 1, '2024-09-12 08:55:21', NULL, 'Salud Total', 'ARL Nueva', 1, '2024-09-13', '2027-04-10', 'B+'),
	(1108123450, 'C.C.', 'Jaime', 'Orduz', '3153402301', '3051202301', 'Mama', 'Magdalena Orduz', 'eWl1SURxTHBGT0FKNlZRSEwzZnhPUT09OjpCZAhj1k1jH+he9sHiXIEN', 3, 1, '2024-09-12 08:51:44', '2024-09-26 11:40:55', 'Nueva EPS', 'Nueva EPS', 1, '2024-09-13', '2026-07-18', 'O+'),
	(1110460410, 'C.C.', 'Administrador', 'Urbes', '3105853668', NULL, NULL, NULL, 'WGVGbUN2QnZuSExsemg5QjdFa1BVUT09OjoZN8tnbOc3uZPY4PrhEmk8', 1, 1, '2024-03-09 15:26:38', '2024-09-20 15:57:09', NULL, NULL, 1, '2024-09-12', '2025-02-12', ''),
	(1111195256, 'C.C.', 'luis carlos', 'ramirez', '3126791860', '3222347725', 'Hijo', 'Dawis sebastian', 'OUp6dHlkbkJjbnNzcTNPWVRqRytBQT09OjoK4z7K089XeX721FoCq++7', 3, 1, '2024-09-24 15:39:32', '2024-09-24 15:41:38', 'Nueva EPS', 'Sura', 1, '2018-09-01', '2024-10-02', 'O+');

-- Volcando estructura para tabla urbes_db.vehiculos
CREATE TABLE IF NOT EXISTS `vehiculos` (
  `placa` varchar(50) NOT NULL,
  `vehiculo` varchar(50) NOT NULL,
  `fecha_registro` datetime DEFAULT NULL,
  `fecha_actualizacion` datetime DEFAULT NULL,
  `fecha_soat` date DEFAULT NULL,
  `fecha_tecno` date DEFAULT NULL,
  PRIMARY KEY (`placa`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Volcando datos para la tabla urbes_db.vehiculos: ~1 rows (aproximadamente)
INSERT INTO `vehiculos` (`placa`, `vehiculo`, `fecha_registro`, `fecha_actualizacion`, `fecha_soat`, `fecha_tecno`) VALUES
	('HNT426', 'Compactador', '2024-09-09 12:51:09', '2024-09-26 18:08:48', '2025-11-12', '2025-10-16');

-- Volcando estructura para tabla urbes_db.vehiculo_compactador
CREATE TABLE IF NOT EXISTS `vehiculo_compactador` (
  `id_registro_veh_compactador` bigint NOT NULL AUTO_INCREMENT,
  `fecha_inicio` date DEFAULT NULL,
  `fecha_fin` date DEFAULT NULL,
  `hora_inicio` time DEFAULT NULL,
  `hora_finalizacion` time DEFAULT NULL,
  `km_inicio` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `km_fin` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `foto_kilometraje_inicial` varchar(300) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `foto_kilometraje_final` varchar(300) DEFAULT NULL,
  `horometro_inicio` varchar(50) DEFAULT NULL,
  `horometro_fin` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `id_labor` smallint DEFAULT NULL,
  `id_vehiculo` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `ciudad` smallint DEFAULT NULL,
  `documento` bigint DEFAULT NULL,
  `id_estado` int DEFAULT NULL,
  `fecha_registro` datetime DEFAULT NULL,
  `observaciones` varchar(500) DEFAULT NULL,
  `fecha_actualizacion` datetime DEFAULT NULL,
  PRIMARY KEY (`id_registro_veh_compactador`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=55 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Volcando datos para la tabla urbes_db.vehiculo_compactador: ~0 rows (aproximadamente)

-- Volcando estructura para tabla urbes_db.zonas
CREATE TABLE IF NOT EXISTS `zonas` (
  `id_zona` mediumint NOT NULL AUTO_INCREMENT,
  `zona` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `id_ciudad` smallint NOT NULL DEFAULT '0',
  `fecha_registro` datetime DEFAULT NULL,
  `fecha_actualizacion` datetime DEFAULT NULL,
  PRIMARY KEY (`id_zona`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Volcando datos para la tabla urbes_db.zonas: ~4 rows (aproximadamente)
INSERT INTO `zonas` (`id_zona`, `zona`, `id_ciudad`, `fecha_registro`, `fecha_actualizacion`) VALUES
	(1, '102', 1, '2024-09-09 23:11:07', '2024-09-09 23:12:56'),
	(2, '101', 1, '2024-09-09 23:13:13', '2024-09-09 23:13:21'),
	(3, '101', 2, '2024-09-23 18:02:38', NULL),
	(4, '103', 1, '2024-09-23 18:53:59', NULL);

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;

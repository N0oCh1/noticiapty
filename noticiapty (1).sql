-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Jul 13, 2025 at 12:41 AM
-- Server version: 9.1.0
-- PHP Version: 7.4.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `noticiapty`
--

-- --------------------------------------------------------

--
-- Table structure for table `categorias`
--

DROP TABLE IF EXISTS `categorias`;
CREATE TABLE IF NOT EXISTS `categorias` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci COMMENT='Categorias';

--
-- Dumping data for table `categorias`
--

INSERT INTO `categorias` (`id`, `nombre`) VALUES
(1, 'Deportes'),
(2, 'Política'),
(3, 'Tecnología'),
(4, 'Entretenimiento');

-- --------------------------------------------------------

--
-- Table structure for table `comentarios`
--

DROP TABLE IF EXISTS `comentarios`;
CREATE TABLE IF NOT EXISTS `comentarios` (
  `id` int NOT NULL AUTO_INCREMENT,
  `noticia_id` int DEFAULT NULL,
  `comentario_id` int DEFAULT NULL,
  `comentario` text,
  `autor` varchar(50) DEFAULT NULL,
  `fecha_creacion` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `noticia_id` (`noticia_id`),
  KEY `comentario_id` (`comentario_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci COMMENT='Comentarios';

-- --------------------------------------------------------

--
-- Table structure for table `imagenes`
--

DROP TABLE IF EXISTS `imagenes`;
CREATE TABLE IF NOT EXISTS `imagenes` (
  `id` int NOT NULL AUTO_INCREMENT,
  `noticia_id` int DEFAULT NULL,
  `imagen` varchar(255) DEFAULT NULL,
  `tipo_imagen` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `noticia_id` (`noticia_id`)
) ENGINE=MyISAM AUTO_INCREMENT=31 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci COMMENT='Imagenes';

--
-- Dumping data for table `imagenes`
--

INSERT INTO `imagenes` (`id`, `noticia_id`, `imagen`, `tipo_imagen`) VALUES
(25, 25, '../imagenDB/metro_panama.jpg', 'image/jpeg'),
(26, 26, '../imagenDB/seleccion_panama.jpg', 'image/jpeg'),
(27, 27, '../imagenDB/festival_jazz.jpg', 'image/jpeg'),
(28, 34, '../imagenDB/blanco.jpg', 'image/jpeg'),
(29, 35, '../imagenDB/museo.jpg', 'image/jpeg'),
(30, 36, '../imagenDB/blanco.jpg', 'image/jpeg');

-- --------------------------------------------------------

--
-- Table structure for table `noticias`
--

DROP TABLE IF EXISTS `noticias`;
CREATE TABLE IF NOT EXISTS `noticias` (
  `id` int NOT NULL AUTO_INCREMENT,
  `titulo` varchar(75) DEFAULT NULL,
  `contenido` text,
  `autor` varchar(50) DEFAULT NULL,
  `activo` tinyint(1) DEFAULT '1',
  `fecha_creacion` datetime DEFAULT CURRENT_TIMESTAMP,
  `categoria_id` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_categoria` (`categoria_id`)
) ENGINE=MyISAM AUTO_INCREMENT=37 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci COMMENT='Noticias';

--
-- Dumping data for table `noticias`
--

INSERT INTO `noticias` (`id`, `titulo`, `contenido`, `autor`, `activo`, `fecha_creacion`, `categoria_id`) VALUES
(21, 'Nuevo Metro de Panamá inicia operaciones', 'La Línea 3 del Metro de Panamá comenzó sus operaciones comerciales hoy, conectando el área oeste con el centro de la ciudad. El proyecto, que representa una inversión de más de $2,500 millones, beneficiará a más de 400,000 usuarios diarios. El sistema cuenta con la más moderna tecnología de transporte masivo y reducirá significativamente los tiempos de viaje.', 'Carlos Rodríguez', 1, '2025-07-11 22:42:09', NULL),
(22, 'Selección de Panamá se prepara para eliminatorias', 'La selección nacional de fútbol intensifica sus entrenamientos de cara a las próximas eliminatorias mundialistas. El técnico Thomas Christiansen ha convocado a las principales figuras del equipo, incluyendo a jugadores que militan en ligas europeas.', 'María González', 1, '2025-07-11 22:42:09', NULL),
(23, 'Festival de Jazz en Casco Antiguo', 'Este fin de semana el Casco Antiguo será sede del Festival Internacional de Jazz de Panamá 2025. El evento contará con la participación de reconocidos artistas nacionales e internacionales. Los conciertos se realizarán en la Plaza Herrera y varios lugares históricos del área.', 'Ana Pérez', 1, '2025-07-11 22:42:09', NULL),
(24, 'Nuevo Metro de Panamá inicia operaciones', 'La Línea 3 del Metro de Panamá comenzó sus operaciones comerciales hoy, conectando el área oeste con el centro de la ciudad. El proyecto, que representa una inversión de más de $2,500 millones, beneficiará a más de 400,000 usuarios diarios. El sistema cuenta con la más moderna tecnología de transporte masivo y reducirá significativamente los tiempos de viaje.', 'Carlos Rodríguez', 1, '2025-07-11 23:05:27', NULL),
(25, 'Selección de Panamá se prepara para eliminatorias', 'La selección nacional de fútbol intensifica sus entrenamientos de cara a las próximas eliminatorias mundialistas. El técnico Thomas Christiansen ha convocado a las principales figuras del equipo, incluyendo a jugadores que militan en ligas europeas.', 'María González', 1, '2025-07-11 23:05:27', NULL),
(26, 'Festival de Jazz en Casco Antiguo', 'Este fin de semana el Casco Antiguo será sede del Festival Internacional de Jazz de Panamá 2025. El evento contará con la participación de reconocidos artistas nacionales e internacionales. Los conciertos se realizarán en la Plaza Herrera y varios lugares históricos del área.', 'Ana Pérez', 1, '2025-07-11 23:05:27', NULL),
(27, 'Nuevo Metro de Panamá inicia operaciones', 'La Línea 3 del Metro de Panamá comenzó sus operaciones comerciales hoy, conectando el área oeste con el centro de la ciudad. El proyecto, que representa una inversión de más de $2,500 millones, beneficiará a más de 400,000 usuarios diarios. El sistema cuenta con la más moderna tecnología de transporte masivo y reducirá significativamente los tiempos de viaje.', 'Carlos Rodríguez', 1, '2025-07-11 23:05:32', NULL),
(28, 'Selección de Panamá se prepara para eliminatorias', 'La selección nacional de fútbol intensifica sus entrenamientos de cara a las próximas eliminatorias mundialistas. El técnico Thomas Christiansen ha convocado a las principales figuras del equipo, incluyendo a jugadores que militan en ligas europeas.', 'María González', 1, '2025-07-11 23:05:32', NULL),
(29, 'Festival de Jazz en Casco Antiguo', 'Este fin de semana el Casco Antiguo será sede del Festival Internacional de Jazz de Panamá 2025. El evento contará con la participación de reconocidos artistas nacionales e internacionales. Los conciertos se realizarán en la Plaza Herrera y varios lugares históricos del área.', 'Ana Pérez', 1, '2025-07-11 23:05:32', NULL),
(30, 'Nuevo Metro de Panamá inicia operaciones', 'La Línea 3 del Metro de Panamá comenzó sus operaciones comerciales hoy, conectando el área oeste con el centro de la ciudad. El proyecto, que representa una inversión de más de $2,500 millones, beneficiará a más de 400,000 usuarios diarios. El sistema cuenta con la más moderna tecnología de transporte masivo y reducirá significativamente los tiempos de viaje.', 'Carlos Rodríguez', 1, '2025-07-11 23:05:36', NULL),
(31, 'Selección de Panamá se prepara para eliminatorias', 'La selección nacional de fútbol intensifica sus entrenamientos de cara a las próximas eliminatorias mundialistas. El técnico Thomas Christiansen ha convocado a las principales figuras del equipo, incluyendo a jugadores que militan en ligas europeas.', 'María González', 1, '2025-07-11 23:05:36', NULL),
(32, 'Festival de Jazz en Casco Antiguo', 'Este fin de semana el Casco Antiguo será sede del Festival Internacional de Jazz de Panamá 2025. El evento contará con la participación de reconocidos artistas nacionales e internacionales. Los conciertos se realizarán en la Plaza Herrera y varios lugares históricos del área.', 'Ana Pérez', 1, '2025-07-11 23:05:36', NULL),
(33, 'Nuevo Metro de Panamá inicia operaciones', 'La Línea 3 del Metro de Panamá comenzó sus operaciones comerciales hoy, conectando el área oeste con el centro de la ciudad. El proyecto, que representa una inversión de más de $2,500 millones, beneficiará a más de 400,000 usuarios diarios. El sistema cuenta con la más moderna tecnología de transporte masivo y reducirá significativamente los tiempos de viaje.', 'Carlos Rodríguez', 1, '2025-07-12 10:37:40', NULL),
(34, 'Panamá clasifica a los Juegos Olímpicos 2024', 'La selección panameña de fútbol logró su histórica clasificación a los Juegos Olímpicos de París 2024. El equipo nacional derrotó a su similar de Costa Rica en un emocionante partido que terminó 2-1, asegurando así su primera participación en unas olimpiadas.', 'Roberto Sánchez', 1, '2025-07-12 11:44:02', 1),
(35, 'Nueva exposición de arte en el Museo del Canal', 'El Museo del Canal inaugura la exposición \"Panamá: Puente del Mundo\" que reúne obras de más de 50 artistas locales e internacionales. La muestra estará disponible durante tres meses y refleja la importancia histórica y cultural del Canal de Panamá.', 'María Torres', 1, '2025-07-12 11:44:02', NULL),
(36, 'Avances en proyecto de energía solar', 'El primer parque de energía solar de gran escala en Panamá Oeste muestra un avance del 75% en su construcción. El proyecto, que ocupará 50 hectáreas, promete suministrar energía limpia a más de 30,000 hogares una vez completado en diciembre de 2025.\r\nEl primer parque de energía solar de gran escala en Panamá Oeste muestra un avance del 75% en su construcción. El proyecto, que ocupará 50 hectáreas, promete suministrar energía limpia a más de 30,000 hogares una vez completado en diciembre de 2025.\r\nEl primer parque de energía solar de gran escala en Panamá Oeste muestra un avance del 75% en su construcción. El proyecto, que ocupará 50 hectáreas, promete suministrar energía limpia a más de 30,000 hogares una vez completado en diciembre de 2025.\r\nEl primer parque de energía solar de gran escala en Panamá Oeste muestra un avance del 75% en su construcción. El proyecto, que ocupará 50 hectáreas, promete suministrar energía limpia a más de 30,000 hogares una vez completado en diciembre de 2025.\r\nEl primer parque de energía solar de gran escala en Panamá Oeste muestra un avance del 75% en su construcción. El proyecto, que ocupará 50 hectáreas, promete suministrar energía limpia a más de 30,000 hogares una vez completado en diciembre de 2025.', 'Luis Chang', 1, '2025-07-12 11:44:02', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `usuarios`
--

DROP TABLE IF EXISTS `usuarios`;
CREATE TABLE IF NOT EXISTS `usuarios` (
  `id` int NOT NULL AUTO_INCREMENT COMMENT 'Primary Key',
  `nombre` varchar(25) DEFAULT NULL,
  `apellido` varchar(25) DEFAULT NULL,
  `usuario` varchar(50) DEFAULT NULL,
  `contrasena` varchar(255) DEFAULT NULL,
  `rol` varchar(25) DEFAULT NULL,
  `activo` tinyint(1) DEFAULT '1',
  `create_time` datetime DEFAULT CURRENT_TIMESTAMP COMMENT 'Create Time',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci COMMENT='Usuarios';

-- --------------------------------------------------------

--
-- Table structure for table `visitas`
--

DROP TABLE IF EXISTS `visitas`;
CREATE TABLE IF NOT EXISTS `visitas` (
  `id` int NOT NULL AUTO_INCREMENT,
  `cantidad` int DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `visitas`
--

INSERT INTO `visitas` (`id`, `cantidad`) VALUES
(1, 16);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

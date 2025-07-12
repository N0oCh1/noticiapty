-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Jul 12, 2025 at 04:43 AM
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
  `activo` tinyint(1) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci COMMENT='Categorias';

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
) ENGINE=MyISAM AUTO_INCREMENT=28 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci COMMENT='Imagenes';

--
-- Dumping data for table `imagenes`
--

INSERT INTO `imagenes` (`id`, `noticia_id`, `imagen`, `tipo_imagen`) VALUES
(25, 25, '../imagenDB/metro_panama.jpg', 'image/jpeg'),
(26, 26, '../imagenDB/seleccion_panama.jpg', 'image/jpeg'),
(27, 27, '../imagenDB/festival_jazz.jpg', 'image/jpeg');

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
  `categoria` varchar(50) DEFAULT NULL,
  `activo` tinyint(1) DEFAULT '1',
  `fecha_creacion` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=33 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci COMMENT='Noticias';

--
-- Dumping data for table `noticias`
--

INSERT INTO `noticias` (`id`, `titulo`, `contenido`, `autor`, `categoria`, `activo`, `fecha_creacion`) VALUES
(21, 'Nuevo Metro de Panamá inicia operaciones', 'La Línea 3 del Metro de Panamá comenzó sus operaciones comerciales hoy, conectando el área oeste con el centro de la ciudad. El proyecto, que representa una inversión de más de $2,500 millones, beneficiará a más de 400,000 usuarios diarios. El sistema cuenta con la más moderna tecnología de transporte masivo y reducirá significativamente los tiempos de viaje.', 'Carlos Rodríguez', 'politica', 1, '2025-07-11 22:42:09'),
(22, 'Selección de Panamá se prepara para eliminatorias', 'La selección nacional de fútbol intensifica sus entrenamientos de cara a las próximas eliminatorias mundialistas. El técnico Thomas Christiansen ha convocado a las principales figuras del equipo, incluyendo a jugadores que militan en ligas europeas.', 'María González', 'deportes', 1, '2025-07-11 22:42:09'),
(23, 'Festival de Jazz en Casco Antiguo', 'Este fin de semana el Casco Antiguo será sede del Festival Internacional de Jazz de Panamá 2025. El evento contará con la participación de reconocidos artistas nacionales e internacionales. Los conciertos se realizarán en la Plaza Herrera y varios lugares históricos del área.', 'Ana Pérez', 'entretenimiento', 1, '2025-07-11 22:42:09'),
(24, 'Nuevo Metro de Panamá inicia operaciones', 'La Línea 3 del Metro de Panamá comenzó sus operaciones comerciales hoy, conectando el área oeste con el centro de la ciudad. El proyecto, que representa una inversión de más de $2,500 millones, beneficiará a más de 400,000 usuarios diarios. El sistema cuenta con la más moderna tecnología de transporte masivo y reducirá significativamente los tiempos de viaje.', 'Carlos Rodríguez', 'politica', 1, '2025-07-11 23:05:27'),
(25, 'Selección de Panamá se prepara para eliminatorias', 'La selección nacional de fútbol intensifica sus entrenamientos de cara a las próximas eliminatorias mundialistas. El técnico Thomas Christiansen ha convocado a las principales figuras del equipo, incluyendo a jugadores que militan en ligas europeas.', 'María González', 'deportes', 1, '2025-07-11 23:05:27'),
(26, 'Festival de Jazz en Casco Antiguo', 'Este fin de semana el Casco Antiguo será sede del Festival Internacional de Jazz de Panamá 2025. El evento contará con la participación de reconocidos artistas nacionales e internacionales. Los conciertos se realizarán en la Plaza Herrera y varios lugares históricos del área.', 'Ana Pérez', 'entretenimiento', 1, '2025-07-11 23:05:27'),
(27, 'Nuevo Metro de Panamá inicia operaciones', 'La Línea 3 del Metro de Panamá comenzó sus operaciones comerciales hoy, conectando el área oeste con el centro de la ciudad. El proyecto, que representa una inversión de más de $2,500 millones, beneficiará a más de 400,000 usuarios diarios. El sistema cuenta con la más moderna tecnología de transporte masivo y reducirá significativamente los tiempos de viaje.', 'Carlos Rodríguez', 'politica', 1, '2025-07-11 23:05:32'),
(28, 'Selección de Panamá se prepara para eliminatorias', 'La selección nacional de fútbol intensifica sus entrenamientos de cara a las próximas eliminatorias mundialistas. El técnico Thomas Christiansen ha convocado a las principales figuras del equipo, incluyendo a jugadores que militan en ligas europeas.', 'María González', 'deportes', 1, '2025-07-11 23:05:32'),
(29, 'Festival de Jazz en Casco Antiguo', 'Este fin de semana el Casco Antiguo será sede del Festival Internacional de Jazz de Panamá 2025. El evento contará con la participación de reconocidos artistas nacionales e internacionales. Los conciertos se realizarán en la Plaza Herrera y varios lugares históricos del área.', 'Ana Pérez', 'entretenimiento', 1, '2025-07-11 23:05:32'),
(30, 'Nuevo Metro de Panamá inicia operaciones', 'La Línea 3 del Metro de Panamá comenzó sus operaciones comerciales hoy, conectando el área oeste con el centro de la ciudad. El proyecto, que representa una inversión de más de $2,500 millones, beneficiará a más de 400,000 usuarios diarios. El sistema cuenta con la más moderna tecnología de transporte masivo y reducirá significativamente los tiempos de viaje.', 'Carlos Rodríguez', 'politica', 1, '2025-07-11 23:05:36'),
(31, 'Selección de Panamá se prepara para eliminatorias', 'La selección nacional de fútbol intensifica sus entrenamientos de cara a las próximas eliminatorias mundialistas. El técnico Thomas Christiansen ha convocado a las principales figuras del equipo, incluyendo a jugadores que militan en ligas europeas.', 'María González', 'deportes', 1, '2025-07-11 23:05:36'),
(32, 'Festival de Jazz en Casco Antiguo', 'Este fin de semana el Casco Antiguo será sede del Festival Internacional de Jazz de Panamá 2025. El evento contará con la participación de reconocidos artistas nacionales e internacionales. Los conciertos se realizarán en la Plaza Herrera y varios lugares históricos del área.', 'Ana Pérez', 'entretenimiento', 1, '2025-07-11 23:05:36');

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
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

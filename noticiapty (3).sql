-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Jul 14, 2025 at 05:05 AM
-- Server version: 9.1.0
-- PHP Version: 8.4.0

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
(25, 26, '../imagenDB/metro_panama.jpg', 'image/jpeg'),
(26, 26, '../imagenDB/seleccion_panama.jpg', 'image/jpeg'),
(27, 26, '../imagenDB/festival_jazz.jpg', 'image/jpeg'),
(28, 36, '../imagenDB/museo.jpg', 'image/jpeg'),
(29, 36, '../imagenDB/museo.jpg', 'image/jpeg'),
(30, 36, '../imagenDB/blanco.jpg', 'image/jpeg');

-- --------------------------------------------------------

--
-- Table structure for table `likes`
--

DROP TABLE IF EXISTS `likes`;
CREATE TABLE IF NOT EXISTS `likes` (
  `id` int NOT NULL AUTO_INCREMENT,
  `usuario_id` int NOT NULL,
  `noticia_id` int NOT NULL,
  `fecha` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `usuario_id` (`usuario_id`,`noticia_id`),
  KEY `noticia_id` (`noticia_id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `likes`
--

INSERT INTO `likes` (`id`, `usuario_id`, `noticia_id`, `fecha`) VALUES
(1, 1, 36, '2025-07-13 16:50:05');

-- --------------------------------------------------------

--
-- Table structure for table `noticias`
--

DROP TABLE IF EXISTS `noticias`;
CREATE TABLE IF NOT EXISTS `noticias` (
  `id` int NOT NULL AUTO_INCREMENT,
  `titulo` varchar(75) DEFAULT NULL,
  `contenido` text,
  `activo` tinyint(1) DEFAULT '1',
  `fecha_creacion` datetime DEFAULT CURRENT_TIMESTAMP,
  `categoria_id` int DEFAULT NULL,
  `usuario_id` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_categoria` (`categoria_id`),
  KEY `fk_usuario_publicador` (`usuario_id`)
) ENGINE=MyISAM AUTO_INCREMENT=37 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci COMMENT='Noticias';

--
-- Dumping data for table `noticias`
--

INSERT INTO `noticias` (`id`, `titulo`, `contenido`, `activo`, `fecha_creacion`, `categoria_id`, `usuario_id`) VALUES
(21, 'Nuevo Metro de Panamá inicia operaciones', 'La Línea 3 del Metro de Panamá comenzó sus operaciones comerciales hoy, conectando el área oeste con el centro de la ciudad. El proyecto, que representa una inversión de más de $2,500 millones, beneficiará a más de 400,000 usuarios diarios. El sistema cuenta con la más moderna tecnología de transporte masivo y reducirá significativamente los tiempos de viaje.', 1, '2025-07-11 22:42:09', NULL, NULL),
(22, 'Selección de Panamá se prepara para eliminatorias', 'La selección nacional de fútbol intensifica sus entrenamientos de cara a las próximas eliminatorias mundialistas. El técnico Thomas Christiansen ha convocado a las principales figuras del equipo, incluyendo a jugadores que militan en ligas europeas.', 1, '2025-07-11 22:42:09', 2, NULL),
(23, 'Festival de Jazz en Casco Antiguo', 'Este fin de semana el Casco Antiguo será sede del Festival Internacional de Jazz de Panamá 2025. El evento contará con la participación de reconocidos artistas nacionales e internacionales. Los conciertos se realizarán en la Plaza Herrera y varios lugares históricos del área.', 1, '2025-07-11 22:42:09', 2, NULL),
(24, 'Nuevo Metro de Panamá inicia operaciones', 'La Línea 3 del Metro de Panamá comenzó sus operaciones comerciales hoy, conectando el área oeste con el centro de la ciudad. El proyecto, que representa una inversión de más de $2,500 millones, beneficiará a más de 400,000 usuarios diarios. El sistema cuenta con la más moderna tecnología de transporte masivo y reducirá significativamente los tiempos de viaje.', 1, '2025-07-11 23:05:27', NULL, NULL),
(25, 'Selección de Panamá se prepara para eliminatorias', 'La selección nacional de fútbol intensifica sus entrenamientos de cara a las próximas eliminatorias mundialistas. El técnico Thomas Christiansen ha convocado a las principales figuras del equipo, incluyendo a jugadores que militan en ligas europeas.', 1, '2025-07-11 23:05:27', NULL, NULL),
(26, 'Festival de Jazz en Casco Antiguo', 'Este fin de semana el Casco Antiguo será sede del Festival Internacional de Jazz de Panamá 2025. El evento contará con la participación de reconocidos artistas nacionales e internacionales. Los conciertos se realizarán en la Plaza Herrera y varios lugares históricos del área.', 1, '2025-07-11 23:05:27', NULL, NULL),
(27, 'Nuevo Metro de Panamá inicia operaciones', 'La Línea 3 del Metro de Panamá comenzó sus operaciones comerciales hoy, conectando el área oeste con el centro de la ciudad. El proyecto, que representa una inversión de más de $2,500 millones, beneficiará a más de 400,000 usuarios diarios. El sistema cuenta con la más moderna tecnología de transporte masivo y reducirá significativamente los tiempos de viaje.', 1, '2025-07-11 23:05:32', NULL, NULL),
(28, 'Selección de Panamá se prepara para eliminatorias', 'La selección nacional de fútbol intensifica sus entrenamientos de cara a las próximas eliminatorias mundialistas. El técnico Thomas Christiansen ha convocado a las principales figuras del equipo, incluyendo a jugadores que militan en ligas europeas.', 1, '2025-07-11 23:05:32', 3, 10),
(29, 'Festival de Jazz en Casco Antiguo', 'Este fin de semana el Casco Antiguo será sede del Festival Internacional de Jazz de Panamá 2025. El evento contará con la participación de reconocidos artistas nacionales e internacionales. Los conciertos se realizarán en la Plaza Herrera y varios lugares históricos del área.', 1, '2025-07-11 23:05:32', 2, 1),
(30, 'Nuevo Metro de Panamá inicia operaciones', 'La Línea 3 del Metro de Panamá comenzó sus operaciones comerciales hoy, conectando el área oeste con el centro de la ciudad. El proyecto, que representa una inversión de más de $2,500 millones, beneficiará a más de 400,000 usuarios diarios. El sistema cuenta con la más moderna tecnología de transporte masivo y reducirá significativamente los tiempos de viaje.', 1, '2025-07-11 23:05:36', 1, 9),
(31, 'Panamá clasifica a los Juegos Olímpicos 2024', 'La selección nacional de fútbol intensifica sus entrenamientos de cara a las próximas eliminatorias mundialistas. El técnico Thomas Christiansen ha convocado a las principales figuras del equipo, incluyendo a jugadores que militan en ligas europeas.', 1, '2025-07-11 23:05:36', 1, 10),
(32, 'Festival de Jazz en Casco Antiguo', 'Este fin de semana el Casco Antiguo será sede del Festival Internacional de Jazz de Panamá 2025. El evento contará con la participación de reconocidos artistas nacionales e internacionales. Los conciertos se realizarán en la Plaza Herrera y varios lugares históricos del área.', 1, '2025-07-11 23:05:36', 3, NULL),
(33, 'Nuevo Metro de Panamá inicia operaciones', 'La Línea 3 del Metro de Panamá comenzó sus operaciones comerciales hoy, conectando el área oeste con el centro de la ciudad. El proyecto, que representa una inversión de más de $2,500 millones, beneficiará a más de 400,000 usuarios diarios. El sistema cuenta con la más moderna tecnología de transporte masivo y reducirá significativamente los tiempos de viaje.', 1, '2025-07-12 10:37:40', 2, 10),
(34, 'Panamá clasifica a los Juegos Olímpicos 2024', 'La selección panameña de fútbol logró su histórica clasificación a los Juegos Olímpicos de París 2024. El equipo nacional derrotó a su similar de Costa Rica en un emocionante partido que terminó 2-1, asegurando así su primera participación en unas olimpiadas.', 1, '2025-07-12 11:44:02', 1, 10),
(35, 'Panamá clasifica a los Juegos Olímpicos 2024', 'El Museo del Canal inaugura la exposición \"Panamá: Puente del Mundo\" que reúne obras de más de 50 artistas locales e internacionales. La muestra estará disponible durante tres meses y refleja la importancia histórica y cultural del Canal de Panamá.', 1, '2025-07-12 11:44:02', 1, 10),
(36, 'Panamá clasifica a los Juegos Olímpicos 2024', 'La llegada de la tecnología **5G** está marcando un hito en el ámbito de las telecomunicaciones. Este nuevo estándar de comunicación promete velocidades de Internet hasta 100 veces más rápidas que las ofrecidas por el 4G, lo que permitirá una mejora significativa en la transmisión de datos y en la experiencia de usuario. La implementación del 5G también abrirá puertas a nuevas aplicaciones, como el internet de las cosas (IoT), vehículos autónomos, y una mayor capacidad para conectar dispositivos simultáneamente sin perder calidad de servicio. Se espera que, en los próximos años, esta tecnología sea accesible para la mayoría de los usuarios, cambiando por completo la manera en que nos comunicamos y accedemos a la información.\r\n\r\nLos océanos están experimentando cambios significativos debido al cambio climático, lo que pone en riesgo a las especies marinas y a las comunidades costeras. Según un informe de la ONU, los niveles del mar han aumentado considerablemente en las últimas décadas, y se espera que este fenómeno continúe debido al derretimiento de los glaciares y el calentamiento global. Estos cambios no solo afectan a la biodiversidad marina, sino que también tienen un impacto directo en las comunidades que dependen de los recursos oceánicos para su sustento. Las temperaturas más altas en los océanos están alterando los ecosistemas marinos, provocando la desaparición de especies y alterando las migraciones de peces. Los científicos hacen un llamado urgente para implementar políticas que frenen el cambio climático y protejan nuestros océanos.\r\n\r\nEn los últimos años, América Latina ha experimentado un notable crecimiento en la adopción de energías renovables. Países como Brasil, Chile y México están liderando el camino con importantes inversiones en energía solar y eólica, lo que les ha permitido diversificar su matriz energética y reducir su dependencia de los combustibles fósiles. Este cambio no solo es favorable para el medio ambiente, sino que también está generando nuevos empleos y oportunidades económicas en la región. La energía renovable se está posicionando como una de las alternativas más viables para combatir el cambio climático y asegurar un futuro más sostenible. Además, la caída de los costos de las tecnologías renovables ha hecho que sean más accesibles para muchos países de la región, impulsando su crecimiento aún más.', 1, '2025-07-12 11:44:02', 1, 10);

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
  PRIMARY KEY (`id`),
  UNIQUE KEY `UQ_usuario` (`usuario`)
) ENGINE=MyISAM AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci COMMENT='Usuarios';

--
-- Dumping data for table `usuarios`
--

INSERT INTO `usuarios` (`id`, `nombre`, `apellido`, `usuario`, `contrasena`, `rol`, `activo`, `create_time`) VALUES
(1, 'kelvin', 'he', 'kelvin', '123456', 'global', 1, '2025-07-13 11:55:01'),
(3, 'juan', 'juan', 'juan', '$2y$10$Lc7GhD7PtMsdZCsxNHZl9efqobieXfeSimHW3BAQDJmgMD8AOs4nC', 'global', 1, '2025-07-13 13:35:10'),
(4, 'aldo', 'aldo', 'aldo', '$2y$10$tQjYSswfNU9gCLIDufWNfOHJ5m8ePeDy./yTwfmX123qD5N6fW1oe', 'global', 1, '2025-07-13 13:43:13'),
(5, 'a', 'a', 'a', '$2y$10$i3QXxRVVterHKIzuZ4sF6.dA5jLiT5DomMWK5Bzl2yx7coJeUhae.', 'global', 1, '2025-07-13 16:41:41'),
(6, 'f', 'f', 'f', '$2y$10$np3.EbSbbgSlPEKDXRTr5OUTrtgghUlLu7Q7vfCTFkd/.YDxRLH1y', 'global', 1, '2025-07-13 16:42:24'),
(7, 'a', 'a', 'as', '$2y$10$kSuwhzV..na63gpRohGDZOkZY1EG0d7fWsU2G0pyGhhfxhGr0WMXG', 'global', 1, '2025-07-13 16:47:23'),
(8, 'hgffhf', 'fghfh', 'fff', '$2y$10$4RI6zQLqipGJ7NRhrYZNB.ZywakESnDCnC5RQk3rhHfxxk1aiN2KW', 'global', 1, '2025-07-13 17:50:35'),
(9, 'hgffhfdfsd', 'fghfh', 'fffsfsd', '$2y$10$tsmDXOluiMYnjUMLG7Abzun3lkFUmnVum6gpiT3zmjl.vPfC0kuzS', 'global', 1, '2025-07-13 17:50:57'),
(10, 'adadasdas', 'asdadas', 'asdadasda', '$2y$10$pNJW29swHEziRjlKXD9W0.f5aZnIgTvOrEQdsskdSssyGmll1tqwe', 'publicador', 1, '2025-07-13 17:51:21'),
(11, 'gggg', 'gggg', 'ggg', '$2y$10$.H1Xa4ELx0819jePDpuELOFIaRq6KyyE0WkKuaDBJ5pN7ykKn5tMW', 'global', 1, '2025-07-13 19:03:19'),
(12, 'v', 'v', 'v', '$2y$12$9z3db42jskJvOdsJtzVWI.kEnK7nuQU0BChJptoZYWYyny23nI0Qi', 'global', 1, '2025-07-13 23:13:04'),
(13, 'q', 'q', 'q', '$2y$12$45wT/aQ1Mls7uZwPQaygCem/kI5Jh0gBvySjlPHem3dLClW6zW8D.', 'global', 1, '2025-07-13 23:24:43');

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
(1, 1337);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

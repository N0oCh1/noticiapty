-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Jul 24, 2025 at 11:12 PM
-- Server version: 9.1.0
-- PHP Version: 8.3.14

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
  `noticia_id` int NOT NULL,
  `usuario_id` int NOT NULL,
  `contenido` text NOT NULL,
  `fecha_creacion` datetime DEFAULT CURRENT_TIMESTAMP,
  `comentario_padre_id` int DEFAULT NULL COMMENT 'Referencia al comentario padre (para respuestas)',
  PRIMARY KEY (`id`),
  KEY `noticia_id` (`noticia_id`),
  KEY `usuario_id` (`usuario_id`)
) ENGINE=MyISAM AUTO_INCREMENT=52 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci COMMENT='Comentarios';

--
-- Dumping data for table `comentarios`
--

INSERT INTO `comentarios` (`id`, `noticia_id`, `usuario_id`, `contenido`, `fecha_creacion`, `comentario_padre_id`) VALUES
(1, 45, 34, 'Goty', '2025-07-17 19:45:48', NULL),
(2, 49, 34, 'Noooooooo😭', '2025-07-17 20:17:34', NULL),
(3, 49, 35, 'Jajajaj XD', '2025-07-17 20:17:59', NULL),
(4, 49, 37, 'Se nos fue un idolo :(', '2025-07-17 20:19:43', NULL),
(36, 51, 37, 'si funciona gente', '2025-07-20 20:17:10', NULL),
(37, 45, 37, 'Goty', '2025-07-20 20:17:29', NULL),
(38, 51, 32, 'solo si depositas 600$ en el curso >:(', '2025-07-21 13:38:37', 36),
(40, 52, 37, '._.', '2025-07-23 20:37:51', NULL),
(41, 50, 37, 'vende humos', '2025-07-23 20:38:10', NULL),
(42, 48, 37, 'y pa cuando el tren a chiriqui????', '2025-07-23 20:38:26', NULL),
(43, 46, 37, 'zzzzz', '2025-07-23 20:38:37', NULL),
(44, 45, 32, 'Balatro no es solo un juego de cartas, es un estilo de vida. Respiro y tomo Balatro. Si tuviera un hijo lo llamaría \"Full House\" y si tuviera dos les pondría \"Doble\" y \"Par\" para mejorarlos a nivel 18 y que rompan las ligas de jefe cuando sean adultos. Brindo por Balatro, Como por Balatro, este me hizo mejor persona, y cuando tengo el celular descargado, cierro mis ojos para jugarlo en mi mente. La rueda de la fortuna dicta mi vida. Siempre tengo un plátano en mi bolsillo para sumar puntos. Me identifico con el Joker \"Misprint\" porque mi estado mental es así de inestable. No puedo subir las escaleras sin pensar en Balatro y el dia que mu3ra espero que pongan en mi tumb4 \"Mur1ó en pleno Balatreo\". El unico corazón que quiero es el de la reina. Mi papá cree que estoy mal, pero él no sabe lo que es ser la carta más alta de la familia. El día que el juego ya no tenga fans es porque ya no estaré en este mundo. Porque yo... Soy el Balatro.', '2025-07-23 23:28:22', NULL),
(45, 51, 32, 'no funciona gaste 600$ y nada 😡', '2025-07-23 23:31:37', NULL),
(46, 51, 35, 'no le sabes 🤫🧏‍♂️', '2025-07-23 23:32:27', 45),
(47, 51, 38, 'donde me inscribo?', '2025-07-24 18:04:40', NULL),
(48, 48, 38, 'y pa colon?', '2025-07-24 18:10:30', NULL),
(49, 49, 38, 'de que te ries??????', '2025-07-24 18:10:51', 3),
(50, 49, 38, 'mi infancia bro :(', '2025-07-24 18:11:02', NULL),
(51, 50, 38, 'zzzzz', '2025-07-24 18:11:26', NULL);

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
) ENGINE=MyISAM AUTO_INCREMENT=101 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci COMMENT='Imagenes';

--
-- Dumping data for table `imagenes`
--

INSERT INTO `imagenes` (`id`, `noticia_id`, `imagen`, `tipo_imagen`) VALUES
(49, 45, '../imagenDB/68799925b0691-Balatro_cover.jpg', 'image/jpeg'),
(50, 45, '../imagenDB/thumb_68799925b0691-Balatro_cover.jpg', 'image/jpeg'),
(51, 45, '../imagenDB/68799925b3bf2-balatro meme 2.jpg', 'image/jpeg'),
(52, 45, '../imagenDB/68799925b3fe1-meme 1.jpg', 'image/jpeg'),
(53, 46, '../imagenDB/68799a1d7ce1b-images.png', 'image/png'),
(54, 46, '../imagenDB/thumb_68799a1d7ce1b-images.png', 'image/png'),
(55, 46, '../imagenDB/68799a1d8722b-450_1000.jpg', 'image/jpeg'),
(56, 46, '../imagenDB/68799a1d876c7-11.png', 'image/png'),
(57, 47, '../imagenDB/68799b5984b8c-2206597329.jpg', 'image/jpeg'),
(58, 47, '../imagenDB/thumb_68799b5984b8c-2206597329.jpg', 'image/jpeg'),
(59, 47, '../imagenDB/68799b599b264-seleccion_panama.jpg', 'image/jpeg'),
(60, 47, '../imagenDB/68799b599b7e8-images.jpg', 'image/jpeg'),
(61, 48, '../imagenDB/68799be4361df-Visita-Hitachi-Monorriel-de-Panama-Linea-3-Mision-Japon-Seul-JL-546.jpg', 'image/jpeg'),
(62, 48, '../imagenDB/thumb_68799be4361df-Visita-Hitachi-Monorriel-de-Panama-Linea-3-Mision-Japon-Seul-JL-546.jpg', 'image/jpeg'),
(63, 48, '../imagenDB/68799be444e6a-LINEA-3para-web.jpg', 'image/jpeg'),
(64, 48, '../imagenDB/68799be4452de-metro-panama-1jpg.jpg', 'image/jpeg'),
(65, 49, '../imagenDB/6879a079c93d4-hq720.jpg', 'image/jpeg'),
(66, 49, '../imagenDB/thumb_6879a079c93d4-hq720.jpg', 'image/jpeg'),
(67, 49, '../imagenDB/6879a079ce1cc-1366_2000.jpg', 'image/jpeg'),
(68, 49, '../imagenDB/6879a079ce592-images.jpg', 'image/jpeg'),
(69, 50, '../imagenDB/6879a33c9b6a5-37569-media.jpg', 'image/jpeg'),
(70, 50, '../imagenDB/thumb_6879a33c9b6a5-37569-media.jpg', 'image/jpeg'),
(71, 50, '../imagenDB/6879a33ca5bf1-images.jpg', 'image/jpeg'),
(72, 50, '../imagenDB/6879a33ca5f5c-DyrjtZNU8AAbQ3H-542x407.jpg', 'image/jpeg'),
(92, 51, '../imagenDB/6881644822cbb-Ruben-feng.jpg', 'image/jpeg'),
(89, 51, '../imagenDB/688164481c592-ram.png', 'image/png'),
(90, 51, '../imagenDB/thumb_688164481c592-ram.png', 'image/png'),
(91, 51, '../imagenDB/68816448227b6-afro.jpg', 'image/jpeg'),
(99, 52, '../imagenDB/6881820ba6ba7-hq720.jpg', 'image/jpeg'),
(98, 52, '../imagenDB/thumb_6881820b9d27f-38bf4d496d635acc334eb1970c9bae92.jpg', 'image/jpeg'),
(97, 52, '../imagenDB/6881820b9d27f-38bf4d496d635acc334eb1970c9bae92.jpg', 'image/jpeg'),
(100, 52, '../imagenDB/6881820ba705b-images.jpg', 'image/jpeg');

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
) ENGINE=MyISAM AUTO_INCREMENT=100 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `likes`
--

INSERT INTO `likes` (`id`, `usuario_id`, `noticia_id`, `fecha`) VALUES
(85, 34, 45, '2025-07-18 00:45:40'),
(86, 34, 49, '2025-07-18 01:17:03'),
(87, 35, 49, '2025-07-18 01:17:55'),
(95, 37, 49, '2025-07-23 22:32:58'),
(89, 37, 48, '2025-07-18 01:22:02'),
(90, 37, 47, '2025-07-18 01:22:10'),
(91, 37, 51, '2025-07-21 01:16:59'),
(92, 37, 45, '2025-07-21 01:17:30'),
(94, 32, 51, '2025-07-21 18:38:09'),
(96, 37, 52, '2025-07-24 01:37:50'),
(97, 37, 50, '2025-07-24 01:38:01'),
(98, 38, 48, '2025-07-24 23:10:20'),
(99, 38, 49, '2025-07-24 23:11:09');

-- --------------------------------------------------------

--
-- Table structure for table `noticias`
--

DROP TABLE IF EXISTS `noticias`;
CREATE TABLE IF NOT EXISTS `noticias` (
  `id` int NOT NULL AUTO_INCREMENT,
  `titulo` varchar(75) DEFAULT NULL,
  `contenido` text,
  `activo` tinyint(1) DEFAULT '3',
  `fecha_creacion` datetime DEFAULT CURRENT_TIMESTAMP,
  `categoria_id` int DEFAULT NULL,
  `usuario_id` int DEFAULT NULL,
  `autor` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_categoria` (`categoria_id`),
  KEY `fk_usuario_publicador` (`usuario_id`)
) ENGINE=MyISAM AUTO_INCREMENT=53 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci COMMENT='Noticias';

--
-- Dumping data for table `noticias`
--

INSERT INTO `noticias` (`id`, `titulo`, `contenido`, `activo`, `fecha_creacion`, `categoria_id`, `usuario_id`, `autor`) VALUES
(45, 'Balatro peak del gaming?', 'Balatro, el innovador roguelike de póker desarrollado por el estudio independiente LocalThunk, se ha consolidado como uno de los mayores fenómenos del gaming en 2024 y 2025, superando ya los 2 millones de copias vendidas en apenas seis meses desde su lanzamiento. El juego ha logrado conquistar tanto a la crítica como a millones de jugadores gracias a su propuesta original que combina mecánicas de póker con dinámicas propias de los juegos de cartas coleccionables y roguelikes, todo con una estética minimalista y adictiva. Uno de sus mayores logros ha sido hacerlo sin recurrir a prácticas comerciales invasivas: no hay microtransacciones, DLCs pagos ni anuncios, una decisión ética que su creador ha defendido públicamente y que lo ha hecho ganar aún más respeto dentro de la comunidad gamer.\r\n\r\nPese a su éxito, Balatro no ha estado libre de polémicas. Inicialmente clasificado como apto para mayores de 3 años, fue abruptamente elevado a PEGI 18 por “similitudes con juegos de azar”, lo que causó su retirada temporal en varias plataformas europeas. Sin embargo, tras una apelación respaldada por expertos y jugadores, logró ser reclasificado como PEGI 12+, abriendo un debate internacional sobre cómo se evalúa el contenido relacionado con apuestas en los videojuegos. Mientras tanto, el equipo detrás del juego anunció una gran actualización gratuita (versión 1.1) que promete añadir nuevas cartas, comodines, reglas especiales y mejoras de accesibilidad, todo sin costo para los usuarios.\r\n\r\nAdemás del éxito comercial y crítico —con premios como “Mejor juego indie” en los Golden Joystick Awards y en The Game Awards—, el juego también ha conquistado a figuras del entretenimiento. El director de cine James Gunn, actual responsable de la nueva película de Superman, confesó en una entrevista que su adicción a Balatro fue tal que llegó a interferir con su rutina de trabajo. Por si fuera poco, el juego ha generado una activa comunidad online, con jugadores compartiendo jugadas imposibles, estrategias extremas y hasta memes sobre combinaciones de cartas. Así, Balatro se perfila no solo como uno de los títulos más influyentes del año, sino como un ejemplo claro del potencial creativo y ético de la escena independiente actual.', 1, '2025-07-17 19:45:25', 4, 32, 'Ramses Szobotka'),
(46, 'Nueva tecnología revolucionaria permite cargar celulares en solo 5 minutos', 'Un grupo de científicos e ingenieros en tecnología de baterías ha desarrollado una nueva generación de baterías de estado sólido que promete revolucionar la manera en la que cargamos nuestros dispositivos móviles. Esta batería avanzada puede recargar completamente un teléfono celular en tan solo cinco minutos, lo que representa un salto significativo respecto a las tecnologías actuales que suelen tardar horas.\r\n\r\nEl desarrollo combina nuevos materiales nanoestructurados y un sistema de gestión inteligente que evita el sobrecalentamiento y maximiza la vida útil de la batería. Además, este avance no solo se aplica a celulares, sino que también tiene potencial para vehículos eléctricos y otros dispositivos portátiles.\r\n\r\nLos investigadores explican que, aunque esta tecnología aún está en fase experimental, esperan que esté disponible comercialmente en un plazo de 3 a 5 años, tras superar las pruebas de seguridad y escalabilidad. La industria tecnológica ha recibido esta noticia con gran entusiasmo, ya que podría cambiar radicalmente la experiencia de los usuarios y acelerar la adopción de dispositivos eléctricos de alto rendimiento.', 3, '2025-07-17 19:49:33', 3, 34, 'Calvin Miro'),
(47, 'Panamá clasifica a la final de la Copa Oro 2025 tras histórica victoria ant', 'La selección nacional de fútbol de Panamá logró una de las victorias más memorables de su historia al derrotar a México por 3-2 en un emocionante partido semifinal de la Copa Oro 2025. El encuentro, celebrado en el Estadio Azteca ante más de 60,000 espectadores, fue un despliegue de talento, estrategia y pasión por parte de los jugadores panameños.\r\n\r\nEl partido comenzó con un México dominante, que abrió el marcador en los primeros minutos. Sin embargo, Panamá respondió rápidamente con dos goles en la primera mitad, destacándose el desempeño del delantero estrella que anotó un doblete. México logró igualar el marcador en el segundo tiempo, pero en los minutos finales, un contraataque letal permitió a Panamá anotar el gol decisivo que los llevó directo a la final.\r\n\r\nEste triunfo ha desatado celebraciones en todo el país, con miles de aficionados tomando las calles para festejar el hito deportivo. El equipo ahora se prepara para enfrentar a Estados Unidos en la gran final, con la esperanza de conquistar su primer título en este torneo y escribir una página dorada en el fútbol centroamericano.', 3, '2025-07-17 19:54:49', 1, 34, 'Roberto Chiari'),
(48, 'Avanza la construcción de la Línea 3 del Metro de Panamá con avances signif', 'La construcción de la esperada Línea 3 del Metro de Panamá continúa a buen ritmo, marcando un hito importante en la expansión del sistema de transporte público de la ciudad. Este proyecto, que conecta el área de la Carretera Panamericana con el centro de la ciudad, promete aliviar la congestión vehicular, reducir los tiempos de viaje y ofrecer una alternativa moderna y eficiente a miles de panameños que diariamente se movilizan en la capital.\r\n\r\nEn los primeros meses de 2025, las autoridades encargadas de la obra reportaron avances significativos en la excavación de túneles y en la instalación de la infraestructura ferroviaria. Hasta la fecha, se han completado más del 40% de las obras civiles, incluyendo estaciones y sistemas eléctricos. Además, se han instalado los primeros rieles en tramos estratégicos, lo que confirma que el proyecto mantiene su cronograma previsto.\r\n\r\nLa Línea 3 tendrá aproximadamente 25 kilómetros de longitud y contará con 14 estaciones, muchas de ellas interconectadas con las líneas existentes, facilitando la integración del sistema. Se espera que al entrar en operación, la línea beneficie a más de 200,000 pasajeros diariamente, mejorando notablemente la movilidad y contribuyendo a la reducción de la contaminación ambiental al disminuir el uso de vehículos particulares.\r\n\r\nLas autoridades también han destacado el compromiso con la seguridad y la sostenibilidad ambiental durante toda la ejecución del proyecto, incorporando tecnologías de última generación y prácticas responsables para minimizar el impacto en las comunidades y el ecosistema circundante.\r\n\r\nAunque algunos sectores han expresado inquietudes por los ajustes en la movilidad temporal y el impacto en comercios cercanos, el gobierno ha implementado planes de mitigación para apoyar a los afectados y garantizar que los beneficios a largo plazo superen ampliamente los inconvenientes temporales.\r\n\r\nLa Línea 3 del Metro de Panamá representa un paso fundamental en la modernización del transporte urbano y en el impulso al desarrollo económico y social de la capital, reafirmando la visión de construir una ciudad más conectada, sostenible y accesible para todos.', 1, '2025-07-17 19:57:08', 3, 34, 'Ruben Feng'),
(49, 'Tragedia en el mundo del automovilismo: Fallece el legendario Rayo McQueen ', 'En un suceso que ha conmocionado a fanáticos y expertos del automovilismo alrededor del mundo, el icónico Rayo McQueen, símbolo de velocidad y valentía en las pistas, falleció la madrugada del pasado sábado tras protagonizar un trágico accidente automovilístico. Las autoridades confirmaron que el choque fue provocado por un estado de ebriedad, desencadenado por problemas de alcoholismo que, hasta ahora, habían permanecido ocultos para el público.\r\n\r\nEl accidente ocurrió en una carretera rural, cuando McQueen perdió el control de su vehículo a alta velocidad y colisionó contra un árbol. Pese a la rápida respuesta de los servicios de emergencia, el piloto no pudo sobrevivir a las graves heridas sufridas. Según informes policiales, se detectaron altos niveles de alcohol en sangre, lo que apunta a que el consumo excesivo fue factor determinante en el siniestro.\r\n\r\nDurante años, McQueen fue admirado por su destreza y múltiples victorias en competencias internacionales. Sin embargo, fuentes cercanas al piloto revelaron que en los últimos meses había enfrentado serias luchas personales con el alcohol, algo que había intentado manejar en privado sin éxito.\r\n\r\nLa noticia ha generado un profundo impacto en la comunidad deportiva, que ahora se enfrenta al difícil debate sobre la salud mental y las presiones que enfrentan los atletas de élite. Organizaciones dedicadas a la prevención del alcoholismo y al apoyo psicológico han hecho un llamado para intensificar los programas de ayuda a deportistas.\r\n\r\nFamiliares, amigos y fanáticos han rendido homenaje a McQueen, recordándolo como un campeón dentro y fuera de las pistas. Se espera que en los próximos días se realice una ceremonia pública para despedir al corredor y celebrar su legado.\r\n\r\nEste trágico desenlace pone en evidencia la importancia de visibilizar y atender los problemas de adicciones, incluso entre aquellos que parecen invencibles, y abre una reflexión sobre la necesidad de apoyo integral para preservar la salud y bienestar de los deportistas.', 1, '2025-07-17 20:16:41', 4, 34, 'Ramses Szobotka'),
(50, 'Gobierno anuncia ambicioso plan de reforma política para fortalecer la tran', 'En una conferencia de prensa celebrada este jueves en el Palacio Presidencial, la presidenta de la República, Laura Montenegro, anunció la presentación oficial de un ambicioso proyecto de reforma política que busca transformar profundamente el sistema democrático del país. El paquete de medidas, titulado \"Reforma 2030: Por una Democracia Transparente\", incluye propuestas clave en materia de financiamiento electoral, rendición de cuentas, participación ciudadana y modernización institucional.\r\n\r\nSegún Montenegro, esta reforma surge como respuesta directa a las crecientes demandas de la ciudadanía por una clase política más ética, accesible y comprometida con el bien común. \"No podemos seguir ignorando la desconfianza que gran parte de la población siente hacia el sistema político. Esta reforma no es solo necesaria, es urgente\", afirmó durante su intervención.\r\n\r\nEntre las medidas más destacadas se encuentran:\r\n\r\nLa eliminación del fuero penal electoral, permitiendo que todos los candidatos y funcionarios públicos puedan ser investigados por corrupción sin restricciones durante las campañas.\r\n\r\nLa reducción del financiamiento estatal a partidos políticos, acompañado de un nuevo sistema de auditoría digital en tiempo real que permitirá a los ciudadanos monitorear cómo se utilizan los fondos públicos en campañas electorales.\r\n\r\nLa implementación de un mecanismo de revocatoria de mandato, a partir del segundo año de gestión, mediante el cual los ciudadanos podrán destituir a cualquier funcionario electo si no cumple con sus promesas de campaña o incurre en actos de corrupción.\r\n\r\nLa creación del Consejo Nacional de Participación Ciudadana, una instancia consultiva compuesta por representantes de la sociedad civil, universidades, gremios y pueblos originarios, que tendrá voz vinculante en la discusión de leyes clave.\r\n\r\nLa propuesta será enviada al Parlamento la próxima semana, donde se anticipa un amplio debate. Mientras tanto, líderes de oposición han expresado posturas mixtas: algunos elogian la intención de fortalecer la institucionalidad democrática, mientras que otros han cuestionado la \"velocidad\" con la que el gobierno busca aprobar la reforma, sugiriendo que podría haber intereses ocultos detrás de ciertas cláusulas.\r\n\r\nOrganizaciones ciudadanas y analistas políticos han aplaudido el carácter integral del proyecto, aunque también han advertido que su éxito dependerá en gran parte de su implementación efectiva y de la voluntad real de todos los actores políticos de romper con las prácticas del pasado.\r\n\r\nDe ser aprobada, la Reforma 2030 podría marcar un antes y un después en la historia democrática del país, posicionándolo como un referente regional en transparencia, innovación institucional y empoderamiento ciudadano.', 1, '2025-07-17 20:28:28', 2, 34, 'Jose Raul Mulino'),
(51, 'Aprende Mewing en 3 sencillos pasos!!!!', '¿Quieres lucir una mandíbula digna de un dios griego sin cirugía ni gimnasio facial? ¡El mewing es la tendencia viral que lo está revolucionando todo! Con solo seguir tres sencillos pasos podrás presumir de perfil marcado, o al menos eso prometen los gurús del internet que juraron que funcionaba.\r\n\r\nPaso 1: Coloca la lengua donde nadie pensó que debía ir\r\nSí, has leído bien. Para hacer mewing tienes que pegar la lengua en el paladar, como si tuvieras un caramelo pegado en el techo de la boca, pero sin el caramelo. No te preocupes si al principio parece incómodo o te provoca ganas de hablar como Darth Vader; dicen que es parte del proceso.\r\n\r\nPaso 2: Respira por la nariz como si te fuera la vida en ello\r\nOlvídate de respirar por la boca —eso está pasado de moda—. Respira por la nariz con tal devoción que podrías competir en una maratón nasal. Recuerda que una respiración correcta es clave para que tu mandíbula no termine pareciendo un bocadillo aplastado.\r\n\r\nPaso 3: No te olvides de sonreír y esperar... pacientemente\r\nComo todo en la vida, la magia no es instantánea. El secreto está en ser constante y mantener la lengua en la posición correcta mientras evitas mirar selfies durante los primeros tres meses, para no deprimirte. Los influencers aseguran que los cambios se notan, pero también podrían estar vendiendo cursos premium.\r\n\r\nAunque los expertos reales siguen debatiendo si el mewing tiene efectos reales o si es solo otra moda pasajera, miles de jóvenes ya han convertido este ejercicio en su ritual diario, junto con el café y las stories de Instagram.\r\n\r\n¿El resultado? Rostros con mandíbula tan marcadas que podrían cortar el aire... o al menos un montón de memes nuevos en internet.\r\n\r\nAsí que, si quieres probar el mewing, recuerda: lengua arriba, nariz abierta y paciencia de santo. ¡Quién sabe! Quizás dentro de unos meses seas la envidia de tus amigos o al menos la estrella de los grupos de WhatsApp con tus selfies raros.', 1, '2025-07-18 00:05:45', 1, 37, 'Arena Rosa'),
(52, '“Grow a Garden”, el juego de Roblox que superó a Fortnite y rompió récords', '“Grow a Garden”, un relajante simulador de jardinería lanzado en Roblox el 26 de marzo de 2025, alcanzó una cifra histórica: más de 21 millones de jugadores conectados al mismo tiempo el 21 de junio, superando incluso a Fortnite, cuyo récord anterior era de 15 millones. \r\n\r\nEl juego —que permite plantar semillas, decorar jardines y recibir recompensas offline— fue creado originalmente por un joven desarrollador en apenas tres días, y luego respaldado por estudios como Splitting Point y Do Big Studios.\r\n\r\nSu jugabilidad sencilla y atractiva ha cautivado a millones, especialmente a jugadores menores de 13 años, con picos de hasta 16,4 millones al mismo tiempo en junio .\r\n\r\nEl éxito ha llevado a Roblox a convertirse en una poderosa plataforma de creador‑economía, generando ingresos millonarios para los desarrolladores y elevando a “Grow a Garden” como un símbolo de lo que un juego indie puede lograr en un entorno de desarrollo abierto', 2, '2025-07-23 19:44:05', 4, 32, 'Kelvin He');

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
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Última actualización',
  PRIMARY KEY (`id`),
  UNIQUE KEY `UQ_usuario` (`usuario`)
) ENGINE=MyISAM AUTO_INCREMENT=40 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci COMMENT='Usuarios';

--
-- Dumping data for table `usuarios`
--

INSERT INTO `usuarios` (`id`, `nombre`, `apellido`, `usuario`, `contrasena`, `rol`, `activo`, `create_time`, `updated_at`) VALUES
(32, 'admin', 'admin', 'admin', '$2y$10$Bkkpu/8ST2gz9WX/ynCcGOOcA/OXYDeWQctnFHVha/NVYBT3PfUXi', 'admin', 1, '2025-07-17 19:37:32', '2025-07-24 17:47:01'),
(33, 'supervisor', 'Roman', 'supervisor', '$2y$10$AlxH107eDZSGBSLvDZY2r.kEhzD3zpf/T2UZ..dAXsFj/9JMsBSjG', 'supervisor', 1, '2025-07-17 19:41:50', '2025-07-17 19:41:50'),
(34, 'Ricardo', 'Martinelli', 'editor', '$2y$10$o7PHmS.l4dos.IE20Nx.yuGK6ck19T7LqjjWePJWfVtI237H1tSSO', 'editor', 1, '2025-07-17 19:42:57', '2025-07-23 23:24:23'),
(35, 'Balatro', 'Balatrez', 'Balatro Balatrez', '$2y$10$pBReWC3vWt403Q27KvyUaeAJ.0dGeHXsc8lALaEYVE2wwZRFV2MNG', 'global', 1, '2025-07-17 19:43:40', '2025-07-24 17:43:12'),
(36, 'global', 'global', 'global', '$2y$10$45TTO8q8KDj5L4c8dqF2t.7kfJBirtHN3U2jpZZpm2lp2.Ru3aNqe', 'global', 1, '2025-07-17 19:44:03', '2025-07-24 17:57:38'),
(37, 'Vlastos', 'Kr', 'V.kr', '$2y$10$2uLFL4ERguyYN4Fi3oMwtekgzw347.6ZbQ6mE54d2wZAAhg9l4Jtu', 'admin', 1, '2025-07-17 20:19:11', '2025-07-24 18:00:07'),
(38, 'Aldo', 'Bovel', 'AsaltaAbuelas3000', '$2y$10$aii7n8nP/m9RT//KLI0QTeg0ZXG47lkBwdyL5y4wNVciWj3TU.wVW', 'global', 1, '2025-07-23 18:36:32', '2025-07-24 17:57:59'),
(39, 'Miguel ', 'Hernandez', 'ultrakiller1945', '$2y$10$pQYOG7.5mmwJ0uJDnPQ..OV9oKdLSDXelnxY3208a7Mhmg0Ekk/Qy', 'global', 1, '2025-07-24 17:56:26', '2025-07-24 17:57:42');

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
(1, 1675);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

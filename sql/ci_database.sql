-- phpMyAdmin SQL Dump
-- version 4.2.11
-- http://www.phpmyadmin.net
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 02-11-2015 a las 13:50:33
-- Versión del servidor: 5.6.21
-- Versión de PHP: 5.5.19

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de datos: `ci_database`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `clientes`
--

CREATE TABLE IF NOT EXISTS `clientes` (
`id` int(10) NOT NULL,
  `tipo_persona` varchar(150) COLLATE utf8_spanish_ci NOT NULL,
  `tipo_doc` varchar(150) COLLATE utf8_spanish_ci NOT NULL,
  `nro_documento` varchar(50) COLLATE utf8_spanish_ci NOT NULL,
  `razon_social` text COLLATE utf8_spanish_ci NOT NULL,
  `direccion` varchar(150) COLLATE utf8_spanish_ci NOT NULL,
  `email` varchar(150) COLLATE utf8_spanish_ci NOT NULL,
  `telefono` int(150) NOT NULL,
  `celular` int(150) NOT NULL,
  `representante` varchar(150) COLLATE utf8_spanish_ci NOT NULL,
  `localidad` varchar(150) COLLATE utf8_spanish_ci NOT NULL,
  `tienda` varchar(150) COLLATE utf8_spanish_ci NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=121 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `clientes`
--

INSERT INTO `clientes` (`id`, `tipo_persona`, `tipo_doc`, `nro_documento`, `razon_social`, `direccion`, `email`, `telefono`, `celular`, `representante`, `localidad`, `tienda`) VALUES
(1, 'Juridico', 'RUC', '1073061479', 'Desarrollo de Softawre y Sitios Web SAC', 'AV Lima 180 stand 50 - Lima Lima', 'gerencia@gerencia.com', 1234654, 989456321, 'Renzo Carlos', 'Lima - Lima', 'Tienda 1'),
(2, 'Natural', 'RUC', '1009104942', 'Creaciones y Diseños Pepito EIRL', 'Av. Los Olvidados de Dios 440 - Lima - Lima', 'ventas@creacionesp.com', 4567984, 987654312, 'Pepito Vargas', 'Lima - Lima', 'Global'),
(110, 'juridico', '', '2147483647', 'Prueba Final', 'Av. Bolivia 180 lima', 'ikarus_94@hotmail.com', 45465456, 954789123, 'dsafasdfdsaf', 'dsafdsfadsf', 'tienda1'),
(111, 'juridico', 'RUC', '2147483647', 'FIna Final', 'Av. Bolivia 180 lima', 'email_e@asd.com', 2147483647, 12365498, 'Abecedario', 'asdfdasfdsa', 'tienda1'),
(112, 'natural', 'RUC', '73061647', 'Agencia Uando', 'Av. Huaylas 744', 'ale.derzz@outlook.com', 0, 975, 'Renzo Carlos Castañeda', 'Chorrillos - Lima - Lima', 'tienda1'),
(113, 'juridico', 'RUC', '73061647', 'ZTa Ceces Oca', 'Av. Puno 457', 'asdsaddsa@safsad.com', 0, 987, 'Renzo Carlos Castañeda', 'Lima - Lima', 'tienda2'),
(114, 'juridico', 'RUC', '789546213', 'Xyz ozner', 'Av. Calle Ocho 450', 'email_e@asd.com', 0, 465, 'dsafdsafds', 'Lima - Lima', 'global'),
(115, 'juridico', 'RUC', '2147483647', 'Cliente Demo', 'Av. Bolivia 180 lima - Lima', '1@w.com', 0, 0, '', 'Lima - Lima', 'tienda1'),
(116, 'juridico', 'RUC', '9104942', 'Razon de Prueba', 'Sin direccion', 'web@lenguajevisual.pe', 0, 987, 'Demo', 'Lima - Lima', 'tienda1'),
(117, 'juridico', 'RUC', '4578', 'Aqui tu Razon Social', 'Av. Bolivia 180 lima', 'asdsaddsa@safsad.com', 0, 987, 'Renzo Carlos Castañeda', 'Lima - Lima', 'tienda1'),
(118, 'juridico', 'RUC', '456789', 'FIna Final', 'AV Lima 180 stand 50 - Lima Lima', 'email_e@asd.com', 0, 789, 'Abecedario', 'Lima - Lima', 'tienda1'),
(119, 'juridico', 'RUC', '09104942', 'Aqui tu Razon Social', 'Av. Bolivia 180 lima', 'web@lenguajevisual.pe', 0, 978, 'Renzo', 'Lima - Lima', 'tienda1'),
(120, 'juridico', 'RUC', '0123456789', 'Nueva Razon Social', 'Av. Bolivia 180 lima', 'demo@demo.com', 0, 879, 'Angello', 'Lima - Lima', 'tienda1');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `facturacion`
--

CREATE TABLE IF NOT EXISTS `facturacion` (
`id` int(10) NOT NULL,
  `id_factura` int(9) unsigned zerofill NOT NULL,
  `id_cliente` varchar(20) COLLATE utf8_spanish_ci NOT NULL,
  `razon_social` text COLLATE utf8_spanish_ci NOT NULL,
  `tipo_documento` varchar(20) COLLATE utf8_spanish_ci NOT NULL,
  `serie` int(3) unsigned zerofill NOT NULL,
  `correlativo` int(6) unsigned zerofill NOT NULL,
  `moneda` varchar(30) COLLATE utf8_spanish_ci NOT NULL,
  `monto` text COLLATE utf8_spanish_ci NOT NULL,
  `fecha_emision` varchar(20) COLLATE utf8_spanish_ci NOT NULL,
  `fecha_cancelacion` varchar(20) COLLATE utf8_spanish_ci NOT NULL,
  `tipo_pago` varchar(20) COLLATE utf8_spanish_ci NOT NULL,
  `estado` int(1) NOT NULL,
  `igv` int(1) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `facturacion`
--

INSERT INTO `facturacion` (`id`, `id_factura`, `id_cliente`, `razon_social`, `tipo_documento`, `serie`, `correlativo`, `moneda`, `monto`, `fecha_emision`, `fecha_cancelacion`, `tipo_pago`, `estado`, `igv`) VALUES
(3, 001000123, '2', 'Creaciones y Diseños Pepito EIRL', 'Factura', 001, 000123, 'soles', '75.00', '27/09/2015', '27/09/2015', 'Contado', 1, 0),
(4, 002000015, '112', 'Agencia Uando', 'Factura', 002, 000015, 'soles', '12.60', '04/10/2015', '04/10/2015', 'Contado', 1, 1),
(5, 001000015, '1', 'Desarrollo de Softawre y Sitios Web SAC', 'Factura', 001, 000015, 'soles', '23.60', '04/10/2015', '07/10/2015', 'Contado', 1, 0),
(6, 001000017, '110', 'Prueba Final', 'Factura', 001, 000017, 'soles', '152.69', '04/10/2015', '04/10/2015', 'Contado', 1, 0),
(7, 001000010, '1', 'Desarrollo de Softawre y Sitios Web SAC', 'Factura', 001, 000010, 'soles', '16.00', '08/10/2015', '08/10/2015', 'Contado', 1, 0),
(8, 001001234, '110', 'Prueba Final', 'Factura', 001, 001234, 'soles', '18.88', '08/10/2015', '08/10/2015', 'Contado', 1, 0),
(9, 001004514, '2', 'Creaciones y Diseños Pepito EIRL', 'Factura', 001, 004514, 'soles', '14.16', '08/10/2015', '08/10/2015', 'Contado', 1, 0),
(10, 001004514, '2', 'Creaciones y Diseños Pepito EIRL', 'Factura', 001, 004514, 'soles', '14.16', '08/10/2015', '08/10/2015', 'Contado', 1, 0),
(11, 001004514, '2', 'Creaciones y Diseños Pepito EIRL', 'Factura', 001, 004514, 'soles', '14.16', '08/10/2015', '08/10/2015', 'Contado', 1, 0),
(12, 001004242, '1', 'Desarrollo de Softawre y Sitios Web SAC', 'Factura', 001, 004242, 'soles', '141.60', '08/10/2015', '08/10/2015', 'Contado', 1, 0),
(13, 000000001, '110', 'Prueba Final', 'Factura', 001, 000000, 'soles', '82.60', '08/10/2015', '08/10/2015', 'Contado', 1, 0),
(14, 000000001, '110', 'Prueba Final', 'Factura', 001, 000000, 'soles', '82.60', '08/10/2015', '08/10/2015', 'Contado', 1, 0),
(15, 001003030, '115', 'Cliente Demo', 'Factura', 001, 003030, 'soles', '531.00', '08/10/2015', '08/10/2015', 'Contado', 1, 0),
(16, 001003030, '115', 'Cliente Demo', 'Factura', 001, 003030, 'Soles', '2850.00', '08/10/2015', '08/10/2015', 'Contado', 1, 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `items`
--

CREATE TABLE IF NOT EXISTS `items` (
`id` int(10) NOT NULL,
  `id_factura` int(9) unsigned zerofill NOT NULL,
  `producto` varchar(100) NOT NULL,
  `cantidad` int(4) NOT NULL,
  `precio_unit` decimal(8,2) NOT NULL,
  `precio` decimal(13,2) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `items`
--

INSERT INTO `items` (`id`, `id_factura`, `producto`, `cantidad`, `precio_unit`, `precio`) VALUES
(3, 001000123, 'Producto XX', 4, '10.00', '40.00'),
(4, 001000123, 'Producto YY', 5, '7.00', '35.00'),
(5, 002000015, 'Producto Dos', 3, '4.20', '12.60'),
(6, 001000015, 'Producto Dos', 4, '5.00', '20.00'),
(7, 001000017, 'Producto Dos', 4, '4.00', '16.00'),
(8, 001000017, 'Producto Uno', 6, '6.00', '36.00'),
(9, 001000017, 'Producto Tres', 2, '4.00', '8.00'),
(10, 001000017, 'Producto Cuatro', 4, '7.00', '28.00'),
(11, 001000017, 'Producto Cinco', 3, '3.00', '9.00'),
(12, 001000017, 'Producto Seis', 2, '5.00', '10.00'),
(13, 001000017, 'Producto Siete', 7, '3.20', '22.40'),
(14, 000000000, 'Producto Nuevo ZZZZZZ', 4, '4.00', '16.00'),
(15, 000000000, 'Gorras', 4, '4.00', '16.00'),
(16, 000000000, '', 0, '0.00', '0.00'),
(17, 000000000, 'Productos', 3, '4.00', '12.00'),
(18, 001003030, 'Productos Ropa', 3, '150.00', '450.00');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `login`
--

CREATE TABLE IF NOT EXISTS `login` (
`id` int(10) NOT NULL,
  `usuario` varchar(200) COLLATE utf8_spanish_ci NOT NULL,
  `pass` varchar(200) COLLATE utf8_spanish_ci NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `login`
--

INSERT INTO `login` (`id`, `usuario`, `pass`) VALUES
(1, 'demo', 'demo');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos`
--

CREATE TABLE IF NOT EXISTS `productos` (
`id_producto` int(10) unsigned zerofill NOT NULL,
  `sku` int(10) unsigned zerofill NOT NULL,
  `nombre_producto` text NOT NULL,
  `cantidad` int(20) NOT NULL,
  `vendidos` int(20) NOT NULL,
  `precio_unit` double NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `productos`
--

INSERT INTO `productos` (`id_producto`, `sku`, `nombre_producto`, `cantidad`, `vendidos`, `precio_unit`) VALUES
(0000000001, 0000000001, 'Hosting Nivel 1 500MB', 100, 0, 150),
(0000000002, 0000000000, 'sadsa', 45, 0, 45),
(0000000003, 0000000002, 'Hosting Nivel 2 1000MB', 50, 0, 200),
(0000000004, 0000000003, 'Hosting Nivel 3 1500MB', 50, 0, 250),
(0000000005, 0000000008, 'Hosting Nivel 4 2000 MB', 50, 0, 95),
(0000000006, 0000045789, 'Desodorante Aval', 3, 0, 50);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE IF NOT EXISTS `usuarios` (
`id` int(10) NOT NULL,
  `usuario` varchar(200) COLLATE utf8_spanish_ci NOT NULL,
  `password` varchar(200) COLLATE utf8_spanish_ci NOT NULL,
  `email` varchar(200) COLLATE utf8_spanish_ci NOT NULL,
  `rol` varchar(15) COLLATE utf8_spanish_ci NOT NULL,
  `nombre` text COLLATE utf8_spanish_ci NOT NULL,
  `avatar_uri` text COLLATE utf8_spanish_ci NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `usuario`, `password`, `email`, `rol`, `nombre`, `avatar_uri`) VALUES
(1, 'admin', 'fe01ce2a7fbac8fafaed7c982a04e229', 'ikarus_94@hotmail.com', 'administrador', 'Admin', 'img/avatar-default.png');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `clientes`
--
ALTER TABLE `clientes`
 ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `facturacion`
--
ALTER TABLE `facturacion`
 ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `items`
--
ALTER TABLE `items`
 ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `login`
--
ALTER TABLE `login`
 ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `productos`
--
ALTER TABLE `productos`
 ADD PRIMARY KEY (`id_producto`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
 ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `usuario` (`usuario`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `clientes`
--
ALTER TABLE `clientes`
MODIFY `id` int(10) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=121;
--
-- AUTO_INCREMENT de la tabla `facturacion`
--
ALTER TABLE `facturacion`
MODIFY `id` int(10) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=17;
--
-- AUTO_INCREMENT de la tabla `items`
--
ALTER TABLE `items`
MODIFY `id` int(10) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=19;
--
-- AUTO_INCREMENT de la tabla `login`
--
ALTER TABLE `login`
MODIFY `id` int(10) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT de la tabla `productos`
--
ALTER TABLE `productos`
MODIFY `id_producto` int(10) unsigned zerofill NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
MODIFY `id` int(10) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

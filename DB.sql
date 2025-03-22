-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 19-03-2025 a las 22:16:48
-- Versión del servidor: 10.4.28-MariaDB
-- Versión de PHP: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `bd_electrodomesticos`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `clientes`
--

CREATE TABLE `clientes` (
  `id` int(11) NOT NULL,
  `nombre_completo` varchar(100) NOT NULL,
  `celular` varchar(20) NOT NULL,
  `articulo` varchar(50) NOT NULL,
  `valor` decimal(10,2) NOT NULL,
  `monto_restante` decimal(10,2) NOT NULL,
  `estado_pago` varchar(50) NOT NULL,
  `fecha_compra` date NOT NULL, 
  `id_usuario` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `clientes`
--

INSERT INTO `clientes` (`id`, `nombre_completo`, `celular`, `articulo`, `valor`, `monto_restante`, `estado_pago`, `fecha_compra`) VALUES
(1, 'Alan', '75412368', 'Plancha', 500.00, 0.00, 'CANCELADO', '2025-03-18'),
(2, 'MARCOS CALLANCHO', '75251453', 'Lavadora', 2000.00, 0.00, 'PENDIENTE', '2025-03-19'),
(3, 'JOSE CARLOS', '78541236', 'Licuadora', 1000.00, 0.00, 'CANCELADO', '2025-03-13'),
(4, 'JUAQUIN', '78945612', 'Licuadora', 1000.00, 0.00, 'CANCELADO', '2025-03-05'),
(5, 'MIGUEL', '74185296', 'Plancha', 500.00, 200.00, 'PENDIENTE', '2025-03-11'),
(6, 'BORIS', '74185263', 'Plancha', 500.00, 600.00, 'PENDIENTE', '2025-03-18'),
(7, 'ALAN', '77777777', 'TV', 3000.00, 3600.00, 'PENDIENTE', '2025-03-19'),
(8, 'ALVARO', '74185241', 'Plancha', 500.00, 600.00, 'PENDIENTE', '2025-03-19'),
(9, 'PAULO DYBALA', '75421365', 'Lavadora', 2000.00, 1800.00, 'PENDIENTE', '2025-03-19');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `gastos_articulo_credito`
--

CREATE TABLE `gastos_articulo_credito` (
  `id` int(11) NOT NULL,
  `nombre_cliente` varchar(255) NOT NULL,
  `articulo` varchar(255) NOT NULL,
  `valor` decimal(10,2) NOT NULL,
  `fecha_compra` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `gastos_articulo_credito`
--

INSERT INTO `gastos_articulo_credito` (`id`, `nombre_cliente`, `articulo`, `valor`, `fecha_compra`) VALUES
(1, 'ALVARO', 'Plancha', 500.00, '2025-03-19 00:00:00'),
(2, 'PAULO DYBALA', 'Lavadora', 2000.00, '2025-03-19 00:00:00');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `gastos_internos`
--

CREATE TABLE `gastos_internos` (
  `id` int(11) NOT NULL,
  `nombre_gasto` varchar(255) NOT NULL,
  `monto_gasto` decimal(10,2) NOT NULL,
  `descripcion` text NOT NULL,
  `fecha` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `gastos_internos`
--

INSERT INTO `gastos_internos` (`id`, `nombre_gasto`, `monto_gasto`, `descripcion`, `fecha`) VALUES
(1, 'COMIDA', 50.00, 'ASADO', '2025-03-19 21:07:54'),
(2, 'PASAJES', 10.00, 'cruce', '2025-03-19 21:12:47'),
(3, 'COCA COLA', 10.00, 'consumo dia', '2025-03-19 22:14:58');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pagos`
--

CREATE TABLE `pagos` (
  `id` int(11) NOT NULL,
  `id_cliente` int(11) NOT NULL,
  `fecha_pago` datetime NOT NULL DEFAULT current_timestamp(),
  `monto_pagado` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `pagos`
--

INSERT INTO `pagos` (`id`, `id_cliente`, `fecha_pago`, `monto_pagado`) VALUES
(1, 1, '2025-03-19 09:59:58', 20.00),
(2, 1, '2025-03-19 10:10:43', 80.00),
(3, 1, '2025-03-19 10:10:59', 70.00),
(4, 1, '2025-03-19 10:11:08', 400.00),
(5, 1, '2025-03-19 10:11:19', 30.00),
(6, 3, '2025-03-19 10:14:00', 200.00),
(7, 3, '2025-03-19 10:14:05', 1000.00),
(8, 4, '2025-03-19 12:06:25', 200.00),
(9, 4, '2025-03-19 13:28:42', 500.00),
(10, 4, '2025-03-19 13:29:05', 500.00),
(11, 5, '2025-03-19 13:49:46', 200.00),
(12, 5, '2025-03-19 14:42:03', 200.00),
(13, 9, '2025-03-19 17:12:46', 400.00),
(14, 9, '2025-03-19 17:13:05', 200.00);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pagos_realizados`
--

CREATE TABLE `pagos_realizados` (
  `id` int(11) NOT NULL,
  `id_cliente` int(11) DEFAULT NULL,
  `fecha_pago` date DEFAULT NULL,
  `monto` decimal(10,2) DEFAULT NULL,
  `numero_pago` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `rol` enum('admin','usuario') NOT NULL DEFAULT 'usuario'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `nombre`, `email`, `password`, `rol`) VALUES
(1, 'jose carlos', 'jc@gmail.com', '$2y$10$WAWG15b6OsZUNySs1uX43.WuDcd1IYASTI3d7iWjPPf3J8sy3CxOi', 'usuario'),
(2, 'roberto', 'ro@gmail.com', '$2y$10$6Ieza9ZYB0AytV8ivoBVGO5VhSWVmha0muZTMXIgNUd0ZtdW2U20a', 'usuario');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `clientes`
--
ALTER TABLE `clientes`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `gastos_articulo_credito`
--
ALTER TABLE `gastos_articulo_credito`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `gastos_internos`
--
ALTER TABLE `gastos_internos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `pagos`
--
ALTER TABLE `pagos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_cliente` (`id_cliente`);

--
-- Indices de la tabla `pagos_realizados`
--
ALTER TABLE `pagos_realizados`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_cliente` (`id_cliente`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `clientes`
--
ALTER TABLE `clientes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `gastos_articulo_credito`
--
ALTER TABLE `gastos_articulo_credito`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `gastos_internos`
--
ALTER TABLE `gastos_internos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `pagos`
--
ALTER TABLE `pagos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT de la tabla `pagos_realizados`
--
ALTER TABLE `pagos_realizados`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `pagos`
--
ALTER TABLE `pagos`
  ADD CONSTRAINT `pagos_ibfk_1` FOREIGN KEY (`id_cliente`) REFERENCES `clientes` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `pagos_realizados`
--
ALTER TABLE `pagos_realizados`
  ADD CONSTRAINT `pagos_realizados_ibfk_1` FOREIGN KEY (`id_cliente`) REFERENCES `clientes` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 02-11-2026 a las 00:50:03
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `tienda_virtual`
--

DELIMITER $$
--
-- Procedimientos
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_actualizar_stock_variante` (IN `p_id_variante` INT, IN `p_cantidad` INT, IN `p_tipo_movimiento` ENUM('Entrada','Salida'), IN `p_motivo` VARCHAR(255))   BEGIN
    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        ROLLBACK;
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Error al actualizar stock';
    END;

    START TRANSACTION;

    IF p_tipo_movimiento = 'Entrada' THEN
        UPDATE VARIANTE_PRODUCTO SET Stock_Actual = Stock_Actual + p_cantidad WHERE Id_variante = p_id_variante;
    ELSE
        UPDATE VARIANTE_PRODUCTO SET Stock_Actual = Stock_Actual - p_cantidad WHERE Id_variante = p_id_variante;
    END IF;

    INSERT INTO MOVIMIENTO_STOCK (Id_variante, Tipo_Movimiento, Cantidad, Motivo)
    VALUES (p_id_variante, p_tipo_movimiento, p_cantidad, p_motivo);

    COMMIT;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_crear_pedido` (IN `p_id_cliente` INT, IN `p_id_direccion` INT, IN `p_id_metodo_envio` INT, IN `p_id_metodo_pago` INT, OUT `p_id_pedido` INT, OUT `p_mensaje` VARCHAR(255))   BEGIN
    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        ROLLBACK;
        SET p_mensaje = 'Error al crear el pedido';
        SET p_id_pedido = NULL;
    END;

    START TRANSACTION;

    -- Verificar que el carrito no esté vacío
    IF NOT EXISTS (SELECT 1 FROM ITEM_CARRITO ic
                   JOIN CARRITO c ON ic.Id_carrito = c.Id_carrito
                   WHERE c.Id_cliente = p_id_cliente) THEN
        SET p_mensaje = 'El carrito está vacío';
        SET p_id_pedido = NULL;
        ROLLBACK;
    ELSE
        -- Insertar pedido
        INSERT INTO PEDIDO (Id_cliente, Id_direccion, Id_metodo_envio, Subtotal, Total_Final)
        VALUES (p_id_cliente, p_id_direccion, p_id_metodo_envio, 0, 0);
        SET p_id_pedido = LAST_INSERT_ID();

        -- Insertar detalles desde el carrito
        INSERT INTO DETALLE_PEDIDO (Id_pedido, Id_variante, Cantidad, Precio_Unitario_Cobrado)
        SELECT p_id_pedido, ic.Id_variante, ic.Cantidad, v.Precio_Venta
        FROM ITEM_CARRITO ic
        JOIN CARRITO c ON ic.Id_carrito = c.Id_carrito
        JOIN VARIANTE_PRODUCTO v ON ic.Id_variante = v.Id_variante
        WHERE c.Id_cliente = p_id_cliente;

        -- Actualizar subtotal y total del pedido
        UPDATE PEDIDO
        SET Subtotal = (SELECT SUM(Cantidad * Precio_Unitario_Cobrado) FROM DETALLE_PEDIDO WHERE Id_pedido = p_id_pedido),
            Total_Final = Subtotal
        WHERE Id_pedido = p_id_pedido;

        -- Descontar stock
        UPDATE VARIANTE_PRODUCTO v
        JOIN DETALLE_PEDIDO d ON v.Id_variante = d.Id_variante
        SET v.Stock_Actual = v.Stock_Actual - d.Cantidad
        WHERE d.Id_pedido = p_id_pedido;

        -- Registrar movimientos de stock
        INSERT INTO MOVIMIENTO_STOCK (Id_variante, Tipo_Movimiento, Cantidad, Motivo)
        SELECT Id_variante, 'Salida', Cantidad, CONCAT('Pedido #', p_id_pedido)
        FROM DETALLE_PEDIDO WHERE Id_pedido = p_id_pedido;

        -- Vaciar carrito
        DELETE FROM ITEM_CARRITO
        WHERE Id_carrito = (SELECT Id_carrito FROM CARRITO WHERE Id_cliente = p_id_cliente);

        -- Registrar transacción de pago (simulacion q se va a usar en mp)
        INSERT INTO TRANSACCION_PAGO (Id_pedido, Id_metodo_pago, Monto, Estado_Pago, Referencia_Pasarela)
        VALUES (p_id_pedido, p_id_metodo_pago, (SELECT Total_Final FROM PEDIDO WHERE Id_pedido = p_id_pedido), 'Aprobado', 'REF-PAGO');

        SET p_mensaje = 'Pedido creado exitosamente';
        COMMIT;
    END IF;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `bebe`
--

CREATE TABLE `bebe` (
  `Id_bebe` int(11) NOT NULL,
  `Id_cliente` int(11) NOT NULL,
  `Nombre` varchar(50) NOT NULL,
  `Fecha_Nacimiento` date NOT NULL,
  `Genero` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `carrito`
--

CREATE TABLE `carrito` (
  `Id_carrito` int(11) NOT NULL,
  `Id_cliente` int(11) NOT NULL,
  `Fecha_Creacion` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `carrito`
--

INSERT INTO `carrito` (`Id_carrito`, `Id_cliente`, `Fecha_Creacion`) VALUES
(3, 6, '2026-10-29 19:06:36'),
(4, 8, '2026-10-29 20:21:58');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categoria`
--

CREATE TABLE `categoria` (
  `Id_categoria` int(11) NOT NULL,
  `Nombre` varchar(50) NOT NULL,
  `Descripcion` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `categoria`
--

INSERT INTO `categoria` (`Id_categoria`, `Nombre`, `Descripcion`) VALUES
(1, 'Bodys', 'Prendas de cuerpo entero'),
(2, 'Pantalones', 'Pantalones y joggers'),
(5, 'Gorritos', 'Gorritos, baberos y escarpines');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cliente`
--

CREATE TABLE `cliente` (
  `Id_cliente` int(11) NOT NULL,
  `Id_usuario` int(11) NOT NULL,
  `Nombre` varchar(50) NOT NULL,
  `Apellido` varchar(50) NOT NULL,
  `DNI_CUIT` varchar(20) DEFAULT NULL,
  `Telefono` varchar(20) DEFAULT NULL,
  `Condicion_IVA` enum('Consumidor Final','Resp. Inscripto') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `cliente`
--

INSERT INTO `cliente` (`Id_cliente`, `Id_usuario`, `Nombre`, `Apellido`, `DNI_CUIT`, `Telefono`, `Condicion_IVA`) VALUES
(5, 7, 'Luciano', 'Cadima', NULL, '+54 1125024824', 'Consumidor Final'),
(6, 8, 'Ricardo', 'Espinoza', '12345678', '11225029128', 'Consumidor Final'),
(7, 9, 'Damian', 'Cadima', NULL, '00000000', 'Consumidor Final'),
(8, 10, 'J.Epstein', '', NULL, '00000000', 'Consumidor Final');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_pedido`
--

CREATE TABLE `detalle_pedido` (
  `Id_detalle` int(11) NOT NULL,
  `Id_pedido` int(11) NOT NULL,
  `Id_variante` int(11) NOT NULL,
  `Cantidad` int(11) NOT NULL CHECK (`Cantidad` > 0),
  `Precio_Unitario_Cobrado` decimal(10,2) NOT NULL,
  `Costo_Unitario_Fabricacion` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `detalle_pedido`
--

INSERT INTO `detalle_pedido` (`Id_detalle`, `Id_pedido`, `Id_variante`, `Cantidad`, `Precio_Unitario_Cobrado`, `Costo_Unitario_Fabricacion`) VALUES
(4, 3, 1, 1, 2500.00, NULL),
(5, 4, 1, 1, 2500.00, NULL),
(6, 5, 8, 3, 11000.00, NULL),
(7, 6, 9, 2, 4500.00, NULL),
(8, 7, 4, 1, 8500.00, NULL),
(9, 8, 3, 1, 3200.00, NULL),
(10, 9, 4, 1, 8500.00, NULL),
(11, 9, 9, 2, 4500.00, NULL),
(12, 10, 1, 1, 2500.00, NULL),
(13, 11, 1, 6, 2500.00, NULL),
(14, 12, 1, 1, 2500.00, NULL),
(15, 13, 8, 1, 11000.00, NULL),
(16, 14, 1, 1, 2500.00, NULL),
(17, 15, 3, 1, 3200.00, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `direccion`
--

CREATE TABLE `direccion` (
  `Id_direccion` int(11) NOT NULL,
  `Id_cliente` int(11) NOT NULL,
  `Calle_Numero` varchar(100) NOT NULL,
  `Calle` varchar(100) DEFAULT NULL,
  `Numero` varchar(10) DEFAULT NULL,
  `Departamento` varchar(20) DEFAULT NULL,
  `Barrio` varchar(50) DEFAULT NULL,
  `Ciudad` varchar(50) NOT NULL,
  `Provincia` varchar(50) NOT NULL,
  `Codigo_Postal` varchar(20) DEFAULT NULL,
  `Tipo` enum('Envio','Facturacion') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `direccion`
--

INSERT INTO `direccion` (`Id_direccion`, `Id_cliente`, `Calle_Numero`, `Calle`, `Numero`, `Departamento`, `Barrio`, `Ciudad`, `Provincia`, `Codigo_Postal`, `Tipo`) VALUES
(5, 5, 'Guamini5511', NULL, NULL, NULL, NULL, 'Caba', 'Buenos Aires', '1439', 'Envio'),
(6, 6, 'Guamini5511', NULL, NULL, NULL, NULL, 'Caba', 'Buenos Aires', '1439', 'Envio'),
(7, 7, 'Guamini5511', NULL, NULL, NULL, NULL, 'Caba', 'Buenos Aires', '1439', 'Envio'),
(8, 7, 'Guamini 5511, Dpto 23, Barrio qwe', NULL, NULL, NULL, NULL, 'Caba', 'Buenos Aires', '1439', 'Envio'),
(9, 6, 'Guaminí 5511, Dpto 234, Barrio Lugano', NULL, NULL, NULL, NULL, 'caba', 'Buenos Aires', '1439', 'Envio'),
(10, 6, 'Guamini 5511, Dpto 23, Barrio Lugano', NULL, NULL, NULL, NULL, 'Caba', 'Buenos Aires', '1439', 'Envio'),
(11, 6, 'Guaminí 5511, Dpto 123123, Barrio lugano', NULL, NULL, NULL, NULL, 'Ciudad Autonoma de Buenos Aires', 'Buenos Aires', '1439', 'Envio'),
(12, 6, 'Guaminí 5511, Barrio lugano', NULL, NULL, NULL, NULL, 'Ciudad Autonoma de Buenos Aires', 'Buenos Aires', '1439', 'Envio'),
(13, 6, 'Guamini 5511, Barrio Lugano', NULL, NULL, NULL, NULL, 'Caba', 'Buenos Aires', '1439', 'Envio'),
(14, 6, 'Guamini 5511, Barrio Lugano', 'Guamini', '5511', NULL, 'Lugano', 'Caba', 'Buenos Aires', '1439', 'Envio');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `envio`
--

CREATE TABLE `envio` (
  `Id_envio` int(11) NOT NULL,
  `Id_pedido` int(11) NOT NULL,
  `Empresa_Logistica` varchar(100) NOT NULL,
  `Numero_Tracking` varchar(100) DEFAULT NULL,
  `Estado_Envio` varchar(50) DEFAULT NULL,
  `Fecha_Despacho` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `envio`
--

INSERT INTO `envio` (`Id_envio`, `Id_pedido`, `Empresa_Logistica`, `Numero_Tracking`, `Estado_Envio`, `Fecha_Despacho`) VALUES
(3, 3, 'Envío Estándar (Correo)', NULL, 'Preparando', NULL),
(4, 4, 'Envío Estándar (Correo)', NULL, 'Cancelado', NULL),
(5, 5, 'Envío Estándar (Correo)', NULL, 'Cancelado', NULL),
(6, 6, 'Envío Express', NULL, 'En viaje', '2026-10-29'),
(7, 7, 'Envío Express', NULL, 'En viaje', '2026-10-29'),
(8, 8, 'Retiro en Local', NULL, 'Cancelado', NULL),
(9, 9, 'Retiro en Local', NULL, 'Cancelado', NULL),
(10, 10, 'Envío Express', '123123', 'En viaje', '2026-10-29'),
(11, 11, 'Retiro en Local', NULL, 'Cancelado', NULL),
(12, 12, 'Correo Argentino', NULL, 'Cancelado', NULL),
(13, 13, 'Envío Express', NULL, 'Preparando', NULL),
(14, 14, 'Envío Estándar (Correo)', NULL, 'Preparando', NULL),
(15, 15, 'Retiro en Local', NULL, 'Cancelado', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `factura`
--

CREATE TABLE `factura` (
  `Id_factura` int(11) NOT NULL,
  `Id_pedido` int(11) NOT NULL,
  `Tipo_Comprobante` enum('A','B','C') NOT NULL,
  `Nro_Comprobante` varchar(50) NOT NULL,
  `CUIT_Receptor` varchar(13) NOT NULL,
  `Fecha_Emision` date NOT NULL,
  `Leida` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `factura`
--

INSERT INTO `factura` (`Id_factura`, `Id_pedido`, `Tipo_Comprobante`, `Nro_Comprobante`, `CUIT_Receptor`, `Fecha_Emision`, `Leida`) VALUES
(3, 6, 'B', '0001-00000003', '00000000000', '2026-10-29', 1),
(4, 7, 'B', '0001-00000004', '12345678', '2026-10-29', 1),
(5, 8, 'B', '0001-00000005', '12345678', '2026-10-29', 1),
(6, 9, 'B', '0001-00000006', '12345678', '2026-10-29', 1),
(7, 10, 'B', '0001-00000007', '12345678', '2026-10-29', 1),
(8, 11, 'B', '0001-00000008', '12345678', '2026-10-30', 1),
(9, 12, 'B', '0001-00000009', '12345678', '2026-10-30', 1),
(10, 13, 'B', '0001-00000010', '12345678', '2026-11-01', 0),
(11, 14, 'B', '0001-00000011', '12345678', '2026-11-01', 0),
(12, 15, 'B', '0001-00000012', '12345678', '2026-11-01', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `falla_produccion`
--

CREATE TABLE `falla_produccion` (
  `Id_falla` int(11) NOT NULL,
  `Id_orden` int(11) NOT NULL,
  `Cantidad_Fallada` int(11) NOT NULL,
  `Motivo_Falla` varchar(255) DEFAULT NULL,
  `Costo_Perdido` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `falla_produccion`
--

INSERT INTO `falla_produccion` (`Id_falla`, `Id_orden`, `Cantidad_Fallada`, `Motivo_Falla`, `Costo_Perdido`) VALUES
(1, 1, 2, 'Mancha de aceite de máquina en tela', 6400.00);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `imagen_producto`
--

CREATE TABLE `imagen_producto` (
  `Id_imagen` int(11) NOT NULL,
  `Id_producto` int(11) NOT NULL,
  `URL_Imagen` varchar(255) NOT NULL,
  `Es_Portada` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `imagen_producto`
--

INSERT INTO `imagen_producto` (`Id_imagen`, `Id_producto`, `URL_Imagen`, `Es_Portada`) VALUES
(1, 1, 'img/body-algodon.jpg', 1),
(2, 1, 'https://tienda.com', 0),
(3, 2, 'img/pantalon-jogger.jpg', 1),
(4, 3, 'img/body-manga-larga.jpg', 1),
(5, 4, 'img/body-estampado.jpg', 1),
(6, 5, 'img/pantalon-ranita.jpg', 1),
(7, 6, 'img/gorrito-oso.jpg', 1),
(8, 7, 'img/gorrito-de-lana-con-vicera-1793332861.jpg', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `insumo`
--

CREATE TABLE `insumo` (
  `Id_insumo` int(11) NOT NULL,
  `Id_proveedor` int(11) NOT NULL,
  `Nombre` varchar(100) NOT NULL,
  `Unidad_Medida` varchar(20) DEFAULT NULL,
  `Costo_Actual` decimal(10,2) NOT NULL,
  `Stock_Disponible` decimal(10,2) NOT NULL DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `insumo`
--

INSERT INTO `insumo` (`Id_insumo`, `Id_proveedor`, `Nombre`, `Unidad_Medida`, `Costo_Actual`, `Stock_Disponible`) VALUES
(1, 1, 'Tela Interlock Algodón Blanco', 'Metros', 4500.00, 120.50),
(2, 1, 'Tela Plush Gris', 'Metros', 6200.00, 45.00),
(3, 2, 'Broches de presión metálicos', 'Unidades', 80.00, 1500.00);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `item_carrito`
--

CREATE TABLE `item_carrito` (
  `Id_item_carrito` int(11) NOT NULL,
  `Id_carrito` int(11) NOT NULL,
  `Id_variante` int(11) NOT NULL,
  `Cantidad` int(11) NOT NULL CHECK (`Cantidad` > 0)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `metodo_envio`
--

CREATE TABLE `metodo_envio` (
  `Id_metodo_envio` int(11) NOT NULL,
  `Nombre` varchar(50) NOT NULL,
  `Costo_Base` decimal(10,2) NOT NULL,
  `Activo` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `metodo_envio`
--

INSERT INTO `metodo_envio` (`Id_metodo_envio`, `Nombre`, `Costo_Base`, `Activo`) VALUES
(1, 'Correo Argentino', 500.00, 1),
(2, 'Envío Express', 800.00, 1),
(3, 'Retiro en Local', 0.00, 1),
(4, 'Envío Estándar (Correo)', 3500.00, 1),
(5, 'Envío Express (Moto)', 5500.00, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `metodo_pago`
--

CREATE TABLE `metodo_pago` (
  `Id_metodo_pago` int(11) NOT NULL,
  `Nombre` varchar(50) NOT NULL,
  `Activo` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `metodo_pago`
--

INSERT INTO `metodo_pago` (`Id_metodo_pago`, `Nombre`, `Activo`) VALUES
(1, 'Tarjeta', 1),
(2, 'Transferencia', 1),
(3, 'Tarjeta de Crédito / Débito', 0),
(4, 'Transferencia Bancaria', 0),
(5, 'Efectivo / Rapipago', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `movimiento_stock`
--

CREATE TABLE `movimiento_stock` (
  `Id_movimiento` int(11) NOT NULL,
  `Id_variante` int(11) DEFAULT NULL,
  `Id_insumo` int(11) DEFAULT NULL,
  `Tipo_Movimiento` enum('Entrada','Salida') NOT NULL,
  `Cantidad` int(11) NOT NULL,
  `Motivo` varchar(255) DEFAULT NULL,
  `Fecha_Movimiento` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `movimiento_stock`
--

INSERT INTO `movimiento_stock` (`Id_movimiento`, `Id_variante`, `Id_insumo`, `Tipo_Movimiento`, `Cantidad`, `Motivo`, `Fecha_Movimiento`) VALUES
(1, 1, NULL, 'Entrada', 98, 'Finalización Orden de Producción #1', '2026-10-02 09:54:49'),
(2, NULL, 1, 'Salida', 40, 'Consumo tela OP #1', '2026-10-02 09:54:49'),
(3, NULL, 3, 'Salida', 300, 'Consumo broches OP #1', '2026-10-02 09:54:49'),
(4, 1, NULL, 'Salida', 2, 'Descuento por venta pedido #1', '2026-10-02 09:54:49'),
(5, 4, NULL, 'Entrada', 1, 'Cancelación del pedido #9', '2026-10-29 20:25:36'),
(6, 9, NULL, 'Entrada', 2, 'Cancelación del pedido #9', '2026-10-29 20:25:36'),
(7, 3, NULL, 'Entrada', 1, 'Cancelación del pedido #8', '2026-10-29 20:50:04'),
(8, 1, NULL, 'Entrada', 6, 'Cancelación del pedido #11', '2026-10-29 21:06:35'),
(9, 8, NULL, 'Entrada', 3, 'Cancelación del pedido #5', '2026-10-29 22:48:21'),
(10, 1, NULL, 'Entrada', 1, 'Cancelación del pedido #4', '2026-10-29 22:49:44'),
(11, 1, NULL, 'Entrada', 1, 'Cancelación del pedido #12', '2026-10-29 23:08:46'),
(12, 3, NULL, 'Entrada', 1, 'Cancelación del pedido #15', '2026-11-01 19:33:48');

--
-- Disparadores `movimiento_stock`
--
DELIMITER $$
CREATE TRIGGER `validar_movimiento_stock_before_insert` BEFORE INSERT ON `movimiento_stock` FOR EACH ROW BEGIN
    IF (NEW.Id_variante IS NOT NULL AND NEW.Id_insumo IS NOT NULL) THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Error: No puede tener Id_variante e Id_insumo al mismo tiempo.';
    END IF;
    IF (NEW.Id_variante IS NULL AND NEW.Id_insumo IS NULL) THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Error: Debe tener Id_variante o Id_insumo (no ambos nulos).';
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `orden_produccion`
--

CREATE TABLE `orden_produccion` (
  `Id_orden` int(11) NOT NULL,
  `Id_variante` int(11) NOT NULL,
  `Cantidad_Planificada` int(11) NOT NULL,
  `Cantidad_Producida_Real` int(11) DEFAULT 0,
  `Fecha_Inicio` date NOT NULL,
  `Estado_Orden` enum('Corte','Costura','Finalizado') DEFAULT 'Corte',
  `Costo_Total_Estimado` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `orden_produccion`
--

INSERT INTO `orden_produccion` (`Id_orden`, `Id_variante`, `Cantidad_Planificada`, `Cantidad_Producida_Real`, `Fecha_Inicio`, `Estado_Orden`, `Costo_Total_Estimado`) VALUES
(1, 1, 100, 98, '2026-05-01', 'Finalizado', 320000.00),
(2, 3, 50, 0, '2026-06-10', 'Corte', 175000.00);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pedido`
--

CREATE TABLE `pedido` (
  `Id_pedido` int(11) NOT NULL,
  `Id_cliente` int(11) NOT NULL,
  `Id_direccion` int(11) NOT NULL,
  `Id_metodo_envio` int(11) NOT NULL,
  `Fecha_Pedido` datetime DEFAULT current_timestamp(),
  `Subtotal` decimal(10,2) NOT NULL,
  `Descuento_Aplicado` decimal(10,2) DEFAULT 0.00,
  `Total_Final` decimal(10,2) NOT NULL,
  `Estado_Pedido` enum('Pendiente','Preparacion','Enviado','Entregado','Cancelado') DEFAULT 'Pendiente',
  `Destinatario_Nombre` varchar(50) DEFAULT NULL,
  `Destinatario_Apellido` varchar(50) DEFAULT NULL,
  `Destinatario_Telefono` varchar(20) DEFAULT NULL,
  `Aviso_Admin` tinyint(1) NOT NULL DEFAULT 1,
  `Aviso_Cliente` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `pedido`
--

INSERT INTO `pedido` (`Id_pedido`, `Id_cliente`, `Id_direccion`, `Id_metodo_envio`, `Fecha_Pedido`, `Subtotal`, `Descuento_Aplicado`, `Total_Final`, `Estado_Pedido`, `Destinatario_Nombre`, `Destinatario_Apellido`, `Destinatario_Telefono`, `Aviso_Admin`, `Aviso_Cliente`) VALUES
(3, 6, 12, 4, '2026-10-27 20:24:45', 2500.00, 0.00, 6000.00, 'Preparacion', NULL, NULL, NULL, 0, 0),
(4, 6, 13, 4, '2026-10-27 20:36:54', 2500.00, 0.00, 6000.00, 'Cancelado', NULL, NULL, NULL, 0, 0),
(5, 6, 13, 4, '2026-10-27 22:02:37', 33000.00, 0.00, 36500.00, 'Cancelado', NULL, NULL, NULL, 0, 0),
(6, 6, 13, 2, '2026-10-29 16:03:10', 9000.00, 0.00, 9800.00, 'Enviado', NULL, NULL, NULL, 0, 0),
(7, 6, 13, 2, '2026-10-29 17:47:05', 8500.00, 0.00, 9300.00, 'Enviado', NULL, NULL, NULL, 0, 0),
(8, 6, 14, 3, '2026-10-29 18:26:18', 3200.00, 0.00, 3200.00, 'Cancelado', 'Ricardo', 'Espinoza', '11225029128', 0, 0),
(9, 6, 14, 3, '2026-10-29 19:08:46', 17500.00, 0.00, 17500.00, 'Cancelado', 'Ricardo', 'Espinoza', '11225029128', 0, 0),
(10, 6, 14, 2, '2026-10-29 20:22:57', 2500.00, 0.00, 3300.00, 'Enviado', 'Ricardo', 'Espinoza', '11225029128', 0, 0),
(11, 6, 14, 3, '2026-10-29 21:06:19', 15000.00, 0.00, 15000.00, 'Cancelado', 'Ricardo', 'Espinoza', '11225029128', 0, 0),
(12, 6, 14, 1, '2026-10-29 23:08:36', 2500.00, 0.00, 3000.00, 'Cancelado', 'Ricardo', 'Espinoza', '11225029128', 0, 0),
(13, 6, 14, 2, '2026-11-01 19:25:23', 11000.00, 0.00, 11800.00, 'Preparacion', 'Ricardo', 'Espinoza', '11225029128', 1, 0),
(14, 6, 14, 4, '2026-11-01 19:33:19', 2500.00, 0.00, 6000.00, 'Preparacion', 'Ricardo', 'Espinoza', '11225029128', 1, 0),
(15, 6, 14, 3, '2026-11-01 19:33:37', 3200.00, 0.00, 3200.00, 'Cancelado', 'Ricardo', 'Espinoza', '11225029128', 1, 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `producto`
--

CREATE TABLE `producto` (
  `Id_producto` int(11) NOT NULL,
  `Id_categoria` int(11) NOT NULL,
  `Id_promocion` int(11) DEFAULT NULL,
  `Nombre` varchar(100) NOT NULL,
  `Descripcion` text DEFAULT NULL,
  `Porcentaje_Ganancia` decimal(5,2) DEFAULT NULL,
  `Activo` tinyint(1) DEFAULT 1,
  `Destacado` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `producto`
--

INSERT INTO `producto` (`Id_producto`, `Id_categoria`, `Id_promocion`, `Nombre`, `Descripcion`, `Porcentaje_Ganancia`, `Activo`, `Destacado`) VALUES
(1, 1, NULL, 'Body de algodón', 'Body suave 100% algodón', NULL, 1, 1),
(2, 2, NULL, 'Pantalón jogger', 'Pantalón cómodo para el hogar', NULL, 1, 0),
(3, 1, 1, 'Body Manga Larga Algodón', 'Body básico liso 100% algodón interlock', 60.00, 1, 1),
(4, 1, NULL, 'Body Estampado Animales', 'Body manga corta con estampa al agua ecológica', 65.00, 1, 0),
(5, 2, 2, 'Pantalón Ranita Gris', 'Pantalón con piecito integrado de plush suave', 50.00, 1, 1),
(6, 5, NULL, 'Gorrito Oso', 'Gorrito con orejitas tejido en hilo de algodón', 70.00, 1, 0),
(7, 5, NULL, 'Gorrito de lana con vicera', '', 0.00, 1, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `promocion`
--

CREATE TABLE `promocion` (
  `Id_promocion` int(11) NOT NULL,
  `Nombre` varchar(50) NOT NULL,
  `Porcentaje_Descuento` decimal(5,2) NOT NULL,
  `Fecha_Inicio` timestamp NOT NULL DEFAULT current_timestamp(),
  `Fecha_Fin` date DEFAULT NULL,
  `Activa` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `promocion`
--

INSERT INTO `promocion` (`Id_promocion`, `Nombre`, `Porcentaje_Descuento`, `Fecha_Inicio`, `Fecha_Fin`, `Activa`) VALUES
(1, 'Bienvenida', 10.00, '2026-10-02 12:54:49', '2026-12-31', 1),
(2, 'Hot Sale', 25.00, '2026-10-02 12:54:49', '2026-06-30', 1),
(3, 'Sin Descuento', 0.00, '2026-10-02 12:54:49', '2027-01-01', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `proveedor`
--

CREATE TABLE `proveedor` (
  `Id_proveedor` int(11) NOT NULL,
  `Nombre_Empresa` varchar(100) NOT NULL,
  `Contacto` varchar(100) DEFAULT NULL,
  `Telefono` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `proveedor`
--

INSERT INTO `proveedor` (`Id_proveedor`, `Nombre_Empresa`, `Contacto`, `Telefono`) VALUES
(1, 'Textil Algodonera S.A.', 'Alberto Fernández', '1122334455'),
(2, 'Botonera Central', 'Ana Martínez', '1166778899');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `receta_produccion`
--

CREATE TABLE `receta_produccion` (
  `Id_receta` int(11) NOT NULL,
  `Id_variante` int(11) NOT NULL,
  `Id_insumo` int(11) NOT NULL,
  `Cantidad_Necesaria` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `receta_produccion`
--

INSERT INTO `receta_produccion` (`Id_receta`, `Id_variante`, `Id_insumo`, `Cantidad_Necesaria`) VALUES
(1, 1, 1, 0.40),
(2, 1, 3, 3.00),
(3, 5, 2, 0.60);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `reporte_cliente`
--

CREATE TABLE `reporte_cliente` (
  `Id_reporte` int(11) NOT NULL,
  `Id_cliente` int(11) NOT NULL,
  `Asunto` varchar(150) NOT NULL,
  `Mensaje` text NOT NULL,
  `Leido` tinyint(1) NOT NULL DEFAULT 0,
  `Fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp(),
  `Respuesta` text DEFAULT NULL,
  `Fecha_respuesta` datetime DEFAULT NULL,
  `Respuesta_vista` tinyint(1) NOT NULL DEFAULT 0,
  `Oculto_cliente` tinyint(1) NOT NULL DEFAULT 0,
  `Oculto_admin` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `reporte_cliente`
--

INSERT INTO `reporte_cliente` (`Id_reporte`, `Id_cliente`, `Asunto`, `Mensaje`, `Leido`, `Fecha_creacion`, `Respuesta`, `Fecha_respuesta`, `Respuesta_vista`, `Oculto_cliente`, `Oculto_admin`) VALUES
(2, 6, 'mi pedido no llega hace 10 días', '1232323232323232323232323123123123123', 1, '2026-10-29 18:29:11', 'oohh lamentamos muchisimo eso xd', '2026-10-29 18:30:52', 1, 1, 0),
(3, 6, 'mi pedido no llega hace 10 días', 'xsssdsdsdsdsdsdsdsd', 1, '2026-10-29 18:42:53', 'wewe', '2026-10-29 15:43:20', 1, 1, 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rol`
--

CREATE TABLE `rol` (
  `id_rol` int(11) NOT NULL,
  `Nombre_Rol` enum('Admin','Cliente','Fabrica') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `rol`
--

INSERT INTO `rol` (`id_rol`, `Nombre_Rol`) VALUES
(1, 'Cliente'),
(2, 'Admin'),
(3, 'Cliente'),
(4, 'Fabrica');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `transaccion_pago`
--

CREATE TABLE `transaccion_pago` (
  `Id_transaccion` int(11) NOT NULL,
  `Id_pedido` int(11) NOT NULL,
  `Id_metodo_pago` int(11) NOT NULL,
  `Monto` decimal(10,2) NOT NULL,
  `Estado_Pago` enum('Aprobado','Rechazado') NOT NULL,
  `Referencia_Pasarela` varchar(100) DEFAULT NULL,
  `Fecha_Pago` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `transaccion_pago`
--

INSERT INTO `transaccion_pago` (`Id_transaccion`, `Id_pedido`, `Id_metodo_pago`, `Monto`, `Estado_Pago`, `Referencia_Pasarela`, `Fecha_Pago`) VALUES
(3, 3, 1, 6000.00, 'Aprobado', 'SIM-261027232445-JJAR', '2026-10-27 20:24:45'),
(4, 4, 1, 6000.00, 'Aprobado', 'SIM-261027233654-HA0Y', '2026-10-27 20:36:54'),
(5, 5, 2, 36500.00, 'Aprobado', 'SIM-261028010237-JKXG-GALICIA', '2026-10-27 22:02:37'),
(6, 6, 2, 9800.00, 'Aprobado', 'SIM-261029190310-FMMV-GALICIA', '2026-10-29 16:03:10'),
(7, 7, 2, 9300.00, 'Aprobado', 'SIM-261029204705-R99G', '2026-10-29 17:47:05'),
(8, 8, 2, 3200.00, 'Aprobado', 'SIM-261029212618-7JXY-GALICIA', '2026-10-29 18:26:18'),
(9, 9, 2, 17500.00, 'Aprobado', 'SIM-261029220846-ILBI-GALICIA', '2026-10-29 19:08:46'),
(10, 10, 2, 3300.00, 'Aprobado', 'SIM-261029232257-JE3R', '2026-10-29 20:22:57'),
(11, 11, 2, 15000.00, 'Aprobado', 'SIM-261030000619-WYDI-GALICIA', '2026-10-29 21:06:19'),
(12, 12, 5, 3000.00, 'Aprobado', 'SIM-261030020836-PDFP', '2026-10-29 23:08:36'),
(13, 13, 2, 11800.00, 'Aprobado', 'SIM-261101222523-YEMV-GALICIA', '2026-11-01 19:25:23'),
(14, 14, 2, 6000.00, 'Aprobado', 'SIM-261101223319-0KUB-GALICIA', '2026-11-01 19:33:19'),
(15, 15, 2, 3200.00, 'Aprobado', 'SIM-261101223337-IQ6M-GALICIA', '2026-11-01 19:33:37');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

CREATE TABLE `usuario` (
  `Id_usuario` int(11) NOT NULL,
  `Id_rol` int(11) NOT NULL,
  `Email` varchar(100) NOT NULL,
  `Password_Hash` varchar(255) NOT NULL,
  `Fecha_Registro` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuario`
--

INSERT INTO `usuario` (`Id_usuario`, `Id_rol`, `Email`, `Password_Hash`, `Fecha_Registro`) VALUES
(7, 1, 'lucianocadima4@gmail.com', '$2y$12$0O8CBSlIKjS6BmoGsAybJ.mGTrgZUe6F1vm5eW8L9riIcgPp3XkD.', '2026-10-26 18:43:16'),
(8, 1, 'bimolucho@gmail.com', '$2y$12$En3.wnrHSyo3ZlKScNf.yOXP6Rn.cWUk7EBHhCkZpajy31AYPneFi', '2026-10-26 20:57:03'),
(9, 1, 'sidius31@gmail.com', '$2y$12$6eA0ube1xl.3kVkje3uQAeIc/wY2lZqWpe39uNoQFIgFZRkyxKW7y', '2026-10-26 20:58:15'),
(10, 2, 'luciano.cadimaet32@gmail.com', '$2y$12$/7VOIQKMMtR66vTWyzNftu7Ama0DrduNGZuFNFfV1IKplg9bj9M1S', '2026-10-29 11:13:33');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `variante_producto`
--

CREATE TABLE `variante_producto` (
  `Id_variante` int(11) NOT NULL,
  `Id_producto` int(11) NOT NULL,
  `SKU` varchar(50) DEFAULT NULL,
  `Talle` varchar(20) DEFAULT NULL,
  `Color` varchar(30) DEFAULT NULL,
  `Precio_Venta` decimal(10,2) NOT NULL,
  `Stock_Actual` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `variante_producto`
--

INSERT INTO `variante_producto` (`Id_variante`, `Id_producto`, `SKU`, `Talle`, `Color`, `Precio_Venta`, `Stock_Actual`) VALUES
(1, 1, 'BOD-BLN-0-3', '0-3 meses', 'Blanco', 2500.00, 47),
(2, 1, 'BOD-ROS-0-3', '0-3 meses', 'Rosa', 2500.00, 30),
(3, 2, 'PAN-GRIS-6-9', '6-9 meses', 'Gris', 3200.00, 25),
(4, 3, 'BD-ML-BL-00M', '0-3 meses', 'Blanco', 8500.00, 49),
(5, 3, 'BD-ML-BL-03M', '3-6 meses', 'Blanco', 8500.00, 45),
(6, 3, 'BD-ML-RS-00M', '0-3 meses', 'Rosa', 8900.00, 30),
(7, 4, 'BD-MC-AN-06M', '6-9 meses', 'Amarillo', 9200.00, 20),
(8, 5, 'PT-RN-GR-03M', '3-6 meses', 'Gris', 11000.00, 14),
(9, 6, 'AC-GR-OS-UNI', 'Único', 'Marrón', 4500.00, 8),
(10, 7, 'KS-7-0-3-MESES-GRIS', '0-3 meses', 'Gris', 6000.00, 2);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `bebe`
--
ALTER TABLE `bebe`
  ADD PRIMARY KEY (`Id_bebe`),
  ADD KEY `Id_cliente` (`Id_cliente`);

--
-- Indices de la tabla `carrito`
--
ALTER TABLE `carrito`
  ADD PRIMARY KEY (`Id_carrito`),
  ADD KEY `Id_cliente` (`Id_cliente`);

--
-- Indices de la tabla `categoria`
--
ALTER TABLE `categoria`
  ADD PRIMARY KEY (`Id_categoria`);

--
-- Indices de la tabla `cliente`
--
ALTER TABLE `cliente`
  ADD PRIMARY KEY (`Id_cliente`),
  ADD UNIQUE KEY `DNI_CUIT` (`DNI_CUIT`),
  ADD KEY `Id_usuario` (`Id_usuario`);

--
-- Indices de la tabla `detalle_pedido`
--
ALTER TABLE `detalle_pedido`
  ADD PRIMARY KEY (`Id_detalle`),
  ADD KEY `Id_pedido` (`Id_pedido`),
  ADD KEY `Id_variante` (`Id_variante`);

--
-- Indices de la tabla `direccion`
--
ALTER TABLE `direccion`
  ADD PRIMARY KEY (`Id_direccion`),
  ADD KEY `Id_cliente` (`Id_cliente`);

--
-- Indices de la tabla `envio`
--
ALTER TABLE `envio`
  ADD PRIMARY KEY (`Id_envio`),
  ADD KEY `Id_pedido` (`Id_pedido`);

--
-- Indices de la tabla `factura`
--
ALTER TABLE `factura`
  ADD PRIMARY KEY (`Id_factura`),
  ADD KEY `Id_pedido` (`Id_pedido`);

--
-- Indices de la tabla `falla_produccion`
--
ALTER TABLE `falla_produccion`
  ADD PRIMARY KEY (`Id_falla`),
  ADD KEY `Id_orden` (`Id_orden`);

--
-- Indices de la tabla `imagen_producto`
--
ALTER TABLE `imagen_producto`
  ADD PRIMARY KEY (`Id_imagen`),
  ADD KEY `Id_producto` (`Id_producto`);

--
-- Indices de la tabla `insumo`
--
ALTER TABLE `insumo`
  ADD PRIMARY KEY (`Id_insumo`),
  ADD KEY `Id_proveedor` (`Id_proveedor`);

--
-- Indices de la tabla `item_carrito`
--
ALTER TABLE `item_carrito`
  ADD PRIMARY KEY (`Id_item_carrito`),
  ADD KEY `Id_carrito` (`Id_carrito`),
  ADD KEY `Id_variante` (`Id_variante`);

--
-- Indices de la tabla `metodo_envio`
--
ALTER TABLE `metodo_envio`
  ADD PRIMARY KEY (`Id_metodo_envio`);

--
-- Indices de la tabla `metodo_pago`
--
ALTER TABLE `metodo_pago`
  ADD PRIMARY KEY (`Id_metodo_pago`);

--
-- Indices de la tabla `movimiento_stock`
--
ALTER TABLE `movimiento_stock`
  ADD PRIMARY KEY (`Id_movimiento`),
  ADD KEY `Id_variante` (`Id_variante`),
  ADD KEY `Id_insumo` (`Id_insumo`);

--
-- Indices de la tabla `orden_produccion`
--
ALTER TABLE `orden_produccion`
  ADD PRIMARY KEY (`Id_orden`),
  ADD KEY `Id_variante` (`Id_variante`);

--
-- Indices de la tabla `pedido`
--
ALTER TABLE `pedido`
  ADD PRIMARY KEY (`Id_pedido`),
  ADD KEY `Id_cliente` (`Id_cliente`),
  ADD KEY `Id_direccion` (`Id_direccion`),
  ADD KEY `Id_metodo_envio` (`Id_metodo_envio`);

--
-- Indices de la tabla `producto`
--
ALTER TABLE `producto`
  ADD PRIMARY KEY (`Id_producto`),
  ADD KEY `Id_categoria` (`Id_categoria`),
  ADD KEY `Id_promocion` (`Id_promocion`);

--
-- Indices de la tabla `promocion`
--
ALTER TABLE `promocion`
  ADD PRIMARY KEY (`Id_promocion`);

--
-- Indices de la tabla `proveedor`
--
ALTER TABLE `proveedor`
  ADD PRIMARY KEY (`Id_proveedor`);

--
-- Indices de la tabla `receta_produccion`
--
ALTER TABLE `receta_produccion`
  ADD PRIMARY KEY (`Id_receta`),
  ADD KEY `Id_variante` (`Id_variante`),
  ADD KEY `Id_insumo` (`Id_insumo`);

--
-- Indices de la tabla `reporte_cliente`
--
ALTER TABLE `reporte_cliente`
  ADD PRIMARY KEY (`Id_reporte`);

--
-- Indices de la tabla `rol`
--
ALTER TABLE `rol`
  ADD PRIMARY KEY (`id_rol`);

--
-- Indices de la tabla `transaccion_pago`
--
ALTER TABLE `transaccion_pago`
  ADD PRIMARY KEY (`Id_transaccion`),
  ADD KEY `Id_pedido` (`Id_pedido`),
  ADD KEY `Id_metodo_pago` (`Id_metodo_pago`);

--
-- Indices de la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`Id_usuario`),
  ADD UNIQUE KEY `Email` (`Email`),
  ADD KEY `Id_rol` (`Id_rol`);

--
-- Indices de la tabla `variante_producto`
--
ALTER TABLE `variante_producto`
  ADD PRIMARY KEY (`Id_variante`),
  ADD UNIQUE KEY `SKU` (`SKU`),
  ADD KEY `Id_producto` (`Id_producto`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `bebe`
--
ALTER TABLE `bebe`
  MODIFY `Id_bebe` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `carrito`
--
ALTER TABLE `carrito`
  MODIFY `Id_carrito` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `categoria`
--
ALTER TABLE `categoria`
  MODIFY `Id_categoria` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `cliente`
--
ALTER TABLE `cliente`
  MODIFY `Id_cliente` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `detalle_pedido`
--
ALTER TABLE `detalle_pedido`
  MODIFY `Id_detalle` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT de la tabla `direccion`
--
ALTER TABLE `direccion`
  MODIFY `Id_direccion` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT de la tabla `envio`
--
ALTER TABLE `envio`
  MODIFY `Id_envio` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT de la tabla `factura`
--
ALTER TABLE `factura`
  MODIFY `Id_factura` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de la tabla `falla_produccion`
--
ALTER TABLE `falla_produccion`
  MODIFY `Id_falla` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `imagen_producto`
--
ALTER TABLE `imagen_producto`
  MODIFY `Id_imagen` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `insumo`
--
ALTER TABLE `insumo`
  MODIFY `Id_insumo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `item_carrito`
--
ALTER TABLE `item_carrito`
  MODIFY `Id_item_carrito` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `metodo_envio`
--
ALTER TABLE `metodo_envio`
  MODIFY `Id_metodo_envio` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `metodo_pago`
--
ALTER TABLE `metodo_pago`
  MODIFY `Id_metodo_pago` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `movimiento_stock`
--
ALTER TABLE `movimiento_stock`
  MODIFY `Id_movimiento` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de la tabla `orden_produccion`
--
ALTER TABLE `orden_produccion`
  MODIFY `Id_orden` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `pedido`
--
ALTER TABLE `pedido`
  MODIFY `Id_pedido` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT de la tabla `producto`
--
ALTER TABLE `producto`
  MODIFY `Id_producto` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `promocion`
--
ALTER TABLE `promocion`
  MODIFY `Id_promocion` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `proveedor`
--
ALTER TABLE `proveedor`
  MODIFY `Id_proveedor` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `receta_produccion`
--
ALTER TABLE `receta_produccion`
  MODIFY `Id_receta` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `reporte_cliente`
--
ALTER TABLE `reporte_cliente`
  MODIFY `Id_reporte` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `rol`
--
ALTER TABLE `rol`
  MODIFY `id_rol` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `transaccion_pago`
--
ALTER TABLE `transaccion_pago`
  MODIFY `Id_transaccion` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT de la tabla `usuario`
--
ALTER TABLE `usuario`
  MODIFY `Id_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `variante_producto`
--
ALTER TABLE `variante_producto`
  MODIFY `Id_variante` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `bebe`
--
ALTER TABLE `bebe`
  ADD CONSTRAINT `bebe_ibfk_1` FOREIGN KEY (`Id_cliente`) REFERENCES `cliente` (`Id_cliente`);

--
-- Filtros para la tabla `carrito`
--
ALTER TABLE `carrito`
  ADD CONSTRAINT `carrito_ibfk_1` FOREIGN KEY (`Id_cliente`) REFERENCES `cliente` (`Id_cliente`);

--
-- Filtros para la tabla `cliente`
--
ALTER TABLE `cliente`
  ADD CONSTRAINT `cliente_ibfk_1` FOREIGN KEY (`Id_usuario`) REFERENCES `usuario` (`Id_usuario`);

--
-- Filtros para la tabla `detalle_pedido`
--
ALTER TABLE `detalle_pedido`
  ADD CONSTRAINT `detalle_pedido_ibfk_1` FOREIGN KEY (`Id_pedido`) REFERENCES `pedido` (`Id_pedido`),
  ADD CONSTRAINT `detalle_pedido_ibfk_2` FOREIGN KEY (`Id_variante`) REFERENCES `variante_producto` (`Id_variante`);

--
-- Filtros para la tabla `direccion`
--
ALTER TABLE `direccion`
  ADD CONSTRAINT `direccion_ibfk_1` FOREIGN KEY (`Id_cliente`) REFERENCES `cliente` (`Id_cliente`);

--
-- Filtros para la tabla `envio`
--
ALTER TABLE `envio`
  ADD CONSTRAINT `envio_ibfk_1` FOREIGN KEY (`Id_pedido`) REFERENCES `pedido` (`Id_pedido`);

--
-- Filtros para la tabla `factura`
--
ALTER TABLE `factura`
  ADD CONSTRAINT `factura_ibfk_1` FOREIGN KEY (`Id_pedido`) REFERENCES `pedido` (`Id_pedido`);

--
-- Filtros para la tabla `falla_produccion`
--
ALTER TABLE `falla_produccion`
  ADD CONSTRAINT `falla_produccion_ibfk_1` FOREIGN KEY (`Id_orden`) REFERENCES `orden_produccion` (`Id_orden`);

--
-- Filtros para la tabla `imagen_producto`
--
ALTER TABLE `imagen_producto`
  ADD CONSTRAINT `imagen_producto_ibfk_1` FOREIGN KEY (`Id_producto`) REFERENCES `producto` (`Id_producto`);

--
-- Filtros para la tabla `insumo`
--
ALTER TABLE `insumo`
  ADD CONSTRAINT `insumo_ibfk_1` FOREIGN KEY (`Id_proveedor`) REFERENCES `proveedor` (`Id_proveedor`);

--
-- Filtros para la tabla `item_carrito`
--
ALTER TABLE `item_carrito`
  ADD CONSTRAINT `item_carrito_ibfk_1` FOREIGN KEY (`Id_carrito`) REFERENCES `carrito` (`Id_carrito`) ON DELETE CASCADE,
  ADD CONSTRAINT `item_carrito_ibfk_2` FOREIGN KEY (`Id_variante`) REFERENCES `variante_producto` (`Id_variante`);

--
-- Filtros para la tabla `movimiento_stock`
--
ALTER TABLE `movimiento_stock`
  ADD CONSTRAINT `movimiento_stock_ibfk_1` FOREIGN KEY (`Id_variante`) REFERENCES `variante_producto` (`Id_variante`),
  ADD CONSTRAINT `movimiento_stock_ibfk_2` FOREIGN KEY (`Id_insumo`) REFERENCES `insumo` (`Id_insumo`);

--
-- Filtros para la tabla `orden_produccion`
--
ALTER TABLE `orden_produccion`
  ADD CONSTRAINT `orden_produccion_ibfk_1` FOREIGN KEY (`Id_variante`) REFERENCES `variante_producto` (`Id_variante`);

--
-- Filtros para la tabla `pedido`
--
ALTER TABLE `pedido`
  ADD CONSTRAINT `pedido_ibfk_1` FOREIGN KEY (`Id_cliente`) REFERENCES `cliente` (`Id_cliente`),
  ADD CONSTRAINT `pedido_ibfk_2` FOREIGN KEY (`Id_direccion`) REFERENCES `direccion` (`Id_direccion`),
  ADD CONSTRAINT `pedido_ibfk_3` FOREIGN KEY (`Id_metodo_envio`) REFERENCES `metodo_envio` (`Id_metodo_envio`);

--
-- Filtros para la tabla `producto`
--
ALTER TABLE `producto`
  ADD CONSTRAINT `producto_ibfk_1` FOREIGN KEY (`Id_categoria`) REFERENCES `categoria` (`Id_categoria`),
  ADD CONSTRAINT `producto_ibfk_2` FOREIGN KEY (`Id_promocion`) REFERENCES `promocion` (`Id_promocion`);

--
-- Filtros para la tabla `receta_produccion`
--
ALTER TABLE `receta_produccion`
  ADD CONSTRAINT `receta_produccion_ibfk_1` FOREIGN KEY (`Id_variante`) REFERENCES `variante_producto` (`Id_variante`),
  ADD CONSTRAINT `receta_produccion_ibfk_2` FOREIGN KEY (`Id_insumo`) REFERENCES `insumo` (`Id_insumo`);

--
-- Filtros para la tabla `transaccion_pago`
--
ALTER TABLE `transaccion_pago`
  ADD CONSTRAINT `transaccion_pago_ibfk_1` FOREIGN KEY (`Id_pedido`) REFERENCES `pedido` (`Id_pedido`),
  ADD CONSTRAINT `transaccion_pago_ibfk_2` FOREIGN KEY (`Id_metodo_pago`) REFERENCES `metodo_pago` (`Id_metodo_pago`);

--
-- Filtros para la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD CONSTRAINT `usuario_ibfk_1` FOREIGN KEY (`Id_rol`) REFERENCES `rol` (`id_rol`);

--
-- Filtros para la tabla `variante_producto`
--
ALTER TABLE `variante_producto`
  ADD CONSTRAINT `variante_producto_ibfk_1` FOREIGN KEY (`Id_producto`) REFERENCES `producto` (`Id_producto`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

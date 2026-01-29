-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost:3306
-- Tiempo de generación: 20-11-2024 a las 18:19:22
-- Versión del servidor: 10.3.39-MariaDB
-- Versión de PHP: 8.3.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `crmdixma`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `alumnocursos`
--

CREATE TABLE `alumnocursos` (
  `StudentCursoID` int(11) NOT NULL,
  `Denominacion` varchar(100) NOT NULL,
  `N_Accion` float DEFAULT NULL,
  `N_Grupo` float DEFAULT NULL,
  `N_Horas` float DEFAULT NULL,
  `Modalidad` varchar(25) DEFAULT NULL,
  `DOC_AF` varchar(50) DEFAULT NULL,
  `Fecha_Inicio` date DEFAULT NULL,
  `Fecha_Fin` date DEFAULT NULL,
  `tutor` varchar(50) DEFAULT NULL,
  `idAlumno` int(11) NOT NULL,
  `idCurso` int(11) DEFAULT NULL,
  `seguimento0` date DEFAULT NULL,
  `seguimento1` date DEFAULT NULL,
  `seguimento2` date DEFAULT NULL,
  `seguimento3` date DEFAULT NULL,
  `seguimento4` date DEFAULT NULL,
  `seguimento5` date DEFAULT NULL,
  `seguimento0check` bit(1) NOT NULL DEFAULT b'0',
  `seguimento1check` bit(1) NOT NULL DEFAULT b'0',
  `seguimento2check` bit(1) NOT NULL DEFAULT b'0',
  `seguimento3check` bit(1) NOT NULL DEFAULT b'0',
  `seguimento4check` bit(1) NOT NULL DEFAULT b'0',
  `seguimento5check` bit(1) NOT NULL DEFAULT b'0',
  `idEmpresa` int(11) DEFAULT NULL,
  `Tipo_Venta` varchar(30) NOT NULL,
  `AP` varchar(100) DEFAULT NULL,
  `Diploma_Status` varchar(20) DEFAULT 'No hecho',
  `CC` bit(1) NOT NULL DEFAULT b'0',
  `RLT` bit(1) NOT NULL DEFAULT b'0',
  `Factura` varchar(30) NOT NULL DEFAULT 'No Enviada',
  `Recibi_Material` bit(1) NOT NULL DEFAULT b'0',
  `Diploma_Status_Ultimo_Cambio` date DEFAULT NULL,
  `Fecha_De_Envio_De_la_Factura` date DEFAULT NULL,
  `Fecha_De_Recibido_De_La_Factura` date DEFAULT NULL,
  `status_curso` varchar(20) NOT NULL DEFAULT 'en curso'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `alumnos`
--

CREATE TABLE `alumnos` (
  `idAlumno` int(50) NOT NULL,
  `idEmpresa` int(50) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `apellidos` varchar(100) NOT NULL,
  `telefono` varchar(10) DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL,
  `fechaNacimiento` date DEFAULT NULL,
  `nif` varchar(15) DEFAULT NULL,
  `sexo` varchar(7) NOT NULL,
  `numeroSeguridadSocial` varchar(13) DEFAULT NULL,
  `categoriaProfesional` varchar(150) DEFAULT NULL,
  `colectivo` varchar(150) DEFAULT NULL,
  `grupoCotizacion` varchar(150) DEFAULT NULL,
  `nivelEstudios` varchar(150) DEFAULT NULL,
  `costeHora` varchar(50) DEFAULT NULL,
  `horarioLaboral` varchar(150) DEFAULT NULL,
  `discapacidad` varchar(3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `citas`
--

CREATE TABLE `citas` (
  `idcita` int(11) NOT NULL,
  `idempresa` int(11) NOT NULL,
  `diacita` varchar(20) NOT NULL,
  `fechacita` varchar(20) NOT NULL,
  `horacita` varchar(20) NOT NULL,
  `idllamada` int(11) NOT NULL,
  `codigousuario` varchar(20) NOT NULL,
  `comercial` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `commentarios`
--

CREATE TABLE `commentarios` (
  `idCommentario` int(11) NOT NULL,
  `commentario` varchar(300) NOT NULL,
  `StudentCursoID` int(11) NOT NULL,
  `date` date NOT NULL,
  `author` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cursos`
--

CREATE TABLE `cursos` (
  `Curso` varchar(300) NOT NULL,
  `Codigo` int(11) NOT NULL,
  `Descripcion` varchar(300) DEFAULT '',
  `Sector` text DEFAULT NULL,
  `tipodeCurso` varchar(100) DEFAULT NULL,
  `horasCurso` varchar(20) DEFAULT NULL,
  `idempresa` int(11) NOT NULL,
  `estadoCurso` varchar(100) NOT NULL DEFAULT 'interesado'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `empresas`
--

CREATE TABLE `empresas` (
  `idempresa` int(11) NOT NULL,
  `fecha` varchar(20) NOT NULL,
  `hora` varchar(10) NOT NULL,
  `nombre` varchar(300) NOT NULL,
  `cif` varchar(150) NOT NULL,
  `nss` varchar(20) NOT NULL,
  `numeroempleados` varchar(10) NOT NULL,
  `calle` varchar(60) NOT NULL,
  `cp` varchar(10) NOT NULL,
  `provincia` varchar(50) NOT NULL,
  `poblacion` varchar(50) NOT NULL,
  `pais` varchar(10) NOT NULL,
  `telef1` varchar(10) NOT NULL,
  `telef2` varchar(10) NOT NULL,
  `email` varchar(60) NOT NULL,
  `personacontacto` varchar(30) NOT NULL,
  `horacontactodesde` varchar(20) NOT NULL,
  `horacontactohasta` varchar(20) NOT NULL,
  `cargo` varchar(40) NOT NULL,
  `observacionesempresa` mediumtext NOT NULL,
  `protecciondedatos` varchar(10) NOT NULL,
  `codigousuario` varchar(20) NOT NULL,
  `sector` varchar(50) NOT NULL,
  `credito` varchar(50) NOT NULL,
  `email2` varchar(50) NOT NULL,
  `telef3` varchar(10) NOT NULL,
  `creditoAnhoAnterior` varchar(50) NOT NULL,
  `creditoGuardado` varchar(50) NOT NULL,
  `creditoCaducar` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `listacursos`
--

CREATE TABLE `listacursos` (
  `idCurso` int(11) NOT NULL,
  `nombreCurso` varchar(400) NOT NULL,
  `tipoCurso` varchar(20) NOT NULL,
  `horasCurso` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `llamadas`
--

CREATE TABLE `llamadas` (
  `idllamada` int(11) NOT NULL,
  `idempresa` int(100) NOT NULL,
  `piloto` varchar(50) NOT NULL,
  `interlocutor` varchar(50) NOT NULL,
  `fecha` varchar(10) NOT NULL,
  `hora` varchar(10) NOT NULL,
  `curso` varchar(50) NOT NULL,
  `nombrecurso` varchar(50) NOT NULL,
  `observacionesinterlocutor` mediumtext NOT NULL,
  `recibidopor` varchar(20) NOT NULL,
  `estadollamada` varchar(40) NOT NULL,
  `diacita` varchar(20) NOT NULL,
  `fechacita` varchar(20) NOT NULL,
  `horacita` varchar(20) NOT NULL,
  `fechapendiente` varchar(20) NOT NULL,
  `horapendiente` varchar(20) NOT NULL,
  `otrosnul` varchar(100) NOT NULL,
  `observacionesOtros` varchar(254) NOT NULL,
  `anoPedirCita` int(11) DEFAULT NULL,
  `mesPedirCita` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `idusuario` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `contrasena` varchar(50) NOT NULL,
  `tipo` varchar(50) NOT NULL,
  `codigousuario` varchar(10) NOT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ventas`
--

CREATE TABLE `ventas` (
  `idventa` int(11) NOT NULL,
  `fecha` varchar(50) NOT NULL,
  `hora` varchar(50) NOT NULL,
  `idcomercial` varchar(50) NOT NULL,
  `idempresa` varchar(50) NOT NULL,
  `curso1` varchar(100) NOT NULL,
  `nombrecurso1` varchar(150) NOT NULL,
  `horascurso1` varchar(50) NOT NULL,
  `modalidadcurso1` varchar(50) NOT NULL,
  `curso2` varchar(50) NOT NULL,
  `nombrecurso2` varchar(100) NOT NULL,
  `horascurso2` varchar(50) NOT NULL,
  `modalidadcurso2` varchar(50) NOT NULL,
  `curso3` varchar(50) NOT NULL,
  `nombrecurso3` varchar(100) NOT NULL,
  `horascurso3` varchar(50) NOT NULL,
  `modalidadcurso3` varchar(50) NOT NULL,
  `observacionesventa` mediumtext NOT NULL,
  `emailfactura` varchar(100) NOT NULL,
  `nombreasesoria` varchar(100) NOT NULL,
  `telfasesoria` varchar(20) NOT NULL,
  `mailasesoria` varchar(100) NOT NULL,
  `importe` varchar(50) NOT NULL,
  `fechacobro` varchar(50) NOT NULL,
  `formapago` varchar(50) NOT NULL,
  `numerocuenta` mediumtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `alumnocursos`
--
ALTER TABLE `alumnocursos`
  ADD PRIMARY KEY (`StudentCursoID`),
  ADD KEY `courseEmpresa` (`idEmpresa`),
  ADD KEY `courseAlumno` (`idAlumno`);

--
-- Indices de la tabla `alumnos`
--
ALTER TABLE `alumnos`
  ADD PRIMARY KEY (`idAlumno`);

--
-- Indices de la tabla `citas`
--
ALTER TABLE `citas`
  ADD PRIMARY KEY (`idcita`);

--
-- Indices de la tabla `commentarios`
--
ALTER TABLE `commentarios`
  ADD PRIMARY KEY (`idCommentario`),
  ADD KEY `courseconstraint` (`StudentCursoID`);

--
-- Indices de la tabla `cursos`
--
ALTER TABLE `cursos`
  ADD PRIMARY KEY (`Codigo`);

--
-- Indices de la tabla `empresas`
--
ALTER TABLE `empresas`
  ADD PRIMARY KEY (`idempresa`);

--
-- Indices de la tabla `listacursos`
--
ALTER TABLE `listacursos`
  ADD PRIMARY KEY (`idCurso`);

--
-- Indices de la tabla `llamadas`
--
ALTER TABLE `llamadas`
  ADD PRIMARY KEY (`idllamada`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`idusuario`);

--
-- Indices de la tabla `ventas`
--
ALTER TABLE `ventas`
  ADD PRIMARY KEY (`idventa`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `alumnocursos`
--
ALTER TABLE `alumnocursos`
  MODIFY `StudentCursoID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `alumnos`
--
ALTER TABLE `alumnos`
  MODIFY `idAlumno` int(50) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `citas`
--
ALTER TABLE `citas`
  MODIFY `idcita` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `commentarios`
--
ALTER TABLE `commentarios`
  MODIFY `idCommentario` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `cursos`
--
ALTER TABLE `cursos`
  MODIFY `Codigo` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `empresas`
--
ALTER TABLE `empresas`
  MODIFY `idempresa` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `listacursos`
--
ALTER TABLE `listacursos`
  MODIFY `idCurso` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `llamadas`
--
ALTER TABLE `llamadas`
  MODIFY `idllamada` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `idusuario` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `ventas`
--
ALTER TABLE `ventas`
  MODIFY `idventa` int(11) NOT NULL AUTO_INCREMENT;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `alumnocursos`
--
ALTER TABLE `alumnocursos`
  ADD CONSTRAINT `courseAlumno` FOREIGN KEY (`idAlumno`) REFERENCES `alumnos` (`idAlumno`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `courseEmpresa` FOREIGN KEY (`idEmpresa`) REFERENCES `empresas` (`idempresa`) ON DELETE SET NULL ON UPDATE SET NULL;

--
-- Filtros para la tabla `commentarios`
--
ALTER TABLE `commentarios`
  ADD CONSTRAINT `courseconstraint` FOREIGN KEY (`StudentCursoID`) REFERENCES `alumnocursos` (`StudentCursoID`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

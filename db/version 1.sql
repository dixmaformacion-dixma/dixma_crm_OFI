ALTER TABLE `alumnocursos` ADD COLUMN `contenido_id` INT;
ALTER TABLE `alumnocursos` ADD COLUMN `diploma_sin_firma` BOOLEAN DEFAULT 1;
ALTER TABLE `alumnocursos` ADD COLUMN `firma_docente` varchar(255);

CREATE TABLE `contenidos` (
  `idcontenido` int(11) NOT NULL,
  `N_Accion` float NOT NULL,
  `Anno` int(11) NOT NULL,
  `Contenido` longtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

ALTER TABLE `contenidos`
  ADD PRIMARY KEY (`idcontenido`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `contenidos`
--
ALTER TABLE `contenidos`
  MODIFY `idcontenido` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;


ALTER TABLE `cursos` ADD COLUMN `contenido_id` INT;

ALTER TABLE `empresas` ADD COLUMN `codigo` VARCHAR(20) AFTER idempresa;
ALTER TABLE `empresas` ADD COLUMN `referencia` VARCHAR(255);
ALTER TABLE `empresas` ADD COLUMN `horario` VARCHAR(255);

ALTER TABLE `llamadas` ADD COLUMN `codigo_llamada` VARCHAR(255) AFTER interlocutor;
ALTER TABLE `llamadas` ADD COLUMN `fecha_seguimiento` date;
ALTER TABLE `llamadas` ADD COLUMN `tipo_seguimiento` varchar(255);
ALTER TABLE `llamadas` ADD COLUMN `usuario_seguimiento` int(11);
ALTER TABLE `empresas` CHANGE `codigo` `codigo` VARCHAR(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL;
INSERT INTO `listacursos` (`idCurso`, `nombreCurso`, `tipoCurso`, `horasCurso`) VALUES (NULL, 'ESPACIOS CONFINADOS', 'OTROS', '8');
UPDATE `listacursos` SET `nombreCurso` = 'TRABAJOS EN ALTURA' WHERE `listacursos`.`idCurso` = 2;
ALTER TABLE `empresas` CHANGE `telef1` `telef1` VARCHAR(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL, CHANGE `telef2` `telef2` VARCHAR(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL, CHANGE `telef3` `telef3` VARCHAR(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL;
ALTER TABLE `llamadas` CHANGE `horapendiente` `horapendiente` VARCHAR(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL;
ALTER TABLE `llamadas` ADD `prioridad` VARCHAR(20) NULL DEFAULT 'BAJO' AFTER `usuario_seguimiento`;
ALTER TABLE `empresas` CHANGE `sector` `sector` VARCHAR(1000) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL;
ALTER TABLE `llamadas` ADD `usuario_asignador` VARCHAR(20) NULL AFTER `prioridad`;
UPDATE `empresas` SET `nombre`=UPPER(`nombre`);
ALTER TABLE `empresas` ADD `pdte_bonificar` VARCHAR(500) NULL AFTER `codigo`;
CREATE TABLE `tipos_cursos` (
  `tipos_cursos_id` int(11) NOT NULL,
  `codigo` varchar(255) NOT NULL,
  `nombre` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;
ALTER TABLE `tipos_cursos`
  ADD PRIMARY KEY (`tipos_cursos_id`);
ALTER TABLE `tipos_cursos`
  MODIFY `tipos_cursos_id` int(11) NOT NULL AUTO_INCREMENT;

INSERT INTO `tipos_cursos` (`tipos_cursos_id`,`codigo`, `nombre`) VALUES 
    (NULL,'TPM','TPM'), 
    (NULL,'TPC','TPC'),
    (NULL,'CONSTRUCION','CONSTRUCION'),
    (NULL,'AUTOESCUELA','AUTOESCUELA'),
    (NULL,'TPCMADERA','TPC MADERA Y MUEBLE'),
    (NULL,'TPCVIDREO','TPC VIDREO Y ROTULACIÓN'),
    (NULL,'OTROS','OTROS');

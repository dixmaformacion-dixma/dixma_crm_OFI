
<?php

function fetchAttachedCourses($idAlumno)
{
    $conexionPDO = realizarConexion();
    $sql = 'SELECT * FROM alumnocursos WHERE idAlumno = ? ORDER BY StudentCursoID DESC';
    $stmt = $conexionPDO->prepare($sql);
    $stmt->bindValue(1, $idAlumno, PDO::PARAM_INT);
    $stmt->execute();

    if ($cursos = $stmt->fetchAll()) {

        unset($conexionPDO);
        return $cursos;
    } else {

        return false;
    }
}
function cargarAlumnoCursos($year, $Tipo_Venta, $limit = 20, $offset = 0)
{
    $temp = "%";
    if ($Tipo_Venta != "Todos") {
        $temp = $Tipo_Venta;
    }

    $conexionPDO = realizarConexion();
    // SQL para obtener el total de cursos
    $sql_total = 'SELECT COUNT(*) FROM `alumnocursos` inner join alumnos on alumnocursos.`idAlumno` = alumnos.idAlumno WHERE (YEAR(`Fecha_Inicio`) = ?) and (`Tipo_Venta` LIKE ?)';
    $stmt_total = $conexionPDO->prepare($sql_total);
    $stmt_total->bindValue(1, $year, PDO::PARAM_STR);
    $stmt_total->bindValue(2, $temp, PDO::PARAM_STR);
    $stmt_total->execute();
    $total_cursos = $stmt_total->fetchColumn();

    // SQL para obtener los cursos paginados
    $sql = 'SELECT * FROM `alumnocursos` inner join alumnos on alumnocursos.`idAlumno` = alumnos.idAlumno WHERE (YEAR(`Fecha_Inicio`) = :year) and (`Tipo_Venta` LIKE :tipo_venta) ORDER BY `Fecha_Fin` DESC LIMIT :limit OFFSET :offset';

    $stmt = $conexionPDO->prepare($sql);

    $stmt->bindValue(':year', $year, PDO::PARAM_STR);
    $stmt->bindValue(':tipo_venta', $temp, PDO::PARAM_STR);
    $stmt->bindValue(':limit', (int) $limit, PDO::PARAM_INT);
    $stmt->bindValue(':offset', (int) $offset, PDO::PARAM_INT);

    $stmt->execute();

    if ($alumnocurso = $stmt->fetchAll()) {
        unset($conexionPDO);
        return ['cursos' => $alumnocurso, 'total' => $total_cursos];
    } else {
        return false;
    }
}
function cargarAlumnoCursosActivos($year, $Tipo_Venta)
{
    $temp = "%";
    if ($Tipo_Venta != "Todos") {
        $temp = $Tipo_Venta;
    }

    $conexionPDO = realizarConexion();
    $sql = 'SELECT * FROM `alumnocursos` inner join alumnos on alumnocursos.`idAlumno` = alumnos.idAlumno WHERE (YEAR(`Fecha_Inicio`) = ?) and (`Tipo_Venta` LIKE ?) AND (`status_curso` = "en curso") ORDER BY `Fecha_Fin`, `N_Accion`';

    $stmt = $conexionPDO->prepare($sql);

    $stmt->bindValue(1, $year, PDO::PARAM_STR);
    $stmt->bindValue(2, $temp, PDO::PARAM_STR);

    $stmt->execute();

    if ($alumnocurso = $stmt->fetchAll()) {
        unset($conexionPDO);
        return $alumnocurso;
    } else {
        return false;
    }
}
function cargarAlumnoCursosPendientes()
{

    $conexionPDO = realizarConexion();
    $sql = 'SELECT * FROM `alumnocursos` inner join alumnos on alumnocursos.`idAlumno` = alumnos.idAlumno WHERE `Fecha_Inicio` = 0 or `Fecha_Fin` = 0 or `Fecha_Fin` = NULL or `Fecha_Inicio` = NULL or `Fecha_Fin` = "" or `Fecha_Inicio` = "" or `Fecha_Fin` IS NULL or `Fecha_Inicio` IS NULL OR `Fecha_Inicio` = "1970-01-01" OR `Fecha_Fin` = "1970-01-01" OR `Fecha_Inicio` = "0001-01-01" OR `Fecha_Fin` = "0001-01-01" ORDER BY `Fecha_Fin`';

    $stmt = $conexionPDO->prepare($sql);

    $stmt->execute();

    if ($alumnocurso = $stmt->fetchAll()) {
        unset($conexionPDO);
        return $alumnocurso;
    } else {
        return false;
    }
}
function buscarAlumnoCursos($filters, $operators, $values, $limit = 20, $offset = 0)
{
    $conexionPDO = realizarConexion();
    $base_sql = '
    SELECT SQL_CALC_FOUND_ROWS
    alumnos.nombre as nombre,
    alumnos.apellidos as apellidos,
    alumnos.telefono as telefono,
    alumnos.email as email,
    alumnos.nif as nif,
    empresas.nombre as nombreEmpresa,
    empresas.email as emailEmpresa,
    empresas.telef1 as telef1,
    empresas.telef2 as telef2,
    empresas.personacontacto as personacontacto,
    alumnocursos.*
    FROM `alumnocursos` 
    LEFT JOIN alumnos on alumnocursos.idAlumno = alumnos.idAlumno 
    LEFT JOIN empresas on alumnocursos.idEmpresa = empresas.idempresa 
    ';

    $where_clauses = [];
    $params = [];

    if (!empty($filters)) {
        foreach ($filters as $key => $filter) {
            $operator = $operators[$key] ?? 'equal';
            $value = $values[$key] ?? null;

            if ($value === null || $value === '') continue;

            // Mapeo de operadores para SQL
            $sql_operator = '=';
            switch ($operator) {
                case 'equal':
                    $sql_operator = '=';
                    break;
                case 'not_equal':
                    $sql_operator = '!=';
                    break;
                case 'contains':
                    $sql_operator = 'LIKE';
                    $value = '%' . $value . '%';
                    break;
                case 'greater_than':
                    $sql_operator = '>';
                    break;
                case 'less_than':
                    $sql_operator = '<';
                    break;
            }

            // Manejo especial para el año
            if ($filter === 'Anno') {
                $where_clauses[] = "(YEAR(alumnocursos.Fecha_Inicio) = ? OR YEAR(alumnocursos.Fecha_Fin) = ?)";
                $params[] = $value;
                $params[] = $value;
            } else {
                $column = '';
                // Mapeo de nombres de filtro a columnas de la base de datos
                switch ($filter) {
                    case 'StudentCursoID':
                        $column = 'alumnocursos.StudentCursoID';
                        break;
                    case 'Denominacion':
                        $column = 'alumnocursos.Denominacion';
                        break;
                    case 'N_Accion':
                        $column = 'alumnocursos.N_Accion';
                        break;
                    case 'N_Grupo':
                        $column = 'alumnocursos.N_Grupo';
                        break;
                    case 'Modalidad':
                        $column = 'alumnocursos.Modalidad';
                        break;
                    case 'tutor':
                        $column = 'alumnocursos.tutor';
                        break;
                    case 'Tipo_Venta':
                        $column = 'alumnocursos.Tipo_Venta';
                        break;
                    case 'status_curso':
                        $column = 'alumnocursos.status_curso';
                        break;
                    case 'nombre_alumno':
                        $where_clauses[] = "(alumnos.nombre LIKE ? OR alumnos.apellidos LIKE ?)";
                        $params[] = '%' . $value . '%';
                        $params[] = '%' . $value . '%';
                        continue 2; // Salta al siguiente filtro
                    case 'nombre_empresa':
                        $column = 'empresas.nombre';
                        break;
                }

                if ($column) {
                    $where_clauses[] = "$column $sql_operator ?";
                    $params[] = $value;
                }
            }
        }
    }

    $sql = $base_sql;
    if (!empty($where_clauses)) {
        $sql .= ' WHERE ' . implode(' AND ', $where_clauses);
    }

    $sql .= " ORDER BY alumnocursos.Fecha_Inicio DESC, alumnocursos.StudentCursoID DESC";
    $sql .= " LIMIT " . (int)$limit . " OFFSET " . (int)$offset;

    $stmt = $conexionPDO->prepare($sql);
    foreach ($params as $key => $val) {
        $stmt->bindValue($key + 1, $val);
    }

    $stmt->execute();
    $cursos = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Obtener el número total de filas que coincidirían sin el LIMIT
    $total_cursos = $conexionPDO->query("SELECT FOUND_ROWS()")->fetchColumn();

    return [
        'cursos' => $cursos,
        'total' => (int)$total_cursos
    ];
}
function cargarAlumnoCurso($StudentCursoID)
{

    $conexionPDO = realizarConexion();
    $sql = "SELECT * FROM alumnocursos WHERE StudentCursoID = $StudentCursoID";
    $stmt = $conexionPDO->query($sql);

    if ($alumnocurso = $stmt->fetch()) {

        unset($conexionPDO);
        return $alumnocurso;
    } else {

        return false;
    }
}
function alumnoCursoAdjuntar($datos)
{
    $conexionPDO = realizarConexion();
    $sql = "INSERT INTO `alumnocursos`(`StudentCursoID`, `Denominacion`, `N_Accion`, `N_Grupo`, `N_Horas`, `Modalidad`, `DOC_AF`, `Fecha_Inicio`, `Fecha_Fin`, `tutor`, `idAlumno`, `idCurso`, `seguimento0`, `seguimento1`, `seguimento2`, `seguimento3`, `seguimento4`, `seguimento5`, `idEmpresa`, `Tipo_Venta`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conexionPDO->prepare($sql);

    if ($stmt) {

        $stmt->bindValue(1, 0, PDO::PARAM_INT);
        $stmt->bindValue(2, $datos['Denominacion'], PDO::PARAM_STR);
        $stmt->bindValue(3, $datos['N_Accion'], PDO::PARAM_STR);
        $stmt->bindValue(4, $datos['N_Grupo'], PDO::PARAM_STR);
        $stmt->bindValue(5, $datos['N_Horas'], PDO::PARAM_STR);
        $stmt->bindValue(6, $datos['Modalidad'], PDO::PARAM_STR);
        $stmt->bindValue(7, $datos['DOC_AF'], PDO::PARAM_STR);
        $stmt->bindValue(8, $datos['Fecha_Inicio'], PDO::PARAM_STR);
        $stmt->bindValue(9, $datos['Fecha_Fin'], PDO::PARAM_STR);
        $stmt->bindValue(10, $datos['tutor'], PDO::PARAM_STR);
        $stmt->bindValue(11, $datos['idAlumno'], PDO::PARAM_STR);
        $stmt->bindValue(12, $datos['idCurso'], PDO::PARAM_STR);
        $stmt->bindValue(13, $datos['seguimento0'], PDO::PARAM_STR);
        $stmt->bindValue(14, $datos['seguimento1'], PDO::PARAM_STR);
        $stmt->bindValue(15, $datos['seguimento2'], PDO::PARAM_STR);
        $stmt->bindValue(16, $datos['seguimento3'], PDO::PARAM_STR);
        $stmt->bindValue(17, $datos['seguimento4'], PDO::PARAM_STR);
        $stmt->bindValue(18, $datos['seguimento5'], PDO::PARAM_STR);
        $stmt->bindValue(19, $datos['idEmpresa'], PDO::PARAM_STR);
        $stmt->bindValue(20, $datos['Tipo_Venta'], PDO::PARAM_STR);

        return $stmt->execute();
    } else {
        return false;
    }

    unset($conexionPDO);
}
function alumnoCursoAdjuntarMultiple($listaAlumnos, $datosCurso)
{
    $conexionPDO = realizarConexion();

    try {
        $conexionPDO->beginTransaction();

        $sql = "INSERT INTO `alumnocursos`(`Denominacion`, `N_Accion`, `N_Grupo`, `N_Horas`, `Modalidad`, `DOC_AF`, `Fecha_Inicio`, `Fecha_Fin`, `tutor`, `idAlumno`, `idCurso`, `idEmpresa`, `Tipo_Venta`, `status_curso`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'en curso')";
        $stmt = $conexionPDO->prepare($sql);

        if (!$stmt) {
            throw new Exception("Error al preparar la consulta.");
        }

        foreach ($listaAlumnos as $idAlumno) {
            $stmt->bindValue(1, $datosCurso['Denominacion'], PDO::PARAM_STR);
            $stmt->bindValue(2, $datosCurso['N_Accion'], PDO::PARAM_STR);
            $stmt->bindValue(3, $datosCurso['N_Grupo'], PDO::PARAM_STR);
            $stmt->bindValue(4, $datosCurso['N_Horas'], PDO::PARAM_STR);
            $stmt->bindValue(5, $datosCurso['Modalidad'], PDO::PARAM_STR);
            $stmt->bindValue(6, $datosCurso['DOC_AF'], PDO::PARAM_STR);
            $stmt->bindValue(7, $datosCurso['Fecha_Inicio'], PDO::PARAM_STR);
            $stmt->bindValue(8, $datosCurso['Fecha_Fin'], PDO::PARAM_STR);
            $stmt->bindValue(9, $datosCurso['tutor'], PDO::PARAM_STR);
            $stmt->bindValue(10, $idAlumno, PDO::PARAM_INT);
            $stmt->bindValue(11, $datosCurso['idCurso'], PDO::PARAM_INT);
            $stmt->bindValue(12, $datosCurso['idEmpresa'], PDO::PARAM_INT);
            $stmt->bindValue(13, $datosCurso['Tipo_Venta'], PDO::PARAM_STR);

            if (!$stmt->execute()) {
                throw new Exception("Error al insertar el curso para el alumno ID: " . $idAlumno);
            }
        }

        $conexionPDO->commit();
        return true;
    } catch (Exception $e) {
        $conexionPDO->rollBack();
        // Opcional: registrar el error $e->getMessage()
        return false;
    }
}
function alumnoCursoEditar($datos)
{
    $conexionPDO = realizarConexion();
    $sql = "UPDATE `alumnocursos` SET `Denominacion` = ?, `N_Accion` = ?, `N_Grupo` = ?, `N_Horas` = ?, `Modalidad` = ?, `DOC_AF` = ?, `Fecha_Inicio` = ?, `Fecha_Fin` = ?, `tutor` = ?, `idCurso` = ?, `idEmpresa` = ?, `Tipo_Venta` = ?, `Diploma_Status` = ?, `Diploma_Status_Ultimo_Cambio` = ?,
    `Fecha_De_Envio_De_la_Factura` = NULLIF(?,''), `Fecha_De_Recibido_De_La_Factura` = NULLIF(?,''), `Factura` = ?, `AP` = ?, `Recibi_Material` = ?, `CC` = ?, `RLT` = ?, `status_curso` = ?, `contenido_id` = ?, `diploma_sin_firma` = ?,`firma_docente`=? WHERE `StudentCursoID` = ?";
    $stmt = $conexionPDO->prepare($sql);

    if ($stmt) {
        $stmt->bindValue(1, $datos['Denominacion'], PDO::PARAM_STR);
        $stmt->bindValue(2, $datos['N_Accion'], PDO::PARAM_INT);
        $stmt->bindValue(3, $datos['N_Grupo'], PDO::PARAM_INT);
        $stmt->bindValue(4, $datos['N_Horas'], PDO::PARAM_INT);
        $stmt->bindValue(5, $datos['Modalidad'], PDO::PARAM_STR);
        $stmt->bindValue(6, $datos['DOC_AF'], PDO::PARAM_STR);
        $stmt->bindValue(7, $datos['Fecha_Inicio'], PDO::PARAM_STR);
        $stmt->bindValue(8, $datos['Fecha_Fin'], PDO::PARAM_STR);
        $stmt->bindValue(9, $datos['tutor'], PDO::PARAM_STR);
        $stmt->bindValue(10, $datos['idCurso'], PDO::PARAM_INT);
        $stmt->bindValue(11, $datos['idEmpresa'], PDO::PARAM_INT);
        $stmt->bindValue(12, $datos['Tipo_Venta'], PDO::PARAM_STR);
        $stmt->bindValue(13, $datos['Diploma_Status'], PDO::PARAM_STR);
        $stmt->bindValue(14, $datos['Diploma_Status_Ultimo_Cambio'], PDO::PARAM_STR);
        $stmt->bindValue(15, $datos['Fecha_De_Envio_De_la_Factura'], PDO::PARAM_STR);
        $stmt->bindValue(16, $datos['Fecha_De_Recibido_De_La_Factura'], PDO::PARAM_STR);
        $stmt->bindValue(17, $datos['Factura'], PDO::PARAM_STR);
        $stmt->bindValue(18, $datos['AP'], PDO::PARAM_STR);
        $stmt->bindValue(19, $datos['Recibi_Material'], PDO::PARAM_INT);
        $stmt->bindValue(20, $datos['CC'], PDO::PARAM_INT);
        $stmt->bindValue(21, $datos['RLT'], PDO::PARAM_INT);
        $stmt->bindValue(22, $datos['status_curso'], PDO::PARAM_STR);
        $stmt->bindValue(23, $datos['contenido_id'], PDO::PARAM_STR);
        $stmt->bindValue(24, $datos['diploma_sin_firma'], PDO::PARAM_STR);
        $stmt->bindValue(25, $datos['firma_docente'], PDO::PARAM_STR);
        $stmt->bindValue(26, $datos['StudentCursoID'], PDO::PARAM_INT);

        $executeStatus = $stmt->execute();
        if (!$executeStatus) {
            echo "<pre>";
            print_r($datos);
            echo "</pre> DBERROR:";
            print_r($stmt->errorInfo());
            echo "<br>";
        }
        return $executeStatus;
    } else {
        return false;
    }

    unset($conexionPDO);
}
function editarFetchaSeguimentos($datos)
{
    $conexionPDO = realizarConexion();
    $sql = "UPDATE `alumnocursos` SET `seguimento0`=?, `seguimento1`=?,`seguimento2`=?,`seguimento3`=?,`seguimento4`=?,`seguimento5`=?,`seguimento0check`=?,`seguimento1check`=?,`seguimento2check`=?,`seguimento3check`=?,`seguimento4check`=?,`seguimento5check`=? WHERE `StudentCursoID` = ?";
    $stmt = $conexionPDO->prepare($sql);

    if ($stmt) {
        $stmt->bindValue(1, $datos['seguimento0'], PDO::PARAM_STR);
        $stmt->bindValue(2, $datos['seguimento1'], PDO::PARAM_STR);
        $stmt->bindValue(3, $datos['seguimento2'], PDO::PARAM_STR);
        $stmt->bindValue(4, $datos['seguimento3'], PDO::PARAM_STR);
        $stmt->bindValue(5, $datos['seguimento4'], PDO::PARAM_STR);
        $stmt->bindValue(6, $datos['seguimento5'], PDO::PARAM_STR);
        $stmt->bindValue(7, $datos['seguimento0check'], PDO::PARAM_INT);
        $stmt->bindValue(8, $datos['seguimento1check'], PDO::PARAM_INT);
        $stmt->bindValue(9, $datos['seguimento2check'], PDO::PARAM_INT);
        $stmt->bindValue(10, $datos['seguimento3check'], PDO::PARAM_INT);
        $stmt->bindValue(11, $datos['seguimento4check'], PDO::PARAM_INT);
        $stmt->bindValue(12, $datos['seguimento5check'], PDO::PARAM_INT);
        $stmt->bindValue(13, $datos['StudentCursoID'], PDO::PARAM_STR);

        return $stmt->execute();
    } else {
        return false;
    }
    unset($conexionPDO);
}
function cargarCursoCommentario($StudentCursoID)
{
    $conexionPDO = realizarConexion();
    $sql = 'SELECT * FROM commentarios where StudentCursoID =' . $StudentCursoID;
    $stmt = $conexionPDO->query($sql);

    if ($cursos = $stmt->fetchAll()) {

        unset($conexionPDO);
        return $cursos;
    } else {

        return false;
    }
}
function insertarCursoCommentario($datosCommentario)
{
    $conexionPDO = realizarConexion();
    $sql = "INSERT INTO `commentarios`(`idCommentario`, `commentario`, `StudentCursoID`, `date`, `author`) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conexionPDO->prepare($sql);

    if ($stmt) {

        $stmt->bindValue(1, 0, PDO::PARAM_INT);
        $stmt->bindValue(2, $datosCommentario['commentario'], PDO::PARAM_STR);
        $stmt->bindValue(3, $datosCommentario['StudentCursoID'], PDO::PARAM_STR);
        $stmt->bindValue(4, $datosCommentario['date'], PDO::PARAM_STR);
        $stmt->bindValue(5, $datosCommentario['author'], PDO::PARAM_STR);

        return $stmt->execute();
    } else {
        return false;
    }

    unset($conexionPDO);
}
function cargarCursoLlamadas($date, $Tipo_Venta = "Todos", $missedCalls = "on")
{
    $temp = "%";
    if ($Tipo_Venta != "Todos") {
        $temp = $Tipo_Venta;
    }

    $conexionPDO = realizarConexion();

    if ($missedCalls == "on") {
        $sql = 'SELECT * FROM `alumnocursos` inner join alumnos on alumnocursos.`idAlumno` = alumnos.idAlumno WHERE
        (
            (`Tipo_Venta` LIKE ?) 
            AND 
            (
                `seguimento0` = ? OR 
                `seguimento1` = ? OR 
                `seguimento2` = ? OR 
                `seguimento3` = ? OR 
                `seguimento4` = ? OR 
                `seguimento5` = ?
            )
        ) 
        OR 
        (
            (`Tipo_Venta` LIKE ?) 
            AND 
            (
                (`seguimento0` < ? AND seguimento0check = 0) OR 
                (`seguimento1` < ? AND seguimento1check = 0) OR 
                (`seguimento2` < ? AND seguimento2check = 0) OR 
                (`seguimento3` < ? AND seguimento3check = 0) OR 
                (`seguimento4` < ? AND seguimento4check = 0) OR 
                (`seguimento5` < ? AND seguimento5check = 0)
            )
        )';
        $stmt = $conexionPDO->prepare($sql);

        $stmt->bindValue(1, $temp, PDO::PARAM_STR);
        $stmt->bindValue(2, $date, PDO::PARAM_STR);
        $stmt->bindValue(3, $date, PDO::PARAM_STR);
        $stmt->bindValue(4, $date, PDO::PARAM_STR);
        $stmt->bindValue(5, $date, PDO::PARAM_STR);
        $stmt->bindValue(6, $date, PDO::PARAM_STR);
        $stmt->bindValue(7, $date, PDO::PARAM_STR);
        $stmt->bindValue(8, $temp, PDO::PARAM_STR);
        $stmt->bindValue(9, $date, PDO::PARAM_STR);
        $stmt->bindValue(10, $date, PDO::PARAM_STR);
        $stmt->bindValue(11, $date, PDO::PARAM_STR);
        $stmt->bindValue(12, $date, PDO::PARAM_STR);
        $stmt->bindValue(13, $date, PDO::PARAM_STR);
        $stmt->bindValue(14, $date, PDO::PARAM_STR);
    } else {
        $sql = 'SELECT * FROM `alumnocursos` inner join alumnos on alumnocursos.`idAlumno` = alumnos.idAlumno WHERE
        (`Tipo_Venta` LIKE ?) AND 
        (`seguimento0` = ? OR 
        `seguimento1` = ? OR 
        `seguimento2` = ? OR 
        `seguimento3` = ? OR 
        `seguimento4` = ? OR 
        `seguimento5` = ?
        )';

        $stmt = $conexionPDO->prepare($sql);

        $stmt->bindValue(1, $temp, PDO::PARAM_STR);
        $stmt->bindValue(2, $date, PDO::PARAM_STR);
        $stmt->bindValue(3, $date, PDO::PARAM_STR);
        $stmt->bindValue(4, $date, PDO::PARAM_STR);
        $stmt->bindValue(5, $date, PDO::PARAM_STR);
        $stmt->bindValue(6, $date, PDO::PARAM_STR);
        $stmt->bindValue(7, $date, PDO::PARAM_STR);
    }

    $stmt->execute();

    if ($alumnocurso = $stmt->fetchAll()) {
        unset($conexionPDO);
        return $alumnocurso;
    } else {
        return false;
    }
}

//if sampleDate is today, function returns it in style specified in $spancss
function checkAndHighlightDate(
    $sampleDate,
    $checked,
    $date = null,
    $highlightSpanCss = "color: blue",
    $missedSpanCss = "background-color:red; color: white; border:1px solid black",
    $completedSpanCss = "color: green",
    $regularSpanCss = ""
) {
    if ($date == null) {
        $date = date("Y-m-d");
    }

    $formattedDate = formattedDate($sampleDate);

    if ($sampleDate < $date) {
        if ($checked == 0) {
            return '<span style="' . $missedSpanCss . '">' . $formattedDate . '</span>';
        } else {
            return '<span style="' . $completedSpanCss . '">' . $formattedDate . '</span>';
        }
    } else {
        if ($sampleDate == $date) {
            return '<span style="' . $highlightSpanCss . '">' . $formattedDate . '</span>';
        }
        return '<span style="' . $regularSpanCss . '">' . $formattedDate . '</span>';
    }
}
function dateMatchHTML($sampleDate, $date = null, $acceptHTML = "*", $rejectHTML = "")
{
    if ($date == null) {
        $date = date("Y-m-d");
    }

    if ($sampleDate == $date) {
        return $acceptHTML;
    } else {
        return $rejectHTML;
    }
}

function eliminarAlumnoCurso($StudentCursoID)
{
    $conexionPDO = realizarConexion();
    $sql = "DELETE FROM `alumnocursos` WHERE `StudentCursoID` = ?";
    $stmt = $conexionPDO->prepare($sql);
    $stmt->bindValue(1, $StudentCursoID, PDO::PARAM_INT);
    return $stmt->execute();
}
?>

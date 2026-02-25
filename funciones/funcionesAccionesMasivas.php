<?php
/**
 * Handler para acciones masivas (actualización en bloque) de Diploma_Status y status_curso
 * para todos los registros de un N_Accion + N_Grupo dado.
 *
 * Espera POST con:
 *   n_accion        (int)
 *   n_grupo         (int)
 *   diploma_status  (string|"no_change")
 *   status_curso    (string|"no_change")
 */

include_once __DIR__ . "/conexionBD.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accionesMasivasSubmit'])) {

    session_start();
    if (empty($_SESSION)) {
        http_response_code(403);
        echo json_encode(['ok' => false, 'msg' => 'No autorizado']);
        exit;
    }

    $diploma_status = $_POST['diploma_status'] ?? 'no_change';
    $status_curso   = $_POST['status_curso']   ?? 'no_change';

    // IDs visibles en la lista (enviados como JSON array)
    $ids_raw = $_POST['student_ids'] ?? '';
    $ids = [];
    if (is_array($ids_raw)) {
        $ids = array_map('intval', $ids_raw);
    } else {
        $decoded = json_decode($ids_raw, true);
        if (is_array($decoded)) {
            $ids = array_map('intval', $decoded);
        }
    }
    $ids = array_filter($ids, function($v) { return $v > 0; });
    $ids = array_values($ids);

    $valid_diploma = ['No hecho', 'Hecho', 'Impreso', 'Entregado', 'Copia recibida', 'Enviado por Mail'];
    $valid_status  = ['en curso', 'finalizado', 'descargado', 'cerrado', 'baja', 'problem'];

    if (empty($ids)) {
        echo json_encode(['ok' => false, 'msg' => 'No se encontraron registros visibles para actualizar']);
        exit;
    }

    $sets   = [];
    $params = [];

    if ($diploma_status !== 'no_change' && in_array($diploma_status, $valid_diploma)) {
        $sets[]  = '`Diploma_Status` = ?';
        $params[] = $diploma_status;
        $sets[]  = '`Diploma_Status_Ultimo_Cambio` = ?';
        $params[] = date('Y-m-d');
    }

    if ($status_curso !== 'no_change' && in_array($status_curso, $valid_status)) {
        $sets[]  = '`status_curso` = ?';
        $params[] = $status_curso;
    }

    if (empty($sets)) {
        echo json_encode(['ok' => false, 'msg' => 'No se ha seleccionado ningún cambio']);
        exit;
    }

    // Placeholders per IN clause
    $placeholders = implode(',', array_fill(0, count($ids), '?'));
    foreach ($ids as $id) {
        $params[] = $id;
    }

    $sql  = 'UPDATE `alumnocursos` SET ' . implode(', ', $sets) . ' WHERE `StudentCursoID` IN (' . $placeholders . ')';
    $pdo  = realizarConexion();
    $stmt = $pdo->prepare($sql);

    if ($stmt && $stmt->execute($params)) {
        $affected = $stmt->rowCount();
        echo json_encode(['ok' => true, 'affected' => $affected]);
    } else {
        echo json_encode(['ok' => false, 'msg' => 'Error al actualizar la base de datos']);
    }
    exit;
}

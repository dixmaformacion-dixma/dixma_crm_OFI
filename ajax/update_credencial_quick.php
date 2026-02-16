<?php
/**
 * Aggiornamento rapido credenziali (CV, Guía, Revisión)
 * Endpoint AJAX per modifiche inline
 */

session_start();

// Verifica autenticazione: permette admin e tutoria
if (empty($_SESSION) || !isset($_SESSION['rol']) || !in_array($_SESSION['rol'], ['admin', 'tutoria'])) {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'No autorizado']);
    exit();
}

header('Content-Type: application/json');

require_once '../funciones/conexionBD.php';
$pdo = realizarConexion();

// Leggi dati JSON
$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['id']) || !isset($data['field'])) {
    echo json_encode(['success' => false, 'message' => 'Datos incompletos']);
    exit();
}

$id = (int)$data['id'];
$field = $data['field'];
$value = $data['value'] ?? null;

try {
    switch ($field) {
        case 'cv':
        case 'guia':
            // Valida 0 o 1
            $value = ($value == 1) ? 1 : 0;
            $stmt = $pdo->prepare("UPDATE credenciales_dixma SET $field = ? WHERE id = ?");
            $stmt->execute([$value, $id]);
            break;
            
        case 'revision':
            // Valida M, MJ, M/MJ o NULL
            $value = in_array($value, ['M', 'MJ', 'M/MJ']) ? $value : null;
            $stmt = $pdo->prepare("UPDATE credenciales_dixma SET revision = ? WHERE id = ?");
            $stmt->execute([$value, $id]);
            break;
            
        case 'revision_highlighted':
            // Toggle 0/1
            $value = ($value == 1) ? 1 : 0;
            $stmt = $pdo->prepare("UPDATE credenciales_dixma SET revision_highlighted = ? WHERE id = ?");
            $stmt->execute([$value, $id]);
            break;
            
        default:
            throw new Exception('Campo no válido');
    }
    
    echo json_encode([
        'success' => true, 
        'message' => 'Actualizado correctamente',
        'field' => $field,
        'value' => $value
    ]);
    
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false, 
        'message' => 'Error al actualizar: ' . $e->getMessage()
    ]);
}

<?php
session_start();
header('Content-Type: application/json');

// Verifica autenticazione
if (empty($_SESSION['rol']) || !in_array($_SESSION['rol'], ['admin', 'tutoria'])) {
    echo json_encode(['success' => false, 'message' => 'No autorizado']);
    exit();
}

require_once '../funciones/conexionBD.php';
$pdo = realizarConexion();

// Leggi parametri
$input = file_get_contents('php://input');
$data = json_decode($input, true);

if (!$data || !isset($data['numero_accion'], $data['year'], $data['tipo'])) {
    echo json_encode(['success' => false, 'message' => 'Parámetros incompletos']);
    exit();
}

$numero_accion = $data['numero_accion'];
$year = (int)$data['year'];
$tipo = $data['tipo'];

try {
    // Cerca credenziali
    $stmt = $pdo->prepare("
        SELECT usuario_supervisor, password_supervisor, usuario_profesor, password_profesor, nombre_curso
        FROM credenciales_dixma
        WHERE numero_accion = ? AND YEAR(inicio_curso) = ? AND activo = 1
        LIMIT 1
    ");
    $stmt->execute([$numero_accion, $year]);
    $cred = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$cred) {
        echo json_encode([
            'success' => false,
            'message' => "No hay credenciales para N° Acción $numero_accion (Año $year)"
        ]);
        exit();
    }
    
    // Seleziona tipo
    if ($tipo === 'profesor') {
        $username = $cred['usuario_profesor'];
        $password = $cred['password_profesor'];
        if (empty($username) || empty($password)) {
            echo json_encode(['success' => false, 'message' => 'Sin credenciales profesor']);
            exit();
        }
    } else {
        $username = $cred['usuario_supervisor'];
        $password = $cred['password_supervisor'];
    }
    
    // Genera token
    $token = bin2hex(random_bytes(32));
    $expires = date('Y-m-d H:i:s', strtotime('+5 minutes'));
    
    $stmt = $pdo->prepare("INSERT INTO login_tokens (token, username, password, expires_at, created_at) VALUES (?, ?, ?, ?, NOW())");
    $stmt->execute([$token, $username, base64_encode($password), $expires]);
    
    echo json_encode([
        'success' => true,
        'url' => 'autologin_dixma.php?token=' . $token,
        'curso' => $cred['nombre_curso'],
        'tipo' => $tipo
    ]);
    
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Error DB: ' . $e->getMessage()]);
}

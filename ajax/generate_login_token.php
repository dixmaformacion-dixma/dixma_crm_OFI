<?php
/**
 * Genera token sicuro monouso per auto-login
 * Endpoint AJAX
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

if (!isset($data['username']) || !isset($data['password'])) {
    echo json_encode(['success' => false, 'message' => 'Credenciales incompletas']);
    exit();
}

$username = $data['username'];
$password = $data['password'];

try {
    // Genera token sicuro monouso
    $token = bin2hex(random_bytes(32)); // 64 caratteri esadecimali
    $expires = date('Y-m-d H:i:s', strtotime('+5 minutes')); // Scade in 5 minuti
    
    // Salva token cifrato nel database
    $stmt = $pdo->prepare("
        INSERT INTO login_tokens (token, username, password, expires_at, created_at)
        VALUES (?, ?, ?, ?, NOW())
    ");
    
    $stmt->execute([
        $token,
        $username,
        base64_encode($password), // Codifica base64 (reversibile)
        $expires
    ]);
    
    echo json_encode([
        'success' => true,
        'token' => $token,
        'url' => 'autologin_dixma.php?token=' . $token
    ]);
    
} catch (PDOException $e) {
    http_response_code(500);
    
    // Messaggio specifico se la tabella non esiste
    if (strpos($e->getMessage(), "doesn't exist") !== false || strpos($e->getMessage(), "no existe") !== false) {
        echo json_encode([
            'success' => false,
            'message' => 'La tabla login_tokens no existe. Ejecuta setup_login_tokens.sql primero.'
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Error al generar token: ' . $e->getMessage()
        ]);
    }
}

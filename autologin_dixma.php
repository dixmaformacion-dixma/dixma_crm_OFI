<?php
/**
 * Auto-login sicuro tramite token monouso
 */

session_start();

require_once 'funciones/conexionBD.php';
$pdo = realizarConexion();

// Ricevi token
$token = $_GET['token'] ?? '';

if (empty($token)) {
    die('❌ Token mancante');
}

$username = '';
$password = '';

try {
    // Cerca token valido
    $stmt = $pdo->prepare("
        SELECT username, password, expires_at, used
        FROM login_tokens
        WHERE token = ?
        LIMIT 1
    ");
    $stmt->execute([$token]);
    $tokenData = $stmt->fetch(PDO::FETCH_ASSOC);
    
    // Validazioni
    if (!$tokenData) {
        die('❌ Token non valido - Il token non è stato trovato nel database. Assicurati di aver eseguito setup_login_tokens.sql');
    }
    
    if ($tokenData['used'] == 1) {
        die('❌ Token già utilizzato - Ogni token può essere usato solo una volta');
    }
    
    if (strtotime($tokenData['expires_at']) < time()) {
        die('❌ Token scaduto - Il link è valido solo per 5 minuti');
    }
    
    // Decodifica credenziali
    $username = $tokenData['username'];
    $password = base64_decode($tokenData['password']);
    
    // Debug (rimuovi in produzione)
    $debugInfo = "Username: " . htmlspecialchars($username) . "<br>Password trovata: " . (empty($password) ? 'NO' : 'SI');
    
    // Marca token come usato
    $stmt = $pdo->prepare("UPDATE login_tokens SET used = 1 WHERE token = ?");
    $stmt->execute([$token]);
    
} catch (PDOException $e) {
    die('❌ Errore database: ' . htmlspecialchars($e->getMessage()) . '<br><br>Hai eseguito setup_login_tokens.sql per creare la tabella?');
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accesso Dixma Virtual Aula</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .loading {
            text-align: center;
            padding: 40px;
            background: white;
            border-radius: 16px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
            max-width: 400px;
        }
        h2 {
            color: #333;
            margin-bottom: 20px;
        }
        .spinner {
            border: 5px solid #f3f3f3;
            border-top: 5px solid #1e989e;
            border-radius: 50%;
            width: 60px;
            height: 60px;
            animation: spin 1s linear infinite;
            margin: 30px auto;
        }
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        .info {
            color: #666;
            font-size: 14px;
            margin-top: 20px;
        }
        .secure-badge {
            display: inline-block;
            padding: 8px 16px;
            background: #e8f5e9;
            color: #2e7d32;
            border-radius: 20px;
            font-size: 12px;
            margin-top: 15px;
        }
    </style>
</head>
<body>
    <div class="loading">
        <h2>🔐 Accesso Automatico</h2>
        <div class="spinner"></div>
        <p class="info">Verrai reindirizzato automaticamente al campus virtuale...</p>
        <div class="secure-badge">🔒 Token Sicuro</div>
        
        <!-- Form nascosto con auto-submit -->
        <form action="https://dixma.virtual-aula.com/login/index.php" method="post" id="autoLoginForm">
            <input type="hidden" name="username" value="<?php echo htmlspecialchars($username); ?>">
            <input type="hidden" name="password" value="<?php echo htmlspecialchars($password); ?>">
            <input type="hidden" name="anchor" value="">
        </form>
    </div>

    <script>
        // Auto-submit dopo 1 secondo
        setTimeout(function() {
            document.getElementById('autoLoginForm').submit();
        }, 1000);
    </script>
</body>
</html>

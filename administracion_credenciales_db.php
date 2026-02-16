<?php
/**
 * Credenciales Dixma - Acceso solo Admin
 * 
 * Página para copiar credenciales supervisor/profesor
 * LEE DESDE BASE DE DATOS MySQL
 */

session_start();

// Verifica autenticación: permite admin e tutoria
if (empty($_SESSION)) {
    header("Location: index.php");
    die();
} else if (!isset($_SESSION['rol']) || !in_array($_SESSION['rol'], ['admin', 'tutoria'])) {
    header("Location: inicio.php");
    die();
}

date_default_timezone_set("Europe/Madrid");
setlocale(LC_ALL, "spanish");

// Conexión a base de datos
require_once 'funciones/conexionBD.php';
$pdo = realizarConexion();

// Parámetros búsqueda
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$filterYear = isset($_GET['year']) ? intval($_GET['year']) : date('Y'); // Default: año actual

// Cargar lista años disponibles desde la BD (dinámica)
try {
    $stmtYears = $pdo->query("SELECT DISTINCT YEAR(inicio_curso) AS y FROM credenciales_dixma WHERE inicio_curso IS NOT NULL ORDER BY y DESC");
    $years = $stmtYears->fetchAll(PDO::FETCH_COLUMN);
} catch (Exception $e) {
    $years = [date('Y')];
}
// Assicurati che l'anno corrente sia presente nella lista
if (!in_array((int)date('Y'), array_map('intval', $years))) {
    array_unshift($years, date('Y'));
}

/**
 * Carga credenciales desde base de datos
 */
function loadCredentials($pdo, $searchTerm = '', $year = null) {
    try {
        $sql = "SELECT * FROM credenciales_dixma WHERE activo = 1";
        $params = [];
        
        if (!empty($searchTerm)) {
            $sql .= " AND nombre_curso LIKE :search";
            $params[':search'] = '%' . $searchTerm . '%';
        }
        
        // Filtro per anno
        if ($year !== null && $year > 0) {
            $sql .= " AND YEAR(inicio_curso) = :year";
            $params[':year'] = $year;
        }
        
        $sql .= " ORDER BY numero ASC";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        
        $credentials = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $credentials[] = [
                'id' => $row['id'],
                'numero' => $row['numero'],
                'numero_accion' => $row['numero_accion'],
                'nombre_curso' => $row['nombre_curso'],
                'usuario_supervisor' => $row['usuario_supervisor'],
                'password_supervisor' => $row['password_supervisor'],
                        'usuario_profesor' => $row['usuario_profesor'] ?? '',
                        'password_profesor' => $row['password_profesor'] ?? '',
                        'tutor' => $row['tutor'] ?? '',
                'inicio_curso' => $row['inicio_curso'] ?? '',
                'url_campus' => $row['url_campus'] ?? 'https://dixma.virtual-aula.com/',
                'cv' => $row['cv'] ?? 0,
                'guia' => $row['guia'] ?? 0,
                'revision' => $row['revision'] ?? '',
                'revision_highlighted' => $row['revision_highlighted'] ?? 0
            ];
        }
        
        return $credentials;
    } catch (PDOException $e) {
        error_log("Error cargando credenciales: " . $e->getMessage());
        return [];
    }
}

// Cargar credenciales
$credentials = loadCredentials($pdo, $search, $filterYear);

// Estadísticas
$totalCredentials = count($credentials);
$withProfesor = 0;
foreach ($credentials as $cred) {
    if (!empty($cred['usuario_profesor'])) {
        $withProfesor++;
    }
}

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Credenciales Dixma - Administración</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <style>
        body {
            background-color: #f3f6f4;
            font-family: Arial, sans-serif;
        }

        .page-header {
            background-color: #b0d588;
            color: white;
            padding: 25px;
            margin-bottom: 30px;
            border-radius: 5px;
            border: 5px solid #8fd247;
            text-align: center;
            letter-spacing: 7px;
        }

        .stat-badge {
            display: inline-block;
            background-color: #8fd247;
            color: white;
            padding: 8px 15px;
            margin: 0 5px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: bold;
        }

        .btn-copy {
            font-size: 11px;
            padding: 4px 8px;
            margin: 2px;
        }

        .toast-notification {
            position: fixed;
            top: 20px;
            right: 20px;
            background: #28a745;
            color: white;
            padding: 15px 25px;
            border-radius: 5px;
            display: none;
            z-index: 9999;
            font-weight: bold;
        }
        
        .badge-clickable {
            cursor: pointer;
            transition: transform 0.1s;
        }
        
        .badge-clickable:hover {
            transform: scale(1.1);
            opacity: 0.9;
        }
        
        .quick-select {
            position: absolute;
            background: white;
            border: 2px solid #8fd247;
            border-radius: 5px;
            padding: 5px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
            z-index: 1000;
        }
        
        .quick-select select {
            border: none;
            padding: 5px;
            font-size: 12px;
        }
        
        .color-toggle {
            display: inline-block;
            width: 18px;
            height: 18px;
            border-radius: 3px;
            cursor: pointer;
            margin-left: 5px;
            vertical-align: middle;
            border: 2px solid #fff;
        }
    </style>
</head>
<body>
    <!-- Menu cabecera -->
    <?php 
        $menuaction = 'administracion';
        require_once './template-parts/header/menu_top.php' 
    ?>

    <div class="container-fluid mt-3">
        <div class="row">
            <!-- Menu lateral Admin -->
            <?php include "template-parts/leftmenu/administracion.template.php"; ?>

            <div class="col-md-10 col-12">
                <!-- Header -->
                <div class="page-header">
                    <h1>🔐 CREDENCIALES DIXMA</h1>
                    <small>Gestión de accesos a cursos</small>
                </div>

                <!-- Estadísticas -->
                <div class="text-center mb-4">
                    <span class="stat-badge">� Año: <?php echo $filterYear > 0 ? $filterYear : 'Todos'; ?></span>
                    <span class="stat-badge">📚 Cursos: <?php echo $totalCredentials; ?><?php echo $search ? ' (filtrados)' : ''; ?></span>
                    <span class="stat-badge">👨‍🏫 Con Profesor: <?php echo $withProfesor; ?></span>
                    <div class="text-center mt-3 mb-3">
                        <a href="gestion_credenciales.php" class="btn btn-primary">⚙️ Gestionar Credenciales</a>
                    </div>
                </div>
                

                <!-- Filtros y búsqueda -->
                <div class="mb-3">
                    <form method="get" action="" id="filterForm">
                        <div class="row g-2">
                            <!-- Filtro Año -->
                            <div class="col-md-2">
                                <label class="form-label"><strong>📅 Año:</strong></label>
                                <select name="year" class="form-select" onchange="document.getElementById('filterForm').submit()">
                                    <option value="0" <?php echo $filterYear == 0 ? 'selected' : ''; ?>>Todos</option>
                                    <?php foreach ($years as $y): ?>
                                        <option value="<?php echo (int)$y; ?>" <?php echo $filterYear == (int)$y ? 'selected' : ''; ?>><?php echo htmlspecialchars($y); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            
                            <!-- Búsqueda curso -->
                            <div class="col-md-8">
                                <label class="form-label"><strong>🔍 Buscar curso:</strong></label>
                                <input type="text" 
                                       name="search" 
                                       class="form-control" 
                                       placeholder="Nombre del curso..."
                                       value="<?php echo htmlspecialchars($search); ?>">
                            </div>
                            
                            <!-- Botones -->
                            <div class="col-md-2">
                                <label class="form-label">&nbsp;</label>
                                <button class="btn w-100" style="background-color: #1e989e; color: white;" type="submit">Buscar</button>
                                <?php if ($search || $filterYear != 2026): ?>
                                    <a href="?" class="btn btn-secondary w-100 mt-1" style="font-size: 12px;">Restablecer</a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </form>
                </div>

                <?php if (empty($credentials)): ?>
            
                    <div class="alert alert-warning text-center mt-4" role="alert">
                        <h4>📑 <?php echo ($search || $filterYear > 0) ? 'No se encontraron resultados' : 'No hay credenciales disponibles'; ?></h4>
                        <?php if ($search || $filterYear > 0): ?>
                            <p>Intenta buscar con un término diferente o cambia el filtro de año</p>
                            <a href="?" class="btn" style="background-color: #1e989e; color: white;">Mostrar todas</a>
                        <?php else: ?>
                            <p>No hay credenciales en la base de datos.</p>
                            <p>Usa la página de gestión para agregar nuevas credenciales.</p>
                            <a href="gestion_credenciales.php" class="btn" style="background-color: #1e989e; color: white;">⚙️ Agregar Credenciales</a>
                        <?php endif; ?>
                    </div>

                <?php else: ?>

                    <!-- Tabla credenciales -->
                    <table class="table table-striped table-bordered table-sm text-center align-middle">
                        <thead style="background-color: #8fd247;">
                            <tr>
                                <th style="width: 40px;">N°</th>
                                <th style="width: 60px;">N° Acción</th>
                                <th>Curso</th>
                                <th style="width: 280px;">Supervisor</th>
                                <th style="width: 280px;">Profesor</th>
                                <th style="width: 50px;">CV</th>
                                <th style="width: 50px;">Guía</th>
                                <th style="width: 70px;">Revisión</th>
                                <th style="width: 150px;">Tutor</th>
                                <th style="width: 220px;">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($credentials as $cred): ?>
                                <tr id="row-<?php echo $cred['id']; ?>">
                                    <!-- Número -->
                                    <td>
                                        <span class="badge bg-secondary" style="font-size: 10px;">
                                            <?php echo htmlspecialchars($cred['numero']); ?>
                                        </span>
                                    </td>

                                    <!-- Número Acción -->
                                    <td>
                                        <span class="badge" style="background-color: #6c9a4d; color: white; font-size: 10px;">
                                            <?php echo htmlspecialchars($cred['numero_accion']); ?>
                                        </span>
                                    </td>

                                    <!-- Nombre Curso -->
                                    <td class="text-start">
                                        <strong><?php echo htmlspecialchars($cred['nombre_curso']); ?></strong>
                                        <?php if (!empty($cred['inicio_curso'])): ?>
                                            <br><small class="text-muted">📅 <?php echo date('d/m/Y', strtotime($cred['inicio_curso'])); ?></small>
                                        <?php endif; ?>
                                    </td>

                                    <!-- Supervisor -->
                                    <td>
                                        <div class="mb-1"><small><strong>👤 Usuario:</strong></small><br>
                                        <code style="font-size: 11px;"><?php echo htmlspecialchars($cred['usuario_supervisor']); ?></code></div>
                                        <div><small><strong>🔑 Password:</strong></small><br>
                                        <code style="font-size: 11px;"><?php echo htmlspecialchars($cred['password_supervisor']); ?></code></div>
                                    </td>

                                    <!-- Profesor -->
                                    <td>
                                        <?php if (!empty($cred['usuario_profesor'])): ?>
                                            <div class="mb-1"><small><strong>👤 Usuario:</strong></small><br>
                                            <code style="font-size: 11px;"><?php echo htmlspecialchars($cred['usuario_profesor']); ?></code></div>
                                            <div><small><strong>🔑 Password:</strong></small><br>
                                            <code style="font-size: 11px;"><?php echo htmlspecialchars($cred['password_profesor']); ?></code></div>
                                        <?php else: ?>
                                            <span class="text-muted">-</span>
                                        <?php endif; ?>
                                    </td>

                                    <!-- CV -->
                                    <td>
                                        <span class="badge badge-clickable <?php echo $cred['cv'] == 1 ? 'bg-success' : 'bg-danger'; ?>" 
                                              data-id="<?php echo $cred['id']; ?>"
                                              data-field="cv"
                                              data-value="<?php echo $cred['cv']; ?>"
                                              onclick="toggleCvGuia(this)"
                                              title="Click para cambiar">
                                            <?php echo $cred['cv'] == 1 ? '✅' : '❌'; ?>
                                        </span>
                                    </td>

                                    <!-- Guía -->
                                    <td>
                                        <span class="badge badge-clickable <?php echo $cred['guia'] == 1 ? 'bg-success' : 'bg-danger'; ?>" 
                                              data-id="<?php echo $cred['id']; ?>"
                                              data-field="guia"
                                              data-value="<?php echo $cred['guia']; ?>"
                                              onclick="toggleCvGuia(this)"
                                              title="Click para cambiar">
                                            <?php echo $cred['guia'] == 1 ? '✅' : '❌'; ?>
                                        </span>
                                    </td>

                                    <!-- Revisión -->
                                    <td>
                                        <?php if (!empty($cred['revision'])): ?>
                                            <?php 
                                            $bgColor = '#6c757d'; // Gris por defecto
                                            if (isset($cred['revision_highlighted']) && $cred['revision_highlighted'] == 1) {
                                                if ($cred['revision'] == 'M') {
                                                    $bgColor = '#ff869a'; // Azul para M
                                                } elseif ($cred['revision'] == 'MJ') {
                                                    $bgColor = '#0dcaf0'; // Celeste para MJ
                                                } elseif ($cred['revision'] == 'M/MJ') {
                                                    $bgColor = '#6f42c1'; // Morado para M/MJ
                                                }
                                            }
                                            ?>
                                            <span class="badge badge-clickable" 
                                                  style="background-color: <?php echo $bgColor; ?>; color: white; font-size: 13px; padding: 6px 10px;"
                                                  data-id="<?php echo $cred['id']; ?>"
                                                  data-field="revision"
                                                  data-value="<?php echo $cred['revision']; ?>"
                                                  data-highlighted="<?php echo $cred['revision_highlighted']; ?>"
                                                  onclick="showRevisionMenu(this, event)"
                                                  title="Click para cambiar">
                                                <?php echo htmlspecialchars($cred['revision']); ?>
                                            </span>
                                            <span class="color-toggle" 
                                                  style="background-color: <?php echo $bgColor; ?>;"
                                                  data-id="<?php echo $cred['id']; ?>"
                                                  data-highlighted="<?php echo $cred['revision_highlighted']; ?>"
                                                  onclick="toggleRevisionColor(this)"
                                                  title="Click para cambiar color">🎨</span>
                                        <?php else: ?>
                                            <span class="badge bg-secondary badge-clickable"
                                                  data-id="<?php echo $cred['id']; ?>"
                                                  data-field="revision"
                                                  data-value=""
                                                  onclick="showRevisionMenu(this, event)"
                                                  title="Click para asignar">-</span>
                                        <?php endif; ?>
                                    </td>

                                    <!-- Tutor -->
                                    <td>
                                        <?php echo htmlspecialchars($cred['tutor'] ?? '-'); ?>
                                    </td>

                                    <!-- Botones Acción -->
                                    <td>
                                        <!-- Botones Profesor (si presente) -->
                                        <?php if (!empty($cred['usuario_profesor'])): ?>
                                            <div>
                                                <button class="btn btn-sm btn-primary btn-copy" 
                                                        onclick="copyText('<?php echo htmlspecialchars($cred['usuario_profesor'], ENT_QUOTES); ?>', 'Usuario Profesor')">
                                                    📋 User P
                                                </button>
                                                <button class="btn btn-sm btn-success btn-copy" 
                                                        onclick="copyText('<?php echo htmlspecialchars($cred['password_profesor'], ENT_QUOTES); ?>', 'Contraseña Profesor')">
                                                    🔑 Pass P
                                                </button>
                                                <button class="btn btn-sm btn-copy" style="background-color: #1e989e; color: white;"
                                                        onclick="loginProfesor('<?php echo htmlspecialchars($cred['usuario_profesor'], ENT_QUOTES); ?>', '<?php echo htmlspecialchars($cred['password_profesor'], ENT_QUOTES); ?>')">
                                                    🚀 Login
                                                </button>
                                            </div>
                                        <?php else: ?>
                                            <span class="text-muted">-</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>

                <?php endif; ?>

                
            </div>
        </div>
    </div>

    <footer class="border-top border-secondary" style="background-color:#e4e4e4; height: 75px;">
        <p class="text-center mt-md-4" style='color: #8fd247;'> 
            <b> © Dixma Formación 2022. | Ctra. Madrid 152, Vigo 36318 | info@dixmaformacion.com | Tlf: +34 604 067 035 </b> 
        </p>
    </footer>

    <!-- Toast notifica -->
    <div id="toast" class="toast-notification"></div>

    <!-- Modal confirmación password -->
    <div class="modal fade" id="passwordModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="border: 3px solid #8fd247;">
                <div class="modal-header" style="background-color: #b0d588; border-bottom: 2px solid #8fd247;">
                    <h5 class="modal-title" style="color: white; font-weight: bold;">
                        ✅ Usuario copiado y sitio abierto!
                    </h5>
                </div>
                <div class="modal-body text-center" style="padding: 30px; font-size: 18px;">
                    <p style="margin-bottom: 25px; color: #333;">
                        ¿Quieres copiar la <strong style="color: #1e989e;">CONTRASEÑA</strong> ahora?
                    </p>
                    <div class="d-grid gap-2">
                        <button type="button" 
                                class="btn btn-lg" 
                                style="background-color: #8fd247; color: white; font-weight: bold;"
                                onclick="copyPasswordFromModal()">
                            🔑 SÍ, COPIAR PASSWORD
                        </button>
                        <button type="button" 
                                class="btn btn-lg btn-secondary" 
                                data-bs-dismiss="modal">
                            ❌ NO, GRACIAS
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap.bundle.min.js"></script>
    <script>
        // Mostrar toast
        function showToast(message, type = 'success') {
            const toast = document.getElementById('toast');
            toast.textContent = message;
            toast.style.background = type === 'success' ? '#28a745' : '#dc3545';
            toast.style.display = 'block';
            
            setTimeout(() => {
                toast.style.display = 'none';
            }, 3000);
        }

        // Copiar texto al portapapeles (con fallback)
        function copyText(text, label) {
            // Método 1: Clipboard API (moderno)
            if (navigator.clipboard && navigator.clipboard.writeText) {
                navigator.clipboard.writeText(text)
                    .then(() => {
                        showToast(`✅ ${label} copiado!`);
                    })
                    .catch(() => {
                        fallbackCopy(text, label);
                    });
            } else {
                // Método 2: Fallback (funciona siempre)
                fallbackCopy(text, label);
            }
        }

        // Fallback para navegadores antiguos o sin HTTPS
        function fallbackCopy(text, label) {
            const textarea = document.createElement('textarea');
            textarea.value = text;
            textarea.style.position = 'fixed';
            textarea.style.opacity = '0';
            document.body.appendChild(textarea);
            textarea.select();
            
            try {
                document.execCommand('copy');
                showToast(`✅ ${label} copiado!`);
            } catch (err) {
                showToast(`❌ Error al copiar: ${err}`, 'error');
            }
            
            document.body.removeChild(textarea);
        }

        // Variable global para guardar password temporalmente
        var tempPassword = '';
        
        // Login directo: copiar usuario y abrir sitio
        function loginDirect(username, password) {
            // Guardar password temporalmente
            tempPassword = password;
            
            // Copiar usuario
            copyText(username, 'Usuario');
            
            // Abrir sitio
            window.open('https://dixma.virtual-aula.com/', '_blank');
            
            // Después de 3 segundos mostrar modal
            setTimeout(() => {
                var modal = new bootstrap.Modal(document.getElementById('passwordModal'));
                modal.show();
            }, 3000);
        }
        
        /**
         * Login sicuro tramite token monouso
         */
        function loginProfesor(username, password) {
            // Mostra loading
            showToast('⏳ Generando acceso seguro...');
            
            // Genera token sicuro via AJAX
            fetch('ajax/generate_login_token.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    username: username,
                    password: password
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Apri auto-login in nuova tab
                    window.open(data.url, '_blank');
                    showToast('✅ Acceso generado correctamente');
                } else {
                    console.error('Error generando token:', data.message);
                    showToast('❌ Error: ' + data.message, 'error');
                }
            })
            .catch(error => {
                console.error('Error en loginProfesor:', error);
                showToast('❌ Error de conexión', 'error');
            });
        }
        
        // Copiar password desde el modal
        function copyPasswordFromModal() {
            copyText(tempPassword, 'Contraseña');
            
            // Cerrar modal
            var modal = bootstrap.Modal.getInstance(document.getElementById('passwordModal'));
            modal.hide();
            
            // Limpiar variable temporal
            tempPassword = '';
        }
        
        // ========== MODIFICA RAPIDA INLINE ==========
        
        // Toggle CV/Guía (OK/NO)
        function toggleCvGuia(element) {
            const id = element.getAttribute('data-id');
            const field = element.getAttribute('data-field');
            const currentValue = parseInt(element.getAttribute('data-value'));
            const newValue = currentValue === 1 ? 0 : 1;
            
            // Chiamata AJAX
            fetch('ajax/update_credencial_quick.php', {
                method: 'POST',
                headers: {'Content-Type': 'application/json'},
                body: JSON.stringify({
                    id: id,
                    field: field,
                    value: newValue
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Aggiorna UI
                    element.setAttribute('data-value', newValue);
                    element.className = 'badge badge-clickable ' + (newValue === 1 ? 'bg-success' : 'bg-danger');
                    element.textContent = newValue === 1 ? '✅' : '❌';
                    showToast('✓ Actualizado');
                } else {
                    showToast('Error: ' + data.message, 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('Error de conexión', 'error');
            });
        }
        
        // Mostrar menú Revisión
        function showRevisionMenu(element, event) {
            event.stopPropagation();
            
            // Rimuovi menu esistenti
            const existing = document.querySelector('.quick-select');
            if (existing) existing.remove();
            
            const id = element.getAttribute('data-id');
            const currentValue = element.getAttribute('data-value');
            
            // Crea menu a tendina
            const menu = document.createElement('div');
            menu.className = 'quick-select';
            menu.style.position = 'absolute';
            menu.style.left = event.pageX + 'px';
            menu.style.top = event.pageY + 'px';
            
            const select = document.createElement('select');
            select.className = 'form-select form-select-sm';
            select.innerHTML = `
                <option value="">-- Sin asignar --</option>
                <option value="M" ${currentValue === 'M' ? 'selected' : ''}>M (Maria)</option>
                <option value="MJ" ${currentValue === 'MJ' ? 'selected' : ''}>MJ (María José)</option>
                <option value="M/MJ" ${currentValue === 'M/MJ' ? 'selected' : ''}>M/MJ (Ambas)</option>
            `;
            
            select.addEventListener('change', function() {
                updateRevision(id, this.value, element);
                menu.remove();
            });
            
            menu.appendChild(select);
            document.body.appendChild(menu);
            select.focus();
            
            // Chiudi al click fuori
            setTimeout(() => {
                document.addEventListener('click', function closeMenu(e) {
                    if (!menu.contains(e.target)) {
                        menu.remove();
                        document.removeEventListener('click', closeMenu);
                    }
                });
            }, 100);
        }
        
        // Aggiorna Revisión
        function updateRevision(id, value, badgeElement) {
            fetch('ajax/update_credencial_quick.php', {
                method: 'POST',
                headers: {'Content-Type': 'application/json'},
                body: JSON.stringify({
                    id: id,
                    field: 'revision',
                    value: value
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Aggiorna badge senza reload
                    badgeElement.setAttribute('data-value', value);
                    
                    if (value === '') {
                        // Sin asignar
                        badgeElement.textContent = '-';
                        badgeElement.className = 'badge bg-secondary badge-clickable';
                        // Nascondi icona colore se esiste
                        const colorIcon = badgeElement.nextElementSibling;
                        if (colorIcon && colorIcon.classList.contains('color-toggle')) {
                            colorIcon.style.display = 'none';
                        }
                    } else {
                        // M, MJ o M/MJ
                        badgeElement.textContent = value;
                        badgeElement.className = 'badge badge-clickable';
                        
                        // Aggiorna colore badge (rispetta highlighted)
                        const highlighted = parseInt(badgeElement.getAttribute('data-highlighted') || 0);
                        let bgColor = '#6c757d'; // Grigio default
                        if (highlighted === 1) {
                            if (value === 'M') bgColor = '#ff869a';
                            else if (value === 'MJ') bgColor = '#0dcaf0';
                            else if (value === 'M/MJ') bgColor = '#6f42c1';
                        }
                        badgeElement.style.backgroundColor = bgColor;
                        badgeElement.style.color = 'white';
                        
                        // Mostra/crea icona colore
                        let colorIcon = badgeElement.nextElementSibling;
                        if (!colorIcon || !colorIcon.classList.contains('color-toggle')) {
                            colorIcon = document.createElement('span');
                            colorIcon.className = 'color-toggle';
                            colorIcon.textContent = '🎨';
                            colorIcon.setAttribute('data-id', id);
                            colorIcon.setAttribute('data-highlighted', highlighted);
                            colorIcon.style.backgroundColor = bgColor;
                            colorIcon.onclick = function() { toggleRevisionColor(this); };
                            colorIcon.title = 'Click para cambiar color';
                            badgeElement.parentNode.insertBefore(colorIcon, badgeElement.nextSibling);
                        }
                        colorIcon.style.display = 'inline-block';
                        colorIcon.style.backgroundColor = bgColor;
                    }
                    
                    showToast('✓ Actualizado');
                } else {
                    showToast('Error: ' + data.message, 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('Error de conexión', 'error');
            });
        }
        
        // Toggle colore Revisión
        function toggleRevisionColor(element) {
            element.style.pointerEvents = 'none'; // Previeni doppio click
            
            const id = element.getAttribute('data-id');
            const currentHighlighted = parseInt(element.getAttribute('data-highlighted'));
            const newHighlighted = currentHighlighted === 1 ? 0 : 1;
            
            fetch('ajax/update_credencial_quick.php', {
                method: 'POST',
                headers: {'Content-Type': 'application/json'},
                body: JSON.stringify({
                    id: id,
                    field: 'revision_highlighted',
                    value: newHighlighted
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Aggiorna colore senza reload
                    element.setAttribute('data-highlighted', newHighlighted);
                    
                    const badge = element.previousElementSibling;
                    const revision = badge.getAttribute('data-value');
                    
                    let newColor = '#6c757d'; // Grigio default
                    if (newHighlighted === 1) {
                        if (revision === 'M') newColor = '#ff869a';
                        else if (revision === 'MJ') newColor = '#0dcaf0';
                        else if (revision === 'M/MJ') newColor = '#6f42c1';
                    }
                    
                    badge.style.backgroundColor = newColor;
                    element.style.backgroundColor = newColor;
                    badge.setAttribute('data-highlighted', newHighlighted);
                    
                    showToast('✓ Color actualizado');
                    element.style.pointerEvents = 'auto';
                } else {
                    showToast('Error: ' + data.message, 'error');
                    element.style.pointerEvents = 'auto';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('Error de conexión', 'error');
                element.style.pointerEvents = 'auto';
            });
        }
        
        // Scroll automatico alla riga dopo reload
        window.addEventListener('load', function() {
            if (window.location.hash) {
                setTimeout(() => {
                    const element = document.querySelector(window.location.hash);
                    if (element) {
                        element.scrollIntoView({ behavior: 'smooth', block: 'center' });
                        // Evidenzia temporaneamente la riga
                        element.style.backgroundColor = '#fffacd';
                        setTimeout(() => {
                            element.style.backgroundColor = '';
                        }, 2000);
                    }
                }, 100);
            }
        });
    </script>
</body>
</html>

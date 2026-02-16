<?php
/**
 * Gestión de Credenciales Dixma - CRUD
 * Agregar, Editar, Eliminar credenciales
 */

session_start();

// Verificar que sea admin o tutoria
if (empty($_SESSION) || !isset($_SESSION['rol']) || !in_array($_SESSION['rol'], ['admin', 'tutoria'])) {
    header('Location: index.php');
    exit();
}

require_once 'funciones/conexionBD.php';
$pdo = realizarConexion();

// Filtro año
$filterYear = isset($_GET['year']) ? intval($_GET['year']) : date('Y');

// Cargar lista años disponibles desde la BD (dinámica)
try {
    $stmtYears = $pdo->query("SELECT DISTINCT YEAR(inicio_curso) AS y FROM credenciales_dixma WHERE inicio_curso IS NOT NULL ORDER BY y DESC");
    $years = $stmtYears->fetchAll(PDO::FETCH_COLUMN);
} catch (Exception $e) {
    $years = [date('Y')];
}
if (!in_array((int)date('Y'), array_map('intval', $years))) {
    array_unshift($years, date('Y'));
}

$mensajes = [];
$errores = [];
$editando = null;

// CREAR nueva credencial
if (isset($_POST['crear'])) {
    try {
                $stmt = $pdo->prepare("
            INSERT INTO credenciales_dixma 
            (numero, numero_accion, nombre_curso, usuario_supervisor, password_supervisor, 
             usuario_profesor, password_profesor, inicio_curso, url_campus, cv, guia, revision, revision_highlighted, tutor)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");

        		$stmt->execute([
        		    $_POST['numero'] ?: null,
        		    $_POST['numero_accion'] ?: null,
        		    $_POST['nombre_curso'],
        		    $_POST['usuario_supervisor'],
        		    $_POST['password_supervisor'],
        		    $_POST['usuario_profesor'] ?: null,
        		    $_POST['password_profesor'] ?: null,
        		    $_POST['inicio_curso'] ?: null,
        		    $_POST['url_campus'] ?: 'https://dixma.virtual-aula.com/',
        		    $_POST['cv'] ?? 0,
        		    $_POST['guia'] ?? 0,
        		    $_POST['revision'] ?: null,
        		    isset($_POST['revision_highlighted']) ? 1 : 0,
        		    $_POST['tutor'] ?: null
        		]);
        
        $mensajes[] = "✅ Credencial creada correctamente";
    } catch (PDOException $e) {
        $errores[] = "❌ Error al crear: " . $e->getMessage();
    }
}

// EDITAR credencial
if (isset($_POST['actualizar'])) {
    try {
                $stmt = $pdo->prepare("
            UPDATE credenciales_dixma SET
                numero = ?,
                numero_accion = ?,
                nombre_curso = ?,
                usuario_supervisor = ?,
                password_supervisor = ?,
                usuario_profesor = ?,
                password_profesor = ?,
                inicio_curso = ?,
                url_campus = ?,
                cv = ?,
                guia = ?,
                revision = ?,
                revision_highlighted = ?,
                tutor = ?
            WHERE id = ?
        ");

                $stmt->execute([
                    $_POST['numero'] ?: null,
                    $_POST['numero_accion'] ?: null,
                    $_POST['nombre_curso'],
                    $_POST['usuario_supervisor'],
                    $_POST['password_supervisor'],
                    $_POST['usuario_profesor'] ?: null,
                    $_POST['password_profesor'] ?: null,
                    $_POST['inicio_curso'] ?: null,
                    $_POST['url_campus'] ?: 'https://dixma.virtual-aula.com/',
                    $_POST['cv'] ?? 0,
                    $_POST['guia'] ?? 0,
                    $_POST['revision'] ?: null,
                    isset($_POST['revision_highlighted']) ? 1 : 0,
                    $_POST['tutor'] ?: null,
                    $_POST['id']
                ]);
        
        $mensajes[] = "✅ Credencial actualizada correctamente";
    } catch (PDOException $e) {
        $errores[] = "❌ Error al actualizar: " . $e->getMessage();
    }
}

// ELIMINAR (soft delete)
if (isset($_GET['eliminar'])) {
    try {
        $stmt = $pdo->prepare("UPDATE credenciales_dixma SET activo = 0 WHERE id = ?");
        $stmt->execute([$_GET['eliminar']]);
        $mensajes[] = "✅ Credencial eliminada correctamente";
    } catch (PDOException $e) {
        $errores[] = "❌ Error al eliminar: " . $e->getMessage();
    }
}

// RESTAURAR
if (isset($_GET['restaurar'])) {
    try {
        $stmt = $pdo->prepare("UPDATE credenciales_dixma SET activo = 1 WHERE id = ?");
        $stmt->execute([$_GET['restaurar']]);
        $mensajes[] = "✅ Credencial restaurada correctamente";
    } catch (PDOException $e) {
        $errores[] = "❌ Error al restaurar: " . $e->getMessage();
    }
}

// Cargar credencial para editar
if (isset($_GET['editar'])) {
    $stmt = $pdo->prepare("SELECT * FROM credenciales_dixma WHERE id = ?");
    $stmt->execute([$_GET['editar']]);
    $editando = $stmt->fetch(PDO::FETCH_ASSOC);
}

// Listar credenciales según la vista seleccionada
$mostrar_eliminadas = isset($_GET['ver_eliminadas']);
$sql = "SELECT * FROM credenciales_dixma WHERE activo = " . ($mostrar_eliminadas ? '0' : '1');

// Aplicar filtro año
if ($filterYear > 0) {
    $sql .= " AND YEAR(inicio_curso) = " . $filterYear;
}

$sql .= " ORDER BY numero ASC";
$stmt = $pdo->query($sql);
$credenciales = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Estadísticas con filtro año
$whereYear = $filterYear > 0 ? " AND YEAR(inicio_curso) = " . $filterYear : "";
$total_activos = $pdo->query("SELECT COUNT(*) FROM credenciales_dixma WHERE activo = 1" . $whereYear)->fetchColumn();
$total_eliminados = $pdo->query("SELECT COUNT(*) FROM credenciales_dixma WHERE activo = 0" . $whereYear)->fetchColumn();
$total_general = $total_activos + $total_eliminados;

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestionar Credenciales - Dixma</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <style>
        body {
            background-color: #f3f6f4;
            padding: 20px;
        }
        .header {
            background: linear-gradient(135deg, #b0d588 0%, #8fd247 100%);
            color: white;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 30px;
            text-align: center;
        }
        .form-card {
            background: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            margin-bottom: 30px;
        }
        .table-container {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .btn-custom {
            background-color: #1e989e;
            color: white;
        }
        .btn-custom:hover {
            background-color: #156d72;
            color: white;
        }
        .deleted-row {
            background-color: #ffe0e0;
            text-decoration: line-through;
            opacity: 0.6;
        }
        .nav-tabs .nav-link {
            color: #666;
            font-weight: bold;
        }
        .nav-tabs .nav-link.active {
            background-color: #8fd247;
            color: white;
            border-color: #8fd247;
        }
        .badge-stat {
            font-size: 16px;
            padding: 8px 15px;
            margin: 5px;
        }
        .status-badge {
            font-size: 11px;
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="header">
            <h1>⚙️ Gestión de Credenciales Dixma</h1>
            <p class="mb-0">Agregar, editar o eliminar credenciales de cursos</p>
        </div>

        <!-- Mensajes -->
        <?php if (!empty($mensajes)): ?>
            <?php foreach ($mensajes as $msg): ?>
                <div class="alert alert-success alert-dismissible fade show">
                    <?php echo $msg; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>

        <?php if (!empty($errores)): ?>
            <?php foreach ($errores as $error): ?>
                <div class="alert alert-danger alert-dismissible fade show">
                    <?php echo $error; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>

        <!-- Formulario Crear/Editar -->
        <div class="form-card">
            <h3><?php echo $editando ? '✏️ Editar Credencial' : '➕ Agregar Nueva Credencial'; ?></h3>
            <form method="post">
                <?php if ($editando): ?>
                    <input type="hidden" name="id" value="<?php echo $editando['id']; ?>">
                <?php endif; ?>

                <div class="row">
                    <div class="col-md-2">
                        <label class="form-label">N° Curso</label>
                        <input type="number" class="form-control" name="numero" 
                               value="<?php echo $editando['numero'] ?? ''; ?>">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">N° Acción</label>
                        <input type="number" class="form-control" name="numero_accion" 
                               value="<?php echo $editando['numero_accion'] ?? ''; ?>">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Nombre del Curso *</label>
                        <input type="text" class="form-control" name="nombre_curso" required
                               value="<?php echo htmlspecialchars($editando['nombre_curso'] ?? ''); ?>">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Inicio Curso</label>
                        <input type="date" class="form-control" name="inicio_curso" 
                               value="<?php echo $editando['inicio_curso'] ?? ''; ?>">
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col-md-12">
                        <h5 class="text-primary">👤 Supervisor</h5>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Usuario Supervisor *</label>
                        <input type="text" class="form-control" name="usuario_supervisor" required
                               value="<?php echo htmlspecialchars($editando['usuario_supervisor'] ?? ''); ?>">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Contraseña Supervisor *</label>
                        <input type="text" class="form-control" name="password_supervisor" required
                               value="<?php echo htmlspecialchars($editando['password_supervisor'] ?? ''); ?>">
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col-md-12">
                        <h5 class="text-success">👨‍🏫 Profesor (Opcional)</h5>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Usuario Profesor</label>
                        <input type="text" class="form-control" name="usuario_profesor"
                               value="<?php echo htmlspecialchars($editando['usuario_profesor'] ?? ''); ?>">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Contraseña Profesor</label>
                        <input type="text" class="form-control" name="password_profesor"
                               value="<?php echo htmlspecialchars($editando['password_profesor'] ?? ''); ?>">
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col-md-6">
                        <label class="form-label">Tutor</label>
                        <input type="text" class="form-control" name="tutor"
                               value="<?php echo htmlspecialchars($editando['tutor'] ?? ''); ?>" placeholder="Nombre del tutor (opcional)">
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col-md-12">
                        <label class="form-label">URL Campus</label>
                        <input type="text" class="form-control" name="url_campus" 
                               value="<?php echo htmlspecialchars($editando['url_campus'] ?? 'https://dixma.virtual-aula.com/'); ?>">
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col-md-12">
                        <h5 class="text-info">📋 Control de Documentación</h5>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label"><b>CV:</b></label>
                        <select name="cv" class="form-select" required>
                            <option value="0" <?php echo (isset($editando['cv']) && $editando['cv'] == 0) ? 'selected' : ''; ?>>❌ NO</option>
                            <option value="1" <?php echo (isset($editando['cv']) && $editando['cv'] == 1) ? 'selected' : ''; ?>>✅ OK</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label"><b>Guía:</b></label>
                        <select name="guia" class="form-select" required>
                            <option value="0" <?php echo (isset($editando['guia']) && $editando['guia'] == 0) ? 'selected' : ''; ?>>❌ NO</option>
                            <option value="1" <?php echo (isset($editando['guia']) && $editando['guia'] == 1) ? 'selected' : ''; ?>>✅ OK</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label"><b>Revisión:</b></label>
                        <select name="revision" class="form-select">
                            <option value="" <?php echo empty($editando['revision']) ? 'selected' : ''; ?>>-- Sin asignar --</option>
                            <option value="M" <?php echo (isset($editando['revision']) && $editando['revision'] == 'M') ? 'selected' : ''; ?>>M (Maria)</option>
                            <option value="MJ" <?php echo (isset($editando['revision']) && $editando['revision'] == 'MJ') ? 'selected' : ''; ?>>MJ (María José)</option>
                            <option value="M/MJ" <?php echo (isset($editando['revision']) && $editando['revision'] == 'M/MJ') ? 'selected' : ''; ?>>M/MJ (Ambas)</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label"><b>Evidenciar:</b></label>
                        <div class="form-check mt-2">
                            <input class="form-check-input" type="checkbox" name="revision_highlighted" id="revision_highlighted" 
                                   <?php echo (isset($editando['revision_highlighted']) && $editando['revision_highlighted'] == 1) ? 'checked' : ''; ?>>
                            <label class="form-check-label" for="revision_highlighted">
                                Activar color revisión
                            </label>
                        </div>
                    </div>
                </div>

                <div class="mt-4">
                    <?php if ($editando): ?>
                        <button type="submit" name="actualizar" class="btn btn-warning btn-lg">
                            💾 Actualizar Credencial
                        </button>
                        <a href="?year=<?php echo $filterYear; ?><?php echo $mostrar_eliminadas ? '&ver_eliminadas=1' : ''; ?>" class="btn btn-secondary btn-lg">❌ Cancelar</a>
                    <?php else: ?>
                        <button type="submit" name="crear" class="btn btn-custom btn-lg">
                            ➕ Crear Credencial
                        </button>
                    <?php endif; ?>
                </div>
            </form>
        </div>

        <!-- Navegación -->
        <div class="text-center mb-4">
            <a href="administracion_credenciales_db.php" class="btn btn-custom btn-lg">
                🔐 Ver Credenciales
            </a>
            <a href="administracion.php" class="btn btn-secondary btn-lg">
                ← Volver a Administración
            </a>
        </div>

        <!-- Filtro Año -->
        <div class="mb-3">
            <form method="get" action="" id="filterForm">
                <?php if ($mostrar_eliminadas): ?>
                    <input type="hidden" name="ver_eliminadas" value="1">
                <?php endif; ?>
                <?php if (isset($_GET['editar'])): ?>
                    <input type="hidden" name="editar" value="<?php echo $_GET['editar']; ?>">
                <?php endif; ?>
                <div class="row justify-content-center">
                    <div class="col-md-3">
                        <label class="form-label"><strong>📅 Filtrar por Año:</strong></label>
                        <select name="year" class="form-select" onchange="document.getElementById('filterForm').submit()">
                            <option value="0" <?php echo $filterYear == 0 ? 'selected' : ''; ?>>Todos los años</option>
                            <?php foreach ($years as $y): ?>
                                <option value="<?php echo (int)$y; ?>" <?php echo $filterYear == (int)$y ? 'selected' : ''; ?>><?php echo htmlspecialchars($y); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
            </form>
        </div>

        <!-- Estadísticas Generales -->
        <div class="text-center mb-4">
            <span class="badge badge-stat" style="background-color: #17a2b8;">📅 Año: <?php echo $filterYear > 0 ? $filterYear : 'Todos'; ?></span>
            <span class="badge badge-stat" style="background-color: #8fd247;">✅ Activos: <?php echo $total_activos; ?></span>
            <span class="badge badge-stat" style="background-color: #dc3545;">🗑️ Eliminados: <?php echo $total_eliminados; ?></span>
            <span class="badge badge-stat" style="background-color: #6c757d;">📊 Total: <?php echo $total_general; ?></span>
        </div>
        <!-- Tabla de Credenciales -->
        <div class="table-container">
            <!-- Tabs Activos/Eliminados -->
            <ul class="nav nav-tabs mb-3" role="tablist">
                <li class="nav-item" role="presentation">
                    <a class="nav-link <?php echo !$mostrar_eliminadas ? 'active' : ''; ?>" 
                       href="?year=<?php echo $filterYear; ?>">
                        ✅ Activos (<?php echo $total_activos; ?>)
                    </a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link <?php echo $mostrar_eliminadas ? 'active' : ''; ?>" 
                       href="?ver_eliminadas=1&year=<?php echo $filterYear; ?>">
                        🗑️ Eliminados (<?php echo $total_eliminados; ?>)
                    </a>
                </li>
            </ul>

            <div class="table-responsive">
                <table class="table table-striped table-bordered table-sm">
                    <thead style="background-color: <?php echo $mostrar_eliminadas ? '#dc3545' : '#8fd247'; ?>; color: white;">
                        <tr>
                            <th>ID</th>
                            <th>Estado</th>
                            <th>N°</th>
                            <th>N° Acción</th>
                            <th>Curso</th>
                            <th>Usuario Sup.</th>
                            <th>Pass Sup.</th>
                            <th>Usuario Prof.</th>
                            <th>Pass Prof.</th>
                            <th>Inicio</th>
                            <th>CV</th>
                            <th>Guía</th>
                            <th>Rev.</th>
                            <th>Tutor</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($credenciales as $cred): ?>
                            <tr class="<?php echo $cred['activo'] == 0 ? 'deleted-row' : ''; ?>">
                                <td><?php echo $cred['id']; ?></td>
                                <td>
                                    <?php if ($cred['activo']): ?>
                                        <span class="badge bg-success status-badge">✅ Activo</span>
                                    <?php else: ?>
                                        <span class="badge bg-danger status-badge">🗑️ Eliminado</span>
                                    <?php endif; ?>
                                </td>
                                <td><?php echo $cred['numero']; ?></td>
                                <td>
                                    <span class="badge" style="background-color: #6c9a4d; color: white; font-size: 11px;">
                                        <?php echo $cred['numero_accion']; ?>
                                    </span>
                                </td>
                                <td class="text-start">
                                    <strong><?php echo htmlspecialchars($cred['nombre_curso']); ?></strong>
                                </td>

                                <!-- Usuario Supervisor -->
                                <td><code><?php echo htmlspecialchars($cred['usuario_supervisor']); ?></code></td>

                                <!-- Password Supervisor -->
                                <td><code><?php echo htmlspecialchars($cred['password_supervisor']); ?></code></td>

                                <!-- Usuario Profesor -->
                                <td><?php echo !empty($cred['usuario_profesor']) ? '<code>'.htmlspecialchars($cred['usuario_profesor']).'</code>' : '-'; ?></td>

                                <!-- Password Profesor -->
                                <td><?php echo !empty($cred['password_profesor']) ? '<code>'.htmlspecialchars($cred['password_profesor']).'</code>' : '-'; ?></td>

                                <!-- Inicio (separate column) -->
                                <td>
                                    <?php echo $cred['inicio_curso'] ? date('d/m/Y', strtotime($cred['inicio_curso'])) : '-'; ?>
                                </td>

                                <!-- CV -->
                                <td>
                                    <span class="badge <?php echo $cred['cv'] == 1 ? 'bg-success' : 'bg-danger'; ?>" style="font-size: 11px;">
                                        <?php echo $cred['cv'] == 1 ? '✅' : '❌'; ?>
                                    </span>
                                </td>

                                <!-- Guía -->
                                <td>
                                    <span class="badge <?php echo $cred['guia'] == 1 ? 'bg-success' : 'bg-danger'; ?>" style="font-size: 11px;">
                                        <?php echo $cred['guia'] == 1 ? '✅' : '❌'; ?>
                                    </span>
                                </td>

                                <!-- Revisión -->
                                <td>
                                    <?php if (!empty($cred['revision'])): ?>
                                        <?php 
                                        $bgColor = '#6c757d';
                                        if (isset($cred['revision_highlighted']) && $cred['revision_highlighted'] == 1) {
                                            if ($cred['revision'] == 'M') {
                                                $bgColor = '#ff869a';
                                            } elseif ($cred['revision'] == 'MJ') {
                                                $bgColor = '#0dcaf0';
                                            } elseif ($cred['revision'] == 'M/MJ') {
                                                $bgColor = '#6f42c1';
                                            }
                                        }
                                        ?>
                                        <span class="badge" style="background-color: <?php echo $bgColor; ?>; color: white; font-size: 12px; padding: 4px 8px;">
                                            <?php echo htmlspecialchars($cred['revision']); ?>
                                        </span>
                                    <?php else: ?>
                                        <span class="text-muted" style="font-size: 11px;">-</span>
                                    <?php endif; ?>
                                </td>

                                <!-- Tutor -->
                                <td><?php echo htmlspecialchars($cred['tutor'] ?? '-'); ?></td>

                                <td class="text-nowrap">
                                    <?php 
                                    $urlParams = "year=" . $filterYear;
                                    if ($mostrar_eliminadas) $urlParams .= "&ver_eliminadas=1";
                                    ?>
                                    <?php if ($cred['activo']): ?>
                                        <a href="?editar=<?php echo $cred['id']; ?>&<?php echo $urlParams; ?>" 
                                           class="btn btn-sm btn-warning"
                                           title="Editar credencial">
                                            ✏️ Editar
                                        </a>
                                        <a href="?eliminar=<?php echo $cred['id']; ?>&<?php echo $urlParams; ?>" 
                                           class="btn btn-sm btn-danger"
                                           title="Eliminar (soft delete)"
                                           onclick="return confirm('¿Estás seguro de eliminar esta credencial?\n\nNota: Se puede restaurar después.');">🗑️ Eliminar
                                        </a>
                                    <?php else: ?>
                                        <a href="?restaurar=<?php echo $cred['id']; ?>&<?php echo $urlParams; ?>" 
                                           class="btn btn-sm btn-success"
                                           title="Restaurar credencial"
                                           onclick="return confirm('¿Restaurar esta credencial?');">♻️ Restaurar
                                        </a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        
    </div>

    <script src="js/bootstrap.bundle.min.js"></script>
</body>
</html>

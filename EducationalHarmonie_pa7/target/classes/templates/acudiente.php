<?php
// Habilitar reporte de errores
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Iniciar sesión
session_start();

// Datos del acudiente (simulados - en producción obtener de la base de datos)
$acudiente_id = 'ACU001'; // ID del acudiente logueado
$estudiantes_acudiente = ['S000003', 'S000005', 'S000009', 'S000012']; // IDs de estudiantes asociados

// Inicializar variables
$success = false;
$message = isset($_SESSION['message']) ? $_SESSION['message'] : '';
$message_type = isset($_SESSION['message_type']) ? $_SESSION['message_type'] : '';
unset($_SESSION['message'], $_SESSION['message_type']);

// Conexión a la base de datos
try {
    $pdo = new PDO('mysql:host=localhost;dbname=sistema_educativo', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}

// Obtener lista de docentes
$docentes = [];
try {
    $stmt = $pdo->query("SELECT DISTINCT docente FROM Cursos ORDER BY docente");
    $docentes = $stmt->fetchAll(PDO::FETCH_COLUMN);
} catch (PDOException $e) {
    $message = "Error al cargar docentes: " . $e->getMessage();
    $message_type = 'danger';
}

// Procesar acciones
$action = $_POST['action'] ?? $_GET['action'] ?? '';

switch ($action) {
    case 'save_queja':
        $id_queja = $_POST['id_queja'] ?? '';
        $docente = $_POST['docente'] ?? '';
        $id_estudiante = $_POST['id_estudiante'] ?? '';
        $fecha = $_POST['fecha'] ?? '';
        $descripcion = $_POST['descripcion'] ?? '';
        $detalle = $_POST['detalle'] ?? '';

        if (empty($id_queja) || empty($docente) || empty($id_estudiante) || empty($fecha) || empty($descripcion) || empty($detalle)) {
            $_SESSION['message'] = 'Todos los campos son obligatorios';
            $_SESSION['message_type'] = 'danger';
        } elseif (!in_array($id_estudiante, $estudiantes_acudiente)) {
            $_SESSION['message'] = 'El estudiante no está asociado a su cuenta';
            $_SESSION['message_type'] = 'danger';
        } else {
            try {
                $stmt = $pdo->prepare('INSERT INTO quejas (id_queja, id_estudiante, docente, fecha, descripcion, detalle) VALUES (?, ?, ?, ?, ?, ?)');
                $stmt->execute([$id_queja, $id_estudiante, $docente, $fecha, $descripcion, $detalle]);
                
                $_SESSION['message'] = 'Queja registrada exitosamente';
                $_SESSION['message_type'] = 'success';
                header('Location: '.$_SERVER['PHP_SELF']);
                exit;
            } catch (PDOException $e) {
                $_SESSION['message'] = 'Error al guardar: ' . $e->getMessage();
                $_SESSION['message_type'] = 'danger';
            }
        }
        break;

    case 'get_quejas':
        try {
            $placeholders = implode(',', array_fill(0, count($estudiantes_acudiente), '?'));
            $stmt = $pdo->prepare("SELECT * FROM quejas WHERE id_estudiante IN ($placeholders) ORDER BY fecha DESC");
            $stmt->execute($estudiantes_acudiente);
            header('Content-Type: application/json');
            echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
            exit;
        } catch (PDOException $e) {
            header('Content-Type: application/json');
            echo json_encode(['error' => $e->getMessage()]);
            exit;
        }

    case 'delete_queja':
        $id = $_POST['id'] ?? '';
        if (!empty($id)) {
            try {
                $placeholders = implode(',', array_fill(0, count($estudiantes_acudiente), '?'));
                $stmt = $pdo->prepare("DELETE FROM quejas WHERE id_queja = ? AND id_estudiante IN ($placeholders)");
                $stmt->execute(array_merge([$id], $estudiantes_acudiente));
                echo json_encode(['success' => $stmt->rowCount() > 0]);
                exit;
            } catch (PDOException $e) {
                echo json_encode(['error' => $e->getMessage()]);
                exit;
            }
        }
        break;

    case 'update_queja':
        $id = $_POST['id'] ?? '';
        $descripcion = $_POST['descripcion'] ?? '';
        $detalle = $_POST['detalle'] ?? '';
        
        if (!empty($id)) {
            try {
                $placeholders = implode(',', array_fill(0, count($estudiantes_acudiente), '?'));
                $stmt = $pdo->prepare("UPDATE quejas SET descripcion = ?, detalle = ? WHERE id_queja = ? AND id_estudiante IN ($placeholders)");
                $stmt->execute(array_merge([$descripcion, $detalle, $id], $estudiantes_acudiente));
                echo json_encode(['success' => $stmt->rowCount() > 0]);
                exit;
            } catch (PDOException $e) {
                echo json_encode(['error' => $e->getMessage()]);
                exit;
            }
        }
        break;
}

// Obtener quejas iniciales
try {
    $placeholders = implode(',', array_fill(0, count($estudiantes_acudiente), '?'));
    $stmt = $pdo->prepare("SELECT * FROM quejas WHERE id_estudiante IN ($placeholders) ORDER BY fecha DESC");
    $stmt->execute($estudiantes_acudiente);
    $quejasIniciales = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $quejasIniciales = [];
    $message = 'Error al cargar quejas: ' . $e->getMessage();
    $message_type = 'danger';
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Acudiente - Educational Harmonie</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #C6DFF3;
            color: #143C5F;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
        }
        .header-bar {
            background-color: #143C5F;
            color: white;
            padding: 15px 20px;
            font-size: 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: fixed;
            width: 100%;
            top: 0;
            z-index: 1000;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        .sidebar {
            background-color: white;
            width: 260px;
            padding: 20px;
            height: 100vh;
            position: fixed;
            top: 65px;
            left: 0;
            border-right: 1px solid #2878BD;
        }
        .content-area {
            margin-left: 280px;
            padding: 90px 30px 30px;
            min-height: 100vh;
            background-color: #C6DFF3;
        }
        .form-container, .table-container {
            background-color: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            margin-bottom: 30px;
        }
        .notification-dropdown {
            position: absolute;
            right: 0;
            top: 40px;
            width: 350px;
            background: white;
            border-radius: 5px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.2);
            z-index: 1001;
            display: none;
        }
        .watermark {
            position: absolute;
            top: 55%;
            left: 60%;
            transform: translate(-50%, -50%);
            opacity: 0.2;
            pointer-events: none;
            z-index: 0;
        }
        /* Estilos adicionales... */
    </style>
</head>
<body>

<div class="watermark">
    <img src="../static/img/logo.JPG.jpg" alt="Logo" style="max-width: 80%; height: auto;">
</div>

<div class="header-bar">
    <div>PANEL DE ACUDIENTE</div>
    <div style="display: flex; align-items: center; gap: 15px;">
        <div id="notificationBell" style="position: relative; cursor: pointer; color: white; font-size: 20px;">
            <i class="fas fa-bell"></i>
            <div id="notificationDropdown" style="position: absolute; right: 0; top: 40px; width: 350px; background: white; border-radius: 5px; box-shadow: 0 2px 10px rgba(0,0,0,0.2); z-index: 1001; display: none;">
                <div style="padding: 10px 15px; background: #143C5F; color: white; border-top-left-radius: 5px; border-top-right-radius: 5px;">
                    <span>Notificaciones</span>
                </div>
                <div style="max-height: 400px; overflow-y: auto;">
                    <!-- Notificaciones dinámicas irían aquí -->
                </div>
            </div>
        </div>
        <button onclick="cerrarSesion()" style="color: white; text-decoration: none; font-weight: bold; font-size: 14px; background: none; border: none; cursor: pointer;">
            <i class="fas fa-sign-out-alt"></i> CERRAR SESIÓN
        </button>
    </div>
</div>

<div class="sidebar">
    <img src="../static/img/logo.JPG.jpg" alt="Logo" style="width: 100%; max-height: 100px; object-fit: contain; margin-bottom: 15px;">
    <div style="font-weight: bold; font-size: 16px; color: #2878BD; text-align: center; margin-bottom: 25px;">
        BIENVENIDO ACUDIENTE
    </div>
    <a href="#" onclick="mostrarVista('quejas')" style="color: #143C5F; text-decoration: none; display: flex; align-items: center; gap: 10px; padding: 12px 15px; margin-bottom: 15px; background-color: #E8F1F8; border-radius: 5px; border: 1px solid #B2CDE6;">
        <i class="fas fa-file-alt"></i> Crear Queja
    </a>
    <a href="./acud_form/formulario.php" onclick="" style="color: #143C5F; text-decoration: none; display: flex; align-items: center; gap: 10px; padding: 12px 15px; margin-bottom: 15px; background-color: #E8F1F8; border-radius: 5px; border: 1px solid #B2CDE6;">
        <i class="fas fa-history"></i> Historial de Quejas
    </a>
</div>

<div class="content-area" id="content-area">
    <div id="welcome-message" style="background-color: white; padding: 30px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
        <h2>Bienvenido al Panel de Acudiente</h2>
        <p>Seleccione una opción del menú para comenzar.</p>
    </div>
    
    <div id="quejas" style="display: none;" class="form-container">
        <h2>Formulario de Quejas</h2>
        <?php if (!empty($message)): ?>
            <div class="alert alert-<?= htmlspecialchars($message_type) ?> alert-dismissible fade show">
                <?= htmlspecialchars($message) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>
        <form method="POST" class="mt-4">
            <input type="hidden" name="action" value="save_queja">
            <div class="mb-3">
                <label for="id_queja" class="form-label">ID Queja</label>
                <input type="text" class="form-control" id="id_queja" name="id_queja" required>
            </div>
            <div class="mb-3">
                <label for="docente" class="form-label">Docente</label>
                <select class="form-control" id="docente" name="docente" required>
                    <option value="">Seleccione un docente</option>
                    <?php foreach ($docentes as $docente): ?>
                        <option value="<?= htmlspecialchars($docente) ?>"><?= htmlspecialchars($docente) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="id_estudiante" class="form-label">Estudiante</label>
                <select class="form-control" id="id_estudiante" name="id_estudiante" required>
                    <option value="">Seleccione un estudiante</option>
                    <?php foreach ($estudiantes_acudiente as $estudiante): ?>
                        <option value="<?= htmlspecialchars($estudiante) ?>"><?= htmlspecialchars($estudiante) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="fecha" class="form-label">Fecha</label>
                <input type="date" class="form-control" id="fecha" name="fecha" required>
            </div>
            <div class="mb-3">
                <label for="descripcion" class="form-label">Descripción</label>
                <textarea class="form-control" id="descripcion" name="descripcion" rows="3" required></textarea>
            </div>
            <div class="mb-3">
                <label for="detalle" class="form-label">Detalle</label>
                <textarea class="form-control" id="detalle" name="detalle" rows="5" required></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Guardar Queja</button>
        </form>
    </div>

    <div id="historial" style="display: none;" class="table-container">
        <h2>Historial de Quejas</h2>
        <div class="d-flex justify-content-between mb-3">
            <div style="width: 70%;">
                <input type="text" class="form-control" id="searchInput" placeholder="Buscar...">
            </div>
            <button class="btn btn-primary" onclick="loadQuejas()">
                <i class="fas fa-sync-alt"></i> Actualizar
            </button>
        </div>
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>ID Queja</th>
                        <th>Docente</th>
                        <th>Estudiante</th>
                        <th>Fecha</th>
                        <th>Descripción</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody id="tablaQuejas">
                    <?php foreach ($quejasIniciales as $queja): ?>
                    <tr>
                        <td><?= htmlspecialchars($queja['id_queja']) ?></td>
                        <td><?= htmlspecialchars($queja['docente'] ?? 'N/A') ?></td>
                        <td><?= htmlspecialchars($queja['id_estudiante']) ?></td>
                        <td><?= htmlspecialchars($queja['fecha']) ?></td>
                        <td><?= htmlspecialchars(substr($queja['descripcion'], 0, 50)) ?>...</td>
                        <td>
                            <button class="btn btn-sm btn-info" onclick="showEditModal('<?= htmlspecialchars($queja['id_queja']) ?>', `<?= htmlspecialchars($queja['descripcion']) ?>`, `<?= htmlspecialchars($queja['detalle']) ?>`)">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn btn-sm btn-danger" onclick="deleteQueja('<?= htmlspecialchars($queja['id_queja']) ?>')">
                                <i class="fas fa-trash"></i>
                            </button>
                            <button class="btn btn-sm btn-secondary" onclick="showDetails('<?= htmlspecialchars($queja['id_queja']) ?>')">
                                <i class="fas fa-eye"></i>
                            </button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Editar -->
<div class="modal fade" id="editModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Editar Queja</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="editId">
                <div class="mb-3">
                    <label class="form-label">Descripción</label>
                    <textarea class="form-control" id="editDescripcion" rows="3" required></textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label">Detalle</label>
                    <textarea class="form-control" id="editDetalle" rows="5" required></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" onclick="saveEdit()">Guardar Cambios</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Detalles -->
<div class="modal fade" id="detailsModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detalles de Queja</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row mb-3">
                    <div class="col-md-4">
                        <strong>ID Queja:</strong> <span id="detailId"></span>
                    </div>
                    <div class="col-md-4">
                        <strong>Docente:</strong> <span id="detailDocente"></span>
                    </div>
                    <div class="col-md-4">
                        <strong>Estudiante:</strong> <span id="detailStudent"></span>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <strong>Fecha:</strong> <span id="detailDate"></span>
                    </div>
                </div>
                <div class="mb-3">
                    <strong>Descripción:</strong>
                    <p id="detailDescription" class="mt-2"></p>
                </div>
                <div class="mb-3">
                    <strong>Detalle:</strong>
                    <p id="detailDetail" class="mt-2"></p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
// Variables
const estudiantesAcudiente = <?= json_encode($estudiantes_acudiente) ?>;

// Mostrar vista por defecto
document.getElementById('quejas').style.display = 'none';
document.getElementById('historial').style.display = 'none';

// Funciones
function mostrarVista(vistaId) {
    ['quejas', 'historial', 'welcome-message'].forEach(id => {
        document.getElementById(id).style.display = 'none';
    });
    document.getElementById(vistaId).style.display = 'block';
    
    if (vistaId === 'quejas') {
        document.getElementById('fecha').value = new Date().toISOString().split('T')[0];
    } else if (vistaId === 'historial') {
        loadQuejas();
    }
}

function loadQuejas() {
    $.ajax({
        url: window.location.href,
        type: 'GET',
        data: { action: 'get_quejas' },
        dataType: 'json',
        success: renderQuejas,
        error: (xhr, status, error) => console.error('Error:', error)
    });
}

function renderQuejas(quejas) {
    const tbody = $('#tablaQuejas').empty();
    
    if (quejas.length === 0) {
        tbody.append('<tr><td colspan="6" class="text-center">No hay quejas registradas</td></tr>');
        return;
    }
    
    quejas.forEach(queja => {
        tbody.append(`
            <tr>
                <td>${queja.id_queja}</td>
                <td>${queja.docente || 'N/A'}</td>
                <td>${queja.id_estudiante}</td>
                <td>${queja.fecha}</td>
                <td>${queja.descripcion.substring(0, 50)}${queja.descripcion.length > 50 ? '...' : ''}</td>
                <td>
                    <button class="btn btn-sm btn-info" onclick="showEditModal('${queja.id_queja}', ${JSON.stringify(queja.descripcion)}, ${JSON.stringify(queja.detalle)})">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button class="btn btn-sm btn-danger" onclick="deleteQueja('${queja.id_queja}')">
                        <i class="fas fa-trash"></i>
                    </button>
                    <button class="btn btn-sm btn-secondary" onclick="showDetails('${queja.id_queja}')">
                        <i class="fas fa-eye"></i>
                    </button>
                </td>
            </tr>
        `);
    });
}

function showEditModal(id, descripcion, detalle) {
    $('#editId').val(id);
    $('#editDescripcion').val(descripcion);
    $('#editDetalle').val(detalle);
    new bootstrap.Modal(document.getElementById('editModal')).show();
}

function saveEdit() {
    const id = $('#editId').val();
    const descripcion = $('#editDescripcion').val();
    const detalle = $('#editDetalle').val();
    
    if (!descripcion || !detalle) {
        alert('Todos los campos son obligatorios');
        return;
    }
    
    $.ajax({
        url: window.location.href,
        type: 'POST',
        data: { action: 'update_queja', id, descripcion, detalle },
        dataType: 'json',
        success: (response) => {
            if (response.success) {
                $('#editModal').modal('hide');
                loadQuejas();
            } else {
                alert(response.error || 'Error desconocido');
            }
        },
        error: (xhr, status, error) => alert('Error: ' + error)
    });
}

function deleteQueja(id) {
    if (!confirm('¿Eliminar esta queja?')) return;
    
    $.ajax({
        url: window.location.href,
        type: 'POST',
        data: { action: 'delete_queja', id },
        dataType: 'json',
        success: (response) => {
            if (response.success) loadQuejas();
            else alert(response.error || 'Error desconocido');
        },
        error: (xhr, status, error) => alert('Error: ' + error)
    });
}

function showDetails(id) {
    $.ajax({
        url: window.location.href,
        type: 'GET',
        data: { action: 'get_queja', id },
        dataType: 'json',
        success: (queja) => {
            $('#detailId').text(queja.id_queja);
            $('#detailDocente').text(queja.docente || 'N/A');
            $('#detailStudent').text(queja.id_estudiante);
            $('#detailDate').text(queja.fecha);
            $('#detailDescription').text(queja.descripcion);
            $('#detailDetail').text(queja.detalle);
            new bootstrap.Modal(document.getElementById('detailsModal')).show();
        },
        error: (xhr, status, error) => alert('Error: ' + error)
    });
}

function cerrarSesion() {
    if (confirm('¿Cerrar sesión?')) {
        window.location.href = 'logout.php';
    }
}

// Eventos
document.getElementById('notificationBell').addEventListener('click', function(e) {
    e.stopPropagation();
    document.getElementById('notificationDropdown').style.display = 
        document.getElementById('notificationDropdown').style.display === 'block' ? 'none' : 'block';
});

document.addEventListener('click', () => {
    document.getElementById('notificationDropdown').style.display = 'none';
});

$('#searchInput').on('keyup', function() {
    const value = $(this).val().toLowerCase();
    $('#tablaQuejas tr').filter(function() {
        $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
    });
});

// Inicialización
$(document).ready(function() {
    $('#fecha').val(new Date().toISOString().split('T')[0]);
    setTimeout(() => $('.alert').alert('close'), 5000);
});
</script>
</body>
</html>
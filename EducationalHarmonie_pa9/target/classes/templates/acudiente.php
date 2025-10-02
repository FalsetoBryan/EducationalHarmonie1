<?php
// Habilitar reporte de errores para depuración
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Inicializar variables para el mensaje y datos
$success = false;
$message = '';
$data = [];

// Conexión a la base de datos
try {
    $pdo = new PDO('mysql:host=localhost;dbname=sistema_educativo', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}

// Verificar si la tabla quejas existe, si no, crearla
try {
    $pdo->exec("CREATE TABLE IF NOT EXISTS quejas (
        id_queja VARCHAR(20) PRIMARY KEY,
        id_estudiante VARCHAR(20) NOT NULL,
        fecha DATE NOT NULL,
        descripcion TEXT NOT NULL,
        detalle TEXT NOT NULL,
        fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )");
} catch (PDOException $e) {
    die("Error al crear tabla: " . $e->getMessage());
}

// Procesar diferentes acciones CRUD
$action = $_POST['action'] ?? $_GET['action'] ?? '';

switch ($action) {
    case 'save_queja':
        // Obtener datos del formulario
        $id_queja = $_POST['id_queja'] ?? '';
        $id_estudiante = $_POST['id_estudiante'] ?? '';
        $fecha = $_POST['fecha'] ?? '';
        $descripcion = $_POST['descripcion'] ?? '';
        $detalle = $_POST['detalle'] ?? '';

        // Validar datos
        if (empty($id_queja) || empty($id_estudiante) || empty($fecha) || empty($descripcion) || empty($detalle)) {
            $message = 'Todos los campos son obligatorios';
        } elseif (!DateTime::createFromFormat('Y-m-d', $fecha)) {
            $message = 'Formato de fecha inválido (use YYYY-MM-DD)';
        } else {
            try {
                // Insertar en la base de datos
                $stmt = $pdo->prepare('INSERT INTO quejas (id_queja, id_estudiante, fecha, descripcion, detalle) VALUES (?, ?, ?, ?, ?)');
                $stmt->execute([$id_queja, $id_estudiante, $fecha, $descripcion, $detalle]);

                $success = true;
                $data = [
                    'id_queja' => $id_queja,
                    'id_estudiante' => $id_estudiante,
                    'fecha' => $fecha,
                    'descripcion' => $descripcion,
                    'detalle' => $detalle
                ];
            } catch (PDOException $e) {
                $message = 'Error en la base de datos: ' . $e->getMessage();
            }
        }
        break;

    case 'get_quejas':
        // Obtener todas las quejas para el historial
        try {
            $stmt = $pdo->query('SELECT * FROM quejas ORDER BY fecha DESC');
            $quejas = $stmt->fetchAll(PDO::FETCH_ASSOC);
            header('Content-Type: application/json');
            echo json_encode($quejas);
            exit;
        } catch (PDOException $e) {
            header('Content-Type: application/json');
            echo json_encode(['error' => $e->getMessage()]);
            exit;
        }
        break;

    case 'delete_queja':
        // Eliminar una queja
        $id = $_POST['id'] ?? '';
        if (!empty($id)) {
            try {
                $stmt = $pdo->prepare('DELETE FROM quejas WHERE id_queja = ?');
                $stmt->execute([$id]);
                echo json_encode(['success' => true]);
                exit;
            } catch (PDOException $e) {
                echo json_encode(['error' => $e->getMessage()]);
                exit;
            }
        }
        break;

    case 'update_queja':
        // Actualizar una queja
        $id = $_POST['id'] ?? '';
        $descripcion = $_POST['descripcion'] ?? '';
        $detalle = $_POST['detalle'] ?? '';
        
        if (!empty($id)) {
            try {
                $stmt = $pdo->prepare('UPDATE quejas SET descripcion = ?, detalle = ? WHERE id_queja = ?');
                $stmt->execute([$descripcion, $detalle, $id]);
                echo json_encode(['success' => true]);
                exit;
            } catch (PDOException $e) {
                echo json_encode(['error' => $e->getMessage()]);
                exit;
            }
        }
        break;
}

// Obtener quejas para mostrar inicialmente (sin AJAX)
try {
    $stmt = $pdo->query('SELECT * FROM quejas ORDER BY fecha DESC');
    $quejasIniciales = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $quejasIniciales = [];
    $message = 'Error al cargar quejas iniciales: ' . $e->getMessage();
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
            background-color: #f8f9fa;
            font-family: Arial, sans-serif;
        }
        .header-bar {
            background-color: #343a40;
            color: white;
            padding: 15px;
            margin-bottom: 20px;
        }
        .sidebar {
            background-color: #e9ecef;
            padding: 20px;
            height: 100vh;
        }
        .menu-item {
            padding: 10px;
            margin-bottom: 10px;
            cursor: pointer;
            border-radius: 5px;
        }
        .menu-item:hover {
            background-color: #d1d7dc;
        }
        .content-area {
            padding: 20px;
        }
        .table-responsive {
            margin-top: 20px;
        }
        .action-btns .btn {
            margin-right: 5px;
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3 col-lg-2 d-md-block sidebar">
                <div class="text-center mb-4">
                    <h4>Menú</h4>
                </div>
                <div class="menu-item" onclick="mostrarVista('quejas')">
                    <i class="fas fa-file-alt"></i> Crear Queja
                </div>
                <div class="menu-item" onclick="mostrarVista('historial')">
                    <i class="fas fa-history"></i> Historial de Quejas
                </div>
                <div class="menu-item" onclick="mostrarVista('notificaciones')">
                    <i class="fas fa-bell"></i> Notificaciones
                </div>
                <div class="menu-item" onclick="mostrarVista('calificar')">
                    <i class="fas fa-star"></i> Calificar el Sistema
                </div>
            </div>

            <!-- Main Content -->
            <div class="col-md-9 col-lg-10 ml-sm-auto content-area">
                <!-- Formulario de Quejas -->
                <div id="quejas" style="display: none;">
                    <h2>Formulario de Quejas</h2>
                    <?php if ($message && $_SERVER['REQUEST_METHOD'] === 'POST'): ?>
                        <div class="alert alert-<?= $success ? 'success' : 'danger' ?>"><?= htmlspecialchars($message) ?></div>
                    <?php endif; ?>
                    <form method="POST" class="mt-4">
                        <input type="hidden" name="action" value="save_queja">
                        <div class="mb-3">
                            <label for="id_queja" class="form-label">ID Queja</label>
                            <input type="text" class="form-control" id="id_queja" name="id_queja" required>
                        </div>
                        <div class="mb-3">
                            <label for="id_estudiante" class="form-label">ID Estudiante</label>
                            <input type="text" class="form-control" id="id_estudiante" name="id_estudiante" required>
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

                <!-- Historial de Quejas -->
                <div id="historial">
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
                                    <th>ID Estudiante</th>
                                    <th>Fecha</th>
                                    <th>Descripción</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody id="tablaQuejas">
                                <?php foreach ($quejasIniciales as $queja): ?>
                                <tr>
                                    <td><?= htmlspecialchars($queja['id_queja']) ?></td>
                                    <td><?= htmlspecialchars($queja['id_estudiante']) ?></td>
                                    <td><?= htmlspecialchars($queja['fecha']) ?></td>
                                    <td><?= htmlspecialchars(substr($queja['descripcion'], 0, 50)) ?>...</td>
                                    <td class="action-btns">
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

                <!-- Otras secciones -->
                <div id="notificaciones" style="display: none;">
                    <h2>Notificaciones</h2>
                    <p>No hay notificaciones nuevas.</p>
                </div>

                <div id="calificar" style="display: none;">
                    <h2>Calificar el Sistema</h2>
                    <form>
                        <div class="mb-3">
                            <label class="form-label">Calificación (1-5)</label>
                            <input type="number" class="form-control" min="1" max="5">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Comentarios</label>
                            <textarea class="form-control" rows="3"></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Enviar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para Editar -->
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

    <!-- Modal para Detalles -->
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
                            <strong>ID Estudiante:</strong> <span id="detailStudent"></span>
                        </div>
                        <div class="col-md-4">
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
        // Mostrar vista por defecto
        document.getElementById('historial').style.display = 'block';
        
        // Función para cambiar vistas
        function mostrarVista(vistaId) {
            ['quejas', 'historial', 'notificaciones', 'calificar'].forEach(id => {
                document.getElementById(id).style.display = 'none';
            });
            document.getElementById(vistaId).style.display = 'block';
            
            if (vistaId === 'historial') {
                loadQuejas();
            } else if (vistaId === 'quejas') {
                // Establecer fecha actual por defecto
                document.getElementById('fecha').value = new Date().toISOString().split('T')[0];
            }
        }
        
        // Cargar quejas via AJAX
        function loadQuejas() {
            $.ajax({
                url: window.location.href,
                type: 'GET',
                data: { action: 'get_quejas' },
                dataType: 'json',
                success: function(response) {
                    if (Array.isArray(response)) {
                        renderQuejas(response);
                    } else {
                        console.error('Error:', response);
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error al cargar quejas:', error);
                }
            });
        }
        
        // Renderizar quejas en la tabla
        function renderQuejas(quejas) {
            const tbody = $('#tablaQuejas');
            tbody.empty();
            
            if (quejas.length === 0) {
                tbody.append('<tr><td colspan="5" class="text-center">No hay quejas registradas</td></tr>');
                return;
            }
            
            quejas.forEach(queja => {
                const tr = $(`
                    <tr>
                        <td>${queja.id_queja}</td>
                        <td>${queja.id_estudiante}</td>
                        <td>${queja.fecha}</td>
                        <td>${queja.descripcion.substring(0, 50)}${queja.descripcion.length > 50 ? '...' : ''}</td>
                        <td class="action-btns">
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
                tbody.append(tr);
            });
        }
        
        // Mostrar modal de edición
        function showEditModal(id, descripcion, detalle) {
            $('#editId').val(id);
            $('#editDescripcion').val(descripcion);
            $('#editDetalle').val(detalle);
            
            new bootstrap.Modal(document.getElementById('editModal')).show();
        }
        
        // Guardar cambios de edición
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
                data: {
                    action: 'update_queja',
                    id: id,
                    descripcion: descripcion,
                    detalle: detalle
                },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        $('#editModal').modal('hide');
                        loadQuejas();
                    } else {
                        alert('Error: ' + (response.error || 'Error desconocido'));
                    }
                },
                error: function(xhr, status, error) {
                    alert('Error al actualizar: ' + error);
                }
            });
        }
        
        // Eliminar queja
        function deleteQueja(id) {
            if (!confirm('¿Está seguro de eliminar esta queja?')) return;
            
            $.ajax({
                url: window.location.href,
                type: 'POST',
                data: { action: 'delete_queja', id: id },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        loadQuejas();
                    } else {
                        alert('Error: ' + (response.error || 'Error desconocido'));
                    }
                },
                error: function(xhr, status, error) {
                    alert('Error al eliminar: ' + error);
                }
            });
        }
        
        // Mostrar detalles
        function showDetails(id) {
            // En un sistema real, haríamos una petición AJAX para obtener los detalles completos
            // Por ahora, mostramos un modal con la información básica
            $('#detailId').text(id);
            
            // Buscar la queja en los datos ya cargados
            const queja = $('#tablaQuejas tr').filter(function() {
                return $(this).find('td:first').text() === id;
            });
            
            if (queja.length) {
                $('#detailStudent').text(queja.find('td:nth-child(2)').text());
                $('#detailDate').text(queja.find('td:nth-child(3)').text());
                $('#detailDescription').text(queja.find('td:nth-child(4)').text());
            }
            
            new bootstrap.Modal(document.getElementById('detailsModal')).show();
        }
        
        // Búsqueda en tiempo real
        $('#searchInput').on('keyup', function() {
            const value = $(this).val().toLowerCase();
            $('#tablaQuejas tr').filter(function() {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
            });
        });
        
        // Inicializar
        $(document).ready(function() {
            // Establecer fecha actual por defecto en el formulario
            $('#fecha').val(new Date().toISOString().split('T')[0]);
        });
    </script>
</body>
</html>
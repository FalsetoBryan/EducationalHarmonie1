<?php
// Iniciar sesión
session_start();

// Conexión a la base de datos
$conexion = mysqli_connect("localhost", "root", "", "sistema_educativo");

// Verificar la conexión
if ($conexion->connect_error) {
    die("Conexión fallida: " . $conexion->connect_error);
}

// Variables para mostrar
$mensaje = "";
$historial = [];
$prediccion = "";
$porcentaje_reincidencia = 0;

// Inicializar historial de predicciones en sesión si no existe
if (!isset($_SESSION['historial_predicciones'])) {
    $_SESSION['historial_predicciones'] = [];
}

// Procesar formulario
if ($_SERVER["REQUEST_METHOD"] == "POST" && !isset($_POST['mostrar_historial'])) {
    // Verificar que los datos del formulario están definidos
    if (isset($_POST['id_estudiante']) && isset($_POST['tipo_falta']) && 
        isset($_POST['frecuencia_faltas']) && isset($_POST['id_falta'])) {
        
        // Sanitizar entradas
        $id_estudiante = mysqli_real_escape_string($conexion, $_POST['id_estudiante']);
        $tipo_falta = mysqli_real_escape_string($conexion, $_POST['tipo_falta']);
        $frecuencia_faltas = intval($_POST['frecuencia_faltas']);
        $id_falta = mysqli_real_escape_string($conexion, $_POST['id_falta']);

        // Consulta para obtener reincidencias
        $consulta = "SELECT id_falta, id_estudiante, tipo_falta, frecuencia_faltas, reincidencia 
                     FROM faltas 
                     WHERE id_estudiante = '$id_estudiante' AND tipo_falta = '$tipo_falta'";

        $resultado = $conexion->query($consulta);

        if ($resultado && $resultado->num_rows > 0) {
            $total_reincidencias = 0;
            $total_faltas = 0;
            $total_frecuencia = 0;

            // Procesar resultados
            while ($row = $resultado->fetch_assoc()) {
                if (strtolower($row['reincidencia']) == 'sí') {
                    $total_reincidencias++;
                }
                $total_faltas++;
                $total_frecuencia += $row['frecuencia_faltas'];
                $historial[] = $row;
            }

            // Generar predicción
            if ($total_reincidencias > 5 && $total_frecuencia > 10) {
                $prediccion = "Alta probabilidad de reincidencia.";
                $porcentaje_reincidencia = 90;
            } elseif ($total_reincidencias > 3 && $total_frecuencia > 7) {
                $prediccion = "Alta probabilidad de reincidencia debido a reincidencias anteriores y alta frecuencia de faltas.";
                $porcentaje_reincidencia = 80;
            } elseif ($total_frecuencia > 5) {
                $prediccion = "Moderada probabilidad de reincidencia debido a alta frecuencia de faltas.";
                $porcentaje_reincidencia = 70;
            } elseif ($total_reincidencias > 3) {
                $prediccion = "Moderada probabilidad de reincidencia debido a reincidencias anteriores.";
                $porcentaje_reincidencia = 60;
            } elseif ($total_frecuencia > 2) {
                $prediccion = "Baja probabilidad de reincidencia, pero hay una frecuencia considerable de faltas.";
                $porcentaje_reincidencia = 50;
            } else {
                $prediccion = "Baja probabilidad de reincidencia.";
                $porcentaje_reincidencia = 30;
            }

            $mensaje = "El estudiante ha reincidido <strong>$total_reincidencias</strong> veces en faltas de tipo <strong>$tipo_falta</strong>.";

            // Guardar en historial de sesión
            $_SESSION['historial_predicciones'][] = [
                'id_estudiante' => $id_estudiante,
                'tipo_falta' => $tipo_falta,
                'prediccion' => $prediccion,
                'porcentaje_reincidencia' => $porcentaje_reincidencia,
                'fecha' => date("Y-m-d H:i:s")
            ];
        } else {
            $mensaje = "No hay reincidencias registradas para este estudiante en faltas de tipo <strong>$tipo_falta</strong>.";
        }
    }
}

// Obtener lista de estudiantes
$estudiantes = $conexion->query("SELECT id_estudiante, nombre FROM estudiantes");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema Educativo - Consulta de Reincidencias</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            position: relative;
        }
        .card {
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        .card-header {
            font-weight: 600;
            letter-spacing: 1px;
            background: linear-gradient(135deg, #007bff, #0056b3);
        }
        .table-container {
            max-height: 400px;
            overflow-y: auto;
            margin-bottom: 20px;
        }
        .prediction-card {
            border-left: 5px solid #ffc107;
            background-color: #fff9e6;
        }
        .history-card {
            border-left: 5px solid #0dcaf0;
            background-color: #e6f7ff;
        }
        .progress {
            height: 25px;
            border-radius: 12px;
        }
        .progress-bar {
            font-size: 14px;
            font-weight: bold;
        }
        
        /* Estilos para la marca de agua */
        .watermark {
            position: fixed;
            bottom: 20px;
            right: 20px;
            opacity: 0.15;
            z-index: 9999;
            transform: rotate(-15deg);
            font-size: 24px;
            font-weight: bold;
            color: #007bff;
            text-shadow: 1px 1px 2px rgba(0,0,0,0.3);
            white-space: nowrap;
            pointer-events: none;
            font-family: Arial, sans-serif;
        }
        
        .watermark::after {
            content: "iEDUCAR PARA CRECER!";
            display: block;
            font-size: 16px;
            text-align: center;
            margin-top: 5px;
            font-style: italic;
        }
        
        body::after {
            content: "";
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="400" height="200" viewBox="0 0 400 200"><text x="50%" y="30%" font-family="Arial" font-weight="bold" font-size="30" fill="%23007bff" opacity="0.08" text-anchor="middle" dominant-baseline="middle">EDUCATIONAL HARMONIE</text><text x="50%" y="50%" font-family="Arial" font-size="24" fill="%23007bff" opacity="0.08" text-anchor="middle" dominant-baseline="middle">iEDUCAR PARA CRECER!</text></svg>');
            background-repeat: repeat;
            z-index: -1;
            pointer-events: none;
        }
    </style>
</head>
<body>
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <!-- Tarjeta principal -->
                <div class="card shadow-lg mb-4">
                    <div class="card-header text-white py-3">
                        <h4 class="mb-0 text-center"><i class="bi bi-search me-2"></i>Consulta de Reincidencias</h4>
                    </div>
                    <div class="card-body p-4">
                        <!-- Formulario de consulta -->
                        <form method="POST" action="" class="needs-validation" novalidate>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="id_estudiante" class="form-label">Estudiante:</label>
                                    <select name="id_estudiante" class="form-select" required>
                                        <option value="" selected disabled>Seleccione un estudiante</option>
                                        <?php while($row = $estudiantes->fetch_assoc()): ?>
                                            <option value="<?= htmlspecialchars($row['id_estudiante']) ?>">
                                                <?= htmlspecialchars($row['nombre']) ?> (ID: <?= htmlspecialchars($row['id_estudiante']) ?>)
                                            </option>
                                        <?php endwhile; ?>
                                    </select>
                                    <div class="invalid-feedback">
                                        Por favor seleccione un estudiante.
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <label for="tipo_falta" class="form-label">Tipo de Falta:</label>
                                    <select name="tipo_falta" class="form-select" required>
                                        <option value="" selected disabled>Seleccione tipo de falta</option>
                                        <option value="Leve">Leve</option>
                                        <option value="Grave">Grave</option>
                                        <option value="Moderada">Moderada</option>
                                    </select>
                                    <div class="invalid-feedback">
                                        Por favor seleccione un tipo de falta.
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <label for="frecuencia_faltas" class="form-label">Frecuencia de Faltas:</label>
                                    <input type="number" name="frecuencia_faltas" class="form-control" min="1" required>
                                    <div class="invalid-feedback">
                                        Por favor ingrese la frecuencia de faltas.
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <label for="id_falta" class="form-label">ID de Falta:</label>
                                    <input type="text" name="id_falta" class="form-control" required>
                                    <div class="invalid-feedback">
                                        Por favor ingrese el ID de la falta.
                                    </div>
                                </div>
                                
                                <div class="col-12 mt-2">
                                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                        <button type="reset" class="btn btn-outline-secondary me-md-2">
                                            <i class="bi bi-eraser"></i> Limpiar
                                        </button>
                                        <button type="submit" class="btn btn-primary">
                                            <i class="bi bi-search"></i> Consultar Reincidencias
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                        
                        <!-- Mensajes de resultado -->
                        <?php if (!empty($mensaje)): ?>
                            <div class="alert alert-info mt-4" role="alert">
                                <i class="bi bi-info-circle-fill me-2"></i><?= $mensaje ?>
                            </div>
                        <?php endif; ?>
                        
                        <!-- Historial detallado -->
                        <?php if (!empty($historial)): ?>
                            <div class="mt-4">
                                <h5 class="mb-3"><i class="bi bi-list-ul me-2"></i>Historial Detallado</h5>
                                <div class="table-container">
                                    <table class="table table-striped table-hover table-bordered">
                                        <thead class="table-light">
                                            <tr>
                                                <th>ID Falta</th>
                                                <th>ID Estudiante</th>
                                                <th>Tipo de Falta</th>
                                                <th>Frecuencia</th>
                                                <th>Reincidencia</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach($historial as $item): ?>
                                                <tr>
                                                    <td><?= htmlspecialchars($item['id_falta']) ?></td>
                                                    <td><?= htmlspecialchars($item['id_estudiante']) ?></td>
                                                    <td><?= htmlspecialchars($item['tipo_falta']) ?></td>
                                                    <td><?= htmlspecialchars($item['frecuencia_faltas']) ?></td>
                                                    <td><?= htmlspecialchars($item['reincidencia']) ?></td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        <?php endif; ?>
                        
                        <!-- Predicción -->
                        <?php if (!empty($prediccion)): ?>
                            <div class="card prediction-card mt-4">
                                <div class="card-body">
                                    <h5 class="card-title"><i class="bi bi-graph-up me-2"></i>Predicción de Reincidencia</h5>
                                    <div class="progress mb-3">
                                        <div class="progress-bar progress-bar-striped progress-bar-animated" 
                                             role="progressbar" 
                                             style="width: <?= $porcentaje_reincidencia ?>%;
                                                    background-color: <?= 
                                                        $porcentaje_reincidencia > 70 ? '#dc3545' : 
                                                        ($porcentaje_reincidencia > 50 ? '#ffc107' : '#28a745')
                                                    ?>;" 
                                             aria-valuenow="<?= $porcentaje_reincidencia ?>" 
                                             aria-valuemin="0" 
                                             aria-valuemax="100">
                                            <?= $porcentaje_reincidencia ?>%
                                        </div>
                                    </div>
                                    <p class="card-text"><?= $prediccion ?></p>
                                </div>
                            </div>
                        <?php endif; ?>
                        
                        <!-- Botón para mostrar historial -->
                        <form method="POST" class="mt-4">
                            <button type="submit" name="mostrar_historial" class="btn btn-info w-100">
                                <i class="bi bi-clock-history me-2"></i>Mostrar Historial de Predicciones
                            </button>
                        </form>
                        
                        <!-- Historial de predicciones -->
                        <?php if (isset($_POST['mostrar_historial'])): ?>
                            <div class="card history-card mt-4">
                                <div class="card-body">
                                    <h5 class="card-title"><i class="bi bi-archive me-2"></i>Historial de Predicciones</h5>
                                    <?php if (!empty($_SESSION['historial_predicciones'])): ?>
                                        <div class="table-responsive">
                                            <table class="table table-sm">
                                                <thead>
                                                    <tr>
                                                        <th>Estudiante</th>
                                                        <th>Tipo Falta</th>
                                                        <th>Predicción</th>
                                                        <th>Probabilidad</th>
                                                        <th>Fecha</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php foreach ($_SESSION['historial_predicciones'] as $pred): ?>
                                                        <tr>
                                                            <td><?= htmlspecialchars($pred['id_estudiante']) ?></td>
                                                            <td><?= htmlspecialchars($pred['tipo_falta']) ?></td>
                                                            <td><?= htmlspecialchars($pred['prediccion']) ?></td>
                                                            <td>
                                                                <span class="badge rounded-pill bg-<?= 
                                                                    $pred['porcentaje_reincidencia'] > 70 ? 'danger' : 
                                                                    ($pred['porcentaje_reincidencia'] > 50 ? 'warning' : 'success') 
                                                                ?>">
                                                                    <?= $pred['porcentaje_reincidencia'] ?>%
                                                                </span>
                                                            </td>
                                                            <td><?= htmlspecialchars($pred['fecha']) ?></td>
                                                        </tr>
                                                    <?php endforeach; ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    <?php else: ?>
                                        <div class="alert alert-secondary mb-0" role="alert">
                                            No hay historial de predicciones registradas.
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endif; ?>
                        
                        <!-- Botón para regresar -->
                        <div class="d-grid mt-4">
                            <a href="http://localhost/EducationalHarmonie_pa7/target/classes/templates/" 
                               class="btn btn-outline-primary">
                                <i class="bi bi-arrow-left-circle me-2"></i>Regresar a la Vista Principal
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Marca de agua fija -->
    <div class="watermark">EDUCATIONAL HARMONIE</div>

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Validación de formulario -->
    <script>
        (function () {
            'use strict'
            var forms = document.querySelectorAll('.needs-validation')
            Array.prototype.slice.call(forms)
                .forEach(function (form) {
                    form.addEventListener('submit', function (event) {
                        if (!form.checkValidity()) {
                            event.preventDefault()
                            event.stopPropagation()
                        }
                        form.classList.add('was-validated')
                    }, false)
                })
        })()
    </script>
</body>
</html>
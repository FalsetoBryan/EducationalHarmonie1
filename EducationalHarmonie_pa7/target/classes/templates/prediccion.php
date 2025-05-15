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

// Verificar si ya hay un historial de predicciones en la sesión
if (!isset($_SESSION['historial_predicciones'])) {
    $_SESSION['historial_predicciones'] = [];
}

// Procesar formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Verificar que los datos del formulario están definidos antes de usarlos
    if (isset($_POST['id_estudiante']) && isset($_POST['tipo_falta']) && isset($_POST['frecuencia_faltas']) && isset($_POST['id_falta'])) {
        $id_estudiante = $_POST['id_estudiante'];
        $tipo_falta = $_POST['tipo_falta'];
        $frecuencia_faltas = $_POST['frecuencia_faltas'];
        $id_falta = $_POST['id_falta'];

        // Consulta para obtener reincidencias y otros datos
        $consulta = "SELECT id_falta, id_estudiante, tipo_falta, frecuencia_faltas, reincidencia 
                     FROM faltas 
                     WHERE id_estudiante = '$id_estudiante' AND tipo_falta = '$tipo_falta'";

        $resultado = $conexion->query($consulta);

        if ($resultado && $resultado->num_rows > 0) {
            $total_reincidencias = 0;
            $total_faltas = 0;
            $total_frecuencia = 0;

            // Variables para hacer la predicción
            while ($row = $resultado->fetch_assoc()) {
                // Sumar las reincidencias 'Sí'
                if (strtolower($row['reincidencia']) == 'sí') {
                    $total_reincidencias++;
                }

                // Sumar las faltas y la frecuencia
                $total_faltas++;
                $total_frecuencia += $row['frecuencia_faltas'];

                // Almacenamos la información para mostrar
                $historial[] = $row;
            }

            // Predicción basada en reincidencias y frecuencia
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

            // Guardar en el historial de la sesión
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

// Obtener lista de estudiantes para el formulario
$estudiantes = $conexion->query("SELECT id_estudiante, nombre FROM estudiantes");

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Consulta de Reincidencias</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">

            <div class="card shadow-lg">
                <div class="card-header text-center bg-primary text-white">
                    <h4>Consulta de Reincidencias</h4>
                </div>
                <div class="card-body">

                    <form method="POST" action="">
                        <div class="mb-3">
                            <label for="id_estudiante" class="form-label">Selecciona Estudiante:</label>
                            <select name="id_estudiante" class="form-select" required>
                                <option value="">Selecciona un estudiante</option>
                                <?php while($row = $estudiantes->fetch_assoc()) { ?>
                                    <option value="<?php echo $row['id_estudiante']; ?>">
                                        <?php echo $row['id_estudiante']; ?>
                                    </option>
                                <?php } ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="tipo_falta" class="form-label">Selecciona Tipo de Falta:</label>
                            <select name="tipo_falta" class="form-select" required>
                                <option value="">Selecciona tipo de falta</option>
                                <option value="Leve">Leve</option>
                                <option value="Grave">Grave</option>
                                <option value="Moderada">Moderada</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="frecuencia_faltas" class="form-label">Frecuencia de Faltas:</label>
                            <input type="number" name="frecuencia_faltas" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label for="id_falta" class="form-label">ID de Falta:</label>
                            <input type="text" name="id_falta" class="form-control" required>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-success">Consultar Reincidencias</button>
                            <a href="" class="btn btn-secondary">Limpiar</a>
                        </div>
                    </form>

                    <?php if (!empty($mensaje)) { ?>
                        <div class="alert alert-info mt-4" role="alert">
                            <?php echo $mensaje; ?>
                        </div>
                    <?php } ?>

                    <?php if (!empty($historial)) { ?>
                        <h5 class="mt-4">Historial Detallado:</h5>
                        <table class="table table-bordered table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>ID Falta</th>
                                    <th>ID Estudiante</th>
                                    <th>Tipo de Falta</th>
                                    <th>Frecuencia de Faltas</th>
                                    <th>Reincidencia</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($historial as $item) { ?>
                                    <tr>
                                        <td><?php echo $item['id_falta']; ?></td>
                                        <td><?php echo $item['id_estudiante']; ?></td>
                                        <td><?php echo $item['tipo_falta']; ?></td>
                                        <td><?php echo $item['frecuencia_faltas']; ?></td>
                                        <td><?php echo $item['reincidencia']; ?></td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    <?php } ?>

                    <!-- Mostrar predicción con porcentaje -->
                    <?php if (!empty($prediccion)) { ?>
                        <div class="alert alert-warning mt-4" role="alert">
                            <strong>Predicción:</strong> <?php echo $prediccion; ?> <br>
                            <strong>Probabilidad de Reincidencia:</strong> <?php echo $porcentaje_reincidencia; ?>%
                        </div>
                    <?php } ?>

                    <!-- Botón para mostrar historial de predicciones -->
                    <form method="POST" action="">
                        <button type="submit" name="mostrar_historial" class="btn btn-info mt-4">Mostrar Historial de Predicciones</button>
                    </form>

                    <?php
                    // Mostrar historial de predicciones
                    if (isset($_POST['mostrar_historial'])) {
                        if (!empty($_SESSION['historial_predicciones'])) {
                            echo "<h5 class='mt-4'>Historial de Predicciones:</h5>";
                            echo "<table class='table table-bordered'>";
                            echo "<thead><tr><th>Estudiante</th><th>Tipo de Falta</th><th>Predicción</th><th>Probabilidad</th><th>Fecha</th></tr></thead><tbody>";
                            foreach ($_SESSION['historial_predicciones'] as $pred) {
                                echo "<tr><td>{$pred['id_estudiante']}</td><td>{$pred['tipo_falta']}</td><td>{$pred['prediccion']}</td><td>{$pred['porcentaje_reincidencia']}%</td><td>{$pred['fecha']}</td></tr>";
                            }
                            echo "</tbody></table>";
                        } else {
                            echo "<p>No hay historial de predicciones.</p>";
                        }
                    }
                    ?>

                    <!-- Botón para regresar a la vista principal -->
                    <div class="d-grid gap-2 mt-4">
                        <a href="http://localhost/EducationalHarmonie_pa7/target/classes/templates/" class="btn btn-primary">Regresar a la Vista Principal</a>
                    </div>

                </div>
            </div>

        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>  
</html>
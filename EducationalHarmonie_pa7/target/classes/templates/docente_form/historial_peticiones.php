<?php
// Conexión a la base de datos
$servername = "localhost"; 
$username = "root"; 
$password = ""; 
$dbname = "sistema_educativo"; 

// Crear la conexión
$conecta = new mysqli($servername, $username, $password, $dbname);

// Verificar la conexión
if ($conecta->connect_error) {
    die("Conexión fallida: " . $conecta->connect_error);
}

// Consulta para obtener el historial de peticiones
$sql = "SELECT id, fecha, descripcion, detalle FROM peticiones";
$result = $conecta->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Historial de Peticiones</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container py-4">
    <h4 class="text-center mb-4">Historial de Reportes</h4>

    <!-- Botón para regresar al menú -->
    <div class="text-center mb-4">
        <a href="docente.php" class="btn btn-secondary">Regresar al Menú</a>
    </div>

    <!-- Tabla para mostrar las peticiones -->
    <div class="table-responsive table-hover">
        <table class="table table-striped">
            <thead class="text-muted">
                <tr>
                    <th class="text-center">ID</th>
                    <th class="text-center">Fecha</th>
                    <th class="text-center">Descripción</th>
                    <th class="text-center">Detalles</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Verificar si hay resultados
                if ($result->num_rows > 1) {
                    // Mostrar cada fila de los resultados
                    while($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td class='text-center'>" . $row['reporte_id'] . "</td>";
                        echo "<td class='text-center'>" . $row['fecha'] . "</td>";
                        echo "<td class='text-center'>" . $row['descripcion'] . "</td>";
                        echo "<td class='text-center'>" . $row['detalle'] . "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='4' class='text-center'>No hay peticiones registradas</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
// Cerrar la conexión
$conecta->close();
?>

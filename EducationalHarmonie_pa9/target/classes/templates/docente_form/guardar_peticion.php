<?php
// Datos de la base de datos
$servername = "localhost"; 
$username = "root"; 
$password = ""; 
$dbname = "buzón_peticiones"; 

// Crear la conexión
$conecta = new mysqli($servername, $username, $password, $dbname);

// Verificar la conexión
if ($conecta->connect_error) {
    die("Conexión fallida: " . $conecta->connect_error);
}

// Verificar que los datos del formulario estén presentes
if (isset($_POST['fecha']) && isset($_POST['descripcion']) && isset($_POST['detalle'])) {
    // Obtener los datos del formulario
    $reporte_id = $_POST['reporte_id'];
    $fecha = $_POST['fecha'];
    $descripcion = $_POST['descripcion'];
    $detalle = $_POST['detalle'];

    // Preparar la consulta para insertar la petición
    $sql = "INSERT INTO peticiones (reporte_id, fecha, descripcion, detalle) VALUES (?, ?, ?,?)";
    $stmt = $conecta->prepare($sql);
    $stmt->bind_param("sss",$reporte_id, $fecha, $descripcion, $detalle);

    // Ejecutar la consulta y dar retroalimentación
    if ($stmt->execute()) {
        echo "Petición guardada con éxito.";
    } else {
        echo "Error: " . $stmt->error;
    }

    // Cerrar la conexión
    $stmt->close();
} else {
    echo "Todos los campos son requeridos.";
}

// Cerrar la conexión
$conecta->close();
?>
<!-- Botón para regresar al menú -->
<div class="text-center mt-4">
    <a href="../docente.php" class="btn btn-primary">Regresar al Menú</a>
</div>

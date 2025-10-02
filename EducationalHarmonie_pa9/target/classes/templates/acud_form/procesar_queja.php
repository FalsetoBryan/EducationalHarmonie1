<?php
// Incluye la conexión a la base de datos
include('conexion.php');

// Verifica si el formulario fue enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Recibe los valores del formulario
    $fecha = $_POST['fecha'];
    $descripcion = $_POST['descripcion'];
    $detalle = $_POST['detalle'];

    try {
        // Prepara la consulta para insertar la queja en la base de datos
        $sql = "INSERT INTO quejas (fecha, descripcion, detalle) VALUES (:fecha, :descripcion, :detalle)";
        $stmt = $pdo->prepare($sql);

        // Vincula los valores a los parámetros de la consulta
        $stmt->bindParam(':fecha', $fecha);
        $stmt->bindParam(':descripcion', $descripcion);
        $stmt->bindParam(':detalle', $detalle);

        // Ejecuta la consulta
        $stmt->execute();

        echo "Queja registrada exitosamente.";
    } catch (PDOException $e) {
        // En caso de error, muestra el mensaje
        echo 'Error: ' . $e->getMessage();
    }
}
?>

<?php
// Configuración de la conexión a la base de datos
$servername = "localhost";
$username = "root"; // Reemplaza con tu usuario de MySQL
$password = ""; // Reemplaza con tu contraseña de MySQL
$dbname = "sistema_educativo";

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// Obtener datos del formulario
$id_estudiante = $_POST['id_estudiante'];
$fecha = $_POST['fecha'];
$descripcion = $_POST['descripcion'];
$detalle = $_POST['detalle'];

// Verificar si el estudiante existe
$sql_verificar = "SELECT id_estudiante FROM estudiantes WHERE id_estudiante = ?";
$stmt = $conn->prepare($sql_verificar);
$stmt->bind_param("i", $id_estudiante);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    echo "Error: El ID del estudiante no existe en la base de datos.";
    exit();
}

// Insertar el reporte en la base de datos
$sql = "INSERT INTO reportes (id_estudiante, fecha, descripcion, detalle) VALUES (?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("isss", $id_estudiante, $fecha, $descripcion, $detalle);

if ($stmt->execute()) {
    echo "Reporte registrado exitosamente.";
} else {
    echo "Error al registrar el reporte: " . $conn->error;
}

$stmt->close();
$conn->close();
?>
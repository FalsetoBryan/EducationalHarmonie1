<?php
// Configuración de la conexión
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "sistema_educativo";

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// Obtener datos del POST
$id_reporte = $_POST['id_reporte'];
$estado = $_POST['estado'];

// Actualizar el estado en la base de datos
$sql = "UPDATE reportes SET estado = ? WHERE id_reporte = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("si", $estado, $id_reporte);

if ($stmt->execute()) {
    echo "success";
} else {
    echo "error";
}

$stmt->close();
$conn->close();
?>
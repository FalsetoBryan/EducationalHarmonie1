<?php
// Incluir la conexión a la base de datos
include 'conecta.php';

// Verificar si se ha recibido el ID
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Consulta para eliminar el usuario
    $delete = "DELETE FROM usuarios WHERE id = $id";
    
    if ($conecta->query($delete) === TRUE) {
        echo "<h3 class='text-success'>¡Usuario eliminado con éxito!</h3>";
    } else {
        echo "<h3 class='text-danger'>Error al eliminar: " . $conecta->error . "</h3>";
    }

    // Redirigir después de eliminar
    header("Location: gestion_usuarios.php");
    exit();
} else {
    echo "<h3 class='text-danger'>ID de usuario no proporcionado.</h3>";
}
?>

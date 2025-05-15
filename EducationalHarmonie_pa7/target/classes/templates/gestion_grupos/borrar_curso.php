<?php
include "conexion.php";

if (isset($_GET['idcurso'])) {
    $idcurso = $_GET['idcurso'];

    // Consulta SQL para borrar el curso
    $sql = "DELETE FROM cursos WHERE idcurso = ?";
    
    // Preparar y ejecutar la consulta
    if ($stmt = $conecta->prepare($sql)) {
        $stmt->bind_param("i", $idcurso);
        if ($stmt->execute()) {
            echo "<script>alert('Curso eliminado con éxito'); window.location.href='cursos.php';</script>";
        } else {
            echo "<script>alert('Error al eliminar el curso'); window.location.href='cursos.php';</script>";
        }
        $stmt->close();
    } else {
        echo "<script>alert('Error en la consulta'); window.location.href='cursos.php';</script>";
    }
} else {
    echo "<script>alert('No se proporcionó un ID de curso'); window.location.href='cursos.php';</script>";
}
?>

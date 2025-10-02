<?php
include "conexion.php";

if (isset($_GET['idcurso'])) {
    $idcurso = $_GET['idcurso'];
    
    // Obtener los datos del curso
    $sql = "SELECT * FROM cursos WHERE idcurso = ?";
    if ($stmt = $conecta->prepare($sql)) {
        $stmt->bind_param("i", $idcurso);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
    } else {
        echo "<script>alert('Curso no encontrado'); window.location.href='cursos.php';</script>";
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recibir los datos del formulario
    $nombre_curso = $_POST['nombre_curso'];
    $grado_escolar = $_POST['grado_escolar'];
    $docente = $_POST['docente'];
    $lider = $_POST['lider'];
    
    // Actualizar el curso
    $sql = "UPDATE cursos SET nombre_curso = ?, grado_escolar = ?, docente = ?, lider = ? WHERE idcurso = ?";
    if ($stmt = $conecta->prepare($sql)) {
        $stmt->bind_param("ssssi", $nombre_curso, $grado_escolar, $docente, $lider, $idcurso);
        if ($stmt->execute()) {
            echo "<script>alert('Curso actualizado con éxito'); window.location.href='cursos.php';</script>";
        } else {
            echo "<script>alert('Error al actualizar el curso');</script>";
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Curso</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container py-4">
        <h3>Editar Curso</h3>
        <form method="POST">
            <div class="mb-3">
                <label for="nombre_curso" class="form-label">Nombre del Curso</label>
                <input type="text" class="form-control" id="nombre_curso" name="nombre_curso" value="<?php echo $row['nombre_curso']; ?>" required>
            </div>
            <div class="mb-3">
                <label for="grado_escolar" class="form-label">Grado Escolar</label>
                <input type="text" class="form-control" id="grado_escolar" name="grado_escolar" value="<?php echo $row['grado_escolar']; ?>" required>
            </div>
            <div class="mb-3">
                <label for="docente" class="form-label">Docente</label>
                <input type="text" class="form-control" id="docente" name="docente" value="<?php echo $row['docente']; ?>" required>
            </div>
            <div class="mb-3">
                <label for="lider" class="form-label">Líder del Curso</label>
                <input type="text" class="form-control" id="lider" name="lider" value="<?php echo $row['lider']; ?>" required>
            </div>
            <button type="submit" class="btn btn-success">Actualizar Curso</button>
        </form>
    </div>
</body>
</html>






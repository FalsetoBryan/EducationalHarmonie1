<?php
// Incluir la conexión a la base de datos
include 'conexion.php';

// Inicializar mensaje vacío
$mensaje = "";

// Procesar formulario al enviar
if (isset($_POST['registrar'])) {
    // Obtener y limpiar datos del formulario
    $nombre_curso = $conecta->real_escape_string($_POST['nombre_curso']);
    $grado_escolar = $conecta->real_escape_string($_POST['grado_escolar']);
    $docente = $conecta->real_escape_string($_POST['docente']);
    $lider = $conecta->real_escape_string($_POST['lider']);

    // Consulta para insertar datos en la tabla usuarios
    $insertar = "INSERT INTO cursos (nombre_curso, grado_escolar, docente, lider) 
                 VALUES ('$nombre_curso', '$grado_escolar', '$docente', '$lider')";
    
    if ($conecta->query($insertar) === TRUE) {
        // Si la inserción es exitosa, redirigir para evitar la reenvío del formulario
        header("Location: cursos.php");  // Asegúrate de redirigir a la página de listado de cursos
        exit;  // Evita que se ejecute cualquier otro código después de la redirección
    } else {
        $mensaje = "<h3 class='text-danger'>Error al registrar: " . $conecta->error . "</h3>";
    }
}
?>

<div class="container py-4">
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" class="border p-4 bg-light rounded">
    <div class="form-group">
            <label for="idcurso">Codigo Del Curso</label>
            <input type="text" name="idcurso" id="idcurso" placeholder="Codigo Del Curso" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="nombre_curso">Nombre Del Curso</label>
            <input type="text" name="nombre_curso" id="nombre_curso" placeholder="Nombre Del Curso" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="grado_escolar">Grado Escolar</label>
            <input type="text" name="grado_escolar" id="grado_escolar" placeholder="Grado Escolar" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="docente">Docente</label>
            <input type="text" name="docente" id="docente" placeholder="Docente" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="lider">Líder Del Curso</label>
            <input type="text" name="lider" id="lider" placeholder="Líder Del Curso" class="form-control" required>
        </div>

        <button type="submit" name="registrar" class="btn btn-success btn-block">Registrar</button>
    </form>

    <div class="mt-3">
        <?php echo $mensaje; ?>
    </div>
</div>




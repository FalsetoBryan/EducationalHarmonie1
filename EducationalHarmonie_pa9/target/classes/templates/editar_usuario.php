<?php
// Incluir la conexión a la base de datos
include 'conecta.php';

// Verificar si se ha recibido el ID del usuario a editar
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Consultar los datos del usuario con el ID proporcionado
    $consulta = "SELECT * FROM usuarios WHERE id = $id";
    $result = $conecta->query($consulta);

    // Si el usuario existe, obtener los datos
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
    } else {
        echo "<h3 class='text-danger'>Usuario no encontrado.</h3>";
        exit();
    }
} else {
    echo "<h3 class='text-danger'>ID no especificado.</h3>";
    exit();
}

// Procesar el formulario de edición
if (isset($_POST['editar'])) {
    // Obtener los nuevos valores del formulario
    $nombre = $conecta->real_escape_string($_POST['nombre']);
    $apellidop = $conecta->real_escape_string($_POST['apellidop']);
    $apellidom = $conecta->real_escape_string($_POST['apellidom']);
    $usuario = $conecta->real_escape_string($_POST['usuario']);
    $correo = $conecta->real_escape_string($_POST['correo']);
    $contraseña = $conecta->real_escape_string($_POST['contraseña']);
    $cargo = $conecta->real_escape_string($_POST['cargo']);
    $curso = $conecta->real_escape_string($_POST['curso']);

    // Actualizar los datos del usuario en la base de datos
    $update = "UPDATE usuarios 
               SET nombre = '$nombre', apellidop = '$apellidop', apellidom = '$apellidom', 
                   usuario = '$usuario', correo = '$correo', contraseña = '$contraseña', idcargo = '$cargo', curso = '$curso' 
               WHERE id = $id";

    if ($conecta->query($update) === TRUE) {
        echo "<h3 class='text-success'>¡Usuario actualizado con éxito!</h3>";
    } else {
        echo "<h3 class='text-danger'>Error al actualizar: " . $conecta->error . "</h3>";
    }
}

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Usuario</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h4 class="text-center mb-4">Editar Usuario</h4>

        <!-- Formulario de edición -->
        <form action="editar_usuario.php?id=<?php echo $row['id']; ?>" method="post" class="border p-4 bg-light rounded">
            <div class="form-group">
                <label for="nombre">Nombre:</label>
                <input type="text" name="nombre" id="nombre" value="<?php echo $row['nombre']; ?>" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="apellidop">Apellido Paterno:</label>
                <input type="text" name="apellidop" id="apellidop" value="<?php echo $row['apellidop']; ?>" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="apellidom">Apellido Materno:</label>
                <input type="text" name="apellidom" id="apellidom" value="<?php echo $row['apellidom']; ?>" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="usuario">Usuario:</label>
                <input type="text" name="usuario" id="usuario" value="<?php echo $row['usuario']; ?>" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="correo">Correo:</label>
                <input type="email" name="correo" id="correo" value="<?php echo $row['correo']; ?>" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="contraseña">Contraseña:</label>
                <input type="password" name="contraseña" id="contraseña" value="<?php echo $row['contraseña']; ?>" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="cargo">Cargo:</label>
                <select name="cargo" id="cargo" class="form-control" required>
                    <option value="">Selecciona un Cargo</option>
                    <?php 
                    // Obtener los cargos disponibles para el select
                    $consulta_cargos = "SELECT * FROM cargo";
                    $result_cargos = $conecta->query($consulta_cargos);

                    while ($cargo_row = $result_cargos->fetch_assoc()) {
                        // Comprobar si el cargo actual coincide con el del usuario
                        $selected = ($cargo_row['id_cargo'] == $row['idcargo']) ? 'selected' : '';
                        echo "<option value='{$cargo_row['id_cargo']}' $selected>{$cargo_row['cargo']}</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="form-group">
                <label for="curso">Curso:</label>
                <input type="text" name="curso" id="curso" value="<?php echo $row['curso']; ?>" class="form-control" required>
            </div>
            <button type="submit" name="editar" class="btn btn-success btn-block">Actualizar</button>
        </form>

        <!-- Botón de Regreso -->
        <div class="mt-3">
            <a href="gestion_usuarios.php" class="btn btn-secondary">Regresar al listado</a>
        </div>
    </div>
</body>
</html>




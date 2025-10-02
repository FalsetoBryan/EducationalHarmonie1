<?php
// Incluir la conexión a la base de datos
include 'conecta.php';

// Inicializar mensaje vacío
$mensaje = "";

// Procesar formulario al enviar
if (isset($_POST['registrar'])) {
    // Obtener y limpiar datos del formulario
    $nombre = $conecta->real_escape_string($_POST['nombre']);
    $apellido1 = $conecta->real_escape_string($_POST['apellidop']);
    $apellido2 = $conecta->real_escape_string($_POST['apellidom']);
    $usuario = $conecta->real_escape_string($_POST['usuario']);
    $correo = $conecta->real_escape_string($_POST['correo']);
    $pass = $conecta->real_escape_string($_POST['contraseña']);
    $cargo = $conecta->real_escape_string($_POST['cargo']);
    $curso = $conecta->real_escape_string($_POST['curso']);

    // Consulta para insertar datos en la tabla usuarios
    $insertar = "INSERT INTO usuarios (nombre, apellidop, apellidom, usuario, correo, contraseña, idcargo, curso) 
                 VALUES ('$nombre', '$apellido1', '$apellido2', '$usuario', '$correo', '$pass', '$cargo','$curso')";
    
    if ($conecta->query($insertar) === TRUE) {
        $mensaje = "<h3 class='text-success'>¡Registro realizado con éxito!</h3>";
    } else {
        $mensaje = "<h3 class='text-danger'>Error al registrar: " . $conecta->error . "</h3>";
    }
}
?>

<div class="container py-4">
    <h4 class="text-center mb-4">Registrar Usuario</h4>
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" class="border p-4 bg-light rounded">
        <div class="form-group">
            <label for="nombre"> Nombre:</label>
            <input type="text" name="nombre" id="nombre" placeholder="" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="apellidop">Apellido Paterno:</label>
            <input type="text" name="apellidop" id="apellidop" placeholder="" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="apellidom">Apellido Materno:</label>
            <input type="text" name="apellidom" id="apellidom" placeholder="" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="usuario">Usuario:</label>
            <input type="text" name="usuario" id="usuario" placeholder="" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="correo">Correo:</label>
            <input type="email" name="correo" id="correo" placeholder="" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="contraseña">Contraseña:</label>
            <input type="password" name="contraseña" id="contraseña" placeholder="" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="cargo">Cargo:</label>
            <select name="cargo" id="cargo" class="form-control" required>
                <option value="">Selecciona un Cargo</option>
                <?php
                // Consulta para obtener los cargos
                $consulta_cargo = "SELECT * FROM cargo";
                $guardar_cargo = $conecta->query($consulta_cargo);
                while ($row_cargo = $guardar_cargo->fetch_assoc()) {
                    echo "<option value='" . $row_cargo['id_cargo'] . "'>" . $row_cargo['cargo'] . "</option>";
                }
                ?>
            </select>
        </div>
        <div class="form-group">
            <label for="curso">Curso</label>
            <input type="text" name="curso" id="curso" placeholder="" class="form-control" required>
        </div>
        <button type="submit" name="registrar" class="btn btn-success btn-block">Registrar</button>
    </form>
    <div class="mt-3">
        <?php echo $mensaje; ?>
    </div>
</div>





    
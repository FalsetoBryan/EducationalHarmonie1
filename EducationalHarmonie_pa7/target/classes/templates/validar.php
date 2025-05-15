<?php
$username = $_POST['username'];
$password = $_POST['password'];
session_start();
$_SESSION['username'] = $username;

$conexion = mysqli_connect("localhost", "root", "", "sistema_educativo");

// Verifica si la conexión fue exitosa
if (!$conexion) {
    die("Error en la conexión: " . mysqli_connect_error());
}

$consulta = "SELECT * FROM usuarios WHERE usuario='$username' AND contraseña='$password'";
$resultado = mysqli_query($conexion, $consulta);

// Verifica si la consulta fue exitosa
if (!$resultado) {
    die("Error en la consulta: " . mysqli_error($conexion));
}

// Verifica si la consulta devolvió algún resultado
$filas = mysqli_fetch_array($resultado);

if ($filas) {
    // Si el usuario fue encontrado, redirige según el id_cargo
    if ($filas['idcargo'] == 1) { // admin
        header("Location: admin.php");
        exit();
    }  elseif ($filas['idcargo'] == 2) { // admin
            header("Location: acudiente.php");
            exit();
    } elseif ($filas['idcargo'] == 3) { // docente
        header("Location: docente.php");
        exit();
    }
} else {
    // Si no se encontró el usuario, muestra un mensaje de error
    include("login.php");
    echo '<h1 class="bad">ERROR EN LA AUTENTIFICACIÓN</h1>';
}

// Libera el resultado y cierra la conexión
mysqli_free_result($resultado);
mysqli_close($conexion);

error_reporting(E_ALL);
ini_set('display_errors', 1);
?>


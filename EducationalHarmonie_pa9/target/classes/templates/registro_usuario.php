<?php
include 'conecta.php';

$nombre = $conecta->real_escape_string($_POST['nombre']);
$apellido1 = $conecta->real_escape_string($_POST['apellidop']);
$apellido2 = $conecta->real_escape_string($_POST['apellidom']);
$usuario = $conecta->real_escape_string($_POST['usuario']);
$correo = $conecta->real_escape_string($_POST['correo']);
$pass = $conecta->real_escape_string($_POST['contraseña']);
$cargo = $conecta->real_escape_string($_POST['cargo']);
$curso = $conecta->real_escape_string($_POST['curso']);

$insertar = "INSERT INTO usuarios (nombre, apellidop, apellidom, usuario, correo, contraseña, idcargo, curso) 
             VALUES ('$nombre', '$apellido1', '$apellido2', '$usuario', '$correo', '$pass', '$cargo','$curso')";

if ($conecta->query($insertar) === TRUE) {
    echo "<div class='text-success'>¡Registro realizado con éxito!</div>";
} else {
    echo "<div class='text-danger'>Error al registrar: " . $conecta->error . "</div>";
}
?>

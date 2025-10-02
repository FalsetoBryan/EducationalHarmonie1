<?php
// Conexión a la base de datos
$host = "localhost";
$usuario = "root";
$password = "";
$base_datos = "sistema_educativo";

$conecta = new mysqli($host, $usuario, $password, $base_datos);

// Verificar conexión
if ($conecta->connect_error) {
    die("Error de conexión: " . $conecta->connect_error);
}
?>

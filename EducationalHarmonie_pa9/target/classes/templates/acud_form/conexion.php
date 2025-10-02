<?php
$host = 'localhost';  // Cambia esto por el host de tu servidor (por ejemplo, 'localhost')
$dbname = 'buzón_peticiones';  // Nombre de la base de datos
$username = 'root';  
$password = '';  
try {
    // Conexión a la base de datos usando PDO
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // En caso de error, muestra el mensaje
    echo 'Error: ' . $e->getMessage();
}
?>
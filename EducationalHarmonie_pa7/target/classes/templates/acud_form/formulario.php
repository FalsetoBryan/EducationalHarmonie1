<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listado de Reportes</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-image: url('../logo/logo.JPG.jpg');
            background-size: 700px;
            background-position: center;
            background-repeat: no-repeat;
            height: 100vh;
        }
        .container {
            background-color: rgba(255, 255, 255, 0.9);
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0px 0px 15px rgba(0, 0, 0, 0.2);
        }
        h4 {
            color: #007bff;
        }
    </style>
</head>
<body>
<div class="container py-4">
    <h4 class="text-center mb-4">Listado de Reportes Estudiantiles</h4>
    
    <div class="text-center mb-4">
        <a href="../acudiente.php" class="btn btn-secondary">Regresar al Menú</a>
        
    </div>

    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID Reporte</th>
                    <th>ID Estudiante</th>
                    <th>Fecha</th>
                    <th>Descripción</th>
                    <th>Detalle</th>
                    <th>Fecha Registro</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Configuración de la conexión
                $servername = "localhost";
                $username = "root";
                $password = "";
                $dbname = "sistema_educativo";

                // Crear conexión
                $conn = new mysqli($servername, $username, $password, $dbname);

                // Verificar conexión
                if ($conn->connect_error) {
                    die("Error de conexión: " . $conn->connect_error);
                }

                // Consulta para obtener los reportes
                $sql = "SELECT * FROM reportes ORDER BY fecha_registro DESC";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        echo "<tr>
                                <td>".$row["id_reporte"]."</td>
                                <td>".$row["id_estudiante"]."</td>
                                <td>".$row["fecha"]."</td>
                                <td>".$row["descripcion"]."</td>
                                <td>".$row["detalle"]."</td>
                                <td>".$row["fecha_registro"]."</td>
                              </tr>";
                    }
                } else {
                    echo "<tr><td colspan='6' class='text-center'>No hay reportes registrados</td></tr>";
                }
                $conn->close();
                ?>
            </tbody>
        </table>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
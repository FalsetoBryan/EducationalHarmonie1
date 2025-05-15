<?php
// Conexión a la base de datos
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "sistema_educativo";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

$result = $conn->query("SELECT id_queja, fecha, id_estudiante, descripcion, detalle FROM quejas");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listado de Quejas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .search-container {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
<div class="container py-4">
    <h4 class="text-center mb-4">Listado de Quejas</h4>

    <!-- Barra de búsqueda -->
    <div class="search-container text-center">
        <input type="text" id="searchInput" class="form-control" placeholder="Buscar quejas...">
        <button class="btn btn-primary mt-2" id="searchBtn">Buscar</button>
        <button class="btn btn-secondary mt-2" id="clearBtn">Limpiar</button>
    </div>

    <!-- Tabla de quejas -->
    <table class="table table-striped">
        <thead>
            <tr>
                <th scope="col">Ticket (ID)</th>
                <th scope="col">Fecha</th>
                <th scope="col">Descripción</th>
                <th scope="col">Detalles</th>
            </tr>
        </thead>
        <tbody id="quejasTable">
            <?php
            if ($result && $result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row["id_queja"] . "</td>";
                    echo "<td>" . $row["fecha"] . "</td>";
                    echo "<td>" . $row["descripcion"] . "</td>";
                    echo "<td>" . $row["detalle"] . "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='4' class='text-center'>No hay quejas registradas.</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>

<!-- Scripts cargados al final -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    $('#searchBtn').click(function() {
        var searchValue = $('#searchInput').val().toLowerCase();
        $('#quejasTable tr').each(function() {
            var rowText = $(this).text().toLowerCase();
            if (rowText.indexOf(searchValue) === -1) {
                $(this).hide();
            } else {
                $(this).show();
            }
        });
    });

    $('#clearBtn').click(function() {
        $('#searchInput').val('');
        $('#quejasTable tr').show();
    });
});
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
$conn->close();
?>

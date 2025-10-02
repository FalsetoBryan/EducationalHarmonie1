<?php
// Incluir la conexión a la base de datos
include 'conecta.php';

// Consulta predeterminada para obtener los usuarios
$consulta = "SELECT u.id, u.nombre, u.apellidop, u.apellidom, u.usuario, u.correo, c.cargo, u.curso 
             FROM usuarios u
             JOIN cargo c ON u.idcargo = c.id_cargo";

// Comprobar si se ha enviado un término de búsqueda mediante AJAX
if (isset($_GET['buscar']) && !empty($_GET['buscar'])) {
    $busqueda = $conecta->real_escape_string($_GET['buscar']);
    // Modificar la consulta para incluir el término de búsqueda
    $consulta .= " WHERE u.nombre LIKE '%$busqueda%' 
                   OR u.apellidop LIKE '%$busqueda%' 
                   OR u.apellidom LIKE '%$busqueda%' 
                   OR u.usuario LIKE '%$busqueda%' 
                   OR u.correo LIKE '%$busqueda%' 
                   OR c.cargo LIKE '%$busqueda%'
                   OR u.curso LIKE '%$busqueda%'";
}

$guardar = $conecta->query($consulta);
?>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Gestión de Usuarios</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<div class="container py-4">
    <h4 class="text-center mb-4"></h4>

    <div class="row text-center col-sm-12 col-md-12 col-lg-12 py-4">
        <ul class="nav nav-tabs" id="myTab" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" id="crear-tab" data-bs-toggle="tab" href="#crear" role="tab" aria-controls="crear" aria-selected="true">Registrar Alumno</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="registro-tab" data-bs-toggle="tab" href="#registro" role="tab" aria-controls="registro" aria-selected="false">Listado</a>
            </li>
        </ul>
    </div>

    <div class="tab-content" id="myTabContent">
        <div class="tab-pane fade show active" id="crear" role="tabpanel" aria-labelledby="crear-tab">
            <?php include 'registro.php'; ?>
        </div>

        <div class="tab-pane fade" id="registro" role="tabpanel" aria-labelledby="registro-tab">
            <h3 class="text-center">Listado de Usuarios</h3>

            <form id="searchForm" class="mb-4">
                <div class="input-group">
                    <input type="text" id="searchInput" class="form-control" placeholder="Buscar usuario..." value="<?php echo isset($_GET['buscar']) ? htmlspecialchars($_GET['buscar']) : ''; ?>">
                    <button type="submit" class="btn btn-primary">Buscar</button>
                    <button type="button" id="clearSearch" class="btn btn-secondary">Limpiar</button>
                </div>
            </form>

            <div class="table-responsive table-hover" id="TablaConsulta">
                <table class="table">
                    <thead class="text-muted">
                        <tr>
                            <th class="text-center">Código único (ID)</th>
                            <th class="text-center">Nombre</th>
                            <th class="text-center">Apellido Paterno</th>
                            <th class="text-center">Apellido Materno</th>
                            <th class="text-center">Usuario</th>
                            <th class="text-center">Correo</th>
                            <th class="text-center">Cargo</th>
                            <th class="text-center">Curso</th>
                            <th class="text-center">Opciones</th>
                        </tr>
                    </thead>
                    <tbody id="userList">
                        <?php 
                        if ($guardar->num_rows > 0) {
                            while($row = $guardar->fetch_assoc()) { 
                        ?>
                        <tr>
                            <td><?php echo $row['id']; ?></td>
                            <td><?php echo $row['nombre']; ?></td>
                            <td><?php echo $row['apellidop']; ?></td>
                            <td><?php echo $row['apellidom']; ?></td>
                            <td><?php echo $row['usuario']; ?></td>
                            <td><?php echo $row['correo']; ?></td>
                            <td><?php echo $row['cargo']; ?></td>
                            <td><?php echo $row['curso']; ?></td>
                            <td>
                                <a href="editar_usuario.php?id=<?php echo $row['id']; ?>" class="btn btn-warning btn-sm">Editar</a> 
                                <a href="borrar_usuario.php?id=<?php echo $row['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('¿Estás seguro de que deseas borrar este usuario?');">Borrar</a>
                            </td>
                        </tr>
                        <?php 
                            } 
                        } else {
                            echo "<tr><td colspan='9' class='text-center'>No hay registros</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
$(document).ready(function(){
    $('#searchForm').submit(function(e){
        e.preventDefault();
        var busqueda = $('#searchInput').val();

        $.ajax({
            url: '', // la misma página
            type: 'GET',
            data: {buscar: busqueda},
            success: function(response) {
                // Extrae y reemplaza solo el contenido del tbody
                const newBody = $(response).find('#userList').html();
                $('#userList').html(newBody);

                // Activa la pestaña de listado
                var tab = new bootstrap.Tab(document.querySelector('#registro-tab'));
                tab.show();
            }
        });
    });

    $('#clearSearch').click(function(){
        $('#searchInput').val('');
        $('#searchForm').submit(); // reinicia la búsqueda
    });
});
</script>

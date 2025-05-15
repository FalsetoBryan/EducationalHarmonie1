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
        .estado-resuelta {
            background-color: #d4edda;
            color: #155724;
        }
        .estado-pendiente {
            background-color: #fff3cd;
            color: #856404;
        }
        .estado-en-curso {
            background-color: #cce5ff;
            color: #004085;
        }
    </style>
</head>
<body>
<div class="container py-4">
    <h4 class="text-center mb-4">Listado De Quejas</h4>
    
    <div class="text-center mb-4">
        <a href="../docente.php" class="btn btn-secondary">Regresar al Menú</a>
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
                    <th>Estado</th>
                    <th>Fecha Registro</th>
                    <th>Acciones</th>
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
                        // Determinar la clase CSS según el estado
                        $estado_clase = '';
                        switch($row["estado"]) {
                            case 'Resuelta':
                                $estado_clase = 'estado-resuelta';
                                break;
                            case 'Pendiente':
                                $estado_clase = 'estado-pendiente';
                                break;
                            case 'En curso':
                                $estado_clase = 'estado-en-curso';
                                break;
                            default:
                                $estado_clase = 'estado-pendiente';
                        }
                        
                        echo "<tr>
                                <td>".$row["id_reporte"]."</td>
                                <td>".$row["id_estudiante"]."</td>
                                <td>".$row["fecha"]."</td>
                                <td>".$row["descripcion"]."</td>
                                <td>".$row["detalle"]."</td>
                                <td class='$estado_clase'>".($row["estado"] ?? 'Pendiente')."</td>
                                <td>".$row["fecha_registro"]."</td>
                                <td>
                                    <div class='btn-group'>
                                        <button class='btn btn-sm btn-success cambiar-estado' data-id='".$row["id_reporte"]."' data-estado='Resuelta'>Resuelta</button>
                                        <button class='btn btn-sm btn-warning cambiar-estado' data-id='".$row["id_reporte"]."' data-estado='En curso'>En curso</button>
                                        <button class='btn btn-sm btn-danger cambiar-estado' data-id='".$row["id_reporte"]."' data-estado='Pendiente'>Pendiente</button>
                                    </div>
                                </td>
                              </tr>";
                    }
                } else {
                    echo "<tr><td colspan='8' class='text-center'>No hay reportes registrados</td></tr>";
                }
                $conn->close();
                ?>
            </tbody>
        </table>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Función para cambiar el estado de la queja
    document.querySelectorAll('.cambiar-estado').forEach(button => {
        button.addEventListener('click', function() {
            const idReporte = this.getAttribute('data-id');
            const nuevoEstado = this.getAttribute('data-estado');
            
            // Enviar la actualización al servidor
            fetch('actualizar_estado.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `id_reporte=${idReporte}&estado=${encodeURIComponent(nuevoEstado)}`
            })
            .then(response => response.text())
            .then(data => {
                if(data === 'success') {
                    // Actualizar la interfaz
                    const fila = this.closest('tr');
                    const celdaEstado = fila.querySelector('td:nth-child(6)');
                    
                    // Cambiar la clase según el estado
                    celdaEstado.className = '';
                    if(nuevoEstado === 'Resuelta') {
                        celdaEstado.classList.add('estado-resuelta');
                    } else if(nuevoEstado === 'Pendiente') {
                        celdaEstado.classList.add('estado-pendiente');
                    } else if(nuevoEstado === 'En curso') {
                        celdaEstado.classList.add('estado-en-curso');
                    }
                    
                    celdaEstado.textContent = nuevoEstado;
                    alert('Estado actualizado correctamente');
                } else {
                    alert('Error al actualizar el estado');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error al comunicarse con el servidor');
            });
        });
    });
</script>
</body>
</html>
<?php
include 'conecta.php';

$sql = "SELECT u.id_usuario, u.nombre, u.apellidop, u.apellidom, u.usuario, u.correo, u.curso, c.cargo 
        FROM usuarios u
        LEFT JOIN cargo c ON u.idcargo = c.id_cargo
        ORDER BY u.id_usuario DESC";

$resultado = $conecta->query($sql);

if ($resultado->num_rows > 0): ?>
    <table class="table table-bordered table-hover">
        <thead class="thead-dark">
            <tr>
                <th>ID</th>
                <th>Nombre Completo</th>
                <th>Usuario</th>
                <th>Correo</th>
                <th>Curso</th>
                <th>Cargo</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($fila = $resultado->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $fila['id_usuario']; ?></td>
                    <td><?php echo $fila['nombre'] . ' ' . $fila['apellidop'] . ' ' . $fila['apellidom']; ?></td>
                    <td><?php echo $fila['usuario']; ?></td>
                    <td><?php echo $fila['correo']; ?></td>
                    <td><?php echo $fila['curso']; ?></td>
                    <td><?php echo $fila['cargo']; ?></td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
<?php else: ?>
    <div class="alert alert-info">No hay usuarios registrados.</div>
<?php endif; ?>


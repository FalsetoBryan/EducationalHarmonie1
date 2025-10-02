<?php
include "conexion.php";

$cursos_por_pagina = 50;
$pagina = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
$inicio = ($pagina - 1) * $cursos_por_pagina;

$total_resultado = $conecta->query("SELECT COUNT(*) AS total FROM cursos");
$total_fila = $total_resultado->fetch_assoc();
$total_cursos = $total_fila['total'];
$total_paginas = ceil($total_cursos / $cursos_por_pagina);

$consulta = "SELECT * FROM cursos ORDER BY id_curso DESC LIMIT $inicio, $cursos_por_pagina";
$guardar = $conecta->query($consulta);
?>

<table class="table">
    <thead class="text-muted">
        <tr>
            <th class="text-center">Código Del Curso</th>
            <th class="text-center">Nombre Del Curso</th>
            <th class="text-center">Grado Escolar</th>
            <th class="text-center">Docente</th>
            <th class="text-center">Líder Del Curso</th>
            <th class="text-center">Opciones</th>
        </tr>
    </thead>
    <tbody>
        <?php while ($row = $guardar->fetch_assoc()): ?>
            <tr>
                <td><?php echo $row['id_curso']; ?></td>
                <td><?php echo $row['nombre_curso']; ?></td>
                <td><?php echo $row['grado_escolar']; ?></td>
                <td><?php echo $row['docente']; ?></td>
                <td><?php echo $row['lider']; ?></td>
                <td>
                    <a href="editar_curso.php?idcurso=<?php echo $row['id_curso']; ?>" class="btn btn-warning btn-sm">Editar</a>
                    <a href="borrar_curso.php?idcurso=<?php echo $row['id_curso']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('¿Estás seguro de que deseas borrar este curso?');">Borrar</a>
                </td>
            </tr>
        <?php endwhile; ?>
    </tbody>
</table>

<div class="d-flex justify-content-between">
    <?php if ($pagina > 1): ?>
        <button class="btn btn-outline-primary paginacion-btn" data-pagina="<?php echo $pagina - 1; ?>">Anterior</button>
    <?php else: ?>
        <button class="btn btn-outline-secondary" disabled>Anterior</button>
    <?php endif; ?>

    <?php if ($pagina < $total_paginas): ?>
        <button class="btn btn-outline-primary paginacion-btn" data-pagina="<?php echo $pagina + 1; ?>">Siguiente</button>
    <?php else: ?>
        <button class="btn btn-outline-secondary" disabled>Siguiente</button>
    <?php endif; ?>
</div>
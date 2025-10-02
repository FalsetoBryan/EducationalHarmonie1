<?php
include "conexion.php";

// Solo para redirección o validaciones, no es necesario mostrar datos aquí
?>
<!DOCTYPE html>
<html lang="es" dir="ltr">
<head>
    <meta charset="UTF-8">
    <title>Gestión de Cursos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-image: url('logo/logo.jpg');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
        }
        .container {
            background-color: rgba(255, 255, 255, 0.85);
            padding: 20px;
            border-radius: 10px;
        }
    </style>
</head>
<body>
<div class="container py-4">
    <h3>Gestión De Cursos</h3>
    <ul class="nav nav-tabs" id="myTab" role="tablist">
        <li class="nav-item"><a class="nav-link active" id="registro-tab" data-bs-toggle="tab" href="#registro" role="tab">Registro</a></li>
        <li class="nav-item"><a class="nav-link" id="listado-tab" data-bs-toggle="tab" href="#listado" role="tab">Listado</a></li>
    </ul>

    <div class="tab-content">
        <!-- Registro -->
        <div class="tab-pane fade show active" id="registro" role="tabpanel">
            <h3 class="text-center">Registro de Cursos</h3>
            <?php include 'registro_cursos.php'; ?>
        </div>

        <!-- Listado -->
        <div class="tab-pane fade" id="listado" role="tabpanel">
            <h3 class="text-center">Listado De Cursos</h3>
            <div id="contenedor-cursos" class="table-responsive table-hover">
                <!-- Aquí se carga vía AJAX -->
            </div>
        </div>
    </div>

    <div class="text-center mt-4">
        <a href="../admin.php" class="btn btn-primary">Regresar al menú</a>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    cargarCursos(1);

    function cargarCursos(pagina) {
        const contenedor = document.getElementById('contenedor-cursos');
        contenedor.innerHTML = '<div class="text-center my-3">Cargando...</div>';
        fetch(`cursos_ajax.php?pagina=${pagina}`)
            .then(res => res.text())
            .then(html => {
                contenedor.innerHTML = html;
                agregarListeners();
            });
    }

    function agregarListeners() {
        document.querySelectorAll('.paginacion-btn').forEach(btn => {
            btn.addEventListener('click', function () {
                const pagina = this.getAttribute('data-pagina');
                cargarCursos(pagina);
            });
        });
    }
});
</script>
</body>
</html>

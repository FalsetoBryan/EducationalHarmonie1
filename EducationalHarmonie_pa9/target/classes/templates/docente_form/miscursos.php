<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Cursos y Horarios - Educational Harmonie</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f0f8ff;
            color: #143C5F;
        }
        .header-bar {
            background-color: #143C5F;
            color: white;
            padding: 15px 0;
            text-align: center;
        }
        .sidebar {
            background-color: white;
            width: 250px;
            padding: 20px;
            height: 100vh;
            position: fixed;
        }
        .sidebar a {
            color: #143C5F;
            font-size: 18px;
            text-decoration: none;
            display: block;
            padding: 12px;
            margin-bottom: 15px;
            background-color: #c6e3f7;
            border-radius: 5px;
        }
        .sidebar a:hover {
            background-color: #70b0e0;
            color: white;
        }
        .content-area {
            margin-left: 270px;
            padding: 20px;
        }
        .content-area h2 {
            font-size: 24px;
            color: #143C5F;
        }
        .table-container {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>

<!-- Barra de encabezado -->
<div class="header-bar">
    <h1>Gestión de Cursos y Horarios</h1>
</div>

<div class="d-flex">
    <!-- Sidebar de navegación -->
    <div class="sidebar">
        <a href="#cursos" class="btn btn-primary">
            <i class="fas fa-book"></i> Mis Cursos
        </a>
        <a href="#clases" class="btn btn-primary">
            <i class="fas fa-chalkboard"></i> Mis Clases
        </a>
        <a href="#horarios" class="btn btn-primary">
            <i class="fas fa-calendar-alt"></i> Mis Horarios
        </a>
        <a href="../docente.php" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Regresar al Menú Principal
        </a>
    </div>

    <!-- Área de contenido -->
    <div class="content-area">
        <!-- Sección de Cursos -->
        <div id="cursos" class="table-container">
            <h2>Mis Cursos</h2>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Curso</th>
                        <th>Materia</th>
                        <th>Estudiantes</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>6° A</td>
                        <td>Matemáticas</td>
                        <td>25</td>
                    </tr>
                    <tr>
                        <td>6° B</td>
                        <td>Ciencias Naturales</td>
                        <td>28</td>
                    </tr>
                    <tr>
                        <td>7° A</td>
                        <td>Física</td>
                        <td>22</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Sección de Clases -->
        <div id="clases" class="table-container">
            <h2>Mis Clases</h2>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Fecha</th>
                        <th>Curso</th>
                        <th>Materia</th>
                        <th>Estado</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>2024-11-20</td>
                        <td>6° A</td>
                        <td>Matemáticas</td>
                        <td>Finalizada</td>
                    </tr>
                    <tr>
                        <td>2024-11-21</td>
                        <td>6° B</td>
                        <td>Ciencias Naturales</td>
                        <td>En progreso</td>
                    </tr>
                    <tr>
                        <td>2024-11-22</td>
                        <td>7° A</td>
                        <td>Física</td>
                        <td>Por iniciar</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Sección de Horarios -->
        <div id="horarios" class="table-container">
            <h2>Mis Horarios</h2>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Día</th>
                        <th>Hora</th>
                        <th>Curso</th>
                        <th>Materia</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Lunes</td>
                        <td>8:00 - 9:30</td>
                        <td>6° A</td>
                        <td>Matemáticas</td>
                    </tr>
                    <tr>
                        <td>Martes</td>
                        <td>10:00 - 11:30</td>
                        <td>6° B</td>
                        <td>Ciencias Naturales</td>
                    </tr>
                    <tr>
                        <td>Miércoles</td>
                        <td>1:00 - 2:30</td>
                        <td>7° A</td>
                        <td>Física</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

</body>
</html>

puedes hacer que ese formulario se muestre en la misma pagina sin cargar otra, tiene que mostrarse aqui mismo al presionar el boton crear reportes
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Docente - Educational Harmonie</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #C6DFF3;
            color: #143C5F;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
        }
        .header-bar {
            background-color: #143C5F;
            color: white;
            padding: 15px 20px;
            font-size: 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: fixed;
            width: 100%;
            top: 0;
            z-index: 1000;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        .header-title {
            font-weight: bold;
            margin: 0;
        }
        .profile-section {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .logout-btn {
            color: white;
            text-decoration: none;
            font-weight: bold;
            font-size: 14px;
        }
        .logout-btn:hover {
            text-decoration: underline;
        }
        .sidebar {
            background-color: white;
            width: 260px;
            padding: 20px;
            height: 100vh;
            position: fixed;
            top: 65px;
            left: 0;
            border-right: 1px solid #2878BD;
            box-shadow: 2px 0 5px rgba(0,0,0,0.05);
        }
        .sidebar .logo {
            width: 100%;
            max-height: 100px;
            object-fit: contain;
            margin-bottom: 15px;
        }
        .sidebar .welcome {
            font-weight: bold;
            font-size: 16px;
            color: #2878BD;
            text-align: center;
            margin-bottom: 25px;
        }
        .sidebar a {
            color: #143C5F;
            font-size: 16px;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 12px 15px;
            margin-bottom: 15px;
            background-color: #E8F1F8;
            border-radius: 5px;
            border: 1px solid #B2CDE6;
            transition: background-color 0.2s;
        }
        .sidebar a:hover {
            background-color: #D4E4F2;
        }
        .content-area {
            margin-left: 280px;
            padding: 90px 30px 30px;
            min-height: 100vh;
            background-color: #C6DFF3;
            position: relative;
            z-index: 1;
        }
        .watermark {
            position: absolute;
            top: 55%;
            left: 60%;
            transform: translate(-50%, -50%);
            opacity: 0.2;
            pointer-events: none;
            z-index: 0;
        }
        .watermark img {
            max-width: 80%;
            height: auto;
        }
        .form-container {
            background-color: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            margin-bottom: 30px;
        }
        .table-container {
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .action-btns .btn {
            margin-right: 5px;
        }
        .badge.bg-warning {
            color: #000;
        }
        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        #info-estudiante {
            transition: all 0.3s ease;
        }
        .loading-spinner {
            padding: 10px;
            text-align: center;
        }
    </style>
</head>
<body>

<!-- Marca de agua -->
<div class="watermark">
    <img src="../static/img/logo.JPG.jpg" alt="Logo de Educational Harmonie">
</div>

<!-- Barra superior -->
<div class="header-bar">
    <div class="header-title">PANEL DOCENTE</div>
    <div class="profile-section">
        <a href="logout.php" class="logout-btn"><i class="fas fa-sign-out-alt"></i> CERRAR SESIÓN</a>
    </div>
</div>

<!-- Sidebar -->
<div class="sidebar">
    <img src="../static/img/logo.JPG.jpg" alt="Logo" class="logo">
    <div class="welcome">BIENVENIDOS A EDUCATIONAL HARMONIE</div>
    <a href="./docente_form/form_reporte.php" onclick=""><i class="fas fa-file-alt"></i> Crear Reportes</a>
    <a href="./docente_form/quejas_acud.php" onclick="mostrarQuejasAcudientes()"><i class="fas fa-comment-dots"></i> Quejas de Acudientes</a>
    <a href="#" onclick="mostrarHistorialReportes()"><i class="fas fa-history"></i> Historial de Reportes</a>
</div>

<!-- Área de contenido -->
<div class="content-area" id="content-area">
    <!-- Contenido inicial -->
    <div class="welcome-message">
        <h2>Bienvenido al Panel Docente</h2>
        <p>Seleccione una opción del menú para comenzar.</p>
    </div>
    
    <!-- Formulario de reportes (oculto inicialmente) -->
    <div id="reportes-form" style="display: none;" class="form-container">
        <h2>Formulario de Reportes</h2>
        <div id="form-message"></div>
        <form id="form-reporte" method="POST" class="mt-4">
            <input type="hidden" name="action" value="save_reporte">
            <input type="hidden" name="estado" value="abierto">
            
            <div class="mb-3">
                <label for="id_estudiante" class="form-label">ID del Estudiante</label>
                <input type="text" class="form-control" id="id_estudiante" name="id_estudiante" required
                       pattern="S\d{6}" 
                       title="El ID debe comenzar con S seguido de 6 dígitos (ej: S000001)"
                       onblur="validarYBuscarEstudiante(this.value)">
                <div id="info-estudiante" class="mt-2 p-2 rounded" style="display: none;"></div>
            </div>
            
            <div class="mb-3">
                <label for="fecha" class="form-label">Fecha</label>
                <input type="date" class="form-control" id="fecha" name="fecha" required>
            </div>
            
            <div class="mb-3">
                <label for="descripcion" class="form-label">Descripción</label>
                <input type="text" class="form-control" id="descripcion" name="descripcion" required>
            </div>
            
            <div class="mb-3">
                <label for="detalle" class="form-label">Detalle</label>
                <textarea class="form-control" id="detalle" name="detalle" rows="5" required></textarea>
            </div>
            
            <button type="submit" class="btn btn-primary">Guardar Reporte</button>
        </form>
    </div>
    
    <!-- Quejas de acudientes (oculto inicialmente) -->
    <div id="quejas-acudientes" style="display: none;" class="table-container">
        <h2>Quejas Recibidas de Acudientes</h2>
        <div class="alert alert-info">
            <i class="fas fa-info-circle"></i> Estas son las quejas que los acudientes han enviado sobre sus estudiantes.
        </div>
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Acudiente</th>
                        <th>Estudiante</th>
                        <th>Fecha</th>
                        <th>Asunto</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody id="tablaQuejasAcudientes">
                    <!-- Las quejas se cargarán aquí dinámicamente -->
                </tbody>
            </table>
        </div>
    </div>
    
    <!-- Historial de reportes (oculto inicialmente) -->
    <div id="historial-reportes" style="display: none;" class="table-container">
        <h2>Historial de Reportes</h2>
        
        <!-- Estadísticas -->
        <div class="row mb-4">
            <div class="col-md-6">
                <div class="card text-white bg-primary mb-3">
                    <div class="card-body">
                        <h5 class="card-title">Reportes Abiertos</h5>
                        <p class="card-text display-4" id="contador-abiertos">0</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card text-white bg-success mb-3">
                    <div class="card-body">
                        <h5 class="card-title">Reportes Cerrados</h5>
                        <p class="card-text display-4" id="contador-cerrados">0</p>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Filtros -->
        <div class="d-flex justify-content-between mb-3">
            <div style="width: 60%;">
                <input type="text" class="form-control" id="searchInput" placeholder="Buscar...">
            </div>
            <div class="btn-group">
                <button class="btn btn-outline-primary" onclick="filtrarReportes('todos')">Todos</button>
                <button class="btn btn-outline-primary" onclick="filtrarReportes('abiertos')">Abiertos</button>
                <button class="btn btn-outline-primary" onclick="filtrarReportes('cerrados')">Cerrados</button>
            </div>
            <button class="btn btn-primary" onclick="loadReportes()">
                <i class="fas fa-sync-alt"></i> Actualizar
            </button>
        </div>
        
        <!-- Tabla de reportes -->
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Estudiante</th>
                        <th>Fecha</th>
                        <th>Descripción</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody id="tablaReportes">
                    <!-- Los reportes se cargarán aquí dinámicamente -->
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
// Variables globales
let currentView = 'welcome';



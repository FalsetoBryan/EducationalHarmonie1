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
    <a href="#" onclick="loadContent('../templates/docente_form/formulario.php')"><i class="fas fa-file-alt"></i> Crear Reportes</a>
    <a href="#" onclick="loadContent('quejas.php')"><i class="fas fa-comment-dots"></i> Quejas de Acudientes</a>
    <a href="#" onclick="loadContent('../templates/docente_form/historial_peticiones.php')"><i class="fas fa-history"></i> Historial de Reportes</a>
</div>

<!-- Área de contenido -->
<div class="content-area" id="content-area">
    <!-- Contenido dinámico -->
</div>

<!-- Script para AJAX -->
<script>
    function loadContent(url) {
        fetch(url)
            .then(response => {
                if (!response.ok) throw new Error("No se pudo cargar el contenido");
                return response.text();
            })
            .then(html => {
                document.getElementById('content-area').innerHTML = html;
            })
            .catch(error => {
                document.getElementById('content-area').innerHTML = "<p>Error al cargar el contenido.</p>";
                console.error("Error:", error);
            });
    }
</script>

</body>
</html>

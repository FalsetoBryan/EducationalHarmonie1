<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Administración </title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
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
            padding: 15px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            height: 70px;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
            box-shadow: 0 2px 4px rgba(0,0,0,0.2);
        }

        .header-title {
            font-size: 24px;
            font-weight: bold;
        }

        .header-user {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .profile-icon {
            font-size: 28px;
            color: white;
        }

        .header-user span {
            color: white;
            font-weight: 500;
        }

        .logout a {
            color: white;
            font-size: 14px;
            text-decoration: none;
        }

        .logout a:hover {
            color: #cccccc;
        }

        .sidebar {
            background-color: white;
            width: 280px;
            padding: 20px;
            border-right: 2px solid #B2CDE6;
            height: 100vh;
            position: fixed;
            top: 70px;
            left: 0;
            box-shadow: 2px 0 5px rgba(0,0,0,0.05);
        }

        .logo-container {
            text-align: center;
            margin-bottom: 20px;
        }

        .logo-container img {
            max-width: 60%;
            height: auto;
        }

        .welcome-text {
            font-size: 18px;
            color: #143C5F;
            text-align: center;
            margin-bottom: 30px;
            font-weight: bold;
        }

        .menu-item {
            font-size: 16px;
            color: #143C5F;
            padding: 15px;
            border: 1px solid #B2CDE6;
            background-color: #E8F1F8;
            border-radius: 5px;
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 15px;
            text-decoration: none;
            transition: background-color 0.2s;
        }

        .menu-item:hover {
            background-color: #D4E4F2;
        }

        .menu-icon {
            font-size: 20px;
        }

        .content-area {
            margin-left: 300px;
            margin-top: 90px;
            padding: 30px;
        }

        h2#tituloSeccion {
            font-weight: bold;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>

<!-- Header superior -->
<div class="header-bar">
    <div class="header-title">PANEL ADMINISTRADOR</div>
    <div class="header-user">
        <i class="fas fa-user-circle profile-icon"></i>
        <div class="logout">
            <a href="logout.php">
            <div><a href="logout.php" class="logout-btn"><i class="fas fa-sign-out-alt"></i> CERRAR SESIÓN</a></div>            </a>
        </div>
    </div>
</div>

<!-- Menú lateral -->
<div class="sidebar">
    <div class="logo-container">
        <img src="../static/img/logo.JPG.jpg" alt="Logo de Educational Harmonie" class="logo">
    </div>
    <div class="welcome-text">BIENVENIDOS A EDUCATIONAL HARMONIE</div>

    <a href="#" class="menu-item" onclick="cargarSeccion('gestion_usuarios.php', 'Gestión de Usuarios')">
        <i class="fas fa-user-plus menu-icon"></i>
        <span>Gestión de Usuarios</span>
    </a>

    <a href="gestion_grupos/cursos.php" class="menu-item">
        <i class="fas fa-graduation-cap menu-icon"></i>
        <span>Gestión de Grupos</span>
    </a>

    <a href="#" class="menu-item" onclick="mostrarNotificaciones()">
        <i class="fas fa-bell menu-icon"></i>
        <span>Notificaciones</span>
    </a>
</div>

<!-- Área de contenido dinámico -->
<div class="content-area">
    <h2 id="tituloSeccion"></h2>
    <div id="contenidoSeccion">
        <!-- Aquí se cargará contenido dinámicamente -->
    </div>
</div>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
function cargarSeccion(url, titulo) {
    document.getElementById("tituloSeccion").innerHTML = titulo;
    fetch(url)
        .then(response => response.text())
        .then(html => {
            const parser = new DOMParser();
            const doc = parser.parseFromString(html, 'text/html');
            const contenido = doc.querySelector('.container') || doc.body;
            document.getElementById("contenidoSeccion").innerHTML = contenido.innerHTML;

            let scripts = contenido.querySelectorAll("script");
            scripts.forEach(oldScript => {
                let newScript = document.createElement("script");
                if (oldScript.src) {
                    newScript.src = oldScript.src;
                } else {
                    newScript.innerHTML = oldScript.innerHTML;
                }
                document.body.appendChild(newScript);
            });
        })
        .catch(error => {
            console.error("Error al cargar la sección:", error);
            document.getElementById("contenidoSeccion").innerHTML = "<p>Error al cargar el contenido.</p>";
        });
}

function mostrarHistoriales() {
    cargarSeccion('historiales.php', 'Historiales');
}

function mostrarNotificaciones() {
    cargarSeccion('notificaciones.php', 'Notificaciones');
}
</script>

</body>
</html>

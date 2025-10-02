<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notificaciones - Educational Harmonie</title>
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
        .notification {
            background-color: #ffffff;
            border: 1px solid #c6e3f7;
            border-radius: 5px;
            padding: 15px;
            margin-bottom: 15px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .notification.read {
            background-color: #e8f5ff;
        }
        .notification button {
            margin-left: 10px;
        }
        .back-button {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>

<!-- Barra de encabezado -->
<div class="header-bar">
    <h1>Notificaciones</h1>
</div>

<div class="d-flex">
    <!-- Sidebar de navegación -->
    <div class="sidebar">
        <a href="#notificaciones" class="btn btn-primary">
            <i class="fas fa-bell"></i> Notificaciones
        </a>
    </div>

    <!-- Área de contenido -->
    <div class="content-area">
        <!-- Botón para regresar al menú principal -->
        <div class="back-button">
            <a href="../acudiente.php" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Regresar al Menú Principal
            </a>
        </div>

        <h2>Notificaciones</h2>
        <div id="notificaciones">
            <div class="notification">
                <span>¡Nueva actualización en el sistema!</span>
                <div>
                    <button class="btn btn-outline-success btn-sm" onclick="marcarLeida(this)">Marcar como leído</button>
                    <button class="btn btn-outline-danger btn-sm" onclick="eliminar(this)">Eliminar</button>
                </div>
            </div>
            <div class="notification">
                <span>Tu reporte del 2024-11-05 ha sido revisado.</span>
                <div>
                    <button class="btn btn-outline-success btn-sm" onclick="marcarLeida(this)">Marcar como leído</button>
                    <button class="btn btn-outline-danger btn-sm" onclick="eliminar(this)">Eliminar</button>
                </div>
            </div>
            <div class="notification read">
                <span>Tu queja ha sido resuelta. Revisa los detalles.</span>
                <div>
                    <button class="btn btn-outline-success btn-sm" disabled>Leído</button>
                    <button class="btn btn-outline-danger btn-sm" onclick="eliminar(this)">Eliminar</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function marcarLeida(button) {
        const notification = button.closest('.notification');
        notification.classList.add('read');
        button.disabled = true;
        button.innerText = 'Leído';
    }

    function eliminar(button) {
        const notification = button.closest('.notification');
        notification.remove();
    }
</script>

</body>
</html>

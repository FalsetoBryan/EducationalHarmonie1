<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte Estudiantil</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Fondo con opacidad */
        body {
            background-image: url('../logo/fondo_form.jpg');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        /* Contenedor principal */
        .main-container {
            background-color: rgba(255, 255, 255, 0.92);
            border-radius: 10px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
            width: 100%;
            max-width: 700px;
            padding: 2.5rem;
            margin: 20px auto;
        }

        /* Estilo del título */
        .form-title {
            color: #2c3e50;
            font-weight: 600;
            margin-bottom: 1.5rem;
            text-align: center;
            position: relative;
            padding-bottom: 10px;
        }

        .form-title:after {
            content: "";
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 80px;
            height: 3px;
            background: linear-gradient(to right, #3498db, #2ecc71);
        }

        /* Estilo de los campos del formulario */
        .form-control {
            border-radius: 6px;
            padding: 12px 15px;
            border: 1px solid #ddd;
            transition: all 0.3s;
        }

        .form-control:focus {
            border-color: #3498db;
            box-shadow: 0 0 0 0.25rem rgba(52, 152, 219, 0.25);
        }

        /* Estilo de los botones */
        .btn {
            border-radius: 6px;
            padding: 10px 20px;
            font-weight: 500;
            transition: all 0.3s;
        }

        .btn-primary {
            background-color: #3498db;
            border-color: #3498db;
        }

        .btn-primary:hover {
            background-color: #2980b9;
            border-color: #2980b9;
            transform: translateY(-2px);
        }

        .btn-secondary {
            background-color: #7f8c8d;
            border-color: #7f8c8d;
        }

        .btn-secondary:hover {
            background-color: #6c757d;
            border-color: #6c757d;
            transform: translateY(-2px);
        }

        /* Estilo del textarea */
        textarea.form-control {
            min-height: 120px;
            resize: vertical;
        }

        /* Efecto de hover para los campos */
        .form-group {
            margin-bottom: 1.5rem;
            transition: all 0.3s;
        }

        .form-group:hover label {
            color: #3498db;
        }

        /* Responsividad */
        @media (max-width: 768px) {
            .main-container {
                padding: 1.5rem;
            }
        }
    </style>
</head>
<body>
<div class="main-container">
    <h4 class="form-title">Formulario de Reporte Estudiantil</h4>

    <!-- Botón para regresar al menú -->
    <div class="text-center mb-4">
        <a href="../docente.php" class="btn btn-secondary mb-4">Regresar al Menú</a>
    </div>

    <!-- Formulario para registrar un reporte -->
    <form action="procesar_reporte.php" method="POST" id="formReporte">
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="id_estudiante" class="form-label">ID del Estudiante:</label>
                    <input type="text" class="form-control" id="id_estudiante" name="id_estudiante" required>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="form-group">
                    <label for="fecha" class="form-label">Fecha del Reporte:</label>
                    <input type="date" class="form-control" id="fecha" name="fecha" required>
                </div>
            </div>
        </div>

        <div class="form-group">
            <label for="descripcion" class="form-label">Descripción del Reporte:</label>
            <input type="text" class="form-control" id="descripcion" name="descripcion" required>
        </div>

        <div class="form-group">
            <label for="detalle" class="form-label">Detalles del Reporte:</label>
            <textarea class="form-control" id="detalle" name="detalle" rows="5" required></textarea>
        </div>

        <div class="text-center mt-4">
            <button type="submit" class="btn btn-primary btn-lg">Registrar Reporte</button>
        </div>
    </form>

    <!-- Mensaje de éxito o error -->
    <div id="mensaje" class="mt-4"></div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function(){
    // Manejo del envío del formulario de forma AJAX
    $('#formReporte').submit(function(e){
        e.preventDefault(); // Prevenir el envío tradicional del formulario
        
        var formData = $(this).serialize();  // Serializar los datos del formulario

        // Realizar la petición AJAX
        $.ajax({
            url: 'procesar_reporte.php',
            type: 'POST',
            data: formData,
            success: function(response) {
                // Mostrar el mensaje de éxito o error
                $('#mensaje').html('<div class="alert alert-success alert-dismissible fade show">' + 
                    response + 
                    '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>');
                
                // Limpiar los campos del formulario después de enviar
                $('#formReporte')[0].reset();
                
                // Desplazarse suavemente al mensaje
                $('html, body').animate({
                    scrollTop: $('#mensaje').offset().top - 100
                }, 500);
            },
            error: function(xhr, status, error) {
                $('#mensaje').html('<div class="alert alert-danger alert-dismissible fade show">Error al procesar el reporte: ' + 
                    error + 
                    '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>');
            }
        });
    });
});
</script>
</body>
</html>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buzón de Quejas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Establecer el logo como fondo */
        body {
            background-image: url('../logo/logo.JPG.jpg');  /* Asegúrate de que la ruta del logo sea correcta */
            background-size: 700px;  /* Hace que el logo ocupe todo el fondo */
            background-position: center;  /* Centra el logo en el fondo */
            background-repeat: no-repeat;  /* Evita que el fondo se repita */
            height: 100vh;  /* Hace que el fondo cubra toda la altura de la ventana */
        }

        /* Agregar un color de fondo semitransparente al contenedor para mejorar la legibilidad */
        .container {
            background-color: rgba(255, 255, 255, 0.8);  /* Fondo blanco semitransparente */
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0px 0px 15px rgba(0, 0, 0, 0.2);
        }

        h4 {
            color: #007bff;  /* Cambia el color del título a azul */
        }

        .btn-primary {
            background-color: #007bff;  /* Color azul para el botón */
            border-color: #007bff;
        }

        .btn-secondary {
            background-color: #6c757d;  /* Color gris para el botón */
            border-color: #6c757d;
        }
    </style>
</head>
<body>
<div class="container py-4">
    <h4 class="text-center mb-4">Formulario de Quejas</h4>

    <!-- Botón para regresar al menú -->
    <div class="text-center mb-4">
        <a href="acudiente.php" class="btn btn-secondary">Regresar al Menú</a>
    </div>

    <!-- Formulario para registrar una nueva queja -->
    <form action="procesar_queja.php" method="POST" id="formQueja">
        <div class="mb-3">
            <label for="fecha" class="form-label">Fecha:</label>
            <input type="date" class="form-control" id="fecha" name="fecha" required>
        </div>

        <div class="mb-3">
            <label for="descripcion" class="form-label">Descripción de la Queja:</label>
            <textarea class="form-control" id="descripcion" name="descripcion" rows="4" required></textarea>
        </div>

        <div class="mb-3">
            <label for="detalle" class="form-label">Detalles de la Queja:</label>
            <textarea class="form-control" id="detalle" name="detalle" rows="5" required></textarea>
        </div>

        <button type="submit" class="btn btn-primary">Enviar Queja</button>
    </form>

    <!-- Mensaje de éxito o error -->
    <div id="mensaje" class="mt-4"></div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function(){
    // Manejo del envío del formulario de forma AJAX para que no se recargue la página
    $('#formQueja').submit(function(e){
        e.preventDefault(); // Prevenir el envío tradicional del formulario
        
        var formData = $(this).serialize();  // Serializar los datos del formulario

        // Realizar la petición AJAX
        $.ajax({
            url: 'procesar_queja.php',  // El archivo que procesará la queja
            type: 'POST',
            data: formData,
            success: function(response) {
                // Mostrar el mensaje de éxito o error sin recargar la página
                $('#mensaje').html('<div class="alert alert-info">' + response + '</div>');
                
                // Limpiar los campos del formulario después de enviar
                $('#formQueja')[0].reset();
            }
        });
    });
});
</script>
</body>
</html>



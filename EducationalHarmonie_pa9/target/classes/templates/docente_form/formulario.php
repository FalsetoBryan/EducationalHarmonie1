<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulario de Peticiones</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container py-4">
    <h4 class="text-center mb-4">Formulario de Reportes</h4>

    <!-- Formulario para registrar una nueva petición -->
    <form action="guardar_peticion.php" method="post" id="formPeticion">
        <div class="mb-3">
            <label for="fecha" class="form-label">Fecha:</label>
            <input type="date" class="form-control" id="fecha" name="fecha" required>
        </div>

        <div class="mb-3">
            <label for="descripcion" class="form-label">Descripción del Problema:</label>
            <input type="text" class="form-control" id="descripcion" name="descripcion" required>
        </div>

        <div class="mb-3">
            <label for="detalle" class="form-label">Detalles de la Petición:</label>
            <textarea class="form-control" id="detalle" name="detalle" rows="5" required></textarea>
        </div>

        <button type="submit" class="btn btn-primary">Enviar Reporte</button>
    </form>

    <!-- Mensaje de éxito o error -->
    <div id="mensaje" class="mt-4"></div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function(){
    // Manejo del envío del formulario de forma AJAX para que no se recargue la página
    $('#formPeticion').submit(function(e){
        e.preventDefault(); // Prevenir el envío tradicional del formulario
        
        var formData = $(this).serialize();  // Serializar los datos del formulario

        // Realizar la petición AJAX
        $.ajax({
            url: 'guardar_peticion.php',  // El archivo que guardará la petición
            type: 'POST',
            data: formData,
            success: function(response) {
                // Mostrar el mensaje de éxito o error sin recargar la página
                $('#mensaje').html('<div class="alert alert-info">' + response + '</div>');
                
                // Limpiar los campos del formulario después de enviar
                $('#formPeticion')[0].reset();
            }
        });
    });
});
</script>
</body>
</html>






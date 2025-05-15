<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Califica el Sistema</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet"> <!-- Font Awesome para las estrellas -->
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
        }
        .container {
            max-width: 600px;
            margin: 50px auto;
            background-color: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .stars {
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 2rem;
            color: #ccc; /* Color por defecto de las estrellas */
            cursor: pointer;
        }
        .stars i {
            margin: 0 5px;
            transition: transform 0.2s ease, color 0.3s ease; /* Suavizar el cambio de color */
        }
        .stars i:hover {
            transform: scale(1.2);
            color: #007bff; /* Azul al pasar el puntero */
        }
        .stars i.active {
            color: #007bff; /* Azul cuando está seleccionada */
        }
        .stars i.inactive {
            color: #ccc; /* Gris cuando no está seleccionada */
        }
        .rating-text {
            text-align: center;
            margin-top: 20px;
            font-size: 1.2rem;
            font-weight: bold;
        }
        .submit-btn {
            display: block;
            margin: 30px auto 0;
            padding: 10px 20px;
            font-size: 1rem;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        .submit-btn:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>

<div class="container">
    <h2 class="text-center mb-4">Califica Nuestro Sistema</h2>

    <!-- Sección de estrellas -->
    <div class="stars" id="stars">
        <i class="fas fa-star inactive" data-index="1"></i>
        <i class="fas fa-star inactive" data-index="2"></i>
        <i class="fas fa-star inactive" data-index="3"></i>
        <i class="fas fa-star inactive" data-index="4"></i>
        <i class="fas fa-star inactive" data-index="5"></i>
    </div>

    <!-- Texto de la calificación seleccionada -->
    <div class="rating-text" id="ratingText">Selecciona tu calificación</div>

    <!-- Botón de enviar calificación -->
    <button class="submit-btn" id="submitBtn" disabled>Enviar Calificación</button>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
$(document).ready(function() {
    let selectedRating = 0;

    // Manejar el hover sobre las estrellas
    $('.stars i').hover(function() {
        const index = $(this).data('index');
        // Resaltar las estrellas hasta la estrella seleccionada
        $('.stars i').each(function(i) {
            $(this).toggleClass('active', i < index);
        });
        $('#ratingText').text(`Calificación: ${index} Estrella${index > 1 ? 's' : ''}`);
    }, function() {
        if (selectedRating === 0) {
            // Si no hay una selección, restaurar el color original
            $('.stars i').removeClass('active');
            $('#ratingText').text('Selecciona tu calificación');
        } else {
            // Si hay una selección, mantener la calificación seleccionada
            $('.stars i').each(function(i) {
                $(this).toggleClass('active', i < selectedRating);
            });
            $('#ratingText').text(`Calificación: ${selectedRating} Estrella${selectedRating > 1 ? 's' : ''}`);
        }
    });

    // Manejar el clic en las estrellas
    $('.stars i').click(function() {
        selectedRating = $(this).data('index');
        // Marcar la calificación seleccionada
        $('.stars i').each(function(i) {
            $(this).toggleClass('active', i < selectedRating);
        });
        $('#ratingText').text(`Calificación: ${selectedRating} Estrella${selectedRating > 1 ? 's' : ''}`);
        $('#submitBtn').prop('disabled', false);
    });

    // Manejar el envío de la calificación
    $('#submitBtn').click(function() {
        alert(`¡Gracias por calificar con ${selectedRating} estrella${selectedRating > 1 ? 's' : ''}!`);
        // Aquí puedes enviar la calificación a tu servidor o base de datos
        // Ejemplo: enviarCalificacion(selectedRating);
    });
});
</script>

</body>
</html>



<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Predicción de Reincidencia</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            background-color: #f5f7fa;
            background-image: url('logo.jpg');
            background-size: 40%;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
            position: relative;
        }

        body::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(245, 247, 250, 0.9);
            z-index: -1;
        }

        h1 {
            background-color: #007bff;
            color: white;
            padding: 20px;
            text-align: center;
            margin: 0;
            font-size: 22px;
            position: relative;
            z-index: 1;
        }

        .container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 30px;
            padding: 20px;
            margin: 20px auto;
            max-width: 1200px;
            position: relative;
            z-index: 1;
        }

        form {
            width: 500px;
            background-color: rgba(255, 255, 255, 0.95);
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            flex-shrink: 0;
            backdrop-filter: blur(2px);
        }

        .result-container {
            width: 400px;
            background-color: rgba(255, 255, 255, 0.95);
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            align-self: flex-start;
            position: sticky;
            top: 20px;
            backdrop-filter: blur(2px);
        }

        label {
            display: block;
            margin-top: 15px;
            font-weight: bold;
        }

        input[type="number"],
        select {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 14px;
            box-sizing: border-box;
        }

        input[type="submit"],
        .back-button,
        .history-button {
            width: 100%;
            padding: 12px;
            border: none;
            border-radius: 6px;
            font-size: 16px;
            cursor: pointer;
            margin-top: 15px;
            text-align: center;
            text-decoration: none;
            display: block;
            box-sizing: border-box;
        }

        input[type="submit"] {
            background-color: #28a745;
            color: white;
            margin-top: 25px;
        }

        input[type="submit"]:hover {
            background-color: #218838;
        }

        .back-button {
            background-color: #007bff;
            color: white;
        }

        .back-button:hover {
            background-color: #0056b3;
        }

        .history-button {
            background-color: #6c757d;
            color: white;
        }

        .history-button:hover {
            background-color: #5a6268;
        }

        .result-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .result-table th, .result-table td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: left;
        }

        .result-table th {
            background-color: #f2f2f2;
        }

        .result-table tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .prediction-high {
            color: #dc3545;
            font-weight: bold;
        }

        .prediction-low {
            color: #28a745;
            font-weight: bold;
        }

        .no-data {
            color: #6c757d;
            font-style: italic;
            text-align: center;
            padding: 20px;
        }

        /* Modal para el historial */
        .modal {
            display: none;
            position: fixed;
            z-index: 100;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0,0,0,0.4);
        }

        .modal-content {
            background-color: #fefefe;
            margin: 5% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            max-width: 900px;
            border-radius: 8px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.2);
            max-height: 80vh;
            overflow-y: auto;
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
        }

        .close:hover {
            color: black;
        }

        .history-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .history-table th, .history-table td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: left;
        }

        .history-table th {
            background-color: #007bff;
            color: white;
        }

        .history-table tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        @media (max-width: 1000px) {
            .container {
                flex-direction: column;
                align-items: center;
            }
            
            .result-container {
                position: static;
                width: 500px;
                margin-top: 20px;
            }
        }

        @media (max-width: 600px) {
            form, .result-container {
                width: 90%;
                padding: 20px;
            }
            
            body {
                background-size: 70%;
            }
            
            .modal-content {
                width: 95%;
                margin: 10% auto;
            }
        }
    </style>
</head>
<body>
    <h1>Formulario de Predicción de Reincidencia</h1>
    
    <div class="container">
        <form id="predictionForm">
            <label for="faltas">Número de Faltas:</label>
            <input type="number" name="faltas" id="faltas" required>
            
            <label for="promedio">Promedio Académico:</label>
            <input type="number" step="0.01" name="promedio" id="promedio" required>
            
            <label for="reincidencia">¿Ha reincidido anteriormente?</label>
            <select name="reincidencia" id="reincidencia" required>
                <option value="si">Sí</option>
                <option value="no">No</option>
            </select>

            <input type="submit" value="Predecir">

            <!-- Botón de Historial -->
            <button type="button" class="history-button" id="historyButton">Ver Historial de Resultados</button>

            <!-- Botones de Limpiar y Regresar -->
            <a href="index.php" class="back-button">Limpiar</a>
            <a href="http://localhost/EducationalHarmonie_pa7/target/classes/templates/" class="back-button">Regresar a la Vista Principal</a>
        </form>

        <div class="result-container">
            <h2>Resultado de la Predicción</h2>
            <div id="resultContent">
                <p class="no-data">Ingrese los datos y haga clic en "Predecir" para ver los resultados</p>
            </div>
        </div>
    </div>

    <!-- Modal para el historial -->
    <div id="historyModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Historial de Predicciones</h2>
            <div id="historyContent">
                <p class="no-data">No hay registros en el historial aún</p>
            </div>
        </div>
    </div>

    <script>
        // Verificar si el logo se carga correctamente
        window.addEventListener('load', function() {
            const img = new Image();
            img.src = 'logo.jpg';
            img.onerror = function() {
                console.error('Error: No se pudo cargar el logo. Verifica que:');
                console.error('1. El archivo logo.jpg exista en la misma carpeta que el HTML');
                console.error('2. El nombre del archivo sea exactamente "logo.jpg" (incluyendo mayúsculas/minúsculas)');
                console.error('3. El archivo no esté corrupto');
            };
        });

        // Almacenamiento del historial
        let predictionHistory = JSON.parse(localStorage.getItem('predictionHistory')) || [];
        
        document.getElementById('predictionForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Obtener los valores del formulario
            const faltas = document.getElementById('faltas').value;
            const promedio = document.getElementById('promedio').value;
            const reincidencia = document.getElementById('reincidencia').value;
            
            // Simular una predicción (en un caso real, aquí harías una llamada AJAX a tu backend)
            const probabilidad = simularPrediccion(faltas, promedio, reincidencia);
            const resultado = probabilidad > 0.6 ? "Alta probabilidad de reincidencia" : "Baja probabilidad de reincidencia";
            
            // Crear objeto con los datos de la predicción
            const predictionData = {
                fecha: new Date().toLocaleString(),
                faltas: faltas,
                promedio: promedio,
                reincidencia: reincidencia,
                probabilidad: probabilidad,
                resultado: resultado
            };
            
            // Agregar al historial
            predictionHistory.unshift(predictionData);
            
            // Guardar en localStorage (máximo 50 registros)
            if (predictionHistory.length > 50) {
                predictionHistory = predictionHistory.slice(0, 50);
            }
            localStorage.setItem('predictionHistory', JSON.stringify(predictionHistory));
            
            // Mostrar los resultados
            mostrarResultados(predictionData);
        });
        
        function simularPrediccion(faltas, promedio, reincidencia) {
            // Esta es una función de simulación - en la práctica, usarías tu modelo real
            let prob = 0;
            
            // Contribución de faltas (0 a 0.4)
            prob += Math.min(faltas / 20, 0.4);
            
            // Contribución del promedio (0 a 0.3)
            prob += (10 - promedio) * 0.03;
            
            // Contribución de reincidencia previa (0.3 si es sí)
            if (reincidencia === 'si') {
                prob += 0.3;
            }
            
            // Asegurarse que esté entre 0 y 1
            return Math.min(Math.max(prob, 0), 0.99);
        }
        
        function mostrarResultados(data) {
            const resultContent = document.getElementById('resultContent');
            
            // Formatear la probabilidad como porcentaje
            const porcentaje = (data.probabilidad * 100).toFixed(1) + '%';
            
            // Crear la tabla de resultados
            resultContent.innerHTML = `
                <table class="result-table">
                    <tr>
                        <th>Variable</th>
                        <th>Valor</th>
                    </tr>
                    <tr>
                        <td>Faltas ingresadas</td>
                        <td>${data.faltas}</td>
                    </tr>
                    <tr>
                        <td>Promedio académico</td>
                        <td>${data.promedio}</td>
                    </tr>
                    <tr>
                        <td>Reincidencia previa</td>
                        <td>${data.reincidencia === 'si' ? 'Sí' : 'No'}</td>
                    </tr>
                    <tr>
                        <td>Probabilidad estimada</td>
                        <td>${porcentaje}</td>
                    </tr>
                    <tr>
                        <td>Resultado</td>
                        <td class="${data.probabilidad > 0.6 ? 'prediction-high' : 'prediction-low'}">${data.resultado}</td>
                    </tr>
                </table>
                <div style="margin-top: 20px; padding: 10px; background-color: #f8f9fa; border-radius: 5px;">
                    <p><strong>Interpretación:</strong> ${obtenerInterpretacion(data.probabilidad)}</p>
                </div>
            `;
        }
        
        function obtenerInterpretacion(probabilidad) {
            if (probabilidad > 0.8) {
                return "Muy alta probabilidad de reincidencia. Se recomienda intervención inmediata.";
            } else if (probabilidad > 0.6) {
                return "Alta probabilidad de reincidencia. Se recomienda seguimiento cercano.";
            } else if (probabilidad > 0.4) {
                return "Probabilidad moderada. Se sugiere monitoreo periódico.";
            } else {
                return "Baja probabilidad de reincidencia. Continúe con el seguimiento regular.";
            }
        }
        
        // Manejo del modal de historial
        const modal = document.getElementById('historyModal');
        const btn = document.getElementById('historyButton');
        const span = document.getElementsByClassName('close')[0];
        
        btn.onclick = function() {
            mostrarHistorial();
            modal.style.display = "block";
        }
        
        span.onclick = function() {
            modal.style.display = "none";
        }
        
        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }
        
        function mostrarHistorial() {
            const historyContent = document.getElementById('historyContent');
            
            if (predictionHistory.length === 0) {
                historyContent.innerHTML = '<p class="no-data">No hay registros en el historial aún</p>';
                return;
            }
            
            let html = `
                <table class="history-table">
                    <thead>
                        <tr>
                            <th>Fecha</th>
                            <th>Faltas</th>
                            <th>Promedio</th>
                            <th>Reincidencia</th>
                            <th>Probabilidad</th>
                            <th>Resultado</th>
                        </tr>
                    </thead>
                    <tbody>
            `;
            
            predictionHistory.forEach(item => {
                const porcentaje = (item.probabilidad * 100).toFixed(1) + '%';
                html += `
                    <tr>
                        <td>${item.fecha}</td>
                        <td>${item.faltas}</td>
                        <td>${item.promedio}</td>
                        <td>${item.reincidencia === 'si' ? 'Sí' : 'No'}</td>
                        <td>${porcentaje}</td>
                        <td class="${item.probabilidad > 0.6 ? 'prediction-high' : 'prediction-low'}">${item.resultado}</td>
                    </tr>
                `;
            });
            
            html += `
                    </tbody>
                </table>
                <p style="text-align: right; margin-top: 10px; color: #6c757d;">
                    Mostrando ${predictionHistory.length} registros
                </p>
            `;
            
            historyContent.innerHTML = html;
        }
    </script>
</body>
</html>
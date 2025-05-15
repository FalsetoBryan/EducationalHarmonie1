<?php
require 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $faltas = $_POST['faltas'];
    $promedio = $_POST['promedio'];
    $reincidencia = $_POST['reincidencia'];

    chdir(__DIR__);

    $arffContent = "@relation reincidencia\n\n";
    $arffContent .= "@attribute faltas numeric\n";
    $arffContent .= "@attribute promedio numeric\n";
    $arffContent .= "@attribute reincidencia {si,no}\n\n";
    $arffContent .= "@data\n";
    $arffContent .= "$faltas,$promedio,$reincidencia\n";

    $fileName = 'temp.arff';
    file_put_contents($fileName, $arffContent);

    echo '<!DOCTYPE html>
    <html lang="es">
    <head>
        <meta charset="UTF-8">
        <title>Resultado de Predicción</title>
        <style>
            body {
                font-family: Arial, sans-serif;
                background-color: #f5f7fa;
                margin: 0;
                padding: 0;
            }
            .container {
                max-width: 700px;
                margin: 40px auto;
                background-color: white;
                padding: 30px;
                border-radius: 8px;
                box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            }
            .header {
                background-color: #007bff;
                color: white;
                padding: 20px;
                font-size: 22px;
                border-top-left-radius: 8px;
                border-top-right-radius: 8px;
                text-align: center;
            }
            h2 {
                color: #28a745;
            }
            .error {
                color: red;
            }
            pre {
                background-color: #f0f0f0;
                padding: 15px;
                border-radius: 6px;
                overflow-x: auto;
            }
            p {
                font-size: 16px;
                color: #333;
            }
            .back-btn {
                display: inline-block;
                margin-top: 20px;
                padding: 10px 20px;
                background-color: #007bff;
                color: white;
                text-decoration: none;
                border-radius: 6px;
            }
        </style>
    </head>
    <body>
    <div class="container">
        <div class="header">Resultado de la Predicción</div>';

    if (!file_exists($fileName)) {
        echo "<h2 class='error'>Error: No se pudo crear el archivo temp.arff.</h2></div></body></html>";
        exit;
    }

    $wekaPath = WEKA_PATH;
    if (!file_exists($wekaPath)) {
        echo "<h2 class='error'>Error: No se encontró el archivo weka.jar en la ruta especificada: $wekaPath</h2></div></body></html>";
        exit;
    }

    $command = "java -cp \"$wekaPath\" weka.classifiers.trees.J48 -l weka_model.model -T $fileName -p 0 -distribution 2>&1";
    exec($command, $outputLines, $returnCode);

    echo "<h3>Comando ejecutado:</h3><pre>$command</pre>";

    if ($returnCode !== 0) {
        echo "<h2 class='error'>Error al ejecutar Weka. Código de retorno: $returnCode</h2>";
        echo "<pre>" . implode("\n", $outputLines) . "</pre></div></body></html>";
        exit;
    }

    echo "<h3>Salida de Weka:</h3><pre>" . implode("\n", $outputLines) . "</pre>";

    $prediccionMostrada = false;
    foreach ($outputLines as $line) {
        if (preg_match('/^\s*\d+\s+\d+:(\w+)\s+\d+:(\w+)\s+[*]?([\d\.]+),([\d\.]+)/', $line, $matches)) {
            $actual = $matches[1];
            $predicted = $matches[2];
            $prob_si = floatval($matches[3]);
            $prob_no = floatval($matches[4]);

            $porcentaje = $predicted === "si" ? round($prob_si * 100, 2) : round($prob_no * 100, 2);

            echo "<h2>Predicción de Reincidencia: $predicted</h2>";
            echo "<p>El estudiante tiene un <strong>$porcentaje%</strong> de probabilidad de reincidir.</p>";
            $prediccionMostrada = true;
            break;
        }
    }

    if (!$prediccionMostrada) {
        echo "<h2 class='error'>Error: No se pudo interpretar la predicción.</h2>";
    }

    echo '<a href="index.php" class="back-btn">Regresar a la Vista Principal</a>';
    echo '</div></body></html>';
}
?>


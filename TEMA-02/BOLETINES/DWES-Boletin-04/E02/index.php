<?php
/*
 * ===== CORRECCIONES (revisión de las soluciones del tema 2) =====
 * - Se generaba una fila menos de las sorteadas ($fila < $numeroFilas): con rand() = 1 la tabla salía vacía.
 */
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DWES-PHP-B04-02</title>
</head>
<body>
    <div class="randoms">
        <pre>
<?php
    $numeroFilas = rand(1, 100);
    $primerNumeroFila = rand(1, 50);
    for ($fila = 1; $fila <= $numeroFilas; $fila++) {   // CORREGIDO: con < salía una fila menos (y ninguna si rand da 1)
        echo $primerNumeroFila . ' ';
        for ($elementoFila = 1; $elementoFila < $primerNumeroFila; $elementoFila++) {
            echo rand(1, 50) . " ";
        }
        echo '<br>';
        $primerNumeroFila = rand(1, 50);
    }
?>
        </pre>
    </div> 
</body>
</html>
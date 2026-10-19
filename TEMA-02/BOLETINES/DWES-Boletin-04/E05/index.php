<?php
/*
 * ===== CORRECCIONES (revisión de las soluciones del tema 2) =====
 * - Se rellenaba con ceros a la izquierda hasta 7 cifras; el enunciado no lo pide.
 */
    function separarEnDigitos(int $numero) {
        $salida = '';
        $cerosIzquierda = '';
        // CORREGIDO: se añadían ceros a la izquierda hasta tener 7 cifras ("0 9 6 6 0 1 3" para 966013);
        // el enunciado no los pide: 455334 ---> "4    5    5    3    3    4"

        while ($numero > 0) {
            $salida = (int)($numero % 10) . $salida;
            $numero = (int)($numero / 10);
            if ( $numero != 0 ) {
                $salida = '    ' . $salida;
            }
        }
        return $cerosIzquierda . $salida;
    }
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DWES-PHPB-B04-05</title>
</head>
<body>
    <pre>
<?php
    $numerosAGenerar = 10;
    $numero = random_int(9999, 1000000);

    for ($fila = 1; $fila <= $numerosAGenerar; $fila++) {
        echo separarEnDigitos($numero) . '<br>';
        $numero = random_int(9999, 1000000);
    }
?>
    </pre>      
</body>
</html>
<?php
/*
 * ===== CORRECCIONES (revisión de las soluciones del tema 2) =====
 * - XSS: el valor introducido se escribía sin escapar en value="..." (y el carácter erróneo en el mensaje).
 * - empty() trataba el binario "0" como campo vacío.
 * - Con un carácter no válido seguido de dígitos ("1a1") se sumaba un int al mensaje de error: TypeError.
 * - XSS: $_SERVER['PHP_SELF'] cambiado por $_SERVER['SCRIPT_NAME'].
 * Nota: el botón Reset sigue sin implementar a propósito (es el ejercicio que se deja al alumno).
 */
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DWES-PHP-B02-10b</title>
</head>
<body style="font-family: monospace">

    <form action="<?php echo $_SERVER["SCRIPT_NAME"]; ?>" method="get">

        <div class="entrada">
            <p>
                <label for="numeroBinario">Introduzca un número binario de 5 dígitos máximo:</label>
<?php
    $numeroDecimal = '';
    if ( !isset($_GET['numeroBinario']) ) {
       echo "<input type='text' id='numeroBinario' name='numeroBinario' />";
       echo "</p></div>";
    } else if ( $_GET['numeroBinario'] === "" ) {   // CORREGIDO: empty() trataba el binario "0" como vacío
        echo "<input type='text' id='numeroBinario' name='numeroBinario' />";
        echo "<span style='color: red'> <-- introduzca el número binario, por favor</span>";
        echo "</p></div>";
    } else {
        echo "<input type='text' id='numeroBinario' name='numeroBinario' value=\"" . htmlspecialchars($_GET['numeroBinario']) . "\" />";   // CORREGIDO: XSS
        echo "</p></div>";        
        $numeroBinario = $_GET['numeroBinario'];
        $numeroDecimal = 0;
        $longitudNumeroBinario = strlen($numeroBinario);

        if ($longitudNumeroBinario > 5) {
            $numeroDecimal = "<span style='color: red'>El número es demasiado largo: introduzca 5 dígitos binarios máximo, por favor</span>";
        } else {
            $numeroDecimal = 0;
            for($i = ($longitudNumeroBinario - 1); $i >= 0; $i--) {
                if ( $numeroBinario[$i] != "1" && $numeroBinario[$i] != "0" ) {
                    $numeroDecimal =  "<span style='color: red'>El número introducido tiene en su posición " . 
                         (( $longitudNumeroBinario - 1) - $i) . 
                         " el caracter inválido " . htmlspecialchars($numeroBinario[$i]) . "</span>";
                    break;   // CORREGIDO: sin el break, el siguiente dígito válido sumaba un int al mensaje (TypeError)
                } else {
                    $numeroDecimal += ($numeroBinario[$i] * (1<<(($longitudNumeroBinario - 1) - $i)));
                }
            }
        }
    }
?>
        <div class="salida">
            <p id="numeroDecimal">El número decimal correspondiente es:&nbsp;<?php echo "{$numeroDecimal}";?></p>
        </div> 
            <button id="botonEnviar">Enviar</button>      
            <button id="botonReset">Reset</button>                        <!-- ejercicio: falta implementar botón Reset -->
    </form>
</body>
</html>
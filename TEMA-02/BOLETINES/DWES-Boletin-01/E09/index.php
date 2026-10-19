<?php
/*
 * ===== CORRECCIONES (revisión de las soluciones del tema 2) =====
 * - El mensaje de error nunca salía: se comprobaba $_GET['botonEviar'] (errata).
 * - Separación: eran 3 espacios (el enunciado pide 4) y en HTML se juntan en uno: ahora 4 &nbsp;.
 * - Sin validación: "abc" -> TypeError (Fatal), "12.7" -> Deprecated y XSS en value="...".
 * - HTML: <p"> mal escrito.
 * - XSS: $_SERVER['PHP_SELF'] cambiado por $_SERVER['SCRIPT_NAME'].
 */
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DWES-PHP-B01-09</title>
</head>
<body style="font-family: monospace">

    <form action="<?php echo $_SERVER['SCRIPT_NAME'] ?>"  method="get">
<?php
    // CORREGIDO: no se validaba: "abc" -> TypeError, "12.7" -> Deprecated, HTML -> XSS en value="..."
    $entero = (isset($_GET['entero']) && preg_match("/^[+-]?\d{1,18}$/", $_GET['entero']) === 1) ? intval($_GET['entero']) : "";

    if( !empty($_GET['botonReset']) ) {
        $entero = ""; 
    }

    echo <<<MARCA
    <p>
        <label for="numero">Introduzca un entero positivo de 5 dígito máximo:</label>
        <input type="number"  id="numero" name="entero" value="{$entero}" />
    </p>
MARCA;

    if ( $entero === "" )  {
        if( !empty($_GET['botonEnviar']) ) {   // CORREGIDO: decía botonEviar
            echo '<p style="color: red">Introduzca el entero solicitado, por favor</p>';
        } else {
            echo '<p>&nbsp;</p>';
        }

        echo '<p id="digitos">&nbsp;</p>';
    } else {
       if ( $entero >= 0) {
            $sep = str_repeat("&nbsp;", 4);   // CORREGIDO: eran 3 espacios normales y el navegador los junta en uno
            $digitos = $entero % 10;
            $entero = floor($entero / 10);
            $digitos = ($entero % 10) . $sep . $digitos;
            $entero = floor($entero / 10);
            $digitos = ($entero % 10) . $sep . $digitos;
            $entero = floor($entero / 10);
            $digitos = ($entero % 10) . $sep . $digitos;
            $entero = floor($entero / 10);
            $digitos = ($entero % 10) . $sep . $digitos;
            $entero = floor($entero / 10);

            if ( $entero !== 0.0) {
                echo "<p id='digitos' style='color: red'>El número debe tener 5 dígitos o menos</p>";
            } else {
                echo "<p id='digitos'>Dígitos separados por 4 espacios: " . $digitos . "</p>";
            }
        } else {
            echo "<p id='digitos' style='color: red'>El número debe ser positivo</p>";
        }
    }
?>
        <button id="botonEnviar" type="submit" name="botonEnviar" value="Enviar">Enviar</button>      
        <button id="botonReset" type="submit" name="botonReset" value="Reset">Reset</button> 
    </form>
</body>
</html>
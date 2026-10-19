<?php
/*
 * ===== CORRECCIONES (revisión de las soluciones del tema 2) =====
 * - empty() trataba el 0 como campo vacío y no se validaba que fuesen enteros: "abc" -> TypeError (Fatal),
 *   "1.5" se aceptaba y el valor se escribía sin escapar en value="..." (XSS). Ahora enteros con preg_match.
 * - XSS: $_SERVER['PHP_SELF'] cambiado por $_SERVER['SCRIPT_NAME'].
 */
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DWES-PHP-B01-04</title>
</head>
<body style="font-family: monospace">

    <form action="<?php echo $_SERVER['SCRIPT_NAME'] ?>"  method="get">
<?php
    // Este es un ejemplo inicial y no estamos metiendo código para:
    //  - validar los números enteros
    //  - convertir explícitamente los string a enteros
    // CORREGIDO: empty() trataba el 0 como vacío y no se validaba: "abc" -> TypeError y XSS en value="..."
    $entero1 = (isset($_GET['entero1']) && preg_match("/^[+-]?\d{1,18}$/", $_GET['entero1']) === 1) ? intval($_GET['entero1']) : "";
    $entero2 = (isset($_GET['entero2']) && preg_match("/^[+-]?\d{1,18}$/", $_GET['entero2']) === 1) ? intval($_GET['entero2']) : "";
    $entero3 = (isset($_GET['entero3']) && preg_match("/^[+-]?\d{1,18}$/", $_GET['entero3']) === 1) ? intval($_GET['entero3']) : "";

    if( !empty($_GET['botonReset']) ) {
        $entero1 =  "";
        $entero2 =  "";
        $entero3 =  "";
    }    

    echo <<<MARCA
    <p>
        <label for="numero1">Introduzca primer entero:</label>
        <input type="number"  id="numero1" name="entero1" value="{$entero1}" />
    </p>
    <p>
        <label for="numero2">Introduzca segundo entero:</label>
        <input type="number"  id="numero2" name="entero2" value="{$entero2}"  />
    </p>
    <p>
        <label for="numero3">Introduzca tercer entero:</label>
        <input type="number" id="numero3" name="entero3" value="{$entero3}" />
    </p>
MARCA;

    if ( $entero1 === "" || $entero2 === "" || $entero3 === "" )  {
        if( !empty($_GET['botonEnviar']) ) {
            echo '<p style="color: red">Rellene todos los campos con números enteros, por favor</p>';
        } else {
            echo '<p>&nbsp;</p>';
        }
        echo <<<MARCA
        <p id="suma">&nbsp;</p>
        <p id="media">&nbsp;</p>
        <p id="producto">&nbsp;</p>
        <p id="mayor">&nbsp;</p>
        <p id="menor">&nbsp;</p>   
MARCA;
    } else {
        $mayor = $entero1;
        if ( $entero1 < $entero2 ) {
            $mayor = $entero2;
            if ( $entero2 < $entero3 ) {
                $mayor = $entero3;
            }
        } else {
            if ( $entero1 < $entero3 )
                $mayor = $entero3;
        }

        $menor = $entero1;
        if ( $entero2 < $entero1 ) {
            $menor = $entero2;
            if ( $entero3 < $entero2 ) {
                $menor = $entero3;
            }
        } else {
            if ( $entero3 < $entero1 )
                $menor = $entero3;
        }

        echo "<p id='suma'>La suma es: " . ($entero1 + $entero2 + $entero3)  . "</p>";
        echo "<p id='media'>La media es: " . ( ($entero1 + $entero2 + $entero3)/3 ) . "</p>";
        echo "<p id='producto'>El producto es: " . ($entero1 * $entero2 * $entero3)  . "</p>";
        echo "<p id='mayor'>El mayor es: " . $mayor  . "</p>";
        echo "<p id='menor'>El menor es: " . $menor  . "</p>";
    }
?>
        <button id="botonEnviar" type="submit" name="botonEnviar" value="Enviar">Enviar</button>      
        <button id="botonReset" type="submit" name="botonReset" value="Reset">Reset</button> 
    </form>
</body>
</html>
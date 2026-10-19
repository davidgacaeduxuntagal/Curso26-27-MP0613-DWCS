<?php
/*
 * ===== CORRECCIONES (revisión de las soluciones del tema 2) =====
 * - El campo oculto primeraVez solo se escribía la primera vez: en envíos alternos no salía el mensaje de error.
 * - XSS: $_SERVER['PHP_SELF'] cambiado por $_SERVER['SCRIPT_NAME'].
 */
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DWES-PHP-B03-04-S1a</title>
</head>
<body >

    <form action="<?php echo $_SERVER['SCRIPT_NAME'] ?>"  method="get">
<?php
    $entero = empty($_GET['entero']) ? "": intval($_GET['entero']);
    echo <<<MARCA
    <p>
        <label for="entero">Introduzca un entero impar positivo de 1 a 20:</label>
        <input type="number"  min="1" max="20" step="2"  id="entero" name="entero" value="{$entero}" />
    </p>
MARCA;
    // CORREGIDO: el campo oculto solo se escribía la primera vez, así que el tercer envío se tomaba otra vez
    // como "primera vez" y no salía el mensaje de error: se escribe siempre
    echo '<input type="hidden"  name="primeraVez" value="true" />';
    $segundaVez = isset($_GET['primeraVez']);

    if ($entero === "" || $entero < 1 || $entero > 20 || ($entero % 2 == 0))  {  // aunque el html se encarga de comprobar rango, no hace daño probar aquí
         if ($segundaVez) {
            echo <<<MARCA
            <p style="color: red">Introduzca el entero IMPAR solicitado (1-20), por favor</p>
            <p id="digitos">&nbsp;</p>
MARCA;
        }
    } else {
        echo "<pre style=\"font-family: monospace\">";
        $medio = floor($entero / 2) + 1;
        // Dibujar parte superior rombo:
        // NOTA: en vez de un espacio, imprimiré un "." (punto) para que puedas
        //  apreciar las diferencias entre distintas soluciones.
        // $anchoFila: número asteriscos desde medio del rombo hasta un lado 
        for ($anchoFila = 1; $anchoFila <= $medio; $anchoFila++) {
            for ($columna = 1; $columna <= $entero; $columna++) {
                if ( ($columna > $medio - $anchoFila) && ($columna < $medio + $anchoFila) ) {
                    echo '*';
                } else {
                    echo '.';
                }
            }
            echo '<br>';
        }

        // Dibujar parte inferior rombo:
        for ($anchoFila = $medio - 1 ; $anchoFila >= 1; $anchoFila--) {
            for ($columna = 1; $columna <= $entero; $columna++) {
                if ( ($columna > $medio - $anchoFila) && ($columna < $medio + $anchoFila ) ) {
                    echo '*';
                } else {
                    echo '.';
                }
            }
            echo '<br>';
        }        

        echo "</pre>";
    }
?>
        <button id="botonEnviar" type="submit">Enviar</button>      
        <button type="reset" ><a href="<?php echo $_SERVER['SCRIPT_NAME'] ?>" style="text-decoration: none;">Borrar</a></button>
    </form>
</body>
</html>
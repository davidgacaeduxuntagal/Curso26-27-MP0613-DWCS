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
    <title>DWES-PHP-B03-04-S6</title>
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
        $medio      = floor($entero / 2) + 1;
       
        // Dibujar rombo en una sola tirada:
        // NOTA: en vez de un espacio, imprimiré un "." (punto) para que puedas
        //  apreciar las diferencias entre distintas soluciones.
        $cadena = str_repeat(".", $medio -1) . "*";
        for ($fila = 2; $fila <= $entero + 1; $fila++) {
            echo $cadena . PHP_EOL;
            $cadena = $fila <= $medio ? (substr($cadena, 1) . "**") : ( "." . substr($cadena, 0, -2));
        }

        echo "</pre>";
    }
?>
        <button id="botonEnviar" type="submit">Enviar</button>      
        <button type="reset" ><a href="<?php echo $_SERVER['SCRIPT_NAME'] ?>" style="text-decoration: none;">Borrar</a></button>
    </form>
</body>
</html>
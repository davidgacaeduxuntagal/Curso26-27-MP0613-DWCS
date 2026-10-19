<?php
/*
 * ===== CORRECCIONES (revisión de las soluciones del tema 2) =====
 * - XSS: mensaje y código se escribían sin escapar en value='...' y en el resultado (el código puede
 *   contener < o comillas). Ahora htmlspecialchars().
 * - El campo oculto primeraVez solo se escribía la primera vez o con error.
 * - XSS: $_SERVER['PHP_SELF'] cambiado por $_SERVER['SCRIPT_NAME'].
 */
    // Implementación mejorada 
    // importante recordar que en codificación de caracteres UTF-8, hay caracteres que ocupan 1 byte, otros
    //  caracteres ocupan 2 bytes, etc: NO podemos recorrer la cadena de texto con indice de string 
    //  $cadena[$i] : hay que utilizar funciones Multibyte (mb) string
    function codificarMensaje (string $mensaje, string $codigo, string $cadenaReferencia): string {
        $cifrado  = "";
        $posicion = 0; 

        for($i = 0; $i < mb_strlen($mensaje); $i++ ) {    
            // Los espacios los dejamos tal cual:
            if ( mb_substr($mensaje, $i, 1) === " " ) {
                 $cifrado .= " ";    
            } else {     
                $posicion = mb_strpos($cadenaReferencia, mb_substr($mensaje, $i, 1));   // buscamos la posición del carácter de codificación 

                if ( $posicion === false ) {  // false = caracter no está en cadenaReferencia
                    return "Carácter en mensaje no soportado: '" . mb_substr($mensaje, $i, 1) . "' : PROCESO ABORTADO";
                } else if ( mb_substr($codigo, $posicion, 1) === "-" ) {
                    $cifrado .= mb_substr($mensaje, $i, 1);
                } else if ( mb_substr($codigo, $posicion, 1) === '.' ) {
                    ;
                } else {      
                    $cifrado .=  mb_substr($codigo, $posicion, 1);    
                }
            }      
        }
      
        return $cifrado;   
    }

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DWES-PHP-B05-01b</title>
    <style>
        .monoespacio { font-family: monospace; }
    </style>
</head>
<body>
    <form action="<?php echo $_SERVER['SCRIPT_NAME'] ?>"  method="get">
<?php
    $cadenaReferencia = 'abcdefghijklmnñopqrstuvwxyzABCDEFGHIJKLMNÑOPQRSTUVWXYZ0123456789';
                    
    // CORREGIDO: el campo oculto solo se escribía la primera vez (o con error): se escribe siempre
    echo '<input type="hidden"  name="primeraVez" value="true" />';
    $segundaVez = isset($_GET['primeraVez']);

    if ( !isset($_GET['codigo']) ) {
        $codigo  = 'fkdjfklhgosidfowheoihfosfdlkfjlkdfjoiwejf2343sd987238479lkdjflk4';
    } else {
        $codigo = $_GET['codigo'];
    }

    if ( empty($_GET['mensaje']) )  {  
        if ($segundaVez) {
            echo <<<MARCA
            <p style="color: red">Rellene el campo mensaje, por favor</p>
MARCA;
        }   
    } 
    
    $mensaje = empty($_GET['mensaje']) ? '' : $_GET['mensaje'];

    // CORREGIDO: XSS: mensaje y código se escribían sin escapar en value='...' (con comilla simple basta
    // un apóstrofo para salir del atributo): htmlspecialchars con ENT_QUOTES
    $mensajeHtml = htmlspecialchars($mensaje, ENT_QUOTES);
    $codigoHtml  = htmlspecialchars($codigo, ENT_QUOTES);


    echo <<<MARCA
        <p>
        <label for="mensaje">Mensaje:
            <input type="text" id="mensaje" size="100" name="mensaje" value='{$mensajeHtml}' class="monoespacio"/>
        </label>
        </p>
        <p class="monoespacio">
        <label for="codigo">Código: 
            <input type="text" id="codigo" size="100" name="codigo" value='{$codigoHtml}' class="monoespacio"/>
        </label><br>
        <label for="cadenaReferencia">Refer.: 
            <input type="text" id="cadenaReferencia" size="100" name="cadenaReferencia" value='{$cadenaReferencia}' readonly  class="monoespacio"/>
        </label>
        </p>
    </div>
MARCA;
 
 
   if ( !empty($_GET['mensaje']) && (mb_strlen($codigo) !== mb_strlen($cadenaReferencia)) )  {  
        echo <<<MARCA
        <p class="monoespacio" style="color: red">La cadena código debe tener la misma longitud que la de Refer., por favor</p>
MARCA;
    } else {
        echo '<p class="monoespacio" style="color: blue">El mensaje codificado es: ' . htmlspecialchars(codificarMensaje($mensaje, $codigo, $cadenaReferencia)) . "</p>";   // CORREGIDO: XSS
    }
?>
        <button id="botonEnviar" type="submit">Enviar</button>      
        <button type="reset" ><a href="<?php echo $_SERVER['SCRIPT_NAME'] ?>" style="text-decoration: none;">Borrar</a></button>    </form>
</body>
</html>
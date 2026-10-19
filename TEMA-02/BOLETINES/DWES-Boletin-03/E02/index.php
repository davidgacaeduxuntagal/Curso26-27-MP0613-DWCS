<?php
/*
 * ===== CORRECCIONES (revisión de las soluciones del tema 2) =====
 * - La validación no funcionaba: is_int(floatval(...)) siempre es false, así que "abc" o -5 daban suma 0
 *   sin aviso, 12.5 se aceptaba y 1e10 agotaba el tiempo de ejecución (Fatal error).
 * - El mensaje de error decía "entre 1 y 100" (copiado del ejercicio 7).
 * - Faltaba cerrar el </form>.
 * - XSS: $_SERVER['PHP_SELF'] cambiado por $_SERVER['SCRIPT_NAME'].
 */
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DWES-PHP-B03-E02</title>
</head>
<body >
    <form action="<?php echo $_SERVER['SCRIPT_NAME'] ?>"  method="get">
<?php 
    // CORREGIDO: con floatval() is_int() nunca era cierto y no se validaba nada ("abc" sumaba hasta 0,
    // 12.5 se aceptaba y 1e10 agotaba el tiempo de ejecución). Entero positivo de 7 cifras como mucho.
    $entero = (isset($_GET['entero']) && preg_match("/^\d{1,7}$/", $_GET['entero']) === 1) ? intval($_GET['entero']) : "";
    $primeraVez   = empty($_GET['primeraVez'])   ? "": $_GET['primeraVez'];

     echo <<<MARCA
     <div class="entradas">
     <h1>Múltiplos de un número</h1>
     <p>
         <label for="entero">Introduzca el número tope hasta el que llegar:</label>
         <input type="number" id="entero" name="entero"  value="{$entero}"/>
     </p>
  
     <button id="botonEnviar" type="submit">Enviar</button>      
     <button type="reset" ><a href="{$_SERVER['SCRIPT_NAME']}" style="text-decoration: none;">Borrar</a></button>
MARCA;
    if ( $entero === "" || $entero < 1 )  {
        if ( $primeraVez === "" ) {
            echo <<<PRIMERAVEZ
                 <input type="hidden" name="primeraVez" value="false"/>
            </div>    
PRIMERAVEZ;
        } else {         
            echo <<<SEGUNDAVEZ
                 <input type="hidden" name="primeraVez" value="false"/>
            </div>   
            <div>
                <p id="salida" style="color: red">Por favor, introduzca un número entero positivo (7 cifras como máximo)</p>
            </div> 
SEGUNDAVEZ;
        }
    } else {
        echo <<<DATOSOK
        <input type="hidden" name="primeraVez" value="false"/>
   </div>   
DATOSOK;         
    echo "<pre style='font-family: monospace'>";
    $suma = 0;

    for($i = 7; $i <= $entero; $i += 7) {
        $suma +=  $i;
    }

    echo "La suma de todos los múltiplos de 7 de 1 hasta {$entero} es: {$suma}";
    echo "</pre>";
}
?>    
    </form>   <!-- CORREGIDO: faltaba cerrar el formulario -->
</body>
</html>
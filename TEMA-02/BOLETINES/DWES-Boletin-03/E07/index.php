<?php
/*
 * ===== CORRECCIONES (revisión de las soluciones del tema 2) =====
 * - La validación no funcionaba (is_int(floatval(...)) siempre es false): "abc" o 0.5 -> DivisionByZeroError
 *   (Fatal error) y 150 o -9 se aceptaban. Ahora entero de 1 a 100.
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
    <title>DWES-PHP-B03-E07</title>
</head>
<body >
    <form action="<?php echo $_SERVER['SCRIPT_NAME'] ?>"  method="get">
<?php 
    // CORREGIDO: con floatval() is_int() nunca era cierto: no se validaba nada ("abc" -> $i % 0 ->
    // DivisionByZeroError, 0.5 -> Deprecated + DivisionByZeroError, 150 o -9 se aceptaban)
    $entero = (isset($_GET['entero']) && preg_match("/^\d{1,3}$/", $_GET['entero']) === 1) ? intval($_GET['entero']) : "";
    $primeraVez   = empty($_GET['primeraVez'])   ? "": $_GET['primeraVez'];

     echo <<<MARCA
     <div class="entradas">
     <h1>Múltiplos de un número</h1>
     <p>
         <label for="entero">Introduzca un número entre 1 y 100:</label>
         <input type="number" id="entero" name="entero"  value="{$entero}"/>
     </p>
  
     <button id="botonEnviar" type="submit">Enviar</button>      
     <button type="reset" ><a href="{$_SERVER['SCRIPT_NAME']}" style="text-decoration: none;">Borrar</a></button>
MARCA;
    if ( $entero === "" || $entero < 1 || $entero > 100 )  {
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
                <p id="salida" style="color: red">Por favor, Introduzca un número entre 1 y 100</p>
            </div> 
SEGUNDAVEZ;
        }
    } else {
        echo <<<DATOSOK
        <input type="hidden" name="primeraVez" value="false"/>
   </div>   
DATOSOK;         
    echo "<pre style='font-family: monospace'>";
    for ($i = 1; $i <= 100; $i++) {
        echo ($i < 100 ? " " : "");
        echo ( ($i % $entero === 0) ? ($i < 100 ? " *" : "  *") : ($i < 10 ? " " . $i : $i) );
        echo " ";
        echo ( $i % 10 ) === 0 ? "<br>" : "";
    }
    echo "</pre>";
}
?>    
    </form>   <!-- CORREGIDO: faltaba cerrar el formulario -->
</body>
</html>
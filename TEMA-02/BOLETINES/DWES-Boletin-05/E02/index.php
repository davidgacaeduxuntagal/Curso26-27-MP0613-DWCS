<?php 
/*
 * ===== CORRECCIONES (revisión de las soluciones del tema 2) =====
 * - No se validaban los datos de la matriz (lo pide el enunciado): ahora se marcan en rojo y se avisa.
 * - XSS: los valores se escribían sin escapar en value="...".
 * - Una celda que no llegaba daba "Undefined array key"; matriz=hola -> TypeError en count().
 * - Sin límite de dimensiones (500 x 500 generaba 250 000 campos): ahora de 1 a 10.
 * - XSS: $_SERVER['PHP_SELF'] cambiado por $_SERVER['SCRIPT_NAME'].
 */
function imprimirMatriz(int $filas, int $columnas): array {
    // Crear tabla básica que permite introducir los datos de la matriz
    echo "<br><br>";
    echo "<table class='matrix'>";
    echo "<thead></thead>";
    echo "<tbody>";

    $activa = false;
    if (isset($_GET['matriz'])) {
        $matriz = $_GET['matriz'];
        $activa = true;
    } else {
        // Añadimos valores random por defecto para hacer más fácil depuración:
        for($fila = 0; $fila < $filas; $fila++) {
            for ($columna = 0; $columna < $columnas; $columna++) {
                $matriz[$fila][$columna] = rand(0,100);
            }
        }
        $activa = true;
    }

    // Añadir filas a la tabla 
    for($fila = 0; $fila < $filas; $fila++) {
        echo "<tr>";

        for($columna = 0; $columna < $columnas; $columna++) {
            echo "<td>";
            // CORREGIDO: el valor se escribía sin escapar (XSS) y una celda que no llega daba "Undefined array key"
            $valor = $activa ? htmlspecialchars($matriz[$fila][$columna] ?? "") : "";
            echo "<input type='number' name=\"matriz[$fila][$columna]\" value=\"{$valor}\" " . (is_numeric($valor) || $valor === "" ? "" : "style='background: #fcc' ") . "/>";   
            echo "</td>";
        }

        echo "</tr>";
    }
    
    echo "</tbody>";
    echo "</table>";

    // CORREGIDO: el enunciado pide validar que solo haya números (enteros o float)
    if ($activa && isset($_GET['matriz'])) {
        foreach ($matriz as $filaMatriz) {
            foreach ($filaMatriz as $valor) {
                if (!is_numeric($valor)) {
                    echo "<p style='color: red'>Hay valores que no son números (en rojo): solo se admiten enteros o decimales</p>";
                    break 2;
                }
            }
        }
    }


    return $matriz;
}

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="styles.css" /> 

    <title>DWES-PHP-B05-E02</title>
</head>
<body >
    <h1>Lectura de matriz:</h1>
    <form action="<?php echo $_SERVER['SCRIPT_NAME'] ?>"  method="get">
<?php 
    $numeroFilas    = empty($_GET['numeroFilas'])    ? "": intval($_GET['numeroFilas']);
    $numeroColumnas = empty($_GET['numeroColumnas']) ? "": intval($_GET['numeroColumnas']);
    $primeraVez     = empty($_GET['primeraVez'])     ? "": $_GET['primeraVez'];


     echo <<<MARCA
        <div id="entrada">
            <p>
                <label for="numeroFilas">Introduzca el número de filas de la matriz:</label>
                <input type="number" id="numeroFilas" name="numeroFilas" value="{$numeroFilas}"/>
            </p>
            <p>
                <label for="numeroColumnas">Introduzca el número de columnas de la matriz:</label>
                <input type="number" id="numeroColumnas" name="numeroColumnas"  value="{$numeroColumnas}" />
            </p>        
            <button type="submit" >Enviar</button>
            <button type="reset" ><a href="{$_SERVER['SCRIPT_NAME']}" style="text-decoration: none;">Borrar</a></button>

MARCA;
    // CORREGIDO: sin límite superior (500 x 500 = 250 000 campos); como en el ejercicio 3, de 1 a 10
    if ( $numeroFilas === "" || $numeroColumnas === "" || $numeroFilas < 1 || $numeroColumnas < 1 || $numeroFilas > 10 || $numeroColumnas > 10 ) {
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
                <p id="salida" style="color: red">Por favor, introduzca ambos datos, 
                 número de filas y número de columnas, ambos enteros de 1 a 10</p>
            </div> 
SEGUNDAVEZ;
        }
    } else {
        echo <<<DATOSOK
        <input type="hidden" name="primeraVez" value="false"/>
   </div>   
DATOSOK;     
        // Comprobamos si cambiaron los datos del formulario: si es así, reseteamos matriz:
        if ( isset($_GET['matriz']) &&   // CORREGIDO: con matriz=hola, count() lanzaba TypeError
            (!is_array($_GET['matriz']) || !is_array($_GET['matriz'][0] ?? null) ||
             $numeroFilas !== count($_GET['matriz']) ||
             $numeroColumnas !== count($_GET['matriz'][0]) )) {
           unset($_GET['matriz']);
       }

        $matriz = imprimirMatriz($numeroFilas, $numeroColumnas);
    }
?>  
    </form>
</body>
</html>
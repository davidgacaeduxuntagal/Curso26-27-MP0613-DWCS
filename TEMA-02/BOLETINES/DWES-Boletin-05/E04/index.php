<?php 
/*
 * ===== CORRECCIONES (revisión de las soluciones del tema 2) =====
 * - El resultado se pintaba con dimensiones (columnas1 x columnas2) en vez de (filas1 x columnas2): con
 *   2x3 por 3x2 salía una fila de más con Warnings y con 3x2 por 2x2 faltaba una fila.
 * - No se validaban los datos: una celda vacía o con texto -> TypeError al multiplicar. Nueva función
 *   esMatrizNumerica() y aviso; las celdas no numéricas se marcan en rojo.
 * - XSS: los valores se escribían sin escapar; matriz1=hola -> TypeError en count().
 * - Sin límite de dimensiones: ahora de 1 a 10 (como dice el enunciado del ejercicio 3).
 * - XSS: $_SERVER['PHP_SELF'] cambiado por $_SERVER['SCRIPT_NAME'].
 */
function imprimirMatriz(int $filas, int $columnas, int $numeroMatriz): array {
    // Crear tabla básica que permite introducir los datos de la matriz
    echo "<table class='matrix'>";
    echo "<thead></thead>";
    echo "<tbody>";

    $activa = false;
    if (isset($_GET['matriz' . $numeroMatriz]) && is_array($_GET['matriz' . $numeroMatriz])) {
        $matriz = $_GET['matriz' . $numeroMatriz];
        $activa = true;
    } else {
        $matriz = array();
    }

    // Añadir filas a la tabla 
    for($fila = 0; $fila < $filas; $fila++) {
        echo "<tr>";

        for($columna = 0; $columna < $columnas; $columna++) {
            echo "<td>";
            // CORREGIDO: el valor se escribía sin escapar (XSS) y una celda que no llega daba "Undefined array key"
            $valor = $activa ? htmlspecialchars($matriz[$fila][$columna] ?? "") : "";
            echo "<input type='number' name=\"matriz{$numeroMatriz}[$fila][$columna]\" value=\"{$valor}\" " . (is_numeric($valor) || $valor === "" ? "" : "style='background: #fcc' ") . "/>";   
            echo "</td>";
        }

        echo "</tr>";
    }

    echo "</tbody>";
    echo "</table>";

    return $matriz;
}

// CORREGIDO: función nueva para validar los datos antes de multiplicar (una celda vacía o con texto
// lanzaba TypeError: Unsupported operand types)
function esMatrizNumerica($matriz, int $filas, int $columnas): bool {
    for ($fila = 0; $fila < $filas; $fila++) {
        for ($columna = 0; $columna < $columnas; $columna++) {
            if (!isset($matriz[$fila][$columna]) || !is_numeric($matriz[$fila][$columna])) {
                return false;
            }
        }
    }
    return true;
}

function productoMatricial($m1, $m2): array {
    // Comprobar dimensiones
    $m1Filas    = count($m1);
    $m1Columnas = count($m1[0]);
    $m2Filas    = count($m2);
    $m2Columnas = count($m2[0]);

    if ( $m1Columnas !== $m2Filas ) {
        throw new Exception("Dimensiones de matrices no compatibles");
    } else {
        for($fila = 0; $fila < $m1Filas; $fila++) {
            for($columna = 0; $columna < $m2Columnas; $columna++) {
                $resultado[$fila][$columna] = 0;
                for($celda = 0; $celda < $m1Columnas; $celda++) { 
                    $resultado[$fila][$columna] += $m1[$fila][$celda] * $m2[$celda][$columna];
                }
            }
        }
        return $resultado;
    }
}

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="styles.css" /> 

    <title>DWES-PHP-B05-E04</title>
</head>
<body >
    <h1>Producto de matrices:</h1>
    <form action="<?php echo $_SERVER['SCRIPT_NAME'] ?>"  method="get">
<?php 
    $numeroFilas     = empty($_GET['numeroFilas'])    ? "": intval($_GET['numeroFilas']);
    $numeroColumnas1 = empty($_GET['numeroColumnas1']) ? "": intval($_GET['numeroColumnas1']);
    $numeroColumnas2 = empty($_GET['numeroColumnas2']) ? "": intval($_GET['numeroColumnas2']);
    $primeraVez      = empty($_GET['primeraVez'])     ? "": $_GET['primeraVez'];

     echo <<<MARCA
        <div id="entrada">
            <p>
                <label for="numeroFilas">Introduzca el número de filas de la matriz 1:</label>
                <input type="number" id="numeroFilas" name="numeroFilas" value="{$numeroFilas}"/>
            </p>
            <p>
                <label for="numeroColumnas">Introduzca el número de columnas de la matriz 1:</label>
                <input type="number" id="numeroColumnas1" name="numeroColumnas1"  value="{$numeroColumnas1}" />
            </p>    
            <p>
            <label for="numeroColumnas">Introduzca el número de columnas de la matriz 2:</label>
            <input type="number" id="numeroColumnas2" name="numeroColumnas2"  value="{$numeroColumnas2}" />
        </p>                    
            <button type="submit" >Enviar</button>
            <button type="reset" ><a href="{$_SERVER['SCRIPT_NAME']}" style="text-decoration: none;">Borrar</a></button>

MARCA;
    // CORREGIDO: sin límite superior; el enunciado del ejercicio 3 dice 1 <= n, m, t <= 10
    if ( $numeroFilas === ""  || $numeroFilas < 1 || $numeroFilas > 10 || $numeroColumnas1 > 10 || $numeroColumnas2 > 10 || 
         $numeroColumnas1 < 1 || $numeroColumnas1 === "" ||
         $numeroColumnas2 < 1 || $numeroColumnas2 === "" ) {
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
                 número de filas y número de columnas, todos enteros de 1 a 10</p>
            </div> 
SEGUNDAVEZ;
        }
    } else {
        echo <<<DATOSOK
        <input type="hidden" name="primeraVez" value="false"/>
   </div>   
DATOSOK;     
        // Comprobamos si cambiaron los datos del formulario: si es así, reseteamos matrices:
        if ( isset($_GET['matriz1']) &&   // CORREGIDO: con matriz1=hola, count() lanzaba TypeError
            (!is_array($_GET['matriz1']) || !is_array($_GET['matriz1'][0] ?? null) ||
             $numeroFilas !== count($_GET['matriz1']) ||
             $numeroColumnas1 !== count($_GET['matriz1'][0])) ) {
            unset($_GET['matriz1']);
        }

        if ( isset($_GET['matriz2']) &&   // CORREGIDO: con matriz2=hola, count() lanzaba TypeError
             (!is_array($_GET['matriz2']) || !is_array($_GET['matriz2'][0] ?? null) ||
              $numeroColumnas1 !== count($_GET['matriz2']) ||
              $numeroColumnas2 !== count($_GET['matriz2'][0])) ) {
            unset($_GET['matriz2']);
        }

        echo "<h3>Matriz 1:</h3>";
        $matriz1 = imprimirMatriz($numeroFilas, $numeroColumnas1, 1);
        echo "<h3>Matriz 2:</h3>";
        $matriz2 = imprimirMatriz($numeroColumnas1, $numeroColumnas2, 2);

        if (isset($_GET['matriz1']) && isset($_GET['matriz2']) ) {
            if ( esMatrizNumerica($matriz1, $numeroFilas, $numeroColumnas1) && esMatrizNumerica($matriz2, $numeroColumnas1, $numeroColumnas2) ) {
                $_GET['matriz3'] = productoMatricial($matriz1, $matriz2);
                echo "<h2>Producto matricial:</h2>";
                // CORREGIDO: el producto de (m x n) por (n x t) es m x t: antes se pintaba n x t
                // (filas de más -> "Undefined array key" o filas de menos)
                $matriz3 = imprimirMatriz($numeroFilas, $numeroColumnas2, 3);
            } else {
                echo "<p style='color: red'>Rellene todas las celdas con números (enteros o decimales)</p>";
            }
        }
    }
?>  
    </form>
</body>
</html>
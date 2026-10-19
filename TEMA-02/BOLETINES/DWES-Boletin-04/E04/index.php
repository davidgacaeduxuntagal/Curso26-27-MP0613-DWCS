<?php
/*
 * ===== CORRECCIONES (revisión de las soluciones del tema 2) =====
 * - calcularFibonacciIterativa(0) devolvía 1 (la recursiva, 0).
 * - Sin límite superior: la versión recursiva es exponencial y con 40 se agotaba el tiempo de ejecución
 *   (Fatal error). Se limita a 30.
 * - XSS: $_SERVER['PHP_SELF'] cambiado por $_SERVER['SCRIPT_NAME'].
 */
/*
 *  Iterativa
 *
*/
function calcularFibonacciIterativa ($termino) {
    $fib1 = 0;
    $fib2 = 1;
    $i = 2;

    while ($i <= $termino) {
        $temp = $fib2;
        $fib2 += $fib1;
        $fib1 = $temp;
        $i++;
    }

    return $termino > 1 ? $fib2 : $termino;   // CORREGIDO: fib(0) es 0, no 1
}

/*
 *  Recursiva
 *
*/
function calcularFibonacciRecursiva ($termino): int {
    if ( $termino < 2 ) {
        return $termino;
    } else {
        return  calcularFibonacciRecursiva($termino - 1) + calcularFibonacciRecursiva($termino - 2);
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DWES-PHP-B04-E04</title>
</head>
<body >
    <h1>Sucesión de Fibonacci:</h1>
    <img src="fibonacci.png" alt="Ëspiral de Fibonacci" />

    <form action="<?php echo $_SERVER['SCRIPT_NAME'] ?>"  method="get">
<?php
    $termino    = empty($_GET['termino'])    ? "": intval($_GET['termino']);
    $primeraVez = empty($_GET['primeraVez']) ? "": $_GET['primeraVez'];

 echo <<<MARCA
    <div class="entradas">
        <p>
            <label for="termino">Introduzca un entero positivo:</label>
            <input type="number" id="termino" name="termino" value="{$termino}" />
        </p>
        <button id="botonEnviar" type="submit">Enviar</button>      
        <button type="reset" ><a href="{$_SERVER['SCRIPT_NAME']}" style="text-decoration: none;">Borrar</a></button>
MARCA;

if ( $termino === "" || $termino < 1 || $termino > 30 )  {   // CORREGIDO: la recursiva con 40 agotaba el tiempo
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
            <p id="salida" style="color: red">Por favor, introduzca un número entero entre 1 y 30</p>
        </div> 
SEGUNDAVEZ;
    }
} else {
    echo <<<DATOSOK
     <input type="hidden" name="primeraVez" value="false"/>
    </div>   
    <div id="salida">
    <h2>Soluciones:</h2>
     <pre><p>
DATOSOK; 
    echo "Iterativa: ";
    for ($i = 1; $i <= $termino; $i++) {
        echo calcularFibonacciIterativa ($i) . ( $i === $termino ? "" : ", ");
    }
    echo "</p></pre>";
    echo "<pre><p>Recursiva: ";
            for ($i = 1; $i <= $termino; $i++) {
                echo calcularFibonacciRecursiva ($i) . ( $i === $termino ? "" : ", ");
            }
}
?>
   </p> </pre>
        </pre>
    </div>
</body>
</html>
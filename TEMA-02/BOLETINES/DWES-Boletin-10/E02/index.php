<?php
/*
 * ===== CORRECCIONES (revisión de las soluciones del tema 2) =====
 * - Sin validación: "abc" -> TypeError; -1 -> recursión infinita (memoria agotada); 30 -> tiempo agotado.
 *   Ahora entero de 1 a 10.
 * - Había dos </form>.
 * - XSS: $_SERVER['PHP_SELF'] cambiado por $_SERVER['SCRIPT_NAME'].
 */
$contador = 0;
// Vamos a utilizar una variable global para contar el número de pasos
// aunque esto no se pide en el enunciado, puede ser ilustrativo.
// Por otro lado, también podríamos utilizar otro parámetro dentro de 
// la firma de la función para contar el número de pasos
function torresDeHanoi (int $discos, int $posteInicial, int $posteFinal, int $posteTemporal) {
    global $contador;
    if ($discos === 1) {
        ++$contador;
        echo "Paso {$contador}: {$posteInicial} --> {$posteFinal}" . "<br>";
    } else {
        torresDeHanoi($discos - 1, $posteInicial, $posteTemporal, $posteFinal);
        ++$contador;
        echo "Paso {$contador}: {$posteInicial} --> {$posteFinal}". "<br>";
        torresDeHanoi($discos - 1, $posteTemporal, $posteFinal, $posteInicial);
    }
} ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DWES-PHP-B10-E02</title>
</head>
<body >
    <h1>Torres de Hanoi</h1>
    <img src="torresHanoi.png" alt="Imagen juego milenario Torres de Hanoi"/>
    
    <form action="<?php echo $_SERVER['SCRIPT_NAME'] ?>"  method="get">
<?php
    // CORREGIDO: no se validaba: "abc" -> TypeError, -1 -> recursión infinita (memoria agotada),
    // 3.5 -> Deprecated y 30 -> se agotaba el tiempo (2^30 - 1 movimientos). Entero de 1 a 10.
    $numeroDiscos = (isset($_GET['numeroDiscos']) && preg_match("/^\d{1,2}$/", $_GET['numeroDiscos']) === 1 &&
                     $_GET['numeroDiscos'] >= 1 && $_GET['numeroDiscos'] <= 10) ? intval($_GET['numeroDiscos']) : "VACIO";
    echo <<<MARCA
    <div id="entrada">
        <p>
            <label for="numeroDiscos">Introduzca el número de discos a mover del poste 1 al poste 3:</label>
            <input type="number" id="numeroDiscos" name="numeroDiscos" />
        </p>
    </div>         
MARCA;

    if ( $numeroDiscos === "VACIO"  )  {
        echo <<<MARCA
        <p style="color: red">Introduzca un número de discos entre 1 y 10, por favor</p>
MARCA;
    } else {
        torresDeHanoi($numeroDiscos, 1, 3, 2);
    }
?>
        <button id="botonEnviar" type="submit">Enviar</button>      
        <button type="reset" ><a href="<?php echo $_SERVER['SCRIPT_NAME'] ?>" style="text-decoration: none;">Borrar</a></button>
    </form>   <!-- CORREGIDO: el formulario se cerraba dos veces -->
</body>
</html>
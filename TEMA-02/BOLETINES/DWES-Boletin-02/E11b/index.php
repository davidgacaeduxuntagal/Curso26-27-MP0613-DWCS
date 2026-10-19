<?php
/*
 * ===== CORRECCIONES (revisión de las soluciones del tema 2) =====
 * - Se aceptaba cualquier número de cifras: intval("99999999999999999999") da PHP_INT_MAX y el resultado
 *   era el de otro número sin avisar. Ahora como mucho 18 cifras (caben en un int).
 * - XSS: $_SERVER['PHP_SELF'] cambiado por $_SERVER['SCRIPT_NAME'].
 */
// función recursiva
function espejo($numero, &$invertido) {
    if ( $numero == 0 ) {
        return;
    } else {
        $invertido .= ($numero % 10);
        espejo((int)($numero / 10), $invertido);
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DWES-PHP-B02-11b</title>
</head>
<body style="font-family: monospace">

<form action="<?php echo $_SERVER['SCRIPT_NAME'] ?>"  method="get">
<?php
    $estado  = (!isset($_GET['estado'])) ? 0 : intval($_GET['estado']);
    $numero  = (!isset($_GET['numero'])  || preg_match("/^[1-9]\d{0,17}$/", $_GET['numero'])   /* CORREGIDO: máximo 18 cifras */ !== 1 ) ? "INVALIDO" : intval($_GET['numero']);

    switch($estado) {
        case 0:
            $estado = 1;
            include "formulario1.php";
            break;

        case 1: 
            $estado = 1;
            if ( $numero === "INVALIDO" ) {
                include "formulario2.php";
            } else {
                $invertido = "";
                espejo($numero, $invertido); 
                include "formulario3.php";
            }

            break;

        default: throw new Exception("Estado no válido: " . $estado);
    }
      
?>
</form>
</body>
</html>
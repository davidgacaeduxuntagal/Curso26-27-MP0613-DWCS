<?php
/*
 * ===== CORRECCIONES (revisión de las soluciones del tema 2) =====
 * - La duración solo admitía de 3 a 39 años (/^([123]\d|[3-9])$/): 1 y 2 años daban error. Ahora 1..39.
 * - La cuota se redondeaba con number_format (2211,81) y el enunciado pide redondear por abajo: 2211,80.
 * - Estado 2: los campos ocultos no se validaban ("duracion=abc" -> TypeError).
 * - formulario2.php: el aviso cerraba con <p> en vez de </p>.
 * - XSS: $_SERVER['PHP_SELF'] cambiado por $_SERVER['SCRIPT_NAME'].
 */
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DWES-PHP-B02-02a</title>
</head>
<body style="font-family: monospace">
    <form action="<?php echo $_SERVER['SCRIPT_NAME'] ?>"  method="get">
<?php
    // $estado:
    //   0 = primera carga de página
    //   1 = introducción de datos 
    //   2 = imprimir mensaje 
    $estado    = (!isset($_GET['estado'])) ? 0 : intval($_GET['estado']);

    // Validamos datos usando expresiones regulares:
    $cuenta   = (!isset($_GET['cuenta'])   || preg_match("/^\d{3,8}$/", $_GET['cuenta']) !== 1)                   ? "INVALIDO" : $_GET['cuenta'];
    $cantidad = (!isset($_GET['cantidad']) || preg_match("/^(\d{5,7})(\d{0}|,\d{2})$/", $_GET['cantidad']) !== 1) ? "INVALIDO" : $_GET['cantidad'];
    $duracion = (!isset($_GET['duracion']) || preg_match("/^([123]\d{1}|[1-9])$/", $_GET['duracion']) !== 1)      ? "INVALIDO" : intval($_GET['duracion']);
    $interes  = (!isset($_GET['interes'])  || preg_match("/^0?,\d{2,}$/", $_GET['interes']) !== 1 )               ? "INVALIDO" : $_GET['interes'];

    // Mostramos formulario adecuado:
    switch($estado) {
        case 0: // Primera carga de página
            $estado = 1;
            include "formulario1.php";
            break;
        
        case 1: // Introducción de datos
            if ( $cuenta === "INVALIDO" || 
                 $cantidad === "INVALIDO"     ||  
                 $duracion === "INVALIDO"     ||
                 $interes === "INVALIDO"  ) {
                $estado = 1;

                include "formulario2.php";
            } else {
                $estado = 2;
                $interesesTotales = floatval($cantidad) * floatval(str_replace(",", ".", $interes))* $duracion;
                $cuotaMensual     = (floatval($cantidad) + $interesesTotales) / ($duracion * 12);
                $interesesTotales = number_format($interesesTotales, 2, ",", "");
                $cuotaMensual     = number_format(floor(round($cuotaMensual * 100, 6)) / 100, 2, ",", "");  // CORREGIDO: redondeo por abajo (2211,80 del enunciado)
                include "formulario3.php";                
            }
            break;

        case 2: // Imprimir datatos
            // CORREGIDO: los campos ocultos no se volvían a validar: manipulados -> TypeError (Fatal error)
            if ( $cuenta === "INVALIDO" || $cantidad === "INVALIDO" || $duracion === "INVALIDO" || $interes === "INVALIDO" ) {
                $estado = 1;
                include "formulario2.php";
                break;
            }
            $estado = 2;
            $interesesTotales = floatval($cantidad) * floatval(str_replace(",", ".", $interes)) * $duracion;
            $cuotaMensual     = (floatval($cantidad) + $interesesTotales) / ($duracion * 12);
            $interesesTotales = number_format($interesesTotales, 2, ",", "");
            $cuotaMensual     = number_format(floor(round($cuotaMensual * 100, 6)) / 100, 2, ",", "");  // CORREGIDO: redondeo por abajo (2211,80 del enunciado)
            include "formulario3.php"; 
            break;

        default: throw new Error("Estado no reconocido: " . $estado);
    }
?>
    </form>
</body>
</html>
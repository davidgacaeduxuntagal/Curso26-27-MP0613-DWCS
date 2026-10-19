<?php
/*
 * ===== CORRECCIONES (revisión de las soluciones del tema 2) =====
 * - Entrada limitada a 12 cifras (ver formulario3.php).
 * - XSS: $_SERVER['PHP_SELF'] cambiado por $_SERVER['SCRIPT_NAME'].
 */
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DWES-PHP-B02-03</title>
</head>
<body style="font-family: monospace">

<form action="<?php echo $_SERVER['SCRIPT_NAME'] ?>"  method="get">
<?php
    /* 
     * EXACTAMENTE LO MISMO QUE DWES-PHP-B02-03a PERO 
     *  SEPARANDO LA PRESENTACIÓN USANDO FICHEROS DE "FORMULARIOS"
    */
    $estado = (!isset($_GET['estado'])) ? 0 : intval($_GET['estado']);
    $numero = (!isset($_GET['numero'])  || preg_match("/^\d{1,12}$/", $_GET['numero'])   /* CORREGIDO: máximo 12 cifras */ !== 1) ? "INVALIDO" : intval($_GET['numero']);


    switch($estado) {
        case 0:
            $estado = 1;
            include "formulario1.php";
            break;

        case 1: 
            $estado = 1;
            if ( $numero === "INVALIDO" || $numero < 2 ) {
                include "formulario2.php";
            } else {
                include "formulario3.php";
            }

            break;

        default: throw new Exception("Estado no válido: " . $estado);
    }
      
?>
</form>
</body>
</html>
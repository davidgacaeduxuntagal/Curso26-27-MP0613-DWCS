<?php
/*
 * ===== CORRECCIONES (revisión de las soluciones del tema 2) =====
 * - Reset: se hacía unset() dos veces de numero1 (ahora numero1 y numero2).
 * - Validación: empty() consideraba vacío el "0" y no se comprobaba que fuesen números: "abc" -> TypeError,
 *   "1.5" -> Deprecated, "00" como segundo número -> DivisionByZeroError. Ahora: enteros de hasta 18 cifras.
 * - Faltaba el cociente que pide el enunciado; con el segundo número 0 se avisa en vez de dividir.
 * - XSS: el valor del GET se escribía sin escapar en value="..."; al validar como entero ya no es posible.
 * - XSS: $_SERVER['PHP_SELF'] (admite /index.php/"><script>...) cambiado por $_SERVER['SCRIPT_NAME'].
 * - El campo del segundo número mostraba el valor del primero (value="<?=$numero1?>").
 */
    /* 
        Otra forma de hacer lo mismo

    */ 
    // Activar para depurar
    ob_end_flush();
    ob_implicit_flush();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DWES-PHP-B01-01c</title>
</head>
<body style="font-family: monospace">

    <form action="<?php echo $_SERVER['SCRIPT_NAME'] ?>"  method="get">
<?php
    if( !empty($_GET['botonReset']) ):
        unset($_GET['numero1']);
        unset($_GET['numero2']);   // CORREGIDO: se borraba dos veces numero1
    endif;

    // CORREGIDO: empty() trataba el "0" como campo vacío y no se validaba nada: con "abc" PHP 8 lanza
    // TypeError al sumar. Ahora solo se aceptan enteros (máximo 18 cifras para que quepan en un int).
    $numero1 = (isset($_GET['numero1']) && preg_match("/^[+-]?\d{1,18}$/", $_GET['numero1']) === 1) ? intval($_GET['numero1']) : "NOVALIDO";
    $numero2 = (isset($_GET['numero2']) && preg_match("/^[+-]?\d{1,18}$/", $_GET['numero2']) === 1) ? intval($_GET['numero2']) : "NOVALIDO";

    if ($numero1 === "NOVALIDO" || $numero2 === "NOVALIDO"):
?>  
        <p>
            <label for="numero1">Introduzca el primer número:</label>
            <input type="number" id="numero1" name="numero1" />
        </p>
        <p>
            <label for="numero2">Introduzca el segundo número:</label>
            <input type="number" id="numero2" name="numero2" />
        </p>
        <p style="color: red">Rellene ambos campos con números enteros, por favor</p>
        <p id="suma">&nbsp;</p>
        <p id="resta">&nbsp;</p>
        <p id="producto">&nbsp;</p>
        <p id="cociente">&nbsp;</p>  
        <p id="resto">&nbsp;</p>    
<?php
    else:
?>
        <p>
            <label for="numero1" >Introduzca el primer número:</label>
            <input type="number" id="numero1" name="numero1" value="<?=$numero1?>"/>
            </p>
        <p>
            <label for="numero2">Introduzca el segundo número:</label>
            <input type="number" id="numero2" name="numero2" value="<?=$numero2?>" />
        </p>

        <p>Dados los números: <?=$numero1?> y <?=$numero2?> </p>   
        <p id='suma'>La suma es: <?=($numero1 + $numero2)?></p>
        <p id='resta'>La resta es: <?=($numero1 - $numero2)?> </p>
        <p id='producto'>El producto es: <?=($numero1 * $numero2)?> </p>
<?php // CORREGIDO: faltaba el cociente y con 0 el resto lanzaba DivisionByZeroError ?>
<?php if ($numero2 === 0) { ?>
        <p id='cociente'>El cociente es: no se puede dividir entre 0</p>
        <p id='resto'>El resto es: no se puede dividir entre 0</p>
<?php } else { ?>
        <p id='cociente'>El cociente es: <?=($numero1 / $numero2)?> </p>
        <p id='resto'>El resto es: <?=($numero1 % $numero2)?> </p>
<?php } ?>
<?php endif; ?>   
 
        <button id="botonEnviar" type="submit" name="botonEnviar" value="Enviar">Enviar</button>      
        <button id="botonReset" type="submit" name="botonReset" value="Reset">Reset</button> 
    </form>
</body>
</html>
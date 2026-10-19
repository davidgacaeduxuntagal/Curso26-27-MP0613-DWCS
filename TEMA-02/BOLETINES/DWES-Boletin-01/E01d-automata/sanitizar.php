<?php
/* 
 *  Comprobar formato (no comprueba rangos) entero válido
 *  en entrada formulario
 *
*/
// CORREGIDO: sin el tipo string (un array "numero1[]=1" provocaba TypeError) y como mucho 18 cifras:
// antes "99999999999999999999" se aceptaba y PHP lo convertía a float perdiendo precisión
function formatoEnteroValido($cadena) {
    if ( is_string($cadena) && preg_match("/^[+-]?\d{1,18}$/", $cadena) === 1 ) {
        return $cadena;
    } else {
        return "NOVALIDO";
    }
}

?>
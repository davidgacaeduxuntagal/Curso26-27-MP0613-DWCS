<?php
/*
 * 13_strings_codificacion_01_problema_solucion.php
 *  (FICHERO CON LAS SOLUCIONES)
 *  Problemas de: bin2hex, hex2bin, str_rot13, convert_uuencode, convert_uudecode,
 *                quoted_printable_encode, quoted_printable_decode
 *  (repasa los ejemplos de ../13_strings_codificacion_01.php)
 *
 *  Resuelve cada problema donde se indica y ejecútalo en modo depuración para comprobarlo.
 */
require_once __DIR__ . '/../utilidades.php';

// =============================================================================================
// PROBLEMA 1. ¿Cuántos bytes ocupa cada carácter?
//  Para cada carácter de la lista muestra sus bytes en hexadecimal (bin2hex) y cuántos bytes
//  ocupa en UTF-8.
// =============================================================================================
titulo('Problema 1');
$caracteres = ["A", "ñ", "€", "😀"];
// completa aquí tu solución
// ---- SOLUCIÓN ----
$bytes = [];
foreach ($caracteres as $c) {
    $bytes[$c] = bin2hex($c) . " (" . strlen($c) . " bytes)";
}
ver('bytes', $bytes);     // A: 41 (1), ñ: c3b1 (2), €: e282ac (3), 😀: f09f9880 (4)

// =============================================================================================
// PROBLEMA 2. Mensaje secreto en hexadecimal
//  Has recibido este mensaje en hexadecimal. Descífralo. Después, genera un código de invitación
//  de 8 caracteres hexadecimales aleatorios.
// =============================================================================================
titulo('Problema 2');
$mensajeHex = "50485020657320646976657274696461";
// completa aquí tu solución
// ---- SOLUCIÓN ----
$mensaje    = hex2bin($mensajeHex);              // "PHP es divertida"
$invitacion = bin2hex(random_bytes(4));          // 4 bytes = 8 caracteres hexadecimales
ver('mensaje', $mensaje);
ver('código de invitación', $invitacion);

// =============================================================================================
// PROBLEMA 3. Acertijos con la respuesta oculta
//  Muestra cada acertijo con su respuesta codificada con ROT13 y, en una segunda lista, las
//  respuestas reveladas.
// =============================================================================================
titulo('Problema 3');
$acertijos = ["Blanco por dentro, verde por fuera" => "pera", "Tiene dientes y no come" => "peine"];
// completa aquí tu solución
// ---- SOLUCIÓN ----
$ocultos = [];
$revelados = [];
foreach ($acertijos as $pregunta => $respuesta) {
    $codificada            = str_rot13($respuesta);
    $ocultos[$pregunta]    = $codificada;               // "cren", "crvar"
    $revelados[$pregunta]  = str_rot13($codificada);    // de nuevo el original
}
ver('ocultos', $ocultos);
ver('revelados', $revelados);

// =============================================================================================
// PROBLEMA 4. Comparar codificaciones
//  Codifica el mismo texto con uuencode, base64 y quoted-printable, y compara la longitud de
//  cada resultado. Comprueba que al decodificar se recupera el original en los tres casos.
// =============================================================================================
titulo('Problema 4');
$texto = "Mañana, reunión a las 10:00 en el aula 2.";
// completa aquí tu solución
// ---- SOLUCIÓN ----
$uu  = convert_uuencode($texto);
$b64 = base64_encode($texto);
$qp  = quoted_printable_encode($texto);
ver('longitudes', ['original' => strlen($texto), 'uuencode' => strlen($uu), 'base64' => strlen($b64), 'quoted-printable' => strlen($qp)]);
ver('¿se recupera?', [
    convert_uudecode($uu) === $texto,
    base64_decode($b64) === $texto,
    quoted_printable_decode($qp) === $texto,
]);

// =============================================================================================
// PROBLEMA 5. Leer el asunto de un correo
//  Este es el cuerpo de un correo en quoted-printable, tal y como se ve en el "código fuente"
//  del email. Obtén el texto legible.
// =============================================================================================
titulo('Problema 5');
$cuerpo = "Estimada alumna:=0A=0ASu matr=C3=ADcula en el m=C3=B3dulo DWCS est=C3=A1 confirmada.";
// completa aquí tu solución
// ---- SOLUCIÓN ----
$legible = quoted_printable_decode($cuerpo);
ver('correo', $legible);     // "Estimada alumna:\n\nSu matrícula en el módulo DWCS está confirmada."

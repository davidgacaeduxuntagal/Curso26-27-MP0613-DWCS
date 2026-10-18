<?php
/*
 * 13_strings_codificacion_01_problema.php
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

// =============================================================================================
// PROBLEMA 2. Mensaje secreto en hexadecimal
//  Has recibido este mensaje en hexadecimal. Descífralo. Después, genera un código de invitación
//  de 8 caracteres hexadecimales aleatorios.
// =============================================================================================
titulo('Problema 2');
$mensajeHex = "50485020657320646976657274696461";
// completa aquí tu solución

// =============================================================================================
// PROBLEMA 3. Acertijos con la respuesta oculta
//  Muestra cada acertijo con su respuesta codificada con ROT13 y, en una segunda lista, las
//  respuestas reveladas.
// =============================================================================================
titulo('Problema 3');
$acertijos = ["Blanco por dentro, verde por fuera" => "pera", "Tiene dientes y no come" => "peine"];
// completa aquí tu solución

// =============================================================================================
// PROBLEMA 4. Comparar codificaciones
//  Codifica el mismo texto con uuencode, base64 y quoted-printable, y compara la longitud de
//  cada resultado. Comprueba que al decodificar se recupera el original en los tres casos.
// =============================================================================================
titulo('Problema 4');
$texto = "Mañana, reunión a las 10:00 en el aula 2.";
// completa aquí tu solución

// =============================================================================================
// PROBLEMA 5. Leer el asunto de un correo
//  Este es el cuerpo de un correo en quoted-printable, tal y como se ve en el "código fuente"
//  del email. Obtén el texto legible.
// =============================================================================================
titulo('Problema 5');
$cuerpo = "Estimada alumna:=0A=0ASu matr=C3=ADcula en el m=C3=B3dulo DWCS est=C3=A1 confirmada.";
// completa aquí tu solución

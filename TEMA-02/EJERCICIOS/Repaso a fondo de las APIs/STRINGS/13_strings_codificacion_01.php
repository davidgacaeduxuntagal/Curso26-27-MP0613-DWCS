<?php
/*
 * 13_strings_codificacion_01.php
 *  STRINGS - CODIFICACIÓN (1): representar los mismos datos de otra forma (y volver atrás)
 *  Funciones: bin2hex, hex2bin, str_rot13, convert_uuencode, convert_uudecode,
 *             quoted_printable_encode, quoted_printable_decode
 *
 *  IMPORTANTE: codificar NO es cifrar. Cualquiera puede decodificar estos textos: sirven para
 *  transportar o mostrar datos, no para protegerlos.
 *
 *  Ejecuta en modo depuración paso a paso (F10) y compara la longitud de cada string antes y
 *  después de codificarlo.
 */
require_once __DIR__ . '/utilidades.php';

// ---------------------------------------------------------------------------------------------
// bin2hex — convierte cada byte en 2 dígitos hexadecimales
// ENLACE: https://www.php.net/manual/es/function.bin2hex.php
// ENUNCIADO EJEMPLO: (1) ver los bytes reales que forman un texto con "ñ" y "€" en UTF-8;
//   (2) generar un token aleatorio para un enlace de "recuperar contraseña".
// NOTA: random_bytes genera bytes aleatorios seguros pero no imprimibles; bin2hex los convierte
//   en texto que se puede poner en una URL.
// ---------------------------------------------------------------------------------------------
titulo('bin2hex');
$texto         = "año 5€";
$hexadecimal   = bin2hex($texto);           // "61c3b16f2035e282ac": ñ = c3b1, € = e282ac
ver('bin2hex', $hexadecimal);
ver('bytes del texto', strlen($texto));     // 9 bytes (aunque veamos 6 caracteres)

$token         = bin2hex(random_bytes(16)); // 32 caracteres hexadecimales, distinto cada vez
ver('token para un enlace', $token);

// ---------------------------------------------------------------------------------------------
// hex2bin — operación inversa: de hexadecimal a bytes
// ENLACE: https://www.php.net/manual/es/function.hex2bin.php
// ENUNCIADO EJEMPLO: recuperar el texto original a partir de su representación hexadecimal.
// NOTA: si el texto tiene un número impar de dígitos o caracteres no hexadecimales, da un
//   aviso (Warning) y devuelve false.
// ---------------------------------------------------------------------------------------------
titulo('hex2bin');
$original      = hex2bin("486f6c61");        // "Hola"
ver('hex2bin', $original);
ver('ida y vuelta', hex2bin(bin2hex($texto)) === $texto);   // true

// ---------------------------------------------------------------------------------------------
// str_rot13 — desplaza cada letra 13 posiciones en el alfabeto (A<->N, B<->O...)
// ENLACE: https://www.php.net/manual/es/function.str-rot13.php
// ENUNCIADO EJEMPLO: en un foro de videojuegos se ocultan los "spoilers" con ROT13 para que no se
//   lean sin querer. Aplicarlo dos veces devuelve el texto original.
// NOTA: solo afecta a letras ASCII (a-z, A-Z); números, tildes y ñ no cambian.
// ---------------------------------------------------------------------------------------------
titulo('str_rot13');
$spoiler       = "El asesino es el mayordomo";
$oculto        = str_rot13($spoiler);        // "Ry nfrfvab rf ry znlbeqbzb"
$revelado      = str_rot13($oculto);         // otra vez el original
ver('str_rot13', $oculto);
ver('str_rot13 dos veces', $revelado);

// ---------------------------------------------------------------------------------------------
// convert_uuencode / convert_uudecode — codificación "uuencode", usada antiguamente para enviar
//   ficheros binarios por correo electrónico (solo admitía texto)
// ENLACE: https://www.php.net/manual/es/function.convert-uuencode.php
// ENLACE: https://www.php.net/manual/es/function.convert-uudecode.php
// ENUNCIADO EJEMPLO: codificar un pequeño mensaje para enviarlo por un canal que solo admite
//   caracteres imprimibles, y decodificarlo después.
// NOTA: hoy en día se usa base64_encode / base64_decode para lo mismo (también en las imágenes
//   incrustadas en HTML: src="data:image/png;base64,...").
// ---------------------------------------------------------------------------------------------
titulo('convert_uuencode / convert_uudecode');
$mensaje       = "Fichero: notas.txt";
$uu            = convert_uuencode($mensaje);
$deVuelta      = convert_uudecode($uu);
ver('uuencode', $uu);
ver('uudecode', $deVuelta);
ver('en base64 (más usado hoy)', base64_encode($mensaje));

// ---------------------------------------------------------------------------------------------
// quoted_printable_encode / quoted_printable_decode — codificación de los correos electrónicos:
//   los caracteres no ASCII se escriben como =XX (su byte en hexadecimal)
// ENLACE: https://www.php.net/manual/es/function.quoted-printable-encode.php
// ENLACE: https://www.php.net/manual/es/function.quoted-printable-decode.php
// ENUNCIADO EJEMPLO: preparar el cuerpo de un correo con tildes y eñes, y leer el texto de un
//   correo recibido en este formato (por ejemplo, al ver el "código fuente" de un email).
// NOTA: las líneas largas se cortan con "=" al final (salto de línea "suave").
// ---------------------------------------------------------------------------------------------
titulo('quoted_printable_encode / quoted_printable_decode');
$cuerpo        = "Información del pedido: 2 camisetas, tamaño M. Precio: 25€";
$qp            = quoted_printable_encode($cuerpo);
ver('quoted-printable', $qp);      // "Informaci=C3=B3n del pedido: 2 camisetas, tama=C3=B1o M. ..."

$recibido      = "Su cita est=C3=A1 confirmada para ma=C3=B1ana";
$legible       = quoted_printable_decode($recibido);   // "Su cita está confirmada para mañana"
ver('decodificado', $legible);

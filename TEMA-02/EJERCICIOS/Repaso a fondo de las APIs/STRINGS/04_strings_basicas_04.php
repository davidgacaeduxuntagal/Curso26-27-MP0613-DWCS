<?php
/*
 * 04_strings_basicas_04.php
 *  STRINGS - BÁSICAS (4): caracteres y códigos, y escribir en la salida
 *  Funciones: ord, chr, quotemeta, echo, print
 *
 *  Ejecuta en modo depuración paso a paso (F10). En los bucles, observa cómo cambian el código
 *  numérico y el carácter en cada vuelta.
 */
require_once __DIR__ . '/utilidades.php';

// ---------------------------------------------------------------------------------------------
// ord — devuelve el código (0-255) del PRIMER BYTE de un string
// ENLACE: https://www.php.net/manual/es/function.ord.php
// ENUNCIADO EJEMPLO: saber si un carácter es una letra mayúscula comparando su código ASCII
//   (A = 65 ... Z = 90) y ver el código de algunos caracteres especiales.
// NOTA: con caracteres de varios bytes (ñ, €) solo devuelve el primer byte: usa mb_ord.
// ---------------------------------------------------------------------------------------------
titulo('ord');
$letra         = "G";
$codigo        = ord($letra);                        // 71
$esMayuscula   = $codigo >= 65 && $codigo <= 90;     // true
ver("ord('$letra')", $codigo);
ver('¿mayúscula?', $esMayuscula);

$espacio       = ord(" ");                           // 32
$saltoLinea    = ord("\n");                          // 10
$primerByteEnie = ord("ñ");                          // 195 (primer byte de los 2 que ocupa en UTF-8)
ver('ord(" ")', $espacio);
ver('ord("\n")', $saltoLinea);
ver('ord("ñ") (solo 1er byte)', $primerByteEnie);

// ---------------------------------------------------------------------------------------------
// chr — devuelve el carácter correspondiente a un código (0-255)
// ENLACE: https://www.php.net/manual/es/function.chr.php
// ENUNCIADO EJEMPLO: generar las letras de las columnas de una hoja de cálculo (A, B, C...) y
//   cifrar una palabra con el cifrado César (cada letra se desplaza 3 posiciones).
// ---------------------------------------------------------------------------------------------
titulo('chr');
$columnas      = [];
for ($i = 0; $i < 5; $i++) {
    $columnas[] = chr(65 + $i);                      // 'A', 'B', 'C', 'D', 'E'
}
ver('columnas', $columnas);

$original      = "HOLA";
$cifrado       = "";
for ($i = 0; $i < strlen($original); $i++) {
    $posicion  = ord($original[$i]) - 65;            // 0..25
    $nueva     = ($posicion + 3) % 26;               // desplazamos 3 (con vuelta al principio)
    $cifrado  .= chr(65 + $nueva);
}
ver("César de '$original'", $cifrado);              // "KROD"

// ---------------------------------------------------------------------------------------------
// quotemeta — añade una barra invertida delante de los caracteres especiales . \ + * ? [ ^ ] $ ( )
// ENLACE: https://www.php.net/manual/es/function.quotemeta.php
// ENUNCIADO EJEMPLO: buscar un precio como "9.99$" dentro de un texto con una expresión regular,
//   sin que el "." y el "$" se interpreten como símbolos especiales.
// NOTA: para expresiones regulares PCRE es mejor preg_quote (escapa también el delimitador).
// ---------------------------------------------------------------------------------------------
titulo('quotemeta');
$buscado       = "9.99$";
$escapado      = quotemeta($buscado);                // "9\.99\$"
ver('quotemeta', $escapado);

$texto         = "Oferta: 9.99$ (antes 19.99$)";
$aparece       = preg_match("/" . $escapado . "/", $texto);   // 1
ver('¿aparece en el texto?', $aparece);

// ---------------------------------------------------------------------------------------------
// echo — escribe uno o varios strings en la salida (no es una función, es una construcción del lenguaje)
// ENLACE: https://www.php.net/manual/es/function.echo.php
// ENUNCIADO EJEMPLO: mostrar un saludo usando varios argumentos separados por comas, con
//   concatenación y con interpolación de variables entre comillas dobles.
// NOTA: con comillas dobles se sustituyen las variables; con comillas simples, no.
// ---------------------------------------------------------------------------------------------
titulo('echo');
$usuario       = "Ana";
$mensajes      = 3;
echo "Hola, ", $usuario, PHP_EOL;                         // varios argumentos
echo "Tienes " . $mensajes . " mensajes nuevos" . PHP_EOL; // concatenación
echo "Hola $usuario, tienes {$mensajes} mensajes" . PHP_EOL; // interpolación
echo 'Con comillas simples no se sustituye: $usuario' . PHP_EOL;

// ---------------------------------------------------------------------------------------------
// print — escribe UN string en la salida y devuelve siempre 1
// ENLACE: https://www.php.net/manual/es/function.print.php
// ENUNCIADO EJEMPLO: mostrar un aviso, comprobando que print devuelve 1 (por eso se puede usar
//   dentro de una expresión, a diferencia de echo).
// ---------------------------------------------------------------------------------------------
titulo('print');
$devuelto      = print "Aviso: la sesión caduca en 5 minutos" . PHP_EOL;
ver('valor devuelto por print', $devuelto);         // 1

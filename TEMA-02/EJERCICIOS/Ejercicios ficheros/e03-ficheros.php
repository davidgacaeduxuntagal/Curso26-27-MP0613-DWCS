<?php
/*
 * e03-ficheros.php
 *  FICHEROS (3): leer POCO A POCO con el puntero (sin cargar todo el fichero en memoria)
 *  Funciones: fgets, feof, fgetc, fread, fpassthru, stream_get_contents
 *
 *  Ejecuta en modo depuración (F10). En los bucles, observa en cada vuelta el valor leído y si
 *  feof($fp) ya es true.
 */
require_once __DIR__ . '/utilidades.php';

// Preparación: un fichero con las opiniones de los clientes del Bar Baio (una por línea)
$opiniones = rutaDatos('opiniones.txt');
file_put_contents($opiniones,
    "Ana: muy buen café\n" .
    "Brais: el pulpo, espectacular\n" .
    "Carmen: un poco caro\n");

// ---------------------------------------------------------------------------------------------
// fgets — lee UNA línea (incluido su "\n") desde la posición actual. Devuelve false al final.
// ENLACE: https://www.php.net/manual/es/function.fgets.php
// ENUNCIADO EJEMPLO: recorrer las opiniones línea a línea y guardarlas sin el salto de línea.
// NOTA: este es el bucle RECOMENDADO: while (($linea = fgets($fp)) !== false)
// ---------------------------------------------------------------------------------------------
titulo('fgets');
$fp            = fopen($opiniones, 'r');
$lista         = [];
while (($linea = fgets($fp)) !== false) {
    $lista[]   = rtrim($linea, "\r\n");            // quitamos el salto de línea del final
}
fclose($fp);
ver('opiniones', $lista);

// ---------------------------------------------------------------------------------------------
// feof — ¿ha llegado el puntero al final del fichero?
// ENLACE: https://www.php.net/manual/es/function.feof.php
// ENUNCIADO EJEMPLO: ver la TRAMPA del bucle while (!feof($fp)): feof solo es true DESPUÉS de que
//   una lectura haya intentado pasar del final, así que la última vuelta lee false.
// NOTA: cuenta las vueltas en el depurador: son 4, no 3.
// ---------------------------------------------------------------------------------------------
titulo('feof');
$fp            = fopen($opiniones, 'r');
$leido         = [];
while (!feof($fp)) {
    $leido[]   = fgets($fp);                       // la cuarta vez devuelve false
}
fclose($fp);
ver('lecturas con while (!feof)', $leido);         // [..., ..., ..., false]

// ---------------------------------------------------------------------------------------------
// fgetc — lee UN carácter (en realidad, un BYTE). Devuelve false al final.
// ENLACE: https://www.php.net/manual/es/function.fgetc.php
// ENUNCIADO EJEMPLO: contar cuántas comas y cuántas letras "a" hay en el fichero de opiniones.
// NOTA: con letras de varios bytes (é, ñ...) devuelve cada byte por separado.
// ---------------------------------------------------------------------------------------------
titulo('fgetc');
$fp            = fopen($opiniones, 'r');
$comas         = 0;
$aes           = 0;
while (($caracter = fgetc($fp)) !== false) {
    if ($caracter === ',') {
        $comas++;
    } elseif ($caracter === 'a' || $caracter === 'A') {
        $aes++;
    }
}
fclose($fp);
ver('comas / letras a', [$comas, $aes]);           // [1, 8]

// ---------------------------------------------------------------------------------------------
// fread — lee como máximo N bytes. Es la función para ficheros BINARIOS (no entiende de líneas).
// ENLACE: https://www.php.net/manual/es/function.fread.php
// ENUNCIADO EJEMPLO: (1) leer un fichero en bloques de 16 bytes (así se procesan ficheros muy
//   grandes sin llenar la memoria); (2) comprobar si un fichero es un PNG leyendo sus 8 primeros
//   bytes, que en todos los PNG son "\x89PNG\r\n\x1a\n".
// ---------------------------------------------------------------------------------------------
titulo('fread');
$fp            = fopen($opiniones, 'r');
$bloques       = [];
while (!feof($fp)) {
    $bloques[] = fread($fp, 16);                   // aquí feof funciona bien: fread no devuelve false
}
fclose($fp);
ver('número de bloques de 16 bytes', count($bloques));
ver('primer bloque', $bloques[0]);                 // "Ana: muy buen ca"

$png           = rutaDatos('pixel.png');
file_put_contents($png, base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNk+M9QDwADhgGAWjR9awAAAABJRU5ErkJggg=='));
$fp            = fopen($png, 'rb');                // "b": modo binario
$cabecera      = fread($fp, 8);
fclose($fp);
ver('cabecera en hexadecimal', bin2hex($cabecera));            // "89504e470d0a1a0a"
ver('¿es un PNG?', $cabecera === "\x89PNG\r\n\x1a\n");         // true

// ---------------------------------------------------------------------------------------------
// fpassthru — envía a la salida TODO lo que queda desde la posición actual. Devuelve los bytes.
// ENLACE: https://www.php.net/manual/es/function.fpassthru.php
// ENUNCIADO EJEMPLO: mostrar todas las opiniones menos la primera (que leemos y descartamos).
// ---------------------------------------------------------------------------------------------
titulo('fpassthru');
$fp            = fopen($opiniones, 'r');
fgets($fp);                                        // saltamos la primera línea
$enviados      = fpassthru($fp);                   // el resto sale por pantalla aquí
fclose($fp);
ver('bytes enviados', $enviados);

// ---------------------------------------------------------------------------------------------
// stream_get_contents — devuelve en un STRING todo lo que queda desde la posición actual
// ENLACE: https://www.php.net/manual/es/function.stream-get-contents.php
// ENUNCIADO EJEMPLO: igual que el anterior, pero guardando el resto en una variable para
//   procesarlo (aquí, contar sus líneas).
// NOTA: es como file_get_contents pero sobre un fichero YA ABIERTO (y desde donde esté el puntero).
// ---------------------------------------------------------------------------------------------
titulo('stream_get_contents');
$fp            = fopen($opiniones, 'r');
fgets($fp);
$resto         = stream_get_contents($fp);
fclose($fp);
ver('resto', $resto);
ver('líneas del resto', substr_count($resto, "\n"));   // 2

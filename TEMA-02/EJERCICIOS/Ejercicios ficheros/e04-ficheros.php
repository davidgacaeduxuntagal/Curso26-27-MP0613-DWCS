<?php
/*
 * e04-ficheros.php
 *  FICHEROS (4): el PUNTERO del fichero. Moverse por el fichero y modificarlo "en el sitio".
 *  Funciones: ftell, fseek (SEEK_SET, SEEK_CUR, SEEK_END), rewind, ftruncate
 *
 *  IMPORTANTE: las posiciones se cuentan en BYTES desde el principio del fichero. En UTF-8 la ñ y
 *  las vocales con tilde ocupan 2 bytes.
 *
 *  Ejecuta en modo depuración (F10) y apunta en un papel el valor de ftell() después de cada línea:
 *  así entenderás cómo se mueve el puntero.
 */
require_once __DIR__ . '/utilidades.php';

$clientes = rutaDatos('clientes.txt');
file_put_contents($clientes, "Línea 1: Antonio\nLínea 2: María\nLínea 3: Xoán\n");

// ---------------------------------------------------------------------------------------------
// ftell — posición actual del puntero (en bytes)
// ENLACE: https://www.php.net/manual/es/function.ftell.php
// ENUNCIADO EJEMPLO: ver cómo avanza el puntero al leer la primera línea del fichero de clientes.
// NOTA: "Línea 1: Antonio\n" tiene 17 caracteres pero 18 BYTES (la í ocupa 2).
// ---------------------------------------------------------------------------------------------
titulo('ftell');
$fp            = fopen($clientes, 'r');
$alAbrir       = ftell($fp);                    // 0
$linea1        = fgets($fp);
$trasLinea1    = ftell($fp);                    // 18
ver('posición al abrir', $alAbrir);
ver('primera línea', $linea1);
ver('posición tras leerla', $trasLinea1);
ver('caracteres / bytes de la línea', [mb_strlen($linea1), strlen($linea1)]);   // [17, 18]

// ---------------------------------------------------------------------------------------------
// fseek — mueve el puntero. Devuelve 0 si funciona y -1 si falla.
//   SEEK_SET: desde el principio (por defecto)  SEEK_CUR: desde la posición actual  SEEK_END: desde el final
// ENLACE: https://www.php.net/manual/es/function.fseek.php
// ENUNCIADO EJEMPLO: leer los 7 últimos bytes del fichero, saltar al nombre de la línea 2, y
//   retroceder 5 bytes desde la posición actual.
// ---------------------------------------------------------------------------------------------
titulo('fseek');
fseek($fp, -7, SEEK_END);                       // 7 bytes antes del final
ver('posición', ftell($fp));
ver('lo que queda', fread($fp, 100));           // " Xoán\n": 7 BYTES son espacio, X, o, á (2), n y \n

fseek($fp, $trasLinea1);                        // volvemos al principio de la línea 2 (SEEK_SET)
fseek($fp, strlen("Línea 2: "), SEEK_CUR);      // avanzamos lo que ocupa "Línea 2: " (10 bytes)
ver('posición del nombre de la línea 2', ftell($fp));   // 28
ver('nombre', rtrim(fgets($fp)));               // "María"

fseek($fp, -5, SEEK_CUR);                       // retrocedemos 5 bytes
ver('tras retroceder 5', ftell($fp));

// ---------------------------------------------------------------------------------------------
// rewind — vuelve a poner el puntero al principio (como fseek($fp, 0))
// ENLACE: https://www.php.net/manual/es/function.rewind.php
// ENUNCIADO EJEMPLO: leer el fichero dos veces seguidas: contar las líneas y después mostrarlas.
// ---------------------------------------------------------------------------------------------
titulo('rewind');
rewind($fp);
$numLineas     = 0;
while (fgets($fp) !== false) {
    $numLineas++;
}
rewind($fp);                                    // sin esto, el siguiente fgets devolvería false
ver('número de líneas', $numLineas);            // 3
ver('primera línea otra vez', rtrim(fgets($fp)));
fclose($fp);

// ---------------------------------------------------------------------------------------------
// MODIFICAR "EN EL SITIO" con r+ — fseek + fwrite SOBRESCRIBE bytes (no inserta)
// ENUNCIADO EJEMPLO: cambiar "María" por "Lucía" en la línea 2. Es la versión CORREGIDA del
//   ejemplo exemplo_fseek.php del material base, que hacía fseek(8): ¡el byte 8 está en la
//   LÍNEA 1, no en la línea 2!
// NOTA: funciona porque "María" y "Lucía" ocupan los dos 6 bytes. Prueba a cambiarlo por "Ana":
//   quedarán restos ("Anaía"). Prueba con "Begoña": pisará el principio de la línea 3.
// ---------------------------------------------------------------------------------------------
titulo('modificar con r+');
$fp            = fopen($clientes, 'r+');
fgets($fp);                                      // saltamos la línea 1 entera (18 bytes)
fseek($fp, strlen("Línea 2: "), SEEK_CUR);       // nos situamos sobre "María"
$escritos      = fwrite($fp, "Lucía");
fclose($fp);
ver('bytes escritos', $escritos);                // 6
ver('fichero', file_get_contents($clientes));

// ---------------------------------------------------------------------------------------------
// ftruncate — corta el fichero al tamaño indicado (con 0, lo vacía). El fichero debe estar abierto
//   para escritura.
// ENLACE: https://www.php.net/manual/es/function.ftruncate.php
// ENUNCIADO EJEMPLO: (1) quitar la última línea del fichero de clientes; (2) vaciar un fichero de
//   registro sin cerrarlo.
// NOTA: ftruncate NO mueve el puntero: después de vaciar, haz rewind antes de volver a escribir.
// ---------------------------------------------------------------------------------------------
titulo('ftruncate');
$fp            = fopen($clientes, 'r+');
fgets($fp);
fgets($fp);
$finLinea2     = ftell($fp);                     // aquí empieza la línea 3
ftruncate($fp, $finLinea2);
fclose($fp);
ver('sin la última línea', file_get_contents($clientes));

$fp            = fopen($clientes, 'r+');
ftruncate($fp, 0);
rewind($fp);
fwrite($fp, "Fichero vaciado y vuelto a escribir\n");
fclose($fp);
ver('contenido final', file_get_contents($clientes));

<?php
/*
 * e01-ficheros.php
 *  FICHEROS (1): primeros pasos. Leer y escribir un fichero COMPLETO con una sola función.
 *  Funciones: file_put_contents, file_get_contents, file, file_exists, filesize, readfile
 *
 *  Cómo trabajar este fichero:
 *    1. Abre en el explorador de VSC la carpeta "datos" (se crea al ejecutar).
 *    2. Pon un punto de ruptura en la primera línea de código y ejecuta en modo depuración (F5).
 *    3. Avanza con F10. Después de cada escritura, abre el fichero en VSC y mira cómo ha quedado.
 */
require_once __DIR__ . '/utilidades.php';

$lista = rutaDatos('lista_compra.txt');
if (file_exists($lista)) {
    unlink($lista);           // empezamos siempre desde cero (unlink borra un fichero: lo verás en e07)
}

// ---------------------------------------------------------------------------------------------
// file_exists — ¿existe un fichero (o un directorio) con esa ruta?
// ENLACE: https://www.php.net/manual/es/function.file-exists.php
// ENUNCIADO EJEMPLO: antes de leer la lista de la compra, comprobar si ya existe.
// NOTA: devuelve true también si la ruta es un DIRECTORIO. Para saber si es un fichero: is_file (e06).
// ---------------------------------------------------------------------------------------------
titulo('file_exists');
$existe        = file_exists($lista);                  // false: lo acabamos de borrar
$existeCarpeta = file_exists(rutaDatos());             // true: la carpeta "datos" existe
ver('¿existe la lista?', $existe);
ver('¿existe la carpeta datos?', $existeCarpeta);

// ---------------------------------------------------------------------------------------------
// file_put_contents — escribe un string (o un array) en un fichero. Lo CREA si no existe y, por
//   defecto, lo SOBRESCRIBE. Devuelve el número de bytes escritos, o false si falla.
// ENLACE: https://www.php.net/manual/es/function.file-put-contents.php
// ENUNCIADO EJEMPLO: guardar la lista de la compra; después, añadir más productos sin borrar los
//   anteriores con el flag FILE_APPEND.
// NOTA: los saltos de línea los escribes tú ("\n"). Si $datos es un array, escribe sus elementos
//   uno detrás de otro (sin separador).
// ---------------------------------------------------------------------------------------------
titulo('file_put_contents');
$bytes         = file_put_contents($lista, "pan\nleche\nhuevos\n");     // 17 bytes
ver('bytes escritos', $bytes);

$masBytes      = file_put_contents($lista, "café\naceite\n", FILE_APPEND);   // "café" ocupa 5 bytes (la é, 2)
ver('bytes añadidos', $masBytes);                                        // 13

$productos     = ["fruta\n", "yogures\n"];
file_put_contents($lista, $productos, FILE_APPEND);                      // un array: se escriben seguidos
ver('¿existe ahora?', file_exists($lista));                             // true

// ---------------------------------------------------------------------------------------------
// filesize — tamaño del fichero en BYTES
// ENLACE: https://www.php.net/manual/es/function.filesize.php
// ENUNCIADO EJEMPLO: saber cuánto ocupa la lista de la compra.
// NOTA: PHP guarda en caché este dato dentro de un script; si el fichero cambia y vuelves a
//   preguntar, llama antes a clearstatcache() (lo verás en e06).
// ---------------------------------------------------------------------------------------------
titulo('filesize');
$tamano        = filesize($lista);                       // 44 = 17 + 13 + 14
ver('tamaño en bytes', $tamano);

// ---------------------------------------------------------------------------------------------
// file_get_contents — lee TODO el fichero y lo devuelve en un string (o false si falla)
// ENLACE: https://www.php.net/manual/es/function.file-get-contents.php
// ENUNCIADO EJEMPLO: leer la lista completa para mostrarla; leer solo un trozo (los 10 primeros
//   bytes); y ver qué pasa al leer un fichero que no existe.
// NOTA: trozo: file_get_contents($f, false, null, $desde, $longitud). Si el fichero no existe da
//   un Warning y devuelve false: compara SIEMPRE con === false (un fichero vacío devuelve "").
// ---------------------------------------------------------------------------------------------
titulo('file_get_contents');
$contenido     = file_get_contents($lista);
ver('contenido', $contenido);                            // "pan\nleche\nhuevos\ncafé\naceite\nfruta\nyogures\n"

$trozo         = file_get_contents($lista, false, null, 0, 10);   // "pan\nleche\n"
ver('10 primeros bytes', $trozo);

$noExiste      = @file_get_contents(rutaDatos('no_existe.txt'));   // @ oculta el Warning...
ver('fichero inexistente', $noExiste);                             // false  ...pero hay que comprobarlo
if ($noExiste === false) {
    ver('motivo', error_get_last()['message']);
}

// ---------------------------------------------------------------------------------------------
// file — lee el fichero y devuelve un ARRAY con una línea en cada elemento
// ENLACE: https://www.php.net/manual/es/function.file.php
// ENUNCIADO EJEMPLO: obtener los productos de la lista como array para contarlos y ordenarlos.
// NOTA: sin flags, cada línea CONSERVA su "\n" (mira el panel VARIABLES).
//   FILE_IGNORE_NEW_LINES quita los saltos de línea; FILE_SKIP_EMPTY_LINES salta las líneas vacías
//   (solo funciona junto con el anterior).
// ---------------------------------------------------------------------------------------------
titulo('file');
$conSaltos     = file($lista);                                          // ["pan\n", "leche\n", ...]
$limpias       = file($lista, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
ver('sin flags (primera línea)', $conSaltos[0]);                       // "pan\n"
ver('número de productos', count($limpias));                           // 7
sort($limpias);
ver('ordenados', $limpias);

// ---------------------------------------------------------------------------------------------
// readfile — ENVÍA el contenido del fichero directamente a la salida y devuelve los bytes leídos
// ENLACE: https://www.php.net/manual/es/function.readfile.php
// ENUNCIADO EJEMPLO: mostrar tal cual una nota legal guardada en un fichero de texto. Es la forma
//   habitual de enviar un fichero para que el usuario lo DESCARGUE (con las cabeceras adecuadas).
// NOTA: no se guarda nada en una variable: el texto aparece directamente en la salida (en la
//   consola de depuración o en el navegador).
// ---------------------------------------------------------------------------------------------
titulo('readfile');
$aviso         = rutaDatos('aviso_legal.txt');
file_put_contents($aviso, "Bar Baio - Rúa do Porto 5 - Baio" . PHP_EOL . "Los precios incluyen IVA." . PHP_EOL);
$leidos        = readfile($aviso);              // el texto sale por pantalla aquí
ver('bytes enviados a la salida', $leidos);

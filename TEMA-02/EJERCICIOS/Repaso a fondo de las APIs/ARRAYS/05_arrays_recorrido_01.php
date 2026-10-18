<?php
/*
 * 05_arrays_recorrido_01.php
 *  ARRAYS - RECORRIDO: el PUNTERO INTERNO de un array
 *  Funciones: current / pos, key, next, prev, reset, end, each (eliminada)
 *
 *  Cada array tiene un "puntero interno" que señala a uno de sus elementos (al principio, al
 *  primero). Estas funciones lo consultan o lo mueven. Hoy lo normal es recorrer con foreach, pero
 *  estas funciones siguen siendo útiles (por ejemplo, end() para el último elemento) y aparecen
 *  en mucho código existente.
 *
 *  Ejecuta en modo depuración paso a paso (F10) e imagina una flecha que se mueve por el array.
 */
require_once __DIR__ . '/utilidades.php';

$playlist = ['intro' => "Amanecer", 'tema1' => "Galicia calling", 'tema2' => "Ría de Vigo", 'final' => "Despedida"];

// ---------------------------------------------------------------------------------------------
// current (alias: pos) — devuelve el VALOR del elemento al que apunta el puntero (sin moverlo)
// ENLACE: https://www.php.net/manual/es/function.current.php
// ENLACE: https://www.php.net/manual/es/function.pos.php
// ENUNCIADO EJEMPLO: un reproductor de música muestra la canción actual de la lista.
// NOTA: devuelve false si el puntero está fuera del array (o el array está vacío).
// ---------------------------------------------------------------------------------------------
titulo('current / pos');
$sonando       = current($playlist);         // "Amanecer"
$alias         = pos($playlist);             // "Amanecer"
ver('sonando', $sonando);
ver('pos', $alias);

// ---------------------------------------------------------------------------------------------
// key — devuelve la CLAVE del elemento al que apunta el puntero
// ENLACE: https://www.php.net/manual/es/function.key.php
// ENUNCIADO EJEMPLO: mostrar el identificador de la pista que está sonando.
// NOTA: devuelve null si el puntero está fuera del array.
// ---------------------------------------------------------------------------------------------
titulo('key');
$pista         = key($playlist);             // "intro"
ver('pista', $pista);

// ---------------------------------------------------------------------------------------------
// next — avanza el puntero al siguiente elemento y devuelve su valor
// ENLACE: https://www.php.net/manual/es/function.next.php
// ENUNCIADO EJEMPLO: el botón "Siguiente canción". Al pasar del último elemento devuelve false.
// ---------------------------------------------------------------------------------------------
titulo('next');
$siguiente     = next($playlist);            // "Galicia calling"
ver('siguiente', $siguiente);
ver('clave actual', key($playlist));         // "tema1"
next($playlist);                             // "Ría de Vigo"
next($playlist);                             // "Despedida"
$fuera         = next($playlist);            // false: nos hemos salido
ver('next tras el último', $fuera);
ver('key fuera del array', key($playlist));  // null

// ---------------------------------------------------------------------------------------------
// reset — vuelve a poner el puntero en el PRIMER elemento y devuelve su valor
// ENLACE: https://www.php.net/manual/es/function.reset.php
// ENUNCIADO EJEMPLO: botón "Volver a empezar" la lista de reproducción; obtener el primer valor
//   de un array asociativo sin conocer su clave.
// ---------------------------------------------------------------------------------------------
titulo('reset');
$primera       = reset($playlist);           // "Amanecer"
ver('reset', $primera);

// ---------------------------------------------------------------------------------------------
// end — mueve el puntero al ÚLTIMO elemento y devuelve su valor
// ENLACE: https://www.php.net/manual/es/function.end.php
// ENUNCIADO EJEMPLO: saltar a la última canción; obtener la extensión de un fichero (el último
//   trozo tras explode).
// NOTA: recibe el array por referencia, así que necesita una VARIABLE: end(explode(...)) da un
//   aviso. Primero guarda el resultado de explode en una variable.
// ---------------------------------------------------------------------------------------------
titulo('end');
$ultima        = end($playlist);             // "Despedida"
ver('end', $ultima);

$partes        = explode(".", "copia.final.tar.gz");
$extension     = end($partes);               // "gz"
ver('extensión', $extension);

// ---------------------------------------------------------------------------------------------
// prev — retrocede el puntero al elemento anterior y devuelve su valor
// ENLACE: https://www.php.net/manual/es/function.prev.php
// ENUNCIADO EJEMPLO: el botón "Canción anterior" estando en la última; y recorrer la lista al
//   revés con un bucle while.
// ---------------------------------------------------------------------------------------------
titulo('prev');
$anterior      = prev($playlist);            // "Ría de Vigo" (estábamos en la última)
ver('anterior', $anterior);

$alReves       = [];
end($playlist);
while (($cancion = current($playlist)) !== false) {   // observa el puntero en cada vuelta
    $alReves[] = key($playlist) . ": " . $cancion;
    prev($playlist);
}
ver('lista al revés', $alReves);

// ---------------------------------------------------------------------------------------------
// each — devolvía la clave y el valor actuales y avanzaba el puntero. ELIMINADA en PHP 8.
// ENLACE: https://www.php.net/manual/es/function.each.php
// ENUNCIADO EJEMPLO: ver que each() ya no existe y escribir el recorrido equivalente con foreach
//   (que no usa ni modifica el puntero interno).
// NOTA: en código antiguo verás:  while (list($clave, $valor) = each($array)) { ... }
// ---------------------------------------------------------------------------------------------
titulo('each (eliminada) -> foreach');
ver('¿existe each?', function_exists('each'));          // false
$recorrido     = [];
foreach ($playlist as $clave => $valor) {
    $recorrido[] = "$clave => $valor";
}
ver('con foreach', $recorrido);

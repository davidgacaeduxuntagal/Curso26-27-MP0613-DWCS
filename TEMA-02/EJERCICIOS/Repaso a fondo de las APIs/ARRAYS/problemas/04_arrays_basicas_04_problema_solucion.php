<?php
/*
 * 04_arrays_basicas_04_problema_solucion.php
 *  (FICHERO CON LAS SOLUCIONES)
 *  Problemas de: array_slice, array_splice, array_chunk, array_rand, shuffle, array_sum, array_product
 *  (repasa los ejemplos de ../04_arrays_basicas_04.php)
 *
 *  Resuelve cada problema donde se indica y ejecútalo en modo depuración para comprobarlo.
 */
require_once __DIR__ . '/../utilidades.php';

// =============================================================================================
// PROBLEMA 1. Paginación
//  Hay 23 noticias y se muestran 5 por página. Calcula el número de páginas y obtén las noticias
//  de la última página con array_slice.
// =============================================================================================
titulo('Problema 1');
$noticias  = [];
foreach (range(1, 23) as $n) {
    $noticias[] = "Noticia $n";
}
$porPagina = 5;
// completa aquí tu solución
// ---- SOLUCIÓN ----
$paginas = (int) ceil(count($noticias) / $porPagina);              // 5
$ultima  = array_slice($noticias, ($paginas - 1) * $porPagina, $porPagina);
ver('páginas', $paginas);
ver('última página', $ultima);     // Noticia 21, 22, 23

// =============================================================================================
// PROBLEMA 2. Reordenar la lista de reproducción
//  Mueve la canción de la posición 3 (empezando en 0) a la posición 0. Pista: array_splice para
//  quitarla (devuelve un array) y array_splice (o array_unshift) para insertarla.
// =============================================================================================
titulo('Problema 2');
$canciones = ["A", "B", "C", "D", "E"];
// completa aquí tu solución
// ---- SOLUCIÓN ----
$quitada = array_splice($canciones, 3, 1);       // ["D"]
array_splice($canciones, 0, 0, $quitada);
ver('lista', $canciones);          // ["D", "A", "B", "C", "E"]

// =============================================================================================
// PROBLEMA 3. Equipos aleatorios
//  Baraja los alumnos y repártelos en equipos de 4. Después, elige al azar el capitán de cada
//  equipo con array_rand.
// =============================================================================================
titulo('Problema 3');
$alumnos = ["Ana", "Brais", "Carmen", "Diego", "Eva", "Fran", "Gael", "Iria"];
// completa aquí tu solución
// ---- SOLUCIÓN ----
shuffle($alumnos);
$equipos    = array_chunk($alumnos, 4);
$capitanes  = [];
foreach ($equipos as $i => $equipo) {
    $capitanes["Equipo " . ($i + 1)] = $equipo[array_rand($equipo)];
}
ver('equipos', $equipos);
ver('capitanes', $capitanes);

// =============================================================================================
// PROBLEMA 4. Media sin la peor y la mejor nota
//  En una competición de saltos se descartan la nota más alta y la más baja de los jueces.
//  Calcula la media del resto. Pista: ordena con sort y quita los extremos con array_slice.
// =============================================================================================
titulo('Problema 4');
$jueces = [7.5, 9.0, 8.0, 6.0, 8.5];
// completa aquí tu solución
// ---- SOLUCIÓN ----
sort($jueces);
$centrales = array_slice($jueces, 1, -1);                 // [7.5, 8.0, 8.5]
$media     = array_sum($centrales) / count($centrales);   // 8
ver('notas centrales', $centrales);
ver('media', $media);

// =============================================================================================
// PROBLEMA 5. Interés compuesto
//  Un depósito de 1000 € renta un 3 %, un 2,5 % y un 4 % en tres años consecutivos. El capital
//  final es 1000 x 1,03 x 1,025 x 1,04. Calcúlalo con array_product.
// =============================================================================================
titulo('Problema 5');
$capital = 1000;
$interes = [3, 2.5, 4];
// completa aquí tu solución
// ---- SOLUCIÓN ----
$factores = [];
foreach ($interes as $porcentaje) {
    $factores[] = 1 + $porcentaje / 100;
}
$final = $capital * array_product($factores);
ver('capital final', round($final, 2));     // 1097.98

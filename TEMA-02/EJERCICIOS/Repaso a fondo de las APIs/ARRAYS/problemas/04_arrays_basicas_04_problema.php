<?php
/*
 * 04_arrays_basicas_04_problema.php
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

// =============================================================================================
// PROBLEMA 2. Reordenar la lista de reproducción
//  Mueve la canción de la posición 3 (empezando en 0) a la posición 0. Pista: array_splice para
//  quitarla (devuelve un array) y array_splice (o array_unshift) para insertarla.
// =============================================================================================
titulo('Problema 2');
$canciones = ["A", "B", "C", "D", "E"];
// completa aquí tu solución

// =============================================================================================
// PROBLEMA 3. Equipos aleatorios
//  Baraja los alumnos y repártelos en equipos de 4. Después, elige al azar el capitán de cada
//  equipo con array_rand.
// =============================================================================================
titulo('Problema 3');
$alumnos = ["Ana", "Brais", "Carmen", "Diego", "Eva", "Fran", "Gael", "Iria"];
// completa aquí tu solución

// =============================================================================================
// PROBLEMA 4. Media sin la peor y la mejor nota
//  En una competición de saltos se descartan la nota más alta y la más baja de los jueces.
//  Calcula la media del resto. Pista: ordena con sort y quita los extremos con array_slice.
// =============================================================================================
titulo('Problema 4');
$jueces = [7.5, 9.0, 8.0, 6.0, 8.5];
// completa aquí tu solución

// =============================================================================================
// PROBLEMA 5. Interés compuesto
//  Un depósito de 1000 € renta un 3 %, un 2,5 % y un 4 % en tres años consecutivos. El capital
//  final es 1000 x 1,03 x 1,025 x 1,04. Calcúlalo con array_product.
// =============================================================================================
titulo('Problema 5');
$capital = 1000;
$interes = [3, 2.5, 4];
// completa aquí tu solución

<?php
/*
 * 09_arrays_iterar_01_problema_solucion.php
 *  (FICHERO CON LAS SOLUCIONES)
 *  Problemas de: array_map, array_filter, array_reduce, array_values, array_walk, array_walk_recursive
 *  (repasa los ejemplos de ../09_arrays_iterar_01.php)
 *
 *  Resuelve cada problema donde se indica y ejecútalo en modo depuración para comprobarlo.
 *  Intenta resolverlos SIN usar foreach.
 */
require_once __DIR__ . '/../utilidades.php';

$alumnos = [
    ['nombre' => "Ana",    'notas' => [7, 8, 9]],
    ['nombre' => "Brais",  'notas' => [4, 5, 3]],
    ['nombre' => "Carmen", 'notas' => [10, 9, 9.5]],
    ['nombre' => "Diego",  'notas' => [5, 4, 6]],
];

// =============================================================================================
// PROBLEMA 1. Medias de cada alumno
//  Obtén con array_map un array nombre => media (redondeada a 2 decimales).
//  Pista: array_map devuelve las medias y array_combine las une con los nombres (array_column).
// =============================================================================================
titulo('Problema 1');
// completa aquí tu solución
// ---- SOLUCIÓN ----
$medias = array_combine(
    array_column($alumnos, 'nombre'),
    array_map(fn($a) => round(array_sum($a['notas']) / count($a['notas']), 2), $alumnos)
);
ver('medias', $medias);     // Ana 8, Brais 4, Carmen 9.5, Diego 5

// =============================================================================================
// PROBLEMA 2. Aprobados
//  Con array_filter, quédate con los alumnos con media >= 5 (usa $medias del problema 1) y
//  obtén la lista de sus nombres renumerada.
// =============================================================================================
titulo('Problema 2');
// completa aquí tu solución
// ---- SOLUCIÓN ----
$aprobados = array_filter($medias, fn($media) => $media >= 5);
ver('aprobados', array_keys($aprobados));       // ["Ana", "Carmen", "Diego"]

// =============================================================================================
// PROBLEMA 3. La nota más alta de todo el grupo
//  Con array_reduce, encuentra la nota más alta de todas las notas de todos los alumnos y el
//  número total de notas.
// =============================================================================================
titulo('Problema 3');
// completa aquí tu solución
// ---- SOLUCIÓN ----
$maxima = array_reduce($alumnos, fn($max, $a) => max($max, max($a['notas'])), 0);
$total  = array_reduce($alumnos, fn($suma, $a) => $suma + count($a['notas']), 0);
ver('nota más alta', $maxima);    // 10
ver('total de notas', $total);    // 12

// =============================================================================================
// PROBLEMA 4. Subir un punto a los suspensos
//  Con array_walk (pasando cada alumno por referencia), suma 1 punto a cada nota inferior a 5,
//  sin pasar de 5. Después muestra las notas de Brais y Diego.
// =============================================================================================
titulo('Problema 4');
// completa aquí tu solución
// ---- SOLUCIÓN ----
array_walk($alumnos, function (&$alumno) {
    $alumno['notas'] = array_map(fn($n) => $n < 5 ? min($n + 1, 5) : $n, $alumno['notas']);
});
ver('Brais', $alumnos[1]['notas']);   // [5, 5, 4]
ver('Diego', $alumnos[3]['notas']);   // [5, 5, 6]

// =============================================================================================
// PROBLEMA 5. Limpiar datos anidados
//  Los datos de un pedido tienen espacios de más en todos los textos, a cualquier nivel. Límpialos
//  con array_walk_recursive y trim. Después, quita con array_filter las líneas vacías del pedido y
//  renumera con array_values.
// =============================================================================================
titulo('Problema 5');
$pedido = [
    'cliente' => "  Ana Pérez ",
    'envio'   => ['calle' => " Mayor 5 ", 'ciudad' => "Vigo  "],
    'lineas'  => ["  camiseta", "", "  ", "gorra  "],
];
// completa aquí tu solución
// ---- SOLUCIÓN ----
array_walk_recursive($pedido, function (&$valor) {
    $valor = trim($valor);
});
$pedido['lineas'] = array_values(array_filter($pedido['lineas']));
ver('pedido limpio', $pedido);   // lineas => ["camiseta", "gorra"]

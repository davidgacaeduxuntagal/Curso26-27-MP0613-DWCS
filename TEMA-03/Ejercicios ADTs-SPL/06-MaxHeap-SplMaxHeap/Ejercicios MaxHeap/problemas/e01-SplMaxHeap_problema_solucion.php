<?php
/*
 * e01-SplMaxHeap_problema_solucion.php
 *  (FICHERO CON LAS SOLUCIONES)
 *  Problemas de: SplMaxHeap (insert, extract, top, valid / current / next, clone, arrays)
 *  (repasa los ejemplos de ../e01-SplMaxHeap.php)
 *
 *  Resuelve cada problema donde se indica y ejecútalo en modo depuración para comprobarlo.
 */
require_once __DIR__ . '/../../../utilidades.php';

// =============================================================================================
// PROBLEMA 1. El podio
//  Mete en un SplMaxHeap las marcas de salto de longitud de $saltos (atleta => metros) como
//  [metros, atleta] y muestra el podio (los 3 primeros) con el formato "1.º Ana (6.42 m)".
// =============================================================================================
titulo('Problema 1');
$saltos = ['Ana' => 6.42, 'Brais' => 6.88, 'Carmen' => 6.15, 'Diego' => 6.71, 'Eva' => 6.90];
// completa aquí tu solución
// ---- SOLUCIÓN ----
$heap = new SplMaxHeap();
foreach ($saltos as $atleta => $metros) {
    $heap->insert([$metros, $atleta]);
}
$podio = [];
for ($puesto = 1; $puesto <= 3; $puesto++) {
    [$metros, $atleta] = $heap->extract();
    $podio[] = "$puesto.º $atleta ($metros m)";
}
ver('podio', $podio);    // ['1.º Eva (6.9 m)', '2.º Brais (6.88 m)', '3.º Diego (6.71 m)']

// =============================================================================================
// PROBLEMA 2. Las palabras más repetidas
//  Cuenta las palabras de $texto (en minúsculas) con array_count_values y usa un SplMaxHeap de
//  [veces, palabra] para mostrar las 3 más repetidas.
// =============================================================================================
titulo('Problema 2');
$texto = 'el pulpo de la feria y el pan de la feria con el vino de la casa';
// completa aquí tu solución
// ---- SOLUCIÓN ----
$veces = array_count_values(explode(' ', mb_strtolower($texto)));
$heap = new SplMaxHeap();
foreach ($veces as $palabra => $n) {
    $heap->insert([$n, $palabra]);
}
$top = [];
for ($i = 0; $i < 3; $i++) {
    [$n, $palabra] = $heap->extract();
    $top[] = "$palabra ($n)";
}
ver('más repetidas', $top);   // ['la (3)', 'el (3)', 'de (3)'] (a igual número, la palabra "mayor" primero)

// =============================================================================================
// PROBLEMA 3. ¿Qué pasa aquí?
//  Este código quiere listar las pujas de mayor a menor y después decir cuántas hay, pero dice
//  que hay 0. Explica el fallo en un comentario y corrígelo.
// =============================================================================================
titulo('Problema 3');
$pujas = new SplMaxHeap();
foreach ([100, 140, 120] as $p) {
    $pujas->insert($p);
}
foreach ($pujas as $p) {
    echo "  puja: $p", nl();             // 140 / 120 / 100
}
ver('pujas (mal)', count($pujas));      // 0
// completa aquí tu solución
// ---- SOLUCIÓN ----
// Fallo: recorrer un montículo con foreach EXTRAE los elementos (en cada vuelta, next() saca la
// raíz). Para listarlos sin perderlos hay que recorrer una COPIA (clone). Y para ver solo el
// máximo basta con top(), que no saca nada.
foreach ([100, 140, 120] as $p) {
    $pujas->insert($p);                 // los volvemos a meter
}
foreach (clone $pujas as $p) {
    echo "  puja: $p", nl();
}
ver('pujas (bien)', count($pujas));     // 3
ver('máxima', $pujas->top());           // 140

// =============================================================================================
// PROBLEMA 4. ¿Cómo harías...?
//  ¿Cómo obtendrías los 2 alumnos con PEOR nota de $notas usando un SplMaxHeap de tamaño 2
//  (como en el ejemplo de los vuelos más baratos)? Escríbelo.
// =============================================================================================
titulo('Problema 4');
$notas = ['Ana' => 7.5, 'Brais' => 4.0, 'Carmen' => 9.0, 'Diego' => 3.5, 'Eva' => 6.0];
// completa aquí tu solución
// ---- SOLUCIÓN ----
$peores = new SplMaxHeap();                  // guarda los 2 peores; su raíz, el "menos malo"
foreach ($notas as $alumno => $nota) {
    $peores->insert([$nota, $alumno]);
    if (count($peores) > 2) {
        $peores->extract();                  // fuera el mejor de los 3
    }
}
ver('los 2 peores', array_reverse(iterator_to_array($peores, false)));   // [[3.5, 'Diego'], [4.0, 'Brais']]

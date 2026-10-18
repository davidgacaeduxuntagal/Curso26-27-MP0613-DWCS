<?php
/*
 * e01-SplMaxHeap_problema.php
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

// =============================================================================================
// PROBLEMA 2. Las palabras más repetidas
//  Cuenta las palabras de $texto (en minúsculas) con array_count_values y usa un SplMaxHeap de
//  [veces, palabra] para mostrar las 3 más repetidas.
// =============================================================================================
titulo('Problema 2');
$texto = 'el pulpo de la feria y el pan de la feria con el vino de la casa';
// completa aquí tu solución

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

// =============================================================================================
// PROBLEMA 4. ¿Cómo harías...?
//  ¿Cómo obtendrías los 2 alumnos con PEOR nota de $notas usando un SplMaxHeap de tamaño 2
//  (como en el ejemplo de los vuelos más baratos)? Escríbelo.
// =============================================================================================
titulo('Problema 4');
$notas = ['Ana' => 7.5, 'Brais' => 4.0, 'Carmen' => 9.0, 'Diego' => 3.5, 'Eva' => 6.0];
// completa aquí tu solución

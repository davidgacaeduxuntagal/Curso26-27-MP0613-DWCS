<?php
/*
 * 07_arrays_ordenar_01_problema_solucion.php
 *  (FICHERO CON LAS SOLUCIONES)
 *  Problemas de: sort, rsort, asort, arsort, ksort, krsort, array_reverse
 *  (repasa los ejemplos de ../07_arrays_ordenar_01.php)
 *
 *  Resuelve cada problema donde se indica y ejecútalo en modo depuración para comprobarlo.
 */
require_once __DIR__ . '/../utilidades.php';

// =============================================================================================
// PROBLEMA 1. Podio
//  Obtén los 3 primeros clasificados de una carrera (menor tiempo = mejor) conservando sus nombres.
// =============================================================================================
titulo('Problema 1');
$tiempos = ['Uxía' => 41.2, 'Brais' => 39.8, 'Carmen' => 40.5, 'Diego' => 43.0, 'Eva' => 39.9];
// completa aquí tu solución
// ---- SOLUCIÓN ----
asort($tiempos);
$podio = array_slice($tiempos, 0, 3, true);
ver('podio', $podio);      // Brais 39.8, Eva 39.9, Carmen 40.5

// =============================================================================================
// PROBLEMA 2. Mediana
//  La mediana es el valor central de los datos ordenados (si hay un número par, la media de los
//  dos centrales). Calcula la mediana de los dos arrays.
// =============================================================================================
titulo('Problema 2');
$impares = [7, 3, 9, 1, 5];
$pares   = [8, 2, 6, 4];
// completa aquí tu solución
// ---- SOLUCIÓN ----
$medianas = [];
foreach (['impares' => $impares, 'pares' => $pares] as $nombre => $datos) {
    sort($datos);
    $n     = count($datos);
    $medio = intdiv($n, 2);
    $medianas[$nombre] = $n % 2 === 1 ? $datos[$medio] : ($datos[$medio - 1] + $datos[$medio]) / 2;
}
ver('medianas', $medianas);    // ['impares' => 5, 'pares' => 5]

// =============================================================================================
// PROBLEMA 3. Índice de un libro
//  Ordena el índice alfabético (palabra => página) por palabra, sin distinguir mayúsculas.
//  Pista: ksort con SORT_STRING | SORT_FLAG_CASE.
// =============================================================================================
titulo('Problema 3');
$indice = ['variable' => 12, 'Array' => 30, 'función' => 45, 'Bucle' => 20];
// completa aquí tu solución
// ---- SOLUCIÓN ----
ksort($indice, SORT_STRING | SORT_FLAG_CASE);
ver('índice', $indice);       // Array, Bucle, función, variable

// =============================================================================================
// PROBLEMA 4. Productos más vendidos
//  A partir de las ventas (producto repetido una vez por cada venta), obtén el ranking de
//  productos de más a menos vendido. Pista: array_count_values + arsort.
// =============================================================================================
titulo('Problema 4');
$ventas = ["café", "té", "café", "zumo", "café", "té", "agua"];
// completa aquí tu solución
// ---- SOLUCIÓN ----
$ranking = array_count_values($ventas);
arsort($ranking);
ver('ranking', $ranking);     // café 3, té 2, zumo 1, agua 1

// =============================================================================================
// PROBLEMA 5. Historial de pedidos
//  Muestra los pedidos (fecha => importe) del más reciente al más antiguo con krsort, y comprueba
//  que array_reverse sobre el array ordenado con ksort da el mismo resultado.
// =============================================================================================
titulo('Problema 5');
$pedidos = ['2026-03-15' => 45.0, '2026-10-02' => 12.5, '2025-12-24' => 80.0];
// completa aquí tu solución
// ---- SOLUCIÓN ----
$recientes = $pedidos;
krsort($recientes);
$ascendente = $pedidos;
ksort($ascendente);
$invertido = array_reverse($ascendente, true);
ver('del más reciente al más antiguo', $recientes);
ver('¿mismo resultado?', $recientes === $invertido);   // true

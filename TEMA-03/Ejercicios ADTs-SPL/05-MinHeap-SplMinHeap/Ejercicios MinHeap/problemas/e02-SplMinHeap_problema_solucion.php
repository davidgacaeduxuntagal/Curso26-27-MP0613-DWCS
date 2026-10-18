<?php
/*
 * e02-SplMinHeap_problema_solucion.php
 *  (FICHERO CON LAS SOLUCIONES)
 *  Problemas de: aplicaciones de los montículos mínimos (top K, fusionar listas, asignar
 *                recursos, el K-ésimo menor)
 *  (repasa los ejemplos de ../e02-SplMinHeap.php)
 *
 *  Resuelve cada problema donde se indica y ejecútalo en modo depuración para comprobarlo.
 */
require_once __DIR__ . '/../../../utilidades.php';

// =============================================================================================
// PROBLEMA 1. Los 3 pedidos más grandes del día
//  Recorre $pedidos UNA sola vez manteniendo un SplMinHeap de como mucho 3 elementos
//  [importe, número de pedido], y muestra los 3 pedidos de mayor importe, de mayor a menor.
// =============================================================================================
titulo('Problema 1');
$pedidos = [1001 => 45.90, 1002 => 120.00, 1003 => 18.50, 1004 => 310.25, 1005 => 99.99, 1006 => 150.00];
// completa aquí tu solución
// ---- SOLUCIÓN ----
$mayores = new SplMinHeap();
foreach ($pedidos as $numero => $importe) {
    $mayores->insert([$importe, $numero]);
    if (count($mayores) > 3) {
        $mayores->extract();                 // fuera el menor de los 4
    }
}
$top = array_reverse(iterator_to_array($mayores, false));
ver('top 3', array_map(fn($p) => "pedido {$p[1]}: {$p[0]} €", $top));
// ['pedido 1004: 310.25 €', 'pedido 1006: 150 €', 'pedido 1002: 120 €']

// =============================================================================================
// PROBLEMA 2. Salas de reuniones
//  Cada reunión de $reuniones tiene hora de inicio y fin (en minutos desde las 8:00). ¿Cuántas
//  salas hacen falta como mínimo para que no se solapen? Ordena las reuniones por inicio y usa un
//  SplMinHeap con la hora de FIN de las reuniones en curso: si la que termina antes ya ha acabado
//  cuando empieza la nueva, esa sala se reutiliza.
// =============================================================================================
titulo('Problema 2');
$reuniones = [[0, 60], [30, 90], [60, 120], [100, 150], [10, 40]];
// completa aquí tu solución
// ---- SOLUCIÓN ----
function salasNecesarias(array $reuniones): int
{
    usort($reuniones, fn($a, $b) => $a[0] <=> $b[0]);
    $finales = new SplMinHeap();                 // hora de fin de las salas ocupadas
    foreach ($reuniones as [$inicio, $fin]) {
        if (!$finales->isEmpty() && $finales->top() <= $inicio) {
            $finales->extract();                 // esa sala ya está libre: se reutiliza
        }
        $finales->insert($fin);
    }
    return count($finales);
}
ver('salas', salasNecesarias($reuniones));     // 3 (entre los minutos 30 y 40 coinciden 3 reuniones)

// =============================================================================================
// PROBLEMA 3. El K-ésimo menor
//  Escribe kesimoMenor(array $valores, int $k) que devuelva el k-ésimo valor más pequeño (k = 1
//  es el mínimo) usando un SplMinHeap. Pruébalo con las edades de $socios.
// =============================================================================================
titulo('Problema 3');
$socios = [34, 19, 52, 27, 41, 23, 60];
// completa aquí tu solución
// ---- SOLUCIÓN ----
function kesimoMenor(array $valores, int $k): mixed
{
    if ($k < 1 || $k > count($valores)) {
        throw new OutOfRangeException("k debe estar entre 1 y " . count($valores));
    }
    $heap = new SplMinHeap();
    foreach ($valores as $v) {
        $heap->insert($v);
    }
    for ($i = 1; $i < $k; $i++) {
        $heap->extract();
    }
    return $heap->top();
}
ver('el más joven', kesimoMenor($socios, 1));    // 19
ver('el 3.º más joven', kesimoMenor($socios, 3)); // 27
ver('la mediana (4.º de 7)', kesimoMenor($socios, 4));   // 34

// =============================================================================================
// PROBLEMA 4. ¿Cómo harías...?
//  Tienes las notas de tres grupos, cada una ya ordenada de menor a mayor. ¿Cómo obtendrías una
//  única lista ordenada sin juntar los arrays y llamar a sort()? Usa la técnica de "fusionar
//  registros" del ejemplo.
// =============================================================================================
titulo('Problema 4');
$grupos = ['A' => [4.5, 6.0, 8.25], 'B' => [5.0, 5.5, 9.0, 9.5], 'C' => [3.75, 7.0]];
// completa aquí tu solución
// ---- SOLUCIÓN ----
$heap = new SplMinHeap();
foreach ($grupos as $g => $notas) {
    $heap->insert([$notas[0], $g, 0]);
}
$todas = [];
while (!$heap->isEmpty()) {
    [$nota, $g, $i] = $heap->extract();
    $todas[] = $nota;
    if (isset($grupos[$g][$i + 1])) {
        $heap->insert([$grupos[$g][$i + 1], $g, $i + 1]);
    }
}
ver('todas ordenadas', $todas);   // [3.75, 4.5, 5.0, 5.5, 6.0, 7.0, 8.25, 9.0, 9.5]

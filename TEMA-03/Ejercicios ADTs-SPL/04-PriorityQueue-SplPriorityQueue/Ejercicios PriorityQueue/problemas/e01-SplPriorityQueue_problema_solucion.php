<?php
/*
 * e01-SplPriorityQueue_problema_solucion.php
 *  (FICHERO CON LAS SOLUCIONES)
 *  Problemas de: SplPriorityQueue (insert, extract, top, setExtractFlags, desempate con arrays,
 *                clone, compare)
 *  (repasa los ejemplos de ../e01-SplPriorityQueue.php)
 *
 *  Resuelve cada problema donde se indica y ejecútalo en modo depuración para comprobarlo.
 */
require_once __DIR__ . '/../../../utilidades.php';

// =============================================================================================
// PROBLEMA 1. Embarque por grupos
//  En el aeropuerto embarcan primero los pasajeros de los grupos prioritarios: 'familias' (3),
//  'business' (2) y 'general' (1). Mete $pasajeros en una SplPriorityQueue y muestra el orden de
//  embarque. A igual grupo, debe embarcar antes el que llegó antes a la puerta.
// =============================================================================================
titulo('Problema 1');
$grupos = ['familias' => 3, 'business' => 2, 'general' => 1];
$pasajeros = [['Ana', 'general'], ['Brais', 'business'], ['Carmen', 'familias'], ['Diego', 'general'], ['Eva', 'business']];
// completa aquí tu solución
// ---- SOLUCIÓN ----
$embarque = new SplPriorityQueue();
foreach ($pasajeros as $orden => [$nombre, $grupo]) {
    $embarque->insert($nombre, [$grupos[$grupo], -$orden]);
}
$orden = [];
while (!$embarque->isEmpty()) {
    $orden[] = $embarque->extract();
}
ver('orden de embarque', $orden);    // ['Carmen', 'Brais', 'Eva', 'Ana', 'Diego']

// =============================================================================================
// PROBLEMA 2. Con su prioridad
//  Con la cola $tareas, muestra cada tarea con su prioridad ("[5] Copia de seguridad") en el
//  orden en que se harán, SIN vaciar la cola original. Comprueba al final que sigue entera.
// =============================================================================================
titulo('Problema 2');
$tareas = new SplPriorityQueue();
$tareas->insert('Actualizar PHP', 3);
$tareas->insert('Copia de seguridad', 5);
$tareas->insert('Revisar logs', 1);
// completa aquí tu solución
// ---- SOLUCIÓN ----
$copia = clone $tareas;
$copia->setExtractFlags(SplPriorityQueue::EXTR_BOTH);
$lista = [];
foreach ($copia as $t) {
    $lista[] = "[{$t['priority']}] {$t['data']}";
}
ver('tareas', $lista);               // ['[5] Copia de seguridad', '[3] Actualizar PHP', '[1] Revisar logs']
ver('la original sigue', count($tareas));   // 3

// =============================================================================================
// PROBLEMA 3. Primero lo que antes caduca
//  En el almacén de un supermercado hay que vender primero los productos que caducan antes.
//  Crea la clase ColaCaducidad (hereda de SplPriorityQueue y redefine compare) para que salga
//  primero la fecha MÁS PRÓXIMA ("Y-m-d", que se puede comparar como texto).
// =============================================================================================
titulo('Problema 3');
// completa aquí tu solución
// ---- SOLUCIÓN ----
final class ColaCaducidad extends SplPriorityQueue
{
    public function compare(mixed $fecha1, mixed $fecha2): int
    {
        return strcmp($fecha2, $fecha1);        // la fecha menor tiene más prioridad
    }
}
$almacen = new ColaCaducidad();
$almacen->insert('yogures (lote 7)', '2026-10-25');
$almacen->insert('leche (lote 3)', '2026-10-20');
$almacen->insert('queso (lote 1)', '2026-11-30');
$almacen->insert('yogures (lote 8)', '2026-10-22');
ver('vender en este orden', iterator_to_array($almacen, false));
// ['leche (lote 3)', 'yogures (lote 8)', 'yogures (lote 7)', 'queso (lote 1)']

// =============================================================================================
// PROBLEMA 4. ¿Cómo harías...?
//  ¿Cómo obtendrías los 3 productos más vendidos de $ventas (producto => unidades) usando una
//  SplPriorityQueue? ¿Y sin ella? Compara las dos soluciones.
// =============================================================================================
titulo('Problema 4');
$ventas = ['ratón' => 120, 'teclado' => 85, 'monitor' => 40, 'cascos' => 150, 'webcam' => 60, 'cable' => 300];
// completa aquí tu solución
// ---- SOLUCIÓN ----
$cola = new SplPriorityQueue();
foreach ($ventas as $producto => $unidades) {
    $cola->insert($producto, $unidades);
}
$top3 = [];
for ($i = 0; $i < 3 && !$cola->isEmpty(); $i++) {
    $top3[] = $cola->extract();
}
ver('con SplPriorityQueue', $top3);              // ['cable', 'cascos', 'ratón']
arsort($ventas);
ver('con arsort', array_slice(array_keys($ventas), 0, 3));   // el mismo resultado
// Las dos valen. arsort ordena TODO (O(n log n)); la cola también inserta todo, pero si los datos
// llegan poco a poco (un flujo de ventas) se puede ir consultando el máximo en cualquier momento.
// Para quedarse con los k mayores de muchísimos datos, lo mejor es un SplMinHeap de tamaño k (ver
// los ejercicios de los montículos).

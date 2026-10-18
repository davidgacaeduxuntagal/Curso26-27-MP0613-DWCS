<?php
/*
 * e01-SplMaxHeap.php
 *  MONTÍCULO MÁXIMO (Max-heap) con SplMaxHeap (1): LA API
 *  Métodos: insert, extract, top, count, isEmpty, valid / current / next, foreach (lo vacía),
 *           clone, arrays [valor, dato] y desempate, comparación con SplMinHeap y SplPriorityQueue
 *
 *  Un MONTÍCULO MÁXIMO es como el mínimo pero al revés: cada nodo es MAYOR O IGUAL que sus hijos,
 *  así que el MÁXIMO está siempre en la raíz. top() es O(1); insert y extract, O(log n).
 *
 *  Cómo trabajar este fichero:
 *   1. Pon un punto de ruptura en la primera línea de código y ejecuta en modo depuración.
 *   2. ver() muestra el montículo en el orden en que saldrían sus elementos (sobre una copia).
 */
require_once __DIR__ . '/../../utilidades.php';

// ---------------------------------------------------------------------------------------------
// insert, top, extract
// ENLACE: https://www.php.net/manual/es/class.splmaxheap.php
// ENLACE: https://www.php.net/manual/es/splheap.top.php
// ENUNCIADO EJEMPLO: la puja más alta de una subasta online, que se actualiza con cada puja.
// ---------------------------------------------------------------------------------------------
titulo('insert, top, extract');
$pujas = new SplMaxHeap();
foreach ([120, 150, 135, 180, 175] as $puja) {
    $pujas->insert($puja);
    echo "  puja de $puja € -> la más alta es ", $pujas->top(), ' €', nl();   // 120 / 150 / 150 / 180 / 180
}
ver('montículo', $pujas);                    // en orden de salida: [180, 175, 150, 135, 120]
ver('gana', $pujas->extract());              // 180
ver('segunda', $pujas->top());               // 175
verError('vacío', fn() => (new SplMaxHeap())->extract());   // RuntimeException: Can't extract from an empty heap

// ---------------------------------------------------------------------------------------------
// Recorrido manual: valid, current, next (y por qué foreach lo vacía)
// ENLACE: https://www.php.net/manual/es/splheap.next.php
// ENUNCIADO EJEMPLO: ver cómo funciona el recorrido de un montículo por dentro.
// NOTA: current() es lo mismo que top(); next() EXTRAE la raíz. Por eso recorrer un montículo lo
//       vacía: foreach llama a next() en cada vuelta.
// ---------------------------------------------------------------------------------------------
titulo('valid, current, next');
$copia = clone $pujas;
while ($copia->valid()) {
    echo '  current: ', $copia->current(), ' (quedan ', count($copia), ')', nl();
    $copia->next();                          // extrae
}
ver('la copia', count($copia));              // 0
ver('el original', count($pujas));           // 4

// ---------------------------------------------------------------------------------------------
// Arrays [valor, dato]: el ranking de un concurso
// ENLACE: https://www.php.net/manual/es/language.operators.comparison.php
// ENUNCIADO EJEMPLO: clasificación de un concurso de tapas: más puntos primero; a igual
//                   puntuación, primero el que se presentó antes.
// NOTA: con [puntos, -orden] el desempate favorece al orden menor (al que llegó antes), igual que
//       en SplPriorityQueue.
// ---------------------------------------------------------------------------------------------
titulo('Ranking con desempate');
$concurso = new SplMaxHeap();
$presentadas = [['Bar Baio', 87], ['O Recuncho', 92], ['A Taberna', 87], ['Casa Pepe', 79]];
foreach ($presentadas as $orden => [$bar, $puntos]) {
    $concurso->insert([$puntos, -$orden, $bar]);
}
$clasificacion = [];
foreach ($concurso as [$puntos, , $bar]) {      // ¡lo vacía! (aquí ya no lo necesitamos)
    $clasificacion[] = "$bar: $puntos puntos";
}
ver('clasificación', $clasificacion);
// ['O Recuncho: 92 puntos', 'Bar Baio: 87 puntos', 'A Taberna: 87 puntos', 'Casa Pepe: 79 puntos']

// ---------------------------------------------------------------------------------------------
// SplMaxHeap, SplMinHeap y SplPriorityQueue: el mismo problema de tres formas
// ENLACE: https://www.php.net/manual/es/spl.datastructures.php
// ENUNCIADO EJEMPLO: sacar las notas de mayor a menor.
// NOTA: SplMaxHeap y SplPriorityQueue sacan primero el mayor; SplMinHeap, el menor (se puede
//       usar con -nota). SplPriorityQueue separa el dato de la prioridad.
// ---------------------------------------------------------------------------------------------
titulo('Tres formas');
$notas = ['Ana' => 7.5, 'Brais' => 9.25, 'Carmen' => 6.0];
$max = new SplMaxHeap();
$min = new SplMinHeap();
$pq = new SplPriorityQueue();
foreach ($notas as $alumno => $nota) {
    $max->insert([$nota, $alumno]);
    $min->insert([-$nota, $alumno]);
    $pq->insert($alumno, $nota);
}
ver('SplMaxHeap', array_map(fn($e) => $e[1], iterator_to_array($max, false)));   // ['Brais', 'Ana', 'Carmen']
ver('SplMinHeap con -nota', array_map(fn($e) => $e[1], iterator_to_array($min, false)));   // ['Brais', 'Ana', 'Carmen']
ver('SplPriorityQueue', iterator_to_array($pq, false));                          // ['Brais', 'Ana', 'Carmen']

<?php
/*
 * e01-SplQueue.php
 *  COLA (Queue) con SplQueue (1): LA API
 *  Métodos: enqueue, dequeue, bottom (el primero), top (el último), isEmpty, count, $cola[] = ...,
 *           foreach (orden FIFO), $cola[i], IT_MODE_DELETE, errores con la cola vacía,
 *           comparación con un array (array_shift es O(n))
 *
 *  Una COLA es una colección en la que los elementos entran por un extremo (el final) y salen por
 *  el otro (el frente): el primero en entrar es el primero en salir (FIFO: First In, First Out).
 *  Como la cola de una panadería.
 *
 *  Cómo trabajar este fichero:
 *   1. Pon un punto de ruptura en la primera línea de código y ejecuta en modo depuración.
 *   2. Avanza con F10: ver() muestra la cola del frente (el que saldrá antes) al final.
 */
require_once __DIR__ . '/../../utilidades.php';

// ---------------------------------------------------------------------------------------------
// enqueue y dequeue — Entrar en la cola y ser atendido
// ENLACE: https://www.php.net/manual/es/class.splqueue.php
// ENLACE: https://www.php.net/manual/es/splqueue.enqueue.php
// ENLACE: https://www.php.net/manual/es/splqueue.dequeue.php
// ENUNCIADO EJEMPLO: la cola de impresión de la secretaría del instituto.
// NOTA: enqueue añade al FINAL (es lo mismo que push); dequeue saca del FRENTE (es lo mismo que
//       shift). Las dos son O(1).
// ---------------------------------------------------------------------------------------------
titulo('enqueue y dequeue');
$impresora = new SplQueue();
$impresora->enqueue('matricula-ana.pdf');
$impresora->enqueue('notas-1DAW.pdf');
$impresora[] = 'horario.pdf';                  // también con [] (añade al final)
ver('cola', $impresora);                       // frente -> ['matricula-ana.pdf', 'notas-1DAW.pdf', 'horario.pdf'] <- final
ver('se imprime', $impresora->dequeue());      // "matricula-ana.pdf" (el primero que llegó)
ver('se imprime', $impresora->dequeue());      // "notas-1DAW.pdf"
ver('quedan', count($impresora));              // 1

// ---------------------------------------------------------------------------------------------
// bottom y top — Mirar los extremos sin sacar
// ENLACE: https://www.php.net/manual/es/spldoublylinkedlist.bottom.php
// ENLACE: https://www.php.net/manual/es/spldoublylinkedlist.top.php
// ENUNCIADO EJEMPLO: saber quién es el siguiente en ser atendido y quién acaba de llegar.
// NOTA: ¡nombres confusos! En una SplQueue, bottom() es el FRENTE (el siguiente en salir) y top()
//       es el FINAL (el último que llegó), porque la cola es una lista donde se mete por "arriba".
// ---------------------------------------------------------------------------------------------
titulo('bottom y top');
$turnos = new SplQueue();
foreach (['Ana', 'Brais', 'Carmen'] as $persona) {
    $turnos->enqueue($persona);
}
ver('siguiente (bottom)', $turnos->bottom());   // "Ana"
ver('el último (top)', $turnos->top());         // "Carmen"
ver('$turnos[0]', $turnos[0]);                  // "Ana" (en una cola, la posición 0 es el frente)

// ---------------------------------------------------------------------------------------------
// Cola vacía
// ENLACE: https://www.php.net/manual/es/splqueue.dequeue.php
// ENUNCIADO EJEMPLO: la impresora pide el siguiente trabajo cuando no hay ninguno.
// ---------------------------------------------------------------------------------------------
titulo('Cola vacía');
$vacia = new SplQueue();
verError('dequeue', fn() => $vacia->dequeue());   // RuntimeException: Can't shift from an empty datastructure
verError('bottom', fn() => $vacia->bottom());     // RuntimeException: Can't peek at an empty datastructure
while (!$impresora->isEmpty()) {                   // la forma habitual de vaciar una cola
    ver('se imprime', $impresora->dequeue());      // "horario.pdf"
}

// ---------------------------------------------------------------------------------------------
// foreach e IT_MODE_DELETE
// ENLACE: https://www.php.net/manual/es/spldoublylinkedlist.setiteratormode.php
// ENUNCIADO EJEMPLO: ver la lista de espera sin atender a nadie, y después atenderlos a todos.
// NOTA: foreach recorre del frente al final (FIFO) sin sacar a nadie. Con IT_MODE_DELETE, foreach
//       va sacando cada elemento (como un bucle de dequeue).
// ---------------------------------------------------------------------------------------------
titulo('foreach');
foreach ($turnos as $posicion => $persona) {
    echo "  $posicion: $persona", nl();            // 0: Ana / 1: Brais / 2: Carmen
}
$turnos->setIteratorMode(SplDoublyLinkedList::IT_MODE_FIFO | SplDoublyLinkedList::IT_MODE_DELETE);
foreach ($turnos as $persona) {
    echo "  atendiendo a $persona", nl();
}
ver('en espera', count($turnos));                  // 0

// ---------------------------------------------------------------------------------------------
// Comparación con un array: array_shift es LENTO
// ENLACE: https://www.php.net/manual/es/function.array-shift.php
// ENUNCIADO EJEMPLO: atender 20 000 peticiones con un array y con una SplQueue.
// NOTA: con un array, sacar el primero (array_shift) obliga a renumerar TODOS los demás: es O(n).
//       Con muchos elementos, la diferencia es enorme. SplQueue::dequeue es O(1).
// ---------------------------------------------------------------------------------------------
titulo('array_shift frente a dequeue');
$n = 20_000;
$array = range(1, $n);
$inicio = hrtime(true);
while ($array !== []) {
    array_shift($array);
}
$msArray = (hrtime(true) - $inicio) / 1e6;

$cola = new SplQueue();
foreach (range(1, $n) as $x) {
    $cola->enqueue($x);
}
$inicio = hrtime(true);
while (!$cola->isEmpty()) {
    $cola->dequeue();
}
$msCola = (hrtime(true) - $inicio) / 1e6;
ver('¿SplQueue más rápida?', $msCola < $msArray);    // true (en un portátil normal, cientos de veces más rápida)
printf('  array_shift: %.1f ms | dequeue: %.1f ms%s', $msArray, $msCola, nl());

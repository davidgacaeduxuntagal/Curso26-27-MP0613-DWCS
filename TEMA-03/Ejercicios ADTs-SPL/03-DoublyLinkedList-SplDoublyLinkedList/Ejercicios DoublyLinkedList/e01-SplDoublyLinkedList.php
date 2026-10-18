<?php
/*
 * e01-SplDoublyLinkedList.php
 *  LISTA DOBLEMENTE ENLAZADA con SplDoublyLinkedList (1): LA API
 *  Métodos: push / pop (final), unshift / shift (principio), top / bottom, add (insertar en una
 *           posición), $lista[i] (offsetGet / offsetSet / offsetUnset / offsetExists), count,
 *           isEmpty, modos de recorrido (IT_MODE_FIFO / IT_MODE_LIFO, IT_MODE_KEEP / IT_MODE_DELETE),
 *           recorrido manual (rewind, valid, current, key, next, prev)
 *
 *  Una LISTA DOBLEMENTE ENLAZADA es una secuencia de NODOS: cada nodo guarda un valor y dos
 *  enlaces, al nodo ANTERIOR y al SIGUIENTE. Por eso se puede añadir y quitar muy rápido por los
 *  dos extremos, y recorrer en los dos sentidos. SplStack y SplQueue son SplDoublyLinkedList con
 *  el sentido de recorrido fijado.
 *
 *  Cómo trabajar este fichero:
 *   1. Pon un punto de ruptura en la primera línea de código y ejecuta en modo depuración.
 *   2. Avanza con F10 y compara cada operación con la figura de los apuntes.
 */
require_once __DIR__ . '/../../utilidades.php';

// ---------------------------------------------------------------------------------------------
// push, pop, unshift, shift — Los dos extremos
// ENLACE: https://www.php.net/manual/es/class.spldoublylinkedlist.php
// ENLACE: https://www.php.net/manual/es/spldoublylinkedlist.unshift.php
// ENLACE: https://www.php.net/manual/es/spldoublylinkedlist.shift.php
// ENUNCIADO EJEMPLO: la lista de reproducción de una emisora: las canciones normales se añaden
//                   al final y los avisos urgentes, al principio.
// NOTA: push / pop trabajan por el FINAL (top) y unshift / shift por el PRINCIPIO (bottom). Las
//       cuatro son O(1). (Con un array, array_unshift y array_shift son O(n).)
// ---------------------------------------------------------------------------------------------
titulo('Los dos extremos');
$emisora = new SplDoublyLinkedList();
$emisora->push('Muiñeira de Chantada');
$emisora->push('Alalá');
$emisora->unshift('AVISO: tráfico en la AP-9');       // al principio
ver('lista', $emisora);                                // ['AVISO: tráfico en la AP-9', 'Muiñeira de Chantada', 'Alalá']
ver('bottom (primero)', $emisora->bottom());           // "AVISO: tráfico en la AP-9"
ver('top (último)', $emisora->top());                  // "Alalá"
ver('shift', $emisora->shift());                       // "AVISO: tráfico en la AP-9"
ver('pop', $emisora->pop());                           // "Alalá"
ver('lista', $emisora);                                // ['Muiñeira de Chantada']

// ---------------------------------------------------------------------------------------------
// add, offsetGet / offsetSet / offsetUnset — Trabajar por posición
// ENLACE: https://www.php.net/manual/es/spldoublylinkedlist.add.php
// ENLACE: https://www.php.net/manual/es/spldoublylinkedlist.offsetunset.php
// ENUNCIADO EJEMPLO: insertar una canción en la 2.ª posición, cambiar otra y borrar una.
// NOTA: add($i, $x) INSERTA delante de la posición $i (con $i = count, añade al final). Acceder
//       por posición en una lista enlazada es O(n): hay que ir saltando de nodo en nodo desde un
//       extremo. Las posiciones se reenumeran después de add y de unset.
// ---------------------------------------------------------------------------------------------
titulo('Por posición');
$lista = new SplDoublyLinkedList();
foreach (['A', 'B', 'D'] as $c) {
    $lista->push($c);
}
$lista->add(2, 'C');                       // delante de la posición 2 ('D')
ver('add(2, C)', $lista);                  // ['A', 'B', 'C', 'D']
$lista->add(count($lista), 'E');           // en la posición count: al final
$lista[0] = 'a';                           // offsetSet: cambia el valor
unset($lista[1]);                          // offsetUnset: borra y reenumera
ver('tras cambiar y borrar', $lista);      // ['a', 'C', 'D', 'E']
ver('$lista[1]', $lista[1]);               // "C" (antes era la posición 2)
ver('isset($lista[9])', isset($lista[9])); // false
verError('$lista[9]', fn() => $lista[9]);  // OutOfRangeException: SplDoublyLinkedList::offsetGet(): Argument #1 ($index) is out of range
verError('add(9, x)', fn() => $lista->add(9, 'x'));   // OutOfRangeException

// ---------------------------------------------------------------------------------------------
// Modos de recorrido: FIFO / LIFO y KEEP / DELETE
// ENLACE: https://www.php.net/manual/es/spldoublylinkedlist.setiteratormode.php
// ENUNCIADO EJEMPLO: mostrar el historial de pedidos del más antiguo al más reciente, y al revés.
// NOTA: a diferencia de SplStack y SplQueue, en una SplDoublyLinkedList se puede elegir el
//       sentido: IT_MODE_FIFO (del principio al final, por defecto) o IT_MODE_LIFO (del final al
//       principio). Y si el recorrido conserva (KEEP) o borra (DELETE) los elementos.
// ---------------------------------------------------------------------------------------------
titulo('Modos de recorrido');
$pedidos = new SplDoublyLinkedList();
foreach ([1001, 1002, 1003] as $p) {
    $pedidos->push($p);
}
ver('modo por defecto', $pedidos->getIteratorMode() === SplDoublyLinkedList::IT_MODE_FIFO);   // true (FIFO | KEEP = 0)
ver('FIFO', iterator_to_array($pedidos));             // [0 => 1001, 1 => 1002, 2 => 1003]
$pedidos->setIteratorMode(SplDoublyLinkedList::IT_MODE_LIFO);
ver('LIFO', iterator_to_array($pedidos));             // [2 => 1003, 1 => 1002, 0 => 1001]
$pedidos->setIteratorMode(SplDoublyLinkedList::IT_MODE_FIFO | SplDoublyLinkedList::IT_MODE_DELETE);
foreach ($pedidos as $p) {
    echo "  procesado $p", nl();
}
ver('quedan', count($pedidos));                       // 0

// ---------------------------------------------------------------------------------------------
// Recorrido manual en los dos sentidos: rewind, valid, current, key, next, prev
// ENLACE: https://www.php.net/manual/es/spldoublylinkedlist.prev.php
// ENUNCIADO EJEMPLO: un reproductor con botones "siguiente" y "anterior".
// NOTA: la lista tiene un "cursor" interno. next() y prev() lo mueven; current() da el valor y
//       key() la posición. Cuando el cursor se sale de la lista, valid() es false y current() null.
// ---------------------------------------------------------------------------------------------
titulo('Siguiente y anterior');
$playlist = new SplDoublyLinkedList();
foreach (['Pandeirada', 'Alalá', 'Foliada', 'Xota'] as $c) {
    $playlist->push($c);
}
$playlist->rewind();                                   // el cursor al principio
ver('suena', $playlist->current());                    // "Pandeirada"
$playlist->next();
$playlist->next();
ver('tras 2 x siguiente', [$playlist->key(), $playlist->current()]);   // [2, 'Foliada']
$playlist->prev();
ver('tras anterior', [$playlist->key(), $playlist->current()]);        // [1, 'Alalá']
$playlist->next();
$playlist->next();
$playlist->next();
ver('fuera de la lista', [$playlist->valid(), $playlist->current()]);  // [false, NULL]

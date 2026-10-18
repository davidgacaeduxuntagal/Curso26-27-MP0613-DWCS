<?php
/*
 * e01-SplStack.php
 *  PILA (Stack) con SplStack (1): LA API
 *  Métodos: push, pop, top, isEmpty, count, recorrer con foreach (orden LIFO), $pila[i]
 *           (0 = la cima), iterator_to_array, modo IT_MODE_DELETE, errores con la pila vacía,
 *           comparación con un array (array_push / array_pop)
 *
 *  Una PILA es una colección en la que solo se puede acceder al ÚLTIMO elemento que se metió:
 *  el último en entrar es el primero en salir (LIFO: Last In, First Out). Como una pila de
 *  platos: se pone uno encima (push) y se quita el de arriba (pop).
 *
 *  Cómo trabajar este fichero:
 *   1. Pon un punto de ruptura en la primera línea de código y ejecuta en modo depuración.
 *   2. Avanza con F10. ver() muestra la pila de la cima al fondo. En el panel VARIABLES de VS Code,
 *      despliega $pila: los datos están dentro, en un campo interno "dllist".
 *   3. Los comentarios a la derecha indican el resultado esperado.
 */
require_once __DIR__ . '/../../utilidades.php';

// ---------------------------------------------------------------------------------------------
// push, top, pop — Meter, consultar y sacar
// ENLACE: https://www.php.net/manual/es/class.splstack.php
// ENLACE: https://www.php.net/manual/es/spldoublylinkedlist.push.php
// ENLACE: https://www.php.net/manual/es/spldoublylinkedlist.pop.php
// ENLACE: https://www.php.net/manual/es/spldoublylinkedlist.top.php
// ENUNCIADO EJEMPLO: las acciones de un editor de texto se apilan para poder deshacerlas.
// NOTA: push mete en la cima; top MIRA la cima sin sacarla; pop la SACA y la devuelve. Las tres
//       operaciones tardan lo mismo con 10 elementos que con un millón: son O(1).
// ---------------------------------------------------------------------------------------------
titulo('push, top, pop');
$acciones = new SplStack();
$acciones->push('escribir "Hola"');
$acciones->push('poner en negrita');
$acciones->push('cambiar el color');
ver('pila', $acciones);                      // cima -> ['cambiar el color', 'poner en negrita', 'escribir "Hola"'] <- fondo
ver('top (mirar)', $acciones->top());        // "cambiar el color"
ver('count', count($acciones));              // 3 (top no saca nada)
ver('pop (deshacer)', $acciones->pop());     // "cambiar el color"
ver('pop (deshacer)', $acciones->pop());     // "poner en negrita"
ver('pila', $acciones);                      // cima -> ['escribir "Hola"'] <- fondo
ver('isEmpty', $acciones->isEmpty());        // false

// ---------------------------------------------------------------------------------------------
// Pila vacía: pop y top lanzan RuntimeException
// ENLACE: https://www.php.net/manual/es/spldoublylinkedlist.pop.php
// ENUNCIADO EJEMPLO: pulsar "deshacer" cuando ya no queda nada que deshacer.
// NOTA: con un array, array_pop([]) devuelve null en silencio. SplStack lanza una excepción: hay
//       que comprobar antes isEmpty() (o capturarla).
// ---------------------------------------------------------------------------------------------
titulo('Pila vacía');
$vacia = new SplStack();
verError('pop', fn() => $vacia->pop());      // RuntimeException: Can't pop from an empty datastructure
verError('top', fn() => $vacia->top());      // RuntimeException: Can't peek at an empty datastructure
$deshecho = $vacia->isEmpty() ? 'nada que deshacer' : $vacia->pop();
ver('con isEmpty', $deshecho);               // "nada que deshacer"

// ---------------------------------------------------------------------------------------------
// foreach y acceso por posición
// ENLACE: https://www.php.net/manual/es/class.spldoublylinkedlist.php
// ENUNCIADO EJEMPLO: mostrar el historial de acciones sin deshacerlas.
// NOTA: foreach recorre de la CIMA al FONDO y NO saca los elementos (modo KEEP). Las claves son las
//       posiciones contadas desde el fondo (3, 2, 1, 0), pero $pila[0] es la CIMA. ¡Cuidado! Si necesitas un array,
//       usa iterator_to_array($pila, false). (SplStack no tiene método toArray.)
// ---------------------------------------------------------------------------------------------
titulo('foreach y posiciones');
$historial = new SplStack();
foreach (['portada', 'productos', 'ficha del ratón', 'carrito'] as $pagina) {
    $historial->push($pagina);
}
foreach ($historial as $clave => $pagina) {
    echo "  $clave: $pagina", nl();          // 3: carrito / 2: ficha del ratón / 1: productos / 0: portada
}
ver('$historial[0] (cima)', $historial[0]);  // "carrito"
ver('$historial[3] (fondo)', $historial[3]); // "portada"
ver('iterator_to_array', iterator_to_array($historial, false));   // ['carrito', 'ficha del ratón', 'productos', 'portada']
ver('sigue entera', count($historial));      // 4

// ---------------------------------------------------------------------------------------------
// setIteratorMode(IT_MODE_DELETE) — Vaciar la pila al recorrerla
// ENLACE: https://www.php.net/manual/es/spldoublylinkedlist.setiteratormode.php
// ENUNCIADO EJEMPLO: procesar todas las tareas pendientes de la pila, quitándolas.
// NOTA: en una SplStack el sentido (LIFO) no se puede cambiar, pero sí si el recorrido conserva
//       (IT_MODE_KEEP, por defecto) o borra (IT_MODE_DELETE) los elementos.
// ---------------------------------------------------------------------------------------------
titulo('IT_MODE_DELETE');
$tareas = new SplStack();
$tareas->push('copia de seguridad');
$tareas->push('enviar facturas');
$tareas->setIteratorMode(SplDoublyLinkedList::IT_MODE_LIFO | SplDoublyLinkedList::IT_MODE_DELETE);
foreach ($tareas as $tarea) {
    echo "  procesando: $tarea", nl();       // enviar facturas / copia de seguridad
}
ver('quedan', count($tareas));               // 0
verError('cambiar a FIFO', fn() => $tareas->setIteratorMode(SplDoublyLinkedList::IT_MODE_FIFO));
// RuntimeException: Iterators' LIFO/FIFO modes for SplStack/SplQueue objects are frozen

// ---------------------------------------------------------------------------------------------
// Comparación: una pila con un array de PHP
// ENLACE: https://www.php.net/manual/es/function.array-pop.php
// ENUNCIADO EJEMPLO: hacer lo mismo con array_push / array_pop / end.
// NOTA: un array también sirve como pila (array_push + array_pop son O(1)). SplStack aporta un
//       objeto con nombre y operaciones claras (top, isEmpty), se puede pasar como tipo
//       (function f(SplStack $p)) y avisa con una excepción si se saca de una pila vacía.
// ---------------------------------------------------------------------------------------------
titulo('Con un array');
$array = [];
array_push($array, 'a', 'b', 'c');
ver('cima (end)', end($array));              // "c"
ver('array_pop', array_pop($array));         // "c"
$arrayVacio = [];
ver('array_pop de un array vacío', array_pop($arrayVacio));   // NULL, sin avisar: el error pasa desapercibido

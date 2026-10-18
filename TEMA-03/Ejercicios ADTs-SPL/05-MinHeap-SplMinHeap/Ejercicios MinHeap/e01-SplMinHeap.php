<?php
/*
 * e01-SplMinHeap.php
 *  MONTÍCULO MÍNIMO (Min-heap) con SplMinHeap (1): LA API
 *  Métodos: insert, extract, top, count, isEmpty, recorrer con foreach (¡lo vacía!), clone,
 *           qué valores se pueden guardar (números, textos, arrays, fechas), montículo vacío
 *
 *  Un MONTÍCULO MÍNIMO es un árbol binario en el que cada nodo es MENOR O IGUAL que sus hijos: el
 *  mínimo está siempre en la raíz. Permite obtener el menor elemento en O(1) e insertar o extraer
 *  en O(log n). A diferencia de SplPriorityQueue, guarda solo VALORES (sin prioridad aparte): el
 *  propio valor decide el orden.
 *
 *  Cómo trabajar este fichero:
 *   1. Pon un punto de ruptura en la primera línea de código y ejecuta en modo depuración.
 *   2. En el panel VARIABLES verás el array interno "heap": NO está ordenado, solo cumple que
 *      cada padre es menor que sus hijos (posición i -> hijos 2i+1 y 2i+2). ver() lo muestra en
 *      el orden en que saldrían.
 */
require_once __DIR__ . '/../../utilidades.php';

// ---------------------------------------------------------------------------------------------
// insert, top, extract
// ENLACE: https://www.php.net/manual/es/class.splminheap.php
// ENLACE: https://www.php.net/manual/es/splheap.insert.php
// ENLACE: https://www.php.net/manual/es/splheap.extract.php
// ENUNCIADO EJEMPLO: los tiempos de los corredores van llegando desordenados; queremos saber en
//                   todo momento el mejor (el menor).
// ---------------------------------------------------------------------------------------------
titulo('insert, top, extract');
$tiempos = new SplMinHeap();
foreach ([42.5, 39.8, 45.1, 41.0, 39.9] as $t) {
    $tiempos->insert($t);
    echo "  llega $t -> el mejor es ", $tiempos->top(), nl();   // 42.5 / 39.8 / 39.8 / 39.8 / 39.8
}
ver('montículo', $tiempos);                  // en orden de salida: [39.8, 39.9, 41.0, 42.5, 45.1]
ver('extract', $tiempos->extract());         // 39.8
ver('extract', $tiempos->extract());         // 39.9
ver('quedan', count($tiempos));              // 3
verError('montículo vacío', fn() => (new SplMinHeap())->top());   // RuntimeException: Can't peek at an empty heap

// ---------------------------------------------------------------------------------------------
// foreach VACÍA el montículo
// ENLACE: https://www.php.net/manual/es/splheap.current.php
// ENUNCIADO EJEMPLO: mostrar los tiempos ordenados sin perderlos.
// NOTA: igual que en SplPriorityQueue, recorrer un SplHeap extrae los elementos (en orden). Las
//       claves que da foreach van de count-1 a 0. Para conservarlo, recorre una copia (clone).
// ---------------------------------------------------------------------------------------------
titulo('foreach');
foreach (clone $tiempos as $clave => $t) {
    echo "  $clave: $t", nl();               // 2: 41 / 1: 42.5 / 0: 45.1
}
ver('sigue entero', count($tiempos));        // 3
$ordenados = iterator_to_array($tiempos, false);   // esto también lo recorre... y lo vacía
ver('ordenados', $ordenados);                // [41.0, 42.5, 45.1]
ver('ahora está vacío', $tiempos->isEmpty());   // true

// ---------------------------------------------------------------------------------------------
// Qué se puede guardar: textos, arrays y objetos comparables
// ENLACE: https://www.php.net/manual/es/language.operators.comparison.php
// ENUNCIADO EJEMPLO: ordenar nombres, tareas [prioridad, nombre] y fechas de citas.
// NOTA: SplMinHeap compara con <=>. Los textos se comparan alfabéticamente (¡las mayúsculas van
//       antes!), los arrays elemento a elemento y los DateTime por la fecha.
// ---------------------------------------------------------------------------------------------
titulo('Textos, arrays y fechas');
$nombres = new SplMinHeap();
foreach (['brais', 'Ana', 'carmen', 'Diego'] as $n) {
    $nombres->insert($n);
}
ver('textos', iterator_to_array($nombres, false));   // ['Ana', 'Diego', 'brais', 'carmen'] (mayúsculas primero)

$tareas = new SplMinHeap();
$tareas->insert([2, 'revisar correo']);
$tareas->insert([1, 'llamar al cliente']);
$tareas->insert([2, 'actualizar web']);
ver('arrays', iterator_to_array($tareas, false));
// [[1, 'llamar al cliente'], [2, 'actualizar web'], [2, 'revisar correo']]: a igual número, por el texto

$citas = new SplMinHeap();
foreach (['2026-10-20 10:00', '2026-10-18 17:30', '2026-10-18 09:00'] as $f) {
    $citas->insert(new DateTimeImmutable($f));
}
ver('próxima cita', $citas->top()->format('d/m H:i'));   // "18/10 09:00"

// ---------------------------------------------------------------------------------------------
// Insertar todo y extraer todo: ordenar (heapsort)
// ENLACE: https://es.wikipedia.org/wiki/Heapsort
// ENUNCIADO EJEMPLO: ordenar los precios de un catálogo de menor a mayor.
// NOTA: meter n elementos y sacarlos todos cuesta O(n log n), como sort(). En la práctica, para
//       ordenar un array usa sort(); el montículo es útil cuando los datos llegan poco a poco y
//       necesitas el mínimo en cada momento.
// ---------------------------------------------------------------------------------------------
titulo('Ordenar con un montículo');
function ordenarConMonticulo(array $valores): array
{
    $heap = new SplMinHeap();
    foreach ($valores as $v) {
        $heap->insert($v);
    }
    $ordenados = [];
    while (!$heap->isEmpty()) {
        $ordenados[] = $heap->extract();
    }
    return $ordenados;
}
$precios = [19.95, 4.5, 179.0, 49.5, 8.99];
ver('heapsort', ordenarConMonticulo($precios));            // [4.5, 8.99, 19.95, 49.5, 179.0]
$conSort = $precios;
sort($conSort);
ver('igual que sort', ordenarConMonticulo($precios) === $conSort);   // true

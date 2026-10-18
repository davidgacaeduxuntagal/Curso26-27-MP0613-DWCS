<?php
/*
 * e01-SplPriorityQueue.php
 *  COLA DE PRIORIDAD con SplPriorityQueue (1): LA API
 *  Métodos: insert($valor, $prioridad), extract, top, count, isEmpty, setExtractFlags
 *           (EXTR_DATA, EXTR_PRIORITY, EXTR_BOTH), prioridades iguales (¡sin orden garantizado!),
 *           prioridades con arrays (desempate), recorrer con foreach (la vacía), clone, redefinir
 *           compare() para que salga primero la MENOR prioridad
 *
 *  Una COLA DE PRIORIDAD es una cola en la que no sale el primero que llegó, sino el que tiene
 *  MAYOR PRIORIDAD. Por dentro, SplPriorityQueue es un montículo (heap): insertar y extraer
 *  cuestan O(log n).
 *
 *  Cómo trabajar este fichero:
 *   1. Pon un punto de ruptura en la primera línea de código y ejecuta en modo depuración.
 *   2. ver() muestra los elementos en el orden en que saldrían (sobre una copia). En el panel
 *      VARIABLES verás el montículo interno "heap", que NO está ordenado como una lista.
 */
require_once __DIR__ . '/../../utilidades.php';

// ---------------------------------------------------------------------------------------------
// insert y extract
// ENLACE: https://www.php.net/manual/es/class.splpriorityqueue.php
// ENLACE: https://www.php.net/manual/es/splpriorityqueue.insert.php
// ENLACE: https://www.php.net/manual/es/splpriorityqueue.extract.php
// ENUNCIADO EJEMPLO: las incidencias del servicio técnico se atienden según su prioridad
//                   (4 = crítica ... 1 = baja), no por orden de llegada.
// NOTA: la prioridad puede ser cualquier valor comparable (números, strings, arrays...). Sale
//       primero la MAYOR. top() mira la siguiente sin sacarla.
// ---------------------------------------------------------------------------------------------
titulo('insert y extract');
$incidencias = new SplPriorityQueue();
$incidencias->insert('Cambiar el tóner', 1);
$incidencias->insert('El servidor no responde', 4);
$incidencias->insert('Wifi lento en el aula 12', 2);
$incidencias->insert('No funciona el proyector', 3);
ver('cola', $incidencias);                   // ['El servidor no responde' (4), 'No funciona el proyector' (3), ...]
ver('top', $incidencias->top());             // "El servidor no responde"
ver('extract', $incidencias->extract());     // "El servidor no responde"
ver('extract', $incidencias->extract());     // "No funciona el proyector"
ver('quedan', count($incidencias));          // 2

// ---------------------------------------------------------------------------------------------
// setExtractFlags — Qué devuelven extract y top
// ENLACE: https://www.php.net/manual/es/splpriorityqueue.setextractflags.php
// ENUNCIADO EJEMPLO: saber también la prioridad de la incidencia que se atiende.
// NOTA: EXTR_DATA (por defecto) devuelve el valor; EXTR_PRIORITY, la prioridad; EXTR_BOTH, un
//       array ['data' => ..., 'priority' => ...].
// ---------------------------------------------------------------------------------------------
titulo('setExtractFlags');
$incidencias->setExtractFlags(SplPriorityQueue::EXTR_BOTH);
ver('EXTR_BOTH', $incidencias->extract());   // ['data' => 'Wifi lento en el aula 12', 'priority' => 2]
$incidencias->setExtractFlags(SplPriorityQueue::EXTR_PRIORITY);
ver('EXTR_PRIORITY', $incidencias->top());   // 1
$incidencias->setExtractFlags(SplPriorityQueue::EXTR_DATA);

// ---------------------------------------------------------------------------------------------
// ¡Prioridades iguales: el orden NO está garantizado!
// ENLACE: https://www.php.net/manual/es/splpriorityqueue.compare.php
// ENUNCIADO EJEMPLO: cinco pacientes en urgencias; tres tienen la misma gravedad (3). Lo justo
//                   sería atender antes al que llegó antes.
// NOTA: SplPriorityQueue NO respeta el orden de llegada entre iguales (no es "estable"). Truco:
//       usar como prioridad un array [prioridad, -orden de llegada]: los arrays se comparan
//       elemento a elemento, así que a igual gravedad gana el que llegó antes (el número mayor,
//       es decir, el de -orden mayor).
// ---------------------------------------------------------------------------------------------
titulo('Prioridades iguales');
$llegadas = [['Ana', 1], ['Brais', 3], ['Carmen', 3], ['Diego', 2], ['Eva', 3]];
$sinDesempate = new SplPriorityQueue();
foreach ($llegadas as [$paciente, $gravedad]) {
    $sinDesempate->insert($paciente, $gravedad);
}
ver('sin desempate', iterator_to_array(clone $sinDesempate, false));   // ['Brais', 'Eva', 'Carmen', 'Diego', 'Ana'] ¡Eva antes que Carmen!

$conDesempate = new SplPriorityQueue();
foreach ($llegadas as $orden => [$paciente, $gravedad]) {
    $conDesempate->insert($paciente, [$gravedad, -$orden]);           // [3, -1] > [3, -2] > [3, -4]
}
ver('con desempate', iterator_to_array(clone $conDesempate, false));  // ['Brais', 'Carmen', 'Eva', 'Diego', 'Ana']

// ---------------------------------------------------------------------------------------------
// foreach VACÍA la cola de prioridad
// ENLACE: https://www.php.net/manual/es/splpriorityqueue.current.php
// ENUNCIADO EJEMPLO: listar las incidencias pendientes... y descubrir que ya no quedan.
// NOTA: recorrer una SplPriorityQueue (o un SplHeap) con foreach EXTRAE cada elemento. Para
//       consultarla sin perderla, recorre una copia: foreach (clone $cola as ...).
// ---------------------------------------------------------------------------------------------
titulo('foreach la vacía');
$cola = new SplPriorityQueue();
foreach (['a' => 1, 'b' => 3, 'c' => 2] as $valor => $prioridad) {
    $cola->insert($valor, $prioridad);
}
foreach (clone $cola as $valor) {
    echo "  $valor", nl();                   // b / c / a (sobre la copia)
}
ver('después de recorrer la copia', count($cola));   // 3
foreach ($cola as $valor) {
}
ver('después de recorrer la original', count($cola)); // 0

// ---------------------------------------------------------------------------------------------
// Cola vacía y compare() — que salga primero la MENOR prioridad
// ENLACE: https://www.php.net/manual/es/splpriorityqueue.compare.php
// ENUNCIADO EJEMPLO: los pedidos se preparan por hora de recogida: primero el que antes se recoge.
// NOTA: compare($p1, $p2) debe devolver un número > 0 si $p1 tiene MÁS prioridad que $p2. Para
//       invertir el orden, se hereda y se cambia el signo. (Otra opción: insertar -prioridad.)
// ---------------------------------------------------------------------------------------------
titulo('compare(): primero la menor');
verError('extract de una cola vacía', fn() => (new SplPriorityQueue())->extract());   // RuntimeException: Can't extract from an empty heap
final class ColaPorHora extends SplPriorityQueue
{
    public function compare(mixed $prioridad1, mixed $prioridad2): int
    {
        return $prioridad2 <=> $prioridad1;  // al revés: la hora MENOR tiene más prioridad
    }
}
$recogidas = new ColaPorHora();
$recogidas->insert('pedido 1001', '18:30');
$recogidas->insert('pedido 1002', '13:15');
$recogidas->insert('pedido 1003', '20:00');
ver('orden de preparación', iterator_to_array(clone $recogidas, false));   // ['pedido 1002', 'pedido 1001', 'pedido 1003']

<?php
/*
 * e02-SplHeap_problema_solucion.php
 *  (FICHERO CON LAS SOLUCIONES)
 *  Problemas de: aplicaciones de los montículos personalizados (planificación con varios
 *                criterios, top K por una puntuación calculada, montículos con tipo)
 *  (repasa los ejemplos de ../e02-SplHeap.php)
 *
 *  Resuelve cada problema donde se indica y ejecútalo en modo depuración para comprobarlo.
 */
require_once __DIR__ . '/../../../utilidades.php';

// =============================================================================================
// PROBLEMA 1. El ascensor
//  Un ascensor que sube atiende las llamadas por PLANTA de menor a mayor, pero las llamadas de
//  emergencia ('emergencia' => true) van siempre primero. Escribe la clase LlamadasAscensor
//  (hereda de SplHeap) y muestra el orden en que se atienden $llamadas.
// =============================================================================================
titulo('Problema 1');
$llamadas = [
    ['planta' => 5, 'emergencia' => false],
    ['planta' => 2, 'emergencia' => false],
    ['planta' => 7, 'emergencia' => true],
    ['planta' => 3, 'emergencia' => false],
];
// completa aquí tu solución
// ---- SOLUCIÓN ----
final class LlamadasAscensor extends SplHeap
{
    protected function compare(mixed $a, mixed $b): int
    {
        return [$a['emergencia'], $b['planta']] <=> [$b['emergencia'], $a['planta']];   // true > false
    }
}
$ascensor = new LlamadasAscensor();
foreach ($llamadas as $l) {
    $ascensor->insert($l);
}
ver('orden', array_map(fn($l) => $l['planta'] . ($l['emergencia'] ? ' (EMERGENCIA)' : ''), iterator_to_array($ascensor, false)));
// ['7 (EMERGENCIA)', '2', '3', '5']

// =============================================================================================
// PROBLEMA 2. Las 2 canciones más escuchadas de la semana
//  Cada canción tiene reproducciones de cada día. Su puntuación es la suma de la semana, pero
//  las del fin de semana (los 2 últimos valores) cuentan el doble. Con un montículo propio de
//  tamaño 2 (su raíz, el peor), obtén las 2 mejores.
// =============================================================================================
titulo('Problema 2');
$canciones = [
    'Muiñeira de Chantada' => [10, 12, 9, 11, 14, 30, 28],
    'Alalá' => [25, 20, 22, 24, 19, 5, 4],
    'Pandeirada' => [8, 9, 7, 10, 12, 35, 40],
    'Xota' => [15, 14, 16, 13, 15, 12, 10],
];
// completa aquí tu solución
// ---- SOLUCIÓN ----
function puntos(array $dias): int
{
    return array_sum(array_slice($dias, 0, 5)) + 2 * array_sum(array_slice($dias, 5));
}
final class PeoresPrimero extends SplHeap
{
    protected function compare(mixed $a, mixed $b): int
    {
        return $b['puntos'] <=> $a['puntos'];        // sale antes el de MENOS puntos
    }
}
$top = new PeoresPrimero();
foreach ($canciones as $titulo => $dias) {
    $top->insert(['titulo' => $titulo, 'puntos' => puntos($dias)]);
    if (count($top) > 2) {
        $top->extract();
    }
}
ver('top 2', array_reverse(array_map(fn($c) => "{$c['titulo']} ({$c['puntos']})", iterator_to_array($top, false))));
// ['Pandeirada (196)', 'Muiñeira de Chantada (172)']

// =============================================================================================
// PROBLEMA 3. ¿Cómo harías...?
//  ¿Cómo harías un montículo que guarde objetos Tarea (con prioridad y título) y que, al
//  recorrerlo con foreach, NO se vacíe? Pista: una clase propia que implemente IteratorAggregate
//  y cuyo getIterator() recorra una copia del montículo interno.
// =============================================================================================
titulo('Problema 3');
// completa aquí tu solución
// ---- SOLUCIÓN ----
final class AgendaTareas implements IteratorAggregate, Countable
{
    private SplHeap $heap;

    public function __construct()
    {
        $this->heap = new class extends SplHeap {
            protected function compare(mixed $a, mixed $b): int
            {
                return $a['prioridad'] <=> $b['prioridad'];
            }
        };
    }

    public function anadir(string $titulo, int $prioridad): void
    {
        $this->heap->insert(['titulo' => $titulo, 'prioridad' => $prioridad]);
    }

    public function getIterator(): Iterator
    {
        return clone $this->heap;                 // se recorre (y vacía) la copia, no el original
    }

    public function count(): int
    {
        return count($this->heap);
    }
}
$agenda = new AgendaTareas();
$agenda->anadir('Llamar al proveedor', 2);
$agenda->anadir('Pagar facturas', 3);
$agenda->anadir('Ordenar el almacén', 1);
foreach ($agenda as $t) {
    echo "  {$t['titulo']}", nl();               // Pagar facturas / Llamar al proveedor / Ordenar el almacén
}
ver('después del foreach', count($agenda));      // 3

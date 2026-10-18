<?php
/*
 * e02-SplPriorityQueue_problema_solucion.php
 *  (FICHERO CON LAS SOLUCIONES)
 *  Problemas de: aplicaciones de las colas de prioridad (planificación, simulación por eventos,
 *                caminos más cortos)
 *  (repasa los ejemplos de ../e02-SplPriorityQueue.php)
 *
 *  Resuelve cada problema donde se indica y ejecútalo en modo depuración para comprobarlo.
 */
require_once __DIR__ . '/../../../utilidades.php';

// =============================================================================================
// PROBLEMA 1. Impresora con prioridades
//  La impresora del instituto atiende primero los trabajos de 'direccion' (3), después los de
//  'profesorado' (2) y por último los de 'alumnado' (1); dentro de cada grupo, el más corto
//  (menos páginas) primero. Escribe la clase ColaImpresion con enviar(string $fichero, string
//  $grupo, int $paginas) y siguiente(): ?string.
// =============================================================================================
titulo('Problema 1');
// completa aquí tu solución
// ---- SOLUCIÓN ----
final class ColaImpresion
{
    private const GRUPOS = ['direccion' => 3, 'profesorado' => 2, 'alumnado' => 1];
    private SplPriorityQueue $cola;

    public function __construct()
    {
        $this->cola = new SplPriorityQueue();
    }

    public function enviar(string $fichero, string $grupo, int $paginas): void
    {
        $this->cola->insert($fichero, [self::GRUPOS[$grupo] ?? throw new InvalidArgumentException("Grupo $grupo"), -$paginas]);
    }

    public function siguiente(): ?string
    {
        return $this->cola->isEmpty() ? null : $this->cola->extract();
    }
}
$impresora = new ColaImpresion();
$impresora->enviar('apuntes-tema3.pdf', 'alumnado', 30);
$impresora->enviar('acta-claustro.pdf', 'direccion', 4);
$impresora->enviar('examen-DWES.pdf', 'profesorado', 6);
$impresora->enviar('tarea2.pdf', 'alumnado', 2);
$impresora->enviar('circular.pdf', 'direccion', 1);
$orden = [];
while (($f = $impresora->siguiente()) !== null) {
    $orden[] = $f;
}
ver('orden', $orden);   // ['circular.pdf', 'acta-claustro.pdf', 'examen-DWES.pdf', 'tarea2.pdf', 'apuntes-tema3.pdf']

// =============================================================================================
// PROBLEMA 2. Simulación por eventos
//  Los eventos de $eventos (minuto => descripción) se generan desordenados. Mételos en una cola
//  de prioridad para procesarlos en orden de tiempo y, cuando se procese "llega cliente", genera
//  un nuevo evento "sale cliente" 5 minutos después. Muestra la secuencia final.
// =============================================================================================
titulo('Problema 2');
$eventos = [[12, 'llega cliente'], [3, 'abre la tienda'], [7, 'llega cliente'], [30, 'cierra la tienda']];
// completa aquí tu solución
// ---- SOLUCIÓN ----
$agenda = new SplPriorityQueue();
$agenda->setExtractFlags(SplPriorityQueue::EXTR_BOTH);
$n = 0;
foreach ($eventos as [$minuto, $evento]) {
    $agenda->insert($evento, [-$minuto, -$n++]);
}
$secuencia = [];
while (!$agenda->isEmpty()) {
    ['data' => $evento, 'priority' => [$menosMinuto]] = $agenda->extract();
    $minuto = -$menosMinuto;
    $secuencia[] = "min $minuto: $evento";
    if ($evento === 'llega cliente') {
        $agenda->insert('sale cliente', [-($minuto + 5), -$n++]);
    }
}
ver('secuencia', $secuencia);
// min 3: abre / min 7: llega / min 12: llega / min 12: sale / min 17: sale / min 30: cierra

// =============================================================================================
// PROBLEMA 3. El trayecto más rápido en autobús
//  En la red de autobuses $lineas, cada tramo tiene una duración en minutos. Adapta Dijkstra
//  para calcular la duración mínima desde 'Estación' hasta 'Hospital' y la ruta seguida.
// =============================================================================================
titulo('Problema 3');
$lineas = [
    'Estación' => ['Centro' => 8, 'Universidad' => 15],
    'Centro' => ['Estación' => 8, 'Universidad' => 5, 'Puerto' => 10],
    'Universidad' => ['Estación' => 15, 'Centro' => 5, 'Hospital' => 12],
    'Puerto' => ['Centro' => 10, 'Hospital' => 6],
    'Hospital' => ['Universidad' => 12, 'Puerto' => 6],
];
// completa aquí tu solución
// ---- SOLUCIÓN ----
function rutaMasCorta(array $grafo, string $origen, string $destino): array
{
    $dist = [$origen => 0];
    $previo = [];
    $cola = new SplPriorityQueue();
    $cola->setExtractFlags(SplPriorityQueue::EXTR_BOTH);
    $cola->insert($origen, 0);
    while (!$cola->isEmpty()) {
        ['data' => $nodo, 'priority' => $p] = $cola->extract();
        if ($nodo === $destino) {
            break;                                   // ya sabemos la distancia mínima al destino
        }
        if (-$p > $dist[$nodo]) {
            continue;
        }
        foreach ($grafo[$nodo] as $vecino => $min) {
            if ($dist[$nodo] + $min < ($dist[$vecino] ?? PHP_INT_MAX)) {
                $dist[$vecino] = $dist[$nodo] + $min;
                $previo[$vecino] = $nodo;
                $cola->insert($vecino, -$dist[$vecino]);
            }
        }
    }
    $ruta = [$destino];
    while (isset($previo[$ruta[0]])) {
        array_unshift($ruta, $previo[$ruta[0]]);
    }
    return [$dist[$destino] ?? null, $ruta];
}
[$minutos, $ruta] = rutaMasCorta($lineas, 'Estación', 'Hospital');
ver('minutos', $minutos);                // 24
ver('ruta', implode(' -> ', $ruta));     // "Estación -> Centro -> Puerto -> Hospital"

// =============================================================================================
// PROBLEMA 4. ¿Cómo harías...?
//  En el problema 3, ¿qué pasaría si usaras una SplQueue (cola normal) en lugar de una
//  SplPriorityQueue? ¿Seguiría dando el camino de MENOS MINUTOS? Razona en un comentario.
// =============================================================================================
titulo('Problema 4');
// completa aquí tu solución
// ---- SOLUCIÓN ----
// Con una cola normal se haría una búsqueda en anchura: encontraría el camino con MENOS TRAMOS
// (Estación -> Universidad -> Hospital, 2 tramos, 27 minutos), no el de menos minutos (24), porque
// no tiene en cuenta la duración de cada tramo. La cola de prioridad saca siempre el punto
// pendiente más cercano EN MINUTOS, y por eso Dijkstra encuentra la ruta más rápida.
ver('respuesta', 'BFS: menos tramos (27 min); Dijkstra: menos minutos (24 min)');

<?php
/*
 * e02-SplPriorityQueue_problema.php
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

// =============================================================================================
// PROBLEMA 2. Simulación por eventos
//  Los eventos de $eventos (minuto => descripción) se generan desordenados. Mételos en una cola
//  de prioridad para procesarlos en orden de tiempo y, cuando se procese "llega cliente", genera
//  un nuevo evento "sale cliente" 5 minutos después. Muestra la secuencia final.
// =============================================================================================
titulo('Problema 2');
$eventos = [[12, 'llega cliente'], [3, 'abre la tienda'], [7, 'llega cliente'], [30, 'cierra la tienda']];
// completa aquí tu solución

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

// =============================================================================================
// PROBLEMA 4. ¿Cómo harías...?
//  En el problema 3, ¿qué pasaría si usaras una SplQueue (cola normal) en lugar de una
//  SplPriorityQueue? ¿Seguiría dando el camino de MENOS MINUTOS? Razona en un comentario.
// =============================================================================================
titulo('Problema 4');
// completa aquí tu solución

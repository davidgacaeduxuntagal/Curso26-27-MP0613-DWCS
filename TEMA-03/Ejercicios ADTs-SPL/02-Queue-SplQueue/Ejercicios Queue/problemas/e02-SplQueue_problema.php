<?php
/*
 * e02-SplQueue_problema.php
 *  Problemas de: aplicaciones de las colas (trabajos pendientes, simulaciones, búsqueda en
 *                anchura, buffers)
 *  (repasa los ejemplos de ../e02-SplQueue.php)
 *
 *  Resuelve cada problema donde se indica y ejecútalo en modo depuración para comprobarlo.
 */
require_once __DIR__ . '/../../../utilidades.php';

// =============================================================================================
// PROBLEMA 1. Tiempo de espera en la ventanilla
//  Cada cliente de $clientes llega en el minuto 'llega' y necesita 'dura' minutos. Solo hay una
//  ventanilla y se atiende por orden de llegada. Calcula con una SplQueue cuánto espera cada
//  cliente (minutos desde que llega hasta que empiezan a atenderle) y la espera media.
// =============================================================================================
titulo('Problema 1');
$clientes = [
    ['nombre' => 'Ana', 'llega' => 0, 'dura' => 5],
    ['nombre' => 'Brais', 'llega' => 2, 'dura' => 3],
    ['nombre' => 'Carmen', 'llega' => 4, 'dura' => 4],
    ['nombre' => 'Diego', 'llega' => 15, 'dura' => 2],
];
// completa aquí tu solución

// =============================================================================================
// PROBLEMA 2. Grados de separación
//  $amigos es una red social (quién es amigo de quién). Con una búsqueda en anchura, escribe
//  separacion(array $red, string $de, string $a): ?int que diga a cuántos "saltos" de amistad
//  está una persona de otra (null si no están conectadas).
// =============================================================================================
titulo('Problema 2');
$amigos = [
    'Ana' => ['Brais', 'Carmen'],
    'Brais' => ['Ana', 'Diego'],
    'Carmen' => ['Ana', 'Diego', 'Eva'],
    'Diego' => ['Brais', 'Carmen', 'Fran'],
    'Eva' => ['Carmen'],
    'Fran' => ['Diego'],
    'Xiana' => [],
];
// completa aquí tu solución

// =============================================================================================
// PROBLEMA 3. Media móvil de las ventas
//  Escribe la clase MediaMovil que guarde en una SplQueue solo los últimos N valores y tenga
//  anadir(float $valor): float, que devuelve la media de los últimos N. Calcula la media móvil
//  de 3 días de las ventas de la semana.
// =============================================================================================
titulo('Problema 3');
$ventas = [120, 80, 100, 160, 140, 90, 200];
// completa aquí tu solución

// =============================================================================================
// PROBLEMA 4. ¿Cómo harías...?
//  En el ejemplo de la cola de correos, ¿cómo harías para que un correo que falla no se
//  reintente inmediatamente, sino como mínimo 2 "vueltas" después? Pista: guarda en el trabajo
//  la vuelta a partir de la cual se puede reintentar y, si aún no ha llegado, devuélvelo al final.
// =============================================================================================
titulo('Problema 4');
// completa aquí tu solución

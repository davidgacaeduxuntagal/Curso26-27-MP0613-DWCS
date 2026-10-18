<?php
/*
 * e02-SplHeap_problema.php
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

// =============================================================================================
// PROBLEMA 3. ¿Cómo harías...?
//  ¿Cómo harías un montículo que guarde objetos Tarea (con prioridad y título) y que, al
//  recorrerlo con foreach, NO se vacíe? Pista: una clase propia que implemente IteratorAggregate
//  y cuyo getIterator() recorra una copia del montículo interno.
// =============================================================================================
titulo('Problema 3');
// completa aquí tu solución

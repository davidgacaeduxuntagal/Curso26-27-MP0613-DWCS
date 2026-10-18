<?php
/*
 * e02-SplMaxHeap_problema.php
 *  Problemas de: aplicaciones de los montículos máximos (K menores, mediana, planificación,
 *                combinar SplMaxHeap y SplMinHeap)
 *  (repasa los ejemplos de ../e02-SplMaxHeap.php)
 *
 *  Resuelve cada problema donde se indica y ejecútalo en modo depuración para comprobarlo.
 */
require_once __DIR__ . '/../../../utilidades.php';

// =============================================================================================
// PROBLEMA 1. Los hoteles más cercanos
//  De $hoteles (nombre => km al centro), obtén los 2 más cercanos recorriendo la lista una sola
//  vez con un SplMaxHeap que nunca tenga más de 2 elementos.
// =============================================================================================
titulo('Problema 1');
$hoteles = ['Atlántico' => 1.2, 'Riazor' => 2.8, 'Plaza' => 0.4, 'Faro' => 5.5, 'Puerto' => 0.9];
// completa aquí tu solución

// =============================================================================================
// PROBLEMA 2. Mediana de los tiempos de respuesta
//  Usando la idea de la clase Mediana del ejemplo (un SplMaxHeap y un SplMinHeap), escribe
//  medianaDe(array $valores): float y calcula la mediana de los tiempos de respuesta (ms) de un
//  servidor. Compruébala ordenando el array.
// =============================================================================================
titulo('Problema 2');
$tiempos = [120, 95, 3000, 110, 101, 99, 130, 105];
// completa aquí tu solución

// =============================================================================================
// PROBLEMA 3. Atender primero el pedido más grande
//  Un almacén con 3 empleados prepara los pedidos de $pedidos (número => artículos). Para acabar
//  cuanto antes, cada empleado libre coge el pedido con MÁS artículos pendiente. Simúlalo con un
//  SplMaxHeap (pedidos) y un SplMinHeap (empleados: [minutos ocupado, nombre]); cada artículo
//  tarda 1 minuto. ¿Cuándo termina el último?
// =============================================================================================
titulo('Problema 3');
$pedidos = [101 => 8, 102 => 3, 103 => 12, 104 => 5, 105 => 7, 106 => 2];
// completa aquí tu solución

// =============================================================================================
// PROBLEMA 4. ¿Cómo harías...?
//  ¿Cómo implementarías la clase Mediana del ejemplo usando SOLO SplMinHeap (sin SplMaxHeap)?
//  Pista: un montículo mínimo de valores con el signo cambiado se comporta como uno máximo.
// =============================================================================================
titulo('Problema 4');
// completa aquí tu solución

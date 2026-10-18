<?php
/*
 * e02-SplStack_problema.php
 *  Problemas de: aplicaciones de las pilas (deshacer, símbolos equilibrados, expresiones,
 *                recorridos en profundidad)
 *  (repasa los ejemplos de ../e02-SplStack.php)
 *
 *  Resuelve cada problema donde se indica y ejecútalo en modo depuración para comprobarlo.
 */
require_once __DIR__ . '/../../../utilidades.php';

// =============================================================================================
// PROBLEMA 1. Paréntesis, corchetes y llaves
//  Escribe equilibrado(string $codigo): bool que compruebe con una SplStack que (), [] y {}
//  están bien emparejados en un trozo de código.
// =============================================================================================
titulo('Problema 1');
$pruebas = ['if ($a[0] > 1) { echo "ok"; }', 'f(x[1)]', '{ [ ( ) ] ', 'array_map(fn($x) => [$x], $y);'];
// completa aquí tu solución

// =============================================================================================
// PROBLEMA 2. El botón "Atrás" del navegador
//  Escribe la clase Navegador con visitar(string $url), atras(): ?string y actual(): ?string.
//  Usa una SplStack para las páginas anteriores. Si no hay página anterior, atras() devuelve
//  null y no cambia la actual. Simula la navegación de abajo.
// =============================================================================================
titulo('Problema 2');
// completa aquí tu solución

// =============================================================================================
// PROBLEMA 3. Calculadora con variables
//  Amplía la idea de evaluarRpn() del ejemplo: escribe evaluar(string $rpn, array $variables)
//  que admita también nombres de variables (por ejemplo "precio unidades * 1.21 *").
// =============================================================================================
titulo('Problema 3');
// completa aquí tu solución

// =============================================================================================
// PROBLEMA 4. ¿Cómo harías...?
//  Un laberinto es una cuadrícula ('#' = muro, '.' = pasillo, 'S' = salida). ¿Cómo comprobarías,
//  con una pila (búsqueda en profundidad), si desde la casilla (1, 1) se llega a la salida?
//  Escribe hayCamino(array $mapa, int $fila, int $columna): bool.
// =============================================================================================
titulo('Problema 4');
$laberinto = [
    '#######',
    '#..#..#',
    '#.##.##',
    '#....S#',
    '#######',
];
$cerrado = ['#####', '#..##', '###S#', '#####'];
// completa aquí tu solución

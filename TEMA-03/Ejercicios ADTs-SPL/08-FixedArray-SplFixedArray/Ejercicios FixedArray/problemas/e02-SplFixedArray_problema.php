<?php
/*
 * e02-SplFixedArray_problema.php
 *  Problemas de: aplicaciones de SplFixedArray (matrices, tableros, contadores, buffers
 *                circulares)
 *  (repasa los ejemplos de ../e02-SplFixedArray.php)
 *
 *  Resuelve cada problema donde se indica y ejecútalo en modo depuración para comprobarlo.
 */
require_once __DIR__ . '/../../../utilidades.php';

// =============================================================================================
// PROBLEMA 1. Hundir la flota
//  Crea un tablero 5x5 (SplFixedArray de SplFixedArray) lleno de '~' (agua). Coloca un barco de
//  3 casillas en horizontal en la fila 2 (columnas 1 a 3) con 'B' y escribe disparar(int $f,
//  int $c): string que devuelva "agua", "tocado" o "repetido" y marque la casilla con 'o' (agua
//  disparada) o 'X' (tocado).
// =============================================================================================
titulo('Problema 1');
// completa aquí tu solución

// =============================================================================================
// PROBLEMA 2. ¿Cuántas veces sale cada número?
//  Lanza un dado 600 veces (mt_rand con la semilla 7) y cuenta cuántas veces sale cada cara en
//  un SplFixedArray de 6 casillas (cara 1 = casilla 0). Comprueba que la suma es 600.
// =============================================================================================
titulo('Problema 2');
mt_srand(7);
// completa aquí tu solución

// =============================================================================================
// PROBLEMA 3. La media móvil de las temperaturas
//  Usa la idea del BufferCircular del ejemplo: escribe la función mediasMoviles(array $valores,
//  int $n): array que devuelva, para cada valor, la media de los últimos $n (o de los que haya
//  si aún no hay $n), usando un SplFixedArray de tamaño $n y una posición que da la vuelta.
// =============================================================================================
titulo('Problema 3');
$temperaturas = [12, 14, 16, 18, 15, 11];
// completa aquí tu solución

// =============================================================================================
// PROBLEMA 4. ¿Cómo harías...?
//  ¿SplFixedArray o un array normal? Para cada caso, contesta en un comentario.
//   a) Las 64 casillas de un tablero de ajedrez.
//   b) Los productos del carrito, que se añaden y se quitan.
//   c) Un millón de lecturas de un sensor, que se cargan una vez y se analizan.
//   d) Los datos de un cliente (nombre, email, teléfono).
// =============================================================================================
titulo('Problema 4');
// completa aquí tu solución

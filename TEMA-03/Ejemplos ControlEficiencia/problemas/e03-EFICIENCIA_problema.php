<?php
/*
 * e03-EFICIENCIA_problema.php
 *  Problemas de: microbenchmarks con la clase Benchmark (medir, comparar, tabla), mediana y media, Xdebug
 *  (repasa los ejemplos de ../e03-EFICIENCIA.php)
 *
 *  Resuelve cada problema donde se indica. Este fichero se debe ejecutar en la terminal y SIN depurador:
 *      php -d xdebug.mode=off e03-EFICIENCIA_problema.php
 */
require_once __DIR__ . '/../utilidades.php';
require_once __DIR__ . '/../Benchmark.php';

// =============================================================================================
// PROBLEMA 1. La media engaña
//  Estas son 7 muestras (en µs) de un microbenchmark; en una, Windows se puso a hacer otra cosa. Escribe
//  media(array $muestras): float y mediana(array $muestras): float (si hay un número par de muestras, la
//  mediana es la media de las dos del centro) y compara las dos con y sin la muestra anormal.
// =============================================================================================
titulo('Problema 1');
$muestras = [10.1, 10.3, 9.9, 10.0, 48.7, 10.2, 10.1];
// completa aquí tu solución

// =============================================================================================
// PROBLEMA 2. isset o array_key_exists: primero, que hagan lo mismo
//  En el carrito, $descuentos[código] guarda el descuento de cada producto; null significa "producto sin
//  descuento pero que existe". Comprueba qué devuelven isset y array_key_exists para 'TEC01' y explica la
//  diferencia. Después compara su velocidad con Benchmark::comparar y muestra la tabla.
// =============================================================================================
titulo('Problema 2');
$descuentos = ['RAT01' => 0.10, 'TEC01' => null, 'PAN01' => 0.25];
// completa aquí tu solución

// =============================================================================================
// PROBLEMA 3. Dar la vuelta a un texto
//  Escribe tres funciones que devuelvan un texto al revés: con strrev, con str_split + array_reverse + implode,
//  y con un bucle for que vaya del último carácter al primero. Comprueba con "Bar Baio" que las tres coinciden y
//  compáralas con un texto de 1000 caracteres. (Para textos con tildes harían falta las funciones mb_*.)
// =============================================================================================
titulo('Problema 3');
$texto = str_repeat('abcdefghij', 100);           // 1000 caracteres
// completa aquí tu solución

// =============================================================================================
// PROBLEMA 4. ¿Es más rápida?
//  Escribe esMasRapida(callable $a, callable $b, int $porLote = 1000): bool que devuelva true si la mediana de
//  $a es menor que la de $b (usa Benchmark::medir). Pruébala comparando in_array con isset en una lista de
//  5000 productos, en los dos órdenes.
// =============================================================================================
titulo('Problema 4');
$codigos = array_map(fn($i) => sprintf('PRD%05d', $i), range(1, 5000));
$indice = array_flip($codigos);
$buscado = 'PRD04999';
// completa aquí tu solución

// =============================================================================================
// PROBLEMA 5. Avisar antes de medir
//  Escribe avisoXdebug(): ?string que devuelva el texto "Xdebug activo (debug, develop): los tiempos no son
//  fiables" (con los modos que estén activos) o null si Xdebug no está cargado o no tiene ningún modo. Recuerda:
//  con xdebug.mode=off, ini_get('xdebug.mode') devuelve '' y lo fiable es xdebug_info('mode').
// =============================================================================================
titulo('Problema 5');
// completa aquí tu solución

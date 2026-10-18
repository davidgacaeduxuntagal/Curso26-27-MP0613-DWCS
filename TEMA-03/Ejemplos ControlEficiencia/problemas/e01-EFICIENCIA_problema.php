<?php
/*
 * e01-EFICIENCIA_problema.php
 *  Problemas de: los relojes de PHP (time, microtime, hrtime, unidades, resolución, REQUEST_TIME_FLOAT)
 *  (repasa los ejemplos de ../e01-EFICIENCIA.php)
 *
 *  Resuelve cada problema donde se indica y ejecútalo en modo depuración para comprobarlo.
 *  Para mirar los tiempos de verdad, en la terminal: php -d xdebug.mode=off e01-EFICIENCIA_problema.php
 */
require_once __DIR__ . '/../utilidades.php';

// =============================================================================================
// PROBLEMA 1. Cada tiempo, en su unidad
//  Escribe nsATexto(int $ns): string que muestre una duración en nanosegundos en la unidad más cómoda,
//  con coma decimal: menos de 1000 ns -> "300 ns"; menos de 1 ms -> "530,8 µs" (1 decimal); menos de 1 s ->
//  "20,07 ms" (2 decimales); si no -> "1,25 s" (2 decimales).
// =============================================================================================
titulo('Problema 1');
$duraciones = [300, 530_800, 20_070_000, 1_250_000_000];
// completa aquí tu solución

// =============================================================================================
// PROBLEMA 2. Arreglar el código antiguo con microtime()
//  Un script antiguo guardó en un fichero el resultado de microtime() SIN true, que es un texto como
//  "0.20840500 1792315625" (microsegundos y segundos). Escribe microtimeAFloat(string $m): float que lo convierta
//  en los segundos con decimales (1792315625.208405) y úsala para calcular cuántos milisegundos pasaron entre
//  las dos marcas guardadas.
// =============================================================================================
titulo('Problema 2');
$marcaInicio = '0.20840500 1792315625';
$marcaFin = '0.05312200 1792315626';
// completa aquí tu solución

// =============================================================================================
// PROBLEMA 3. hrtime() sin true
//  hrtime() devuelve [segundos, nanosegundos]. Escribe duracionNs(array $inicio, array $fin): int que calcule
//  los nanosegundos entre dos lecturas. ¡Ojo con el caso en que los nanosegundos del final son MENORES que los
//  del inicio (porque ha cambiado el segundo)! Pruébala con los datos y con dos lecturas reales.
// =============================================================================================
titulo('Problema 3');
$a = [7323, 900_000_000];
$b = [7324, 100_000_000];
// completa aquí tu solución

// =============================================================================================
// PROBLEMA 4. ¿Cuánto distingue cada reloj?
//  Escribe menorPaso(callable $reloj, int $intentos): float que lea el reloj dos veces seguidas $intentos
//  veces y devuelva la menor diferencia que no sea 0 (en las unidades del reloj). Úsala para hrtime(true) (en ns)
//  y para microtime(true) (pasa el resultado a ns). ¿Cuál distingue intervalos más pequeños?
// =============================================================================================
titulo('Problema 4');
// completa aquí tu solución

// =============================================================================================
// PROBLEMA 5. ¿Cuánto lleva el script?
//  Escribe msDesdeInicio(): float que devuelva los milisegundos que han pasado desde que empezó el script
//  (usa $_SERVER['REQUEST_TIME_FLOAT']). Muestra el valor antes y después de esperar 20 ms y comprueba que la
//  diferencia es de al menos 20 ms.
// =============================================================================================
titulo('Problema 5');
// completa aquí tu solución

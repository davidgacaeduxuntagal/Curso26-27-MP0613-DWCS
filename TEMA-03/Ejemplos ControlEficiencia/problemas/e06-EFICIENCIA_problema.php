<?php
/*
 * e06-EFICIENCIA_problema.php
 *  Problemas de: complejidad de los algoritmos medida con tamaños que se duplican (O(n²) frente a O(n))
 *  (repasa el ejercicio resuelto de ../e06-EFICIENCIA.php)
 *
 *  Resuelve cada problema donde se indica y ejecútalo en modo depuración para comprobarlo.
 *  Para mirar los tiempos de verdad, en la terminal: php -d xdebug.mode=off e06-EFICIENCIA_problema.php
 */
require_once __DIR__ . '/../utilidades.php';
require_once __DIR__ . '/../Cronometro.php';

// =============================================================================================
// PROBLEMA 1. ¿Qué complejidad tiene?
//  Escribe complejidad(array $tiempos): string que reciba los tiempos medidos con tamaños que se duplican
//  (n, 2n, 4n...), calcule el factor medio entre cada tiempo y el anterior y devuelva "O(1)" (factor < 1,5),
//  "O(n)" (< 3), "O(n²)" (< 6) u "O(n³)" (el resto), junto con el factor: "O(n²) (x4,0)".
// =============================================================================================
titulo('Problema 1');
$medidos = [
    'bucles anidados' => [9.78, 38.44, 155.65, 634.62],
    'isset' => [0.056, 0.099, 0.219, 0.445],
    'count()' => [0.0001, 0.0001, 0.0001, 0.0001],
    'tres bucles' => [2.0, 16.5, 129.0, 1040.0],
];
// completa aquí tu solución

// =============================================================================================
// PROBLEMA 2. ¿Cuánto tardaría con más datos?
//  Escribe estimarMs(float $msActual, int $nActual, int $nNuevo, int $exponente): float que estime el tiempo con
//  $nNuevo datos sabiendo que el algoritmo es O(n^exponente). ¿Cuánto tardarían los bucles anidados del ejercicio
//  resuelto (634,62 ms con 8000 correos) con 100 000? ¿Y la versión con isset (0,445 ms, O(n))?
// =============================================================================================
titulo('Problema 2');
// completa aquí tu solución

// =============================================================================================
// PROBLEMA 3. Dos productos que suman el presupuesto
//  Un cliente tiene una tarjeta regalo de exactamente 50 € y quiere dos productos distintos que la gasten entera.
//  Escribe parQueSumaLento(array $precios, int $objetivo): ?array, con dos bucles anidados, y parQueSumaRapido(),
//  con un solo bucle y un array de "precios ya vistos" (si ya has visto objetivo - precio, ¡ya está!). Las dos
//  devuelven los dos precios o null. Trabaja con céntimos (enteros) para evitar problemas con los decimales.
// =============================================================================================
titulo('Problema 3');
$preciosCentimos = [1999, 1250, 899, 3001, 4500, 2750, 600];      // 19,99 €, 12,50 €...
// completa aquí tu solución

// =============================================================================================
// PROBLEMA 4. Medir las dos versiones del problema 3
//  Con las funciones del problema 3, mide las dos con listas de 1000, 2000 y 4000 precios distintos y un objetivo
//  imposible (el peor caso: hay que mirarlo todo). Usa complejidad() del problema 1 para clasificarlas.
// =============================================================================================
titulo('Problema 4');
// completa aquí tu solución

// =============================================================================================
// PROBLEMA 5. Alumnos matriculados en dos cursos
//  Hay dos listas de correos (curso de PHP y curso de bases de datos). Escribe enLosDosLento(array $a, array $b)
//  con un foreach e in_array, y enLosDosRapido(array $a, array $b) con array_flip e isset (o con
//  array_intersect_key). Comprueba que devuelven lo mismo y compara su tiempo con 5000 alumnos en cada curso.
// =============================================================================================
titulo('Problema 5');
$cursoPhp = array_map(fn($i) => "alumno$i@iesrodeira.gal", range(1, 5000));
$cursoBd = array_map(fn($i) => "alumno$i@iesrodeira.gal", range(4001, 9000));    // coinciden del 4001 al 5000
// completa aquí tu solución

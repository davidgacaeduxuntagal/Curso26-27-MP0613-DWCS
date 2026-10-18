<?php
/*
 * e06-EFICIENCIA_problema_solucion.php
 *  (FICHERO CON LAS SOLUCIONES)
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
// ---- SOLUCIÓN ----
function complejidad(array $tiempos): string
{
    $factores = [];
    for ($i = 1; $i < count($tiempos); $i++) {
        $factores[] = $tiempos[$i] / $tiempos[$i - 1];
    }
    $factor = array_sum($factores) / count($factores);
    $clase = match (true) {
        $factor < 1.5 => 'O(1)',
        $factor < 3 => 'O(n)',
        $factor < 6 => 'O(n²)',
        default => 'O(n³)',
    };
    return $clase . ' (x' . number_format($factor, 1, ',', '') . ')';
}

ver('complejidades', array_map('complejidad', $medidos));
// ['bucles anidados' => 'O(n²) (x4,0)', 'isset' => 'O(n) (x2,0)', 'count()' => 'O(1) (x1,0)', 'tres bucles' => 'O(n³) (x8,0)']

// =============================================================================================
// PROBLEMA 2. ¿Cuánto tardaría con más datos?
//  Escribe estimarMs(float $msActual, int $nActual, int $nNuevo, int $exponente): float que estime el tiempo con
//  $nNuevo datos sabiendo que el algoritmo es O(n^exponente). ¿Cuánto tardarían los bucles anidados del ejercicio
//  resuelto (634,62 ms con 8000 correos) con 100 000? ¿Y la versión con isset (0,445 ms, O(n))?
// =============================================================================================
titulo('Problema 2');
// completa aquí tu solución
// ---- SOLUCIÓN ----
function estimarMs(float $msActual, int $nActual, int $nNuevo, int $exponente): float
{
    return $msActual * ($nNuevo / $nActual) ** $exponente;
}

ver('bucles anidados con 100 000 (s)', round(estimarMs(634.62, 8000, 100_000, 2) / 1000, 1));   // 99.2 (¡más de minuto y medio!)
ver('isset con 100 000 (ms)', round(estimarMs(0.445, 8000, 100_000, 1), 2));                   // 5.56

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
// ---- SOLUCIÓN ----
function parQueSumaLento(array $precios, int $objetivo): ?array
{
    $n = count($precios);
    for ($i = 0; $i < $n; $i++) {
        for ($j = $i + 1; $j < $n; $j++) {
            if ($precios[$i] + $precios[$j] === $objetivo) {
                return [$precios[$i], $precios[$j]];
            }
        }
    }
    return null;
}

function parQueSumaRapido(array $precios, int $objetivo): ?array
{
    $vistos = [];
    foreach ($precios as $precio) {
        if (isset($vistos[$objetivo - $precio])) {
            return [$objetivo - $precio, $precio];
        }
        $vistos[$precio] = true;
    }
    return null;
}

ver('lento, 50 €', parQueSumaLento($preciosCentimos, 5000));     // [1999, 3001]
ver('rápido, 50 €', parQueSumaRapido($preciosCentimos, 5000));   // [1999, 3001]
ver('lento, 1 €', parQueSumaLento($preciosCentimos, 100));       // null
ver('rápido, 1 €', parQueSumaRapido($preciosCentimos, 100));     // null

// =============================================================================================
// PROBLEMA 4. Medir las dos versiones del problema 3
//  Con las funciones del problema 3, mide las dos con listas de 1000, 2000 y 4000 precios distintos y un objetivo
//  imposible (el peor caso: hay que mirarlo todo). Usa complejidad() del problema 1 para clasificarlas.
// =============================================================================================
titulo('Problema 4');
// completa aquí tu solución
// ---- SOLUCIÓN ----
$tiempos = ['parQueSumaLento' => [], 'parQueSumaRapido' => []];
foreach ([1000, 2000, 4000] as $n) {
    $precios = range(1, $n);                      // del 1 al n: ninguna pareja suma -1
    foreach (array_keys($tiempos) as $funcion) {
        [, $ms] = Cronometro::medir($funcion, $precios, -1);
        $tiempos[$funcion][] = $ms;
    }
}
ver('lento', complejidad($tiempos['parQueSumaLento']));     // "O(n²) (x3,5)" (aprox.: entre x3,5 y x4,5)
ver('rápido', complejidad($tiempos['parQueSumaRapido']));   // "O(n) (x2,1)" (aprox.)

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
// ---- SOLUCIÓN ----
function enLosDosLento(array $a, array $b): array
{
    $comunes = [];
    foreach ($a as $correo) {
        if (in_array($correo, $b, true)) {
            $comunes[] = $correo;
        }
    }
    return $comunes;
}

function enLosDosRapido(array $a, array $b): array
{
    $indiceB = array_flip($b);
    $comunes = [];
    foreach ($a as $correo) {
        if (isset($indiceB[$correo])) {
            $comunes[] = $correo;
        }
    }
    return $comunes;
}

[$lento, $msLento] = Cronometro::medir('enLosDosLento', $cursoPhp, $cursoBd);
[$rapido, $msRapido] = Cronometro::medir('enLosDosRapido', $cursoPhp, $cursoBd);
ver('mismo resultado', $lento === $rapido);                   // true
ver('en los dos cursos', count($rapido));                     // 1000
ver('lento', Cronometro::formatear($msLento));                // "68,19 ms" (aprox.)
ver('rápido', Cronometro::formatear($msRapido));              // "259 µs" (aprox.): unas 250 veces menos

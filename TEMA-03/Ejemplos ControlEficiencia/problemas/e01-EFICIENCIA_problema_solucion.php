<?php
/*
 * e01-EFICIENCIA_problema_solucion.php
 *  (FICHERO CON LAS SOLUCIONES)
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
// ---- SOLUCIÓN ----
function nsATexto(int $ns): string
{
    return match (true) {
        $ns < 1_000 => "$ns ns",
        $ns < 1_000_000 => number_format($ns / 1e3, 1, ',', '.') . ' µs',
        $ns < 1_000_000_000 => number_format($ns / 1e6, 2, ',', '.') . ' ms',
        default => number_format($ns / 1e9, 2, ',', '.') . ' s',
    };
}

ver('en texto', array_map('nsATexto', $duraciones));   // ['300 ns', '530,8 µs', '20,07 ms', '1,25 s']

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
// ---- SOLUCIÓN ----
function microtimeAFloat(string $m): float
{
    [$fraccion, $segundos] = explode(' ', $m);
    return (int) $segundos + (float) $fraccion;
}

ver('inicio', microtimeAFloat($marcaInicio));                         // 1792315625.208405
$ms = (microtimeAFloat($marcaFin) - microtimeAFloat($marcaInicio)) * 1000;
ver('ms entre las dos marcas', round($ms, 3));                         // 844.717

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
// ---- SOLUCIÓN ----
function duracionNs(array $inicio, array $fin): int
{
    // basta con pasar las dos lecturas a nanosegundos y restar: así no hay que "llevarse" nada
    return ($fin[0] - $inicio[0]) * 1_000_000_000 + ($fin[1] - $inicio[1]);
}

ver('de [7323, 0.9 s] a [7324, 0.1 s]', duracionNs($a, $b));           // 200000000 (0,2 s)
$lectura1 = hrtime();
usleep(5_000);
$lectura2 = hrtime();
ver('5 ms de espera, en ms', round(duracionNs($lectura1, $lectura2) / 1e6) >= 5);   // true

// =============================================================================================
// PROBLEMA 4. ¿Cuánto distingue cada reloj?
//  Escribe menorPaso(callable $reloj, int $intentos): float que lea el reloj dos veces seguidas $intentos
//  veces y devuelva la menor diferencia que no sea 0 (en las unidades del reloj). Úsala para hrtime(true) (en ns)
//  y para microtime(true) (pasa el resultado a ns). ¿Cuál distingue intervalos más pequeños?
// =============================================================================================
titulo('Problema 4');
// completa aquí tu solución
// ---- SOLUCIÓN ----
function menorPaso(callable $reloj, int $intentos): float
{
    $menor = INF;
    for ($i = 0; $i < $intentos; $i++) {
        $a = $reloj();
        $b = $reloj();
        if ($b > $a) {
            $menor = min($menor, $b - $a);
        }
    }
    return $menor;
}

$pasoHr = menorPaso(fn() => hrtime(true), 1000);
$pasoMicro = menorPaso(fn() => microtime(true), 100_000) * 1e9;    // segundos -> ns
ver('hrtime (ns)', $pasoHr);                                        // 100.0 (en Windows; es float porque empieza en INF)
ver('microtime (ns)', round($pasoMicro));                           // 954.0 (en Windows: casi 1 µs)
ver('hrtime distingue menos tiempo', $pasoHr < $pasoMicro);         // true

// =============================================================================================
// PROBLEMA 5. ¿Cuánto lleva el script?
//  Escribe msDesdeInicio(): float que devuelva los milisegundos que han pasado desde que empezó el script
//  (usa $_SERVER['REQUEST_TIME_FLOAT']). Muestra el valor antes y después de esperar 20 ms y comprueba que la
//  diferencia es de al menos 20 ms.
// =============================================================================================
titulo('Problema 5');
// completa aquí tu solución
// ---- SOLUCIÓN ----
function msDesdeInicio(): float
{
    return (microtime(true) - $_SERVER['REQUEST_TIME_FLOAT']) * 1000;
}

$antes = msDesdeInicio();
usleep(20_000);
$despues = msDesdeInicio();
ver('antes (ms)', round($antes, 1));                     // 37.3 (aprox.: casi todo, el problema 4)
ver('después - antes >= 20', $despues - $antes >= 20);   // true

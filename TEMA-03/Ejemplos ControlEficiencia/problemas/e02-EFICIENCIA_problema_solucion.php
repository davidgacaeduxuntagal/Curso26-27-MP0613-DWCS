<?php
/*
 * e02-EFICIENCIA_problema_solucion.php
 *  (FICHERO CON LAS SOLUCIONES)
 *  Problemas de: cronometrar funciones con la clase Cronometro (medir, vueltas, informe) y ver cómo crece el tiempo
 *  (repasa los ejemplos de ../e02-EFICIENCIA.php)
 *
 *  Resuelve cada problema donde se indica y ejecútalo en modo depuración para comprobarlo.
 *  Para mirar los tiempos de verdad, en la terminal: php -d xdebug.mode=off e02-EFICIENCIA_problema.php
 */
require_once __DIR__ . '/../utilidades.php';
require_once __DIR__ . '/../Cronometro.php';

// =============================================================================================
// PROBLEMA 1. Tres formas de sumar de 1 a n
//  Escribe sumaBucle(int $n), sumaRange(int $n) (array_sum + range) y sumaFormula(int $n) (n·(n+1)/2). Con
//  Cronometro::medir, mide las tres con n = 1 000 000, comprueba que dan lo mismo y muestra cuánto tarda cada una.
// =============================================================================================
titulo('Problema 1');
$n = 1_000_000;
// completa aquí tu solución
// ---- SOLUCIÓN ----
function sumaBucle(int $n): int
{
    $suma = 0;
    for ($i = 1; $i <= $n; $i++) {
        $suma += $i;
    }
    return $suma;
}

function sumaRange(int $n): int
{
    return array_sum(range(1, $n));
}

function sumaFormula(int $n): int
{
    return intdiv($n * ($n + 1), 2);
}

$tiempos = [];
$resultados = [];
foreach (['sumaBucle', 'sumaRange', 'sumaFormula'] as $funcion) {
    [$resultados[$funcion], $ms] = Cronometro::medir($funcion, $n);
    $tiempos[$funcion] = Cronometro::formatear($ms);
}
ver('las tres dan lo mismo', count(array_unique($resultados)) === 1);   // true (500000500000)
ver('tiempos', $tiempos);   // ['sumaBucle' => '17,27 ms', 'sumaRange' => '5,66 ms', 'sumaFormula' => '5 µs'] (aprox.)

// =============================================================================================
// PROBLEMA 2. La media de varias ejecuciones
//  Una sola medida varía mucho. Escribe msMedio(callable $funcion, int $veces, mixed ...$argumentos): float que
//  ejecute la función $veces veces (con Cronometro::medir) y devuelva la media en milisegundos. Pruébala
//  midiendo 20 veces str_repeat('a', 1_000_000).
// =============================================================================================
titulo('Problema 2');
// completa aquí tu solución
// ---- SOLUCIÓN ----
function msMedio(callable $funcion, int $veces, mixed ...$argumentos): float
{
    $total = 0.0;
    for ($i = 0; $i < $veces; $i++) {
        [, $ms] = Cronometro::medir($funcion, ...$argumentos);
        $total += $ms;
    }
    return $total / $veces;
}

$media = msMedio('str_repeat', 20, 'a', 1_000_000);
ver('str_repeat de 1 MB, media (ms)', round($media, 3));   // 0.021 (aprox.: crear 1 MB es muy rápido)

// =============================================================================================
// PROBLEMA 3. Avisar si una función tarda demasiado
//  Escribe conLimite(callable $funcion, float $limiteMs, mixed ...$argumentos): mixed que ejecute la función,
//  muestre con ver() un aviso "¡Lenta! 31,20 ms (límite 10 ms)" si ha tardado más del límite, y devuelva su
//  resultado. Pruébala con la función lenta y con la rápida que se dan.
// =============================================================================================
titulo('Problema 3');
$lenta = function (string $producto): string {
    usleep(30_000);                          // simula una consulta lenta a una API de precios
    return "precio de $producto: 19,99 €";
};
$rapida = fn(string $producto): string => "precio de $producto: 9,99 €";
// completa aquí tu solución
// ---- SOLUCIÓN ----
function conLimite(callable $funcion, float $limiteMs, mixed ...$argumentos): mixed
{
    [$resultado, $ms] = Cronometro::medir($funcion, ...$argumentos);
    if ($ms > $limiteMs) {
        ver('aviso', '¡Lenta! ' . Cronometro::formatear($ms) . " (límite $limiteMs ms)");
    }
    return $resultado;
}

ver('lenta', conLimite($lenta, 10, 'ratón'));      // aviso "¡Lenta! 33,30 ms (límite 10 ms)" (en Windows, entre 27 y 35 ms) y "precio de ratón: 19,99 €"
ver('rápida', conLimite($rapida, 10, 'teclado'));  // sin aviso: "precio de teclado: 9,99 €"

// =============================================================================================
// PROBLEMA 4. ¿Dónde se va el tiempo?
//  Con un Cronometro y una vuelta por parte, mide este proceso: 1) 'generar': 20 000 números al azar entre 1 y
//  100 000 (con la semilla 7); 2) 'filtrar': quedarse con los primos (escribe esPrimo(int $n): bool probando
//  divisores hasta la raíz cuadrada); 3) 'ordenar': ordenarlos de mayor a menor. Muestra cuántos primos hay, el
//  mayor, el informe y el nombre de la vuelta más lenta.
// =============================================================================================
titulo('Problema 4');
mt_srand(7);
// completa aquí tu solución
// ---- SOLUCIÓN ----
function esPrimo(int $n): bool
{
    if ($n < 2) {
        return false;
    }
    for ($d = 2; $d * $d <= $n; $d++) {
        if ($n % $d === 0) {
            return false;
        }
    }
    return true;
}

$crono = (new Cronometro())->iniciar();
$numeros = [];
for ($i = 0; $i < 20_000; $i++) {
    $numeros[] = mt_rand(1, 100_000);
}
$crono->vuelta('generar');
$primos = array_values(array_filter($numeros, 'esPrimo'));
$crono->vuelta('filtrar');
rsort($primos);
$crono->vuelta('ordenar');
$crono->parar();

ver('primos', count($primos));                          // 1928 (siempre el mismo: semilla 7)
ver('el mayor', $primos[0]);                            // 99871
ver('informe', $crono->informe());                      // "generar: 1,87 ms · filtrar: 14,47 ms · ordenar: 241 µs · total: 16,58 ms" (aprox.)
$vueltas = $crono->vueltas();
ver('la más lenta', array_search(max($vueltas), $vueltas, true));   // "filtrar"

// =============================================================================================
// PROBLEMA 5. ¿Cómo crece el método de la burbuja?
//  Se da ordenarBurbuja(). Mídela con listas de 500, 1000 y 2000 números al azar (semilla 1) y muestra cuántas
//  veces más tarda cada tamaño que el anterior. ¿Qué complejidad tiene? Compara con sort() para 2000 números.
// =============================================================================================
titulo('Problema 5');

function ordenarBurbuja(array $lista): array
{
    $n = count($lista);
    for ($i = 0; $i < $n - 1; $i++) {
        for ($j = 0; $j < $n - 1 - $i; $j++) {
            if ($lista[$j] > $lista[$j + 1]) {
                [$lista[$j], $lista[$j + 1]] = [$lista[$j + 1], $lista[$j]];
            }
        }
    }
    return $lista;
}

mt_srand(1);
// completa aquí tu solución
// ---- SOLUCIÓN ----
$anterior = null;
foreach ([500, 1000, 2000] as $tamanio) {
    $lista = [];
    for ($i = 0; $i < $tamanio; $i++) {
        $lista[] = mt_rand(1, 1_000_000);
    }
    [$ordenada, $ms] = Cronometro::medir('ordenarBurbuja', $lista);
    $factor = $anterior === null ? '' : ' x' . number_format($ms / $anterior, 1, ',', '');
    echo "  $tamanio: ", Cronometro::formatear($ms), $factor, nl();   // 500: 5,38 ms / 1000: 22,13 ms x4,1 / 2000: 90,63 ms x4,1 (aprox.)
    $anterior = $ms;
}
$copia = $lista;                                       // la de 2000
[, $msSort] = Cronometro::medir(function () use (&$copia) {
    sort($copia);
});
ver('sort() con 2000', Cronometro::formatear($msSort));    // "226 µs" (aprox.): unas 400 veces más rápido
ver('mismo orden', $copia === $ordenada);                    // true
ver('complejidad de la burbuja', 'O(n²): al duplicar n, el tiempo x4');

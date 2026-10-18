<?php
/*
 * e04-EFICIENCIA.php
 *  MEDIR LA MEMORIA: memory_get_usage, memory_get_peak_usage, memory_reset_peak_usage (PHP 8.2),
 *  memory_limit; arrays frente a generadores (yield)
 *  (apartado 5 de los apuntes)
 *
 *  Además de cuánto TARDA un script importa cuánta MEMORIA usa: cada petición tiene un límite (memory_limit,
 *  128 MB por defecto) y, si lo supera, PHP la corta con un error fatal.
 *
 *  Cómo trabajar este fichero:
 *   1. Pon un punto de ruptura en la primera línea de código y avanza con F10.
 *   2. Los bytes de los comentarios se obtuvieron con PHP 8.2 de 64 bits sin Xdebug; con Xdebug o en otra
 *      versión pueden cambiar un poco, pero no el orden de magnitud. Los tiempos son orientativos.
 */
require_once __DIR__ . '/utilidades.php';
require_once __DIR__ . '/Cronometro.php';

/** Bytes en la unidad más cómoda: "608 B", "4,00 MB" */
function bytes(int $n): string
{
    return match (true) {
        abs($n) < 1024 => "$n B",
        abs($n) < 1024 ** 2 => number_format($n / 1024, 2, ',', '.') . ' KB',
        default => number_format($n / 1024 ** 2, 2, ',', '.') . ' MB',
    };
}

// ---------------------------------------------------------------------------------------------
// memory_get_usage — Cuánta memoria usa el script AHORA
// ENLACE: https://www.php.net/manual/es/function.memory-get-usage.php
// ENUNCIADO EJEMPLO: ¿cuánto ocupa en memoria un array con 100 000 números?
// NOTA: se mide antes y después y se resta, igual que con el tiempo. Con el argumento true devuelve la memoria
//       que PHP ha pedido al sistema operativo (va en bloques de 2 MB), que siempre es más.
// ---------------------------------------------------------------------------------------------
titulo('memory_get_usage');
ver('memoria usada', bytes(memory_get_usage()));                 // "459,62 KB" (aprox.; más con Xdebug)
ver('memoria pedida al sistema', bytes(memory_get_usage(true)));  // "2,00 MB"
$antes = memory_get_usage();
$numeros = range(1, 100_000);
ver('el array ocupa', bytes(memory_get_usage() - $antes));        // "4,00 MB" (unos 40 bytes por número: el array reserva sitio de más)
unset($numeros);                                                  // se libera al quitar la última referencia
ver('después de unset', bytes(memory_get_usage() - $antes));      // "0 B"

// ---------------------------------------------------------------------------------------------
// memory_get_peak_usage y memory_reset_peak_usage — El PICO de memoria
// ENLACE: https://www.php.net/manual/es/function.memory-get-peak-usage.php
// ENLACE: https://www.php.net/manual/es/function.memory-reset-peak-usage.php
// ENUNCIADO EJEMPLO: una función crea un array enorme, lo usa y lo libera: al final memory_get_usage no lo
//                   ve, pero durante un momento la memoria subió mucho.
// NOTA: el pico es lo que hay que comparar con memory_limit. memory_reset_peak_usage() (PHP 8.2) pone el pico a
//       la memoria actual, para medir el pico de un trozo concreto.
// ---------------------------------------------------------------------------------------------
titulo('Pico de memoria');

function sumaDeCuadrados(int $n): int
{
    $cuadrados = array_map(fn(int $x) => $x * $x, range(1, $n));    // un array temporal grande
    return array_sum($cuadrados);
}                                                                    // al salir, $cuadrados se libera

memory_reset_peak_usage();
$base = memory_get_peak_usage();
$antes = memory_get_usage();
$total = sumaDeCuadrados(500_000);
ver('resultado', $total);                                         // 41666791666750000
ver('memoria después - antes', bytes(memory_get_usage() - $antes));   // "0 B": ya se ha liberado
ver('pero el pico subió', bytes(memory_get_peak_usage() - $base));    // "20,00 MB" (aprox.): el de range() y el de array_map a la vez
ver('límite de memoria', ini_get('memory_limit'));               // "512M" en el XAMPP del aula ("128M" por defecto)

// ---------------------------------------------------------------------------------------------
// Generadores (yield) — Recorrer muchos datos sin tenerlos todos en memoria
// ENLACE: https://www.php.net/manual/es/language.generators.overview.php
// ENUNCIADO EJEMPLO: sumar el importe de un millón de pedidos que se van leyendo uno a uno.
// NOTA: range(1, 1000000) crea el millón de números de golpe; un generador los va PRODUCIENDO de uno en uno,
//       así que la memoria no depende de cuántos haya. A cambio, cada vuelta cuesta algo más de tiempo: es un
//       COMPROMISO entre memoria y tiempo, y solo midiendo se sabe cuánto se gana y cuánto se pierde.
// ---------------------------------------------------------------------------------------------
titulo('Array frente a generador');

function numerosHasta(int $n): Generator
{
    for ($i = 1; $i <= $n; $i++) {
        yield $i;
    }
}

memory_reset_peak_usage();
$base = memory_get_peak_usage();
[$s1, $ms1] = Cronometro::medir(function () {
    $suma = 0;
    foreach (range(1, 1_000_000) as $x) {
        $suma += $x;
    }
    return $suma;
});
$picoArray = memory_get_peak_usage() - $base;

memory_reset_peak_usage();
$base = memory_get_peak_usage();
[$s2, $ms2] = Cronometro::medir(function () {
    $suma = 0;
    foreach (numerosHasta(1_000_000) as $x) {
        $suma += $x;
    }
    return $suma;
});
$picoGenerador = memory_get_peak_usage() - $base;

ver('mismo resultado', $s1 === $s2);                              // true (500000500000)
ver('array: memoria', bytes($picoArray));                         // "18,00 MB" (aprox.)
ver('generador: memoria', bytes($picoGenerador));                 // "992 B" (aprox.: lo que ocupan el generador y la closure)
ver('array: tiempo', Cronometro::formatear($ms1));                // "15,46 ms" (aprox.)
ver('generador: tiempo', Cronometro::formatear($ms2));            // "36,09 ms" (aprox.: más del doble)

<?php
/*
 * e03-EFICIENCIA_problema_solucion.php
 *  (FICHERO CON LAS SOLUCIONES)
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
// ---- SOLUCIÓN ----
function media(array $muestras): float
{
    return array_sum($muestras) / count($muestras);
}

function mediana(array $muestras): float
{
    sort($muestras);
    $n = count($muestras);
    $medio = intdiv($n, 2);
    return $n % 2 === 1 ? $muestras[$medio] : ($muestras[$medio - 1] + $muestras[$medio]) / 2;
}

ver('media', round(media($muestras), 2));          // 15.61: la muestra de 48,7 la arrastra
ver('mediana', mediana($muestras));                 // 10.1
$sinAnormal = array_values(array_filter($muestras, fn($m) => $m < 40));
ver('media sin la anormal', round(media($sinAnormal), 2));   // 10.1
ver('mediana sin la anormal', mediana($sinAnormal));         // 10.1 (casi no cambia)

// =============================================================================================
// PROBLEMA 2. isset o array_key_exists: primero, que hagan lo mismo
//  En el carrito, $descuentos[código] guarda el descuento de cada producto; null significa "producto sin
//  descuento pero que existe". Comprueba qué devuelven isset y array_key_exists para 'TEC01' y explica la
//  diferencia. Después compara su velocidad con Benchmark::comparar y muestra la tabla.
// =============================================================================================
titulo('Problema 2');
$descuentos = ['RAT01' => 0.10, 'TEC01' => null, 'PAN01' => 0.25];
// completa aquí tu solución
// ---- SOLUCIÓN ----
ver('isset', isset($descuentos['TEC01']));                       // false: isset dice false si el valor es null
ver('array_key_exists', array_key_exists('TEC01', $descuentos)); // true: solo mira si la clave existe
bloque(Benchmark::tabla(Benchmark::comparar([
    'isset' => fn() => isset($descuentos['TEC01']),
    'array_key_exists' => fn() => array_key_exists('TEC01', $descuentos),
], porLote: 10_000)));
// isset                  0,032 µs/llamada  x1,0
// array_key_exists       0,034 µs/llamada  x1,1   (empatan: elige según lo que necesites saber, no por velocidad)

// =============================================================================================
// PROBLEMA 3. Dar la vuelta a un texto
//  Escribe tres funciones que devuelvan un texto al revés: con strrev, con str_split + array_reverse + implode,
//  y con un bucle for que vaya del último carácter al primero. Comprueba con "Bar Baio" que las tres coinciden y
//  compáralas con un texto de 1000 caracteres. (Para textos con tildes harían falta las funciones mb_*.)
// =============================================================================================
titulo('Problema 3');
$texto = str_repeat('abcdefghij', 100);           // 1000 caracteres
// completa aquí tu solución
// ---- SOLUCIÓN ----
function alReves1(string $t): string
{
    return strrev($t);
}

function alReves2(string $t): string
{
    return implode('', array_reverse(str_split($t)));
}

function alReves3(string $t): string
{
    $resultado = '';
    for ($i = strlen($t) - 1; $i >= 0; $i--) {
        $resultado .= $t[$i];
    }
    return $resultado;
}

ver('las tres', [alReves1('Bar Baio'), alReves2('Bar Baio'), alReves3('Bar Baio')]);   // ['oiaB raB', 'oiaB raB', 'oiaB raB']
bloque(Benchmark::tabla(Benchmark::comparar([
    'strrev' => fn() => alReves1($texto),
    'str_split + array_reverse' => fn() => alReves2($texto),
    'bucle for' => fn() => alReves3($texto),
], porLote: 500)));
// strrev                          0,273 µs/llamada  x1,0
// str_split + array_reverse      11,917 µs/llamada  x43,6
// bucle for                      26,291 µs/llamada  x96,2   (aprox.: la función de PHP es la más rápida)

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
// ---- SOLUCIÓN ----
function esMasRapida(callable $a, callable $b, int $porLote = 1000): bool
{
    return Benchmark::medir($a, $porLote)['mediana'] < Benchmark::medir($b, $porLote)['mediana'];
}

$conIsset = fn() => isset($indice[$buscado]);
$conInArray = fn() => in_array($buscado, $codigos, true);
ver('isset más rápida que in_array', esMasRapida($conIsset, $conInArray));   // true
ver('in_array más rápida que isset', esMasRapida($conInArray, $conIsset));   // false

// =============================================================================================
// PROBLEMA 5. Avisar antes de medir
//  Escribe avisoXdebug(): ?string que devuelva el texto "Xdebug activo (debug, develop): los tiempos no son
//  fiables" (con los modos que estén activos) o null si Xdebug no está cargado o no tiene ningún modo. Recuerda:
//  con xdebug.mode=off, ini_get('xdebug.mode') devuelve '' y lo fiable es xdebug_info('mode').
// =============================================================================================
titulo('Problema 5');
// completa aquí tu solución
// ---- SOLUCIÓN ----
function avisoXdebug(): ?string
{
    if (!extension_loaded('xdebug') || !function_exists('xdebug_info')) {
        return null;
    }
    $modos = xdebug_info('mode');
    return $modos === [] ? null : 'Xdebug activo (' . implode(', ', $modos) . '): los tiempos no son fiables';
}

ver('aviso', avisoXdebug());     // NULL con -d xdebug.mode=off; "Xdebug activo (debug, develop): ..." al depurar

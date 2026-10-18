<?php
/*
 * e02-EFICIENCIA_problema.php
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

// =============================================================================================
// PROBLEMA 2. La media de varias ejecuciones
//  Una sola medida varía mucho. Escribe msMedio(callable $funcion, int $veces, mixed ...$argumentos): float que
//  ejecute la función $veces veces (con Cronometro::medir) y devuelva la media en milisegundos. Pruébala
//  midiendo 20 veces str_repeat('a', 1_000_000).
// =============================================================================================
titulo('Problema 2');
// completa aquí tu solución

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

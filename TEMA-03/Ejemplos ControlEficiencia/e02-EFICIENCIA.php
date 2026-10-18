<?php
/*
 * e02-EFICIENCIA.php
 *  MEDIR EL TIEMPO (2): CRONOMETRAR UNA FUNCIÓN Y LAS PARTES DE UN PROCESO
 *  Clase Cronometro (Cronometro.php): iniciar, vuelta, parar, totalMs, vueltas, informe, Cronometro::medir,
 *  Cronometro::formatear; callables (nombre de función, closure, método, sintaxis strlen(...))
 *  (apartado 3 de los apuntes)
 *
 *  El patrón siempre es el mismo: $inicio = hrtime(true); ...código...; $ms = (hrtime(true) - $inicio) / 1e6;
 *  Para no repetirlo, se mete en una clase que además permite medir por partes ("vueltas").
 *
 *  Cómo trabajar este fichero:
 *   1. Pon un punto de ruptura en la primera línea de código y ejecuta en modo depuración. Usa F11 para entrar
 *      en los métodos de Cronometro y ver cómo cambian $inicio, $ultimaMarca y $vueltas.
 *   2. Los TIEMPOS de los comentarios son orientativos (se obtuvieron con xdebug.mode=off). En modo depuración
 *      saldrán bastante más altos: es normal (lo explica e03-EFICIENCIA.php).
 */
require_once __DIR__ . '/utilidades.php';
require_once __DIR__ . '/Cronometro.php';

// ---------------------------------------------------------------------------------------------
// hrtime(true) antes y después — El patrón básico
// ENLACE: https://www.php.net/manual/es/function.hrtime.php
// ENUNCIADO EJEMPLO: ¿cuánto tarda en calcularse el 27.º número de Fibonacci con una función recursiva?
// NOTA: se resta y se divide entre 1e6 para pasar de nanosegundos a milisegundos. Lo que se mide es solo lo que
//       queda entre las dos lecturas: no metas en medio echo, var_dump ni nada que no quieras medir.
// ---------------------------------------------------------------------------------------------
titulo('El patrón básico');

function fibRecursivo(int $n): int
{
    return $n < 2 ? $n : fibRecursivo($n - 1) + fibRecursivo($n - 2);
}

$inicio = hrtime(true);
$resultado = fibRecursivo(27);
$ms = (hrtime(true) - $inicio) / 1e6;
ver('fibRecursivo(27)', $resultado);                    // 196418
ver('ha tardado (ms)', round($ms, 1));                  // 20 (aprox.)

// ---------------------------------------------------------------------------------------------
// Cronometro::medir — Medir cualquier función en una línea
// ENLACE: https://www.php.net/manual/es/language.types.callable.php
// ENUNCIADO EJEMPLO: comparar la versión recursiva de Fibonacci con una versión con un bucle.
// NOTA: medir() recibe un callable (el nombre de una función, una closure, [objeto, 'método'] o la sintaxis
//       strlen(...) de PHP 8.1) y sus argumentos, y devuelve [resultado, milisegundos]. Con la desestructuración
//       [$a, $b] = ... se recogen las dos cosas.
// ---------------------------------------------------------------------------------------------
titulo('Cronometro::medir');

function fibIterativo(int $n): int
{
    [$anterior, $actual] = [0, 1];
    for ($i = 0; $i < $n; $i++) {
        [$anterior, $actual] = [$actual, $anterior + $actual];
    }
    return $anterior;
}

[$r1, $ms1] = Cronometro::medir('fibRecursivo', 27);
[$r2, $ms2] = Cronometro::medir(fibIterativo(...), 27);
ver('recursivo', "$r1 en " . Cronometro::formatear($ms1));     // "196418 en 20,07 ms" (aprox.)
ver('iterativo', "$r2 en " . Cronometro::formatear($ms2));     // "196418 en 5 µs" (aprox.)
ver('el iterativo es más rápido', $ms2 < $ms1);                // true (miles de veces más rápido)

[$suma, $msSuma] = Cronometro::medir(fn(int $n) => array_sum(range(1, $n)), 1_000_000);
ver('suma de 1 a 1 000 000', $suma);                           // 500000500000
ver('tiempo', Cronometro::formatear($msSuma));                 // "5,95 ms" (aprox.)

// ---------------------------------------------------------------------------------------------
// ¿Cómo crece el tiempo? — Medir con distintos tamaños
// ENLACE: https://www.php.net/manual/es/function.printf.php
// ENUNCIADO EJEMPLO: ¿se podrá calcular fibRecursivo(40) para una práctica?
// NOTA: un solo tiempo dice poco; lo interesante es cómo CRECE al crecer el problema. Cada vez que n sube en 1,
//       la versión recursiva tarda unas 1,6 veces más (crecimiento exponencial): fib(40) tardaría varios
//       SEGUNDOS. La iterativa ni se nota.
// ---------------------------------------------------------------------------------------------
titulo('Cómo crece el tiempo');
$anterior = null;
foreach ([20, 22, 24, 26] as $n) {
    [, $ms] = Cronometro::medir('fibRecursivo', $n);              // [, $ms]: solo nos interesa el tiempo
    $factor = $anterior === null ? '' : sprintf(' (x%.1f)', $ms / $anterior);
    echo "  fib($n): ", Cronometro::formatear($ms), $factor, nl();  // 715 µs / 1,80 ms (x2.5) / 4,78 ms (x2.7) / 12,29 ms (x2.6)
    $anterior = $ms;
}

// ---------------------------------------------------------------------------------------------
// Vueltas — Cuánto tarda cada parte de un proceso
// ENLACE: https://www.php.net/manual/es/function.str-getcsv.php
// ENUNCIADO EJEMPLO: la tienda online importa cada noche un CSV con 50 000 pedidos y tarda demasiado:
//                   ¿dónde se va el tiempo, al leer, al calcular o al ordenar?
// NOTA: cada vuelta() guarda lo que ha pasado desde la vuelta anterior. Así no se adivina dónde está el cuello
//       de botella: se mide. (Los datos se generan aquí mismo con una semilla fija, para que salgan iguales.)
// ---------------------------------------------------------------------------------------------
titulo('Vueltas');
mt_srand(42);
$lineas = [];
for ($i = 1; $i <= 50_000; $i++) {
    $lineas[] = "$i;cliente" . mt_rand(1, 5000) . ';' . mt_rand(100, 99_999) / 100;
}

$crono = (new Cronometro())->iniciar();

$pedidos = array_map(fn(string $linea) => str_getcsv($linea, ';'), $lineas);      // 1) leer
$crono->vuelta('leer');

$totales = [];                                                                       // 2) calcular
foreach ($pedidos as [, $cliente, $importe]) {
    $totales[$cliente] = ($totales[$cliente] ?? 0) + (float) $importe;
}
$crono->vuelta('calcular');

arsort($totales);                                                                    // 3) ordenar
$mejores = array_slice($totales, 0, 3, true);
$crono->vuelta('ordenar');
$crono->parar();

ver('clientes distintos', count($totales));                    // 5000
ver('mejor cliente', array_key_first($mejores));               // "cliente2341"
ver('su total', round(reset($mejores), 2));                    // 12665.29 (siempre el mismo: semilla 42)
ver('vueltas (ms)', array_map(fn($ms) => round($ms, 1), $crono->vueltas()));   // ['leer' => 30.5, 'calcular' => 4.1, 'ordenar' => 0.7] (aprox.)
ver('informe', $crono->informe());                             // "leer: 30,52 ms · calcular: 4,14 ms · ordenar: 685 µs · total: 35,34 ms" (aprox.)

// ---------------------------------------------------------------------------------------------
// Errores de uso — Pedir una vuelta sin haber iniciado
// ENLACE: https://www.php.net/manual/es/class.logicexception.php
// ENUNCIADO EJEMPLO: alguien se olvida de llamar a iniciar().
// NOTA: Cronometro lanza LogicException: es un error del programador, no de los datos.
// ---------------------------------------------------------------------------------------------
titulo('Errores de uso');
$sinIniciar = new Cronometro();
verError('vuelta sin iniciar', fn() => $sinIniciar->vuelta('x'));   // "LogicException: Primero hay que llamar a iniciar()"

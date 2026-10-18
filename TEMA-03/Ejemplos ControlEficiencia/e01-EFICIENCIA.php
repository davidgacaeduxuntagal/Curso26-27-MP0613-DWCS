<?php
/*
 * e01-EFICIENCIA.php
 *  MEDIR EL TIEMPO (1): LOS RELOJES DE PHP
 *  Funciones: time, microtime, microtime(true), hrtime, hrtime(true), $_SERVER['REQUEST_TIME_FLOAT'],
 *             usleep, conversión de unidades (s, ms, µs, ns)
 *  (apartado 2 de los apuntes)
 *
 *  Para saber cuánto tarda algo se lee un reloj ANTES, se lee DESPUÉS y se restan. PHP tiene varios relojes
 *  con distinta precisión; para medir duraciones el bueno es hrtime(true).
 *
 *  Cómo trabajar este fichero:
 *   1. Pon un punto de ruptura en la primera línea de código y ejecuta en modo depuración.
 *   2. Avanza con F10 y observa en el panel VARIABLES los valores de cada reloj.
 *   3. Los comentarios a la derecha indican el resultado esperado. Los TIEMPOS cambian en cada ejecución y en
 *      cada equipo: el comentario dice el orden de magnitud que se obtuvo al preparar el material.
 *   4. OJO: el depurador (Xdebug) hace que PHP vaya bastante más lento. Para medir de verdad, ejecuta en la
 *      terminal con: php -d xdebug.mode=off e01-EFICIENCIA.php
 */
require_once __DIR__ . '/utilidades.php';

// ---------------------------------------------------------------------------------------------
// time — Segundos desde el 1 de enero de 1970 (marca de tiempo Unix)
// ENLACE: https://www.php.net/manual/es/function.time.php
// ENUNCIADO EJEMPLO: guardar en la base de datos cuándo se registró un usuario.
// NOTA: solo tiene precisión de SEGUNDOS: sirve para fechas, no para medir lo que tarda una función
//       (casi todo lo que hace un script tarda mucho menos de un segundo).
// ---------------------------------------------------------------------------------------------
titulo('time');
$ahora = time();
ver('time()', $ahora);                                // 1792315625 (cambia: es la hora actual)
ver('es un', get_debug_type($ahora));                 // "int"
$antes = time();
$suma = array_sum(range(1, 100000));                  // algo que tarda medio milisegundo
ver('time() después - antes', time() - $antes);       // 0 (casi siempre): ¡no sirve para medir esto!

// ---------------------------------------------------------------------------------------------
// microtime — Segundos con microsegundos
// ENLACE: https://www.php.net/manual/es/function.microtime.php
// ENUNCIADO EJEMPLO: anotar en un registro (log) la hora exacta de cada petición.
// NOTA: SIN argumento devuelve un STRING raro "0.20840500 1792315625" (microsegundos y segundos por separado).
//       Con microtime(true) devuelve un float en segundos, que ya se puede restar.
// ---------------------------------------------------------------------------------------------
titulo('microtime');
ver('microtime()', microtime());                      // "0.20840500 1792315625" (string: no sirve para restar)
$inicio = microtime(true);
ver('microtime(true)', $inicio);                      // 1792315625.208438 (float en segundos)
$suma = array_sum(range(1, 100000));
$segundos = microtime(true) - $inicio;
ver('ha tardado (s)', $segundos);                     // 0.000532... (del orden de medio ms)
ver('ha tardado (ms)', round($segundos * 1000, 3));   // 0.532 (aprox.: × 1000 para pasar a milisegundos)

// ---------------------------------------------------------------------------------------------
// hrtime — El reloj de alta resolución (en nanosegundos)
// ENLACE: https://www.php.net/manual/es/function.hrtime.php
// ENUNCIADO EJEMPLO: medir cuánto tarda la función que calcula el precio final del carrito.
// NOTA: hrtime(true) devuelve un ENTERO en nanosegundos (1 s = 1 000 000 000 ns) contados desde un momento
//       arbitrario (normalmente, el arranque del equipo): no es una hora, solo sirve para restar. Es MONOTÓNICO:
//       nunca va hacia atrás aunque se cambie la hora del sistema (horario de verano, sincronización NTP...).
//       hrtime() sin argumento devuelve [segundos, nanosegundos].
// ---------------------------------------------------------------------------------------------
titulo('hrtime');
ver('hrtime()', hrtime());                            // [7323, 295169000] (segundos y nanosegundos)
$inicio = hrtime(true);
ver('hrtime(true)', $inicio);                         // 7323295188600 (int en nanosegundos)
$suma = array_sum(range(1, 100000));
$ns = hrtime(true) - $inicio;
ver('ha tardado (ns)', $ns);                          // 530800 (aprox.)
ver('ha tardado (µs)', $ns / 1e3);                    // 530.8
ver('ha tardado (ms)', $ns / 1e6);                    // 0.5308
ver('ha tardado (s)', $ns / 1e9);                     // 0.0005308

// ---------------------------------------------------------------------------------------------
// Resolución — ¿Cuál es la diferencia más pequeña que distingue cada reloj?
// ENLACE: https://www.php.net/manual/es/function.hrtime.php
// ENUNCIADO EJEMPLO: decidir si se puede medir una sola llamada a strlen().
// NOTA: se leen dos veces seguidas y se busca la menor diferencia que no sea 0. En Windows, hrtime avanza de
//       100 en 100 ns y microtime(true) de microsegundo en microsegundo. Lo que tarde menos que eso, mide 0:
//       por eso las funciones muy rápidas se miden repitiéndolas muchas veces (e03-EFICIENCIA.php).
// ---------------------------------------------------------------------------------------------
titulo('Resolución de los relojes');
$menorHr = PHP_INT_MAX;
for ($i = 0; $i < 1000; $i++) {
    $a = hrtime(true);
    $b = hrtime(true);
    if ($b > $a) {
        $menorHr = min($menorHr, $b - $a);
    }
}
ver('hrtime: menor paso (ns)', $menorHr);             // 100 (en Windows; en otros sistemas puede ser distinto)
$menorMicro = PHP_FLOAT_MAX;
for ($i = 0; $i < 100000; $i++) {
    $a = microtime(true);
    $b = microtime(true);
    if ($b > $a) {
        $menorMicro = min($menorMicro, $b - $a);
    }
}
ver('microtime: menor paso (µs)', round($menorMicro * 1e6, 2));   // 0.95 (alrededor de 1 µs)
$cero = hrtime(true);
$longitud = strlen('Bar Baio');
ver('una llamada a strlen (ns)', hrtime(true) - $cero);           // 100-300: casi lo mínimo que distingue el reloj

// ---------------------------------------------------------------------------------------------
// $_SERVER['REQUEST_TIME_FLOAT'] — Cuándo empezó la petición (o el script)
// ENLACE: https://www.php.net/manual/es/reserved.variables.server.php
// ENUNCIADO EJEMPLO: escribir al pie de la página "Página generada en 35 ms".
// NOTA: PHP lo rellena él solo al empezar (microtime(true) del inicio), así que mide TODO el script, incluidos
//       los require. Existe también desde la terminal. (e05-EFICIENCIA.php lo usa en una página.)
// ---------------------------------------------------------------------------------------------
titulo('REQUEST_TIME_FLOAT');
ver('inicio del script', $_SERVER['REQUEST_TIME_FLOAT']);         // 1792315625.208027
$transcurrido = microtime(true) - $_SERVER['REQUEST_TIME_FLOAT'];
ver('ms desde que empezó', round($transcurrido * 1000, 1));       // 12.5 (casi todo, los bucles de arriba; más si depuras)

// ---------------------------------------------------------------------------------------------
// usleep — Esperar (y comprobar que esperar tampoco es exacto)
// ENLACE: https://www.php.net/manual/es/function.usleep.php
// ENUNCIADO EJEMPLO: simular una consulta lenta a una API para probar el cronómetro.
// NOTA: usleep recibe MICROsegundos. El sistema operativo no garantiza despertar justo a tiempo: en Windows
//       el planificador puede trabajar a saltos de hasta 15,6 ms, y pedir 10 ms puede dar 20 ms o más.
// ---------------------------------------------------------------------------------------------
titulo('usleep');
$inicio = hrtime(true);
usleep(10_000);                                       // 10 000 µs = 10 ms (el _ separa cifras, desde PHP 7.4)
ver('usleep(10 ms) ha tardado (ms)', round((hrtime(true) - $inicio) / 1e6, 1));   // entre 13 y 23 en Windows (y pedir 1 ms puede dar 16): nunca es exacto

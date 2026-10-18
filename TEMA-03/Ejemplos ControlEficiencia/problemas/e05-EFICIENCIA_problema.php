<?php
/*
 * e05-EFICIENCIA_problema.php
 *  Problemas de: medir una página (Server-Timing, tiempo real frente a CPU, registro de peticiones lentas)
 *  (repasa los ejemplos de ../e05-EFICIENCIA.php)
 *
 *  Resuelve cada problema donde se indica y ejecútalo en modo depuración para comprobarlo. Todos se pueden
 *  hacer y probar desde la terminal: trabajan con los textos de las cabeceras y del registro.
 */
require_once __DIR__ . '/../utilidades.php';

// =============================================================================================
// PROBLEMA 1. Construir la cabecera Server-Timing
//  Escribe serverTiming(array $medidas): string que reciba nombre => milisegundos, o nombre => [milisegundos,
//  descripción], y devuelva el texto de la cabecera con un decimal: "Server-Timing: bd;dur=31.3;desc="Base de
//  datos", calculo;dur=4.1". (Ojo: aquí el decimal es un punto, como exige el formato de la cabecera.)
// =============================================================================================
titulo('Problema 1');
$medidas = ['bd' => [31.27, 'Base de datos'], 'calculo' => 4.137, 'html' => 0.02];
// completa aquí tu solución

// =============================================================================================
// PROBLEMA 2. Leer una cabecera Server-Timing
//  Al revés: escribe leerServerTiming(string $cabecera): array que devuelva nombre => milisegundos (float). La
//  descripción y el resto de parámetros se ignoran; una medida sin dur cuenta como 0.
// =============================================================================================
titulo('Problema 2');
$recibida = 'Server-Timing: bd;dur=31.3;desc="Base de datos", cache;desc="acierto", calculo;dur=4.1';
// completa aquí tu solución

// =============================================================================================
// PROBLEMA 3. ¿Calcula o espera?
//  Escribe diagnostico(float $realMs, float $cpuMs): string que devuelva "espera (base de datos, ficheros o
//  red)" si la CPU es menos del 30 % del tiempo real, y "cálculo (revisa el código PHP)" si no. Pruébala con las
//  tres páginas medidas.
// =============================================================================================
titulo('Problema 3');
$paginas = [
    'inicio.php' => ['real' => 400.0, 'cpu' => 20.0],
    'informe.php' => ['real' => 900.0, 'cpu' => 850.0],
    'buscar.php' => ['real' => 120.0, 'cpu' => 36.0],
];
// completa aquí tu solución

// =============================================================================================
// PROBLEMA 4. La línea del registro de peticiones lentas
//  Escribe lineaLenta(string $pagina, float $ms, int $umbralMs, DateTimeInterface $momento): ?string que
//  devuelva null si la petición no supera el umbral y, si lo supera, la línea "2025-03-14 10:30:00 /buscar.php
//  812.4 ms" (con el salto de línea al final, lista para error_log($linea, 3, $fichero)).
// =============================================================================================
titulo('Problema 4');
$momento = new DateTimeImmutable('2025-03-14 10:30:00');
// completa aquí tu solución

// =============================================================================================
// PROBLEMA 5. Estadísticas del registro
//  Este es el contenido de datos/lentas.log de una semana. Escribe estadisticas(string $log): array que devuelva,
//  por página, cuántas veces aparece y su tiempo medio en ms (redondeado a 1 decimal), ordenadas de la que tiene
//  la media más alta a la más baja.
// =============================================================================================
titulo('Problema 5');
$log = <<<LOG
2025-03-10 09:12:01 /buscar.php 812.4 ms
2025-03-10 11:40:22 /informe.php 1530.0 ms
2025-03-11 08:05:13 /buscar.php 640.6 ms
2025-03-12 17:30:45 /index.php 520.1 ms
2025-03-13 12:00:00 /informe.php 1710.0 ms
2025-03-14 10:30:00 /buscar.php 905.0 ms
LOG;
// completa aquí tu solución

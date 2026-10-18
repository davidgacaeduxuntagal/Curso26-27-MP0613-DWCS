<?php
/*
 * e04-EFICIENCIA_problema.php
 *  Problemas de: medir la memoria (memory_get_usage, memory_get_peak_usage, memory_reset_peak_usage) y generadores
 *  (repasa los ejemplos de ../e04-EFICIENCIA.php)
 *
 *  Resuelve cada problema donde se indica y ejecútalo en modo depuración para comprobarlo. Los bytes de los
 *  comentarios se obtuvieron con PHP 8.2 de 64 bits sin Xdebug.
 */
require_once __DIR__ . '/../utilidades.php';

// =============================================================================================
// PROBLEMA 1. Bytes legibles
//  Escribe formatearBytes(int $bytes): string que use la unidad más grande posible entre B, KB, MB y GB (1 KB =
//  1024 B), con 2 decimales y coma decimal salvo en bytes: "512 B", "1,50 KB", "4,00 MB", "2,25 GB".
// =============================================================================================
titulo('Problema 1');
$tamanios = [512, 1536, 4_194_304, 2_415_919_104];
// completa aquí tu solución

// =============================================================================================
// PROBLEMA 2. ¿Cuánto ocupa lo que crea una función?
//  Escribe memoriaDe(callable $crear): int que devuelva los bytes que ocupa el valor que devuelve $crear.
//  ¡Cuidado!: si no guardas el resultado en una variable mientras mides, PHP lo libera enseguida y mide 0.
//  Pruébala con range(1, 100000) y con un texto de 1 000 000 de caracteres.
// =============================================================================================
titulo('Problema 2');
// completa aquí tu solución

// =============================================================================================
// PROBLEMA 3. Muchos textos pequeños o uno grande
//  Una lista de 10 000 correos se puede guardar como array o como un único texto separado por comas. Con
//  memoriaDe() del problema 2 (o midiendo a mano), compara lo que ocupa cada forma. ¿Por qué el array ocupa
//  mucho más que la suma de las letras?
// =============================================================================================
titulo('Problema 3');
$correos = array_map(fn($i) => "alumno$i@iesrodeira.gal", range(1, 10_000));
// completa aquí tu solución

// =============================================================================================
// PROBLEMA 4. El pico de una función
//  Escribe picoDe(callable $funcion): int que devuelva cuántos bytes ha subido el PICO de memoria mientras se
//  ejecutaba $funcion (usa memory_reset_peak_usage, PHP 8.2). Úsala para comparar dos formas de sumar los
//  cuadrados de 1 a 200 000: con array_sum(array_map(..., range(...))) y con un bucle for que vaya sumando.
//  Comprueba que dan lo mismo. ¿Por qué el bucle no sube el pico?
// =============================================================================================
titulo('Problema 4');
$n = 200_000;
// completa aquí tu solución

// =============================================================================================
// PROBLEMA 5. Un generador para un fichero enorme
//  Un CSV de pedidos llega como un texto con una línea por pedido. Escribe lineas(string $texto): Generator que
//  devuelva las líneas de una en una (usa strtok) y úsalo para sumar los importes (3.ª columna). Compara el
//  pico de memoria con hacerlo con explode("\n", ...) y comprueba que la suma es la misma.
// =============================================================================================
titulo('Problema 5');
mt_srand(3);
$csv = '';
for ($i = 1; $i <= 50_000; $i++) {
    $csv .= "$i;cliente" . mt_rand(1, 500) . ';' . mt_rand(100, 9_999) / 100 . "\n";
}
// completa aquí tu solución

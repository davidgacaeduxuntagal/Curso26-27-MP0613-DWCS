<?php
/*
 * e04-EFICIENCIA_problema_solucion.php
 *  (FICHERO CON LAS SOLUCIONES)
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
// ---- SOLUCIÓN ----
function formatearBytes(int $bytes): string
{
    $unidades = ['B', 'KB', 'MB', 'GB'];
    $i = 0;
    $valor = $bytes;
    while ($valor >= 1024 && $i < count($unidades) - 1) {
        $valor /= 1024;
        $i++;
    }
    return $i === 0 ? "$bytes B" : number_format($valor, 2, ',', '.') . ' ' . $unidades[$i];
}

ver('legibles', array_map('formatearBytes', $tamanios));   // ['512 B', '1,50 KB', '4,00 MB', '2,25 GB']

// =============================================================================================
// PROBLEMA 2. ¿Cuánto ocupa lo que crea una función?
//  Escribe memoriaDe(callable $crear): int que devuelva los bytes que ocupa el valor que devuelve $crear.
//  ¡Cuidado!: si no guardas el resultado en una variable mientras mides, PHP lo libera enseguida y mide 0.
//  Pruébala con range(1, 100000) y con un texto de 1 000 000 de caracteres.
// =============================================================================================
titulo('Problema 2');
// completa aquí tu solución
// ---- SOLUCIÓN ----
function memoriaDe(callable $crear): int
{
    $antes = memory_get_usage();
    $valor = $crear();                       // se guarda: mientras exista $valor, ocupa memoria
    $despues = memory_get_usage();
    unset($valor);
    return $despues - $antes;
}

ver('range(1, 100000)', memoriaDe(fn() => range(1, 100_000)));         // 4194384 (4 MB)
ver('texto de 1 000 000 de letras', memoriaDe(fn() => str_repeat('x', 1_000_000)));   // 1003520 (aprox. 1 MB: 1 byte por letra)

// =============================================================================================
// PROBLEMA 3. Muchos textos pequeños o uno grande
//  Una lista de 10 000 correos se puede guardar como array o como un único texto separado por comas. Con
//  memoriaDe() del problema 2 (o midiendo a mano), compara lo que ocupa cada forma. ¿Por qué el array ocupa
//  mucho más que la suma de las letras?
// =============================================================================================
titulo('Problema 3');
$correos = array_map(fn($i) => "alumno$i@iesrodeira.gal", range(1, 10_000));
// completa aquí tu solución
// ---- SOLUCIÓN ----
$antes = memory_get_usage();
$copiaArray = array_map(fn($c) => $c . '', $correos);    // una copia nueva de cada texto
$bytesArray = memory_get_usage() - $antes;
$antes = memory_get_usage();
$unTexto = implode(',', $correos);
$bytesTexto = memory_get_usage() - $antes;
ver('letras en total', strlen($unTexto));                 // 258893
ver('como array (bytes)', $bytesArray);                   // 825504 (unas 3 veces más)
ver('como un texto (bytes)', $bytesTexto);                // 262144 (casi lo mismo que las letras)
ver('por qué', 'cada texto del array lleva su cabecera (longitud, contador de referencias...) y el array, sus casillas');

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
// ---- SOLUCIÓN ----
function picoDe(callable $funcion): int
{
    memory_reset_peak_usage();
    $base = memory_get_peak_usage();
    $funcion();
    return memory_get_peak_usage() - $base;
}

$conArrays = 0;
$conBucle = 0;
$picoArrays = picoDe(function () use ($n, &$conArrays) {
    $conArrays = array_sum(array_map(fn($x) => $x * $x, range(1, $n)));   // range y array_map: 2 arrays
});
$picoBucle = picoDe(function () use ($n, &$conBucle) {
    for ($x = 1; $x <= $n; $x++) {
        $conBucle += $x * $x;                // solo un número que va creciendo
    }
});
ver('dan lo mismo', $conArrays === $conBucle);        // true (2666686666700000)
ver('pico con arrays', $picoArrays);                  // 12583456 (unos 12 MB)
ver('pico con el bucle', $picoBucle);                 // 0: no crea ningún array

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
// ---- SOLUCIÓN ----
function lineas(string $texto): Generator
{
    $linea = strtok($texto, "\n");
    while ($linea !== false) {
        yield $linea;
        $linea = strtok("\n");
    }
}

memory_reset_peak_usage();
$base = memory_get_peak_usage();
$suma1 = 0.0;
foreach (explode("\n", trim($csv)) as $linea) {
    $suma1 += (float) explode(';', $linea)[2];
}
$picoExplode = memory_get_peak_usage() - $base;

memory_reset_peak_usage();
$base = memory_get_peak_usage();
$suma2 = 0.0;
foreach (lineas($csv) as $linea) {
    $suma2 += (float) explode(';', $linea)[2];
}
$picoGenerador = memory_get_peak_usage() - $base;

ver('misma suma', round($suma1, 2) === round($suma2, 2));   // true
ver('suma', round($suma1, 2));                               // 2527723.19 (siempre la misma: semilla 3)
ver('pico con explode', $picoExplode);                       // 4570904 (unos 4,4 MB: todas las líneas a la vez)
ver('pico con el generador', $picoGenerador);                // 960: de una en una

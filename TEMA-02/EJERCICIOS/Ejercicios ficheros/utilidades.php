<?php
/*
 * utilidades.php
 *  Funciones auxiliares para los ejemplos de ficheros. Funcionan tanto desde la terminal / depurador
 *  de VSC (CLI) como desde el navegador (Apache).
 *
 *  CONSEJO para depurar: pon un punto de ruptura en la primera línea del ejemplo y avanza con
 *  "Step Over" (F10). Así no entras en estas funciones y ves las variables en el panel VARIABLES.
 *  Ten abierta a la vez la carpeta "datos" en el explorador de VSC: verás aparecer y cambiar los
 *  ficheros a medida que avanzas.
 */

/**
 * Ruta de un fichero dentro de la carpeta "datos" (junto a este fichero). Crea la carpeta si no existe.
 *  rutaDatos('notas.txt')  ->  .../Ejercicios ficheros/datos/notas.txt
 */
function rutaDatos(string $nombre = ''): string
{
    $carpeta = __DIR__ . '/datos';
    if (!is_dir($carpeta)) {
        mkdir($carpeta, 0777, true);
    }
    return $nombre === '' ? $carpeta : $carpeta . '/' . $nombre;
}

/**
 * Muestra una etiqueta y un valor:
 *  - los strings, tal cual entre comillas dobles (así se ven los espacios y los saltos de línea)
 *  - el resto (números, true/false/null, arrays...) con var_export
 */
function ver(string $etiqueta, mixed $valor): void
{
    if (is_resource($valor)) {
        $valor = 'resource(' . get_resource_id($valor) . ') of type (' . get_resource_type($valor) . ')';
    }
    $texto = $etiqueta . ' => ' . (is_string($valor) ? '"' . $valor . '"' : var_export($valor, true));
    if (PHP_SAPI === 'cli') {
        echo $texto, PHP_EOL;
    } else {
        echo '<pre>', htmlspecialchars($texto, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'), '</pre>';
    }
}

/**
 * Muestra un título de sección.
 */
function titulo(string $texto): void
{
    if (PHP_SAPI === 'cli') {
        echo PHP_EOL, '===== ', $texto, ' =====', PHP_EOL;
    } else {
        echo '<h3>', htmlspecialchars($texto), '</h3>';
    }
}

/**
 * Salto de línea adecuado para la salida (en el navegador, <br>).
 */
function nl(): string
{
    return PHP_SAPI === 'cli' ? PHP_EOL : '<br>';
}

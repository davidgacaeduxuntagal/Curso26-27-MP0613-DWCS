<?php
/*
 * utilidades.php
 *  Función auxiliar para mostrar los resultados de los ejemplos, tanto si se ejecutan
 *  desde la terminal / depurador de VSC (CLI) como desde el navegador (Apache).
 *
 *  CONSEJO para depurar: pon un punto de ruptura en la primera línea del ejemplo y avanza
 *  con "Step Over" (F10). Así no entras en esta función y ves las variables en el panel VARIABLES.
 */

/**
 * Muestra una etiqueta y el valor de una variable:
 *  - los strings, tal cual entre comillas dobles (así se ven los espacios y las barras \ reales)
 *  - el resto (números, true/false/null, arrays...) con var_export
 */
function ver(string $etiqueta, mixed $valor): void
{
    $texto = $etiqueta . ' => ' . (is_string($valor) ? '"' . $valor . '"' : var_export($valor, true));
    if (PHP_SAPI === 'cli') {
        echo $texto, PHP_EOL;
    } else {
        // En el navegador escapamos el texto para ver exactamente lo que contiene el string
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

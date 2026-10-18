<?php
/*
 * utilidades.php
 *  Funciones auxiliares para los ejemplos de fecha y hora. Funcionan tanto desde la terminal /
 *  depurador de VSC (CLI) como desde el navegador (Apache).
 *
 *  CONSEJO para depurar: pon un punto de ruptura en la primera línea del ejemplo y avanza con
 *  "Step Over" (F10). Así no entras en estas funciones y ves las variables en el panel VARIABLES.
 *  En el panel VARIABLES, despliega los objetos DateTime / DateInterval para ver sus campos
 *  (date, timezone, y, m, d, days, invert...).
 */

// Todos los ejemplos trabajan con la hora peninsular española, configure lo que configure php.ini
date_default_timezone_set('Europe/Madrid');

/**
 * Ruta de un fichero dentro de la carpeta "datos" (junto a este fichero). Crea la carpeta si no existe.
 *  rutaDatos('notas.txt')  ->  .../Ejercicios DATETIME/datos/notas.txt
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
 *  - las fechas, intervalos y zonas horarias, de forma resumida (ver describir())
 *  - el resto (números, true/false/null, arrays...) con var_export
 */
function ver(string $etiqueta, mixed $valor): void
{
    $valor = describir($valor);
    $texto = $etiqueta . ' => ' . (is_string($valor) ? '"' . $valor . '"' : var_export($valor, true));
    if (PHP_SAPI === 'cli') {
        echo $texto, PHP_EOL;
    } else {
        echo '<pre>', htmlspecialchars($texto, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'), '</pre>';
    }
}

/**
 * Convierte los objetos de fecha en un texto corto y legible (también dentro de arrays):
 *  DateTimeImmutable(2026-10-16 21:30:00 Europe/Madrid, Friday)
 *  DateInterval(+0 a 2 m 3 d 00:00:00, days=64)
 *  DateTimeZone(Europe/Madrid)
 */
function describir(mixed $valor): mixed
{
    if ($valor instanceof DateTimeInterface) {
        return get_class($valor) . '(' . $valor->format('Y-m-d H:i:s e, l') . ')';
    }
    if ($valor instanceof DateInterval) {
        $dias = $valor->days === false ? 'false' : $valor->days;
        return 'DateInterval(' . $valor->format('%R%y a %m m %d d %H:%I:%S') . ", days=$dias)";
    }
    if ($valor instanceof DateTimeZone) {
        return 'DateTimeZone(' . $valor->getName() . ')';
    }
    if (is_array($valor)) {
        return array_map('describir', $valor);
    }
    return $valor;
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

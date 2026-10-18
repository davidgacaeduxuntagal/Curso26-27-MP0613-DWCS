<?php
/*
 * buscar-los-errores_solucion.php — EJERCICIO DE DEPURACIÓN (SOLUCIÓN)
 *
 *  Este script prueba tres funciones del torneo con datos de ejemplo. Las tres tienen un ERROR:
 *  el resultado obtenido no coincide con el esperado. No hay ningún mensaje de error de PHP:
 *  son errores de LÓGICA, los más difíciles de encontrar.
 *
 *  Cómo trabajar:
 *   1. Ejecuta el script (F5 en VSC con "Launch currently open script") y mira qué pruebas fallan.
 *   2. Pon un punto de ruptura (F9) en la primera línea de la función que falla y vuelve a
 *      ejecutar. Avanza con F10 / F11 y mira en el panel VARIABLES (o añade una expresión en
 *      INSPECCIÓN / WATCH) el valor de cada variable en cada momento.
 *   3. Cuando encuentres el error, escribe en un comentario CUÁL era y POR QUÉ, y corrígelo.
 *   4. Vuelve a ejecutar: todas las pruebas deben decir "OK".
 *  La solución está en buscar-los-errores_solucion.php (¡no la mires antes de intentarlo!).
 */

const MAX_EQUIPOS = 16;

// Devuelve true si todavía se puede inscribir algún equipo
function quedanPlazas(array $equipos): bool
{
    // ERROR 1: con <= devuelve true también cuando ya hay 16 equipos. Con 16 NO quedan plazas.
    //          Lo delata la prueba de 16 equipos: count() vale 16 y 16 <= 16 es true.
    //          Es un error «por uno» (off-by-one), muy habitual en los límites.
    return count($equipos) < MAX_EQUIPOS;
}

// Devuelve el teléfono sin espacios, o '' si no es válido (9 cifras, empezando por 6, 7, 8 o 9)
function normalizarTelefono(string $telefono): string
{
    // ERROR 2: str_replace NO modifica la variable: DEVUELVE el texto nuevo, y aquí se perdía.
    //          En el depurador se ve que $telefono sigue valiendo '600 123 456' después de la línea.
    $telefono = str_replace(' ', '', $telefono);
    return preg_match('/^[6789]\d{8}$/', $telefono) ? $telefono : '';
}

// Devuelve true si el nombre del equipo tiene entre 3 y 10 caracteres
function longitudCorrecta(string $nombre): bool
{
    // ERROR 3: strlen cuenta BYTES, no caracteres. En UTF-8, la Ñ y la ú ocupan 2 bytes, así
    //          que 'Los Ñandús' (10 caracteres) mide 12 con strlen. Añadiendo la expresión
    //          strlen($nombre) en WATCH se ve enseguida. mb_strlen cuenta caracteres.
    return mb_strlen($nombre) >= 3 && mb_strlen($nombre) <= 10;
}

// ---------------------------------------------------------------------------------------------
// Pruebas: [descripción, resultado obtenido, resultado esperado]
// ---------------------------------------------------------------------------------------------
$pruebas = [
    ['15 equipos: quedan plazas', quedanPlazas(array_fill(0, 15, [])), true],
    ['16 equipos: no quedan plazas', quedanPlazas(array_fill(0, 16, [])), false],
    ['teléfono "600123456"', normalizarTelefono('600123456'), '600123456'],
    ['teléfono "600 123 456"', normalizarTelefono('600 123 456'), '600123456'],
    ['nombre "Os Ñus" (6 letras)', longitudCorrecta('Os Ñus'), true],
    ['nombre "Los Ñandús" (10 letras)', longitudCorrecta('Los Ñandús'), true],
    ['nombre "Azulgrana FC" (12 letras)', longitudCorrecta('Azulgrana FC'), false],
];

foreach ($pruebas as [$descripcion, $obtenido, $esperado]) {
    $estado = $obtenido === $esperado ? 'OK   ' : 'FALLA';
    echo $estado, ' ', $descripcion, ': obtenido ', var_export($obtenido, true),
         ', esperado ', var_export($esperado, true), PHP_EOL;
}

<?php
/*
 * 08_regexes_preg_02_problema_solucion.php
 *  (FICHERO CON LAS SOLUCIONES)
 *  Problemas de: preg_replace, preg_filter, preg_replace_callback, preg_replace_callback_array
 *  (repasa los ejemplos de ../08_regexes_preg_02.php)
 *
 *  Resuelve cada problema donde se indica y ejecútalo en modo depuración para comprobarlo.
 */
require_once __DIR__ . '/../utilidades.php';

// =============================================================================================
// PROBLEMA 1. Normalizar teléfonos
//  Los teléfonos llegan escritos de muchas formas. Quita todo lo que no sea dígito y, si empieza
//  por 34 y tiene 11 dígitos, quita ese prefijo. Después dale el formato "612 345 678".
// =============================================================================================
titulo('Problema 1');
$telefonos = ["612-345-678", "+34 612 345 678", "(986) 12 34 56", "0034612345678"];
// completa aquí tu solución
// ---- SOLUCIÓN ----
$normalizados = [];
foreach ($telefonos as $t) {
    $digitos = preg_replace('/\D/', '', $t);
    $digitos = preg_replace('/^(?:00)?34(\d{9})$/', '$1', $digitos);
    $normalizados[$t] = preg_replace('/^(\d{3})(\d{3})(\d{3})$/', '$1 $2 $3', $digitos);
}
ver('normalizados', $normalizados);

// =============================================================================================
// PROBLEMA 2. Enlaces automáticos
//  Convierte en enlaces HTML las URL que aparezcan en un comentario:
//  "Mira https://x.es/a" -> 'Mira <a href="https://x.es/a">https://x.es/a</a>'
// =============================================================================================
titulo('Problema 2');
$comentario = "Mira https://www.iesrodeira.gal y también http://example.com/ruta?a=1 ¡genial!";
// completa aquí tu solución
// ---- SOLUCIÓN ----
$conEnlaces = preg_replace('/https?:\/\/\S+/', '<a href="$0">$0</a>', $comentario);
ver('con enlaces', $conEnlaces);

// =============================================================================================
// PROBLEMA 3. Solo los códigos válidos, reformateados
//  De una lista de códigos de producto, quédate SOLO con los que tienen el formato "AAA999"
//  (3 letras y 3 dígitos) y reescríbelos como "AAA-999" en mayúsculas. Usa preg_filter.
// =============================================================================================
titulo('Problema 3');
$codigos = ["abc123", "XYZ999", "AB12", "QWE-456", "rty007"];
// completa aquí tu solución
// ---- SOLUCIÓN ----
$validos = preg_filter('/^([a-z]{3})(\d{3})$/i', '$1-$2', $codigos);
$validos = array_map('strtoupper', $validos);
ver('válidos', $validos);    // [0 => ABC-123, 1 => XYZ-999, 4 => RTY-007]

// =============================================================================================
// PROBLEMA 4. Convertir temperaturas
//  Convierte todas las temperaturas en grados Fahrenheit del texto ("86ºF") a Celsius con un
//  decimal ("30,0ºC"). C = (F - 32) * 5 / 9.
// =============================================================================================
titulo('Problema 4');
$texto = "Máxima de 86ºF y mínima de 59ºF; ayer llegó a 100ºF.";
// completa aquí tu solución
// ---- SOLUCIÓN ----
$convertido = preg_replace_callback('/(\d+)ºF/u', function ($m) {
    $celsius = ((int) $m[1] - 32) * 5 / 9;
    return number_format($celsius, 1, ',', '') . 'ºC';
}, $texto);
ver('en Celsius', $convertido);   // 30,0ºC, 15,0ºC, 37,8ºC

// =============================================================================================
// PROBLEMA 5. Mini lenguaje de marcas
//  Convierte un texto con marcas propias a HTML con preg_replace_callback_array:
//    *texto*       -> <b>texto</b>
//    [texto](url)  -> <a href="url">texto</a>
//    :sonrisa:     -> 😀       (y :triste: -> 😢; otros códigos se dejan igual)
// =============================================================================================
titulo('Problema 5');
$texto = "Esto es *importante*. Visita [mi web](https://x.es) :sonrisa: :triste: :otro:";
// completa aquí tu solución
// ---- SOLUCIÓN ----
$emojis = ['sonrisa' => '😀', 'triste' => '😢'];
$html   = preg_replace_callback_array([
    '/\*(.+?)\*/'               => fn($m) => "<b>$m[1]</b>",
    '/\[(.+?)\]\((\S+?)\)/'     => fn($m) => "<a href=\"$m[2]\">$m[1]</a>",
    '/:(\w+):/'                 => fn($m) => $emojis[$m[1]] ?? $m[0],
], $texto);
ver('HTML', $html);

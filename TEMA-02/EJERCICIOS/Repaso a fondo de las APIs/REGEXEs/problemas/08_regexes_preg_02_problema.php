<?php
/*
 * 08_regexes_preg_02_problema.php
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

// =============================================================================================
// PROBLEMA 2. Enlaces automáticos
//  Convierte en enlaces HTML las URL que aparezcan en un comentario:
//  "Mira https://x.es/a" -> 'Mira <a href="https://x.es/a">https://x.es/a</a>'
// =============================================================================================
titulo('Problema 2');
$comentario = "Mira https://www.iesrodeira.gal y también http://example.com/ruta?a=1 ¡genial!";
// completa aquí tu solución

// =============================================================================================
// PROBLEMA 3. Solo los códigos válidos, reformateados
//  De una lista de códigos de producto, quédate SOLO con los que tienen el formato "AAA999"
//  (3 letras y 3 dígitos) y reescríbelos como "AAA-999" en mayúsculas. Usa preg_filter.
// =============================================================================================
titulo('Problema 3');
$codigos = ["abc123", "XYZ999", "AB12", "QWE-456", "rty007"];
// completa aquí tu solución

// =============================================================================================
// PROBLEMA 4. Convertir temperaturas
//  Convierte todas las temperaturas en grados Fahrenheit del texto ("86ºF") a Celsius con un
//  decimal ("30,0ºC"). C = (F - 32) * 5 / 9.
// =============================================================================================
titulo('Problema 4');
$texto = "Máxima de 86ºF y mínima de 59ºF; ayer llegó a 100ºF.";
// completa aquí tu solución

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

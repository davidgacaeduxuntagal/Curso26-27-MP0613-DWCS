<?php
/*
 * 16_strings_i18n_01_problema_solucion.php
 *  (FICHERO CON LAS SOLUCIONES)
 *  Problemas de: setlocale, localeconv, number_format, nl_langinfo, money_format (eliminada)
 *  (repasa los ejemplos de ../16_strings_i18n_01.php)
 *
 *  Resuelve cada problema donde se indica y ejecútalo en modo depuración para comprobarlo.
 */
require_once __DIR__ . '/../utilidades.php';

// =============================================================================================
// PROBLEMA 1. Activar el idioma español en cualquier sistema
//  Activa el locale español probando varios nombres (Linux y Windows) y muestra qué nombre se
//  ha activado. Si no se ha podido activar ninguno, muestra un aviso.
// =============================================================================================
titulo('Problema 1');
// completa aquí tu solución
// ---- SOLUCIÓN ----
$locale = setlocale(LC_ALL, ["es_ES.UTF-8", "es_ES", "Spanish_Spain", "es-ES", "es"]);
ver('locale', $locale === false ? "AVISO: no hay ningún locale español instalado" : $locale);

// =============================================================================================
// PROBLEMA 2. Factura con formato español
//  Calcula la base, el IVA (21 %) y el total de las líneas de una factura y muéstralos con 2
//  decimales, coma decimal y punto de miles (number_format).
// =============================================================================================
titulo('Problema 2');
$lineas = [["Portátil", 1, 899.99], ["Ratón", 3, 12.5], ["Monitor 27\"", 2, 239]];
// completa aquí tu solución
// ---- SOLUCIÓN ----
$base = 0;
foreach ($lineas as [$producto, $cantidad, $precio]) {
    $base += $cantidad * $precio;
}
$iva   = $base * 0.21;
$total = $base + $iva;
ver('base', number_format($base, 2, ",", ".") . " €");    // "1.415,49 €"
ver('IVA', number_format($iva, 2, ",", ".") . " €");      // "297,25 €"
ver('total', number_format($total, 2, ",", ".") . " €");  // "1.712,74 €"

// =============================================================================================
// PROBLEMA 3. Separadores según el locale
//  Escribe una función formatoLocal($numero, $decimales) que use los separadores decimal y de
//  miles del locale activo (localeconv). Pruébala con el locale español y con el "C".
// =============================================================================================
titulo('Problema 3');
// completa aquí tu solución
// ---- SOLUCIÓN ----
function formatoLocal(float $numero, int $decimales): string
{
    $info = localeconv();
    $miles = $info['thousands_sep'] !== '' ? $info['thousands_sep'] : '';
    return number_format($numero, $decimales, $info['decimal_point'], $miles);
}
setlocale(LC_ALL, ["es_ES.UTF-8", "es_ES", "Spanish_Spain", "es"]);
ver('español', formatoLocal(1234567.891, 2));   // "1.234.567,89"
setlocale(LC_ALL, "C");
ver('locale C', formatoLocal(1234567.891, 2));  // "1234567.89"

// =============================================================================================
// PROBLEMA 4. Código que funciona en Windows y en Linux
//  Este código falla en Windows porque nl_langinfo no existe allí. ¿Cómo harías para que no
//  falle en ningún sistema? Muestra el resultado o el mensaje "no disponible".
// =============================================================================================
titulo('Problema 4');
// $formatoFecha = nl_langinfo(D_FMT);   // <- en Windows: Error "Call to undefined function"
// ¿cómo lo harías?
// ---- SOLUCIÓN ----
$formatoFecha = function_exists('nl_langinfo') ? nl_langinfo(D_FMT) : "no disponible en " . PHP_OS_FAMILY;
ver('formato de fecha del locale', $formatoFecha);

// =============================================================================================
// PROBLEMA 5. Precios en varias monedas
//  money_format ya no existe. Muestra el mismo precio como lo vería un cliente de España (EUR),
//  de Reino Unido (GBP) y de Japón (JPY), usando NumberFormatter (extensión intl).
// =============================================================================================
titulo('Problema 5');
$precio = 1999.5;
// completa aquí tu solución
// ---- SOLUCIÓN ----
$monedas = ['es_ES' => 'EUR', 'en_GB' => 'GBP', 'ja_JP' => 'JPY'];
$precios = [];
if (class_exists('NumberFormatter')) {
    foreach ($monedas as $locale => $moneda) {
        $formato           = new NumberFormatter($locale, NumberFormatter::CURRENCY);
        $precios[$locale]  = $formato->formatCurrency($precio, $moneda);
    }
}
ver('precios', $precios);    // ['es_ES' => '1.999,50 €', 'en_GB' => '£1,999.50', 'ja_JP' => '￥2,000']

<?php
/*
 * 16_strings_i18n_01_problema.php
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

// =============================================================================================
// PROBLEMA 2. Factura con formato español
//  Calcula la base, el IVA (21 %) y el total de las líneas de una factura y muéstralos con 2
//  decimales, coma decimal y punto de miles (number_format).
// =============================================================================================
titulo('Problema 2');
$lineas = [["Portátil", 1, 899.99], ["Ratón", 3, 12.5], ["Monitor 27\"", 2, 239]];
// completa aquí tu solución

// =============================================================================================
// PROBLEMA 3. Separadores según el locale
//  Escribe una función formatoLocal($numero, $decimales) que use los separadores decimal y de
//  miles del locale activo (localeconv). Pruébala con el locale español y con el "C".
// =============================================================================================
titulo('Problema 3');
// completa aquí tu solución

// =============================================================================================
// PROBLEMA 4. Código que funciona en Windows y en Linux
//  Este código falla en Windows porque nl_langinfo no existe allí. ¿Cómo harías para que no
//  falle en ningún sistema? Muestra el resultado o el mensaje "no disponible".
// =============================================================================================
titulo('Problema 4');
// $formatoFecha = nl_langinfo(D_FMT);   // <- en Windows: Error "Call to undefined function"
// ¿cómo lo harías?

// =============================================================================================
// PROBLEMA 5. Precios en varias monedas
//  money_format ya no existe. Muestra el mismo precio como lo vería un cliente de España (EUR),
//  de Reino Unido (GBP) y de Japón (JPY), usando NumberFormatter (extensión intl).
// =============================================================================================
titulo('Problema 5');
$precio = 1999.5;
// completa aquí tu solución

<?php
/*
 * 16_strings_i18n_01.php
 *  STRINGS - I18N (1): idioma y país (locale), números y monedas
 *  Funciones: setlocale, localeconv, number_format, nl_langinfo, money_format (eliminada)
 *
 *  "i18n" es la abreviatura de "internationalization" (i + 18 letras + n): preparar una
 *  aplicación para varios idiomas y países. Un "locale" es la configuración de un idioma + país
 *  (es_ES, gl_ES, en_US...): separador decimal, símbolo de moneda, orden alfabético...
 *
 *  ¡OJO! El locale depende del SISTEMA OPERATIVO y de los idiomas que tenga instalados:
 *      Linux:   "es_ES.UTF-8"           Windows: "Spanish_Spain" o "es-ES"
 *
 *  Ejecuta en modo depuración paso a paso (F10).
 */
require_once __DIR__ . '/utilidades.php';

// ---------------------------------------------------------------------------------------------
// setlocale — establece (o consulta) el locale del proceso
// ENLACE: https://www.php.net/manual/es/function.setlocale.php
// ENUNCIADO EJEMPLO: configurar el idioma español para el formato de números, probando varios
//   nombres para que funcione en Linux y en Windows, y consultar el locale actual.
// NOTA: devuelve el nombre del locale activado o false si no existe ninguno de los indicados.
//   Categorías: LC_ALL (todas), LC_NUMERIC (números), LC_MONETARY (moneda), LC_COLLATE (orden),
//   LC_TIME (fechas)... Con "0" como locale solo CONSULTA el actual sin cambiarlo.
// ---------------------------------------------------------------------------------------------
titulo('setlocale');
$antes         = setlocale(LC_ALL, "0");                         // consultar
$activado      = setlocale(LC_ALL, ["es_ES.UTF-8", "es_ES", "Spanish_Spain", "es"]);
$noExiste      = setlocale(LC_ALL, "klingon_KLINGON");           // false
setlocale(LC_ALL, $activado);                                    // lo dejamos en español
ver('locale inicial', $antes);
ver('locale activado', $activado);   // en Windows: "Spanish_Spain.1252"; en Linux: "es_ES.UTF-8"
ver('locale inexistente', $noExiste);

// ---------------------------------------------------------------------------------------------
// localeconv — devuelve un array con los datos numéricos y monetarios del locale activo
// ENLACE: https://www.php.net/manual/es/function.localeconv.php
// ENUNCIADO EJEMPLO: saber qué separador decimal y de miles y qué símbolo de moneda usa el
//   país configurado, para mostrar precios.
// NOTA: en Windows el locale español usa la codificación Windows-1252, por eso el símbolo "€"
//   llega en esa codificación y se ve mal en UTF-8: hay que convertirlo con mb_convert_encoding.
// ---------------------------------------------------------------------------------------------
titulo('localeconv');
$info          = localeconv();
ver('localeconv (algunos datos)', [
    'decimal_point'   => $info['decimal_point'],      // ","
    'thousands_sep'   => $info['thousands_sep'],      // "."
    'int_curr_symbol' => $info['int_curr_symbol'],    // "EUR"
]);
$simbolo       = $info['currency_symbol'];
if (!mb_check_encoding($simbolo, 'UTF-8')) {
    $simbolo   = mb_convert_encoding($simbolo, 'UTF-8', 'Windows-1252');
}
ver('símbolo de moneda', $simbolo);                   // "€"

// ---------------------------------------------------------------------------------------------
// number_format — formatea un número con decimales y separadores de miles
// ENLACE: https://www.php.net/manual/es/function.number-format.php
// ENUNCIADO EJEMPLO: mostrar el precio de un producto y el total de una factura al estilo
//   español (1.234,50) y al estilo inglés (1,234.50).
// NOTA: parámetros: (número, decimales, separador decimal, separador de miles). NO depende del
//   locale: los separadores se indican explícitamente. Por defecto usa el formato inglés.
//   Redondea: 2.675 con 2 decimales puede dar 2.68.
// ---------------------------------------------------------------------------------------------
titulo('number_format');
$total         = 1234.5;
$espanol       = number_format($total, 2, ",", ".");           // "1.234,50"
$ingles        = number_format($total, 2);                     // "1,234.50"
$sinDecimales  = number_format(1234567.891);                   // "1,234,568"
$conLocale     = number_format($total, 2, $info['decimal_point'], $info['thousands_sep']);
ver('español', $espanol . " €");
ver('inglés', "$" . $ingles);
ver('sin decimales', $sinDecimales);
ver('con los separadores del locale', $conLocale);

// ---------------------------------------------------------------------------------------------
// nl_langinfo — información del locale (nombres de días y meses, formato de fecha...)
// ENLACE: https://www.php.net/manual/es/function.nl-langinfo.php
// ENUNCIADO EJEMPLO: obtener el nombre del primer día de la semana en el idioma configurado.
// NOTA: NO EXISTE EN WINDOWS. Por eso comprobamos antes si la función existe con function_exists.
// ---------------------------------------------------------------------------------------------
titulo('nl_langinfo');
if (function_exists('nl_langinfo')) {
    $dia       = nl_langinfo(DAY_2);                             // "lunes" en Linux con es_ES
    ver('nl_langinfo(DAY_2)', $dia);
} else {
    ver('nl_langinfo', 'no disponible en este sistema (' . PHP_OS_FAMILY . ')');
}

// ---------------------------------------------------------------------------------------------
// money_format — formateaba cantidades de dinero según el locale. ELIMINADA en PHP 8.
// ENLACE: https://www.php.net/manual/es/function.money-format.php
// ENUNCIADO EJEMPLO: mostrar un precio con el formato de moneda de España y de EE. UU. usando la
//   alternativa actual, la clase NumberFormatter de la extensión intl.
// NOTA: llamar hoy a money_format() produce un error fatal ("Call to undefined function"). La
//   extensión intl debe estar activada en php.ini (extension=intl).
// ---------------------------------------------------------------------------------------------
titulo('money_format (eliminada) -> NumberFormatter');
ver('¿existe money_format?', function_exists('money_format'));    // false
if (class_exists('NumberFormatter')) {
    $formatoEs = new NumberFormatter('es_ES', NumberFormatter::CURRENCY);
    $formatoUs = new NumberFormatter('en_US', NumberFormatter::CURRENCY);
    ver('es_ES', $formatoEs->formatCurrency($total, 'EUR'));       // "1.234,50 €"
    ver('en_US', $formatoUs->formatCurrency($total, 'USD'));       // "$1,234.50"
} else {
    ver('NumberFormatter', 'la extensión intl no está activada');
}

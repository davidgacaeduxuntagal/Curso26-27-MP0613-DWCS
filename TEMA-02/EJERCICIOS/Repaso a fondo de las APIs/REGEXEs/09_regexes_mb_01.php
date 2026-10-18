<?php
/*
 * 09_regexes_mb_01.php
 *  REGEXES - API MULTIBYTE (1): las funciones mb_ereg (motor Oniguruma)
 *  Funciones: mb_regex_encoding, mb_ereg, mb_eregi, mb_ereg_match, mb_ereg_replace,
 *             mb_eregi_replace, mb_ereg_replace_callback, mb_split
 *
 *  La extensión mbstring tiene su PROPIO motor de expresiones regulares, pensado para textos
 *  multibyte. Diferencias importantes con preg_:
 *    - los patrones se escriben SIN delimitadores ni modificadores: "^\d+$" en lugar de '/^\d+$/'
 *    - las opciones (i, x, m...) se pasan como parámetro aparte o con mb_regex_set_options
 *    - trabajan directamente con la codificación de mb_regex_encoding (UTF-8)
 *  Hoy se usan mucho menos que preg_ (que con /u también maneja UTF-8), pero aparecen en código
 *  existente y en el cheatsheet.
 *
 *  Ejecuta en modo depuración paso a paso (F10).
 */
require_once __DIR__ . '/utilidades.php';

// ---------------------------------------------------------------------------------------------
// mb_regex_encoding — consulta o establece la codificación de las funciones mb_ereg
// ENLACE: https://www.php.net/manual/es/function.mb-regex-encoding.php
// ENUNCIADO EJEMPLO: comprobar que trabajamos en UTF-8 antes de usar el resto de funciones.
// ---------------------------------------------------------------------------------------------
titulo('mb_regex_encoding');
$codificacion  = mb_regex_encoding();          // "UTF-8"
mb_regex_encoding("UTF-8");                    // la fijamos explícitamente
ver('codificación', $codificacion);

// ---------------------------------------------------------------------------------------------
// mb_ereg — busca un patrón; devuelve true / false y rellena el array de coincidencias
// ENLACE: https://www.php.net/manual/es/function.mb-ereg.php
// ENUNCIADO EJEMPLO: obtener el código postal y la ciudad de una dirección con tildes y eñes.
// NOTA: desde PHP 8 devuelve un bool (antes devolvía la longitud). Admite grupos con nombre.
// ---------------------------------------------------------------------------------------------
titulo('mb_ereg');
$direccion     = "Rúa da Peña 3, 36380 Gondomar";
$encontrado    = mb_ereg("(\d{5}) (\w+)$", $direccion, $m);
ver('¿encontrado?', $encontrado);              // true
ver('$m', $m);                                 // [0 => "36380 Gondomar", 1 => "36380", 2 => "Gondomar"]

mb_ereg("^(?<via>\w+) (?:da |do |de )?(?<nombre>\w+)", $direccion, $m);
ver('vía y nombre', [$m['via'], $m['nombre']]);    // ["Rúa", "Peña"] (\w incluye ú y ñ)

// ---------------------------------------------------------------------------------------------
// mb_eregi — como mb_ereg pero SIN distinguir mayúsculas (también en letras con tilde)
// ENLACE: https://www.php.net/manual/es/function.mb-eregi.php
// ENUNCIADO EJEMPLO: buscar la palabra "ÁRBOL" en un texto donde aparece en minúsculas.
// ---------------------------------------------------------------------------------------------
titulo('mb_eregi');
ver('mb_ereg', mb_ereg("ÁRBOL", "un árbol grande"));     // false
ver('mb_eregi', mb_eregi("ÁRBOL", "un árbol grande"));   // true

// ---------------------------------------------------------------------------------------------
// mb_ereg_match — ¿coincide el patrón desde el PRINCIPIO del texto?
// ENLACE: https://www.php.net/manual/es/function.mb-ereg-match.php
// ENUNCIADO EJEMPLO: validar un nombre que solo tenga letras (con tildes) y espacios.
// NOTA: está anclado al principio, pero NO al final: añade $ para validar el texto completo.
//   El tercer parámetro son las opciones ("i" = sin distinguir mayúsculas...).
// ---------------------------------------------------------------------------------------------
titulo('mb_ereg_match');
ver('"María Núñez"', mb_ereg_match("[[:alpha:] ]+$", "María Núñez"));   // true
ver('"María 2"', mb_ereg_match("[[:alpha:] ]+$", "María 2"));           // false
ver('sin $: "María 2"', mb_ereg_match("[[:alpha:] ]+", "María 2"));     // true (solo mira el principio)
ver('no empieza así', mb_ereg_match("Núñez", "María Núñez"));           // false

// ---------------------------------------------------------------------------------------------
// mb_ereg_replace / mb_eregi_replace — reemplazar (distinguiendo / sin distinguir mayúsculas)
// ENLACE: https://www.php.net/manual/es/function.mb-ereg-replace.php
// ENLACE: https://www.php.net/manual/es/function.mb-eregi-replace.php
// ENUNCIADO EJEMPLO: quitar los signos de puntuación de un texto en español, convertir fechas al
//   formato español (con \1, \2... en el reemplazo) y censurar una palabra sin distinguir mayúsculas.
// NOTA: en el reemplazo los grupos se escriben \1, \2 (no $1).
// ---------------------------------------------------------------------------------------------
titulo('mb_ereg_replace / mb_eregi_replace');
$frase         = "¡Hola! ¿Qué tal, Begoña?";
ver('sin puntuación', mb_ereg_replace("[[:punct:]¡¿]", "", $frase));                 // "Hola Qué tal Begoña"
ver('fecha', mb_ereg_replace("(\d{4})-(\d{2})-(\d{2})", "\\3/\\2/\\1", "Entrega: 2026-12-01"));
ver('censura', mb_eregi_replace("cañón", "****", "Un CAÑÓN y un cañón"));             // "Un **** y un ****"

// ---------------------------------------------------------------------------------------------
// mb_ereg_replace_callback — el reemplazo lo calcula una función
// ENLACE: https://www.php.net/manual/es/function.mb-ereg-replace-callback.php
// ENUNCIADO EJEMPLO: poner en mayúscula la primera letra de cada palabra de un texto en gallego,
//   incluidas las que empiezan por tilde ("ánxela" -> "Ánxela").
// ---------------------------------------------------------------------------------------------
titulo('mb_ereg_replace_callback');
$texto         = "ánxela e óscar viven en ourense";
$capitalizado  = mb_ereg_replace_callback("\b(\w)(\w*)", fn($m) => mb_strtoupper($m[1]) . $m[2], $texto);
ver('capitalizado', $capitalizado);          // "Ánxela E Óscar Viven En Ourense"

// ---------------------------------------------------------------------------------------------
// mb_split — divide un texto multibyte usando una regex como separador
// ENLACE: https://www.php.net/manual/es/function.mb-split.php
// ENUNCIADO EJEMPLO: separar las palabras de una frase en español con signos ¡ ¿ , . y espacios.
// NOTA: no tiene la opción de quitar los trozos vacíos: se puede usar array_filter.
// ---------------------------------------------------------------------------------------------
titulo('mb_split');
$trozos        = mb_split("[\s,.¡!¿?]+", "¡Buenos días, Begoña! ¿Cómo estás?");
ver('mb_split', $trozos);                                     // ["", "Buenos", "días", "Begoña", "Cómo", "estás", ""]
ver('sin vacíos', array_values(array_filter($trozos)));

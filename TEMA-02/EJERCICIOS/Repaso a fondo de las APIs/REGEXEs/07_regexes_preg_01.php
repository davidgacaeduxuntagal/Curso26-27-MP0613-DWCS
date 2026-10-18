<?php
/*
 * 07_regexes_preg_01.php
 *  REGEXES - API PCRE (1): buscar, dividir, filtrar y controlar errores
 *  Funciones: preg_match, preg_match_all, preg_split, preg_grep, preg_quote, preg_last_error,
 *             preg_last_error_msg
 *
 *  Las funciones preg_ (Perl Compatible Regular Expressions) son la API principal de PHP para
 *  expresiones regulares. Recuerda usar el modificador u con textos en UTF-8.
 *
 *  Ejecuta en modo depuración (F10) y despliega en el panel VARIABLES los arrays de resultados:
 *  la ESTRUCTURA de esos arrays es lo más importante de este fichero.
 */
require_once __DIR__ . '/utilidades.php';

// ---------------------------------------------------------------------------------------------
// preg_match — busca la PRIMERA coincidencia. Devuelve 1, 0 o false (error en el patrón)
// ENLACE: https://www.php.net/manual/es/function.preg-match.php
// ENUNCIADO EJEMPLO: obtener el número de pedido de un asunto de correo, y su POSICIÓN en el
//   texto con PREG_OFFSET_CAPTURE.
// NOTA: parámetros: (patrón, texto, &coincidencias, flags, desplazamiento). Con
//   PREG_OFFSET_CAPTURE cada elemento es [texto, posición en BYTES]. El quinto parámetro indica
//   desde qué byte empezar a buscar.
// ---------------------------------------------------------------------------------------------
titulo('preg_match');
$asunto        = "Re: Pedido #F2026-0037 enviado";
$encontrado    = preg_match('/#(F\d{4}-\d{4})/', $asunto, $m);
ver('¿encontrado?', $encontrado);           // 1
ver('número de pedido', $m[1]);             // "F2026-0037"

preg_match('/#(F\d{4}-\d{4})/', $asunto, $conPosicion, PREG_OFFSET_CAPTURE);
ver('con PREG_OFFSET_CAPTURE', $conPosicion);   // [[ "#F2026-0037", 11 ], [ "F2026-0037", 12 ]]

$desde         = preg_match('/\d+/', "abc 12 def 345", $m, 0, 7);   // empieza a buscar en el byte 7
ver('buscando desde la posición 7', $m[0]);   // "345"

// ---------------------------------------------------------------------------------------------
// preg_match_all — busca TODAS las coincidencias. Devuelve cuántas ha encontrado
// ENLACE: https://www.php.net/manual/es/function.preg-match-all.php
// ENUNCIADO EJEMPLO: extraer todos los hashtags de un tuit; y todos los precios de un ticket con
//   su producto, organizados de dos formas.
// NOTA: el formato del array depende del flag:
//   PREG_PATTERN_ORDER (por defecto): $m[0] = todas las coincidencias completas, $m[1] = todos los
//                                     grupos 1, $m[2] = todos los grupos 2...
//   PREG_SET_ORDER: $m[0] = primera coincidencia con sus grupos, $m[1] = segunda... (suele ser
//                   más cómodo para recorrer con foreach)
// ---------------------------------------------------------------------------------------------
titulo('preg_match_all');
$tuit          = "Aprendiendo #PHP y #MySQL en el #IESRodeira";
$total         = preg_match_all('/#(\w+)/', $tuit, $m);
ver('número de hashtags', $total);      // 3
ver('hashtags', $m[1]);                 // ["PHP", "MySQL", "IESRodeira"]

$ticket        = "Pan 1,20\nLeche 0,95\nCafé molido 4,50";
preg_match_all('/^(?<producto>.+) (?<precio>\d+,\d{2})$/mu', $ticket, $porPatron);
preg_match_all('/^(?<producto>.+) (?<precio>\d+,\d{2})$/mu', $ticket, $porLinea, PREG_SET_ORDER);
ver('PREG_PATTERN_ORDER: productos', $porPatron['producto']);
ver('PREG_PATTERN_ORDER: precios', $porPatron['precio']);
foreach ($porLinea as $linea) {
    ver('PREG_SET_ORDER', "{$linea['producto']} -> {$linea['precio']}");
}

// ---------------------------------------------------------------------------------------------
// preg_split — divide un texto usando una regex como separador (un explode "potente")
// ENLACE: https://www.php.net/manual/es/function.preg-split.php
// ENUNCIADO EJEMPLO: separar las palabras de una frase aunque haya varios espacios, comas o
//   puntos seguidos; separar etiquetas escritas con comas o punto y coma.
// NOTA: flags útiles: PREG_SPLIT_NO_EMPTY (quita los trozos vacíos), PREG_SPLIT_DELIM_CAPTURE
//   (incluye también los separadores si están entre paréntesis).
// ---------------------------------------------------------------------------------------------
titulo('preg_split');
$frase         = "Hola,   ¿qué tal?  Bien.";
$conExplode    = explode(" ", $frase);                                   // trozos vacíos
$palabras      = preg_split('/[\s,.¿?]+/u', $frase, -1, PREG_SPLIT_NO_EMPTY);
ver('explode (mal)', $conExplode);
ver('preg_split', $palabras);                                            // ["Hola", "qué", "tal", "Bien"]

$operacion     = "12+5*3-4";
$conOperadores = preg_split('/([+*\/-])/', $operacion, -1, PREG_SPLIT_DELIM_CAPTURE);
ver('con PREG_SPLIT_DELIM_CAPTURE', $conOperadores);                     // ["12","+","5","*","3","-","4"]

// ---------------------------------------------------------------------------------------------
// preg_grep — devuelve los elementos de un ARRAY que coinciden con el patrón
// ENLACE: https://www.php.net/manual/es/function.preg-grep.php
// ENUNCIADO EJEMPLO: de una lista de ficheros, quedarse con las imágenes; y con PREG_GREP_INVERT,
//   con los que NO son imágenes.
// NOTA: conserva las claves del array original.
// ---------------------------------------------------------------------------------------------
titulo('preg_grep');
$ficheros      = ["foto.jpg", "notas.txt", "logo.PNG", "informe.pdf", "banner.gif"];
$imagenes      = preg_grep('/\.(jpe?g|png|gif)$/i', $ficheros);                     // claves 0, 2, 4
$noImagenes    = preg_grep('/\.(jpe?g|png|gif)$/i', $ficheros, PREG_GREP_INVERT);   // claves 1, 3
ver('imágenes', $imagenes);
ver('no imágenes', $noImagenes);

// ---------------------------------------------------------------------------------------------
// preg_quote — escapa los caracteres especiales de un texto para usarlo DENTRO de un patrón
// ENLACE: https://www.php.net/manual/es/function.preg-quote.php
// ENUNCIADO EJEMPLO: un buscador busca exactamente lo que escribe el usuario. Si escribe
//   "1+1=2?" y lo metemos tal cual en el patrón, el + y el ? se interpretan como cuantificadores.
// NOTA: el segundo parámetro es el DELIMITADOR que usas, para que también lo escape.
//   ¡Nunca metas texto del usuario en un patrón sin preg_quote!
// ---------------------------------------------------------------------------------------------
titulo('preg_quote');
$busqueda      = "1+1=2?";
$texto         = "En matemáticas, 1+1=2? Sí.";
$sinQuote      = preg_match('/' . $busqueda . '/', $texto);                    // 0
$conQuote      = preg_match('/' . preg_quote($busqueda, '/') . '/', $texto);   // 1
ver('preg_quote', preg_quote($busqueda, '/'));     // "1\+1\=2\?"
ver('sin preg_quote', $sinQuote);
ver('con preg_quote', $conQuote);

// ---------------------------------------------------------------------------------------------
// preg_last_error / preg_last_error_msg — código y mensaje del último error de una función preg_
// ENLACE: https://www.php.net/manual/es/function.preg-last-error.php
// ENLACE: https://www.php.net/manual/es/function.preg-last-error-msg.php
// ENUNCIADO EJEMPLO: (1) con /u y un texto que NO es UTF-8 válido, preg_match devuelve false;
//   (2) un patrón "catastrófico" (cuantificadores anidados) agota el límite de retroceso
//   (backtracking) de PCRE.
// NOTA: un patrón mal escrito (por ejemplo, un paréntesis sin cerrar) produce además un Warning.
//   Desde PHP 8: preg_last_error_msg() devuelve el texto del error ("No error" si no hay error).
// ---------------------------------------------------------------------------------------------
titulo('preg_last_error / preg_last_error_msg');
$latin1        = mb_convert_encoding("Peña", "ISO-8859-1", "UTF-8");   // no es UTF-8 válido
$resultado     = preg_match('/ñ/u', $latin1);
ver('preg_match con UTF-8 inválido', $resultado);                       // false
ver('preg_last_error', preg_last_error() === PREG_BAD_UTF8_ERROR ? "PREG_BAD_UTF8_ERROR" : preg_last_error());
ver('preg_last_error_msg', preg_last_error_msg());                       // "Malformed UTF-8 characters..."

// (\d+)+ se puede repartir de muchísimas formas: al llegar a la "x" PCRE prueba todas las
// combinaciones posibles (2^30) hasta agotar el límite pcre.backtrack_limit de php.ini
$catastrofico  = preg_match('/^(\d+)+$/', str_repeat("1", 30) . "x");
ver('patrón catastrófico', $catastrofico);                               // false
ver('error', preg_last_error_msg());                                     // "Backtrack limit exhausted"

preg_match('/ok/', "todo ok");
ver('tras una búsqueda correcta', preg_last_error_msg());                // "No error"

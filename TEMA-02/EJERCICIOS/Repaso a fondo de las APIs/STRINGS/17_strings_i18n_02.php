<?php
/*
 * 17_strings_i18n_02.php
 *  STRINGS - I18N (2): funciones "multibyte" (mb_) para textos con tildes, eñes y emojis
 *  Funciones: mb_strlen, mb_substr, mb_strtoupper, mb_strtolower, mb_convert_case,
 *             mb_str_split, mb_strwidth, mb_strimwidth
 *
 *  Las funciones clásicas (strlen, substr, strtoupper...) trabajan con BYTES. En UTF-8 un
 *  carácter puede ocupar de 1 a 4 bytes ("a" = 1, "ñ" = 2, "€" = 3, "😀" = 4), así que con
 *  textos en español fallan. Las funciones mb_ (extensión mbstring) trabajan con CARACTERES.
 *
 *  Ejecuta en modo depuración paso a paso (F10) y compara cada pareja función clásica / función mb_.
 */
require_once __DIR__ . '/utilidades.php';

// ---------------------------------------------------------------------------------------------
// mb_strlen — longitud en CARACTERES
// ENLACE: https://www.php.net/manual/es/function.mb-strlen.php
// ENUNCIADO EJEMPLO: un formulario limita la biografía a 20 caracteres. Con strlen, un texto
//   con tildes y un emoji parecería más largo de lo que es.
// ---------------------------------------------------------------------------------------------
titulo('mb_strlen');
$bio           = "Diseñadora 😀 en Vigo";
$bytes         = strlen($bio);              // 24 bytes
$caracteres    = mb_strlen($bio);           // 20 caracteres
ver('strlen', $bytes);
ver('mb_strlen', $caracteres);
ver('¿cabe en 20 caracteres?', $caracteres <= 20);   // true

// ---------------------------------------------------------------------------------------------
// mb_substr — extrae una parte contando CARACTERES
// ENLACE: https://www.php.net/manual/es/function.mb-substr.php
// ENUNCIADO EJEMPLO: obtener las iniciales de un nombre que empieza por "Ángel" y los primeros
//   caracteres de una ciudad con eñe.
// NOTA: substr puede cortar un carácter por la mitad y dejar un byte suelto (carácter inválido).
// ---------------------------------------------------------------------------------------------
titulo('mb_substr');
$nombre        = "Ángel Núñez";
$inicialMal    = substr($nombre, 0, 1);          // medio carácter: byte inválido
$inicialBien   = mb_substr($nombre, 0, 1);       // "Á"
ver('substr (mal)', $inicialMal);
ver('mb_substr', $inicialBien);

$ciudad        = "A Coruña";
ver('mb_substr($ciudad, 0, 7)', mb_substr($ciudad, 0, 7));   // "A Coruñ"
ver('mb_substr($ciudad, -3)', mb_substr($ciudad, -3));       // "uña"

// ---------------------------------------------------------------------------------------------
// mb_strtoupper / mb_strtolower — mayúsculas y minúsculas de TODAS las letras
// ENLACE: https://www.php.net/manual/es/function.mb-strtoupper.php
// ENLACE: https://www.php.net/manual/es/function.mb-strtolower.php
// ENUNCIADO EJEMPLO: mostrar el título de una noticia en mayúsculas y comparar dos nombres sin
//   tener en cuenta mayúsculas ("ÁLVAREZ" y "álvarez").
// ---------------------------------------------------------------------------------------------
titulo('mb_strtoupper / mb_strtolower');
$titulo        = "La campeona gallega de atletismo";
ver('strtoupper("ñandú")', strtoupper("ñandú"));         // "ñANDú" (no cambia ñ ni ú)
ver('mb_strtoupper("ñandú")', mb_strtoupper("ñandú"));   // "ÑANDÚ"
ver('titular', mb_strtoupper($titulo));

$iguales       = mb_strtolower("ÁLVAREZ") === mb_strtolower("álvarez");   // true
ver('¿mismo apellido?', $iguales);

// ---------------------------------------------------------------------------------------------
// mb_convert_case — cambia mayúsculas / minúsculas con varios modos
// ENLACE: https://www.php.net/manual/es/function.mb-convert-case.php
// ENUNCIADO EJEMPLO: normalizar el nombre y apellidos que escribe un usuario TODO EN MAYÚSCULAS
//   para mostrarlo con la inicial de cada palabra en mayúscula (lo que ucwords no hace bien con tildes).
// NOTA: modos: MB_CASE_UPPER, MB_CASE_LOWER, MB_CASE_TITLE (inicial de cada palabra).
// ---------------------------------------------------------------------------------------------
titulo('mb_convert_case');
$escrito       = "MARÍA ÁLVAREZ NÚÑEZ";
$conUcwords    = ucwords(strtolower($escrito));                  // "MarÍa Álvarez NÚÑez" (mal: í, ú, ñ no cambian)
$conMb         = mb_convert_case($escrito, MB_CASE_TITLE);       // "María Álvarez Núñez"
ver('ucwords(strtolower()) (mal)', $conUcwords);
ver('mb_convert_case TITLE', $conMb);

// ---------------------------------------------------------------------------------------------
// mb_str_split — divide en CARACTERES (o en trozos de N caracteres)
// ENLACE: https://www.php.net/manual/es/function.mb-str-split.php
// ENUNCIADO EJEMPLO: obtener las letras de una palabra con eñe para un juego del ahorcado, e
//   invertir correctamente una palabra (strrev la estropea).
// ---------------------------------------------------------------------------------------------
titulo('mb_str_split');
$palabra       = "cañón";
$letrasMal     = str_split($palabra);              // 7 trozos: la ñ y la ó partidas
$letras        = mb_str_split($palabra);           // ["c", "a", "ñ", "ó", "n"]
ver('str_split (mal)', count($letrasMal));
ver('mb_str_split', $letras);

$invertida     = implode("", array_reverse($letras));   // "nóñac"
ver('palabra invertida', $invertida);

// ---------------------------------------------------------------------------------------------
// mb_strwidth — ANCHO con el que se ve un texto en pantalla (en columnas de texto)
// ENLACE: https://www.php.net/manual/es/function.mb-strwidth.php
// ENUNCIADO EJEMPLO: alinear columnas en la terminal. Los caracteres chinos/japoneses y muchos
//   emojis ocupan 2 columnas, aunque sean 1 carácter.
// ---------------------------------------------------------------------------------------------
titulo('mb_strwidth');
$textos        = ["Vigo", "Peña", "東京", "😀"];
foreach ($textos as $t) {
    ver("'$t' -> bytes / caracteres / ancho", [strlen($t), mb_strlen($t), mb_strwidth($t)]);
}

// ---------------------------------------------------------------------------------------------
// mb_strimwidth — recorta un texto a un ANCHO, añadiendo una marca de recorte ("...")
// ENLACE: https://www.php.net/manual/es/function.mb-strimwidth.php
// ENUNCIADO EJEMPLO: mostrar en un listado de noticias solo los primeros 25 caracteres del
//   titular seguidos de "...", sin cortar caracteres por la mitad.
// NOTA: parámetros: (texto, inicio, ancho total, marca). El ancho INCLUYE la marca.
// ---------------------------------------------------------------------------------------------
titulo('mb_strimwidth');
$titular       = "Últimas noticias: el Celta gana en Balaídos con dos goles de Aspas";
$recortado     = mb_strimwidth($titular, 0, 25, "...");   // "Últimas noticias: el C..."
ver('mb_strimwidth', $recortado);
ver('ancho del resultado', mb_strwidth($recortado));      // 25

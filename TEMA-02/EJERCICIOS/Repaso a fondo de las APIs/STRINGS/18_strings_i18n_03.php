<?php
/*
 * 18_strings_i18n_03.php
 *  STRINGS - I18N (3): buscar en textos multibyte y trabajar con códigos Unicode
 *  Funciones: mb_internal_encoding, mb_strpos, mb_stripos, mb_strrpos, mb_stristr, mb_strrchr,
 *             mb_ord, mb_chr
 *
 *  Son las versiones "multibyte" de las funciones de búsqueda de 07 y 08: devuelven posiciones
 *  en CARACTERES (no en bytes) y no distinguen mayúsculas también en letras con tilde.
 *
 *  Ejecuta en modo depuración paso a paso (F10).
 */
require_once __DIR__ . '/utilidades.php';

// ---------------------------------------------------------------------------------------------
// mb_internal_encoding — consulta o establece la codificación por defecto de las funciones mb_
// ENLACE: https://www.php.net/manual/es/function.mb-internal-encoding.php
// ENUNCIADO EJEMPLO: comprobar que las funciones mb_ trabajan en UTF-8 (el valor por defecto
//   desde PHP 5.6, tomado de la directiva default_charset de php.ini).
// NOTA: todas las funciones mb_ aceptan además un último parámetro opcional con la codificación.
// ---------------------------------------------------------------------------------------------
titulo('mb_internal_encoding');
$codificacion  = mb_internal_encoding();          // "UTF-8"
ver('codificación interna', $codificacion);

// ---------------------------------------------------------------------------------------------
// mb_strpos — posición (en CARACTERES) de la primera aparición
// ENLACE: https://www.php.net/manual/es/function.mb-strpos.php
// ENUNCIADO EJEMPLO: en un SMS con tildes, saber en qué carácter empieza la palabra "código"
//   para resaltarla. strpos daría la posición en bytes, que no coincide.
// NOTA: igual que strpos, devuelve false si no lo encuentra: compara con !== false.
// ---------------------------------------------------------------------------------------------
titulo('mb_strpos');
$sms           = "Tu pedido llegará mañana. Tu código es 4821";
$enBytes       = strpos($sms, "código");        // 31 (bytes: "á" y "ñ" cuentan 2)
$enCaracteres  = mb_strpos($sms, "código");     // 29 (caracteres)
ver('strpos (bytes)', $enBytes);
ver('mb_strpos (caracteres)', $enCaracteres);
ver('a partir de esa posición', mb_substr($sms, $enCaracteres));

// ---------------------------------------------------------------------------------------------
// mb_stripos — primera aparición SIN distinguir mayúsculas (también en letras con tilde)
// ENLACE: https://www.php.net/manual/es/function.mb-stripos.php
// ENUNCIADO EJEMPLO: buscar la palabra "árbol" en un texto donde aparece como "ÁRBOL".
// ---------------------------------------------------------------------------------------------
titulo('mb_stripos');
$texto         = "PLANTA UN ÁRBOL EN TU BARRIO";
$conStripos    = stripos($texto, "árbol");      // false: stripos no iguala "á" con "Á"
$conMb         = mb_stripos($texto, "árbol");   // 10
ver('stripos', $conStripos);
ver('mb_stripos', $conMb);

// ---------------------------------------------------------------------------------------------
// mb_strrpos — posición (en caracteres) de la ÚLTIMA aparición
// ENLACE: https://www.php.net/manual/es/function.mb-strrpos.php
// ENUNCIADO EJEMPLO: obtener el último apellido de un nombre completo con tildes (lo que hay
//   después del último espacio).
// ---------------------------------------------------------------------------------------------
titulo('mb_strrpos');
$completo      = "José Ramón Núñez Ibáñez";
$ultimoEspacio = mb_strrpos($completo, " ");               // 16
$ultimo        = mb_substr($completo, $ultimoEspacio + 1); // "Ibáñez"
ver('mb_strrpos(" ")', $ultimoEspacio);
ver('último apellido', $ultimo);

// ---------------------------------------------------------------------------------------------
// mb_stristr — texto desde la primera aparición, sin distinguir mayúsculas (versión mb_ de stristr)
// ENLACE: https://www.php.net/manual/es/function.mb-stristr.php
// ENUNCIADO EJEMPLO: de una dirección "C/ Peñasco 3, ÁTICO B" obtener la parte desde "ático",
//   y con el 3er parámetro a true lo que hay antes.
// ---------------------------------------------------------------------------------------------
titulo('mb_stristr');
$direccion     = "C/ Peñasco 3, ÁTICO B";
$desde         = mb_stristr($direccion, "ático");         // "ÁTICO B"
$antes         = mb_stristr($direccion, "ático", true);   // "C/ Peñasco 3, "
ver('mb_stristr', $desde);
ver('mb_stristr(..., true)', $antes);

// ---------------------------------------------------------------------------------------------
// mb_strrchr — texto desde la ÚLTIMA aparición (versión mb_ de strrchr)
// ENLACE: https://www.php.net/manual/es/function.mb-strrchr.php
// ENUNCIADO EJEMPLO: obtener la extensión de un fichero cuyo nombre tiene eñes y tildes.
// NOTA: a diferencia de strrchr, la "aguja" puede tener varios caracteres.
// ---------------------------------------------------------------------------------------------
titulo('mb_strrchr');
$fichero       = "Diseño_Señalización.v2.pdf";
$extension     = mb_strrchr($fichero, ".");               // ".pdf"
ver('mb_strrchr', $extension);

// ---------------------------------------------------------------------------------------------
// mb_ord / mb_chr — código Unicode de un carácter, y carácter a partir de su código
// ENLACE: https://www.php.net/manual/es/function.mb-ord.php
// ENLACE: https://www.php.net/manual/es/function.mb-chr.php
// ENUNCIADO EJEMPLO: ver el código Unicode de la "ñ" y del "€" (ord solo da el primer byte), y
//   generar las letras del alfabeto griego para numerar apartados (α, β, γ...).
// NOTA: los códigos Unicode se suelen escribir en hexadecimal: U+00F1 es la "ñ".
// ---------------------------------------------------------------------------------------------
titulo('mb_ord / mb_chr');
$codigoEnie    = mb_ord("ñ");                         // 241 (U+00F1)
$codigoEuro    = mb_ord("€");                         // 8364 (U+20AC)
ver('ord("ñ") (primer byte)', ord("ñ"));             // 195
ver('mb_ord("ñ")', $codigoEnie);
ver('mb_ord("€") en hexadecimal', sprintf("U+%04X", $codigoEuro));

$griegas       = [];
for ($codigo = 0x3B1; $codigo < 0x3B1 + 5; $codigo++) {   // 0x3B1 = α
    $griegas[] = mb_chr($codigo);
}
ver('letras griegas', implode(" ", $griegas));        // "α β γ δ ε"

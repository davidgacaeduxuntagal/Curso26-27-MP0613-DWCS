<?php
/*
 * 02_regexes_basicas_02.php
 *  REGEXES - BÁSICAS (2): cuantificadores, alternativas y límites de palabra
 *  Contenidos: * + ? {n} {n,} {n,m}, cuantificadores codiciosos y perezosos, alternancia |,
 *              límites de palabra \b \B, modificador m (multilínea)
 *
 *  Un CUANTIFICADOR indica cuántas veces se repite el elemento ANTERIOR (un carácter, una clase o
 *  un grupo entre paréntesis):
 *      x*      0 o más veces            x{3}    exactamente 3 veces
 *      x+      1 o más veces            x{2,}   2 o más veces
 *      x?      0 o 1 vez (opcional)     x{2,4}  entre 2 y 4 veces
 *
 *  Para VER qué parte del texto coincide, usamos el tercer parámetro de preg_match: un array donde
 *  $coincidencia[0] es el texto encontrado. Míralo en el panel VARIABLES en cada paso (F10).
 */
require_once __DIR__ . '/utilidades.php';

// ---------------------------------------------------------------------------------------------
// + (una o más) y * (cero o más)
// ENLACE: https://www.php.net/manual/es/regexp.reference.repetition.php
// ENUNCIADO EJEMPLO: encontrar el primer número (de cualquier longitud) de una dirección, y ver
//   la diferencia entre + y * con la palabra "gooool".
// ---------------------------------------------------------------------------------------------
titulo('+ y *');
preg_match('/\d+/', "Calle Mayor 125, 2º B", $coincidencia);
ver('/\d+/', $coincidencia[0]);                         // "125"

$conMas        = preg_match('/go+l/', "gl");            // 0: necesita al menos una o
$conAsterisco  = preg_match('/go*l/', "gl");            // 1: la o puede no estar
preg_match('/go+l/', "¡gooool!", $gol);
ver('/go+l/ en "gl"', $conMas);
ver('/go*l/ en "gl"', $conAsterisco);
ver('/go+l/ en "¡gooool!"', $gol[0]);                   // "gooool"

// ---------------------------------------------------------------------------------------------
// ? (opcional)
// ENUNCIADO EJEMPLO: aceptar "color" y "colour", y un teléfono con o sin el prefijo "+34 ".
// ---------------------------------------------------------------------------------------------
titulo('? opcional');
$palabras      = [];
foreach (["color", "colour", "colouur"] as $p) {
    $palabras[$p] = preg_match('/^colou?r$/', $p);       // 1, 1, 0
}
ver('/^colou?r$/', $palabras);

$conPrefijo    = preg_match('/^(\+34 )?\d{9}$/', "+34 612345678");   // 1 (el grupo entero es opcional)
$sinPrefijo    = preg_match('/^(\+34 )?\d{9}$/', "612345678");       // 1
ver('teléfono con prefijo', $conPrefijo);
ver('teléfono sin prefijo', $sinPrefijo);

// ---------------------------------------------------------------------------------------------
// {n}, {n,}, {n,m}
// ENUNCIADO EJEMPLO: un código postal tiene exactamente 5 dígitos; un nombre de usuario entre 4 y
//   12 caracteres de palabra; una contraseña al menos 8 caracteres.
// ---------------------------------------------------------------------------------------------
titulo('{n} {n,} {n,m}');
ver('código postal 36380', preg_match('/^\d{5}$/', "36380"));          // 1
ver('código postal 3638', preg_match('/^\d{5}$/', "3638"));            // 0
ver('usuario "ana_2026"', preg_match('/^\w{4,12}$/', "ana_2026"));    // 1
ver('usuario "an"', preg_match('/^\w{4,12}$/', "an"));                // 0
ver('contraseña de 8 o más', preg_match('/^.{8,}$/', "clave123"));    // 1

// ---------------------------------------------------------------------------------------------
// CODICIOSOS frente a PEREZOSOS (lazy)
//   Por defecto los cuantificadores son CODICIOSOS: cogen todo lo que pueden.
//   Con ? detrás (*?, +?, ??, {n,m}?) son PEREZOSOS: cogen lo mínimo posible.
// ENUNCIADO EJEMPLO: extraer la primera etiqueta HTML de un texto. El patrón codicioso se "come"
//   desde el primer < hasta el ÚLTIMO >; el perezoso (con +? en lugar de +) se detiene en el primer >.
// NOTA: ¡cuidado al escribir patrones con "? >" (sin espacio) en un comentario de una línea!: PHP lo
//   toma como el cierre de la etiqueta PHP. Por eso aquí no se escribe el patrón en el comentario.
// ---------------------------------------------------------------------------------------------
titulo('codicioso y perezoso');
$html          = "<b>Hola</b> y <i>adiós</i>";
preg_match('/<.+>/', $html, $codicioso);
preg_match('/<.+?>/', $html, $perezoso);
ver('codicioso /<.+>/', $codicioso[0]);       // "<b>Hola</b> y <i>adiós</i>"
ver('perezoso /<.+?>/', $perezoso[0]);        // "<b>"

// ---------------------------------------------------------------------------------------------
// ALTERNANCIA | — una cosa u otra
// ENLACE: https://www.php.net/manual/es/regexp.reference.alternation.php
// ENUNCIADO EJEMPLO: comprobar que la extensión de un fichero es jpg, jpeg, png o gif.
// NOTA: | tiene la prioridad más baja: '/^jpg|png$/' significa "empieza por jpg" O "termina en
//   png". Para limitar las alternativas se usan paréntesis: '/^(jpg|png)$/'.
// ---------------------------------------------------------------------------------------------
titulo('alternancia |');
$ficheros      = ["foto.jpg", "foto.jpeg", "logo.PNG", "doc.pdf"];
$imagenes      = [];
foreach ($ficheros as $f) {
    $imagenes[$f] = preg_match('/\.(jpe?g|png|gif)$/i', $f);   // 1, 1, 1, 0
}
ver('¿imagen?', $imagenes);

$sinParentesis = preg_match('/^jpg|png$/', "jpg.exe");         // 1 (¡empieza por jpg!)
ver('/^jpg|png$/ con "jpg.exe" (error típico)', $sinParentesis);

// ---------------------------------------------------------------------------------------------
// LÍMITES DE PALABRA \b y \B
//   \b  posición entre un carácter de palabra (\w) y otro que no lo es (o el principio / final)
//   \B  posición que NO es límite de palabra
// ENUNCIADO EJEMPLO: buscar la palabra "sol" sola, sin encontrarla dentro de "soldado" o "girasol".
// NOTA: \b usa \w, que sin /u no incluye tildes ni ñ.
// ---------------------------------------------------------------------------------------------
titulo('\b y \B');
$frases        = ["Hace sol", "El soldado", "Un girasol"];
$conB          = [];
foreach ($frases as $f) {
    $conB[$f] = preg_match('/\bsol\b/', $f);           // 1, 0, 0
}
ver('/\bsol\b/', $conB);
ver('/\Bsol/ (sol dentro de una palabra)', preg_match('/\Bsol/', "Un girasol"));   // 1

// ---------------------------------------------------------------------------------------------
// MODIFICADOR m (multilínea) — ^ y $ pasan a ser principio y final de CADA LÍNEA
// ENUNCIADO EJEMPLO: en un texto de varias líneas, saber si alguna línea empieza por "ERROR".
// ---------------------------------------------------------------------------------------------
titulo('modificador m');
$log           = "INFO arrancando\nERROR sin conexión\nINFO reintentando";
$sinM          = preg_match('/^ERROR/', $log);        // 0: ^ es el principio del texto entero
$conM          = preg_match('/^ERROR/m', $log);       // 1: ^ es el principio de cualquier línea
ver('sin /m', $sinM);
ver('con /m', $conM);

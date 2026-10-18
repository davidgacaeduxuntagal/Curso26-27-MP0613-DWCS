<?php
/*
 * 01_regexes_basicas_01.php
 *  REGEXES - BÁSICAS (1): primeros pasos con expresiones regulares
 *  Contenidos: delimitadores y preg_match, literales, metacaracteres y escape, el punto, clases de
 *              caracteres [ ], rangos, negación [^ ], clases abreviadas \d \w \s, anclas ^ $,
 *              modificadores i, u, x
 *
 *  Una EXPRESIÓN REGULAR (regex) es un PATRÓN que describe un conjunto de textos. En PHP se escribe
 *  como un string con DELIMITADORES (normalmente /.../) y, detrás, los MODIFICADORES: '/patrón/i'
 *
 *  En estos ficheros BÁSICOS solo usamos preg_match($patron, $texto), que devuelve:
 *      1     si el patrón aparece en el texto
 *      0     si no aparece
 *      false si el patrón está mal escrito
 *  (la estudiaremos a fondo en 07_regexes_preg_01.php)
 *
 *  CONSEJO: escribe los patrones entre COMILLAS SIMPLES. Con comillas dobles PHP interpreta antes
 *  las barras invertidas (\n, \d...) y es fácil equivocarse.
 *
 *  Prueba también tus patrones en https://regex101.com (elige el "flavor" PCRE2 / PHP).
 *  Ejecuta en modo depuración paso a paso (F10).
 */
require_once __DIR__ . '/utilidades.php';

// ---------------------------------------------------------------------------------------------
// LITERALES — los caracteres normales se buscan tal cual (distinguiendo mayúsculas)
// ENLACE: https://www.php.net/manual/es/regexp.introduction.php
// ENUNCIADO EJEMPLO: comprobar si un comentario contiene la palabra "gratis".
// NOTA: el patrón puede aparecer en CUALQUIER parte del texto (no tiene que ocupar todo el texto).
// ---------------------------------------------------------------------------------------------
titulo('literales');
$comentario    = "Descarga gratis el programa";
$aparece       = preg_match('/gratis/', $comentario);         // 1
$mayusculas    = preg_match('/GRATIS/', $comentario);         // 0 (distingue mayúsculas)
ver("'/gratis/'", $aparece);
ver("'/GRATIS/'", $mayusculas);

// ---------------------------------------------------------------------------------------------
// MODIFICADOR i — no distingue mayúsculas y minúsculas
// ENLACE: https://www.php.net/manual/es/reference.pcre.pattern.modifiers.php
// ENUNCIADO EJEMPLO: detectar "gratis" se escriba como se escriba ("GRATIS", "Gratis"...).
// ---------------------------------------------------------------------------------------------
titulo('modificador i');
$conI          = preg_match('/GRATIS/i', $comentario);        // 1
ver("'/GRATIS/i'", $conI);

// ---------------------------------------------------------------------------------------------
// METACARACTERES y ESCAPE — estos caracteres tienen significado especial:  . ^ $ * + ? ( ) [ ] { } | \ /
//   Para buscarlos literalmente hay que ponerles delante una barra invertida: \.  \$  \(  \/
// ENLACE: https://www.php.net/manual/es/regexp.reference.meta.php
// ENUNCIADO EJEMPLO: buscar el precio "9.99$" en un texto, y una ruta con barras.
// NOTA: la barra / es el delimitador, así que dentro del patrón se escribe \/ (o se usa otro
//   delimitador, como #...# o ~...~).
// ---------------------------------------------------------------------------------------------
titulo('metacaracteres y escape');
$texto         = "Oferta: 9.99$";
$sinEscapar    = preg_match('/9.99$/', $texto);       // 0: el . es "cualquier carácter" y el $ es "final"
$escapado      = preg_match('/9\.99\$/', $texto);     // 1
ver("'/9.99\$/'", $sinEscapar);
ver("'/9\\.99\\\$/'", $escapado);

$ruta          = "/var/www/html";
$conBarras     = preg_match('/\/var\/www/', $ruta);   // 1
$otroDelim     = preg_match('#/var/www#', $ruta);     // 1 (más legible)
ver('con \/', $conBarras);
ver('con # como delimitador', $otroDelim);

// ---------------------------------------------------------------------------------------------
// EL PUNTO . — cualquier carácter (excepto el salto de línea)
// ENUNCIADO EJEMPLO: buscar "c?sa": casa, cosa, cesa... (cualquier letra en medio).
// ---------------------------------------------------------------------------------------------
titulo('el punto');
$palabras      = ["casa", "cosa", "cesa", "csa", "caosa"];
$resultados    = [];
foreach ($palabras as $p) {
    $resultados[$p] = preg_match('/^c.sa$/', $p);     // ^ y $: el texto entero (se ven más abajo)
}
ver("'/^c.sa\$/'", $resultados);                      // casa, cosa, cesa: 1; csa, caosa: 0

// ---------------------------------------------------------------------------------------------
// CLASES DE CARACTERES [ ] — UNO de los caracteres de la lista
//   [aeiou]  una vocal        [0-9]  un dígito (rango)        [a-zA-Z]  una letra ASCII
//   [^0-9]   cualquier carácter que NO sea un dígito (^ al principio de la clase = negación)
// ENLACE: https://www.php.net/manual/es/regexp.reference.character-classes.php
// ENUNCIADO EJEMPLO: comprobar si la letra de un grupo es A, B o C; si un carácter es un dígito
//   hexadecimal; y si una contraseña tiene algún carácter que no sea letra ni número.
// ---------------------------------------------------------------------------------------------
titulo('clases de caracteres');
$grupoValido   = preg_match('/^DAW[ABC]$/', "DAWB");          // 1
$grupoMalo     = preg_match('/^DAW[ABC]$/', "DAWD");          // 0
$hexadecimal   = preg_match('/^[0-9a-fA-F]$/', "e");          // 1
$especial      = preg_match('/[^a-zA-Z0-9]/', "Clave_2026");  // 1 (el "_")
ver('DAWB', $grupoValido);
ver('DAWD', $grupoMalo);
ver('"e" es hexadecimal', $hexadecimal);
ver('"Clave_2026" tiene un carácter especial', $especial);

// ---------------------------------------------------------------------------------------------
// CLASES ABREVIADAS
//   \d  dígito [0-9]                \D  NO dígito
//   \w  carácter de palabra [a-zA-Z0-9_]   \W  NO carácter de palabra
//   \s  espacio en blanco (espacio, \t, \n, \r...)   \S  NO espacio
// ENLACE: https://www.php.net/manual/es/regexp.reference.escape.php
// ENUNCIADO EJEMPLO: comprobar si un nombre de usuario contiene espacios y si un texto contiene
//   algún número.
// NOTA: sin el modificador u, \w NO incluye la ñ ni las letras con tilde.
// ---------------------------------------------------------------------------------------------
titulo('clases abreviadas');
$tieneEspacio  = preg_match('/\s/', "ana perez");             // 1
$tieneNumero   = preg_match('/\d/', "Calle Mayor 5");         // 1
$sinNumero     = preg_match('/\d/', "Calle Mayor");           // 0
ver('¿espacio en "ana perez"?', $tieneEspacio);
ver('¿número en "Calle Mayor 5"?', $tieneNumero);
ver('¿número en "Calle Mayor"?', $sinNumero);

// ---------------------------------------------------------------------------------------------
// ANCLAS ^ y $ — principio y final del texto
// ENLACE: https://www.php.net/manual/es/regexp.reference.anchors.php
// ENUNCIADO EJEMPLO: para VALIDAR un dato completo hay que anclar el patrón. Sin anclas,
//   '/\d\d\d\d\d/' acepta "abc12345xyz" como código postal porque contiene 5 dígitos.
// NOTA: $ también acepta un salto de línea final. Para ser estrictos se usa \z (fin absoluto)
//   o el modificador D.
// ---------------------------------------------------------------------------------------------
titulo('anclas ^ $');
$sinAnclas     = preg_match('/\d\d\d\d\d/', "abc12345xyz");   // 1 (¡mal para validar!)
$conAnclas     = preg_match('/^\d\d\d\d\d$/', "abc12345xyz"); // 0
$valido        = preg_match('/^\d\d\d\d\d$/', "36380");       // 1
$empieza       = preg_match('/^Hola/', "Hola, ¿qué tal?");    // 1: empieza por "Hola"
ver('sin anclas', $sinAnclas);
ver('con anclas', $conAnclas);
ver('"36380" con anclas', $valido);
ver('empieza por Hola', $empieza);

// ---------------------------------------------------------------------------------------------
// MODIFICADOR u — trabaja en UTF-8 (necesario con tildes, ñ, emojis)
// ENUNCIADO EJEMPLO: comprobar que una palabra tiene exactamente 4 caracteres: "Peña" tiene 4
//   caracteres pero 5 bytes. Sin /u, el punto "ve" bytes.
// ---------------------------------------------------------------------------------------------
titulo('modificador u');
$sinU          = preg_match('/^....$/', "Peña");     // 0 (5 bytes)
$conU          = preg_match('/^....$/u', "Peña");    // 1 (4 caracteres)
ver('sin /u', $sinU);
ver('con /u', $conU);

// ---------------------------------------------------------------------------------------------
// MODIFICADOR x — permite espacios y comentarios dentro del patrón (para patrones largos)
// ENUNCIADO EJEMPLO: el mismo patrón de código postal escrito de forma más legible.
// NOTA: con x, los espacios del patrón se ignoran; para buscar un espacio escribe \s o [ ].
// ---------------------------------------------------------------------------------------------
titulo('modificador x');
$legible       = '/^
    \d\d     # provincia (2 dígitos)
    \d\d\d   # municipio (3 dígitos)
$/x';
ver('código postal con /x', preg_match($legible, "36380"));   // 1

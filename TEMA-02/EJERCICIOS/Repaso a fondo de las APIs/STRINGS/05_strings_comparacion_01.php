<?php
/*
 * 05_strings_comparacion_01.php
 *  STRINGS - COMPARACIÓN (1): comparar strings completos o sus primeros caracteres
 *  Funciones: strcmp, strcasecmp, strncmp, strncasecmp, substr_compare, strcoll
 *
 *  Todas estas funciones devuelven un ENTERO:
 *      < 0  si el primer string va ANTES que el segundo
 *        0  si son iguales
 *      > 0  si el primer string va DESPUÉS que el segundo
 *  (en PHP 8 devuelven -1, 0 o 1, pero no te fíes del valor exacto: compara con < 0, == 0, > 0)
 *
 *  Ejecuta en modo depuración paso a paso (F10) y comprueba el signo de cada resultado.
 */
require_once __DIR__ . '/utilidades.php';

// ---------------------------------------------------------------------------------------------
// strcmp — compara dos strings byte a byte, DISTINGUIENDO mayúsculas y minúsculas
// ENLACE: https://www.php.net/manual/es/function.strcmp.php
// ENUNCIADO EJEMPLO: comprobar si el código de un cupón de descuento es exactamente el correcto,
//   y ordenar una lista de apellidos con usort.
// NOTA: compara códigos ASCII: las MAYÚSCULAS (65-90) van antes que las minúsculas (97-122).
// ---------------------------------------------------------------------------------------------
titulo('strcmp');
$cupon         = "VERANO26";
$escrito       = "verano26";
$resultado     = strcmp($cupon, $escrito);         // < 0  ("V" = 86 < "v" = 118)
$esCorrecto    = $resultado === 0;                 // false
ver("strcmp('$cupon', '$escrito')", $resultado);
ver('¿cupón correcto?', $esCorrecto);

$apellidos     = ["Pérez", "abad", "Lago", "Castro"];
usort($apellidos, 'strcmp');                       // usort usa strcmp para comparar de 2 en 2
ver('ordenados con strcmp', $apellidos);           // "abad" queda al final (minúscula)

// ---------------------------------------------------------------------------------------------
// strcasecmp — igual que strcmp pero SIN distinguir mayúsculas y minúsculas
// ENLACE: https://www.php.net/manual/es/function.strcasecmp.php
// ENUNCIADO EJEMPLO: al iniciar sesión el nombre de usuario no debe distinguir mayúsculas.
// NOTA: solo para letras ASCII (no iguala "Ñ" con "ñ" ni "Á" con "á").
// ---------------------------------------------------------------------------------------------
titulo('strcasecmp');
$usuarioGuardado = "AnaPerez";
$usuarioLogin    = "anaperez";
$mismoUsuario    = strcasecmp($usuarioGuardado, $usuarioLogin) === 0;   // true
ver('¿mismo usuario?', $mismoUsuario);

usort($apellidos, 'strcasecmp');
ver('ordenados con strcasecmp', $apellidos);       // ahora "abad" va el primero

// ---------------------------------------------------------------------------------------------
// strncmp — compara solo los N PRIMEROS caracteres (distingue mayúsculas)
// ENLACE: https://www.php.net/manual/es/function.strncmp.php
// ENUNCIADO EJEMPLO: comprobar si un código postal pertenece a la provincia de Pontevedra
//   (empieza por "36").
// ---------------------------------------------------------------------------------------------
titulo('strncmp');
$codigoPostal  = "36380";
$esPontevedra  = strncmp($codigoPostal, "36", 2) === 0;   // true
ver("¿$codigoPostal es de Pontevedra?", $esPontevedra);

// ---------------------------------------------------------------------------------------------
// strncasecmp — compara los N primeros caracteres SIN distinguir mayúsculas
// ENLACE: https://www.php.net/manual/es/function.strncasecmp.php
// ENUNCIADO EJEMPLO: saber si una URL empieza por "https://" aunque el usuario la escriba como
//   "HTTPS://".
// ---------------------------------------------------------------------------------------------
titulo('strncasecmp');
$url           = "HTTPS://www.iesrodeira.gal";
$esSegura      = strncasecmp($url, "https://", 8) === 0;  // true
ver('¿URL segura?', $esSegura);

// ---------------------------------------------------------------------------------------------
// substr_compare — compara una PARTE de un string (desde una posición) con otro string
// ENLACE: https://www.php.net/manual/es/function.substr-compare.php
// ENUNCIADO EJEMPLO: comprobar si un fichero subido tiene extensión ".pdf" sin distinguir
//   mayúsculas (comparamos los 4 últimos caracteres).
// NOTA: parámetros: (texto, buscado, posición, longitud, sin_distinguir_mayúsculas).
//   Posición negativa = contando desde el final.
// ---------------------------------------------------------------------------------------------
titulo('substr_compare');
$fichero       = "Memoria_Proyecto.PDF";
$esPdf         = substr_compare($fichero, ".pdf", -4, 4, true) === 0;   // true
ver("¿'$fichero' es PDF?", $esPdf);

// ---------------------------------------------------------------------------------------------
// strcoll — compara según las reglas del IDIOMA configurado con setlocale (LC_COLLATE)
// ENLACE: https://www.php.net/manual/es/function.strcoll.php
// ENUNCIADO EJEMPLO: ordenar palabras en español: con strcmp "árbol" va después de "zorro"
//   (porque el byte de la "á" es mayor que "z"); con strcoll y el idioma español, no.
// NOTA: depende del sistema operativo y de los idiomas instalados. En Windows el nombre del
//   idioma es "Spanish_Spain"; en Linux "es_ES.UTF-8". Le pasamos varios para que funcione en ambos.
// ---------------------------------------------------------------------------------------------
titulo('strcoll');
$idioma        = setlocale(LC_COLLATE, ["es_ES.UTF-8", "es_ES", "Spanish_Spain", "es"]);
ver('idioma activado', $idioma);

$conStrcmp     = strcmp("árbol", "zorro");        // > 0  ("árbol" después de "zorro")
$conStrcoll    = strcoll("árbol", "zorro");       // < 0  si el idioma español está disponible
ver('strcmp("árbol", "zorro")', $conStrcmp);
ver('strcoll("árbol", "zorro")', $conStrcoll);

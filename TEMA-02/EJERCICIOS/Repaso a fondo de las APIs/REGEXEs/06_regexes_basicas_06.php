<?php
/*
 * 06_regexes_basicas_06.php
 *  REGEXES - BÁSICAS (6): aserciones (lookahead / lookbehind) y propiedades Unicode
 *  Contenidos: (?= ) (?! ) (?<= ) (?<! ), validación de contraseñas fuertes, \p{L} \p{Lu} \p{N}
 *              con el modificador u
 *
 *  Una ASERCIÓN comprueba que algo está (o no está) delante o detrás de la posición actual, pero
 *  NO forma parte de la coincidencia (no "consume" caracteres):
 *      x(?=y)    x seguido de y              (lookahead positivo)
 *      x(?!y)    x NO seguido de y           (lookahead negativo)
 *      (?<=y)x   x precedido de y            (lookbehind positivo)
 *      (?<!y)x   x NO precedido de y         (lookbehind negativo)
 *
 *  Ejecuta en modo depuración (F10) y compara $m[0] con y sin aserción.
 */
require_once __DIR__ . '/utilidades.php';

// ---------------------------------------------------------------------------------------------
// LOOKAHEAD POSITIVO (?= )
// ENLACE: https://www.php.net/manual/es/regexp.reference.assertions.php
// ENUNCIADO EJEMPLO: obtener el número de un precio que va seguido del símbolo "€", sin incluir
//   el símbolo en el resultado.
// ---------------------------------------------------------------------------------------------
titulo('lookahead (?= )');
$texto         = "Pack de 3 camisetas por 25€";
preg_match('/\d+(?=€)/u', $texto, $m);
ver('número seguido de €', $m[0]);            // "25" (el 3 no va seguido de €)

// ---------------------------------------------------------------------------------------------
// LOOKAHEAD NEGATIVO (?! )
// ENUNCIADO EJEMPLO: buscar la palabra "Java" pero NO cuando forma parte de "JavaScript".
// ---------------------------------------------------------------------------------------------
titulo('lookahead negativo (?! )');
ver('"Sé JavaScript"', preg_match('/Java(?!Script)/', "Sé JavaScript"));   // 0
ver('"Sé Java y PHP"', preg_match('/Java(?!Script)/', "Sé Java y PHP"));   // 1

// ---------------------------------------------------------------------------------------------
// LOOKBEHIND POSITIVO (?<= ) y NEGATIVO (?<! )
// ENUNCIADO EJEMPLO: obtener la cantidad de un precio en dólares "$40" sin el símbolo, y encontrar
//   un número que NO sea negativo (no precedido de "-").
// NOTA: en PCRE, lo que va dentro de un lookbehind debe tener longitud fija (o alternativas de
//   longitud fija).
// ---------------------------------------------------------------------------------------------
titulo('lookbehind (?<= ) y (?<! )');
preg_match('/(?<=\$)\d+/', "Precio: $40 (antes $55)", $m);
ver('cantidad tras $', $m[0]);                 // "40"

preg_match('/(?<![-\d])\d+/', "Temperaturas: -3 y 12 grados", $m);
ver('primer número no negativo', $m[0]);       // "12"

// ---------------------------------------------------------------------------------------------
// VALIDAR UNA CONTRASEÑA FUERTE con varios lookahead al principio
//   Cada (?=.*X) comprueba "en algún sitio hay un X" sin avanzar; después .{8,} comprueba la longitud.
// ENUNCIADO EJEMPLO: la contraseña debe tener al menos 8 caracteres, una minúscula, una
//   mayúscula, un dígito y un carácter que no sea letra ni número.
// ---------------------------------------------------------------------------------------------
titulo('contraseña fuerte');
$fuerte        = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^a-zA-Z\d]).{8,}$/';
$claves        = ["Rodeira2026!", "rodeira2026!", "Rodeira2026", "Ro2!", "RODEIRA2026!"];
$resultado     = [];
foreach ($claves as $c) {
    $resultado[$c] = preg_match($fuerte, $c);    // 1, 0 (sin mayúscula), 0 (sin especial), 0 (corta), 0 (sin minúscula)
}
ver('¿fuerte?', $resultado);

// ---------------------------------------------------------------------------------------------
// PROPIEDADES UNICODE \p{...} (con el modificador u)
//   \p{L}  cualquier letra de cualquier idioma (incluye á, ñ, ç...)   \P{L}  lo que NO es letra
//   \p{Lu} letra mayúscula   \p{Ll} letra minúscula   \p{N} número   \p{P} signo de puntuación
// ENLACE: https://www.php.net/manual/es/regexp.reference.unicode.php
// ENUNCIADO EJEMPLO: validar un nombre propio en español o gallego ("María Núñez", "Xoán
//   Castelao"): con [a-zA-Z] fallaría por las tildes y la ñ.
// ---------------------------------------------------------------------------------------------
titulo('propiedades Unicode');
$conAscii      = '/^[a-zA-Z ]+$/';
$conUnicode    = '/^[\p{L} ]+$/u';
$nombre        = "María Núñez";
ver('[a-zA-Z ]', preg_match($conAscii, $nombre));     // 0
ver('[\p{L} ]', preg_match($conUnicode, $nombre));    // 1

$nombrePropio  = '/^\p{Lu}\p{Ll}+( \p{Lu}\p{Ll}+)*$/u';   // cada palabra empieza por mayúscula
ver('"Xoán Castelao"', preg_match($nombrePropio, "Xoán Castelao"));   // 1
ver('"xoán castelao"', preg_match($nombrePropio, "xoán castelao"));   // 0

// ---------------------------------------------------------------------------------------------
// \w CON Y SIN EL MODIFICADOR u
// ENUNCIADO EJEMPLO: comprobar que un nombre de usuario solo tiene caracteres de palabra:
//   "peña_2026". Sin /u, \w no acepta la ñ; con /u sí.
// ---------------------------------------------------------------------------------------------
titulo('\w con /u');
ver('sin /u', preg_match('/^\w+$/', "peña_2026"));    // 0
ver('con /u', preg_match('/^\w+$/u', "peña_2026"));   // 1

<?php
/*
 * 04_regexes_basicas_04.php
 *  REGEXES - BÁSICAS (4): GRUPOS DE CAPTURA (capture groups)
 *  Contenidos: grupos ( ), el array de coincidencias, grupos anidados, grupos opcionales,
 *              grupos no capturadores (?: ), referencias hacia atrás \1
 *
 *  Los paréntesis tienen DOS funciones:
 *    1. AGRUPAR: aplicar un cuantificador o una alternancia a varios elementos: (ab)+  (jpg|png)
 *    2. CAPTURAR: guardar la parte del texto que coincide con el grupo para usarla después.
 *
 *  preg_match($patron, $texto, $m) rellena el array $m:
 *      $m[0]  -> todo el texto que coincide con el patrón
 *      $m[1]  -> lo que coincide con el 1er grupo (el 1er paréntesis que se ABRE)
 *      $m[2]  -> lo que coincide con el 2º grupo ... y así sucesivamente
 *
 *  Ejecuta en modo depuración (F10) y despliega el array $m en el panel VARIABLES en cada ejemplo.
 */
require_once __DIR__ . '/utilidades.php';

// ---------------------------------------------------------------------------------------------
// GRUPOS DE CAPTURA ( )
// ENLACE: https://www.php.net/manual/es/regexp.reference.subpatterns.php
// ENUNCIADO EJEMPLO: de una fecha "16/10/2026" obtener el día, el mes y el año por separado;
//   convertirla al formato de MySQL "2026-10-16".
// ---------------------------------------------------------------------------------------------
titulo('grupos de captura');
$fecha         = "16/10/2026";
$ok            = preg_match('/^(\d{2})\/(\d{2})\/(\d{4})$/', $fecha, $m);
ver('¿coincide?', $ok);
ver('$m', $m);                       // [0 => "16/10/2026", 1 => "16", 2 => "10", 3 => "2026"]
ver('fecha MySQL', "$m[3]-$m[2]-$m[1]");                      // "2026-10-16"

[, $dia, $mes, $anio] = $m;          // desestructuración: saltamos $m[0]
ver('día / mes / año', [$dia, $mes, $anio]);

// ---------------------------------------------------------------------------------------------
// SI NO HAY COINCIDENCIA, el array queda VACÍO
// ENUNCIADO EJEMPLO: comprobar SIEMPRE el valor devuelto por preg_match antes de usar $m.
// ---------------------------------------------------------------------------------------------
titulo('sin coincidencia');
$resultado     = preg_match('/^(\d{2})\/(\d{2})\/(\d{4})$/', "16-10-2026", $m);
ver('¿coincide?', $resultado);       // 0
ver('$m', $m);                       // []  -> $m[1] daría un aviso "Undefined array key"

// ---------------------------------------------------------------------------------------------
// GRUPOS ANIDADOS — se numeran por el orden en que se ABRE cada paréntesis
// ENUNCIADO EJEMPLO: de una hora "14:35:20" capturar la hora completa hh:mm (grupo 1), y dentro de
//   ella las horas (2) y los minutos (3); los segundos van en el grupo 4.
// ---------------------------------------------------------------------------------------------
titulo('grupos anidados');
preg_match('/^((\d{2}):(\d{2})):(\d{2})$/', "14:35:20", $m);
ver('$m', $m);   // [0 => 14:35:20, 1 => 14:35, 2 => 14, 3 => 35, 4 => 20]

// ---------------------------------------------------------------------------------------------
// GRUPOS OPCIONALES — si un grupo no participa, su posición queda como "" (o no aparece si es
//   el último). Con PREG_UNMATCHED_AS_NULL aparece como null.
// ENUNCIADO EJEMPLO: un precio con decimales opcionales: "25" o "25,90". Capturar la parte entera
//   y la decimal.
// ---------------------------------------------------------------------------------------------
titulo('grupos opcionales');
$patron        = '/^(\d+)(,(\d{2}))?$/';
preg_match($patron, "25,90", $conDecimales);
preg_match($patron, "25", $sinDecimales);
preg_match($patron, "25", $conNull, PREG_UNMATCHED_AS_NULL);
ver('"25,90"', $conDecimales);       // [25,90 / 25 / ,90 / 90]
ver('"25"', $sinDecimales);          // [25 / 25]   (los grupos finales que no participan no aparecen)
ver('"25" con PREG_UNMATCHED_AS_NULL', $conNull);   // [25 / 25 / null / null]

// ---------------------------------------------------------------------------------------------
// GRUPOS NO CAPTURADORES (?: ) — agrupan pero NO capturan (no ocupan un número en $m)
// ENUNCIADO EJEMPLO: en un teléfono con prefijo opcional (+34), solo nos interesa capturar los 9
//   dígitos. Si el prefijo fuera un grupo normal, el número pasaría a ser el grupo 2.
// ---------------------------------------------------------------------------------------------
titulo('grupos no capturadores');
preg_match('/^(\+34 )?(\d{9})$/', "+34 612345678", $capturando);
preg_match('/^(?:\+34 )?(\d{9})$/', "+34 612345678", $sinCapturar);
ver('con ( )', $capturando);          // [.., 1 => "+34 ", 2 => "612345678"]
ver('con (?: )', $sinCapturar);       // [.., 1 => "612345678"]

// ---------------------------------------------------------------------------------------------
// ALTERNATIVAS dentro de un grupo
// ENUNCIADO EJEMPLO: de un texto "Pedido enviado por SEUR" capturar la empresa de transporte,
//   que puede ser SEUR, MRW o Correos.
// ---------------------------------------------------------------------------------------------
titulo('alternativas en un grupo');
preg_match('/enviado por (SEUR|MRW|Correos)/', "Pedido 37 enviado por MRW el lunes", $m);
ver('empresa', $m[1]);                // "MRW"

// ---------------------------------------------------------------------------------------------
// REFERENCIAS HACIA ATRÁS \1, \2... — "lo mismo que capturó el grupo N"
// ENLACE: https://www.php.net/manual/es/regexp.reference.back-references.php
// ENUNCIADO EJEMPLO: (1) detectar palabras repetidas por error ("el el"); (2) comprobar que una
//   fecha usa el mismo separador las dos veces ("16/10/2026" o "16-10-2026", pero no "16/10-2026").
// NOTA: dentro de un string con comillas simples se escribe \1; la referencia apunta al TEXTO
//   capturado, no al patrón.
// ---------------------------------------------------------------------------------------------
titulo('referencias hacia atrás');
preg_match('/\b(\w+) \1\b/', "Me gusta el el café", $m);
ver('palabra repetida', $m[1] ?? null);                       // "el"

$mismoSeparador = '/^\d{2}([\/-])\d{2}\1\d{4}$/';
ver('"16/10/2026"', preg_match($mismoSeparador, "16/10/2026"));   // 1
ver('"16-10-2026"', preg_match($mismoSeparador, "16-10-2026"));   // 1
ver('"16/10-2026"', preg_match($mismoSeparador, "16/10-2026"));   // 0

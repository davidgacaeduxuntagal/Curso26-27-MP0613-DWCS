<?php
/*
 * 05_regexes_basicas_05.php
 *  REGEXES - BÁSICAS (5): GRUPOS CON NOMBRE (named capture groups)
 *  Contenidos: (?<nombre> ), (?P<nombre> ), acceso por nombre y por número, referencias por
 *              nombre \k<nombre>, grupos con nombre opcionales, nombres repetidos con (?J)
 *
 *  Con muchos grupos, $m[4] o $m[7] es difícil de leer y se estropea si añadimos un paréntesis.
 *  Un grupo con nombre (?<anio>\d{4}) aparece en $m con su NOMBRE ($m['anio']) y también con su
 *  número.
 *
 *  Ejecuta en modo depuración (F10) y despliega $m en el panel VARIABLES: verás cada grupo dos
 *  veces, con su nombre y con su número.
 */
require_once __DIR__ . '/utilidades.php';

// ---------------------------------------------------------------------------------------------
// GRUPOS CON NOMBRE (?<nombre> ... )
// ENLACE: https://www.php.net/manual/es/regexp.reference.subpatterns.php
// ENUNCIADO EJEMPLO: de una fecha ISO "2026-10-16" obtener año, mes y día por su nombre.
// ---------------------------------------------------------------------------------------------
titulo('grupos con nombre');
preg_match('/^(?<anio>\d{4})-(?<mes>\d{2})-(?<dia>\d{2})$/', "2026-10-16", $m);
ver('$m', $m);       // [0 => ..., 'anio' => 2026, 1 => 2026, 'mes' => 10, 2 => 10, 'dia' => 16, 3 => 16]
ver('fecha española', "{$m['dia']}/{$m['mes']}/{$m['anio']}");

// Para quedarnos solo con los grupos con nombre (sin las claves numéricas):
$soloNombres   = array_filter($m, 'is_string', ARRAY_FILTER_USE_KEY);
ver('solo los grupos con nombre', $soloNombres);

// ---------------------------------------------------------------------------------------------
// SINTAXIS ALTERNATIVAS: (?P<nombre> ) y (?'nombre' ) — equivalentes a (?<nombre> )
// ENUNCIADO EJEMPLO: analizar una línea de un registro de Apache: IP, método y ruta.
// NOTA: (?P<...>) es la sintaxis de Python, muy usada en código antiguo.
// ---------------------------------------------------------------------------------------------
titulo('sintaxis (?P<nombre>)');
$linea         = '192.168.1.20 - - [16/Oct/2026:10:05:00] "GET /tienda/carrito.php HTTP/1.1" 200';
preg_match('/^(?P<ip>[\d.]+) .*"(?P<metodo>GET|POST) (?P<ruta>\S+)/', $linea, $m);
ver('IP', $m['ip']);              // "192.168.1.20"
ver('método', $m['metodo']);      // "GET"
ver('ruta', $m['ruta']);          // "/tienda/carrito.php"

// ---------------------------------------------------------------------------------------------
// MEZCLAR grupos con nombre y sin nombre — los números se asignan a TODOS por orden de apertura
// ENUNCIADO EJEMPLO: un código de producto "ELEC-2026-0042": categoría con nombre, año sin
//   nombre y número con nombre. Observa qué número recibe cada uno.
// ---------------------------------------------------------------------------------------------
titulo('mezclando con y sin nombre');
preg_match('/^(?<categoria>[A-Z]{4})-(\d{4})-(?<numero>\d{4})$/', "ELEC-2026-0042", $m);
ver('$m', $m);       // categoria = 1, el año = 2, numero = 3

// ---------------------------------------------------------------------------------------------
// GRUPOS CON NOMBRE OPCIONALES
// ENUNCIADO EJEMPLO: una dirección "Calle Mayor 5" o "Calle Mayor 5, 2º B": el piso es opcional.
//   Con PREG_UNMATCHED_AS_NULL el grupo 'piso' existe y vale null si no aparece; así podemos usar ??.
// ---------------------------------------------------------------------------------------------
titulo('grupos con nombre opcionales');
$patron        = '/^(?<calle>.+?) (?<numero>\d+)(?:, (?<piso>.+))?$/u';
foreach (["Calle Mayor 5, 2º B", "Rúa do Príncipe 12"] as $direccion) {
    preg_match($patron, $direccion, $m, PREG_UNMATCHED_AS_NULL);
    ver($direccion, "calle={$m['calle']} | número={$m['numero']} | piso=" . ($m['piso'] ?? '(sin piso)'));
}

// ---------------------------------------------------------------------------------------------
// REFERENCIAS POR NOMBRE \k<nombre> (o (?P=nombre))
// ENLACE: https://www.php.net/manual/es/regexp.reference.back-references.php
// ENUNCIADO EJEMPLO: comprobar que una etiqueta HTML se cierra con la MISMA etiqueta con la que
//   se abrió: <b>...</b> sí, <b>...</i> no.
// ---------------------------------------------------------------------------------------------
titulo('referencias por nombre');
$etiqueta      = '/^<(?<tag>[a-z]+)>.*<\/\k<tag>>$/';
ver('<b>hola</b>', preg_match($etiqueta, "<b>hola</b>"));     // 1
ver('<b>hola</i>', preg_match($etiqueta, "<b>hola</i>"));     // 0

// ---------------------------------------------------------------------------------------------
// EL MISMO NOMBRE EN VARIAS ALTERNATIVAS — modificador (?J)
// ENUNCIADO EJEMPLO: aceptar fechas "16/10/2026" (española) y "2026-10-16" (ISO) y obtener SIEMPRE
//   $m['anio'], $m['mes'] y $m['dia'], sea cual sea el formato.
// NOTA: sin (?J), repetir un nombre de grupo es un error. Con (?J) al principio se permite, y
//   PHP guarda en $m['nombre'] el valor del grupo que ha participado en la coincidencia.
//   Observa en el depurador que los grupos NUMERADOS siguen siendo 6 (1-3 vacíos o 4-6 vacíos).
// ---------------------------------------------------------------------------------------------
titulo('mismo nombre en varias alternativas');
$dosFormatos   = '/^(?J)(?:(?<dia>\d{2})\/(?<mes>\d{2})\/(?<anio>\d{4})|(?<anio>\d{4})-(?<mes>\d{2})-(?<dia>\d{2}))$/';
foreach (["16/10/2026", "2026-10-16"] as $f) {
    preg_match($dosFormatos, $f, $m);
    ver($f, "día {$m['dia']}, mes {$m['mes']}, año {$m['anio']}");
}

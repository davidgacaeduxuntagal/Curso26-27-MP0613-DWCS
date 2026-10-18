<?php
/*
 * 05_regexes_basicas_05_problema_solucion.php
 *  (FICHERO CON LAS SOLUCIONES)
 *  Problemas de: grupos con nombre (?<nombre> ), referencias por nombre \k<nombre>, grupos con
 *                nombre opcionales, (?J)
 *  (repasa los ejemplos de ../05_regexes_basicas_05.php)
 *
 *  Resuelve cada problema donde se indica y ejecútalo en modo depuración para comprobarlo.
 */
require_once __DIR__ . '/../utilidades.php';

// =============================================================================================
// PROBLEMA 1. Matrícula de coche
//  Captura con grupos con nombre los "numeros" y las "letras" de una matrícula "1234-BCD" (el
//  guion es opcional) y muéstralos con $m['numeros'] y $m['letras'].
// =============================================================================================
titulo('Problema 1');
$matricula = "1234-BCD";
// completa aquí tu solución
// ---- SOLUCIÓN ----
preg_match('/^(?<numeros>\d{4})-?(?<letras>[B-DF-HJ-NP-TV-Z]{3})$/i', $matricula, $m);
ver('números', $m['numeros']);   // "1234"
ver('letras', $m['letras']);     // "BCD"

// =============================================================================================
// PROBLEMA 2. Línea de un fichero de log
//  Extrae con grupos con nombre la fecha, la hora, el nivel (INFO, WARNING o ERROR) y el mensaje
//  de cada línea, y construye un array de arrays solo con los grupos con nombre.
// =============================================================================================
titulo('Problema 2');
$lineas = [
    "2026-10-16 09:15:02 [INFO] Usuario ana conectado",
    "2026-10-16 09:20:45 [ERROR] Fallo al guardar el pedido 37",
];
// completa aquí tu solución
// ---- SOLUCIÓN ----
$registros = [];
foreach ($lineas as $linea) {
    preg_match('/^(?<fecha>\d{4}-\d{2}-\d{2}) (?<hora>\d{2}:\d{2}:\d{2}) \[(?<nivel>INFO|WARNING|ERROR)\] (?<mensaje>.+)$/', $linea, $m);
    $registros[] = array_filter($m, 'is_string', ARRAY_FILTER_USE_KEY);
}
ver('registros', $registros);

// =============================================================================================
// PROBLEMA 3. Teléfono con extensión opcional
//  Un teléfono de empresa puede llevar extensión: "986123456 ext. 204". Captura "numero" y
//  "extension"; si no hay extensión, muestra "(sin extensión)". Usa PREG_UNMATCHED_AS_NULL y ??.
// =============================================================================================
titulo('Problema 3');
$telefonos = ["986123456 ext. 204", "986654321"];
// completa aquí tu solución
// ---- SOLUCIÓN ----
foreach ($telefonos as $t) {
    preg_match('/^(?<numero>\d{9})(?: ext\. (?<extension>\d+))?$/', $t, $m, PREG_UNMATCHED_AS_NULL);
    ver($t, $m['numero'] . " / " . ($m['extension'] ?? "(sin extensión)"));
}

// =============================================================================================
// PROBLEMA 4. Comillas que cierran igual que abren
//  Un texto puede ir entre comillas simples o dobles, pero debe cerrar con el mismo tipo con el
//  que abre. Usa un grupo con nombre para la comilla y \k<nombre> para el cierre, y captura el
//  contenido en otro grupo con nombre.
// =============================================================================================
titulo('Problema 4');
$textos = ['"hola"', "'adiós'", "\"mal'", "'mal\""];
// completa aquí tu solución
// ---- SOLUCIÓN ----
$resultado = [];
foreach ($textos as $t) {
    $resultado[$t] = preg_match('/^(?<comilla>["\'])(?<contenido>.*)\k<comilla>$/', $t, $m) ? $m['contenido'] : "(incorrecto)";
}
ver('contenido', $resultado);   // hola, adiós, (incorrecto), (incorrecto)

// =============================================================================================
// PROBLEMA 5. Dos formatos de hora
//  Las horas pueden venir como "14:30" o como "2:30 PM". Con (?J) y grupos con el mismo nombre
//  en cada alternativa, obtén siempre $m['hora'] y $m['minutos'], y convierte la segunda al
//  formato de 24 horas.
// =============================================================================================
titulo('Problema 5');
$horas = ["14:30", "2:30 PM", "9:05 AM"];
// completa aquí tu solución
// ---- SOLUCIÓN ----
$patron = '/^(?J)(?:(?<hora>\d{2}):(?<minutos>\d{2})|(?<hora>\d{1,2}):(?<minutos>\d{2}) (?<ampm>AM|PM))$/';
$en24   = [];
foreach ($horas as $h) {
    preg_match($patron, $h, $m);
    $hora   = (int) $m['hora'];
    if (($m['ampm'] ?? '') === 'PM' && $hora < 12) {
        $hora += 12;
    }
    $en24[$h] = sprintf("%02d:%s", $hora, $m['minutos']);
}
ver('en 24 horas', $en24);   // 14:30, 14:30, 09:05

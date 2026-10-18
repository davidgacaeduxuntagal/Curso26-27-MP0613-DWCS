<?php
/*
 * e03-JSON_problema_solucion.php
 *  (FICHERO CON LAS SOLUCIONES)
 *  Problemas de: opciones de json_encode (JSON_PRETTY_PRINT, JSON_UNESCAPED_UNICODE,
 *                JSON_UNESCAPED_SLASHES, JSON_FORCE_OBJECT, JSON_PRESERVE_ZERO_FRACTION,
 *                JSON_NUMERIC_CHECK, JSON_HEX_*), combinar opciones con |
 *  (repasa los ejemplos de ../e03-JSON.php)
 *
 *  Resuelve cada problema donde se indica y ejecútalo en modo depuración para comprobarlo.
 */
require_once __DIR__ . '/../utilidades.php';

$local = [
    'nombre'     => 'Bar Baio — Cociña galega',
    'web'        => 'https://barbaio.gal/carta',
    'telefono'   => '0981123456',
    'mesas'      => '12',
    'valoracion' => 4.0,
];

// =============================================================================================
// PROBLEMA 1. Un JSON legible
//  Obtén el JSON de $local con saltos de línea y sangría, con los caracteres especiales (—, ñ)
//  y las barras de la URL escritos tal cual. Muestra cuántos bytes ocupa con y sin estas
//  opciones.
// =============================================================================================
titulo('Problema 1');
// completa aquí tu solución
// ---- SOLUCIÓN ----
$legible = json_encode($local, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
echo $legible, nl();
ver('bytes sin opciones', strlen(json_encode($local)));   // 136
ver('bytes legible', strlen($legible));                   // 157

// =============================================================================================
// PROBLEMA 2. El fallo de las opciones
//  Un compañero quiere el JSON formateado y con las tildes escritas tal cual. Sale formateado,
//  pero las tildes salen como \u00e9 y no entiende por qué. Explica el fallo en un comentario
//  y corrígelo.
// =============================================================================================
titulo('Problema 2');
$opciones = JSON_PRETTY_PRINT or JSON_UNESCAPED_UNICODE;
ver('JSON del compañero', json_encode(['plato' => 'Café con leche'], $opciones));
// completa aquí tu solución
// ---- SOLUCIÓN ----
// Fallo: "or" (y ||) es un operador LÓGICO, no de bits. Además "or" tiene menos prioridad que
// "=", así que se ejecuta ($opciones = JSON_PRETTY_PRINT) or ...: aquí $opciones vale 128 y
// se pierde JSON_UNESCAPED_UNICODE. Con || se obtendría true (= 1 = JSON_HEX_TAG).
// Las opciones se combinan con el operador de bits |:
$opciones = JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE;
ver('opciones', $opciones);                                    // 384
ver('JSON corregido', json_encode(['plato' => 'Café con leche'], $opciones));

// =============================================================================================
// PROBLEMA 3. Números que no deben serlo
//  Otro compañero usa JSON_NUMERIC_CHECK para que "mesas" salga como número. Muestra qué le
//  pasa al teléfono y obtén un JSON correcto en el que mesas sea un número (12), el teléfono
//  siga siendo "0981123456" y la valoración se escriba 4.0.
// =============================================================================================
titulo('Problema 3');
ver('con NUMERIC_CHECK', json_encode($local, JSON_NUMERIC_CHECK | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
// completa aquí tu solución
// ---- SOLUCIÓN ----
// El teléfono pasa a ser 981123456: pierde el 0 de la izquierda.
$correcto = $local;
$correcto['mesas'] = (int) $local['mesas'];
$json = json_encode($correcto, JSON_PRESERVE_ZERO_FRACTION | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
ver('correcto', $json);    // ..."telefono":"0981123456","mesas":12,"valoracion":4.0}

// =============================================================================================
// PROBLEMA 4. Siempre un objeto
//  La app espera que "horario" sea SIEMPRE un objeto { }, pero cuando el bar no tiene horario
//  especial el array está vacío y sale []. Consigue que salga {} en los dos casos sin que
//  "festivos" (que es una lista) deje de ser un array. (Pista: JSON_FORCE_OBJECT afecta a todo;
//  piensa en un objeto stdClass o en un cast a (object).)
// =============================================================================================
titulo('Problema 4');
$conHorario = ['horario' => ['lunes' => 'cerrado'], 'festivos' => ['2026-12-25']];
$sinHorario = ['horario' => [], 'festivos' => ['2026-12-25']];
ver('sin horario (mal)', json_encode($sinHorario));           // {"horario":[],"festivos":["2026-12-25"]}
ver('con FORCE_OBJECT (mal)', json_encode($sinHorario, JSON_FORCE_OBJECT));   // festivos deja de ser lista
// completa aquí tu solución
// ---- SOLUCIÓN ----
foreach ([$conHorario, $sinHorario] as $datos) {
    $datos['horario'] = (object) $datos['horario'];          // un objeto, aunque esté vacío
    ver('bien', json_encode($datos));
}
// {"horario":{"lunes":"cerrado"},"festivos":["2026-12-25"]} y {"horario":{},"festivos":["2026-12-25"]}

// =============================================================================================
// PROBLEMA 5. Pasar datos a JavaScript sin riesgos
//  Genera el código HTML <script>const opinion = ...;</script> con la opinión de $opinion de
//  forma que el texto no pueda cerrar la etiqueta <script>. Comprueba que el HTML generado solo
//  contiene un "</script>" y que al decodificar el JSON se recupera la opinión original.
// =============================================================================================
titulo('Problema 5');
$opinion = ['autor' => "O'Neill", 'texto' => 'Genial </script><script>alert("hola")</script> & barato'];
// completa aquí tu solución
// ---- SOLUCIÓN ----
$json = json_encode($opinion, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_UNESCAPED_SLASHES);
$html = "<script>const opinion = $json;</script>";
ver('HTML', $html);
ver('número de </script>', substr_count($html, '</script>'));    // 1
ver('se recupera', json_decode($json, true) === $opinion);      // true

<?php
/*
 * 15_strings_codificacion_03_problema_solucion.php
 *  (FICHERO CON LAS SOLUCIONES)
 *  Problemas de: utf8_encode, utf8_decode, mb_detect_encoding, mb_check_encoding, mb_convert_encoding
 *  (repasa los ejemplos de ../15_strings_codificacion_03.php)
 *
 *  Resuelve cada problema donde se indica y ejecútalo en modo depuración para comprobarlo.
 */
require_once __DIR__ . '/../utilidades.php';

// Simulamos líneas de un CSV: unas guardadas en UTF-8 y otras en Windows-1252 (Excel)
$lineasCsv = [
    "Peña;Vigo;5€",
    mb_convert_encoding("Núñez;Ourense;10€", "Windows-1252", "UTF-8"),
    mb_convert_encoding("Ibáñez;Lugo;7€", "Windows-1252", "UTF-8"),
];

// =============================================================================================
// PROBLEMA 1. ¿Qué líneas no son UTF-8?
//  Indica para cada línea si es UTF-8 válido (mb_check_encoding).
// =============================================================================================
titulo('Problema 1');
// completa aquí tu solución
// ---- SOLUCIÓN ----
$validas = [];
foreach ($lineasCsv as $i => $linea) {
    $validas[$i] = mb_check_encoding($linea, "UTF-8");
}
ver('¿UTF-8 válido?', $validas);      // [true, false, false]

// =============================================================================================
// PROBLEMA 2. Normalizar todo a UTF-8
//  Convierte a UTF-8 SOLO las líneas que lo necesiten, detectando su codificación con
//  mb_detect_encoding (lista ["UTF-8", "Windows-1252"], modo estricto).
// =============================================================================================
titulo('Problema 2');
// completa aquí tu solución
// ---- SOLUCIÓN ----
$normalizadas = [];
foreach ($lineasCsv as $linea) {
    $codificacion   = mb_detect_encoding($linea, ["UTF-8", "Windows-1252"], true);
    $normalizadas[] = $codificacion === "UTF-8" ? $linea : mb_convert_encoding($linea, "UTF-8", $codificacion);
}
ver('normalizadas', $normalizadas);   // las 3 líneas legibles

// =============================================================================================
// PROBLEMA 3. Exportar para Excel
//  Excel en Windows abre mejor los CSV en Windows-1252. Convierte el texto UTF-8 a Windows-1252 y
//  comprueba con bin2hex que el "€" pasa a ser el byte 80 y la "ñ" el byte f1.
// =============================================================================================
titulo('Problema 3');
$paraExportar = "Año;Peña;5€";
// completa aquí tu solución
// ---- SOLUCIÓN ----
$exportado = mb_convert_encoding($paraExportar, "Windows-1252", "UTF-8");
ver('bytes UTF-8', bin2hex($paraExportar));
ver('bytes Windows-1252', bin2hex($exportado));
ver('longitud UTF-8 / Windows-1252', [strlen($paraExportar), strlen($exportado)]);   // [15, 11]

// =============================================================================================
// PROBLEMA 4. Reparar un texto "mojibake"
//  Este texto se ve mal porque un texto UTF-8 se interpretó como Windows-1252 y se volvió a
//  guardar en UTF-8. ¿Cómo harías para repararlo? (es la operación inversa)
// =============================================================================================
titulo('Problema 4');
$roto = "CampeÃ³n de EspaÃ±a";
// ¿cómo lo repararías?
// ---- SOLUCIÓN ----
$reparado = mb_convert_encoding($roto, "Windows-1252", "UTF-8");   // deshacemos la conversión errónea
ver('reparado', $reparado);       // "Campeón de España"

// =============================================================================================
// PROBLEMA 5. Sustituir las funciones obsoletas
//  Este código usa utf8_decode / utf8_encode (obsoletas en PHP 8.2). Reescríbelo con
//  mb_convert_encoding y comprueba que el resultado es el mismo.
// =============================================================================================
titulo('Problema 5');
$texto = "Canción de verano";
// completa aquí tu solución
// ---- SOLUCIÓN ----
// Antes:  $latin1 = utf8_decode($texto);   $utf8 = utf8_encode($latin1);
$latin1 = mb_convert_encoding($texto, "ISO-8859-1", "UTF-8");
$utf8   = mb_convert_encoding($latin1, "UTF-8", "ISO-8859-1");
ver('bytes en ISO-8859-1', bin2hex($latin1));
ver('¿ida y vuelta correcta?', $utf8 === $texto);   // true

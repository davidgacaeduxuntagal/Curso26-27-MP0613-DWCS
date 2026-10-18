<?php
/*
 * e04-JSON_problema.php
 *  Problemas de: json_last_error, json_last_error_msg, JSON_ERROR_*, JSON_THROW_ON_ERROR,
 *                JsonException, UTF-8 no válido, INF / NAN
 *  (repasa los ejemplos de ../e04-JSON.php)
 *
 *  Resuelve cada problema donde se indica y ejecútalo en modo depuración para comprobarlo.
 */
require_once __DIR__ . '/../utilidades.php';

// =============================================================================================
// PROBLEMA 1. Encuentra y corrige los errores
//  Cada string de $documentos tiene UN error de sintaxis JSON. Escribe junto a cada uno cuál
//  es y crea el array $corregidos con las versiones correctas. Comprueba con json_last_error()
//  que todos se decodifican sin error.
// =============================================================================================
titulo('Problema 1');
$documentos = [
    "{'nombre': 'Ana'}",
    '{"nombre": "Ana", "edad": 30,}',
    '{nombre: "Ana"}',
    '{"activo": True}',
    '{"telefono": 0981123456}',
    '["pulpo" "pan"]',
];
// completa aquí tu solución

// =============================================================================================
// PROBLEMA 2. ¿Error o dato válido?
//  Escribe una función esJsonValido(string $texto): bool (PHP 8.2 no tiene json_validate) que
//  funcione bien también con "null", "false", "0" y "[]". Pruébala con $pruebas.
// =============================================================================================
titulo('Problema 2');
$pruebas = ['null', 'false', '0', '[]', '""', '{"a":1}', 'nul', "{'a':1}", ''];
// completa aquí tu solución

// =============================================================================================
// PROBLEMA 3. Procesar pedidos con excepciones
//  Recorre $recibidos y decodifica cada uno con JSON_THROW_ON_ERROR. Guarda los correctos en
//  $aceptados y, para los incorrectos, guarda en $rechazados su posición y el mensaje de la
//  excepción. El bucle NO debe detenerse con el primer error.
// =============================================================================================
titulo('Problema 3');
$recibidos = ['{"mesa": 1, "total": 12.5}', '{"mesa": 2, "total": }', '{"mesa": 3, "total": 8}', '{"mesa": 4'];
$aceptados = [];
$rechazados = [];
// completa aquí tu solución

// =============================================================================================
// PROBLEMA 4. Un fichero en otra codificación
//  $lineaCsv viene de un programa antiguo en Windows-1252 y json_encode falla. Muestra el
//  error, y después convierte los datos a UTF-8 para que json_encode funcione y "Muñoz" se
//  vea correctamente.
// =============================================================================================
titulo('Problema 4');
$lineaCsv = mb_convert_encoding('Begoña;Muñoz;Ourense', 'Windows-1252', 'UTF-8');
$campos = explode(';', $lineaCsv);
// completa aquí tu solución

// =============================================================================================
// PROBLEMA 5. Estadísticas que no se pueden codificar
//  Calcula la media de cada plato de $valoraciones. El plato sin valoraciones hace que
//  json_encode falle. ¿Cómo harías para que el JSON se genere bien y la media de ese plato sea
//  null? Hazlo sin usar JSON_PARTIAL_OUTPUT_ON_ERROR.
// =============================================================================================
titulo('Problema 5');
$valoraciones = ['Pulpo' => [5, 4, 5], 'Zorza' => [], 'Filloas' => [4, 3]];
$medias = [];
foreach ($valoraciones as $plato => $notas) {
    $medias[$plato] = fdiv(array_sum($notas), count($notas));     // fdiv(0, 0) = NAN (con / sería un error)
}
ver('medias', $medias);
ver('json_encode', json_encode($medias));            // false
// completa aquí tu solución

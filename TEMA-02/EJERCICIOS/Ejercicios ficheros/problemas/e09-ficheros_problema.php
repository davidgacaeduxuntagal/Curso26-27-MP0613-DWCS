<?php
/*
 * e09-ficheros_problema.php
 *  Problemas de: php://memory, php://temp, php://output, php://filter, data://,
 *                stream_get_meta_data, stream_get_wrappers, stream_context_create
 *  (repasa los ejemplos de ../e09-ficheros.php)
 *
 *  Resuelve cada problema donde se indica y ejecútalo en modo depuración para comprobarlo.
 */
require_once __DIR__ . '/../utilidades.php';

// =============================================================================================
// PROBLEMA 1. Array a CSV (en un string)
//  Escribe una función arrayACsv(array $filas, string $separador = ';'): string que use fputcsv
//  sobre php://temp y devuelva el CSV como string, sin crear ficheros en disco.
// =============================================================================================
titulo('Problema 1');
$alumnos = [['dni', 'nombre', 'nota'], ['12345678Z', 'Ana Pérez', '8,5'], ['87654321X', 'Lago; Brais', '6']];
// completa aquí tu solución

// =============================================================================================
// PROBLEMA 2. CSV (en un string) a array
//  Al revés: escribe csvAArray(string $csv, string $separador = ';'): array que lea el CSV con
//  fgetcsv desde un flujo data:// (o php://memory) y devuelva un array de arrays asociativos usando
//  la primera fila como cabecera. Comprueba que recuperas el nombre "Lago; Brais".
// =============================================================================================
titulo('Problema 2');
// completa aquí tu solución

// =============================================================================================
// PROBLEMA 3. Escribir en pantalla o en fichero con la misma función
//  Escribe una función tablaMultiplicar(int $n, $destino): void que escriba la tabla de $n con
//  fprintf en el flujo $destino. Úsala para mostrarla por pantalla (php://output) y para guardarla
//  en p09_tabla9.txt.
// =============================================================================================
titulo('Problema 3');
// completa aquí tu solución

// =============================================================================================
// PROBLEMA 4. Filtros encadenados
//  Con un solo file_get_contents y php://filter, lee el fichero p09_secreto.txt aplicando ROT13 y
//  pasándolo a mayúsculas (se pueden encadenar filtros con "|").
// =============================================================================================
titulo('Problema 4');
file_put_contents(rutaDatos('p09_secreto.txt'), "ry cnffjbeq rf onenb");   // "el password es barao" en ROT13
// completa aquí tu solución

// =============================================================================================
// PROBLEMA 5. Información de los flujos
//  Abre tres flujos (un fichero de disco, php://memory y un data://) y muestra para cada uno el
//  wrapper_type, el stream_type, el modo y si se puede usar fseek (seekable).
// =============================================================================================
titulo('Problema 5');
// completa aquí tu solución

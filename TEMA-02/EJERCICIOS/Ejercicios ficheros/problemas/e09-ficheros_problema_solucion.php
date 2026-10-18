<?php
/*
 * e09-ficheros_problema_solucion.php
 *  (FICHERO CON LAS SOLUCIONES)
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
// ---- SOLUCIÓN ----
function arrayACsv(array $filas, string $separador = ';'): string
{
    $fp = fopen('php://temp', 'w+');
    foreach ($filas as $fila) {
        fputcsv($fp, $fila, $separador);
    }
    rewind($fp);
    $csv = stream_get_contents($fp);
    fclose($fp);
    return $csv;
}
ver('CSV', arrayACsv($alumnos));

// =============================================================================================
// PROBLEMA 2. CSV (en un string) a array
//  Al revés: escribe csvAArray(string $csv, string $separador = ';'): array que lea el CSV con
//  fgetcsv desde un flujo data:// (o php://memory) y devuelva un array de arrays asociativos usando
//  la primera fila como cabecera. Comprueba que recuperas el nombre "Lago; Brais".
// =============================================================================================
titulo('Problema 2');
// completa aquí tu solución
// ---- SOLUCIÓN ----
function csvAArray(string $csv, string $separador = ';'): array
{
    $fp = fopen('data://text/plain;base64,' . base64_encode($csv), 'r');
    $cabecera = fgetcsv($fp, null, $separador);
    $filas = [];
    while (($fila = fgetcsv($fp, null, $separador)) !== false) {
        $filas[] = array_combine($cabecera, $fila);
    }
    fclose($fp);
    return $filas;
}
$filas = csvAArray(arrayACsv($alumnos));
ver('filas', $filas);
ver('nombre del segundo', $filas[1]['nombre']);    // "Lago; Brais"

// =============================================================================================
// PROBLEMA 3. Escribir en pantalla o en fichero con la misma función
//  Escribe una función tablaMultiplicar(int $n, $destino): void que escriba la tabla de $n con
//  fprintf en el flujo $destino. Úsala para mostrarla por pantalla (php://output) y para guardarla
//  en p09_tabla9.txt.
// =============================================================================================
titulo('Problema 3');
// completa aquí tu solución
// ---- SOLUCIÓN ----
function tablaMultiplicar(int $n, $destino): void
{
    for ($i = 1; $i <= 10; $i++) {
        fprintf($destino, "%2d x %2d = %3d\n", $n, $i, $n * $i);
    }
}
$pantalla = fopen('php://output', 'w');
tablaMultiplicar(9, $pantalla);
fclose($pantalla);
$fichero = fopen(rutaDatos('p09_tabla9.txt'), 'w');
tablaMultiplicar(9, $fichero);
fclose($fichero);
ver('líneas en el fichero', count(file(rutaDatos('p09_tabla9.txt'))));   // 10

// =============================================================================================
// PROBLEMA 4. Filtros encadenados
//  Con un solo file_get_contents y php://filter, lee el fichero p09_secreto.txt aplicando ROT13 y
//  pasándolo a mayúsculas (se pueden encadenar filtros con "|").
// =============================================================================================
titulo('Problema 4');
file_put_contents(rutaDatos('p09_secreto.txt'), "ry cnffjbeq rf onenb");   // "el password es barao" en ROT13
// completa aquí tu solución
// ---- SOLUCIÓN ----
$texto = file_get_contents('php://filter/read=string.rot13|string.toupper/resource=' . rutaDatos('p09_secreto.txt'));
ver('descifrado', $texto);       // "EL PASSWORD ES BARAO"

// =============================================================================================
// PROBLEMA 5. Información de los flujos
//  Abre tres flujos (un fichero de disco, php://memory y un data://) y muestra para cada uno el
//  wrapper_type, el stream_type, el modo y si se puede usar fseek (seekable).
// =============================================================================================
titulo('Problema 5');
// completa aquí tu solución
// ---- SOLUCIÓN ----
$flujos = [
    'fichero' => fopen(__FILE__, 'r'),
    'memoria' => fopen('php://memory', 'w+'),
    'data'    => fopen('data://text/plain,hola', 'r'),
];
$info = [];
foreach ($flujos as $nombre => $fp) {
    $meta = stream_get_meta_data($fp);
    $info[$nombre] = [$meta['wrapper_type'], $meta['stream_type'], $meta['mode'], $meta['seekable']];
    fclose($fp);
}
ver('wrapper / stream / modo / seekable', $info);

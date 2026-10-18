<?php
/*
 * e05-ficheros.php
 *  FICHEROS (5): ficheros con FORMATO: CSV, INI, JSON y texto con formato fijo
 *  Funciones: fputcsv, fgetcsv, parse_ini_file, fscanf, json_encode + file_put_contents,
 *             json_decode + file_get_contents
 *
 *  Ejecuta en modo depuración (F10) y abre cada fichero generado en VSC para comparar el
 *  fichero con el array que se obtiene al leerlo.
 */
require_once __DIR__ . '/utilidades.php';

// ---------------------------------------------------------------------------------------------
// fputcsv — escribe un array como una línea en formato CSV
// ENLACE: https://www.php.net/manual/es/function.fputcsv.php
// ENUNCIADO EJEMPLO: guardar las notas de los alumnos (práctica 2 del material base) en un CSV con
//   ";" como separador, como lo usa una hoja de cálculo en español. Uno de los nombres contiene un
//   ";" y otro unas comillas: fputcsv los "protege" automáticamente.
// NOTA: parámetros: (fichero, array, separador, delimitador de texto). Devuelve los bytes escritos.
// ---------------------------------------------------------------------------------------------
titulo('fputcsv');
$csv           = rutaDatos('notas.csv');
$alumnos       = [
    ['dni', 'nombre', 'nota'],
    ['12345678Z', 'Ana Pérez', 8.5],
    ['87654321X', 'Lago; Brais', 6],
    ['11223344B', 'Carmen "la rápida" Núñez', 9.25],
];
$fp            = fopen($csv, 'w');
foreach ($alumnos as $fila) {
    fputcsv($fp, $fila, ';');
}
fclose($fp);
ver('fichero CSV', file_get_contents($csv));      // fíjate en las comillas de las líneas 3 y 4

// ---------------------------------------------------------------------------------------------
// fgetcsv — lee una línea CSV y la devuelve como array (o false al final)
// ENLACE: https://www.php.net/manual/es/function.fgetcsv.php
// ENUNCIADO EJEMPLO: leer el CSV anterior y convertir cada fila en un array asociativo usando la
//   primera línea como cabecera; después, buscar la nota de un DNI.
// NOTA: parámetros: (fichero, longitud máxima o null, separador). Los campos se leen como STRINGS.
// ---------------------------------------------------------------------------------------------
titulo('fgetcsv');
$fp            = fopen($csv, 'r');
$cabecera      = fgetcsv($fp, null, ';');         // ["dni", "nombre", "nota"]
$porDni        = [];
while (($fila = fgetcsv($fp, null, ';')) !== false) {
    $alumno               = array_combine($cabecera, $fila);
    $porDni[$alumno['dni']] = $alumno;
}
fclose($fp);
ver('alumnos', $porDni);
ver('nota de 87654321X', $porDni['87654321X']['nota']);   // "6" (string)

// ---------------------------------------------------------------------------------------------
// parse_ini_file — lee un fichero de configuración .ini y lo convierte en un array
// ENLACE: https://www.php.net/manual/es/function.parse-ini-file.php
// ENUNCIADO EJEMPLO: leer la configuración de la aplicación: datos de la base de datos y opciones.
// NOTA: segundo parámetro true = agrupar por [secciones]. Tercer parámetro:
//   INI_SCANNER_NORMAL (por defecto): todo son strings y "true" pasa a ser "1"
//   INI_SCANNER_TYPED: true/false/null y los números se convierten a su tipo
//   (En el material base aparece parse_ini_file($f, INI_SCANNER_RAW): es incorrecto, el modo va
//   en el TERCER parámetro.)
// ---------------------------------------------------------------------------------------------
titulo('parse_ini_file');
$ini           = rutaDatos('config.ini');
file_put_contents($ini, <<<INI
; Configuración de la tienda
[bd]
host = localhost
puerto = 3306
usuario = tienda

[aplicacion]
nombre = "Bar Baio"
depuracion = true
iva = 0.21
INI);
$normal        = parse_ini_file($ini, true);
$tipado        = parse_ini_file($ini, true, INI_SCANNER_TYPED);
ver('con secciones (normal)', $normal);
ver('depuracion (normal / typed)', [$normal['aplicacion']['depuracion'], $tipado['aplicacion']['depuracion']]);   // ["1", true]
ver('puerto (typed)', $tipado['bd']['puerto']);       // 3306 (int)

// ---------------------------------------------------------------------------------------------
// fscanf — lee una línea y la interpreta con un formato (como sscanf). Devuelve un array o false.
// ENLACE: https://www.php.net/manual/es/function.fscanf.php
// ENUNCIADO EJEMPLO: leer las temperaturas de una estación meteorológica, guardadas con un
//   formato fijo "fecha ciudad temperatura", y calcular la media.
// NOTA: %s lee una palabra (hasta un espacio), %d un entero, %f un decimal.
// ---------------------------------------------------------------------------------------------
titulo('fscanf');
$temperaturas  = rutaDatos('temperaturas.txt');
file_put_contents($temperaturas, "2026-10-14 Vigo 18.5\n2026-10-15 Vigo 17.0\n2026-10-16 Vigo 15.5\n");
$fp            = fopen($temperaturas, 'r');
$valores       = [];
while (($datos = fscanf($fp, "%s %s %f")) !== false) {
    [$fecha, $ciudad, $grados] = $datos;
    $valores[$fecha] = $grados;
}
fclose($fp);
ver('temperaturas', $valores);
ver('media', array_sum($valores) / count($valores));   // 17

// ---------------------------------------------------------------------------------------------
// JSON + file_put_contents / file_get_contents — guardar y recuperar estructuras de datos
// ENLACE: https://www.php.net/manual/es/function.json-encode.php
// ENLACE: https://www.php.net/manual/es/function.json-decode.php
// ENUNCIADO EJEMPLO: guardar una lista de tareas (array de arrays) en un fichero JSON, marcar una
//   tarea como hecha y volver a guardarla.
// NOTA: JSON_PRETTY_PRINT la escribe legible y JSON_UNESCAPED_UNICODE deja las tildes como tales.
//   json_decode($texto, true) devuelve arrays asociativos (sin true, objetos).
// ---------------------------------------------------------------------------------------------
titulo('JSON');
$json          = rutaDatos('tareas.json');
$tareas        = [
    ['texto' => 'Estudiar ficheros', 'hecha' => false],
    ['texto' => 'Hacer la práctica del Bar Baio', 'hecha' => false],
];
file_put_contents($json, json_encode($tareas, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

$leidas        = json_decode(file_get_contents($json), true);
$leidas[0]['hecha'] = true;
file_put_contents($json, json_encode($leidas, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
ver('fichero JSON', file_get_contents($json));
ver('¿primera tarea hecha?', json_decode(file_get_contents($json), true)[0]['hecha']);   // true

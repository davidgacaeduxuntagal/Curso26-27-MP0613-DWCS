<?php
/*
 * e05-ficheros_problema_solucion.php
 *  (FICHERO CON LAS SOLUCIONES)
 *  Problemas de: fputcsv, fgetcsv, parse_ini_file, fscanf, JSON con ficheros
 *  (repasa los ejemplos de ../e05-ficheros.php)
 *
 *  Resuelve cada problema donde se indica y ejecútalo en modo depuración para comprobarlo.
 */
require_once __DIR__ . '/../utilidades.php';

// =============================================================================================
// PROBLEMA 1. Libro de opiniones en CSV (práctica 1 del material base)
//  Guarda las opiniones en p05_opiniones.csv con fputcsv (fecha, nombre, email, opinión). Una de
//  las opiniones tiene comas y un salto de línea. Después léelas con fgetcsv y comprueba que la
//  opinión con salto de línea se recupera entera.
// =============================================================================================
titulo('Problema 1');
$opiniones = [
    ['Ana', 'ana@x.gal', 'Muy buen café'],
    ['Brais', '', "El pulpo, espectacular.\nVolveremos, seguro"],
];
$csv = rutaDatos('p05_opiniones.csv');
// completa aquí tu solución
// ---- SOLUCIÓN ----
$fp = fopen($csv, 'w');
foreach ($opiniones as [$nombre, $email, $texto]) {
    fputcsv($fp, [date('Y-m-d H:i'), $nombre, $email, $texto]);
}
fclose($fp);

$fp = fopen($csv, 'r');
$leidas = [];
while (($fila = fgetcsv($fp)) !== false) {
    $leidas[] = $fila;
}
fclose($fp);
ver('número de opiniones', count($leidas));     // 2 (aunque el fichero tenga 3 líneas)
ver('opinión de Brais', $leidas[1][3]);

// =============================================================================================
// PROBLEMA 2. Exportar para Excel
//  Escribe una función exportarCsv(array $filas, string $ruta): void que escriba un CSV con ";"
//  como separador. Úsala con el array de productos y comprueba con fgetcsv el precio de la Tortilla.
// =============================================================================================
titulo('Problema 2');
$productos = [['codigo', 'producto', 'precio'], ['CAF01', 'Café', '1,50'], ['TOR01', 'Tortilla', '3,20']];
// completa aquí tu solución
// ---- SOLUCIÓN ----
function exportarCsv(array $filas, string $ruta): void
{
    $fp = fopen($ruta, 'w');
    foreach ($filas as $fila) {
        fputcsv($fp, $fila, ';');
    }
    fclose($fp);
}
exportarCsv($productos, rutaDatos('p05_productos.csv'));
$fp = fopen(rutaDatos('p05_productos.csv'), 'r');
$cabecera = fgetcsv($fp, null, ';');
$precioTortilla = null;
while (($fila = fgetcsv($fp, null, ';')) !== false) {
    $producto = array_combine($cabecera, $fila);
    if ($producto['producto'] === 'Tortilla') {
        $precioTortilla = $producto['precio'];
    }
}
fclose($fp);
ver('precio de la Tortilla', $precioTortilla);   // "3,20"

// =============================================================================================
// PROBLEMA 3. Configuración con valores por defecto
//  Lee p05_config.ini (con secciones y tipos). Si falta alguna opción, usa los valores por defecto
//  de $porDefecto (pista: array_replace_recursive).
// =============================================================================================
titulo('Problema 3');
$ini = rutaDatos('p05_config.ini');
file_put_contents($ini, "[web]\ntitulo = \"Bar Baio\"\nmantenimiento = false\n\n[correo]\nservidor = smtp.example.com\n");
$porDefecto = [
    'web'    => ['titulo' => 'Mi web', 'mantenimiento' => false, 'elementos_por_pagina' => 10],
    'correo' => ['servidor' => 'localhost', 'puerto' => 25],
];
// completa aquí tu solución
// ---- SOLUCIÓN ----
$leida  = parse_ini_file($ini, true, INI_SCANNER_TYPED);
$config = array_replace_recursive($porDefecto, $leida);
ver('configuración final', $config);
ver('elementos por página', $config['web']['elementos_por_pagina']);   // 10 (por defecto)
ver('¿mantenimiento?', $config['web']['mantenimiento']);               // false (bool)

// =============================================================================================
// PROBLEMA 4. Fichero de puntuaciones con formato fijo
//  Cada línea de p05_ranking.txt tiene "puesto jugador puntos". Léelo con fscanf y obtén el
//  jugador con más puntos y la suma total.
// =============================================================================================
titulo('Problema 4');
$ranking = rutaDatos('p05_ranking.txt');
file_put_contents($ranking, "1 uxia 1520\n2 xoan 1380\n3 iria 1610\n");
// completa aquí tu solución
// ---- SOLUCIÓN ----
$fp = fopen($ranking, 'r');
$mejor = null;
$total = 0;
while (($datos = fscanf($fp, "%d %s %d")) !== false) {
    [$puesto, $jugador, $puntos] = $datos;
    $total += $puntos;
    if ($mejor === null || $puntos > $mejor[1]) {
        $mejor = [$jugador, $puntos];
    }
}
fclose($fp);
ver('mejor', $mejor);     // ["iria", 1610]
ver('total', $total);     // 4510

// =============================================================================================
// PROBLEMA 5. Agenda en JSON
//  Escribe dos funciones: cargarAgenda(): array (lee p05_agenda.json o devuelve [] si no existe) y
//  guardarAgenda(array $agenda): void. Añade dos contactos, guarda, vuelve a cargar y elimina uno.
// =============================================================================================
titulo('Problema 5');
@unlink(rutaDatos('p05_agenda.json'));
// completa aquí tu solución
// ---- SOLUCIÓN ----
function cargarAgenda(): array
{
    $ruta = rutaDatos('p05_agenda.json');
    return is_file($ruta) ? json_decode(file_get_contents($ruta), true) : [];
}

function guardarAgenda(array $agenda): void
{
    file_put_contents(rutaDatos('p05_agenda.json'), json_encode($agenda, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE), LOCK_EX);
}

$agenda = cargarAgenda();
$agenda['Ana'] = '612345678';
$agenda['Brais'] = '698765432';
guardarAgenda($agenda);
$agenda = cargarAgenda();
unset($agenda['Ana']);
guardarAgenda($agenda);
ver('agenda final', cargarAgenda());   // ['Brais' => '698765432']

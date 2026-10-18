<?php
/*
 * e05-ficheros_problema.php
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

// =============================================================================================
// PROBLEMA 2. Exportar para Excel
//  Escribe una función exportarCsv(array $filas, string $ruta): void que escriba un CSV con ";"
//  como separador. Úsala con el array de productos y comprueba con fgetcsv el precio de la Tortilla.
// =============================================================================================
titulo('Problema 2');
$productos = [['codigo', 'producto', 'precio'], ['CAF01', 'Café', '1,50'], ['TOR01', 'Tortilla', '3,20']];
// completa aquí tu solución

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

// =============================================================================================
// PROBLEMA 4. Fichero de puntuaciones con formato fijo
//  Cada línea de p05_ranking.txt tiene "puesto jugador puntos". Léelo con fscanf y obtén el
//  jugador con más puntos y la suma total.
// =============================================================================================
titulo('Problema 4');
$ranking = rutaDatos('p05_ranking.txt');
file_put_contents($ranking, "1 uxia 1520\n2 xoan 1380\n3 iria 1610\n");
// completa aquí tu solución

// =============================================================================================
// PROBLEMA 5. Agenda en JSON
//  Escribe dos funciones: cargarAgenda(): array (lee p05_agenda.json o devuelve [] si no existe) y
//  guardarAgenda(array $agenda): void. Añade dos contactos, guarda, vuelve a cargar y elimina uno.
// =============================================================================================
titulo('Problema 5');
@unlink(rutaDatos('p05_agenda.json'));
// completa aquí tu solución

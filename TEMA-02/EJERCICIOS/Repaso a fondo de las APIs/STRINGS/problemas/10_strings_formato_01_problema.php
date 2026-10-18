<?php
/*
 * 10_strings_formato_01_problema.php
 *  Problemas de: printf, sprintf, vprintf, vsprintf, fprintf, vfprintf, sscanf, str_getcsv
 *  (repasa los ejemplos de ../10_strings_formato_01.php)
 *
 *  Resuelve cada problema donde se indica y ejecútalo en modo depuración para comprobarlo.
 */
require_once __DIR__ . '/../utilidades.php';

// =============================================================================================
// PROBLEMA 1. Tabla de notas alineada
//  Escribe con printf una tabla con el nombre alineado a la izquierda en 12 caracteres y la nota
//  alineada a la derecha en 6 caracteres con 2 decimales. Añade una línea final con la media.
// =============================================================================================
titulo('Problema 1');
$notas = ["Brais" => 6.5, "Uxia" => 9.25, "Xoan" => 4];
// completa aquí tu solución

// =============================================================================================
// PROBLEMA 2. Duración de una película
//  Convierte una duración en minutos al formato "2h 05min" con sprintf.
// =============================================================================================
titulo('Problema 2');
$minutos = 125;
// completa aquí tu solución

// =============================================================================================
// PROBLEMA 3. Mensajes con plantillas
//  Las plantillas de mensajes y sus datos están en arrays. Genera cada mensaje con vsprintf.
// =============================================================================================
titulo('Problema 3');
$mensajes = [
    ["Hola %s, tienes %d mensajes nuevos", ["Ana", 3]],
    ["Tu pedido %s llegará el %02d/%02d", ["F2026-37", 7, 11]],
];
// completa aquí tu solución

// =============================================================================================
// PROBLEMA 4. Leer un registro (log)
//  Cada línea del log tiene el formato "AAAA-MM-DD nivel mensaje". Con sscanf obtén el año, el
//  mes, el día y el nivel ("%d-%d-%d %s") de cada línea, y escribe con fprintf en un stream de
//  memoria solo las líneas de nivel ERROR con el formato "DD/MM/AAAA: línea completa".
// =============================================================================================
titulo('Problema 4');
$log = ["2026-10-16 INFO arranque", "2026-10-16 ERROR sin conexión", "2026-10-17 ERROR disco lleno"];
// completa aquí tu solución

// =============================================================================================
// PROBLEMA 5. Importar un CSV
//  Convierte las líneas CSV (separadas por ";") en un array de alumnos con las claves "nombre",
//  "ciudad" y "nota" (la nota como número decimal, cambiando la coma por punto).
// =============================================================================================
titulo('Problema 5');
$csv = [
    'Ana Pérez;"Vigo; Pontevedra";8,5',
    'Brais Otero;Lugo;6',
];
// completa aquí tu solución

<?php
/*
 * 10_strings_formato_01_problema_solucion.php
 *  (FICHERO CON LAS SOLUCIONES)
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
// ---- SOLUCIÓN ----
foreach ($notas as $nombre => $nota) {
    printf("%-12s%6.2f" . PHP_EOL, $nombre, $nota);
}
printf("%-12s%6.2f" . PHP_EOL, "MEDIA", array_sum($notas) / count($notas));

// =============================================================================================
// PROBLEMA 2. Duración de una película
//  Convierte una duración en minutos al formato "2h 05min" con sprintf.
// =============================================================================================
titulo('Problema 2');
$minutos = 125;
// completa aquí tu solución
// ---- SOLUCIÓN ----
$duracion = sprintf("%dh %02dmin", intdiv($minutos, 60), $minutos % 60);
ver('duración', $duracion);       // "2h 05min"

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
// ---- SOLUCIÓN ----
$generados = [];
foreach ($mensajes as [$plantilla, $valores]) {
    $generados[] = vsprintf($plantilla, $valores);
}
ver('mensajes', $generados);   // ["Hola Ana, tienes 3 mensajes nuevos", "Tu pedido F2026-37 llegará el 07/11"]

// =============================================================================================
// PROBLEMA 4. Leer un registro (log)
//  Cada línea del log tiene el formato "AAAA-MM-DD nivel mensaje". Con sscanf obtén el año, el
//  mes, el día y el nivel ("%d-%d-%d %s") de cada línea, y escribe con fprintf en un stream de
//  memoria solo las líneas de nivel ERROR con el formato "DD/MM/AAAA: línea completa".
// =============================================================================================
titulo('Problema 4');
$log = ["2026-10-16 INFO arranque", "2026-10-16 ERROR sin conexión", "2026-10-17 ERROR disco lleno"];
// completa aquí tu solución
// ---- SOLUCIÓN ----
$errores = fopen("php://memory", "w+");
foreach ($log as $linea) {
    [$anio, $mes, $dia, $nivel] = sscanf($linea, "%d-%d-%d %s");
    if ($nivel === "ERROR") {
        fprintf($errores, "%02d/%02d/%04d: %s\n", $dia, $mes, $anio, $linea);
    }
}
rewind($errores);
ver('errores', stream_get_contents($errores));
fclose($errores);

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
// ---- SOLUCIÓN ----
$alumnos = [];
foreach ($csv as $linea) {
    [$nombre, $ciudad, $nota] = str_getcsv($linea, ";");
    $alumnos[] = ['nombre' => $nombre, 'ciudad' => $ciudad, 'nota' => (float) str_replace(",", ".", $nota)];
}
ver('alumnos', $alumnos);

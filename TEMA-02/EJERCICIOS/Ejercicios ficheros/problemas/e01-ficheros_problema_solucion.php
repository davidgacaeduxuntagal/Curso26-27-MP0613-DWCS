<?php
/*
 * e01-ficheros_problema_solucion.php
 *  (FICHERO CON LAS SOLUCIONES)
 *  Problemas de: file_put_contents, file_get_contents, file, file_exists, filesize, readfile
 *  (repasa los ejemplos de ../e01-ficheros.php)
 *
 *  Resuelve cada problema donde se indica y ejecútalo en modo depuración para comprobarlo.
 *  Los ficheros se crean en la carpeta ../datos (con el prefijo p01_).
 */
require_once __DIR__ . '/../utilidades.php';

// =============================================================================================
// PROBLEMA 1. Diario de clase
//  Guarda en p01_diario.txt una línea con la fecha de hoy y el texto "Hoy hemos visto ficheros".
//  Cada vez que ejecutes el script se debe AÑADIR una línea nueva (no borrar las anteriores).
//  Muestra cuántas líneas tiene el diario.
// =============================================================================================
titulo('Problema 1');
$diario = rutaDatos('p01_diario.txt');
// completa aquí tu solución
// ---- SOLUCIÓN ----
file_put_contents($diario, date('d/m/Y') . " - Hoy hemos visto ficheros\n", FILE_APPEND);
$lineas = file($diario, FILE_IGNORE_NEW_LINES);
ver('líneas del diario', count($lineas));     // crece en 1 cada vez que se ejecuta

// =============================================================================================
// PROBLEMA 2. Lectura segura
//  Escribe una función leerTexto(string $ruta): string que devuelva el contenido del fichero, o el
//  texto "(el fichero no existe)" si no existe, SIN que aparezca ningún aviso (Warning).
// =============================================================================================
titulo('Problema 2');
// completa aquí tu solución
// ---- SOLUCIÓN ----
function leerTexto(string $ruta): string
{
    if (!file_exists($ruta)) {
        return "(el fichero no existe)";
    }
    return file_get_contents($ruta);
}
ver('existe', leerTexto($diario) !== "(el fichero no existe)");    // true
ver('no existe', leerTexto(rutaDatos('p01_fantasma.txt')));        // "(el fichero no existe)"

// =============================================================================================
// PROBLEMA 3. Palabra más larga
//  El fichero p01_palabras.txt tiene una palabra por línea (lo creamos aquí). Léelo con file() y
//  obtén la palabra más larga (en caracteres, no en bytes).
// =============================================================================================
titulo('Problema 3');
$palabras = rutaDatos('p01_palabras.txt');
file_put_contents($palabras, "sol\n\ncarballeira\nmar\nesmorga\n\n");
// completa aquí tu solución
// ---- SOLUCIÓN ----
$lista     = file($palabras, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
$masLarga  = '';
foreach ($lista as $palabra) {
    if (mb_strlen($palabra) > mb_strlen($masLarga)) {
        $masLarga = $palabra;
    }
}
ver('palabras', $lista);
ver('más larga', $masLarga);       // "carballeira"

// =============================================================================================
// PROBLEMA 4. Tamaño legible
//  Escribe una función tamanoLegible(string $ruta): string que devuelva el tamaño de un fichero
//  como "123 bytes", "4,5 KB" o "1,2 MB" (1 KB = 1024 bytes). Pruébala con este propio fichero
//  (__FILE__) y con un fichero de 3000 bytes que crees con str_repeat.
// =============================================================================================
titulo('Problema 4');
// completa aquí tu solución
// ---- SOLUCIÓN ----
function tamanoLegible(string $ruta): string
{
    $bytes = filesize($ruta);
    if ($bytes < 1024) {
        return "$bytes bytes";
    }
    if ($bytes < 1024 * 1024) {
        return number_format($bytes / 1024, 1, ',', '.') . " KB";
    }
    return number_format($bytes / (1024 * 1024), 1, ',', '.') . " MB";
}
$grande = rutaDatos('p01_grande.txt');
file_put_contents($grande, str_repeat("x", 3000));
ver('este fichero', tamanoLegible(__FILE__));
ver('fichero de 3000 bytes', tamanoLegible($grande));   // "2,9 KB"

// =============================================================================================
// PROBLEMA 5. Plantilla de correo
//  En p01_plantilla.txt hay una plantilla con marcadores {nombre} y {pedido}. Léela, sustituye los
//  marcadores y guarda el resultado en p01_correo_ana.txt. Después muéstralo con readfile.
// =============================================================================================
titulo('Problema 5');
$plantilla = rutaDatos('p01_plantilla.txt');
file_put_contents($plantilla, "Hola {nombre}:\nTu pedido {pedido} ya está en camino.\nGracias por comprar en el Bar Baio.\n");
// completa aquí tu solución
// ---- SOLUCIÓN ----
$texto  = str_replace(['{nombre}', '{pedido}'], ['Ana', 'F2026-0037'], file_get_contents($plantilla));
$correo = rutaDatos('p01_correo_ana.txt');
file_put_contents($correo, $texto);
readfile($correo);

<?php
/*
 * 07_strings_busqueda_01_problema_solucion.php
 *  (FICHERO CON LAS SOLUCIONES)
 *  Problemas de: str_contains, str_starts_with, str_ends_with, strpos, stripos, strrpos, strripos
 *  (repasa los ejemplos de ../07_strings_busqueda_01.php)
 *
 *  Resuelve cada problema donde se indica y ejecútalo en modo depuración para comprobarlo.
 */
require_once __DIR__ . '/../utilidades.php';

// =============================================================================================
// PROBLEMA 1. Clasificar URLs
//  Para cada URL indica si es segura (empieza por "https://"), si es una imagen (termina en
//  ".jpg" o ".png") y si pertenece al dominio "iesrodeira.gal" (lo contiene).
// =============================================================================================
titulo('Problema 1');
$urls = [
    "https://www.iesrodeira.gal/logo.png",
    "http://example.com/index.html",
    "https://example.com/foto.jpg",
];
// completa aquí tu solución
// ---- SOLUCIÓN ----
$clasificacion = [];
foreach ($urls as $url) {
    $clasificacion[$url] = [
        'segura'  => str_starts_with($url, "https://"),
        'imagen'  => str_ends_with($url, ".jpg") || str_ends_with($url, ".png"),
        'rodeira' => str_contains($url, "iesrodeira.gal"),
    ];
}
ver('clasificación', $clasificacion);

// =============================================================================================
// PROBLEMA 2. El error del 0
//  Este código quiere comprobar si el mensaje empieza por "ERROR", pero falla. ¿Por qué?
//  Corrígelo en $correcto.
// =============================================================================================
titulo('Problema 2');
$log      = "ERROR: base de datos no disponible";
$erroneo  = strpos($log, "ERROR") ? "hay error" : "todo bien";    // dice "todo bien" (¡mal!)
ver('código erróneo', $erroneo);
// ¿por qué falla? corrígelo
// ---- SOLUCIÓN ----
// strpos devuelve 0 (posición inicial), y 0 se evalúa como false en el operador ternario.
$correcto = strpos($log, "ERROR") !== false ? "hay error" : "todo bien";
ver('código correcto', $correcto);     // "hay error"

// =============================================================================================
// PROBLEMA 3. Todas las posiciones
//  Obtén TODAS las posiciones en las que aparece la palabra "php" (sin distinguir mayúsculas)
//  usando stripos con su tercer parámetro (desde dónde buscar) dentro de un bucle.
// =============================================================================================
titulo('Problema 3');
$texto = "PHP es un lenguaje. Con php se hacen webs. Php mola.";
// completa aquí tu solución
// ---- SOLUCIÓN ----
$posiciones = [];
$pos        = stripos($texto, "php");
while ($pos !== false) {
    $posiciones[] = $pos;
    $pos          = stripos($texto, "php", $pos + 1);
}
ver('posiciones', $posiciones);        // [0, 24, 43]

// =============================================================================================
// PROBLEMA 4. Ruta y nombre de fichero
//  De la ruta completa, obtén la carpeta, el nombre del fichero sin extensión y la extensión,
//  usando strrpos (dos veces: para "/" y para ".").
// =============================================================================================
titulo('Problema 4');
$ruta = "/var/www/html/tema2/informe.final.pdf";
// completa aquí tu solución
// ---- SOLUCIÓN ----
$ultimaBarra = strrpos($ruta, "/");
$ultimoPunto = strrpos($ruta, ".");
$carpeta     = substr($ruta, 0, $ultimaBarra);                                   // "/var/www/html/tema2"
$nombre      = substr($ruta, $ultimaBarra + 1, $ultimoPunto - $ultimaBarra - 1); // "informe.final"
$extension   = substr($ruta, $ultimoPunto + 1);                                  // "pdf"
ver('carpeta', $carpeta);
ver('nombre', $nombre);
ver('extensión', $extension);

// =============================================================================================
// PROBLEMA 5. Último aviso de un log
//  ¿Qué texto hay después del ÚLTIMO "aviso" (sin distinguir mayúsculas) del log? Usa strripos.
// =============================================================================================
titulo('Problema 5');
$log = "Aviso: disco al 80%. Todo ok. AVISO: disco al 95%. Revisar.";
// completa aquí tu solución
// ---- SOLUCIÓN ----
$ultimo = substr($log, strripos($log, "aviso"));
ver('último aviso', $ultimo);          // "AVISO: disco al 95%. Revisar."

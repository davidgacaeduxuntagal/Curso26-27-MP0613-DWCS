<?php
/*
 * 07_strings_busqueda_01_problema.php
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

// =============================================================================================
// PROBLEMA 3. Todas las posiciones
//  Obtén TODAS las posiciones en las que aparece la palabra "php" (sin distinguir mayúsculas)
//  usando stripos con su tercer parámetro (desde dónde buscar) dentro de un bucle.
// =============================================================================================
titulo('Problema 3');
$texto = "PHP es un lenguaje. Con php se hacen webs. Php mola.";
// completa aquí tu solución

// =============================================================================================
// PROBLEMA 4. Ruta y nombre de fichero
//  De la ruta completa, obtén la carpeta, el nombre del fichero sin extensión y la extensión,
//  usando strrpos (dos veces: para "/" y para ".").
// =============================================================================================
titulo('Problema 4');
$ruta = "/var/www/html/tema2/informe.final.pdf";
// completa aquí tu solución

// =============================================================================================
// PROBLEMA 5. Último aviso de un log
//  ¿Qué texto hay después del ÚLTIMO "aviso" (sin distinguir mayúsculas) del log? Usa strripos.
// =============================================================================================
titulo('Problema 5');
$log = "Aviso: disco al 80%. Todo ok. AVISO: disco al 95%. Revisar.";
// completa aquí tu solución

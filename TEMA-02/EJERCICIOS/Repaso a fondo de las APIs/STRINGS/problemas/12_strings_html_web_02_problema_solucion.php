<?php
/*
 * 12_strings_html_web_02_problema_solucion.php
 *  (FICHERO CON LAS SOLUCIONES)
 *  Problemas de: addslashes, stripslashes, addcslashes, stripcslashes
 *  (repasa los ejemplos de ../12_strings_html_web_02.php)
 *
 *  Resuelve cada problema donde se indica y ejecútalo en modo depuración para comprobarlo.
 */
require_once __DIR__ . '/../utilidades.php';

// =============================================================================================
// PROBLEMA 1. Generar código JavaScript
//  Genera la línea de JavaScript  var titulo = '...';  con el título dado, de forma que las
//  comillas del título no rompan la cadena de JavaScript.
// =============================================================================================
titulo('Problema 1');
$tituloLibro = "L'Île de \"Robinson\"";
// completa aquí tu solución
// ---- SOLUCIÓN ----
$js = "var titulo = '" . addslashes($tituloLibro) . "';";
ver('JavaScript', $js);     // var titulo = 'L\'Île de \"Robinson\"';

// =============================================================================================
// PROBLEMA 2. Datos con barras de más
//  Unos datos antiguos se guardaron con barras añadidas. Recupera los textos originales.
// =============================================================================================
titulo('Problema 2');
$guardados = ["O\\'Neill", "Bar \\\"Pepe\\\"", "C:\\\\datos"];
// completa aquí tu solución
// ---- SOLUCIÓN ----
$originales = array_map('stripslashes', $guardados);
ver('originales', $originales);       // ["O'Neill", 'Bar "Pepe"', "C:\datos"]

// =============================================================================================
// PROBLEMA 3. Búsqueda con LIKE
//  Para buscar en SQL con LIKE '%texto%', hay que escapar en el texto del usuario los comodines
//  "%" y "_" (y la propia barra). Genera el patrón para cada búsqueda.
// =============================================================================================
titulo('Problema 3');
$busquedas = ["50%", "mi_fichero", "normal"];
// completa aquí tu solución
// ---- SOLUCIÓN ----
$patrones = [];
foreach ($busquedas as $b) {
    $patrones[] = "%" . addcslashes($b, "%_\\") . "%";
}
ver('patrones LIKE', $patrones);      // ["%50\%%", "%mi\_fichero%", "%normal%"]

// =============================================================================================
// PROBLEMA 4. Mensajes de un fichero de traducciones
//  En un fichero de traducciones los mensajes se guardan con secuencias de escape escritas como
//  texto (\n, \t). Conviértelas en caracteres reales y cuenta cuántas líneas tiene cada mensaje.
// =============================================================================================
titulo('Problema 4');
$traducciones = ['bienvenida' => 'Hola\nBienvenido al curso', 'pie' => 'Gracias\n\tEl equipo\nIES Rodeira'];
// completa aquí tu solución
// ---- SOLUCIÓN ----
$lineas = [];
foreach ($traducciones as $clave => $mensaje) {
    $real           = stripcslashes($mensaje);
    $lineas[$clave] = substr_count($real, "\n") + 1;
}
ver('líneas por mensaje', $lineas);   // ['bienvenida' => 2, 'pie' => 3]

// =============================================================================================
// PROBLEMA 5. Hacer visibles los caracteres invisibles
//  Un campo de texto parece "Ana" pero la comparación con "Ana" falla. ¿Cómo harías para ver
//  qué caracteres invisibles contiene? (pista: addcslashes con el rango de caracteres de control)
// =============================================================================================
titulo('Problema 5');
$campo = "Ana\t\r\n";
ver('¿igual a "Ana"?', $campo === "Ana");   // false
// ¿cómo lo harías?
// ---- SOLUCIÓN ----
$visible = addcslashes($campo, "\0..\37");
ver('caracteres visibles', $visible);      // "Ana\t\r\n"

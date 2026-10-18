<?php
/*
 * 05_strings_comparacion_01_problema.php
 *  Problemas de: strcmp, strcasecmp, strncmp, strncasecmp, substr_compare, strcoll
 *  (repasa los ejemplos de ../05_strings_comparacion_01.php)
 *
 *  Resuelve cada problema donde se indica y ejecútalo en modo depuración para comprobarlo.
 */
require_once __DIR__ . '/../utilidades.php';

// =============================================================================================
// PROBLEMA 1. ¿Antes o después?
//  Usando strcmp, escribe un mensaje que diga si $palabra1 va antes, después o es igual que
//  $palabra2 en orden alfabético (ASCII). Prueba después con "casa" y "Casa".
// =============================================================================================
titulo('Problema 1');
$palabra1 = "manzana";
$palabra2 = "melocotón";
// completa aquí tu solución

// =============================================================================================
// PROBLEMA 2. Buscar un usuario sin distinguir mayúsculas
//  Comprueba si el usuario que intenta iniciar sesión existe en la lista, sin distinguir
//  mayúsculas. No uses strtolower: usa strcasecmp.
// =============================================================================================
titulo('Problema 2');
$registrados = ["AnaPerez", "BraisOtero", "CarmenNunez"];
$login       = "braisotero";
// completa aquí tu solución

// =============================================================================================
// PROBLEMA 3. Teléfonos fijos de Galicia
//  Los teléfonos fijos de las provincias gallegas empiezan por 981 (A Coruña), 982 (Lugo),
//  988 (Ourense) o 986 (Pontevedra). Usando strncmp, obtén la provincia de cada teléfono, o
//  "fuera de Galicia".
// =============================================================================================
titulo('Problema 3');
$prefijos  = ["981" => "A Coruña", "982" => "Lugo", "988" => "Ourense", "986" => "Pontevedra"];
$telefonos = ["986123456", "915551234", "982000111"];
// completa aquí tu solución

// =============================================================================================
// PROBLEMA 4. Ficheros de imagen
//  De la lista de ficheros subidos, quédate solo con los que tienen extensión ".jpg" o ".png",
//  sin distinguir mayúsculas. Usa substr_compare con posición negativa.
// =============================================================================================
titulo('Problema 4');
$ficheros = ["foto.JPG", "documento.pdf", "logo.png", "notas.txt", "playa.Png"];
// completa aquí tu solución

// =============================================================================================
// PROBLEMA 5. Ordenar comprobando el idioma
//  Ordena la lista de pueblos con usort usando strcmp y, después, usando strcoll con el idioma
//  español activado. ¿Dónde queda "Ávila" en cada caso?
// =============================================================================================
titulo('Problema 5');
$pueblos = ["Vigo", "Ávila", "Betanzos", "Ourense"];
// completa aquí tu solución

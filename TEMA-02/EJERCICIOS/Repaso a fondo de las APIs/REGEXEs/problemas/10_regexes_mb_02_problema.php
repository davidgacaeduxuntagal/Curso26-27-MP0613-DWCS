<?php
/*
 * 10_regexes_mb_02_problema.php
 *  Problemas de: mb_regex_set_options, mb_ereg_search_init, mb_ereg_search, mb_ereg_search_pos,
 *                mb_ereg_search_regs, mb_ereg_search_getregs, mb_ereg_search_getpos,
 *                mb_ereg_search_setpos
 *  (repasa los ejemplos de ../10_regexes_mb_02.php)
 *
 *  Resuelve cada problema donde se indica y ejecútalo en modo depuración para comprobarlo.
 */
require_once __DIR__ . '/../utilidades.php';

mb_regex_encoding("UTF-8");

// =============================================================================================
// PROBLEMA 1. Contar apariciones sin distinguir mayúsculas
//  Cuenta cuántas veces aparece la palabra "año" (como "año", "Año" o "AÑO") en el texto, usando
//  mb_ereg_search con la opción "i" en mb_ereg_search_init (tercer parámetro).
// =============================================================================================
titulo('Problema 1');
$texto = "Feliz AÑO nuevo. Este año será mejor que el año pasado. ¡Año a año!";
// completa aquí tu solución

// =============================================================================================
// PROBLEMA 2. Variables de una plantilla
//  Obtén con mb_ereg_search_regs los nombres de todas las variables de la plantilla, escritas
//  como {variable}, sin las llaves y sin repetir.
// =============================================================================================
titulo('Problema 2');
$plantilla = "Hola {nombre}, tu pedido {pedido} llegará el {fecha}. Gracias, {nombre}.";
// completa aquí tu solución

// =============================================================================================
// PROBLEMA 3. Resaltar palabras
//  Usando mb_ereg_search_pos (posición y longitud en bytes), construye el texto con las palabras
//  que empiezan por "c" o "C" entre corchetes. Pista: ve copiando trozos con substr.
// =============================================================================================
titulo('Problema 3');
$frase = "Cada camión carga cajas en Coruña";
// completa aquí tu solución

// =============================================================================================
// PROBLEMA 4. Buscar a partir de una marca
//  En un documento, los datos de interés empiezan tras la línea "---". Obtén todas las parejas
//  "clave: valor" que hay DESPUÉS de esa marca usando mb_ereg_search_setpos.
// =============================================================================================
titulo('Problema 4');
$documento = "titulo: borrador\nautor: nadie\n---\nnombre: Ana\nciudad: Vigo\nnota: 9";
// completa aquí tu solución

// =============================================================================================
// PROBLEMA 5. Opciones globales
//  Cambia las opciones por defecto con mb_regex_set_options para que todas las búsquedas sean
//  sin distinguir mayúsculas, comprueba con mb_ereg que "GALICIA" encaja con "galicia", restaura
//  las opciones anteriores y comprueba que ya no encaja.
// =============================================================================================
titulo('Problema 5');
// completa aquí tu solución

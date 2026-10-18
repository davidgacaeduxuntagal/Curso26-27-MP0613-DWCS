<?php
/*
 * 09_regexes_mb_01_problema.php
 *  Problemas de: mb_regex_encoding, mb_ereg, mb_eregi, mb_ereg_match, mb_ereg_replace,
 *                mb_eregi_replace, mb_ereg_replace_callback, mb_split
 *  (repasa los ejemplos de ../09_regexes_mb_01.php)
 *
 *  Recuerda: con mb_ereg los patrones van SIN delimitadores y los grupos en el reemplazo son \1.
 */
require_once __DIR__ . '/../utilidades.php';

mb_regex_encoding("UTF-8");

// =============================================================================================
// PROBLEMA 1. Nombres de pueblos gallegos
//  Comprueba con mb_ereg_match que cada nombre está formado solo por palabras que empiezan por
//  mayúscula, separadas por espacios, pudiendo tener artículos en minúscula ("A", "O", "de", "do",
//  "da"...). Simplificación: letras, espacios y que empiece por mayúscula.
// =============================================================================================
titulo('Problema 1');
$pueblos = ["Santiago de Compostela", "A Coruña", "Ourense", "vigo", "Lugo 2"];
// completa aquí tu solución

// =============================================================================================
// PROBLEMA 2. Código y nombre de un producto
//  Extrae con mb_ereg y grupos con nombre el "codigo" (letras y dígitos) y el "nombre" (resto)
//  de "ELE-042 Cafetera eléctrica pequeña".
// =============================================================================================
titulo('Problema 2');
$linea = "ELE-042 Cafetera eléctrica pequeña";
// completa aquí tu solución

// =============================================================================================
// PROBLEMA 3. Quitar tildes para hacer búsquedas
//  Escribe una función sinTildes(string $t): string que use mb_ereg_replace_callback para cambiar
//  cada vocal con tilde (mayúscula o minúscula) por la misma sin tilde. Úsala para buscar
//  "ACCION" en "Acción de gracias" con mb_eregi.
// =============================================================================================
titulo('Problema 3');
// completa aquí tu solución

// =============================================================================================
// PROBLEMA 4. Formato de fechas
//  Con mb_ereg_replace cambia todas las fechas "dd/mm/aaaa" de un texto al formato "aaaa-mm-dd".
// =============================================================================================
titulo('Problema 4');
$texto = "Matrícula: del 01/09/2026 al 15/09/2026. Inicio: 16/09/2026.";
// completa aquí tu solución

// =============================================================================================
// PROBLEMA 5. Frecuencia de palabras en un texto en español
//  Divide el texto en palabras con mb_split (separadores: espacios y signos de puntuación,
//  incluidos ¡ ¿), pásalas a minúsculas y cuenta cuántas veces aparece cada una.
// =============================================================================================
titulo('Problema 5');
$texto = "¿Mañana? ¡Mañana no! Pasado mañana, quizá.";
// completa aquí tu solución

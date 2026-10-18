<?php
/*
 * 18_strings_i18n_03_problema.php
 *  Problemas de: mb_internal_encoding, mb_strpos, mb_stripos, mb_strrpos, mb_stristr, mb_strrchr,
 *                mb_ord, mb_chr
 *  (repasa los ejemplos de ../18_strings_i18n_03.php)
 *
 *  Resuelve cada problema donde se indica y ejecútalo en modo depuración para comprobarlo.
 */
require_once __DIR__ . '/../utilidades.php';

// =============================================================================================
// PROBLEMA 1. Resaltar una palabra
//  Rodea con "[" y "]" la primera aparición de la palabra buscada (sin distinguir mayúsculas,
//  también con tildes). Usa mb_stripos, mb_strlen y mb_substr.
// =============================================================================================
titulo('Problema 1');
$texto   = "La CANCIÓN del verano es una canción pegadiza";
$buscada = "canción";
// completa aquí tu solución

// =============================================================================================
// PROBLEMA 2. Nombre y apellidos
//  Separa el nombre (primera palabra) y los apellidos (el resto) usando mb_strpos, y el último
//  apellido usando mb_strrpos.
// =============================================================================================
titulo('Problema 2');
$completo = "Begoña Íñiguez Oñate";
// completa aquí tu solución

// =============================================================================================
// PROBLEMA 3. Dominio y extensión
//  De cada email, obtén con mb_stristr la parte desde "@" y, con mb_strrchr, el dominio de nivel
//  superior (".gal", ".es"...).
// =============================================================================================
titulo('Problema 3');
$emails = ["begoña@iesrodeira.gal", "ÍÑIGO@EXAMPLE.ES"];
// completa aquí tu solución

// =============================================================================================
// PROBLEMA 4. Códigos Unicode de una palabra
//  Muestra el código Unicode (en formato U+XXXX) de cada carácter de la palabra.
// =============================================================================================
titulo('Problema 4');
$palabra = "Ñandú€";
// completa aquí tu solución

// =============================================================================================
// PROBLEMA 5. Cifrado César con eñe
//  Cifra el mensaje desplazando 1 posición cada letra del alfabeto español en minúsculas
//  "abcdefghijklmnñopqrstuvwxyz" (la "z" pasa a ser la "a"). Los demás caracteres no cambian.
//  ¿Cómo lo harías con mb_str_split y mb_strpos sobre el alfabeto?
// =============================================================================================
titulo('Problema 5');
$alfabeto = "abcdefghijklmnñopqrstuvwxyz";
$mensaje  = "mañana zarpamos";
// ¿cómo lo harías?

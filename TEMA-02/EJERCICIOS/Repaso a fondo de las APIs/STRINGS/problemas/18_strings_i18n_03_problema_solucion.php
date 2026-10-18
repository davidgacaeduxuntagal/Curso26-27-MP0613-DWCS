<?php
/*
 * 18_strings_i18n_03_problema_solucion.php
 *  (FICHERO CON LAS SOLUCIONES)
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
// ---- SOLUCIÓN ----
$pos = mb_stripos($texto, $buscada);
if ($pos !== false) {
    $largo      = mb_strlen($buscada);
    $resaltado  = mb_substr($texto, 0, $pos) . "[" . mb_substr($texto, $pos, $largo) . "]" . mb_substr($texto, $pos + $largo);
} else {
    $resaltado  = $texto;
}
ver('resaltado', $resaltado);     // "La [CANCIÓN] del verano es una canción pegadiza"

// =============================================================================================
// PROBLEMA 2. Nombre y apellidos
//  Separa el nombre (primera palabra) y los apellidos (el resto) usando mb_strpos, y el último
//  apellido usando mb_strrpos.
// =============================================================================================
titulo('Problema 2');
$completo = "Begoña Íñiguez Oñate";
// completa aquí tu solución
// ---- SOLUCIÓN ----
$primerEspacio = mb_strpos($completo, " ");
$ultimoEspacio = mb_strrpos($completo, " ");
$nombre        = mb_substr($completo, 0, $primerEspacio);     // "Begoña"
$apellidos     = mb_substr($completo, $primerEspacio + 1);    // "Íñiguez Oñate"
$ultimo        = mb_substr($completo, $ultimoEspacio + 1);    // "Oñate"
ver('nombre / apellidos / último', [$nombre, $apellidos, $ultimo]);

// =============================================================================================
// PROBLEMA 3. Dominio y extensión
//  De cada email, obtén con mb_stristr la parte desde "@" y, con mb_strrchr, el dominio de nivel
//  superior (".gal", ".es"...).
// =============================================================================================
titulo('Problema 3');
$emails = ["begoña@iesrodeira.gal", "ÍÑIGO@EXAMPLE.ES"];
// completa aquí tu solución
// ---- SOLUCIÓN ----
$partes = [];
foreach ($emails as $email) {
    $partes[$email] = [mb_stristr($email, "@"), mb_strtolower(mb_strrchr($email, "."))];
}
ver('partes', $partes);    // ["@iesrodeira.gal", ".gal"], ["@EXAMPLE.ES", ".es"]

// =============================================================================================
// PROBLEMA 4. Códigos Unicode de una palabra
//  Muestra el código Unicode (en formato U+XXXX) de cada carácter de la palabra.
// =============================================================================================
titulo('Problema 4');
$palabra = "Ñandú€";
// completa aquí tu solución
// ---- SOLUCIÓN ----
$codigos = [];
foreach (mb_str_split($palabra) as $caracter) {
    $codigos[$caracter] = sprintf("U+%04X", mb_ord($caracter));
}
ver('códigos', $codigos);    // Ñ U+00D1, a U+0061, n U+006E, d U+0064, ú U+00FA, € U+20AC

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
// ---- SOLUCIÓN ----
$total   = mb_strlen($alfabeto);
$cifrado = "";
foreach (mb_str_split($mensaje) as $letra) {
    $pos      = mb_strpos($alfabeto, $letra);
    $cifrado .= $pos === false ? $letra : mb_substr($alfabeto, ($pos + 1) % $total, 1);
}
ver('cifrado', $cifrado);    // "nbobñb absqbnpt"

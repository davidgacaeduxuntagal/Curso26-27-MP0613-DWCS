<?php
/*
 * 17_strings_i18n_02_problema_solucion.php
 *  (FICHERO CON LAS SOLUCIONES)
 *  Problemas de: mb_strlen, mb_substr, mb_strtoupper, mb_strtolower, mb_convert_case,
 *                mb_str_split, mb_strwidth, mb_strimwidth
 *  (repasa los ejemplos de ../17_strings_i18n_02.php)
 *
 *  Resuelve cada problema donde se indica y ejecútalo en modo depuración para comprobarlo.
 */
require_once __DIR__ . '/../utilidades.php';

// =============================================================================================
// PROBLEMA 1. Límite de caracteres de un mensaje
//  Un servicio de mensajes admite como máximo 30 caracteres. Para cada mensaje, indica cuántos
//  caracteres tiene y cuántos le sobran o le faltan. Compara con lo que diría strlen.
// =============================================================================================
titulo('Problema 1');
$mensajes = ["¡Mañana a las 9 en la estación!", "Ok 👍", "Reunión aplazada"];
// completa aquí tu solución
// ---- SOLUCIÓN ----
$informe = [];
foreach ($mensajes as $m) {
    $informe[$m] = ['strlen' => strlen($m), 'mb_strlen' => mb_strlen($m), 'restantes' => 30 - mb_strlen($m)];
}
ver('informe', $informe);

// =============================================================================================
// PROBLEMA 2. Iniciales
//  Obtén las iniciales en mayúsculas de cada nombre: "álvaro núñez ibáñez" -> "ÁNI".
// =============================================================================================
titulo('Problema 2');
$nombres = ["álvaro núñez ibáñez", "óscar pérez", "úrsula iglesias"];
// completa aquí tu solución
// ---- SOLUCIÓN ----
$iniciales = [];
foreach ($nombres as $nombre) {
    $letras = "";
    foreach (explode(" ", $nombre) as $palabra) {
        $letras .= mb_strtoupper(mb_substr($palabra, 0, 1));
    }
    $iniciales[$nombre] = $letras;
}
ver('iniciales', $iniciales);    // ÁNI, ÓP, ÚI

// =============================================================================================
// PROBLEMA 3. Normalizar nombres de calles
//  Los nombres de calle llegan en mayúsculas. Pásalos a formato título (MB_CASE_TITLE) pero con
//  las palabras "de", "del", "la", "las", "los" y "y" en minúsculas.
// =============================================================================================
titulo('Problema 3');
$calle = "AVENIDA DE LA CONSTITUCIÓN Y DEL PUERTO";
// completa aquí tu solución
// ---- SOLUCIÓN ----
$enMinusculas = ["De", "Del", "La", "Las", "Los", "Y"];
$palabras     = explode(" ", mb_convert_case($calle, MB_CASE_TITLE));
foreach ($palabras as $i => $palabra) {
    if ($i > 0 && in_array($palabra, $enMinusculas)) {
        $palabras[$i] = mb_strtolower($palabra);
    }
}
$normalizada = implode(" ", $palabras);
ver('calle', $normalizada);      // "Avenida de la Constitución y del Puerto"

// =============================================================================================
// PROBLEMA 4. Palíndromos con tildes
//  ¿"Dábale arroz a la zorra el abad" es un palíndromo? Quita espacios, pasa a minúsculas y
//  cambia las vocales con tilde por vocales sin tilde. Invierte con mb_str_split (no con strrev).
// =============================================================================================
titulo('Problema 4');
$frase = "Dábale arroz a la zorra el abad";
// completa aquí tu solución
// ---- SOLUCIÓN ----
$limpia       = strtr(mb_strtolower(str_replace(" ", "", $frase)), ["á" => "a", "é" => "e", "í" => "i", "ó" => "o", "ú" => "u"]);
$invertida    = implode("", array_reverse(mb_str_split($limpia)));
$esPalindromo = $limpia === $invertida;
ver('limpia', $limpia);
ver('¿palíndromo?', $esPalindromo);    // true

// =============================================================================================
// PROBLEMA 5. Listado de titulares
//  Muestra los titulares recortados a un ancho de 30 con "…" al final si no caben, y después
//  rellenados con puntos hasta un ancho de 32 para alinear la columna del número de visitas.
//  Pista: str_pad cuenta bytes; calcula los puntos con mb_strwidth.
// =============================================================================================
titulo('Problema 5');
$titulares = ["Llueve en Galicia" => 120, "Inaugurada la nueva estación de autobuses de Pontevedra" => 4580, "El Celta gana 2-0" => 99];
// completa aquí tu solución
// ---- SOLUCIÓN ----
$lineas = [];
foreach ($titulares as $titular => $visitas) {
    $corto    = mb_strimwidth($titular, 0, 30, "…");
    $relleno  = str_repeat(".", 32 - mb_strwidth($corto));
    $lineas[] = $corto . $relleno . sprintf("%5d", $visitas);
}
foreach ($lineas as $linea) {
    echo $linea, PHP_EOL;
}

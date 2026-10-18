<?php
/*
 * 06_strings_comparacion_02_problema.php
 *  Problemas de: strnatcmp, strnatcasecmp, strspn, strcspn, similar_text
 *  (repasa los ejemplos de ../06_strings_comparacion_02.php)
 *
 *  Resuelve cada problema donde se indica y ejecútalo en modo depuración para comprobarlo.
 */
require_once __DIR__ . '/../utilidades.php';

// =============================================================================================
// PROBLEMA 1. Ordenar capítulos
//  Ordena los capítulos de una serie en el orden correcto (natural, sin distinguir mayúsculas).
// =============================================================================================
titulo('Problema 1');
$capitulos = ["Capitulo12.mp4", "capitulo3.mp4", "Capitulo1.mp4", "CAPITULO25.mp4", "capitulo2.mp4"];
// completa aquí tu solución

// =============================================================================================
// PROBLEMA 2. ¿Qué versión es más nueva?
//  Usando strnatcmp, obtén la versión más moderna de la lista.
// =============================================================================================
titulo('Problema 2');
$versiones = ["8.2.12", "8.2.9", "8.10.1", "8.3.0"];
// completa aquí tu solución

// =============================================================================================
// PROBLEMA 3. Validar un código postal
//  Un código postal español tiene exactamente 5 dígitos. Valida cada código usando strspn y strlen.
// =============================================================================================
titulo('Problema 3');
$codigos = ["36380", "3638", "36A80", "363800"];
// completa aquí tu solución

// =============================================================================================
// PROBLEMA 4. Primer separador de una línea
//  Las líneas de un fichero pueden usar como separador ";", "," o tabulador. Usando strcspn,
//  obtén el primer campo de cada línea (todo lo que hay antes del primer separador).
// =============================================================================================
titulo('Problema 4');
$lineas = ["Ana;Pérez;Vigo", "Brais,Otero,Lugo", "Carmen\tNúñez\tOurense"];
// completa aquí tu solución

// =============================================================================================
// PROBLEMA 5. Corrector de ciudades
//  Un usuario escribe mal el nombre de una ciudad. Sugiere la ciudad de la lista que más se
//  parezca (similar_text con porcentaje), pero solo si el parecido es de al menos el 70 %.
// =============================================================================================
titulo('Problema 5');
$ciudades = ["Vigo", "Pontevedra", "Santiago", "Ourense", "Lugo"];
$escrito  = "Pontebedra";
// completa aquí tu solución

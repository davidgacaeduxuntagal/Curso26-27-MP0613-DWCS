<?php
/*
 * 07_regexes_preg_01_problema.php
 *  Problemas de: preg_match, preg_match_all, preg_split, preg_grep, preg_quote, preg_last_error,
 *                preg_last_error_msg
 *  (repasa los ejemplos de ../07_regexes_preg_01.php)
 *
 *  Resuelve cada problema donde se indica y ejecútalo en modo depuración para comprobarlo.
 */
require_once __DIR__ . '/../utilidades.php';

// =============================================================================================
// PROBLEMA 1. Enlaces de una página
//  Extrae con preg_match_all (PREG_SET_ORDER y grupos con nombre) la URL y el texto de todos los
//  enlaces <a href="...">texto</a> del HTML.
// =============================================================================================
titulo('Problema 1');
$html = '<p>Visita <a href="https://www.iesrodeira.gal">el instituto</a> o <a href="/contacto.php">contacta</a>.</p>';
// completa aquí tu solución

// =============================================================================================
// PROBLEMA 2. Suma de los números de un texto
//  Suma todos los números (enteros o con decimales con coma) que aparecen en el texto.
// =============================================================================================
titulo('Problema 2');
$texto = "Compré 3 libros de 12,50 €, 2 cuadernos de 1,75 € y 1 mochila de 25 €";
// completa aquí tu solución

// =============================================================================================
// PROBLEMA 3. Separar una lista escrita a mano
//  Los usuarios escriben listas con separadores variados: comas, punto y coma, "y", barras...
//  Obtén los elementos limpios con preg_split.
// =============================================================================================
titulo('Problema 3');
$lista = "pan, leche;huevos y café / azúcar ,  sal";
// completa aquí tu solución

// =============================================================================================
// PROBLEMA 4. Buscador de contactos
//  El usuario escribe un texto de búsqueda (que puede tener caracteres especiales). Con preg_grep
//  y preg_quote, obtén los contactos cuyo nombre CONTIENE ese texto, sin distinguir mayúsculas.
// =============================================================================================
titulo('Problema 4');
$contactos = ["Ana (trabajo)", "Ana Pérez", "Brais (casa)", "Carmen"];
$busqueda  = "(TRABAJO)";
// completa aquí tu solución

// =============================================================================================
// PROBLEMA 5. Función de búsqueda segura
//  Escribe una función buscar(string $patron, string $texto): string que devuelva "sí", "no" o
//  "ERROR: mensaje" usando preg_last_error_msg. Pruébala con un texto UTF-8 inválido y con un
//  texto correcto. (Usa @ delante de preg_match para que no salga el aviso si el patrón está mal.)
// =============================================================================================
titulo('Problema 5');
$malCodificado = "\xE1rbol";     // "árbol" en ISO-8859-1: no es UTF-8 válido
// completa aquí tu solución

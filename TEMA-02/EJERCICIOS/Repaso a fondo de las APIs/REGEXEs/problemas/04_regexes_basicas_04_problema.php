<?php
/*
 * 04_regexes_basicas_04_problema.php
 *  Problemas de: grupos de captura ( ), grupos anidados y opcionales, grupos no capturadores (?: ),
 *                referencias hacia atrás \1
 *  (repasa los ejemplos de ../04_regexes_basicas_04.php)
 *
 *  Resuelve cada problema donde se indica y ejecútalo en modo depuración para comprobarlo.
 */
require_once __DIR__ . '/../utilidades.php';

// =============================================================================================
// PROBLEMA 1. Partes de un email
//  Con un solo preg_match y grupos de captura, obtén el usuario, el dominio y la extensión
//  (lo que va tras el último punto) de "ana.perez@iesrodeira.gal".
// =============================================================================================
titulo('Problema 1');
$email = "ana.perez@iesrodeira.gal";
// completa aquí tu solución

// =============================================================================================
// PROBLEMA 2. Duración de un vídeo
//  Las duraciones pueden venir como "1:05:30" (horas:minutos:segundos) o "05:30" (sin horas).
//  Captura horas (opcional), minutos y segundos, y calcula la duración total en segundos.
//  Pista: grupo no capturador opcional que contiene el grupo de las horas: (?:(\d+):)?
// =============================================================================================
titulo('Problema 2');
$duraciones = ["1:05:30", "05:30", "12:00:01"];
// completa aquí tu solución

// =============================================================================================
// PROBLEMA 3. Grupos anidados
//  En el código de producto "ES-36-0042-B", captura con grupos anidados: el país y la provincia
//  juntos ("ES-36", grupo 1), el país (2), la provincia (3), el número (4) y la variante (5).
//  Antes de ejecutar, escribe en un comentario qué número tendrá cada grupo.
// =============================================================================================
titulo('Problema 3');
$codigo = "ES-36-0042-B";
// completa aquí tu solución

// =============================================================================================
// PROBLEMA 4. Letras dobles
//  Encuentra con preg_match_all todas las letras repetidas dos veces seguidas en la frase
//  ("ll", "rr", "cc"...). Usa una referencia hacia atrás.
// =============================================================================================
titulo('Problema 4');
$frase = "La lluvia cae en la carretera de Ferrol";
// completa aquí tu solución

// =============================================================================================
// PROBLEMA 5. Etiquetas HTML que se cierran bien
//  Comprueba si cada texto es una etiqueta de apertura, contenido y su etiqueta de cierre
//  correcta: "<p>hola</p>" sí, "<p>hola</div>" no. Usa una referencia hacia atrás numerada.
// =============================================================================================
titulo('Problema 5');
$textos = ["<p>hola</p>", "<div>texto</div>", "<p>hola</div>", "<b>sin cerrar"];
// completa aquí tu solución

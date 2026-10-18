<?php
/*
 * 04_regexes_basicas_04_problema_solucion.php
 *  (FICHERO CON LAS SOLUCIONES)
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
// ---- SOLUCIÓN ----
preg_match('/^([^@]+)@(.+)\.([a-z]+)$/i', $email, $m);
ver('usuario', $m[1]);      // "ana.perez"
ver('dominio', $m[2]);      // "iesrodeira"
ver('extensión', $m[3]);    // "gal"

// =============================================================================================
// PROBLEMA 2. Duración de un vídeo
//  Las duraciones pueden venir como "1:05:30" (horas:minutos:segundos) o "05:30" (sin horas).
//  Captura horas (opcional), minutos y segundos, y calcula la duración total en segundos.
//  Pista: grupo no capturador opcional que contiene el grupo de las horas: (?:(\d+):)?
// =============================================================================================
titulo('Problema 2');
$duraciones = ["1:05:30", "05:30", "12:00:01"];
// completa aquí tu solución
// ---- SOLUCIÓN ----
$segundos = [];
foreach ($duraciones as $d) {
    preg_match('/^(?:(\d+):)?(\d{2}):(\d{2})$/', $d, $m);
    $horas         = $m[1] === '' ? 0 : (int) $m[1];
    $segundos[$d]  = $horas * 3600 + (int) $m[2] * 60 + (int) $m[3];
}
ver('segundos', $segundos);    // 3930, 330, 43201

// =============================================================================================
// PROBLEMA 3. Grupos anidados
//  En el código de producto "ES-36-0042-B", captura con grupos anidados: el país y la provincia
//  juntos ("ES-36", grupo 1), el país (2), la provincia (3), el número (4) y la variante (5).
//  Antes de ejecutar, escribe en un comentario qué número tendrá cada grupo.
// =============================================================================================
titulo('Problema 3');
$codigo = "ES-36-0042-B";
// completa aquí tu solución
// ---- SOLUCIÓN ----
// grupo 1 = ((..)-(..)) se abre primero; 2 = país; 3 = provincia; 4 = número; 5 = variante
preg_match('/^(([A-Z]{2})-(\d{2}))-(\d{4})-([A-Z])$/', $codigo, $m);
ver('$m', $m);

// =============================================================================================
// PROBLEMA 4. Letras dobles
//  Encuentra con preg_match_all todas las letras repetidas dos veces seguidas en la frase
//  ("ll", "rr", "cc"...). Usa una referencia hacia atrás.
// =============================================================================================
titulo('Problema 4');
$frase = "La lluvia cae en la carretera de Ferrol";
// completa aquí tu solución
// ---- SOLUCIÓN ----
preg_match_all('/(\w)\1/', $frase, $m);
ver('dobles', $m[0]);        // ["ll", "rr", "rr"]

// =============================================================================================
// PROBLEMA 5. Etiquetas HTML que se cierran bien
//  Comprueba si cada texto es una etiqueta de apertura, contenido y su etiqueta de cierre
//  correcta: "<p>hola</p>" sí, "<p>hola</div>" no. Usa una referencia hacia atrás numerada.
// =============================================================================================
titulo('Problema 5');
$textos = ["<p>hola</p>", "<div>texto</div>", "<p>hola</div>", "<b>sin cerrar"];
// completa aquí tu solución
// ---- SOLUCIÓN ----
$resultado = [];
foreach ($textos as $t) {
    $resultado[$t] = preg_match('/^<([a-z]+)>.*<\/\1>$/', $t);
}
ver('¿bien cerradas?', $resultado);   // 1, 1, 0, 0

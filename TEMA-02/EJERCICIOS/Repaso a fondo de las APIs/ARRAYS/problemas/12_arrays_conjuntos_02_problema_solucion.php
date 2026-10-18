<?php
/*
 * 12_arrays_conjuntos_02_problema_solucion.php
 *  (FICHERO CON LAS SOLUCIONES)
 *  Problemas de: array_udiff, array_uintersect, array_diff_ukey, array_intersect_ukey,
 *                array_diff_uassoc, array_udiff_assoc
 *  (repasa los ejemplos de ../12_arrays_conjuntos_02.php)
 *
 *  Resuelve cada problema donde se indica y ejecútalo en modo depuración para comprobarlo.
 */
require_once __DIR__ . '/../utilidades.php';

$usuariosWeb = [
    ['email' => "ana@x.gal",    'nombre' => "Ana"],
    ['email' => "BRAIS@x.gal",  'nombre' => "Brais"],
    ['email' => "carmen@x.gal", 'nombre' => "Carmen"],
];
$usuariosApp = [
    ['email' => "brais@x.gal",  'plataforma' => "Android"],
    ['email' => "diego@x.gal",  'plataforma' => "iOS"],
    ['email' => "Ana@X.gal",    'plataforma' => "iOS"],
];

// =============================================================================================
// PROBLEMA 1. Usuarios que solo usan la web
//  Obtén los nombres de los usuarios de la web que NO usan la app. Los emails pueden tener
//  mayúsculas distintas: compara con strcasecmp.
// =============================================================================================
titulo('Problema 1');
// completa aquí tu solución
// ---- SOLUCIÓN ----
$soloWeb = array_udiff($usuariosWeb, $usuariosApp, fn($a, $b) => strcasecmp($a['email'], $b['email']));
ver('solo web', array_column($soloWeb, 'nombre'));    // ["Carmen"]

// =============================================================================================
// PROBLEMA 2. Usuarios de la web que también usan la app
//  Con array_uintersect, obtén los nombres de los usuarios que están en las dos listas.
// =============================================================================================
titulo('Problema 2');
// completa aquí tu solución
// ---- SOLUCIÓN ----
$ambas = array_uintersect($usuariosWeb, $usuariosApp, fn($a, $b) => strcasecmp($a['email'], $b['email']));
ver('en las dos', array_column($ambas, 'nombre'));    // ["Ana", "Brais"]

// =============================================================================================
// PROBLEMA 3. Variables de entorno
//  En Windows los nombres de las variables de entorno no distinguen mayúsculas. ¿Qué variables
//  necesarias no están definidas? ¿Cuáles de las definidas son necesarias?
// =============================================================================================
titulo('Problema 3');
$necesarias = ['DB_HOST' => null, 'DB_USER' => null, 'DB_PASS' => null];
$definidas  = ['db_host' => "localhost", 'Db_User' => "root", 'APP_ENV' => "dev"];
// completa aquí tu solución
// ---- SOLUCIÓN ----
$noDefinidas = array_keys(array_diff_ukey($necesarias, $definidas, 'strcasecmp'));   // [DB_PASS]
$utiles      = array_intersect_ukey($definidas, $necesarias, 'strcasecmp');         // db_host, Db_User
ver('no definidas', $noDefinidas);
ver('definidas y necesarias', $utiles);

// =============================================================================================
// PROBLEMA 4. Cambios en la configuración
//  Compara la configuración nueva con la antigua: los nombres de las opciones no distinguen
//  mayúsculas pero los valores sí. ¿Qué opciones nuevas o modificadas hay?
// =============================================================================================
titulo('Problema 4');
$antigua = ['Idioma' => "es", 'ZONA' => "Europe/Madrid", 'tema' => "claro"];
$nueva   = ['idioma' => "es", 'zona' => "Europe/London", 'TEMA' => "claro", 'fuente' => "grande"];
// completa aquí tu solución
// ---- SOLUCIÓN ----
$cambios = array_diff_uassoc($nueva, $antigua, 'strcasecmp');
ver('opciones nuevas o modificadas', $cambios);   // zona => Europe/London, fuente => grande

// =============================================================================================
// PROBLEMA 5. Notas revisadas
//  Tras una revisión, algunas notas han cambiado. Considera que no hay cambio si la diferencia es
//  menor de 0,05 (redondeos). ¿Qué notas han cambiado realmente? Usa array_udiff_assoc.
// =============================================================================================
titulo('Problema 5');
$antes   = ['Ana' => 7.25, 'Brais' => 4.9, 'Carmen' => 8.0];
$despues = ['Ana' => 7.27, 'Brais' => 5.0, 'Carmen' => 8.0];
// completa aquí tu solución
// ---- SOLUCIÓN ----
$cambiadas = array_udiff_assoc($despues, $antes, fn($a, $b) => abs($a - $b) < 0.05 ? 0 : ($a <=> $b));
ver('notas cambiadas', $cambiadas);     // ['Brais' => 5.0]

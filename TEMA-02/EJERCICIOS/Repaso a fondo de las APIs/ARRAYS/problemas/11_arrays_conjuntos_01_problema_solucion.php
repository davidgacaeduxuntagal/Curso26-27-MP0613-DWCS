<?php
/*
 * 11_arrays_conjuntos_01_problema_solucion.php
 *  (FICHERO CON LAS SOLUCIONES)
 *  Problemas de: array_diff, array_diff_assoc, array_diff_key, array_intersect, array_intersect_assoc,
 *                array_intersect_key
 *  (repasa los ejemplos de ../11_arrays_conjuntos_01.php)
 *
 *  Resuelve cada problema donde se indica y ejecútalo en modo depuración para comprobarlo.
 */
require_once __DIR__ . '/../utilidades.php';

// =============================================================================================
// PROBLEMA 1. Amigos en común
//  Obtén los amigos en común de Ana y Brais, los que solo tiene Ana y todos los amigos de los dos
//  sin repetir (unión). Renumera los resultados.
// =============================================================================================
titulo('Problema 1');
$amigosAna   = ["Carmen", "Diego", "Eva", "Fran"];
$amigosBrais = ["Eva", "Gael", "Carmen", "Iria"];
// completa aquí tu solución
// ---- SOLUCIÓN ----
$comunes  = array_values(array_intersect($amigosAna, $amigosBrais));          // [Carmen, Eva]
$soloAna  = array_values(array_diff($amigosAna, $amigosBrais));               // [Diego, Fran]
$todos    = array_values(array_unique(array_merge($amigosAna, $amigosBrais)));
ver('en común', $comunes);
ver('solo de Ana', $soloAna);
ver('todos', $todos);

// =============================================================================================
// PROBLEMA 2. Permisos de un usuario
//  ¿Qué permisos necesarios para publicar le faltan al usuario? ¿Tiene alguno de más?
// =============================================================================================
titulo('Problema 2');
$necesarios = ["leer", "escribir", "publicar"];
$delUsuario = ["leer", "comentar", "escribir"];
// completa aquí tu solución
// ---- SOLUCIÓN ----
$faltan  = array_values(array_diff($necesarios, $delUsuario));   // [publicar]
$deMas   = array_values(array_diff($delUsuario, $necesarios));   // [comentar]
ver('le faltan', $faltan);
ver('tiene de más', $deMas);
ver('¿puede publicar?', count($faltan) === 0);                    // false

// =============================================================================================
// PROBLEMA 3. Filtrar un formulario
//  De los datos recibidos, quédate solo con los campos permitidos (lista blanca) y obtén la lista
//  de campos no esperados que han llegado (posible manipulación).
// =============================================================================================
titulo('Problema 3');
$post       = ['usuario' => "ana", 'password' => "1234", 'rol' => "admin", 'debug' => "1"];
$permitidos = ["usuario", "password", "recordar"];
// completa aquí tu solución
// ---- SOLUCIÓN ----
$plantilla  = array_flip($permitidos);
$limpio     = array_intersect_key($post, $plantilla);
$inesperados = array_keys(array_diff_key($post, $plantilla));
ver('datos permitidos', $limpio);
ver('campos no esperados', $inesperados);    // ["rol", "debug"]

// =============================================================================================
// PROBLEMA 4. Corregir un examen
//  Calcula los aciertos, los fallos (respondidas pero distintas de la solución) y las preguntas
//  no contestadas. La nota es (aciertos - fallos / 3) sobre el total, en escala de 10.
// =============================================================================================
titulo('Problema 4');
$soluciones = [1 => "a", 2 => "c", 3 => "b", 4 => "d", 5 => "a", 6 => "b"];
$respuestas = [1 => "a", 2 => "b", 3 => "b", 5 => "a", 6 => "c"];          // la 4 en blanco
// completa aquí tu solución
// ---- SOLUCIÓN ----
$aciertos      = array_intersect_assoc($respuestas, $soluciones);
$fallos        = array_diff_assoc($respuestas, $soluciones);
$sinContestar  = array_keys(array_diff_key($soluciones, $respuestas));
$nota          = (count($aciertos) - count($fallos) / 3) * 10 / count($soluciones);
ver('aciertos', array_keys($aciertos));      // [1, 3, 5]
ver('fallos', array_keys($fallos));          // [2, 6]
ver('sin contestar', $sinContestar);         // [4]
ver('nota', round($nota, 2));                // 3.89

// =============================================================================================
// PROBLEMA 5. ¿Qué ha cambiado en el stock?
//  Compara el stock de ayer y el de hoy (producto => unidades): productos nuevos, productos que
//  ya no están y productos cuyo número de unidades ha cambiado.
// =============================================================================================
titulo('Problema 5');
$ayer = ['ratón' => 10, 'teclado' => 5, 'monitor' => 2];
$hoy  = ['ratón' => 10, 'teclado' => 3, 'webcam' => 7];
// completa aquí tu solución
// ---- SOLUCIÓN ----
$nuevos     = array_keys(array_diff_key($hoy, $ayer));                          // [webcam]
$eliminados = array_keys(array_diff_key($ayer, $hoy));                          // [monitor]
$cambiados  = array_intersect_key(array_diff_assoc($hoy, $ayer), $ayer);        // [teclado => 3]
ver('nuevos', $nuevos);
ver('eliminados', $eliminados);
ver('con unidades distintas', $cambiados);

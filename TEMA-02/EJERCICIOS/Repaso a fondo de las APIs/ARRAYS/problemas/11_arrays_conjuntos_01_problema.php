<?php
/*
 * 11_arrays_conjuntos_01_problema.php
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

// =============================================================================================
// PROBLEMA 2. Permisos de un usuario
//  ¿Qué permisos necesarios para publicar le faltan al usuario? ¿Tiene alguno de más?
// =============================================================================================
titulo('Problema 2');
$necesarios = ["leer", "escribir", "publicar"];
$delUsuario = ["leer", "comentar", "escribir"];
// completa aquí tu solución

// =============================================================================================
// PROBLEMA 3. Filtrar un formulario
//  De los datos recibidos, quédate solo con los campos permitidos (lista blanca) y obtén la lista
//  de campos no esperados que han llegado (posible manipulación).
// =============================================================================================
titulo('Problema 3');
$post       = ['usuario' => "ana", 'password' => "1234", 'rol' => "admin", 'debug' => "1"];
$permitidos = ["usuario", "password", "recordar"];
// completa aquí tu solución

// =============================================================================================
// PROBLEMA 4. Corregir un examen
//  Calcula los aciertos, los fallos (respondidas pero distintas de la solución) y las preguntas
//  no contestadas. La nota es (aciertos - fallos / 3) sobre el total, en escala de 10.
// =============================================================================================
titulo('Problema 4');
$soluciones = [1 => "a", 2 => "c", 3 => "b", 4 => "d", 5 => "a", 6 => "b"];
$respuestas = [1 => "a", 2 => "b", 3 => "b", 5 => "a", 6 => "c"];          // la 4 en blanco
// completa aquí tu solución

// =============================================================================================
// PROBLEMA 5. ¿Qué ha cambiado en el stock?
//  Compara el stock de ayer y el de hoy (producto => unidades): productos nuevos, productos que
//  ya no están y productos cuyo número de unidades ha cambiado.
// =============================================================================================
titulo('Problema 5');
$ayer = ['ratón' => 10, 'teclado' => 5, 'monitor' => 2];
$hoy  = ['ratón' => 10, 'teclado' => 3, 'webcam' => 7];
// completa aquí tu solución

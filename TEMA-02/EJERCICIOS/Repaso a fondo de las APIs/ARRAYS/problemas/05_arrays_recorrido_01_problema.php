<?php
/*
 * 05_arrays_recorrido_01_problema.php
 *  Problemas de: current / pos, key, next, prev, reset, end, each (eliminada)
 *  (repasa los ejemplos de ../05_arrays_recorrido_01.php)
 *
 *  Resuelve cada problema donde se indica y ejecútalo en modo depuración para comprobarlo.
 */
require_once __DIR__ . '/../utilidades.php';

// =============================================================================================
// PROBLEMA 1. Primero y último de un array asociativo
//  Sin conocer las claves, obtén con reset y end el primer y el último valor del array, y con key
//  sus claves.
// =============================================================================================
titulo('Problema 1');
$clasificacion = ['Celta' => 45, 'Dépor' => 41, 'Lugo' => 30, 'Ourense' => 22];
// completa aquí tu solución

// =============================================================================================
// PROBLEMA 2. Recorrer con while y el puntero
//  Recorre el array con un bucle while usando current, key y next (sin foreach) y construye las
//  líneas "1. Celta: 45 puntos".
// =============================================================================================
titulo('Problema 2');
// completa aquí tu solución

// =============================================================================================
// PROBLEMA 3. Carrusel de imágenes
//  Un carrusel muestra una imagen. Simula los botones: "siguiente", "siguiente", "anterior",
//  "siguiente", "siguiente", "siguiente". Si se pasa del final, vuelve al principio (reset); si
//  se pasa del principio, va al final (end). ¿Qué imagen se ve al terminar?
// =============================================================================================
titulo('Problema 3');
$imagenes = ["playa.jpg", "monte.jpg", "ria.jpg"];
$botones  = ["siguiente", "siguiente", "anterior", "siguiente", "siguiente", "siguiente"];
// completa aquí tu solución

// =============================================================================================
// PROBLEMA 4. Código antiguo con each
//  Este código de PHP 5 ya no funciona en PHP 8. Reescríbelo con foreach para obtener el mismo
//  resultado (un array de líneas "clave = valor").
//      while (list($clave, $valor) = each($config)) { $lineas[] = "$clave = $valor"; }
// =============================================================================================
titulo('Problema 4');
$config = ['host' => "localhost", 'puerto' => 3306, 'bd' => "tienda"];
// completa aquí tu solución

// =============================================================================================
// PROBLEMA 5. ¿Por qué falla?
//  Queremos la extensión de un fichero en una sola línea con end(explode(".", $fichero)), pero da
//  un aviso. ¿Por qué? Escríbelo correctamente.
// =============================================================================================
titulo('Problema 5');
$fichero = "presentacion.final.odp";
// ¿por qué falla? escríbelo bien

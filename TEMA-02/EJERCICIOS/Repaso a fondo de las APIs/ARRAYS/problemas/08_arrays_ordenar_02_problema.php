<?php
/*
 * 08_arrays_ordenar_02_problema.php
 *  Problemas de: usort, uasort, uksort, natsort, natcasesort, array_multisort
 *  (repasa los ejemplos de ../08_arrays_ordenar_02.php)
 *
 *  Resuelve cada problema donde se indica y ejecútalo en modo depuración para comprobarlo.
 */
require_once __DIR__ . '/../utilidades.php';

$productos = [
    ['nombre' => "Portátil",  'precio' => 899, 'valoracion' => 4.5],
    ['nombre' => "Ratón",     'precio' => 25,  'valoracion' => 4.8],
    ['nombre' => "Monitor",   'precio' => 239, 'valoracion' => 4.5],
    ['nombre' => "Teclado",   'precio' => 59,  'valoracion' => 4.1],
];

// =============================================================================================
// PROBLEMA 1. Ordenar productos en una tienda online
//  Ordena los productos (usort) por valoración de mayor a menor y, a igual valoración, por precio
//  de menor a mayor. Muestra solo los nombres.
// =============================================================================================
titulo('Problema 1');
// completa aquí tu solución

// =============================================================================================
// PROBLEMA 2. Ordenar por longitud del nombre conservando claves
//  El array es usuario => nombre completo. Ordénalo por la longitud del nombre (de más corto a más
//  largo) conservando el usuario. Usa uasort y mb_strlen.
// =============================================================================================
titulo('Problema 2');
$nombres = ['ana' => "Ana Pérez Lago", 'bo' => "Brais Otero", 'cn' => "Carmen Núñez Vázquez"];
// completa aquí tu solución

// =============================================================================================
// PROBLEMA 3. Días de la semana
//  Las claves son días de la semana en desorden. Ordénalas en el orden de la semana con uksort.
// =============================================================================================
titulo('Problema 3');
$horas = ['viernes' => 3, 'lunes' => 5, 'miércoles' => 4, 'martes' => 6, 'jueves' => 2];
$orden = ["lunes", "martes", "miércoles", "jueves", "viernes"];
// completa aquí tu solución

// =============================================================================================
// PROBLEMA 4. Versiones de un programa
//  Ordena las versiones de menor a mayor con orden natural y obtén la última versión.
// =============================================================================================
titulo('Problema 4');
$versiones = ["v2.10", "v2.9", "V2.1", "v10.0", "v2.11"];
// completa aquí tu solución

// =============================================================================================
// PROBLEMA 5. Ordenar por dos columnas con array_multisort
//  Ordena los productos por valoración DESCENDENTE y precio ASCENDENTE usando array_multisort
//  (el mismo criterio del problema 1) y comprueba que el resultado es el mismo.
// =============================================================================================
titulo('Problema 5');
// completa aquí tu solución

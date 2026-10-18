<?php
/*
 * 01_arrays_basicas_01_problema.php
 *  Problemas de: array, list, count / sizeof, range, array_fill, array_fill_keys, array_pad, compact, extract
 *  (repasa los ejemplos de ../01_arrays_basicas_01.php)
 *
 *  Resuelve cada problema donde se indica y ejecútalo en modo depuración para comprobarlo.
 */
require_once __DIR__ . '/../utilidades.php';

// =============================================================================================
// PROBLEMA 1. Tabla de multiplicar
//  Crea con range un array con los números del 1 al 10 y genera con un foreach un array
//  asociativo con la tabla del 7: ['7 x 1' => 7, '7 x 2' => 14, ...]. ¿Cuántos elementos tiene?
// =============================================================================================
titulo('Problema 1');
$tabla = 7;
// completa aquí tu solución

// =============================================================================================
// PROBLEMA 2. Asientos de un autobús
//  Un autobús tiene los asientos numerados del 1 al 12, todos "libre". Créalo con array_fill,
//  reserva los asientos 3, 4 y 10, y cuenta cuántos quedan libres.
// =============================================================================================
titulo('Problema 2');
$reservas = [3, 4, 10];
// completa aquí tu solución

// =============================================================================================
// PROBLEMA 3. Pedido con valores por defecto
//  Crea con array_fill_keys un pedido con las claves "producto", "cantidad", "talla" y "color", todas
//  a null. Después asigna los valores recibidos (los que no lleguen quedan a null).
// =============================================================================================
titulo('Problema 3');
$recibido = ['producto' => "camiseta", 'talla' => "M"];
// completa aquí tu solución

// =============================================================================================
// PROBLEMA 4. Coordenadas de varias ciudades
//  Recorre el array de ciudades y, usando list (o [ ]) en el foreach, construye frases como
//  "Vigo está en 42.24, -8.72".
// =============================================================================================
titulo('Problema 4');
$ciudades = [["Vigo", 42.24, -8.72], ["Lugo", 43.01, -7.56], ["Ourense", 42.34, -7.86]];
// completa aquí tu solución

// =============================================================================================
// PROBLEMA 5. De variables a array y vuelta
//  Junta las variables en un array con compact, añade la clave "iva" con el 21 % del precio, y
//  después crea de nuevo variables con extract. ¿Qué variables se han creado?
// =============================================================================================
titulo('Problema 5');
$articulo = "Auriculares";
$precio   = 40;
// completa aquí tu solución

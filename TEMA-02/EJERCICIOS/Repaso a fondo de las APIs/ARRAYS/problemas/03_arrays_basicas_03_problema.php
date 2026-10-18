<?php
/*
 * 03_arrays_basicas_03_problema.php
 *  Problemas de: array_keys, array_flip, array_change_key_case, array_is_list, array_count_values, array_unique
 *  (repasa los ejemplos de ../03_arrays_basicas_03.php)
 *
 *  Resuelve cada problema donde se indica y ejecútalo en modo depuración para comprobarlo.
 */
require_once __DIR__ . '/../utilidades.php';

// =============================================================================================
// PROBLEMA 1. La palabra más repetida
//  Cuenta cuántas veces aparece cada palabra en el texto (en minúsculas) y obtén la más repetida.
//  Pista: explode, array_count_values, max y array_keys (con el valor buscado).
// =============================================================================================
titulo('Problema 1');
$texto = "el perro y el gato y el loro";
// completa aquí tu solución

// =============================================================================================
// PROBLEMA 2. Traductor de días
//  Tienes un array número => día en gallego. Obtén con array_flip el número de un día a partir de
//  su nombre, y comprueba con array_is_list si el array original es una lista.
// =============================================================================================
titulo('Problema 2');
$dias = [1 => "luns", 2 => "martes", 3 => "mércores", 4 => "xoves", 5 => "venres"];
// completa aquí tu solución

// =============================================================================================
// PROBLEMA 3. Correos sin repetir
//  Una newsletter tiene correos repetidos con distintas mayúsculas. Obtén la lista de correos
//  únicos en minúsculas, renumerada (0, 1, 2...), y cuántos duplicados se han eliminado.
// =============================================================================================
titulo('Problema 3');
$suscriptores = ["ana@x.gal", "BRAIS@x.gal", "Ana@X.gal", "carmen@x.gal", "brais@x.gal"];
// completa aquí tu solución

// =============================================================================================
// PROBLEMA 4. Campos de un formulario
//  Los nombres de los campos llegan con mayúsculas mezcladas. Normaliza las claves a minúsculas
//  y comprueba si han llegado todos los campos obligatorios (usa array_keys).
// =============================================================================================
titulo('Problema 4');
$formulario   = ['Nombre' => "Ana", 'EMAIL' => "ana@x.gal", 'Ciudad' => "Vigo"];
$obligatorios = ["nombre", "email", "telefono"];
// completa aquí tu solución

// =============================================================================================
// PROBLEMA 5. Alumnos con matrícula de honor
//  Obtén los nombres de los alumnos que tienen exactamente un 10 (comparación estricta, el "10"
//  de texto no cuenta) y cuántas notas distintas hay.
// =============================================================================================
titulo('Problema 5');
$notas = ['Ana' => 10, 'Brais' => 8, 'Carmen' => 10, 'Diego' => "10", 'Eva' => 8];
// completa aquí tu solución

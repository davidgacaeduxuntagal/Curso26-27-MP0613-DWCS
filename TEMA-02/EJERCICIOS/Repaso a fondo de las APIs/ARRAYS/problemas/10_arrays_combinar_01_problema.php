<?php
/*
 * 10_arrays_combinar_01_problema.php
 *  Problemas de: array_merge (y +), array_merge_recursive, array_replace, array_replace_recursive,
 *                array_combine, array_column
 *  (repasa los ejemplos de ../10_arrays_combinar_01.php)
 *
 *  Resuelve cada problema donde se indica y ejecútalo en modo depuración para comprobarlo.
 */
require_once __DIR__ . '/../utilidades.php';

// =============================================================================================
// PROBLEMA 1. Leer un CSV completo
//  La primera línea son las cabeceras. Convierte cada una de las demás líneas en un array
//  asociativo con array_combine (y str_getcsv para separar los campos).
// =============================================================================================
titulo('Problema 1');
$csv = [
    "id;nombre;ciudad;nota",
    "1;Ana;Vigo;8.5",
    "2;Brais;Lugo;6",
    "3;Carmen;Vigo;9.25",
];
// completa aquí tu solución

// =============================================================================================
// PROBLEMA 2. Columnas de los datos
//  Con los alumnos del problema 1, obtén con array_column: la lista de nombres, un array
//  id => nota y los alumnos indexados por nombre.
// =============================================================================================
titulo('Problema 2');
// completa aquí tu solución

// =============================================================================================
// PROBLEMA 3. Opciones de una petición
//  Una función recibe opciones del usuario y debe completar las que falten con valores por
//  defecto. Hazlo de dos formas: con array_merge y con el operador +. ¿Dan lo mismo?
// =============================================================================================
titulo('Problema 3');
$porDefecto = ['metodo' => "GET", 'timeout' => 30, 'reintentos' => 3];
$opciones   = ['timeout' => 10];
// completa aquí tu solución

// =============================================================================================
// PROBLEMA 4. Juntar las etiquetas de varios artículos
//  Cada artículo tiene sus etiquetas agrupadas por tipo. Junta las de los dos artículos para que
//  en cada tipo aparezcan todas las etiquetas sin repetir.
// =============================================================================================
titulo('Problema 4');
$articulo1 = ['tema' => ["php", "web"], 'nivel' => ["básico"]];
$articulo2 = ['tema' => ["mysql", "php"], 'nivel' => ["medio"]];
// completa aquí tu solución

// =============================================================================================
// PROBLEMA 5. Actualizar una ficha
//  Aplica los cambios a la ficha del alumno: los datos de contacto anidados solo deben cambiar en
//  lo que se indica (el email debe conservarse).
// =============================================================================================
titulo('Problema 5');
$ficha   = ['nombre' => "Ana", 'contacto' => ['email' => "ana@x.gal", 'telefono' => "612345678"], 'curso' => "DAW1"];
$cambios = ['contacto' => ['telefono' => "698000111"], 'curso' => "DAW2"];
// completa aquí tu solución

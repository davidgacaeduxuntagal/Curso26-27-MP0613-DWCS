<?php
/*
 * 10_arrays_combinar_01_problema_solucion.php
 *  (FICHERO CON LAS SOLUCIONES)
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
// ---- SOLUCIÓN ----
$cabeceras = str_getcsv($csv[0], ";");
$alumnos   = [];
foreach (array_slice($csv, 1) as $linea) {
    $alumnos[] = array_combine($cabeceras, str_getcsv($linea, ";"));
}
ver('alumnos', $alumnos);

// =============================================================================================
// PROBLEMA 2. Columnas de los datos
//  Con los alumnos del problema 1, obtén con array_column: la lista de nombres, un array
//  id => nota y los alumnos indexados por nombre.
// =============================================================================================
titulo('Problema 2');
// completa aquí tu solución
// ---- SOLUCIÓN ----
ver('nombres', array_column($alumnos, 'nombre'));
ver('id => nota', array_column($alumnos, 'nota', 'id'));
ver('por nombre', array_column($alumnos, null, 'nombre'));

// =============================================================================================
// PROBLEMA 3. Opciones de una petición
//  Una función recibe opciones del usuario y debe completar las que falten con valores por
//  defecto. Hazlo de dos formas: con array_merge y con el operador +. ¿Dan lo mismo?
// =============================================================================================
titulo('Problema 3');
$porDefecto = ['metodo' => "GET", 'timeout' => 30, 'reintentos' => 3];
$opciones   = ['timeout' => 10];
// completa aquí tu solución
// ---- SOLUCIÓN ----
$conMerge = array_merge($porDefecto, $opciones);   // el ÚLTIMO gana
$conMas   = $opciones + $porDefecto;               // el PRIMERO gana
ver('array_merge', $conMerge);
ver('+', $conMas);
ver('¿mismos valores?', $conMerge == $conMas);    // true (== no tiene en cuenta el orden de las claves)

// =============================================================================================
// PROBLEMA 4. Juntar las etiquetas de varios artículos
//  Cada artículo tiene sus etiquetas agrupadas por tipo. Junta las de los dos artículos para que
//  en cada tipo aparezcan todas las etiquetas sin repetir.
// =============================================================================================
titulo('Problema 4');
$articulo1 = ['tema' => ["php", "web"], 'nivel' => ["básico"]];
$articulo2 = ['tema' => ["mysql", "php"], 'nivel' => ["medio"]];
// completa aquí tu solución
// ---- SOLUCIÓN ----
$juntas = array_merge_recursive($articulo1, $articulo2);
$juntas = array_map(fn($lista) => array_values(array_unique($lista)), $juntas);
ver('etiquetas', $juntas);    // tema => [php, web, mysql], nivel => [básico, medio]

// =============================================================================================
// PROBLEMA 5. Actualizar una ficha
//  Aplica los cambios a la ficha del alumno: los datos de contacto anidados solo deben cambiar en
//  lo que se indica (el email debe conservarse).
// =============================================================================================
titulo('Problema 5');
$ficha   = ['nombre' => "Ana", 'contacto' => ['email' => "ana@x.gal", 'telefono' => "612345678"], 'curso' => "DAW1"];
$cambios = ['contacto' => ['telefono' => "698000111"], 'curso' => "DAW2"];
// completa aquí tu solución
// ---- SOLUCIÓN ----
$actualizada = array_replace_recursive($ficha, $cambios);
ver('ficha actualizada', $actualizada);
ver('con array_replace se perdería el email', array_replace($ficha, $cambios)['contacto']);

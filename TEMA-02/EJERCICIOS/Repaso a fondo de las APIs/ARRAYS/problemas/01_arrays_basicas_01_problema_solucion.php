<?php
/*
 * 01_arrays_basicas_01_problema_solucion.php
 *  (FICHERO CON LAS SOLUCIONES)
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
// ---- SOLUCIÓN ----
$resultado = [];
foreach (range(1, 10) as $n) {
    $resultado["$tabla x $n"] = $tabla * $n;
}
ver('tabla del 7', $resultado);
ver('elementos', count($resultado));      // 10

// =============================================================================================
// PROBLEMA 2. Asientos de un autobús
//  Un autobús tiene los asientos numerados del 1 al 12, todos "libre". Créalo con array_fill,
//  reserva los asientos 3, 4 y 10, y cuenta cuántos quedan libres.
// =============================================================================================
titulo('Problema 2');
$reservas = [3, 4, 10];
// completa aquí tu solución
// ---- SOLUCIÓN ----
$asientos = array_fill(1, 12, "libre");
foreach ($reservas as $numero) {
    $asientos[$numero] = "ocupado";
}
$libres = count(array_keys($asientos, "libre"));
ver('asientos', $asientos);
ver('libres', $libres);                   // 9

// =============================================================================================
// PROBLEMA 3. Pedido con valores por defecto
//  Crea con array_fill_keys un pedido con las claves "producto", "cantidad", "talla" y "color", todas
//  a null. Después asigna los valores recibidos (los que no lleguen quedan a null).
// =============================================================================================
titulo('Problema 3');
$recibido = ['producto' => "camiseta", 'talla' => "M"];
// completa aquí tu solución
// ---- SOLUCIÓN ----
$pedido = array_fill_keys(["producto", "cantidad", "talla", "color"], null);
foreach ($recibido as $clave => $valor) {
    $pedido[$clave] = $valor;
}
ver('pedido', $pedido);

// =============================================================================================
// PROBLEMA 4. Coordenadas de varias ciudades
//  Recorre el array de ciudades y, usando list (o [ ]) en el foreach, construye frases como
//  "Vigo está en 42.24, -8.72".
// =============================================================================================
titulo('Problema 4');
$ciudades = [["Vigo", 42.24, -8.72], ["Lugo", 43.01, -7.56], ["Ourense", 42.34, -7.86]];
// completa aquí tu solución
// ---- SOLUCIÓN ----
$frases = [];
foreach ($ciudades as [$nombre, $latitud, $longitud]) {
    $frases[] = "$nombre está en $latitud, $longitud";
}
ver('frases', $frases);

// =============================================================================================
// PROBLEMA 5. De variables a array y vuelta
//  Junta las variables en un array con compact, añade la clave "iva" con el 21 % del precio, y
//  después crea de nuevo variables con extract. ¿Qué variables se han creado?
// =============================================================================================
titulo('Problema 5');
$articulo = "Auriculares";
$precio   = 40;
// completa aquí tu solución
// ---- SOLUCIÓN ----
$datos        = compact('articulo', 'precio');
$datos['iva'] = $datos['precio'] * 0.21;
$creadas      = extract($datos);           // crea (o sobrescribe) $articulo, $precio e $iva
ver('array', $datos);
ver('variables creadas', $creadas);        // 3
ver('$iva', $iva);                         // 8.4

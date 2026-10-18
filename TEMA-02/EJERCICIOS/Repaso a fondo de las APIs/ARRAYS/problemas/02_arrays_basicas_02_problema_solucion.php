<?php
/*
 * 02_arrays_basicas_02_problema_solucion.php
 *  (FICHERO CON LAS SOLUCIONES)
 *  Problemas de: array_push, array_pop, array_unshift, array_shift, unset, array_key_first, array_key_last
 *  (repasa los ejemplos de ../02_arrays_basicas_02.php)
 *
 *  Resuelve cada problema donde se indica y ejecútalo en modo depuración para comprobarlo.
 */
require_once __DIR__ . '/../utilidades.php';

// =============================================================================================
// PROBLEMA 1. Deshacer en un editor
//  Un editor guarda cada acción en una pila. Simula: escribir "Hola", escribir " mundo", poner
//  negrita y después deshacer 2 veces. ¿Qué acciones quedan y cuáles se han deshecho?
// =============================================================================================
titulo('Problema 1');
$acciones = [];
// completa aquí tu solución
// ---- SOLUCIÓN ----
array_push($acciones, "escribir 'Hola'", "escribir ' mundo'", "negrita");
$deshechas   = [];
$deshechas[] = array_pop($acciones);
$deshechas[] = array_pop($acciones);
ver('quedan', $acciones);            // ["escribir 'Hola'"]
ver('deshechas', $deshechas);        // ["negrita", "escribir ' mundo'"]

// =============================================================================================
// PROBLEMA 2. Turnos de una carnicería
//  Los clientes cogen número y se atienden por orden de llegada (cola). Llegan Ana, Brais y Carmen;
//  se atiende a uno; llega Diego; se atienden dos más. ¿Quién queda esperando?
// =============================================================================================
titulo('Problema 2');
$cola = [];
// completa aquí tu solución
// ---- SOLUCIÓN ----
array_push($cola, "Ana", "Brais", "Carmen");
$atendidos   = [array_shift($cola)];
$cola[]      = "Diego";
$atendidos[] = array_shift($cola);
$atendidos[] = array_shift($cola);
ver('atendidos', $atendidos);        // ["Ana", "Brais", "Carmen"]
ver('esperando', $cola);             // ["Diego"]

// =============================================================================================
// PROBLEMA 3. Cliente VIP
//  En la cola anterior llega un cliente VIP que pasa el primero. Añádelo y comprueba quién es el
//  primero y el último de la cola con array_key_first / array_key_last.
// =============================================================================================
titulo('Problema 3');
$cola = ["Eva", "Fran"];
// completa aquí tu solución
// ---- SOLUCIÓN ----
array_unshift($cola, "Señora VIP");
ver('primero', $cola[array_key_first($cola)]);   // "Señora VIP"
ver('último', $cola[array_key_last($cola)]);     // "Fran"

// =============================================================================================
// PROBLEMA 4. Quitar del carrito los productos agotados
//  El carrito es código => cantidad. Elimina con unset los productos que están en la lista de
//  agotados. ¿Por qué no hay "huecos" problemáticos aquí y en una lista sí?
// =============================================================================================
titulo('Problema 4');
$carrito  = ['P01' => 2, 'P02' => 1, 'P03' => 4, 'P04' => 1];
$agotados = ['P02', 'P04'];
// completa aquí tu solución
// ---- SOLUCIÓN ----
foreach ($agotados as $codigo) {
    unset($carrito[$codigo]);
}
ver('carrito', $carrito);            // ['P01' => 2, 'P03' => 4]
// Las claves son códigos de texto: no esperamos claves consecutivas, así que no hay "huecos".
// En una lista (claves 0, 1, 2...) unset deja huecos y habría que usar array_values.

// =============================================================================================
// PROBLEMA 5. Últimas búsquedas
//  Una web guarda las 3 últimas búsquedas del usuario: cada nueva búsqueda se añade al principio
//  y, si hay más de 3, se quita la más antigua (la última). Procesa las búsquedas dadas.
// =============================================================================================
titulo('Problema 5');
$busquedas = ["zapatillas", "camiseta", "mochila", "gorra", "calcetines"];
$recientes = [];
// completa aquí tu solución
// ---- SOLUCIÓN ----
foreach ($busquedas as $busqueda) {
    array_unshift($recientes, $busqueda);
    if (count($recientes) > 3) {
        array_pop($recientes);
    }
}
ver('últimas búsquedas', $recientes);   // ["calcetines", "gorra", "mochila"]

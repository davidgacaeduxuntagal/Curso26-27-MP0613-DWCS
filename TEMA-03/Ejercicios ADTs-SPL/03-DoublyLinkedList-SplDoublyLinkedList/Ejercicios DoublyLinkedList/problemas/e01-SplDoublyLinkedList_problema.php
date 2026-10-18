<?php
/*
 * e01-SplDoublyLinkedList_problema.php
 *  Problemas de: SplDoublyLinkedList (push, pop, shift, unshift, add, offsetGet / offsetSet /
 *                offsetUnset, modos de recorrido, rewind / next / prev)
 *  (repasa los ejemplos de ../e01-SplDoublyLinkedList.php)
 *
 *  Resuelve cada problema donde se indica y ejecútalo en modo depuración para comprobarlo.
 */
require_once __DIR__ . '/../../../utilidades.php';

// =============================================================================================
// PROBLEMA 1. Rotar una lista
//  Escribe rotar(SplDoublyLinkedList $lista, int $n): void que mueva los $n primeros elementos
//  al final (con $n negativo, los $n últimos al principio). Usa solo shift/push y pop/unshift.
// =============================================================================================
titulo('Problema 1');
$dias = new SplDoublyLinkedList();
foreach (['luns', 'martes', 'mércores', 'xoves', 'venres'] as $d) {
    $dias->push($d);
}
// completa aquí tu solución

// =============================================================================================
// PROBLEMA 2. Insertar y borrar por posición
//  En la lista de la compra $compra: inserta 'aceite' en la posición 1, cambia 'pan' por
//  'pan integral', y borra 'leche' (búscala; no sabes en qué posición está). Muestra la lista.
// =============================================================================================
titulo('Problema 2');
$compra = new SplDoublyLinkedList();
foreach (['pan', 'leche', 'huevos', 'tomates'] as $p) {
    $compra->push($p);
}
// completa aquí tu solución

// =============================================================================================
// PROBLEMA 3. Del revés sin copiar
//  Muestra los elementos de $ultimos (las últimas visitas a la web) del más reciente al más
//  antiguo SIN crear otra lista ni un array al revés: cambia el modo de recorrido.
// =============================================================================================
titulo('Problema 3');
$ultimos = new SplDoublyLinkedList();
foreach (['/inicio', '/productos', '/carrito', '/pago'] as $url) {
    $ultimos->push($url);
}
// completa aquí tu solución

// =============================================================================================
// PROBLEMA 4. Un carrusel de imágenes
//  Escribe la clase Carrusel (con una SplDoublyLinkedList de imágenes) con los métodos actual(),
//  siguiente() y anterior(), que den la vuelta al llegar a un extremo (después de la última, la
//  primera; antes de la primera, la última). Pruébalo con la secuencia de abajo.
// =============================================================================================
titulo('Problema 4');
// completa aquí tu solución

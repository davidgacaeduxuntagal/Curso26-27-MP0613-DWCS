<?php
/*
 * e11-POO_problema.php
 *  Problemas de: Iterator, IteratorAggregate, ArrayAccess, Countable, iteradores de la SPL,
 *                SplStack / SplQueue / SplObjectStorage, generadores (yield, yield from)
 *  (repasa los ejemplos de ../Ejercicios OOP/e11-POO.php)
 *
 *  Resuelve cada problema donde se indica y ejecútalo en modo depuración para comprobarlo.
 */
require_once __DIR__ . '/../Ejercicios OOP/utilidades.php';

// =============================================================================================
// PROBLEMA 1. Un rango de fechas recorrible
//  Escribe la clase RangoFechas (desde y hasta, DateTimeImmutable) que implemente
//  IteratorAggregate: su getIterator() debe ser un GENERADOR (con yield) que entregue cada día
//  del rango (incluidos los dos extremos) con la clave "Y-m-d". Recorre la semana del 12 al 18
//  de octubre de 2026 y muestra los días que no son fin de semana.
// =============================================================================================
titulo('Problema 1');
// completa aquí tu solución

// =============================================================================================
// PROBLEMA 2. Un inventario que se usa como un array
//  Escribe la clase Inventario que implemente ArrayAccess y Countable: $inv['RAT-01'] = 5 fija
//  el stock (entero >= 0; si no, InvalidArgumentException), $inv['RAT-01'] lo lee (0 si no
//  existe), isset y unset funcionan, y count() devuelve el total de UNIDADES (no de referencias).
// =============================================================================================
titulo('Problema 2');
// completa aquí tu solución

// =============================================================================================
// PROBLEMA 3. Leer un log enorme con un generador
//  Escribe el generador lineasConError(iterable $lineas): Generator que entregue solo las líneas
//  que contienen "ERROR", con su número de línea (empezando en 1) como clave. Pruébalo con el
//  array $log (en la vida real sería un SplFileObject o un fgets de un fichero de varios GB).
// =============================================================================================
titulo('Problema 3');
$log = ['10:00 INFO arranque', '10:01 ERROR sin conexión a la BD', '10:02 INFO reintento', '10:03 ERROR tiempo agotado', '10:04 INFO ok'];
// completa aquí tu solución

// =============================================================================================
// PROBLEMA 4. Comprobar los paréntesis con una pila
//  Escribe equilibrado(string $expresion): bool que use SplStack para comprobar que los
//  paréntesis, corchetes y llaves están bien emparejados.
// =============================================================================================
titulo('Problema 4');
$pruebas = ['(a + b) * [c - {d / e}]', '(a + b]', '((a)', 'f(x) = {[ ]}'];
// completa aquí tu solución

// =============================================================================================
// PROBLEMA 5. Paginar con iteradores de la SPL
//  ¿Cómo mostrarías la página 2 (de 3 elementos por página) de los productos con precio menor
//  de 50 €, SIN crear arrays intermedios? Usa CallbackFilterIterator y LimitIterator.
// =============================================================================================
titulo('Problema 5');
$productos = new ArrayIterator([
    'Ratón' => 19.95, 'Monitor' => 179.0, 'Teclado' => 49.5, 'Cable' => 8.99, 'Webcam' => 39.9,
    'Altavoces' => 65.0, 'Funda' => 12.0, 'Hub USB' => 24.5, 'Cascos' => 59.0, 'Alfombrilla' => 7.5,
]);
// completa aquí tu solución

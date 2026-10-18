<?php
/*
 * e01-SplFixedArray_problema.php
 *  Problemas de: SplFixedArray (crear, leer y escribir, getSize, setSize, toArray, fromArray,
 *                foreach, errores)
 *  (repasa los ejemplos de ../e01-SplFixedArray.php)
 *
 *  Resuelve cada problema donde se indica y ejecútalo en modo depuración para comprobarlo.
 */
require_once __DIR__ . '/../../../utilidades.php';

// =============================================================================================
// PROBLEMA 1. Ventas por mes
//  Crea un SplFixedArray de 12 casillas con las ventas de cada mes (0 = enero), inicializadas a
//  0. Registra las ventas de $operaciones (mes 1..12 => importe; puede haber varias por mes) y
//  muestra el mes con más ventas.
// =============================================================================================
titulo('Problema 1');
$operaciones = [[1, 120.0], [3, 80.5], [1, 45.0], [12, 300.0], [3, 99.5], [7, 60.0]];
$meses = ['ene', 'feb', 'mar', 'abr', 'may', 'jun', 'jul', 'ago', 'sep', 'oct', 'nov', 'dic'];
// completa aquí tu solución

// =============================================================================================
// PROBLEMA 2. Invertir sin otro array
//  Escribe invertir(SplFixedArray $a): void que dé la vuelta al contenido intercambiando la
//  casilla i con la casilla tamaño-1-i (sin toArray ni array_reverse).
// =============================================================================================
titulo('Problema 2');
$dorsales = SplFixedArray::fromArray([7, 12, 3, 25, 18]);
// completa aquí tu solución

// =============================================================================================
// PROBLEMA 3. Leer con seguridad
//  Escribe leer(SplFixedArray $a, int $i, mixed $porDefecto = null): mixed que devuelva el valor
//  de la casilla $i o $porDefecto si el índice está fuera de rango, SIN usar try/catch (usa
//  getSize o isset).
// =============================================================================================
titulo('Problema 3');
$plazas = SplFixedArray::fromArray(['Ana', null, 'Carmen']);
// completa aquí tu solución

// =============================================================================================
// PROBLEMA 4. ¿Cómo harías...?
//  Un aula tiene 30 ordenadores numerados del 1 al 30. ¿Cómo guardarías en un SplFixedArray qué
//  alumno usa cada uno, de forma que "ordenador 1" sea la casilla 0? Escribe asignar() y
//  quienUsa() y comprueba que asignar el ordenador 31 da un error claro.
// =============================================================================================
titulo('Problema 4');
// completa aquí tu solución

<?php
/*
 * e01-SplStack_problema.php
 *  Problemas de: SplStack (push, pop, top, isEmpty, count, foreach, IT_MODE_DELETE)
 *  (repasa los ejemplos de ../e01-SplStack.php)
 *
 *  Resuelve cada problema donde se indica y ejecútalo en modo depuración para comprobarlo.
 */
require_once __DIR__ . '/../../../utilidades.php';

// =============================================================================================
// PROBLEMA 1. Invertir una palabra
//  Escribe invertir(string $texto): string que meta cada carácter en una SplStack y luego los
//  saque para construir el texto al revés (usa mb_str_split para que funcione con tildes y ñ).
// =============================================================================================
titulo('Problema 1');
// completa aquí tu solución

// =============================================================================================
// PROBLEMA 2. ¿Palíndromo?
//  Usando invertir() del problema 1 (o una pila), escribe esPalindromo(string $frase): bool que
//  no tenga en cuenta mayúsculas, espacios ni signos de puntuación.
// =============================================================================================
titulo('Problema 2');
$frases = ['Anita lava la tina', 'Dábale arroz a la zorra el abad', 'Bar Baio'];
// completa aquí tu solución

// =============================================================================================
// PROBLEMA 3. De decimal a binario
//  Escribe aBinario(int $n): string que convierta un número a binario con una pila: se apilan
//  los restos de dividir entre 2 y después se sacan. Compara con decbin().
// =============================================================================================
titulo('Problema 3');
// completa aquí tu solución

// =============================================================================================
// PROBLEMA 4. La pila de platos con límite
//  Escribe la clase PilaLimitada que envuelva una SplStack y tenga un tamaño máximo: apilar()
//  lanza OverflowException si está llena y desapilar() lanza UnderflowException si está vacía.
//  Añade cima(), estaLlena() y cuantos(). Pruébala con un máximo de 3 platos.
// =============================================================================================
titulo('Problema 4');
// completa aquí tu solución

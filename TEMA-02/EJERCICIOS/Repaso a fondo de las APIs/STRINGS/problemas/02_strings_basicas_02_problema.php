<?php
/*
 * 02_strings_basicas_02_problema.php
 *  Problemas de: trim, ltrim, rtrim / chop, str_pad, substr, chunk_split, str_shuffle
 *  (repasa los ejemplos de ../02_strings_basicas_02.php)
 *
 *  Resuelve cada problema donde se indica y ejecútalo en modo depuración para comprobarlo.
 */
require_once __DIR__ . '/../utilidades.php';

// =============================================================================================
// PROBLEMA 1. Limpiar los campos de un formulario
//  Los datos llegan de un formulario con espacios y saltos de línea sobrantes. Límpialos todos
//  y averigua cuántos campos quedan vacíos.
// =============================================================================================
titulo('Problema 1');
$formulario = ['nombre' => "  Ana ", 'apellidos' => "   ", 'ciudad' => "Vigo\n", 'telefono' => "\t"];
// completa aquí tu solución

// =============================================================================================
// PROBLEMA 2. Código de producto
//  Los códigos de producto tienen el formato "PRD-" + número con 5 cifras rellenado con ceros.
//  Genera los códigos de los productos 7, 125 y 30412.
// =============================================================================================
titulo('Problema 2');
$numeros = [7, 125, 30412];
// completa aquí tu solución

// =============================================================================================
// PROBLEMA 3. Partes de una matrícula y de una hora
//  De la matrícula "1234BCD" obtén los números y las letras. De la hora "14:35:20" obtén horas,
//  minutos y segundos usando substr (no explode).
// =============================================================================================
titulo('Problema 3');
$matricula = "1234BCD";
$hora      = "14:35:20";
// completa aquí tu solución

// =============================================================================================
// PROBLEMA 4. Número de tarjeta legible
//  Muestra el número de tarjeta en bloques de 4 separados por guiones, sin guion al final:
//  "4111222233334444" -> "4111-2222-3333-4444".
// =============================================================================================
titulo('Problema 4');
$tarjeta = "4111222233334444";
// completa aquí tu solución

// =============================================================================================
// PROBLEMA 5. Juego de las letras desordenadas
//  Elige al azar una palabra de la lista y muéstrala desordenada. ¿Cómo harías para que la
//  palabra desordenada nunca coincida con la original?
// =============================================================================================
titulo('Problema 5');
$palabras = ["servidor", "variable", "funcion", "formulario"];
// ¿cómo lo harías? Guarda la palabra en $secreta y la versión desordenada en $desordenada

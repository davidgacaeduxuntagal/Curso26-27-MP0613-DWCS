<?php
/*
 * 01_strings_basicas_01_problema_solucion.php
 *  (FICHERO CON LAS SOLUCIONES)
 *  Problemas de: strlen, strtolower, strtoupper, ucfirst, lcfirst, ucwords, strrev, str_repeat
 *  (repasa los ejemplos de ../01_strings_basicas_01.php)
 *
 *  Resuelve cada problema donde se indica y ejecútalo en modo depuración para comprobarlo.
 */
require_once __DIR__ . '/../utilidades.php';

// =============================================================================================
// PROBLEMA 1. Validar un nombre de usuario
//  Un nombre de usuario es válido si tiene entre 4 y 12 caracteres. Además se guarda siempre en
//  minúsculas. Calcula $usuarioGuardado (en minúsculas) y $esValido (true / false).
// =============================================================================================
titulo('Problema 1');
$usuario = "AnaPerez2026";
// completa aquí tu solución
// ---- SOLUCIÓN ----
$usuarioGuardado = strtolower($usuario);
$esValido        = strlen($usuario) >= 4 && strlen($usuario) <= 12;
ver('usuario guardado', $usuarioGuardado);   // "anaperez2026"
ver('¿válido?', $esValido);                  // true

// =============================================================================================
// PROBLEMA 2. Normalizar el nombre de una ciudad
//  Los usuarios escriben la ciudad de cualquier forma ("SANTIAGO DE COMPOSTELA", "santiago de
//  compostela"...). Queremos guardarla con la inicial de cada palabra en mayúscula. ¿Cómo harías
//  para que funcione tanto si viene en mayúsculas como en minúsculas? (sin tildes en este caso)
// =============================================================================================
titulo('Problema 2');
$ciudades = ["SANTIAGO DE COMPOSTELA", "vilagarcía de arousa", "a coruña"];
// ¿cómo harías la conversión para cada ciudad? Guarda los resultados en $normalizadas
// ---- SOLUCIÓN ----
$normalizadas = [];
foreach ($ciudades as $ciudad) {
    $normalizadas[] = ucwords(strtolower($ciudad));
}
ver('ciudades normalizadas', $normalizadas);
// Observa que con tildes en mayúsculas no funcionaría bien: ver mb_convert_case en 17_strings_i18n_02.php

// =============================================================================================
// PROBLEMA 3. Frase palíndroma
//  "Anita lava la tina" es un palíndromo si ignoramos espacios y mayúsculas. Escribe el código
//  que calcule $esPalindromo para la frase dada. Pista: quita los espacios con str_replace.
// =============================================================================================
titulo('Problema 3');
$frase = "Anita lava la tina";
// completa aquí tu solución
// ---- SOLUCIÓN ----
$limpia       = strtolower(str_replace(" ", "", $frase));   // "anitalavalatina"
$esPalindromo = $limpia === strrev($limpia);
ver('¿palíndromo?', $esPalindromo);                         // true

// =============================================================================================
// PROBLEMA 4. Ocultar un email
//  Para mostrar un email en una página sin que se lea entero, deja visible la primera letra del
//  usuario y sustituye el resto del usuario por asteriscos: "ana.perez@gmail.com" ->
//  "a********@gmail.com". Usa strlen, str_repeat y la posición de la "@" (strpos).
// =============================================================================================
titulo('Problema 4');
$email = "ana.perez@gmail.com";
// completa aquí tu solución
// ---- SOLUCIÓN ----
$posArroba = strpos($email, "@");
$oculto    = $email[0] . str_repeat("*", $posArroba - 1) . substr($email, $posArroba);
ver('email oculto', $oculto);              // "a********@gmail.com"

// =============================================================================================
// PROBLEMA 5. Nombres de variables
//  Convierte el nombre de una clase "PascalCase" en el de una variable "camelCase", y un título
//  en minúsculas en una frase que empiece por mayúscula.
// =============================================================================================
titulo('Problema 5');
$clase  = "CarritoDeCompra";
$titulo = "resumen del pedido";
// completa aquí tu solución
// ---- SOLUCIÓN ----
$variable = lcfirst($clase);      // "carritoDeCompra"
$frase    = ucfirst($titulo);     // "Resumen del pedido"
ver('variable', $variable);
ver('frase', $frase);

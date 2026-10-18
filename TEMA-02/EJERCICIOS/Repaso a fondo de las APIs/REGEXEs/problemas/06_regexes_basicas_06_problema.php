<?php
/*
 * 06_regexes_basicas_06_problema.php
 *  Problemas de: aserciones (?= ) (?! ) (?<= ) (?<! ) y propiedades Unicode \p{...}
 *  (repasa los ejemplos de ../06_regexes_basicas_06.php)
 *
 *  Resuelve cada problema donde se indica y ejecútalo en modo depuración para comprobarlo.
 */
require_once __DIR__ . '/../utilidades.php';

// =============================================================================================
// PROBLEMA 1. Política de contraseñas
//  Una contraseña es válida si tiene entre 10 y 20 caracteres, al menos 2 dígitos (no
//  necesariamente seguidos), al menos una mayúscula y NO contiene espacios.
// =============================================================================================
titulo('Problema 1');
$claves = ["Servidor2026", "servidor2026", "Servidor2", "Servi dor2026", "Sv12"];
// completa aquí tu solución

// =============================================================================================
// PROBLEMA 2. Importes en euros
//  Obtén con preg_match_all todos los importes que van seguidos de " €" (sin incluir el símbolo)
//  pero no los que van seguidos de " $".
// =============================================================================================
titulo('Problema 2');
$texto = "Billete 45,50 €, hotel 120 $, cena 30 € y taxi 15 $";
// completa aquí tu solución

// =============================================================================================
// PROBLEMA 3. Usuarios mencionados
//  En un mensaje, las menciones son "@" seguido de letras o dígitos. Obtén los nombres mencionados
//  SIN la "@", pero que no sean parte de un email (es decir, la "@" no puede ir precedida de una
//  letra o dígito).
// =============================================================================================
titulo('Problema 3');
$mensaje = "Hola @ana y @brais26, escribid a info@empresa.es";
// completa aquí tu solución

// =============================================================================================
// PROBLEMA 4. Números que no son años
//  Obtén los números de 4 cifras del texto que NO van precedidos de la palabra "año " ni seguidos
//  de "€".
// =============================================================================================
titulo('Problema 4');
$texto = "En el año 2026 se vendieron 1500 unidades a 1200€ y 3400 piezas";
// completa aquí tu solución

// =============================================================================================
// PROBLEMA 5. Iniciales de nombres en cualquier idioma
//  Obtén las iniciales (primera letra de cada palabra que empieza por MAYÚSCULA) de los nombres,
//  usando \p{Lu} y el modificador u. Deben funcionar con tildes y eñes.
// =============================================================================================
titulo('Problema 5');
$nombres = ["Ángel Núñez de la Fuente", "Óscar Íñiguez", "Ñuño Pérez"];
// completa aquí tu solución

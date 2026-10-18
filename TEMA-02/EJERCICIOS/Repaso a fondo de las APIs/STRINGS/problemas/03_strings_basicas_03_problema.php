<?php
/*
 * 03_strings_basicas_03_problema.php
 *  Problemas de: explode, implode / join, str_split, strtok, parse_str, str_word_count, count_chars
 *  (repasa los ejemplos de ../03_strings_basicas_03.php)
 *
 *  Resuelve cada problema donde se indica y ejecútalo en modo depuración para comprobarlo.
 */
require_once __DIR__ . '/../utilidades.php';

// =============================================================================================
// PROBLEMA 1. Etiquetas de un artículo
//  Un usuario escribe las etiquetas de un artículo separadas por comas, con espacios de más.
//  Obtén un array de etiquetas limpias (sin espacios) y vuelve a unirlas con " #" delante de cada
//  una: "#php #mysql #apache".
// =============================================================================================
titulo('Problema 1');
$etiquetas = "php ,  mysql,apache  ";
// completa aquí tu solución

// =============================================================================================
// PROBLEMA 2. Letra del DNI
//  La letra del DNI se obtiene así: número % 23 y se busca esa posición en la cadena
//  "TRWAGMYFPDXBNJZSQVHLCKE". Comprueba si el DNI dado es correcto.
//  Usa str_split (o accede directamente a la posición del string).
// =============================================================================================
titulo('Problema 2');
$dni    = "12345678Z";
$letras = "TRWAGMYFPDXBNJZSQVHLCKE";
// completa aquí tu solución

// =============================================================================================
// PROBLEMA 3. Parámetros de una URL
//  De la URL dada, obtén el valor del parámetro "pagina" y la lista de categorías.
//  Pista: separa primero lo que hay después del "?" (explode con límite) y usa parse_str.
// =============================================================================================
titulo('Problema 3');
$url = "https://tienda.example/buscar.php?q=camiseta&pagina=3&cat[]=ropa&cat[]=deporte";
// completa aquí tu solución

// =============================================================================================
// PROBLEMA 4. Comandos de un chat
//  Un bot recibe mensajes como "/sumar 4 10 23". Usando strtok con el espacio como separador,
//  obtén el comando ("/sumar") y suma los números que le siguen.
// =============================================================================================
titulo('Problema 4');
$mensaje = "/sumar 4 10 23";
// completa aquí tu solución

// =============================================================================================
// PROBLEMA 5. Vocales de un texto
//  Cuenta cuántas veces aparece cada vocal (a, e, i, o, u) en el texto. Usa count_chars en modo 1
//  y chr / ord. ¿Cuántas palabras tiene el texto según str_word_count?
// =============================================================================================
titulo('Problema 5');
$texto = "la programacion web en entorno servidor";
// completa aquí tu solución

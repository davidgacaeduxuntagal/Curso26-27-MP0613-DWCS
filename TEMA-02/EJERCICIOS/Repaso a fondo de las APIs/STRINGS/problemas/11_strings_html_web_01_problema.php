<?php
/*
 * 11_strings_html_web_01_problema.php
 *  Problemas de: htmlspecialchars, htmlspecialchars_decode, htmlentities, html_entity_decode,
 *                get_html_translation_table, nl2br, strip_tags
 *  (repasa los ejemplos de ../11_strings_html_web_01.php)
 *
 *  Resuelve cada problema donde se indica y ejecútalo en modo depuración para comprobarlo.
 */
require_once __DIR__ . '/../utilidades.php';

// =============================================================================================
// PROBLEMA 1. Mostrar comentarios de forma segura
//  Genera el HTML de una lista <ul> con los comentarios de los usuarios. Ningún comentario debe
//  poder inyectar HTML, y los saltos de línea deben verse en la página.
// =============================================================================================
titulo('Problema 1');
$comentarios = ["Muy buen artículo\nGracias", "<b>Compra</b> aquí: <a href='http://spam'>clic</a>"];
// completa aquí tu solución

// =============================================================================================
// PROBLEMA 2. Formulario pegajoso
//  Genera el <input> de un formulario que vuelve a mostrar lo que escribió el usuario. ¿Qué pasa
//  si NO escapas el valor? Compara los dos resultados.
// =============================================================================================
titulo('Problema 2');
$escrito = "Bar \"El Puerto\" & 'Terraza'";
// completa aquí tu solución

// =============================================================================================
// PROBLEMA 3. Texto plano de un correo HTML
//  Obtén el texto de un correo escrito en HTML: sin etiquetas y con las entidades convertidas
//  en caracteres. Después, cuenta las palabras.
// =============================================================================================
titulo('Problema 3');
$correoHtml = "<p>Hola <b>Ana</b>,</p><p>Tu pedido de 3&nbsp;camisetas est&aacute; en camino &amp; llegar&aacute; ma&ntilde;ana.</p>";
// completa aquí tu solución

// =============================================================================================
// PROBLEMA 4. Biografía con formato limitado
//  En la biografía se permiten solo las etiquetas <b>, <i> y <br>. Elimina el resto.
// =============================================================================================
titulo('Problema 4');
$bio = "<h1>Hola</h1> Soy <b>desarrolladora</b><br>Me gusta <i>PHP</i> <script>robar()</script>";
// completa aquí tu solución

// =============================================================================================
// PROBLEMA 5. ¿Qué cambia cada función?
//  Usando get_html_translation_table, averigua cuántos caracteres convierte htmlspecialchars y
//  cuántos htmlentities (con ENT_QUOTES | ENT_HTML5). Después, comprueba qué entidad usa
//  htmlentities para "€" y para "©".
// =============================================================================================
titulo('Problema 5');
// completa aquí tu solución

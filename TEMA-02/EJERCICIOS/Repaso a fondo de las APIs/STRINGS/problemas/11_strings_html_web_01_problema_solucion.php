<?php
/*
 * 11_strings_html_web_01_problema_solucion.php
 *  (FICHERO CON LAS SOLUCIONES)
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
// ---- SOLUCIÓN ----
$html = "<ul>";
foreach ($comentarios as $comentario) {
    $html .= "<li>" . nl2br(htmlspecialchars($comentario, ENT_QUOTES, 'UTF-8')) . "</li>";
}
$html .= "</ul>";
ver('HTML', $html);

// =============================================================================================
// PROBLEMA 2. Formulario pegajoso
//  Genera el <input> de un formulario que vuelve a mostrar lo que escribió el usuario. ¿Qué pasa
//  si NO escapas el valor? Compara los dos resultados.
// =============================================================================================
titulo('Problema 2');
$escrito = "Bar \"El Puerto\" & 'Terraza'";
// completa aquí tu solución
// ---- SOLUCIÓN ----
$sinEscapar = "<input type='text' name='local' value='" . $escrito . "'>";          // el ' cierra el atributo
$escapado   = "<input type='text' name='local' value='" . htmlspecialchars($escrito, ENT_QUOTES) . "'>";
ver('sin escapar (HTML roto)', $sinEscapar);
ver('escapado', $escapado);

// =============================================================================================
// PROBLEMA 3. Texto plano de un correo HTML
//  Obtén el texto de un correo escrito en HTML: sin etiquetas y con las entidades convertidas
//  en caracteres. Después, cuenta las palabras.
// =============================================================================================
titulo('Problema 3');
$correoHtml = "<p>Hola <b>Ana</b>,</p><p>Tu pedido de 3&nbsp;camisetas est&aacute; en camino &amp; llegar&aacute; ma&ntilde;ana.</p>";
// completa aquí tu solución
// ---- SOLUCIÓN ----
$texto = html_entity_decode(strip_tags($correoHtml), ENT_QUOTES | ENT_HTML5, 'UTF-8');
ver('texto plano', $texto);
ver('palabras', str_word_count($texto, 0, "áéíóúñ0123456789"));

// =============================================================================================
// PROBLEMA 4. Biografía con formato limitado
//  En la biografía se permiten solo las etiquetas <b>, <i> y <br>. Elimina el resto.
// =============================================================================================
titulo('Problema 4');
$bio = "<h1>Hola</h1> Soy <b>desarrolladora</b><br>Me gusta <i>PHP</i> <script>robar()</script>";
// completa aquí tu solución
// ---- SOLUCIÓN ----
$limpia = strip_tags($bio, "<b><i><br>");
ver('biografía', $limpia);     // "Hola Soy <b>desarrolladora</b><br>Me gusta <i>PHP</i> robar()"

// =============================================================================================
// PROBLEMA 5. ¿Qué cambia cada función?
//  Usando get_html_translation_table, averigua cuántos caracteres convierte htmlspecialchars y
//  cuántos htmlentities (con ENT_QUOTES | ENT_HTML5). Después, comprueba qué entidad usa
//  htmlentities para "€" y para "©".
// =============================================================================================
titulo('Problema 5');
// completa aquí tu solución
// ---- SOLUCIÓN ----
$tablaEspeciales = get_html_translation_table(HTML_SPECIALCHARS, ENT_QUOTES | ENT_HTML5);
$tablaEntidades  = get_html_translation_table(HTML_ENTITIES, ENT_QUOTES | ENT_HTML5);
ver('htmlspecialchars convierte', count($tablaEspeciales));   // 5
ver('htmlentities convierte', count($tablaEntidades));         // más de 1500
ver('€ y ©', [htmlentities("€"), htmlentities("©")]);         // ["&euro;", "&copy;"]

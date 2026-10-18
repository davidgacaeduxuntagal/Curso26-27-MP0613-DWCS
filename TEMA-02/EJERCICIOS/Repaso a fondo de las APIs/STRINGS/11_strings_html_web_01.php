<?php
/*
 * 11_strings_html_web_01.php
 *  STRINGS - HTML y WEB (1): escribir texto del usuario en una página HTML de forma segura
 *  Funciones: htmlspecialchars, htmlspecialchars_decode, htmlentities, html_entity_decode,
 *             get_html_translation_table, nl2br, strip_tags
 *
 *  Por qué importa: si escribimos en la página lo que escribe un usuario SIN escaparlo, puede
 *  inyectar HTML o JavaScript (ataque XSS, Cross-Site Scripting).
 *
 *  Ejecuta en modo depuración paso a paso (F10) y mira en el panel VARIABLES cómo quedan los
 *  strings (ahí se ven tal cual, sin que el navegador los interprete).
 */
require_once __DIR__ . '/utilidades.php';

// ---------------------------------------------------------------------------------------------
// htmlspecialchars — convierte & < > " ' en entidades HTML (&amp; &lt; &gt; &quot; &#039;)
// ENLACE: https://www.php.net/manual/es/function.htmlspecialchars.php
// ENUNCIADO EJEMPLO: un usuario escribe en el libro de visitas un comentario con una etiqueta
//   <script>. Lo escapamos antes de mostrarlo para que se vea como texto y no se ejecute.
// NOTA: es LA función a usar siempre que escribas en el HTML datos que vienen del usuario.
//   ENT_QUOTES escapa también la comilla simple (necesario dentro de atributos value='...').
//   Desde PHP 8.1, ENT_QUOTES | ENT_SUBSTITUTE | ENT_HTML401 es el valor por defecto.
// ---------------------------------------------------------------------------------------------
titulo('htmlspecialchars');
$comentario    = "¡Genial! <script>alert('hackeado')</script> & más";
$seguro        = htmlspecialchars($comentario, ENT_QUOTES, 'UTF-8');
ver('original', $comentario);
ver('escapado', $seguro);   // "¡Genial! &lt;script&gt;alert(&#039;hackeado&#039;)&lt;/script&gt; &amp; más"

$nombre        = "O'Neill";
$campoHtml     = "<input name='apellido' value='" . htmlspecialchars($nombre, ENT_QUOTES) . "'>";
ver('dentro de un atributo', $campoHtml);   // value='O&#039;Neill'  (sin escapar se rompería el HTML)

// ---------------------------------------------------------------------------------------------
// htmlspecialchars_decode — operación inversa de htmlspecialchars
// ENLACE: https://www.php.net/manual/es/function.htmlspecialchars-decode.php
// ENUNCIADO EJEMPLO: recuperar el texto original de un comentario que se guardó escapado.
// ---------------------------------------------------------------------------------------------
titulo('htmlspecialchars_decode');
$recuperado    = htmlspecialchars_decode($seguro, ENT_QUOTES);
ver('decodificado', $recuperado);
ver('¿igual al original?', $recuperado === $comentario);    // true

// ---------------------------------------------------------------------------------------------
// htmlentities — convierte TODOS los caracteres que tienen entidad HTML (á -> &aacute;, € -> &euro;...)
// ENLACE: https://www.php.net/manual/es/function.htmlentities.php
// ENUNCIADO EJEMPLO: preparar un texto con tildes y el símbolo del euro para un sistema antiguo
//   que solo admite ASCII.
// NOTA: con páginas en UTF-8 normalmente basta htmlspecialchars; htmlentities hace el texto
//   más largo y menos legible.
// ---------------------------------------------------------------------------------------------
titulo('htmlentities');
$oferta        = "Camión de juguete: 15€ <¡oferta!>";
$entidades     = htmlentities($oferta, ENT_QUOTES, 'UTF-8');
$especiales    = htmlspecialchars($oferta, ENT_QUOTES, 'UTF-8');
ver('htmlentities', $entidades);       // "Cami&oacute;n de juguete: 15&euro; &lt;&iexcl;oferta!&gt;"
ver('htmlspecialchars', $especiales);  // solo cambia < y >

// ---------------------------------------------------------------------------------------------
// html_entity_decode — convierte TODAS las entidades HTML en sus caracteres
// ENLACE: https://www.php.net/manual/es/function.html-entity-decode.php
// ENUNCIADO EJEMPLO: un texto copiado del código fuente de una web contiene entidades; lo
//   convertimos en texto normal para guardarlo.
// ---------------------------------------------------------------------------------------------
titulo('html_entity_decode');
$deLaWeb       = "Caf&eacute; &amp; t&eacute; por 3&nbsp;&euro;";
$texto         = html_entity_decode($deLaWeb, ENT_QUOTES | ENT_HTML5, 'UTF-8');
ver('html_entity_decode', $texto);     // "Café & té por 3 €" (con espacio no separable)

// ---------------------------------------------------------------------------------------------
// get_html_translation_table — devuelve la tabla de traducción que usan las funciones anteriores
// ENLACE: https://www.php.net/manual/es/function.get-html-translation-table.php
// ENUNCIADO EJEMPLO: ver exactamente qué caracteres cambia htmlspecialchars, y cuántos cambia
//   htmlentities.
// ---------------------------------------------------------------------------------------------
titulo('get_html_translation_table');
$tablaEspeciales = get_html_translation_table(HTML_SPECIALCHARS, ENT_QUOTES);
ver('tabla de htmlspecialchars', $tablaEspeciales);   // ['"' => '&quot;', '&' => '&amp;', ...]

$tablaEntidades  = get_html_translation_table(HTML_ENTITIES, ENT_QUOTES | ENT_HTML5);
ver('nº de entidades en HTML5', count($tablaEntidades));
ver('entidad de "ñ"', $tablaEntidades['ñ']);         // "&ntilde;"

// ---------------------------------------------------------------------------------------------
// nl2br — inserta <br> delante de cada salto de línea
// ENLACE: https://www.php.net/manual/es/function.nl2br.php
// ENUNCIADO EJEMPLO: mostrar en la página el texto de un <textarea> respetando los saltos de
//   línea que escribió el usuario (en HTML los \n no se ven).
// NOTA: el orden correcto es nl2br(htmlspecialchars($texto)): primero escapar, después <br>.
// ---------------------------------------------------------------------------------------------
titulo('nl2br');
$direccion     = "Calle Mayor, 5\n36380 Gondomar\nPontevedra";
$conBr         = nl2br(htmlspecialchars($direccion));
ver('nl2br', $conBr);   // "Calle Mayor, 5<br />\n36380 Gondomar<br />\nPontevedra"

// ---------------------------------------------------------------------------------------------
// strip_tags — elimina las etiquetas HTML (y PHP) de un texto
// ENLACE: https://www.php.net/manual/es/function.strip-tags.php
// ENUNCIADO EJEMPLO: generar el resumen en texto plano de una noticia escrita en HTML, y
//   permitir solo algunas etiquetas (<b> e <i>) en la biografía de un usuario.
// NOTA: NO es una protección completa contra XSS (por ejemplo, deja los atributos de las
//   etiquetas permitidas, como onclick). Para mostrar texto, usa htmlspecialchars.
// ---------------------------------------------------------------------------------------------
titulo('strip_tags');
$noticia       = "<h1>Gran victoria</h1><p>El equipo <b>local</b> ganó <a href='#'>ayer</a>.</p>";
$textoPlano    = strip_tags($noticia);                         // "Gran victoriaEl equipo local ganó ayer."
ver('strip_tags', $textoPlano);

$bio           = "<b>Programadora</b> en <i>PHP</i>. <script>alert(1)</script><img src=x>";
$bioLimpia     = strip_tags($bio, "<b><i>");                   // conserva <b> e <i>; el texto "alert(1)" queda como texto
ver('strip_tags permitiendo <b><i>', $bioLimpia);

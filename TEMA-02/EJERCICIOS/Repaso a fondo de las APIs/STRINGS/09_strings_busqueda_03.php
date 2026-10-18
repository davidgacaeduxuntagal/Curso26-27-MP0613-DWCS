<?php
/*
 * 09_strings_busqueda_03.php
 *  STRINGS - BÚSQUEDA (3): buscar y REEMPLAZAR
 *  Funciones: str_replace, str_ireplace, substr_replace, strtr
 *
 *  Ejecuta en modo depuración paso a paso (F10). Compara cada string antes y después del reemplazo.
 */
require_once __DIR__ . '/utilidades.php';

// ---------------------------------------------------------------------------------------------
// str_replace — reemplaza TODAS las apariciones de un texto por otro (distingue mayúsculas)
// ENLACE: https://www.php.net/manual/es/function.str-replace.php
// ENUNCIADO EJEMPLO: rellenar una plantilla de correo electrónico sustituyendo {nombre} y
//   {fecha}; convertir un precio "25.90" a formato español "25,90"; y saber cuántas
//   sustituciones se han hecho.
// NOTA: se pueden pasar ARRAYS: el 1er elemento de buscar se cambia por el 1º de reemplazar,
//   etc. El 4º parámetro (por referencia) recibe el número de reemplazos hechos.
// ---------------------------------------------------------------------------------------------
titulo('str_replace');
$precio        = "25.90";
$precioEs      = str_replace(".", ",", $precio);           // "25,90"
ver('str_replace', $precioEs);

$plantilla     = "Hola {nombre}, tu cita es el {fecha}. Recuerda, {nombre}: sé puntual.";
$correo        = str_replace(["{nombre}", "{fecha}"], ["Ana", "20/10/2026"], $plantilla, $cambios);
ver('plantilla rellenada', $correo);
ver('número de reemplazos', $cambios);                    // 3 (2 nombres + 1 fecha)

$sinEspacios   = str_replace(" ", "", "ES91 2100 0418 4502");   // "ES91210004184502"
ver('quitar espacios', $sinEspacios);

// ---------------------------------------------------------------------------------------------
// str_ireplace — como str_replace pero SIN distinguir mayúsculas
// ENLACE: https://www.php.net/manual/es/function.str-ireplace.php
// ENUNCIADO EJEMPLO: censurar palabras prohibidas en los comentarios de un foro, las escriba el
//   usuario como las escriba.
// ---------------------------------------------------------------------------------------------
titulo('str_ireplace');
$comentario    = "Este juego es una CACA, una caca total";
$censurado     = str_ireplace("caca", "****", $comentario);   // "Este juego es una ****, una **** total"
ver('str_ireplace', $censurado);

$conStrReplace = str_replace("caca", "****", $comentario);    // solo cambia la minúscula
ver('con str_replace (distingue mayúsculas)', $conStrReplace);

// ---------------------------------------------------------------------------------------------
// substr_replace — reemplaza el texto que hay en una POSICIÓN concreta (no busca nada)
// ENLACE: https://www.php.net/manual/es/function.substr-replace.php
// ENUNCIADO EJEMPLO: ocultar los dígitos centrales de un teléfono (612***678), insertar un
//   texto en una posición y recortar un título largo añadiendo "...".
// NOTA: parámetros: (texto, reemplazo, inicio, longitud). Con longitud 0 INSERTA sin borrar.
// ---------------------------------------------------------------------------------------------
titulo('substr_replace');
$telefono      = "612345678";
$oculto        = substr_replace($telefono, "***", 3, 3);   // "612***678"
ver('teléfono oculto', $oculto);

$saludo        = "Hola mundo";
$insertado     = substr_replace($saludo, " bonito", 4, 0); // "Hola bonito mundo"
ver('insertar con longitud 0', $insertado);

$titulo        = "Desarrollo web en entorno servidor con PHP y MySQL";
$corto         = substr_replace($titulo, "...", 25);       // corta desde la posición 25
ver('título recortado', $corto);

// ---------------------------------------------------------------------------------------------
// strtr — traduce caracteres o reemplaza varios textos a la vez
// ENLACE: https://www.php.net/manual/es/function.strtr.php
// ENUNCIADO EJEMPLO: (1) generar el "slug" de una URL quitando tildes y cambiando espacios por
//   guiones, carácter a carácter; (2) intercambiar dos palabras de golpe.
// NOTA: dos formas de uso:
//   - strtr($texto, "abc", "xyz"): cambia a->x, b->y, c->z (carácter a carácter, de BYTES:
//     no sirve para tildes en UTF-8).
//   - strtr($texto, ["buscar" => "reemplazo", ...]): con un array. Prueba primero los textos más
//     largos y NO vuelve a reemplazar lo ya reemplazado (str_replace sí lo haría).
// ---------------------------------------------------------------------------------------------
titulo('strtr');
$tituloNoticia = "Campeón de Galicia en atletismo";
$slug          = strtolower(strtr($tituloNoticia, ["á" => "a", "é" => "e", "í" => "i", "ó" => "o", "ú" => "u", "ñ" => "n", " " => "-"]));
ver('slug', $slug);                                       // "campeon-de-galicia-en-atletismo"

$codigo        = strtr("2026-10-16", "-", "/");           // "2026/10/16" (carácter a carácter)
ver('strtr carácter a carácter', $codigo);

$frase         = "el perro persigue al gato";
$intercambio   = strtr($frase, ["perro" => "gato", "gato" => "perro"]);   // "el gato persigue al perro"
$conReplace    = str_replace(["perro", "gato"], ["gato", "perro"], $frase); // "el perro persigue al perro" (!)
ver('intercambio con strtr', $intercambio);
ver('intercambio con str_replace (mal)', $conReplace);

<?php
/*
 * 07_strings_busqueda_01.php
 *  STRINGS - BÚSQUEDA (1): ¿contiene?, ¿empieza / termina por?, ¿en qué posición está?
 *  Funciones: str_contains, str_starts_with, str_ends_with, strpos, stripos, strrpos, strripos
 *
 *  Ejecuta en modo depuración paso a paso (F10). Presta mucha atención a la diferencia entre
 *  0 (encontrado en la posición 0) y false (no encontrado).
 */
require_once __DIR__ . '/utilidades.php';

// ---------------------------------------------------------------------------------------------
// str_contains — ¿contiene un string a otro? (PHP 8)
// ENLACE: https://www.php.net/manual/es/function.str-contains.php
// ENUNCIADO EJEMPLO: rechazar comentarios de un foro que contengan un enlace ("http").
// NOTA: distingue mayúsculas. Un string vacío como "aguja" siempre se considera contenido.
// ---------------------------------------------------------------------------------------------
titulo('str_contains');
$comentario    = "Mira mi web: http://ofertas-falsas.example";
$tieneEnlace   = str_contains($comentario, "http");       // true
ver('¿contiene un enlace?', $tieneEnlace);

$distingue     = str_contains($comentario, "MIRA");       // false (distingue mayúsculas)
ver('str_contains("MIRA")', $distingue);

// ---------------------------------------------------------------------------------------------
// str_starts_with / str_ends_with — ¿empieza / termina por...? (PHP 8)
// ENLACE: https://www.php.net/manual/es/function.str-starts-with.php
// ENLACE: https://www.php.net/manual/es/function.str-ends-with.php
// ENUNCIADO EJEMPLO: saber si un teléfono es un móvil español (+34 6...) y si una imagen subida
//   es un ".png".
// ---------------------------------------------------------------------------------------------
titulo('str_starts_with / str_ends_with');
$telefono      = "+34 612 345 678";
$esMovil       = str_starts_with($telefono, "+34 6");     // true
ver('¿móvil español?', $esMovil);

$imagen        = "avatar.png";
$esPng         = str_ends_with($imagen, ".png");          // true
$esPngMayus    = str_ends_with("AVATAR.PNG", ".png");      // false: distingue mayúsculas
ver('¿termina en .png?', $esPng);
ver('¿"AVATAR.PNG" termina en .png?', $esPngMayus);

// ---------------------------------------------------------------------------------------------
// strpos — posición de la PRIMERA aparición (o false si no está). Distingue mayúsculas.
// ENLACE: https://www.php.net/manual/es/function.strpos.php
// ENUNCIADO EJEMPLO: encontrar dónde está la "@" de un email para separar usuario y dominio,
//   y el error típico de comprobar el resultado con if (strpos(...)).
// NOTA: ¡CUIDADO! Si lo buscado está al principio devuelve 0, que en un if es FALSO.
//   Compara SIEMPRE con !== false. El tercer parámetro indica desde dónde empezar a buscar.
// ---------------------------------------------------------------------------------------------
titulo('strpos');
$email         = "ana@iesrodeira.gal";
$posArroba     = strpos($email, "@");                     // 3
$dominio       = substr($email, $posArroba + 1);           // "iesrodeira.gal"
ver('strpos("@")', $posArroba);
ver('dominio', $dominio);

$frase         = "PHP es un lenguaje de servidor";
$posPHP        = strpos($frase, "PHP");                   // 0  <- está al principio
$malComprobado = $posPHP ? "encontrado" : "NO encontrado"; // "NO encontrado" (¡error!)
$bienComprobado = $posPHP !== false ? "encontrado" : "NO encontrado";
ver('strpos("PHP")', $posPHP);
ver('comprobado con if ($pos)', $malComprobado);
ver('comprobado con !== false', $bienComprobado);

$noEsta        = strpos($frase, "Java");                  // false
ver('strpos("Java")', $noEsta);

$segundaE      = strpos($frase, "e", strpos($frase, "e") + 1);   // segunda "e"
ver('posición de la segunda "e"', $segundaE);

// ---------------------------------------------------------------------------------------------
// stripos — como strpos pero SIN distinguir mayúsculas
// ENLACE: https://www.php.net/manual/es/function.stripos.php
// ENUNCIADO EJEMPLO: buscar la palabra "oferta" en el asunto de un correo, se escriba como se escriba.
// ---------------------------------------------------------------------------------------------
titulo('stripos');
$asunto        = "¡¡SUPER OFERTA solo hoy!!";
$posOferta     = stripos($asunto, "oferta");              // 10 (los "¡" ocupan 2 bytes cada uno)
$esPublicidad  = $posOferta !== false;                    // true
ver('stripos("oferta")', $posOferta);
ver('¿publicidad?', $esPublicidad);

// ---------------------------------------------------------------------------------------------
// strrpos — posición de la ÚLTIMA aparición (distingue mayúsculas)
// ENLACE: https://www.php.net/manual/es/function.strrpos.php
// ENUNCIADO EJEMPLO: obtener la extensión de un fichero que tiene varios puntos en el nombre
//   ("copia.de.seguridad.tar.gz" -> "gz") y el nombre del fichero en una ruta.
// ---------------------------------------------------------------------------------------------
titulo('strrpos');
$fichero       = "copia.de.seguridad.tar.gz";
$ultimoPunto   = strrpos($fichero, ".");                  // 22
$extension     = substr($fichero, $ultimoPunto + 1);       // "gz"
ver('strrpos(".")', $ultimoPunto);
ver('extensión', $extension);

$ruta          = "/var/www/html/tema2/index.php";
$nombreFichero = substr($ruta, strrpos($ruta, "/") + 1);   // "index.php"
ver('nombre del fichero', $nombreFichero);

// ---------------------------------------------------------------------------------------------
// strripos — última aparición SIN distinguir mayúsculas
// ENLACE: https://www.php.net/manual/es/function.strripos.php
// ENUNCIADO EJEMPLO: en un historial de mensajes, saber dónde aparece por última vez la palabra
//   "error", escrita como sea.
// ---------------------------------------------------------------------------------------------
titulo('strripos');
$log           = "Error al conectar. Reintentando... ERROR de nuevo. Conectado.";
$ultimoError   = strripos($log, "error");                 // 35
ver('strripos("error")', $ultimoError);
ver('desde el último error', substr($log, $ultimoError));

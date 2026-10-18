<?php
/*
 * 08_strings_busqueda_02.php
 *  STRINGS - BÚSQUEDA (2): obtener el trozo de texto a partir de lo encontrado y contar apariciones
 *  Funciones: strstr / strchr, stristr, strrchr, strpbrk, substr_count
 *
 *  A diferencia de strpos (que devuelve una POSICIÓN), estas funciones devuelven el TEXTO.
 *  Ejecuta en modo depuración paso a paso (F10).
 */
require_once __DIR__ . '/utilidades.php';

// ---------------------------------------------------------------------------------------------
// strstr (alias: strchr) — devuelve el texto desde la PRIMERA aparición (incluida) hasta el final
// ENLACE: https://www.php.net/manual/es/function.strstr.php
// ENLACE: https://www.php.net/manual/es/function.strchr.php
// ENUNCIADO EJEMPLO: obtener el dominio de un email ("@iesrodeira.gal") y, con el tercer
//   parámetro a true, lo que hay ANTES ("ana").
// NOTA: devuelve false si no lo encuentra. Distingue mayúsculas.
// ---------------------------------------------------------------------------------------------
titulo('strstr / strchr');
$email         = "ana@iesrodeira.gal";
$conArroba     = strstr($email, "@");                // "@iesrodeira.gal"
$antes         = strstr($email, "@", true);          // "ana"
ver('strstr', $conArroba);
ver('strstr(..., true)', $antes);

$alias         = strchr($email, "@");                // igual que strstr
ver('strchr', $alias);

$noEncontrado  = strstr($email, "#");                // false
ver('strstr no encontrado', $noEncontrado);

// ---------------------------------------------------------------------------------------------
// stristr — como strstr pero SIN distinguir mayúsculas
// ENLACE: https://www.php.net/manual/es/function.stristr.php
// ENUNCIADO EJEMPLO: de una línea de un fichero de configuración "DB_HOST = localhost" obtener la
//   parte a partir de "host", se escriba como se escriba.
// ---------------------------------------------------------------------------------------------
titulo('stristr');
$config        = "DB_HOST = localhost";
$desdeHost     = stristr($config, "host");           // "HOST = localhost"
ver('stristr', $desdeHost);

// ---------------------------------------------------------------------------------------------
// strrchr — devuelve el texto desde la ÚLTIMA aparición de un CARÁCTER hasta el final
// ENLACE: https://www.php.net/manual/es/function.strrchr.php
// ENUNCIADO EJEMPLO: obtener la extensión de un fichero (con el punto) y la última parte de una URL.
// NOTA: si le pasas varios caracteres, solo usa el PRIMERO.
// ---------------------------------------------------------------------------------------------
titulo('strrchr');
$fichero       = "informe.final.v2.docx";
$extension     = strrchr($fichero, ".");             // ".docx"
ver('strrchr(".")', $extension);

$url           = "https://www.example.com/productos/zapatillas-running";
$slug          = substr(strrchr($url, "/"), 1);      // "zapatillas-running"
ver('última parte de la URL', $slug);

// ---------------------------------------------------------------------------------------------
// strpbrk — busca la primera aparición de CUALQUIERA de los caracteres indicados
// ENLACE: https://www.php.net/manual/es/function.strpbrk.php
// ENUNCIADO EJEMPLO: comprobar si un nombre de usuario contiene caracteres prohibidos
//   (espacio, /, \, <, >) y ver desde dónde.
// NOTA: devuelve el texto desde ese carácter, o false si no hay ninguno.
// ---------------------------------------------------------------------------------------------
titulo('strpbrk');
$usuario       = "ana<script>";
$prohibido     = strpbrk($usuario, " /\\<>");        // "<script>"
$esValido      = $prohibido === false;               // false
ver('strpbrk', $prohibido);
ver('¿usuario válido?', $esValido);

$usuarioBueno  = strpbrk("ana_perez", " /\\<>");     // false
ver('strpbrk usuario correcto', $usuarioBueno);

// ---------------------------------------------------------------------------------------------
// substr_count — cuenta cuántas veces aparece un texto dentro de otro
// ENLACE: https://www.php.net/manual/es/function.substr-count.php
// ENUNCIADO EJEMPLO: contar las líneas de un texto (número de saltos de línea + 1), cuántas
//   veces aparece una palabra en un artículo y cuántos campos tiene una línea CSV.
// NOTA: distingue mayúsculas y NO cuenta apariciones solapadas ("aaa" tiene 1 "aa", no 2).
// ---------------------------------------------------------------------------------------------
titulo('substr_count');
$texto         = "Línea 1\nLínea 2\nLínea 3";
$lineas        = substr_count($texto, "\n") + 1;     // 3
ver('número de líneas', $lineas);

$articulo      = "PHP es fácil. Con PHP haces webs. PHP está en todas partes.";
$vecesPHP      = substr_count($articulo, "PHP");     // 3
ver('veces que aparece PHP', $vecesPHP);

$csv           = "nombre;apellidos;email;telefono";
$numCampos     = substr_count($csv, ";") + 1;        // 4
ver('campos del CSV', $numCampos);

$solapado      = substr_count("aaa", "aa");          // 1
ver('substr_count("aaa", "aa")', $solapado);

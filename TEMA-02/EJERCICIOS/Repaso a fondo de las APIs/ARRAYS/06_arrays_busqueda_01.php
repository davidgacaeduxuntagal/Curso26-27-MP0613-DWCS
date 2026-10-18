<?php
/*
 * 06_arrays_busqueda_01.php
 *  ARRAYS - BÚSQUEDA: ¿existe este valor? ¿y esta clave? ¿en qué clave está?
 *  Funciones: in_array, array_search, array_key_exists, key_exists (y su diferencia con isset)
 *
 *  Ejecuta en modo depuración paso a paso (F10). Presta atención a la comparación estricta
 *  (tercer parámetro true) y a la diferencia entre 0 y false.
 */
require_once __DIR__ . '/utilidades.php';

// ---------------------------------------------------------------------------------------------
// in_array — ¿contiene el array este VALOR? Devuelve true / false
// ENLACE: https://www.php.net/manual/es/function.in-array.php
// ENUNCIADO EJEMPLO: comprobar que el valor elegido en un desplegable de un formulario es una de
//   las opciones permitidas (lista blanca), y que una extensión de fichero está permitida.
// NOTA: por defecto compara con == (no estricta): in_array("1e1", ["10"]) es true. Usa el tercer
//   parámetro a true (===) para validar datos.
// ---------------------------------------------------------------------------------------------
titulo('in_array');
$tallasPermitidas = ["S", "M", "L", "XL"];
$elegida       = "M";
$esValida      = in_array($elegida, $tallasPermitidas, true);      // true
ver("¿talla '$elegida' válida?", $esValida);

$extensiones   = ["jpg", "png", "gif"];
$subida        = "PDF";
ver("¿extensión '$subida' permitida?", in_array(strtolower($subida), $extensiones, true));   // false

$numeros       = [0, 1, 2];
$noEstricto    = in_array("abc", $numeros);          // false en PHP 8 ("abc" == 0 es false)
$conString     = in_array("1", $numeros);            // true  ("1" == 1)
$estricto      = in_array("1", $numeros, true);      // false ("1" !== 1)
ver('in_array("abc", [0,1,2])', $noEstricto);
ver('in_array("1", [0,1,2])', $conString);
ver('in_array("1", [0,1,2], true)', $estricto);

// ---------------------------------------------------------------------------------------------
// array_search — busca un VALOR y devuelve su CLAVE (o false si no está)
// ENLACE: https://www.php.net/manual/es/function.array-search.php
// ENUNCIADO EJEMPLO: averiguar en qué posición del ranking está un jugador, y el DNI de un
//   alumno a partir de su email.
// NOTA: ¡CUIDADO! Si el valor está en la clave 0, devuelve 0, que en un if es falso: compara con
//   !== false. Si el valor está repetido, devuelve la PRIMERA clave.
// ---------------------------------------------------------------------------------------------
titulo('array_search');
$ranking       = ["Uxía", "Xoan", "Iria", "Brais"];
$posicion      = array_search("Iria", $ranking);         // 2
ver('posición de Iria (empezando en 1)', $posicion + 1); // 3

$lider         = array_search("Uxía", $ranking);         // 0  <- ¡en un if sería falso!
$mal           = $lider ? "está" : "NO está";            // "NO está" (error)
$bien          = $lider !== false ? "está" : "NO está";  // "está"
ver('array_search("Uxía")', $lider);
ver('comprobado con if ($pos)', $mal);
ver('comprobado con !== false', $bien);

$emails        = ['12345678Z' => "ana@x.gal", '87654321X' => "brais@x.gal"];
$dni           = array_search("brais@x.gal", $emails);   // "87654321X"
ver('DNI de brais@x.gal', $dni);

// ---------------------------------------------------------------------------------------------
// array_key_exists (alias: key_exists) — ¿existe esta CLAVE en el array?
// ENLACE: https://www.php.net/manual/es/function.array-key-exists.php
// ENLACE: https://www.php.net/manual/es/function.key-exists.php
// ENUNCIADO EJEMPLO: comprobar si un producto está en el carrito (el carrito es código => cantidad)
//   y si un campo ha llegado en el formulario.
// ---------------------------------------------------------------------------------------------
titulo('array_key_exists / key_exists');
$carrito       = ['P001' => 2, 'P007' => 1];
$estaP007      = array_key_exists('P007', $carrito);     // true
$estaP003      = key_exists('P003', $carrito);           // false (alias)
ver('¿P007 en el carrito?', $estaP007);
ver('¿P003 en el carrito?', $estaP003);

// ---------------------------------------------------------------------------------------------
// array_key_exists frente a isset — la diferencia está en los valores null
// ENLACE: https://www.php.net/manual/es/function.isset.php
// ENUNCIADO EJEMPLO: en los datos de un usuario el campo "telefono" existe pero vale null (no lo
//   ha indicado). isset dice que NO está; array_key_exists dice que SÍ (la clave existe).
// NOTA: isset es más rápido y es lo que se usa normalmente con $_GET / $_POST. El operador ?? se
//   basa en isset: $valor = $array['clave'] ?? 'por defecto';
// ---------------------------------------------------------------------------------------------
titulo('array_key_exists frente a isset');
$usuario       = ['nombre' => "Ana", 'telefono' => null];
ver('isset($usuario["telefono"])', isset($usuario['telefono']));                     // false
ver('array_key_exists("telefono", $usuario)', array_key_exists('telefono', $usuario)); // true
ver('teléfono con ??', $usuario['telefono'] ?? "no indicado");                        // "no indicado"

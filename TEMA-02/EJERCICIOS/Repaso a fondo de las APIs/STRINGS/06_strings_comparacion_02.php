<?php
/*
 * 06_strings_comparacion_02.php
 *  STRINGS - COMPARACIÓN (2): orden "natural", conjuntos de caracteres y parecido entre textos
 *  Funciones: strnatcmp, strnatcasecmp, strspn, strcspn, similar_text
 *
 *  Ejecuta en modo depuración paso a paso (F10). Compara los arrays ordenados de una y otra
 *  forma en el panel VARIABLES.
 */
require_once __DIR__ . '/utilidades.php';

// ---------------------------------------------------------------------------------------------
// strnatcmp — compara con "orden natural": los números dentro del texto se comparan como números
// ENLACE: https://www.php.net/manual/es/function.strnatcmp.php
// ENUNCIADO EJEMPLO: ordenar los nombres de unas fotos. Con strcmp "foto10" queda antes que
//   "foto2" (porque compara carácter a carácter y "1" < "2"); con orden natural, no.
// ---------------------------------------------------------------------------------------------
titulo('strnatcmp');
$fotos         = ["foto10.jpg", "foto2.jpg", "foto1.jpg", "foto20.jpg"];

$ordenNormal   = $fotos;
usort($ordenNormal, 'strcmp');          // ["foto1.jpg", "foto10.jpg", "foto2.jpg", "foto20.jpg"]
ver('con strcmp', $ordenNormal);

$ordenNatural  = $fotos;
usort($ordenNatural, 'strnatcmp');      // ["foto1.jpg", "foto2.jpg", "foto10.jpg", "foto20.jpg"]
ver('con strnatcmp', $ordenNatural);

$version       = strnatcmp("v1.9", "v1.10");   // < 0: la versión 1.9 es anterior a la 1.10
ver('strnatcmp("v1.9", "v1.10")', $version);

// ---------------------------------------------------------------------------------------------
// strnatcasecmp — orden natural SIN distinguir mayúsculas
// ENLACE: https://www.php.net/manual/es/function.strnatcasecmp.php
// ENUNCIADO EJEMPLO: ordenar los temas de un curso escritos con mayúsculas y minúsculas mezcladas.
// ---------------------------------------------------------------------------------------------
titulo('strnatcasecmp');
$temas         = ["tema10", "Tema2", "TEMA1", "tema3"];
usort($temas, 'strnatcasecmp');          // ["TEMA1", "Tema2", "tema3", "tema10"]
ver('con strnatcasecmp', $temas);

// ---------------------------------------------------------------------------------------------
// strspn — longitud del tramo INICIAL formado solo por los caracteres permitidos
// ENLACE: https://www.php.net/manual/es/function.strspn.php
// ENUNCIADO EJEMPLO: comprobar si un número de teléfono contiene solo dígitos (si el tramo
//   inicial de dígitos mide lo mismo que todo el string, es que no hay otros caracteres).
// ---------------------------------------------------------------------------------------------
titulo('strspn');
$telefono      = "986123456";
$tramo         = strspn($telefono, "0123456789");          // 9
$soloDigitos   = $tramo === strlen($telefono);              // true
ver('strspn', $tramo);
ver('¿solo dígitos?', $soloDigitos);

$conLetra      = "98612A456";
$tramo2        = strspn($conLetra, "0123456789");           // 5 (se para en la "A")
ver("strspn('$conLetra')", $tramo2);

// ---------------------------------------------------------------------------------------------
// strcspn — longitud del tramo inicial formado por caracteres que NO están en la lista
// ENLACE: https://www.php.net/manual/es/function.strcspn.php
// ENUNCIADO EJEMPLO: obtener el nombre de usuario de un email (todo lo que hay antes de "@") y
//   comprobar si una contraseña contiene algún dígito.
// ---------------------------------------------------------------------------------------------
titulo('strcspn');
$email         = "ana.perez@iesrodeira.gal";
$hastaArroba   = strcspn($email, "@");                     // 9
$usuario       = substr($email, 0, $hastaArroba);          // "ana.perez"
ver('strcspn', $hastaArroba);
ver('usuario', $usuario);

$password      = "secreto";
$tieneDigito   = strcspn($password, "0123456789") < strlen($password);   // false
ver('¿la contraseña tiene algún dígito?', $tieneDigito);

// ---------------------------------------------------------------------------------------------
// similar_text — calcula cuánto se parecen dos strings (nº de caracteres comunes y porcentaje)
// ENLACE: https://www.php.net/manual/es/function.similar-text.php
// ENUNCIADO EJEMPLO: un buscador sugiere "¿Quisiste decir...?" eligiendo, de una lista de
//   productos, el que más se parece a lo que escribió el usuario.
// NOTA: el porcentaje se devuelve en el TERCER parámetro, que se pasa POR REFERENCIA.
//   Relacionada: levenshtein() (nº de cambios para pasar de un texto a otro).
// ---------------------------------------------------------------------------------------------
titulo('similar_text');
$comunes       = similar_text("portatil", "portátil", $porcentaje);
ver('caracteres comunes', $comunes);
ver('porcentaje', round($porcentaje, 1));

$busqueda      = "teclao";
$productos     = ["ratón", "teclado", "monitor", "altavoz"];
$mejor         = "";
$mejorPorc     = 0;
foreach ($productos as $producto) {
    similar_text($busqueda, $producto, $porc);
    if ($porc > $mejorPorc) {
        $mejorPorc = $porc;
        $mejor     = $producto;
    }
}
ver("¿Quisiste decir...? (para '$busqueda')", $mejor);          // "teclado"

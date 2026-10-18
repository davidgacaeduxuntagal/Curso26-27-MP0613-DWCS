<?php
/*
 * 03_strings_basicas_03.php
 *  STRINGS - BÁSICAS (3): trocear un string en partes y volver a unirlas
 *  Funciones: explode, implode / join, str_split, strtok, parse_str, str_word_count, count_chars
 *
 *  Ejecuta en modo depuración paso a paso (F10). Despliega en el panel VARIABLES los ARRAYS que
 *  devuelven estas funciones para ver sus claves y valores.
 */
require_once __DIR__ . '/utilidades.php';

// ---------------------------------------------------------------------------------------------
// explode — divide un string en un array, usando un separador
// ENLACE: https://www.php.net/manual/es/function.explode.php
// ENUNCIADO EJEMPLO: una línea de un fichero de alumnos tiene los campos separados por ";".
//   Obtener cada campo por separado. Obtener también el usuario y el dominio de un email.
// NOTA: el tercer parámetro (límite) indica el número máximo de trozos.
// ---------------------------------------------------------------------------------------------
titulo('explode');
$linea         = "12345678Z;Ana;Pérez Lago;DAW2";
$campos        = explode(";", $linea);                 // [0 => "12345678Z", 1 => "Ana", ...]
ver('explode', $campos);

[$dni, $nombre, $apellidos, $grupo] = $campos;         // desestructuración del array
ver('grupo', $grupo);

$email         = "ana.perez@iesrodeira.gal";
[$usuario, $dominio] = explode("@", $email);
ver('usuario', $usuario);
ver('dominio', $dominio);

$ruta          = "img/fotos/2026/playa.jpg";
$dosPartes     = explode("/", $ruta, 2);               // ["img", "fotos/2026/playa.jpg"]
ver('explode con límite 2', $dosPartes);

// ---------------------------------------------------------------------------------------------
// implode (alias: join) — une los elementos de un array en un string, con un separador
// ENLACE: https://www.php.net/manual/es/function.implode.php
// ENLACE: https://www.php.net/manual/es/function.join.php
// ENUNCIADO EJEMPLO: mostrar la lista de módulos de un alumno separada por comas y construir
//   una ruta de carpetas.
// ---------------------------------------------------------------------------------------------
titulo('implode / join');
$modulos       = ["DWCS", "DWCC", "DIW", "DAW"];
$lista         = implode(", ", $modulos);              // "DWCS, DWCC, DIW, DAW"
ver('implode', $lista);

$carpetas      = ["home", "alumno", "proyectos"];
$rutaUnida     = "/" . join("/", $carpetas);           // "/home/alumno/proyectos"
ver('join', $rutaUnida);

// ---------------------------------------------------------------------------------------------
// str_split — divide un string en trozos de longitud fija (por defecto, de 1 en 1)
// ENLACE: https://www.php.net/manual/es/function.str-split.php
// ENUNCIADO EJEMPLO: sumar los dígitos de un número (como en algunos dígitos de control) y
//   agrupar un número de teléfono en pares.
// NOTA: trabaja con BYTES: para textos con tildes usa mb_str_split.
// ---------------------------------------------------------------------------------------------
titulo('str_split');
$numero        = "2026";
$digitos       = str_split($numero);                   // ["2", "0", "2", "6"]
$suma          = array_sum($digitos);                  // 10 (PHP convierte "2" en 2 al sumar)
ver('str_split', $digitos);
ver('suma de dígitos', $suma);

$telefono      = "986123456";
$grupos        = str_split($telefono, 3);              // ["986", "123", "456"]
ver('str_split de 3 en 3', implode(" ", $grupos));

// ---------------------------------------------------------------------------------------------
// strtok — va devolviendo los "tokens" (trozos) de un string, uno en cada llamada
// ENLACE: https://www.php.net/manual/es/function.strtok.php
// ENUNCIADO EJEMPLO: recorrer las palabras de una frase separadas por espacios, comas o puntos.
// NOTA: la PRIMERA llamada recibe el string; las siguientes SOLO el separador (recuerda el
//   string internamente). Admite varios separadores a la vez. Devuelve false al terminar.
//   Observa en el depurador cómo cambia $token en cada vuelta del bucle.
// ---------------------------------------------------------------------------------------------
titulo('strtok');
$frase         = "PHP, MySQL y Apache. Pila LAMP";
$palabras      = [];
$token         = strtok($frase, " ,.");
while ($token !== false) {
    $palabras[] = $token;
    $token      = strtok(" ,.");
}
ver('strtok', $palabras);                               // ["PHP", "MySQL", "y", "Apache", "Pila", "LAMP"]

// ---------------------------------------------------------------------------------------------
// parse_str — convierte una "query string" (lo que va tras el ? de una URL) en un array
// ENLACE: https://www.php.net/manual/es/function.parse-str.php
// ENUNCIADO EJEMPLO: obtener los parámetros de la URL de una búsqueda de una tienda online.
// NOTA: desde PHP 8 el segundo parámetro (el array resultado) es OBLIGATORIO. Decodifica los %
//   y los +, y entiende la sintaxis de arrays (talla[]=M&talla[]=L), igual que $_GET.
// ---------------------------------------------------------------------------------------------
titulo('parse_str');
$query         = "q=zapatillas+running&precio_max=80&talla[]=42&talla[]=43&marca=Ni%C3%B1o";
parse_str($query, $parametros);
ver('parse_str', $parametros);   // ['q' => 'zapatillas running', 'precio_max' => '80', 'talla' => ['42','43'], ...]

// ---------------------------------------------------------------------------------------------
// str_word_count — cuenta (o devuelve) las palabras de un texto
// ENLACE: https://www.php.net/manual/es/function.str-word-count.php
// ENUNCIADO EJEMPLO: contar las palabras de la descripción de un producto (máximo 50 palabras).
// NOTA: el segundo parámetro: 0 = número de palabras, 1 = array de palabras, 2 = array con la
//   posición de cada palabra. ¡OJO! Solo considera letras ASCII: la ñ y las tildes "rompen"
//   las palabras. Se arregla indicando esos caracteres en el tercer parámetro.
// ---------------------------------------------------------------------------------------------
titulo('str_word_count');
$descripcion   = "Zapatillas ligeras para correr por asfalto";
$total         = str_word_count($descripcion);                 // 6
ver('str_word_count', $total);

$enEspanol     = "El niño comió";
$mal           = str_word_count($enEspanol);                   // 4 (¡mal!) "ni", "o" ...
$listaMal      = str_word_count($enEspanol, 1);                // mira qué palabras salen
$bien          = str_word_count($enEspanol, 0, "áéíóúñÁÉÍÓÚÑ"); // 3
ver('str_word_count con ñ (mal)', $mal);
ver('palabras detectadas (mal)', $listaMal);
ver('str_word_count indicando ñ y tildes', $bien);

// ---------------------------------------------------------------------------------------------
// count_chars — información sobre los caracteres (bytes) usados en un string
// ENLACE: https://www.php.net/manual/es/function.count-chars.php
// ENUNCIADO EJEMPLO: contar cuántas veces aparece cada letra en un texto (análisis de
//   frecuencias, como en los criptogramas) y obtener los caracteres distintos que se usan.
// NOTA: modo 1 = array [código ASCII => veces] (solo los que aparecen); modo 3 = string con
//   los caracteres distintos ordenados. Las claves son CÓDIGOS: usa chr() para verlas como letra.
// ---------------------------------------------------------------------------------------------
titulo('count_chars');
$texto         = "banana";
$frecuencias   = count_chars($texto, 1);            // [97 => 3, 98 => 1, 110 => 2]
ver('count_chars modo 1', $frecuencias);

$conLetras     = [];
foreach ($frecuencias as $codigo => $veces) {
    $conLetras[chr($codigo)] = $veces;              // ['a' => 3, 'b' => 1, 'n' => 2]
}
ver('frecuencias por letra', $conLetras);

$distintos     = count_chars($texto, 3);            // "abn"
ver('count_chars modo 3', $distintos);

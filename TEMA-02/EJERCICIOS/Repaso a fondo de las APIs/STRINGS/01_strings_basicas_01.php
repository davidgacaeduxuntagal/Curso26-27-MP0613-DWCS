<?php
/*
 * 01_strings_basicas_01.php
 *  STRINGS - BÁSICAS (1): longitud, mayúsculas / minúsculas y repetición
 *  Funciones: strlen, strtolower, strtoupper, ucfirst, lcfirst, ucwords, strrev, str_repeat
 *
 *  Cómo trabajar este fichero:
 *    1. Pon un punto de ruptura en la primera línea de código de cada ejemplo.
 *    2. Ejecuta en modo depuración (F5) y avanza línea a línea con F10.
 *    3. Antes de ejecutar cada línea, PIENSA qué valor tendrá la variable; después compruébalo
 *       en el panel VARIABLES.
 */
require_once __DIR__ . '/utilidades.php';

// ---------------------------------------------------------------------------------------------
// strlen — devuelve la longitud de un string, en BYTES
// ENLACE: https://www.php.net/manual/es/function.strlen.php
// ENUNCIADO EJEMPLO: comprobar si la contraseña que escribe un usuario en un formulario de
//   registro tiene al menos 8 caracteres.
// NOTA: cuenta BYTES, no caracteres. En UTF-8 la "ñ" y las vocales con tilde ocupan 2 bytes,
//   por eso strlen("año") es 4. Para contar caracteres se usa mb_strlen (17_strings_i18n_02.php).
// ---------------------------------------------------------------------------------------------
titulo('strlen');
$password      = "secreto1";
$longitud      = strlen($password);      // 8
$esValida      = $longitud >= 8;         // true
ver("strlen('$password')", $longitud);
ver('¿contraseña válida?', $esValida);

$palabra       = "año";
$bytes         = strlen($palabra);       // ¡4, no 3!
ver("strlen('$palabra')", $bytes);

// ---------------------------------------------------------------------------------------------
// strtolower / strtoupper — pasan un string a minúsculas / mayúsculas
// ENLACE: https://www.php.net/manual/es/function.strtolower.php
// ENLACE: https://www.php.net/manual/es/function.strtoupper.php
// ENUNCIADO EJEMPLO: los correos electrónicos no distinguen mayúsculas, así que antes de
//   guardarlos o compararlos los pasamos a minúsculas. Los códigos de matrícula se guardan en
//   mayúsculas.
// NOTA: desde PHP 8.2 solo cambian las letras ASCII (A-Z). Las letras con tilde y la ñ NO
//   cambian: usa mb_strtolower / mb_strtoupper para textos en español.
// ---------------------------------------------------------------------------------------------
titulo('strtolower / strtoupper');
$email         = "Ana.Perez@IESRodeira.GAL";
$emailNormal   = strtolower($email);      // "ana.perez@iesrodeira.gal"
ver('strtolower', $emailNormal);

$matricula     = "1234 bcd";
$matriculaMay  = strtoupper($matricula);  // "1234 BCD"
ver('strtoupper', $matriculaMay);

$conTilde      = strtoupper("camión");    // "CAMIóN"  <- la ó no cambia
ver('strtoupper con tilde', $conTilde);

// ---------------------------------------------------------------------------------------------
// ucfirst / lcfirst — primera letra en mayúscula / minúscula
// ENLACE: https://www.php.net/manual/es/function.ucfirst.php
// ENLACE: https://www.php.net/manual/es/function.lcfirst.php
// ENUNCIADO EJEMPLO: construir una frase que empieza con mayúscula a partir de un texto en
//   minúsculas, y obtener el nombre "camelCase" de una variable a partir de "PascalCase".
// ---------------------------------------------------------------------------------------------
titulo('ucfirst / lcfirst');
$mensaje       = "bienvenido al curso";
$frase         = ucfirst($mensaje);        // "Bienvenido al curso"
ver('ucfirst', $frase);

$clase         = "NumeroDeAlumnos";
$variable      = lcfirst($clase);          // "numeroDeAlumnos"
ver('lcfirst', $variable);

// ---------------------------------------------------------------------------------------------
// ucwords — pone en mayúscula la primera letra de CADA palabra
// ENLACE: https://www.php.net/manual/es/function.ucwords.php
// ENUNCIADO EJEMPLO: normalizar el nombre y apellidos que un usuario escribe todo en minúsculas.
// NOTA: el segundo parámetro (opcional) indica qué caracteres separan palabras (por defecto
//   espacios, tabuladores y saltos de línea). Si el texto viene en MAYÚSCULAS, primero hay que
//   pasarlo a minúsculas.
// ---------------------------------------------------------------------------------------------
titulo('ucwords');
$nombre        = "juan lópez garcía";
$nombreBonito  = ucwords($nombre);                      // "Juan López García"
ver('ucwords', $nombreBonito);

$compuesto     = "maría rodríguez-castro";
$sinGuion      = ucwords($compuesto);                   // "María Rodríguez-castro"
$conGuion      = ucwords($compuesto, " -");             // "María Rodríguez-Castro"
ver('ucwords sin delimitador', $sinGuion);
ver('ucwords con " -"', $conGuion);

$gritando      = "PEDRO SÁNCHEZ";
$arreglado     = ucwords(strtolower($gritando));        // "Pedro SÁnchez" <- ¡ojo con la Á!
ver('ucwords(strtolower(...))', $arreglado);

// ---------------------------------------------------------------------------------------------
// strrev — invierte un string
// ENLACE: https://www.php.net/manual/es/function.strrev.php
// ENUNCIADO EJEMPLO: comprobar si una palabra es un palíndromo (se lee igual al revés).
// NOTA: invierte BYTES: con letras de varios bytes (ñ, tildes) el resultado se estropea.
// ---------------------------------------------------------------------------------------------
titulo('strrev');
$candidata     = "reconocer";
$alReves       = strrev($candidata);              // "reconocer"
$esPalindromo  = $candidata === $alReves;         // true
ver('strrev', $alReves);
ver('¿palíndromo?', $esPalindromo);

$roto          = strrev("niño");                  // bytes al revés: carácter inválido
ver('strrev("niño") (estropeado)', $roto);

// ---------------------------------------------------------------------------------------------
// str_repeat — repite un string N veces
// ENLACE: https://www.php.net/manual/es/function.str-repeat.php
// ENUNCIADO EJEMPLO: ocultar un número de tarjeta mostrando solo los 4 últimos dígitos, y dibujar
//   una barra de progreso de texto.
// ---------------------------------------------------------------------------------------------
titulo('str_repeat');
$tarjeta       = "4111222233334444";
$oculta        = str_repeat("*", 12) . "4444";              // "************4444"
ver('tarjeta oculta', $oculta);

$porcentaje    = 70;
$barra         = "[" . str_repeat("#", $porcentaje / 10) . str_repeat(".", 10 - $porcentaje / 10) . "] {$porcentaje}%";
ver('barra de progreso', $barra);                           // "[#######...] 70%"

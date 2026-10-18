<?php
/*
 * 09_strings_busqueda_03_problema_solucion.php
 *  (FICHERO CON LAS SOLUCIONES)
 *  Problemas de: str_replace, str_ireplace, substr_replace, strtr
 *  (repasa los ejemplos de ../09_strings_busqueda_03.php)
 *
 *  Resuelve cada problema donde se indica y ejecútalo en modo depuración para comprobarlo.
 */
require_once __DIR__ . '/../utilidades.php';

// =============================================================================================
// PROBLEMA 1. Plantilla de un certificado
//  Rellena la plantilla con los datos del array $datos. Las claves del array, entre llaves, son
//  los marcadores ({nombre}, {curso}...). Usa str_replace con dos arrays.
// =============================================================================================
titulo('Problema 1');
$plantilla = "Se certifica que {nombre} ha superado el curso {curso} con una nota de {nota}.";
$datos     = ['nombre' => "Ana Pérez", 'curso' => "DAW", 'nota' => "8,5"];
// completa aquí tu solución
// ---- SOLUCIÓN ----
$marcadores = [];
foreach (array_keys($datos) as $clave) {
    $marcadores[] = "{" . $clave . "}";
}
$certificado = str_replace($marcadores, array_values($datos), $plantilla);
ver('certificado', $certificado);

// =============================================================================================
// PROBLEMA 2. Filtro de palabras malsonantes
//  Sustituye cada palabra prohibida por tantos asteriscos como letras tenga, sin distinguir
//  mayúsculas. ¿Cuántas sustituciones se han hecho?
// =============================================================================================
titulo('Problema 2');
$prohibidas = ["tonto", "feo"];
$mensaje    = "Eres TONTO y feo, muy tonto";
// completa aquí tu solución
// ---- SOLUCIÓN ----
$asteriscos = [];
foreach ($prohibidas as $palabra) {
    $asteriscos[] = str_repeat("*", strlen($palabra));
}
$filtrado = str_ireplace($prohibidas, $asteriscos, $mensaje, $cambios);
ver('filtrado', $filtrado);       // "Eres ***** y ***, muy *****"
ver('cambios', $cambios);         // 3

// =============================================================================================
// PROBLEMA 3. Formato de un número de cuenta
//  Inserta un espacio cada 4 caracteres en el IBAN usando substr_replace con longitud 0
//  (empieza por el final para que no se desplacen las posiciones).
// =============================================================================================
titulo('Problema 3');
$iban = "ES9121000418450200051332";
// completa aquí tu solución
// ---- SOLUCIÓN ----
$formateado = $iban;
for ($pos = strlen($iban) - 4; $pos > 0; $pos -= 4) {
    $formateado = substr_replace($formateado, " ", $pos, 0);
}
ver('IBAN', $formateado);         // "ES91 2100 0418 4502 0005 1332"

// =============================================================================================
// PROBLEMA 4. Nombre de fichero sin tildes
//  Convierte el título en un nombre de fichero: sin tildes ni eñes, en minúsculas, con "_" en
//  lugar de espacios y con extensión ".pdf". Usa strtr con un array.
// =============================================================================================
titulo('Problema 4');
$titulo = "Programación Web año 2026";
// completa aquí tu solución
// ---- SOLUCIÓN ----
$cambios = ["á" => "a", "é" => "e", "í" => "i", "ó" => "o", "ú" => "u", "ñ" => "n",
            "Á" => "a", "É" => "e", "Í" => "i", "Ó" => "o", "Ú" => "u", "Ñ" => "n", " " => "_"];
$fichero = strtolower(strtr($titulo, $cambios)) . ".pdf";
ver('fichero', $fichero);         // "programacion_web_ano_2026.pdf"

// =============================================================================================
// PROBLEMA 5. ¿str_replace o strtr?
//  Queremos convertir "a" -> "b" y "b" -> "a" en el texto "abba". ¿Qué devuelve str_replace con
//  arrays? ¿Y strtr? Explica la diferencia en un comentario.
// =============================================================================================
titulo('Problema 5');
$texto = "abba";
// completa aquí tu solución
// ---- SOLUCIÓN ----
$conReplace = str_replace(["a", "b"], ["b", "a"], $texto);   // "aaaa"
$conStrtr   = strtr($texto, ["a" => "b", "b" => "a"]);       // "baab"
// str_replace hace los reemplazos UNO DETRÁS DE OTRO: primero "a"->"b" ("bbbb") y después
// "b"->"a" ("aaaa"). strtr recorre el texto una sola vez y no vuelve a cambiar lo ya cambiado.
ver('str_replace', $conReplace);
ver('strtr', $conStrtr);

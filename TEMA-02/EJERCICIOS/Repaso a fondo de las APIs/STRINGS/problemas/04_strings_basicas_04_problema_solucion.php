<?php
/*
 * 04_strings_basicas_04_problema_solucion.php
 *  (FICHERO CON LAS SOLUCIONES)
 *  Problemas de: ord, chr, quotemeta, echo, print
 *  (repasa los ejemplos de ../04_strings_basicas_04.php)
 *
 *  Resuelve cada problema donde se indica y ejecútalo en modo depuración para comprobarlo.
 */
require_once __DIR__ . '/../utilidades.php';

// =============================================================================================
// PROBLEMA 1. Contraseña fuerte
//  Recorre la contraseña carácter a carácter y, usando ord, cuenta cuántas mayúsculas (65-90),
//  minúsculas (97-122) y dígitos (48-57) tiene. Es fuerte si tiene al menos uno de cada tipo.
// =============================================================================================
titulo('Problema 1');
$password = "Servidor2026";
// completa aquí tu solución
// ---- SOLUCIÓN ----
$mayusculas = $minusculas = $digitos = 0;
for ($i = 0; $i < strlen($password); $i++) {
    $codigo = ord($password[$i]);
    if ($codigo >= 65 && $codigo <= 90) {
        $mayusculas++;
    } elseif ($codigo >= 97 && $codigo <= 122) {
        $minusculas++;
    } elseif ($codigo >= 48 && $codigo <= 57) {
        $digitos++;
    }
}
$esFuerte = $mayusculas > 0 && $minusculas > 0 && $digitos > 0;
ver('mayúsculas / minúsculas / dígitos', [$mayusculas, $minusculas, $digitos]);   // [1, 7, 4]
ver('¿fuerte?', $esFuerte);                                                        // true

// =============================================================================================
// PROBLEMA 2. Descifrar un mensaje César
//  El mensaje se cifró desplazando cada letra mayúscula 3 posiciones hacia delante. Descífralo
//  (desplazando 3 hacia atrás). Los espacios se dejan igual.
// =============================================================================================
titulo('Problema 2');
$cifrado = "SKS HV JHQLDO";
// completa aquí tu solución
// ---- SOLUCIÓN ----
$descifrado = "";
for ($i = 0; $i < strlen($cifrado); $i++) {
    $c = $cifrado[$i];
    if ($c === " ") {
        $descifrado .= " ";
    } else {
        $posicion    = ord($c) - 65;
        $descifrado .= chr(65 + ($posicion - 3 + 26) % 26);   // +26 para no tener negativos
    }
}
ver('descifrado', $descifrado);       // "PHP ES GENIAL"

// =============================================================================================
// PROBLEMA 3. Letras de las columnas de una hoja de cálculo
//  Genera con chr un string con las letras de las columnas de la A a la J separadas por "|":
//  "A|B|C|D|E|F|G|H|I|J".
// =============================================================================================
titulo('Problema 3');
// completa aquí tu solución
// ---- SOLUCIÓN ----
$letras = [];
for ($codigo = ord("A"); $codigo <= ord("J"); $codigo++) {
    $letras[] = chr($codigo);
}
$cabecera = implode("|", $letras);
ver('cabecera', $cabecera);

// =============================================================================================
// PROBLEMA 4. Buscar un texto con caracteres especiales
//  ¿Aparece exactamente "(x+1)*2" en la fórmula? Usa quotemeta para poder buscarlo con preg_match.
// =============================================================================================
titulo('Problema 4');
$formula = "resultado = (x+1)*2 - y";
$buscado = "(x+1)*2";
// completa aquí tu solución
// ---- SOLUCIÓN ----
$aparece = preg_match("/" . quotemeta($buscado) . "/", $formula) === 1;
ver('expresión regular', "/" . quotemeta($buscado) . "/");   // "/\(x\+1\)\*2/"
ver('¿aparece?', $aparece);                                  // true

// =============================================================================================
// PROBLEMA 5. echo y print
//  Escribe con UNA sola instrucción echo (con varios argumentos separados por comas) la línea:
//  "Alumno: Ana | Nota: 8.5". Después, ¿cómo podrías usar print dentro de una expresión con el
//  operador ternario para escribir "Aprobado" o "Suspenso"?
// =============================================================================================
titulo('Problema 5');
$alumno = "Ana";
$nota   = 8.5;
// completa aquí tu solución
// ---- SOLUCIÓN ----
echo "Alumno: ", $alumno, " | Nota: ", $nota, PHP_EOL;
$nota >= 5 ? print "Aprobado" . PHP_EOL : print "Suspenso" . PHP_EOL;   // print sí es una expresión

<?php
/*
 * 01_regexes_basicas_01_problema_solucion.php
 *  (FICHERO CON LAS SOLUCIONES)
 *  Problemas de: literales, escape, el punto, clases [ ], rangos, negación, \d \w \s, anclas ^ $,
 *                modificadores i, u, x
 *  (repasa los ejemplos de ../01_regexes_basicas_01.php)
 *
 *  Escribe el PATRÓN en la variable indicada; el código de comprobación ya está hecho y te muestra
 *  qué textos coinciden. Ejecútalo en modo depuración y ajusta el patrón hasta que funcione.
 */
require_once __DIR__ . '/../utilidades.php';

function probar(string $patron, array $textos): array
{
    $resultado = [];
    foreach ($textos as $texto) {
        $resultado[$texto] = preg_match($patron, $texto);
    }
    return $resultado;
}

// =============================================================================================
// PROBLEMA 1. Código de grupo
//  Los grupos del instituto se llaman "DAW", "DAM" o "ASIR", seguidos de "1" o "2" y de una letra
//  de grupo A o B (por ejemplo "DAW2A"). Escribe un patrón que valide el nombre COMPLETO, sin
//  distinguir mayúsculas. Deben pasar los 3 primeros y fallar los 3 últimos.
// =============================================================================================
titulo('Problema 1');
$textos = ["DAW2A", "dam1b", "ASIR1A", "DAW3A", "SMR1A", "DAW2AB"];
// completa aquí tu solución: $patron1 = '...';
// ---- SOLUCIÓN ----
$patron1 = '/^(DAW|DAM|ASIR)[12][AB]$/i';
ver($patron1, probar($patron1, $textos));

// =============================================================================================
// PROBLEMA 2. Contiene caracteres no permitidos
//  Un nombre de fichero solo puede tener letras ASCII, dígitos, guion, guion bajo y punto.
//  Escribe un patrón que DETECTE si el nombre tiene algún carácter NO permitido (clase negada).
//  Debe dar 1 en "mi fichero.txt", "foto(1).jpg" y "año.pdf"; y 0 en "foto_1.jpg" y "informe-final.pdf".
// =============================================================================================
titulo('Problema 2');
$textos = ["mi fichero.txt", "foto(1).jpg", "año.pdf", "foto_1.jpg", "informe-final.pdf"];
// completa aquí tu solución: $patron2 = '...';
// ---- SOLUCIÓN ----
$patron2 = '/[^a-zA-Z0-9_.-]/';
ver($patron2, probar($patron2, $textos));

// =============================================================================================
// PROBLEMA 3. Precio en euros
//  Valida un precio con el formato exacto: uno a tres dígitos, una coma, dos dígitos, un espacio y
//  el símbolo €. Ejemplos válidos: "9,99 €", "125,00 €". No valen: "9.99 €", "1250,00 €", "9,9 €".
//  Recuerda que € ocupa varios bytes.
// =============================================================================================
titulo('Problema 3');
$textos = ["9,99 €", "125,00 €", "9.99 €", "1250,00 €", "9,9 €"];
// completa aquí tu solución: $patron3 = '...';
// ---- SOLUCIÓN ----
$patron3 = '/^\d{1,3},\d\d €$/u';
ver($patron3, probar($patron3, $textos));

// =============================================================================================
// PROBLEMA 4. Rutas de Windows
//  Comprueba si una ruta empieza por "C:\Users\". Escribe el patrón de dos formas: escapando las
//  barras invertidas y... ¿cuántas barras hacen falta en el string de PHP con comillas simples?
// =============================================================================================
titulo('Problema 4');
$textos = ['C:\Users\ana\Documentos', 'D:\Users\ana', 'C:\Windows\System32'];
// ¿cómo lo escribirías? $patron4 = '...';
// ---- SOLUCIÓN ----
// En la regex hace falta \\ para una barra literal. En PHP, con comillas simples, '\\' es UNA
// barra, así que para escribir \\ en la regex hay que poner '\\\\' (4 barras) en el string.
$patron4 = '/^C:\\\\Users\\\\/';
ver($patron4, probar($patron4, $textos));   // 1, 0, 0

// =============================================================================================
// PROBLEMA 5. Palabras de 5 letras con tilde
//  Valida que el texto sea una palabra de exactamente 5 letras minúsculas (puede tener tildes y ñ:
//  á é í ó ú ñ). Debe aceptar "árbol", "niños" y "casas", y rechazar "árboles", "Casas" y "casa1".
//  Usa una clase de caracteres con los rangos y las letras con tilde, y el modificador u.
// =============================================================================================
titulo('Problema 5');
$textos = ["árbol", "niños", "casas", "árboles", "Casas", "casa1"];
// completa aquí tu solución: $patron5 = '...';
// ---- SOLUCIÓN ----
$patron5 = '/^[a-záéíóúñ]{5}$/u';
ver($patron5, probar($patron5, $textos));

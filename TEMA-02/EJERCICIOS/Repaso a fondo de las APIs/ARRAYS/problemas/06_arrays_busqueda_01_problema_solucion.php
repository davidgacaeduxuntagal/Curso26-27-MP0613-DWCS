<?php
/*
 * 06_arrays_busqueda_01_problema_solucion.php
 *  (FICHERO CON LAS SOLUCIONES)
 *  Problemas de: in_array, array_search, array_key_exists, key_exists (e isset)
 *  (repasa los ejemplos de ../06_arrays_busqueda_01.php)
 *
 *  Resuelve cada problema donde se indica y ejecútalo en modo depuración para comprobarlo.
 */
require_once __DIR__ . '/../utilidades.php';

// =============================================================================================
// PROBLEMA 1. Validar un formulario con listas blancas
//  Comprueba que el método de pago y el país recibidos están entre los permitidos (comparación
//  estricta). Acumula los errores en un array.
// =============================================================================================
titulo('Problema 1');
$pagosPermitidos  = ["tarjeta", "bizum", "transferencia"];
$paisesPermitidos = ["ES", "PT", "FR"];
$recibido         = ['pago' => "paypal", 'pais' => "ES"];
// completa aquí tu solución
// ---- SOLUCIÓN ----
$errores = [];
if (!in_array($recibido['pago'], $pagosPermitidos, true)) {
    $errores[] = "Método de pago no permitido: {$recibido['pago']}";
}
if (!in_array($recibido['pais'], $paisesPermitidos, true)) {
    $errores[] = "País no permitido: {$recibido['pais']}";
}
ver('errores', $errores);        // ["Método de pago no permitido: paypal"]

// =============================================================================================
// PROBLEMA 2. ¿Quién tiene este teléfono?
//  La agenda es nombre => teléfono. Busca a quién pertenece cada teléfono de la lista o indica
//  "desconocido". ¡Cuidado con el primer contacto!
// =============================================================================================
titulo('Problema 2');
$agenda    = ['Ana' => "612345678", 'Brais' => "698765432", 'Carmen' => "655111222"];
$llamadas  = ["612345678", "600000000", "655111222"];
// completa aquí tu solución
// ---- SOLUCIÓN ----
$quien = [];
foreach ($llamadas as $telefono) {
    $nombre           = array_search($telefono, $agenda, true);
    $quien[$telefono] = $nombre !== false ? $nombre : "desconocido";
}
ver('llamadas', $quien);

// =============================================================================================
// PROBLEMA 3. Añadir al carrito
//  El carrito es código => cantidad. Añade los productos de la lista: si ya está en el carrito,
//  suma 1 a su cantidad; si no, añádelo con cantidad 1. Usa array_key_exists.
// =============================================================================================
titulo('Problema 3');
$carrito = ['P01' => 1];
$anadir  = ['P02', 'P01', 'P03', 'P02', 'P01'];
// completa aquí tu solución
// ---- SOLUCIÓN ----
foreach ($anadir as $codigo) {
    if (array_key_exists($codigo, $carrito)) {
        $carrito[$codigo]++;
    } else {
        $carrito[$codigo] = 1;
    }
}
ver('carrito', $carrito);        // ['P01' => 3, 'P02' => 2, 'P03' => 1]

// =============================================================================================
// PROBLEMA 4. isset o array_key_exists
//  El perfil tiene la clave "segundo_apellido" con valor null. Escribe qué devuelve cada
//  comprobación y una línea que muestre el segundo apellido o "(sin segundo apellido)" usando ??.
// =============================================================================================
titulo('Problema 4');
$perfil = ['nombre' => "Ana", 'apellido' => "Pérez", 'segundo_apellido' => null];
// completa aquí tu solución
// ---- SOLUCIÓN ----
ver('isset', isset($perfil['segundo_apellido']));                         // false
ver('array_key_exists', array_key_exists('segundo_apellido', $perfil));   // true
ver('segundo apellido', $perfil['segundo_apellido'] ?? "(sin segundo apellido)");

// =============================================================================================
// PROBLEMA 5. Palabras prohibidas
//  Indica qué palabras del mensaje (en minúsculas y sin signos de puntuación) están en la lista
//  de prohibidas y en qué posición de la lista están.
// =============================================================================================
titulo('Problema 5');
$prohibidas = ["spam", "gratis", "oferta"];
$mensaje    = "¡OFERTA! Entra ya, es GRATIS.";
// completa aquí tu solución
// ---- SOLUCIÓN ----
$limpio      = strtolower(str_replace(["¡", "!", ",", "."], "", $mensaje));
$encontradas = [];
foreach (explode(" ", $limpio) as $palabra) {
    if (in_array($palabra, $prohibidas, true)) {
        $encontradas[$palabra] = array_search($palabra, $prohibidas, true);
    }
}
ver('prohibidas encontradas', $encontradas);   // ['oferta' => 2, 'gratis' => 1]

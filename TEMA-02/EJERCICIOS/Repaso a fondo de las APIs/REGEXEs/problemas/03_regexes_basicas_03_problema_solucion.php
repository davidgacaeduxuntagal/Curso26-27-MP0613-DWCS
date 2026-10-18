<?php
/*
 * 03_regexes_basicas_03_problema_solucion.php
 *  (FICHERO CON LAS SOLUCIONES)
 *  Problemas de: validación del formato de datos habituales con expresiones regulares
 *  (repasa los ejemplos de ../03_regexes_basicas_03.php)
 *
 *  En cada problema escribe el patrón. Los textos de prueba están pensados para que pasen los
 *  primeros y fallen los demás (lo indica el comentario).
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
// PROBLEMA 1. IBAN español
//  Un IBAN español tiene "ES", 2 dígitos de control y 20 dígitos más. Se puede escribir todo junto
//  o en bloques de 4 separados por espacios ("ES91 2100 0418 4502 0005 1332").
//  Pasan: los 2 primeros. Fallan: los 3 últimos.
// =============================================================================================
titulo('Problema 1');
$textos = ["ES9121000418450200051332", "ES91 2100 0418 4502 0005 1332", "ES912100", "FR9121000418450200051332", "ES91-2100-0418-4502-0005-1332"];
// completa aquí tu solución: $iban = '...';
// ---- SOLUCIÓN ----
$iban = '/^ES\d{2}( ?\d{4}){5}$/';
ver($iban, probar($iban, $textos));

// =============================================================================================
// PROBLEMA 2. Color HTML
//  Un color HTML es "#" seguido de 3 o 6 dígitos hexadecimales (sin distinguir mayúsculas).
//  Pasan: "#fff", "#FF8000", "#a1b2c3". Fallan: "fff", "#ffff", "#GG0000".
// =============================================================================================
titulo('Problema 2');
$textos = ["#fff", "#FF8000", "#a1b2c3", "fff", "#ffff", "#GG0000"];
// completa aquí tu solución: $color = '...';
// ---- SOLUCIÓN ----
$color = '/^#([0-9a-f]{3}|[0-9a-f]{6})$/i';
ver($color, probar($color, $textos));

// =============================================================================================
// PROBLEMA 3. Dirección IP (formato)
//  Valida el formato de una IPv4: 4 números de 1 a 3 dígitos separados por puntos. ¿Detecta tu
//  patrón que "300.1.1.1" no es una IP válida? Comprueba además que cada número es <= 255 usando
//  explode, o compara con filter_var($ip, FILTER_VALIDATE_IP).
// =============================================================================================
titulo('Problema 3');
$textos = ["192.168.1.20", "8.8.8.8", "300.1.1.1", "192.168.1", "192.168.1.1.5"];
// completa aquí tu solución
// ---- SOLUCIÓN ----
$ip = '/^\d{1,3}(\.\d{1,3}){3}$/';
ver('solo formato', probar($ip, $textos));      // "300.1.1.1" pasa el formato
$validas = [];
foreach ($textos as $t) {
    $validas[$t] = preg_match($ip, $t) && max(explode(".", $t)) <= 255 ? 1 : 0;
}
ver('formato y rango', $validas);
$conFilter = [];
foreach ($textos as $t) {
    $conFilter[$t] = filter_var($t, FILTER_VALIDATE_IP) !== false ? 1 : 0;
}
ver('filter_var', $conFilter);

// =============================================================================================
// PROBLEMA 4. Fecha y hora completa
//  Valida el formato "dd/mm/aaaa hh:mm" (día 01-31, mes 01-12, hora 00-23, minutos 00-59).
//  Reutiliza los patrones de fecha y hora del fichero de ejemplos.
// =============================================================================================
titulo('Problema 4');
$textos = ["16/10/2026 09:30", "01/01/2027 23:59", "16/10/2026 24:00", "16/10/2026", "16/10/2026  09:30"];
// completa aquí tu solución: $fechaHora = '...';
// ---- SOLUCIÓN ----
$fechaHora = '/^(0[1-9]|[12]\d|3[01])\/(0[1-9]|1[0-2])\/\d{4} ([01]\d|2[0-3]):[0-5]\d$/';
ver($fechaHora, probar($fechaHora, $textos));   // 1, 1, 0, 0, 0

// =============================================================================================
// PROBLEMA 5. Función de validación de un formulario
//  Escribe una función validar(array $datos): array que compruebe con regex el código postal, el
//  teléfono y el DNI, y devuelva un array con los nombres de los campos incorrectos.
// =============================================================================================
titulo('Problema 5');
$formulario = ['cp' => "36380", 'telefono' => "61234567", 'dni' => "12345678Z"];
// completa aquí tu solución
// ---- SOLUCIÓN ----
function validar(array $datos): array
{
    $patrones = [
        'cp'       => '/^(0[1-9]|[1-4]\d|5[0-2])\d{3}$/',
        'telefono' => '/^(\+34\s?)?[6-9]\d{2}\s?\d{3}\s?\d{3}$/',
        'dni'      => '/^(\d{8}|[XYZ]\d{7})[A-HJ-NP-TV-Z]$/i',
    ];
    $incorrectos = [];
    foreach ($patrones as $campo => $patron) {
        if (!preg_match($patron, $datos[$campo] ?? '')) {
            $incorrectos[] = $campo;
        }
    }
    return $incorrectos;
}
ver('campos incorrectos', validar($formulario));   // ["telefono"]

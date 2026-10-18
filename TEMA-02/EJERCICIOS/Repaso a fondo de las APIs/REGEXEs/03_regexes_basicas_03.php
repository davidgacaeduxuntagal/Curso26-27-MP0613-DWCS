<?php
/*
 * 03_regexes_basicas_03.php
 *  REGEXES - BÁSICAS (3): validar el FORMATO de datos habituales en formularios
 *  Contenidos: entero, decimal, código postal, teléfono, DNI / NIE, email, fecha, hora, matrícula, URL
 *
 *  Reglas para validar con expresiones regulares:
 *    1. Anclar SIEMPRE el patrón con ^ y $ (o \z): el dato COMPLETO debe tener el formato.
 *    2. Una regex comprueba el FORMATO, no si el dato es correcto: "31/02/2026" tiene formato de
 *       fecha pero no existe (para eso: checkdate). "12345678A" tiene formato de DNI pero la letra
 *       puede ser incorrecta (hay que calcularla).
 *    3. Para algunos tipos PHP ya trae validación: filter_var($x, FILTER_VALIDATE_EMAIL / _INT /
 *       _FLOAT / _URL). Úsala cuando exista; la regex, para formatos propios.
 *
 *  Cada ejemplo prueba el patrón con una lista de casos válidos e inválidos. Ejecuta en modo
 *  depuración (F10) y, antes de mirar el resultado, intenta adivinar cuáles pasan.
 */
require_once __DIR__ . '/utilidades.php';

/**
 * Prueba un patrón con varios textos y devuelve [texto => 1 / 0].
 */
function probar(string $patron, array $textos): array
{
    $resultado = [];
    foreach ($textos as $texto) {
        $resultado[$texto] = preg_match($patron, $texto);
    }
    return $resultado;
}

// ---------------------------------------------------------------------------------------------
// NÚMERO ENTERO (con signo opcional)
// ENLACE: https://www.php.net/manual/es/regexp.reference.repetition.php
// ENUNCIADO EJEMPLO: validar la cantidad de un producto o una temperatura (puede ser negativa).
//   Patrón: signo opcional [+-]? y uno o más dígitos \d+
// ---------------------------------------------------------------------------------------------
titulo('entero');
$entero        = '/^[+-]?\d+$/';
ver($entero, probar($entero, ["42", "-7", "+15", "3.5", "12a", ""]));   // 1, 1, 1, 0, 0, 0

// ---------------------------------------------------------------------------------------------
// NÚMERO DECIMAL (con coma o punto, decimales opcionales)
// ENUNCIADO EJEMPLO: validar un precio escrito por un usuario español ("12,50") o inglés ("12.50").
//   Patrón: \d+ parte entera, ([.,]\d+)? parte decimal opcional
// ---------------------------------------------------------------------------------------------
titulo('decimal');
$decimal       = '/^\d+([.,]\d+)?$/';
ver($decimal, probar($decimal, ["12", "12,50", "12.5", "12,", ",5", "1.234,50"]));   // 1, 1, 1, 0, 0, 0

// ---------------------------------------------------------------------------------------------
// CÓDIGO POSTAL ESPAÑOL — 5 dígitos; las dos primeras cifras (provincia) van de 01 a 52
// ENUNCIADO EJEMPLO: rechazar "00000" o "99999", que tienen 5 dígitos pero no son códigos válidos.
//   Patrón: (0[1-9]|[1-4]\d|5[0-2]) para la provincia y \d{3}
// ---------------------------------------------------------------------------------------------
titulo('código postal');
$cp            = '/^(0[1-9]|[1-4]\d|5[0-2])\d{3}$/';
ver($cp, probar($cp, ["36380", "08001", "52005", "00000", "99999", "3638"]));   // 1, 1, 1, 0, 0, 0

// ---------------------------------------------------------------------------------------------
// TELÉFONO ESPAÑOL — 9 dígitos que empiezan por 6, 7, 8 o 9; prefijo +34 opcional; se permiten
//   espacios entre grupos
// ENUNCIADO EJEMPLO: validar el teléfono de contacto de un formulario de reserva.
// ---------------------------------------------------------------------------------------------
titulo('teléfono');
$telefono      = '/^(\+34\s?)?[6-9]\d{2}\s?\d{3}\s?\d{3}$/';
ver($telefono, probar($telefono, ["612345678", "+34 612 345 678", "986123456", "512345678", "61234567"]));   // 1, 1, 1, 0, 0

// ---------------------------------------------------------------------------------------------
// DNI y NIE — 8 dígitos + letra (DNI), o X/Y/Z + 7 dígitos + letra (NIE)
// ENUNCIADO EJEMPLO: comprobar el formato del documento de identidad de un alumno. La letra
//   nunca es I, Ñ, O ni U.
// NOTA: el formato correcto NO garantiza que la letra sea la que corresponde (habría que
//   calcularla: número % 23).
// ---------------------------------------------------------------------------------------------
titulo('DNI / NIE');
$dni           = '/^[XYZ]?\d{7,8}[A-HJ-NP-TV-Z]$/i';
ver($dni, probar($dni, ["12345678Z", "12345678z", "X1234567L", "1234567Z", "12345678U"]));   // 1, 1, 1, 1 (¡!), 0
// ¡Error de diseño! "1234567Z" (7 dígitos SIN letra inicial) no es ni DNI ni NIE, pero encaja:
// [XYZ]? es opcional y \d{7,8} acepta 7 dígitos. Patrón correcto: o 8 dígitos, o X/Y/Z + 7 dígitos.
$dniPreciso    = '/^(\d{8}|[XYZ]\d{7})[A-HJ-NP-TV-Z]$/i';
ver($dniPreciso, probar($dniPreciso, ["12345678Z", "X1234567L", "1234567Z"]));               // 1, 1, 0

// ---------------------------------------------------------------------------------------------
// EMAIL (simplificado) — algo@algo.dominio
// ENUNCIADO EJEMPLO: comprobar a grandes rasgos el email de registro y compararlo con
//   filter_var(FILTER_VALIDATE_EMAIL), que es más completo y es lo recomendado.
// NOTA: un patrón 100 % correcto para emails es larguísimo. Este es suficiente para ejercicios.
// ---------------------------------------------------------------------------------------------
titulo('email');
$email         = '/^[\w.+-]+@[\w-]+(\.[\w-]+)*\.[a-z]{2,}$/i';
$emails        = ["ana@iesrodeira.gal", "ana.perez+dwcs@x.es", "ana@", "@x.es", "ana@x", "ana pérez@x.es"];
ver($email, probar($email, $emails));                  // 1, 1, 0, 0, 0, 0
$conFilter     = [];
foreach ($emails as $e) {
    $conFilter[$e] = filter_var($e, FILTER_VALIDATE_EMAIL) !== false ? 1 : 0;
}
ver('filter_var', $conFilter);

// ---------------------------------------------------------------------------------------------
// FECHA dd/mm/aaaa — día 01-31, mes 01-12
// ENUNCIADO EJEMPLO: validar la fecha de nacimiento escrita en formato español. La regex
//   comprueba el formato y los rangos básicos; checkdate comprueba que la fecha existe.
// ---------------------------------------------------------------------------------------------
titulo('fecha');
$fecha         = '/^(0[1-9]|[12]\d|3[01])\/(0[1-9]|1[0-2])\/\d{4}$/';
ver($fecha, probar($fecha, ["16/10/2026", "01/01/2000", "32/01/2026", "16/13/2026", "1/1/2026", "31/02/2026"]));
// 1, 1, 0, 0, 0, 1  <- "31/02/2026" tiene buen formato pero no existe:
ver('checkdate(2, 31, 2026)', checkdate(2, 31, 2026));    // false

// ---------------------------------------------------------------------------------------------
// HORA hh:mm (formato 24 horas)
// ENUNCIADO EJEMPLO: validar la hora de una cita: 00:00 a 23:59.
// ---------------------------------------------------------------------------------------------
titulo('hora');
$hora          = '/^([01]\d|2[0-3]):[0-5]\d$/';
ver($hora, probar($hora, ["09:30", "23:59", "00:00", "24:00", "9:30", "12:60"]));   // 1, 1, 1, 0, 0, 0

// ---------------------------------------------------------------------------------------------
// MATRÍCULA de coche española (actual): 4 dígitos + 3 consonantes (sin vocales, ni Ñ ni Q)
// ENUNCIADO EJEMPLO: validar la matrícula en el formulario de un aparcamiento, con o sin guion
//   o espacio en medio.
// ---------------------------------------------------------------------------------------------
titulo('matrícula');
$matricula     = '/^\d{4}[\s-]?[B-DF-HJ-NP-TV-Z]{3}$/i';
ver($matricula, probar($matricula, ["1234BCD", "1234-bcd", "1234 BCD", "1234ABC", "123BCD"]));   // 1, 1, 1, 0, 0

// ---------------------------------------------------------------------------------------------
// URL sencilla (http o https)
// ENUNCIADO EJEMPLO: comprobar el enlace a la web personal de un usuario, y compararlo con
//   filter_var(FILTER_VALIDATE_URL).
// ---------------------------------------------------------------------------------------------
titulo('URL');
$url           = '/^https?:\/\/[\w-]+(\.[\w-]+)+(\/\S*)?$/i';
ver($url, probar($url, ["https://www.iesrodeira.gal", "http://x.es/ruta?a=1", "ftp://x.es", "https://localhost", "www.x.es"]));
// 1, 1, 0, 0, 0

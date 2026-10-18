<?php
/*
 * e01-DATETIME_problema_solucion.php
 *  (FICHERO CON LAS SOLUCIONES)
 *  Problemas de: time, date_default_timezone_set, date, mktime, checkdate, strtotime, hrtime
 *  (repasa los ejemplos de ../e01-DATETIME.php)
 *
 *  Resuelve cada problema donde se indica y ejecútalo en modo depuración para comprobarlo.
 */
require_once __DIR__ . '/../utilidades.php';

// =============================================================================================
// PROBLEMA 1. Formatos de fecha
//  Con la marca de tiempo $ts, obtén con date() estos textos exactos:
//   a) "24/12/2026"   b) "24-12-26 21:00"   c) "Thursday, December 24"   d) "Día 358 del año"
//   e) "Semana 52"    f) "2026-12-24T21:00:00+01:00"
// =============================================================================================
titulo('Problema 1');
$ts = mktime(21, 0, 0, 12, 24, 2026);
// completa aquí tu solución
// ---- SOLUCIÓN ----
ver('a', date('d/m/Y', $ts));                     // "24/12/2026"
ver('b', date('d-m-y H:i', $ts));                 // "24-12-26 21:00"
ver('c', date('l, F j', $ts));                    // "Thursday, December 24"
ver('d', 'Día ' . (date('z', $ts) + 1) . ' del año');   // "Día 358 del año" (z empieza en 0)
ver('e', 'Semana ' . date('W', $ts));             // "Semana 52"
ver('f', date('c', $ts));                         // "2026-12-24T21:00:00+01:00"

// =============================================================================================
// PROBLEMA 2. Días de cada mes
//  Sin escribir a mano cuántos días tiene cada mes, muestra para 2026 y 2028 el número de días
//  de febrero, y para 2026 un array mes => días (1 => 31, 2 => 28, ...). Indica también si cada
//  año es bisiesto.
// =============================================================================================
titulo('Problema 2');
// completa aquí tu solución
// ---- SOLUCIÓN ----
foreach ([2026, 2028] as $anio) {
    $febrero = mktime(0, 0, 0, 2, 1, $anio);
    ver("febrero de $anio", date('t', $febrero) . ' días, bisiesto: ' . date('L', $febrero));
}
// febrero de 2026: 28 días, bisiesto: 0;  febrero de 2028: 29 días, bisiesto: 1
$dias = [];
for ($mes = 1; $mes <= 12; $mes++) {
    $dias[$mes] = (int) date('t', mktime(0, 0, 0, $mes, 1, 2026));
}
ver('días de cada mes de 2026', $dias);

// =============================================================================================
// PROBLEMA 3. Validar la fecha de nacimiento del formulario
//  El formulario de alta envía el día, el mes y el año en tres campos de texto. Escribe la
//  función validarNacimiento(string $dia, string $mes, string $anio): bool que compruebe que
//  son números, que la fecha existe y que el año está entre 1900 y 2026.
// =============================================================================================
titulo('Problema 3');
$pruebas = [['5', '4', '1985'], ['29', '2', '2026'], ['31', '12', '1899'], ['3', 'abril', '1990'], ['', '1', '2000'], ['29', '02', '2024']];
// completa aquí tu solución
// ---- SOLUCIÓN ----
function validarNacimiento(string $dia, string $mes, string $anio): bool
{
    if (!ctype_digit($dia) || !ctype_digit($mes) || !ctype_digit($anio)) {
        return false;                                  // ctype_digit('') es false
    }
    return checkdate((int) $mes, (int) $dia, (int) $anio) && (int) $anio >= 1900 && (int) $anio <= 2026;
}
foreach ($pruebas as [$d, $m, $a]) {
    ver("$d/$m/$a", validarNacimiento($d, $m, $a));   // true, false, false, false, false, true
}

// =============================================================================================
// PROBLEMA 4. Fechas relativas con strtotime
//  A partir de la fecha de un pedido ($pedido, el jueves 15/10/2026 a las 18:00), calcula con
//  strtotime: la entrega (+3 días), el primer lunes después del pedido, el último día del mes
//  y el primer día del mes siguiente. Muéstralas con formato "D d/m/Y".
// =============================================================================================
titulo('Problema 4');
$pedido = strtotime('2026-10-15 18:00');
// completa aquí tu solución
// ---- SOLUCIÓN ----
ver('entrega', date('D d/m/Y', strtotime('+3 days', $pedido)));                   // "Sun 18/10/2026"
ver('próximo lunes', date('D d/m/Y', strtotime('next monday', $pedido)));         // "Mon 19/10/2026"
ver('último día del mes', date('D d/m/Y', strtotime('last day of this month', $pedido)));   // "Sat 31/10/2026"
ver('primero del mes siguiente', date('D d/m/Y', strtotime('first day of next month', $pedido)));   // "Sun 01/11/2026"

// =============================================================================================
// PROBLEMA 5. ¿Qué hora es en...?
//  Escribe la función horaEn(int $ts, string $zona): string que devuelva la hora "H:i" de la
//  marca de tiempo en la zona indicada usando date_default_timezone_set (y dejando después la
//  zona como estaba). Pruébala con la hora de la cena en Madrid, Canarias, Londres y Nueva York.
// =============================================================================================
titulo('Problema 5');
$cena = mktime(21, 30, 0, 10, 16, 2026);
// completa aquí tu solución
// ---- SOLUCIÓN ----
function horaEn(int $ts, string $zona): string
{
    $anterior = date_default_timezone_get();
    date_default_timezone_set($zona);
    $hora = date('H:i', $ts);
    date_default_timezone_set($anterior);              // ¡no dejes cambiada la zona del script!
    return $hora;
}
foreach (['Europe/Madrid', 'Atlantic/Canary', 'Europe/London', 'America/New_York'] as $zona) {
    ver($zona, horaEn($cena, $zona));                  // "21:30", "20:30", "20:30", "15:30"
}
ver('la zona sigue siendo', date_default_timezone_get());   // "Europe/Madrid"

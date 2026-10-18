<?php
/*
 * e01-DATETIME_problema.php
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

// =============================================================================================
// PROBLEMA 2. Días de cada mes
//  Sin escribir a mano cuántos días tiene cada mes, muestra para 2026 y 2028 el número de días
//  de febrero, y para 2026 un array mes => días (1 => 31, 2 => 28, ...). Indica también si cada
//  año es bisiesto.
// =============================================================================================
titulo('Problema 2');
// completa aquí tu solución

// =============================================================================================
// PROBLEMA 3. Validar la fecha de nacimiento del formulario
//  El formulario de alta envía el día, el mes y el año en tres campos de texto. Escribe la
//  función validarNacimiento(string $dia, string $mes, string $anio): bool que compruebe que
//  son números, que la fecha existe y que el año está entre 1900 y 2026.
// =============================================================================================
titulo('Problema 3');
$pruebas = [['5', '4', '1985'], ['29', '2', '2026'], ['31', '12', '1899'], ['3', 'abril', '1990'], ['', '1', '2000'], ['29', '02', '2024']];
// completa aquí tu solución

// =============================================================================================
// PROBLEMA 4. Fechas relativas con strtotime
//  A partir de la fecha de un pedido ($pedido, el jueves 15/10/2026 a las 18:00), calcula con
//  strtotime: la entrega (+3 días), el primer lunes después del pedido, el último día del mes
//  y el primer día del mes siguiente. Muéstralas con formato "D d/m/Y".
// =============================================================================================
titulo('Problema 4');
$pedido = strtotime('2026-10-15 18:00');
// completa aquí tu solución

// =============================================================================================
// PROBLEMA 5. ¿Qué hora es en...?
//  Escribe la función horaEn(int $ts, string $zona): string que devuelva la hora "H:i" de la
//  marca de tiempo en la zona indicada usando date_default_timezone_set (y dejando después la
//  zona como estaba). Pruébala con la hora de la cena en Madrid, Canarias, Londres y Nueva York.
// =============================================================================================
titulo('Problema 5');
$cena = mktime(21, 30, 0, 10, 16, 2026);
// completa aquí tu solución

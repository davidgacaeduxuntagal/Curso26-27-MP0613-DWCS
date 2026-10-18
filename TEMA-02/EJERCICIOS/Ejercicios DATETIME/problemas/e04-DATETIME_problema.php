<?php
/*
 * e04-DATETIME_problema.php
 *  Problemas de: diff, DateInterval (y, m, d, days, invert), DateInterval::format,
 *                new DateInterval, add, sub, fin de mes
 *  (repasa los ejemplos de ../e04-DATETIME.php)
 *
 *  Resuelve cada problema donde se indica y ejecútalo en modo depuración para comprobarlo.
 */
require_once __DIR__ . '/../utilidades.php';

$hoy = new DateTimeImmutable('2026-10-16');    // "hoy" fijo para que los resultados no cambien

// =============================================================================================
// PROBLEMA 1. Edades y cumpleaños
//  Para cada socio de $socios calcula su edad el día $hoy y cuántos días faltan para su próximo
//  cumpleaños (0 si es hoy). Pista: el próximo cumpleaños es el de este año o, si ya pasó, el
//  del año que viene.
// =============================================================================================
titulo('Problema 1');
$socios = ['Ana' => '1990-10-16', 'Brais' => '2001-12-24', 'Carmen' => '1975-01-03'];
// completa aquí tu solución

// =============================================================================================
// PROBLEMA 2. ¿Cuánto falta?
//  Escribe cuentaAtras(DateTimeImmutable $ahora, DateTimeImmutable $evento): string que
//  devuelva "Faltan X días, H horas y M minutos", o "El evento ya ha pasado" si es anterior.
//  Usa diff, invert y DateInterval::format.
// =============================================================================================
titulo('Problema 2');
$ahora = new DateTimeImmutable('2026-10-16 12:00');
// completa aquí tu solución

// =============================================================================================
// PROBLEMA 3. Duración de los turnos
//  A partir de las fichas de entrada y salida de $fichajes, calcula los minutos trabajados en
//  cada turno y el total de la semana en formato "H horas y M minutos". (Uno de los turnos
//  termina después de medianoche.)
// =============================================================================================
titulo('Problema 3');
$fichajes = [
    ['2026-10-12 19:00', '2026-10-12 23:45'],
    ['2026-10-13 12:30', '2026-10-13 16:15'],
    ['2026-10-16 20:00', '2026-10-17 01:30'],
];
// completa aquí tu solución

// =============================================================================================
// PROBLEMA 4. Fechas de una suscripción
//  Una suscripción empieza en $alta y dura un periodo ($duracion en formato ISO 8601). Calcula
//  la fecha de fin, la fecha del aviso (7 días antes del fin) y cuántos días dura en total.
//  Prueba con 'P1M', 'P3M' y 'P1Y'.
// =============================================================================================
titulo('Problema 4');
$alta = new DateTimeImmutable('2026-10-16');
foreach (['P1M', 'P3M', 'P1Y'] as $duracion) {
    // completa aquí tu solución
}

// =============================================================================================
// PROBLEMA 5. ¿Cómo harías...?
//  ¿Cómo harías para calcular el último día de cada uno de los 12 meses de 2026 sin escribir
//  ninguna fecha más que la del 31 de enero? (Pista: sumar P1M al 31 de enero NO funciona:
//  compruébalo.)
// =============================================================================================
titulo('Problema 5');
$enero = new DateTimeImmutable('2026-01-31');
ver('31/01 + P1M (no funciona)', $enero->add(new DateInterval('P1M'))->format('Y-m-d'));   // "2026-03-03"
// completa aquí tu solución

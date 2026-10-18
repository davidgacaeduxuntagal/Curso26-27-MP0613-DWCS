<?php
/*
 * e05-DATETIME_problema.php
 *  Problemas de: DatePeriod (fecha final, repeticiones, EXCLUDE_START_DATE, INCLUDE_END_DATE),
 *                iterator_count, iterator_to_array, días laborables, calendarios
 *  (repasa los ejemplos de ../e05-DATETIME.php)
 *
 *  Resuelve cada problema donde se indica y ejecútalo en modo depuración para comprobarlo.
 */
require_once __DIR__ . '/../utilidades.php';

// =============================================================================================
// PROBLEMA 1. Huecos para reservar
//  El bar admite reservas para la cena cada 30 minutos desde las 20:30 hasta las 23:00 (esta
//  incluida). Genera con DatePeriod la lista de horas "H:i" para el día $dia.
// =============================================================================================
titulo('Problema 1');
$dia = new DateTimeImmutable('2026-10-16');
// completa aquí tu solución

// =============================================================================================
// PROBLEMA 2. Viernes 13
//  ¿Qué meses de 2026 y 2027 tienen un viernes 13? Recorre con DatePeriod el día 13 de cada mes.
// =============================================================================================
titulo('Problema 2');
// completa aquí tu solución

// =============================================================================================
// PROBLEMA 3. Plan de pagos
//  Una compra de 600 € se paga en 6 cuotas mensuales a partir del 5 de noviembre de 2026.
//  Genera el plan (usa DatePeriod con número de repeticiones) como un array
//  "dd/mm/aaaa" => importe, y comprueba que la suma es 600.
// =============================================================================================
titulo('Problema 3');
$total = 600;
$cuotas = 6;
// completa aquí tu solución

// =============================================================================================
// PROBLEMA 4. Plazo de devolución
//  Un cliente tiene 14 días NATURALES para devolver un producto, pero si el último día cae en
//  sábado, domingo o festivo, el plazo se amplía hasta el siguiente día laborable. Escribe
//  finPlazo(DateTimeImmutable $compra, array $festivos): DateTimeImmutable.
// =============================================================================================
titulo('Problema 4');
$festivos = ['2026-10-12', '2026-11-01', '2026-11-02', '2026-12-08'];
// completa aquí tu solución

// =============================================================================================
// PROBLEMA 5. Calendario del mes con las reservas marcadas
//  Genera el calendario de octubre de 2026 (de lunes a domingo, como en el ejemplo) y marca
//  con un * los días que tienen alguna reserva en $reservas. Muestra el número de reservas
//  de cada semana.
// =============================================================================================
titulo('Problema 5');
$reservas = ['2026-10-02', '2026-10-03', '2026-10-03', '2026-10-16', '2026-10-30'];
// completa aquí tu solución

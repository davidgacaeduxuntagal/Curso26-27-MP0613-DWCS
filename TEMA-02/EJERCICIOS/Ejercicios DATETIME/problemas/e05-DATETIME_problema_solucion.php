<?php
/*
 * e05-DATETIME_problema_solucion.php
 *  (FICHERO CON LAS SOLUCIONES)
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
// ---- SOLUCIÓN ----
$huecos = new DatePeriod($dia->setTime(20, 30), new DateInterval('PT30M'), $dia->setTime(23, 0), DatePeriod::INCLUDE_END_DATE);
$horas = array_map(fn($h) => $h->format('H:i'), iterator_to_array($huecos));
ver('horas', $horas);           // ['20:30', '21:00', '21:30', '22:00', '22:30', '23:00']

// =============================================================================================
// PROBLEMA 2. Viernes 13
//  ¿Qué meses de 2026 y 2027 tienen un viernes 13? Recorre con DatePeriod el día 13 de cada mes.
// =============================================================================================
titulo('Problema 2');
// completa aquí tu solución
// ---- SOLUCIÓN ----
$trece = new DatePeriod(new DateTimeImmutable('2026-01-13'), new DateInterval('P1M'), new DateTimeImmutable('2028-01-01'));
$viernes13 = [];
foreach ($trece as $fecha) {
    if ($fecha->format('N') === '5') {
        $viernes13[] = $fecha->format('m/Y');
    }
}
ver('viernes 13', $viernes13);   // ['02/2026', '03/2026', '11/2026', '08/2027']

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
// ---- SOLUCIÓN ----
$plan = [];
foreach (new DatePeriod(new DateTimeImmutable('2026-11-05'), new DateInterval('P1M'), $cuotas - 1) as $fecha) {
    $plan[$fecha->format('d/m/Y')] = $total / $cuotas;
}
ver('plan', $plan);              // 05/11/2026 ... 05/04/2027, 100 cada una
ver('suma', array_sum($plan));   // 600

// =============================================================================================
// PROBLEMA 4. Plazo de devolución
//  Un cliente tiene 14 días NATURALES para devolver un producto, pero si el último día cae en
//  sábado, domingo o festivo, el plazo se amplía hasta el siguiente día laborable. Escribe
//  finPlazo(DateTimeImmutable $compra, array $festivos): DateTimeImmutable.
// =============================================================================================
titulo('Problema 4');
$festivos = ['2026-10-12', '2026-11-01', '2026-11-02', '2026-12-08'];
// completa aquí tu solución
// ---- SOLUCIÓN ----
function finPlazo(DateTimeImmutable $compra, array $festivos): DateTimeImmutable
{
    $fin = $compra->modify('+14 days');
    while ($fin->format('N') >= 6 || in_array($fin->format('Y-m-d'), $festivos, true)) {
        $fin = $fin->modify('+1 day');
    }
    return $fin;
}
ver('compra 05/10', finPlazo(new DateTimeImmutable('2026-10-05'), $festivos)->format('D d/m'));   // "Mon 19/10"
ver('compra 17/10', finPlazo(new DateTimeImmutable('2026-10-17'), $festivos)->format('D d/m'));   // "Tue 03/11" (el 31/10 es sábado; el 1 y el 2/11, festivos)
ver('compra 18/09', finPlazo(new DateTimeImmutable('2026-09-18'), $festivos)->format('D d/m'));   // "Fri 02/10"

// =============================================================================================
// PROBLEMA 5. Calendario del mes con las reservas marcadas
//  Genera el calendario de octubre de 2026 (de lunes a domingo, como en el ejemplo) y marca
//  con un * los días que tienen alguna reserva en $reservas. Muestra el número de reservas
//  de cada semana.
// =============================================================================================
titulo('Problema 5');
$reservas = ['2026-10-02', '2026-10-03', '2026-10-03', '2026-10-16', '2026-10-30'];
// completa aquí tu solución
// ---- SOLUCIÓN ----
$porDia = array_count_values($reservas);
$primero = new DateTimeImmutable('2026-10-01');
$desde = $primero->modify('monday this week');
$hasta = $primero->modify('last day of this month')->modify('sunday this week');
$semanas = [];
$reservasSemana = [];
foreach (new DatePeriod($desde, new DateInterval('P1D'), $hasta, DatePeriod::INCLUDE_END_DATE) as $d) {
    $w = $d->format('W');
    $clave = $d->format('Y-m-d');
    $texto = $d->format('m') === '10' ? $d->format('j') . (isset($porDia[$clave]) ? '*' : ' ') : '  ';
    $semanas[$w][] = str_pad($texto, 3, ' ', STR_PAD_LEFT);
    $reservasSemana[$w] = ($reservasSemana[$w] ?? 0) + ($porDia[$clave] ?? 0);
}
echo '  L   M   X   J   V   S   D', nl();
foreach ($semanas as $w => $dias) {
    echo implode(' ', $dias), "   (semana $w: {$reservasSemana[$w]} reservas)", nl();
}
// semana 40: 3 reservas (días 2 y 3), 41: 0, 42: 1, 43: 0, 44: 1

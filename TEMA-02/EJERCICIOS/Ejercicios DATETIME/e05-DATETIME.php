<?php
/*
 * e05-DATETIME.php
 *  FECHA Y HORA (5): SERIES DE FECHAS CON DatePeriod
 *  Clases y funciones: new DatePeriod (con fecha final o con repeticiones),
 *           DatePeriod::EXCLUDE_START_DATE, DatePeriod::INCLUDE_END_DATE (PHP 8.2),
 *           iterator_count, iterator_to_array, días laborables, calendario de un mes
 *  (apartado 6 de los apuntes)
 *
 *  Cómo trabajar este fichero:
 *   1. Pon un punto de ruptura DENTRO de los foreach y ejecuta en modo depuración.
 *   2. En cada vuelta, mira en el panel VARIABLES la fecha que genera el periodo.
 *   3. Los comentarios a la derecha indican el resultado esperado.
 */
require_once __DIR__ . '/utilidades.php';

// ---------------------------------------------------------------------------------------------
// new DatePeriod($inicio, $intervalo, $fin) — Recorrer fechas con un intervalo fijo
// ENLACE: https://www.php.net/manual/es/class.dateperiod.php
// ENLACE: https://www.php.net/manual/es/dateperiod.construct.php
// ENUNCIADO EJEMPLO: el curso de cocina es todos los martes de noviembre: mostrar las fechas.
// NOTA: genera $inicio, $inicio + intervalo, ... mientras la fecha sea ANTERIOR a $fin (la fecha
//       final NO se incluye). Cada elemento es del mismo tipo que $inicio.
// ---------------------------------------------------------------------------------------------
titulo('Martes de noviembre');
$inicio = new DateTimeImmutable('first tuesday of november 2026');
$fin    = new DateTimeImmutable('2026-12-01');
$martes = new DatePeriod($inicio, new DateInterval('P1W'), $fin);
foreach ($martes as $dia) {
    ver('sesión', $dia->format('D d/m/Y'));        // Tue 03/11, 10/11, 17/11, 24/11
}
ver('número de sesiones', iterator_count($martes));   // 4

// ---------------------------------------------------------------------------------------------
// DatePeriod con número de repeticiones
// ENLACE: https://www.php.net/manual/es/dateperiod.construct.php
// ENUNCIADO EJEMPLO: un tratamiento de 4 citas cada 15 días a partir del 1 de octubre.
// NOTA: el tercer parámetro puede ser un entero: el número de REPETICIONES (además de la fecha de
//       inicio). Con 3 se obtienen 4 fechas.
// ---------------------------------------------------------------------------------------------
titulo('Repeticiones');
$citas = new DatePeriod(new DateTimeImmutable('2026-10-01 10:00'), new DateInterval('P15D'), 3);
foreach ($citas as $i => $cita) {
    ver("cita $i", $cita->format('d/m H:i'));      // 01/10, 16/10, 31/10, 15/11 a las 10:00
}

// ---------------------------------------------------------------------------------------------
// DatePeriod::EXCLUDE_START_DATE y DatePeriod::INCLUDE_END_DATE
// ENLACE: https://www.php.net/manual/es/class.dateperiod.php  (constantes)
// ENUNCIADO EJEMPLO: contar los cumpleaños que ha celebrado Carlos (material de referencia) y
//                   listar los días de unas vacaciones incluyendo el último.
// NOTA: EXCLUDE_START_DATE quita la fecha de inicio (el día que nació no es un cumpleaños).
//       INCLUDE_END_DATE (PHP 8.2) incluye la fecha final. Se pueden combinar con |.
// ---------------------------------------------------------------------------------------------
titulo('EXCLUDE_START_DATE e INCLUDE_END_DATE');
$hoy = new DateTimeImmutable('2026-10-16');        // fijamos "hoy"
$carlos = new DateTimeImmutable('1982-08-15');
$cumples = new DatePeriod($carlos, new DateInterval('P1Y'), $hoy, DatePeriod::EXCLUDE_START_DATE);
ver('cumpleaños celebrados', iterator_count($cumples));    // 44 (igual que $carlos->diff($hoy)->y)
ver('edad con diff', $carlos->diff($hoy)->y);              // 44

$vacaciones = new DatePeriod(new DateTimeImmutable('2026-08-01'), new DateInterval('P1D'), new DateTimeImmutable('2026-08-05'));
ver('sin INCLUDE_END_DATE', iterator_count($vacaciones));  // 4 (del 1 al 4)
$vacaciones = new DatePeriod(new DateTimeImmutable('2026-08-01'), new DateInterval('P1D'), new DateTimeImmutable('2026-08-05'), DatePeriod::INCLUDE_END_DATE);
ver('con INCLUDE_END_DATE', iterator_count($vacaciones));  // 5 (del 1 al 5)

// ---------------------------------------------------------------------------------------------
// iterator_to_array — Convertir el periodo en un array
// ENLACE: https://www.php.net/manual/es/function.iterator-to-array.php
// ENUNCIADO EJEMPLO: día de la semana en que cayó el cumpleaños de María los primeros años
//                   (material de referencia).
// NOTA: útil para usar después funciones de arrays (array_map, array_filter, count...).
// ---------------------------------------------------------------------------------------------
titulo('iterator_to_array');
$maria = new DateTimeImmutable('1985-04-05');
$periodo = new DatePeriod($maria, new DateInterval('P1Y'), 4);
$dias = array_map(fn($f) => $f->format('Y-m-d D'), iterator_to_array($periodo));
ver('cumpleaños de María', $dias);    // 1985-04-05 Fri, 1986 Sat, 1987 Sun, 1988 Tue (bisiesto: salta el lunes), 1989 Wed

// ---------------------------------------------------------------------------------------------
// Días laborables entre dos fechas
// ENLACE: https://www.php.net/manual/es/class.dateperiod.php
// ENUNCIADO EJEMPLO: un pedido al proveedor tarda 5 días LABORABLES: ¿cuándo llega? ¿y cuántos
//                   días laborables hay en octubre (sin el festivo del 12)?
// NOTA: se recorren los días uno a uno y se descartan sábados, domingos (format('N') >= 6) y
//       festivos (un array de fechas 'Y-m-d').
// ---------------------------------------------------------------------------------------------
titulo('Días laborables');
$festivos = ['2026-10-12', '2026-11-01', '2026-12-08', '2026-12-25'];
function esLaborable(DateTimeInterface $dia, array $festivos): bool
{
    return $dia->format('N') < 6 && !in_array($dia->format('Y-m-d'), $festivos, true);
}

$octubre = new DatePeriod(new DateTimeImmutable('2026-10-01'), new DateInterval('P1D'), new DateTimeImmutable('2026-11-01'));
$laborables = 0;
foreach ($octubre as $dia) {
    if (esLaborable($dia, $festivos)) {
        $laborables++;
    }
}
ver('laborables en octubre', $laborables);         // 21 (22 de lunes a viernes, menos el día 12)

function sumarDiasLaborables(DateTimeImmutable $desde, int $dias, array $festivos): DateTimeImmutable
{
    $fecha = $desde;
    while ($dias > 0) {
        $fecha = $fecha->modify('+1 day');
        if (esLaborable($fecha, $festivos)) {
            $dias--;
        }
    }
    return $fecha;
}
ver('pedido del viernes 9/10 + 5 laborables', sumarDiasLaborables(new DateTimeImmutable('2026-10-09'), 5, $festivos)->format('D d/m'));
// "Mon 19/10": 13, 14, 15, 16 y 19 (el 12 es festivo y el fin de semana no cuenta)

// ---------------------------------------------------------------------------------------------
// Calendario de un mes
// ENLACE: https://www.php.net/manual/es/class.dateperiod.php
// ENUNCIADO EJEMPLO: generar las semanas de noviembre de 2026 para que el cliente elija el día
//                   de la reserva (de lunes a domingo, con huecos al principio y al final).
// NOTA: el calendario empieza el lunes de la semana del día 1 ('monday this week') y termina el
//       domingo de la semana del último día.
// ---------------------------------------------------------------------------------------------
titulo('Calendario de noviembre de 2026');
$primero = new DateTimeImmutable('2026-11-01');
$desde = $primero->modify('monday this week');                         // lunes 26/10
$hasta = $primero->modify('last day of this month')->modify('sunday this week');   // domingo 06/12
$semanas = [];
foreach (new DatePeriod($desde, new DateInterval('P1D'), $hasta, DatePeriod::INCLUDE_END_DATE) as $dia) {
    $semana = $dia->format('W');                                       // número de semana ISO
    $semanas[$semana][] = $dia->format('m') === '11' ? $dia->format('j') : '';   // '' = otro mes
}
echo ' L  M  X  J  V  S  D', nl();
foreach ($semanas as $dias) {
    echo implode(' ', array_map(fn($d) => str_pad($d, 2, ' ', STR_PAD_LEFT), $dias)), nl();
}
ver('semanas', count($semanas));                   // 6 (el día 1 es domingo y el 30, lunes)

<?php
/*
 * e04-DATETIME_problema_solucion.php
 *  (FICHERO CON LAS SOLUCIONES)
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
// ---- SOLUCIÓN ----
foreach ($socios as $nombre => $texto) {
    $nacimiento = new DateTimeImmutable($texto);
    $edad = $nacimiento->diff($hoy)->y;
    $cumple = $nacimiento->setDate((int) $hoy->format('Y'), (int) $nacimiento->format('m'), (int) $nacimiento->format('d'));
    if ($cumple < $hoy) {
        $cumple = $cumple->modify('+1 year');
    }
    ver($nombre, "$edad años, faltan " . $hoy->diff($cumple)->days . ' días');
}
// Ana: 36 años, faltan 0 días; Brais: 24 años, faltan 69 días; Carmen: 51 años, faltan 79 días

// =============================================================================================
// PROBLEMA 2. ¿Cuánto falta?
//  Escribe cuentaAtras(DateTimeImmutable $ahora, DateTimeImmutable $evento): string que
//  devuelva "Faltan X días, H horas y M minutos", o "El evento ya ha pasado" si es anterior.
//  Usa diff, invert y DateInterval::format.
// =============================================================================================
titulo('Problema 2');
$ahora = new DateTimeImmutable('2026-10-16 12:00');
// completa aquí tu solución
// ---- SOLUCIÓN ----
function cuentaAtras(DateTimeImmutable $ahora, DateTimeImmutable $evento): string
{
    $falta = $ahora->diff($evento);
    if ($falta->invert === 1) {
        return 'El evento ya ha pasado';
    }
    return $falta->format('Faltan %a días, %h horas y %i minutos');
}
ver('Nochevieja', cuentaAtras($ahora, new DateTimeImmutable('2026-12-31 23:59')));   // "Faltan 76 días, 11 horas y 59 minutos"
ver('esta noche', cuentaAtras($ahora, new DateTimeImmutable('2026-10-16 21:30')));   // "Faltan 0 días, 9 horas y 30 minutos"
ver('ayer', cuentaAtras($ahora, new DateTimeImmutable('2026-10-15 21:30')));         // "El evento ya ha pasado"

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
// ---- SOLUCIÓN ----
$total = 0;
foreach ($fichajes as [$entrada, $salida]) {
    $turno = (new DateTimeImmutable($entrada))->diff(new DateTimeImmutable($salida));
    $minutos = $turno->days * 24 * 60 + $turno->h * 60 + $turno->i;
    ver($entrada, "$minutos minutos");                   // 285, 225, 330
    $total += $minutos;
}
ver('total', intdiv($total, 60) . ' horas y ' . ($total % 60) . ' minutos');   // "14 horas y 0 minutos"

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
    // ---- SOLUCIÓN ----
    $fin = $alta->add(new DateInterval($duracion));
    $aviso = $fin->sub(new DateInterval('P7D'));
    ver($duracion, 'fin ' . $fin->format('d/m/Y') . ', aviso ' . $aviso->format('d/m/Y') . ', ' . $alta->diff($fin)->days . ' días');
    // P1M: fin 16/11/2026, aviso 09/11/2026, 31 días; P3M: fin 16/01/2027, aviso 09/01/2027, 92 días;
    // P1Y: fin 16/10/2027, aviso 09/10/2027, 365 días
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
// ---- SOLUCIÓN ----
$ultimos = [];
for ($i = 0; $i < 12; $i++) {
    $ultimos[] = $enero->modify("first day of +$i month")->modify('last day of this month')->format('d/m');
}
ver('últimos días', $ultimos);       // 31/01, 28/02, 31/03, 30/04, 31/05, 30/06, 31/07, 31/08, 30/09, 31/10, 30/11, 31/12

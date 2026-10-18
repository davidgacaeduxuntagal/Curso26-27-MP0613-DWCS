<?php
/*
 * e04-DATETIME.php
 *  FECHA Y HORA (4): INTERVALOS Y DIFERENCIAS
 *  Métodos: diff (y, m, d, h, i, s, days, invert), DateInterval::format, new DateInterval
 *           (formato ISO 8601 P...T...), DateInterval::createFromDateString, add, sub,
 *           el problema de fin de mes
 *  (apartado 5 de los apuntes)
 *
 *  Cómo trabajar este fichero:
 *   1. Pon un punto de ruptura en la primera línea de código y ejecuta en modo depuración.
 *   2. Avanza con F10 y despliega los objetos DateInterval en el panel VARIABLES: fíjate en
 *      la diferencia entre d (días "sueltos") y days (días totales), y en invert.
 *   3. Los comentarios a la derecha indican el resultado esperado.
 */
require_once __DIR__ . '/utilidades.php';

// ---------------------------------------------------------------------------------------------
// diff — Diferencia entre dos fechas (devuelve un DateInterval)
// ENLACE: https://www.php.net/manual/es/datetime.diff.php
// ENLACE: https://www.php.net/manual/es/class.dateinterval.php
// ENUNCIADO EJEMPLO: ¿cuánto se llevan María y Carlos? (ejemplo del material de referencia)
// NOTA: y, m, d... es la diferencia DESGLOSADA (2 años, 7 meses y 21 días); days es el total de
//       días. invert = 1 indica que la segunda fecha es ANTERIOR a la primera.
// ---------------------------------------------------------------------------------------------
titulo('diff');
$maria  = new DateTimeImmutable('1985-04-05');
$carlos = new DateTimeImmutable('1982-08-15');
$diferencia = $maria->diff($carlos);
ver('diferencia', $diferencia);                     // -2 a 7 m 21 d, days=964
ver('años', $diferencia->y);                        // 2
ver('meses', $diferencia->m);                       // 7
ver('días (sueltos)', $diferencia->d);              // 21
ver('días en total', $diferencia->days);            // 964
ver('invert', $diferencia->invert);                 // 1: Carlos nació ANTES que María
ver('al revés: invert', $carlos->diff($maria)->invert);   // 0
ver('frase', "María y Carlos se llevan {$diferencia->y} años y {$diferencia->m} meses");

// ---------------------------------------------------------------------------------------------
// diff — Calcular la edad
// ENLACE: https://www.php.net/manual/es/datetime.diff.php
// ENUNCIADO EJEMPLO: saber si un cliente es mayor de edad y si es menor de 12 (menú infantil).
// NOTA: la edad son los AÑOS CUMPLIDOS: $nacimiento->diff($hoy)->y. No dividas los días entre
//       365: falla por los años bisiestos (y los cumpleaños del 29 de febrero).
// ---------------------------------------------------------------------------------------------
titulo('Edad');
$hoy = new DateTimeImmutable('2026-10-16');          // fijamos "hoy" para que el resultado no cambie
$clientes = ['Ana' => '2008-02-29', 'Brais' => '2008-10-17', 'Carmen' => '2015-03-01', 'Diego' => '2008-10-16'];
foreach ($clientes as $nombre => $fechaNacimiento) {
    $edad = (new DateTimeImmutable($fechaNacimiento))->diff($hoy)->y;
    ver($nombre, $edad . ' años' . ($edad >= 18 ? ', mayor de edad' : '') . ($edad < 12 ? ', menú infantil' : ''));
}
// Ana 18, mayor de edad; Brais 17 (cumple mañana); Carmen 11, menú infantil; Diego 18 (cumple hoy)
$malCalculada = intdiv((new DateTimeImmutable('2008-10-17'))->diff($hoy)->days, 365);
ver('Brais dividiendo entre 365', $malCalculada);    // 18 ¡incorrecto! (aún tiene 17)

// ---------------------------------------------------------------------------------------------
// DateInterval::format — Mostrar un intervalo
// ENLACE: https://www.php.net/manual/es/dateinterval.format.php
// ENUNCIADO EJEMPLO: mostrar cuánto falta para una reserva y cuánto duró una cena.
// NOTA: usa caracteres DISTINTOS de date(), precedidos de %: %y años, %m meses, %d días,
//       %h horas, %i minutos, %s segundos, %a días totales, %R signo (+/-), %r signo solo si es
//       negativo. En mayúsculas (%Y, %M, %D, %H, %I, %S) con ceros a la izquierda.
// ---------------------------------------------------------------------------------------------
titulo('DateInterval::format');
$ahora   = new DateTimeImmutable('2026-10-16 12:00');
$reserva = new DateTimeImmutable('2026-12-24 21:30');
$falta = $ahora->diff($reserva);
ver('falta', $falta->format('%m meses, %d días, %h horas y %i minutos'));   // "2 meses, 8 días, 9 horas y 30 minutos"
ver('en días', $falta->format('%a días'));                                  // "69 días"
ver('con signo', $falta->format('%R%a días'));                              // "+69 días"
ver('ya pasada', $reserva->diff($ahora)->format('%R%a días'));              // "-69 días"
ver('%Y frente a %y', $maria->diff($carlos)->format('%Y / %y'));            // "02 / 2"
$cena = (new DateTimeImmutable('2026-10-16 21:30'))->diff(new DateTimeImmutable('2026-10-16 23:05'));
ver('duración de la cena', $cena->format('%H:%I'));                         // "01:35"
ver('en minutos', $cena->h * 60 + $cena->i);                                // 95

// ---------------------------------------------------------------------------------------------
// new DateInterval — Crear un intervalo (formato ISO 8601)
// ENLACE: https://www.php.net/manual/es/dateinterval.construct.php
// ENUNCIADO EJEMPLO: definir duraciones: una reserva de 2 horas, una oferta de 2 semanas...
// NOTA: empieza por P; Y años, M meses, W semanas, D días; después de T: H horas, M minutos,
//       S segundos. ¡La M antes de la T son meses y después son minutos! Si el formato no es
//       válido lanza una excepción. Un intervalo creado así tiene days = false (no sabe de qué
//       fechas sale).
// ---------------------------------------------------------------------------------------------
titulo('new DateInterval');
ver('P2M2D', new DateInterval('P2M2D'));               // 2 meses y 2 días (material de referencia)
ver('PT2H', new DateInterval('PT2H'));                 // 2 horas
ver('P1M (un mes)', new DateInterval('P1M'));          // m = 1
ver('PT1M (un minuto)', new DateInterval('PT1M'));     // i = 1
ver('P2W (2 semanas)', new DateInterval('P2W'));       // d = 14
ver('P1Y2M10DT2H30M', (new DateInterval('P1Y2M10DT2H30M'))->format('%y-%m-%d %h:%i'));   // "1-2-10 2:30"
try {
    new DateInterval('2 horas');
} catch (Exception $e) {
    ver('formato no válido', $e->getMessage());       // "Unknown or bad format (2 horas)"
}

// ---------------------------------------------------------------------------------------------
// DateInterval::createFromDateString — Crear un intervalo con texto (en inglés)
// ENLACE: https://www.php.net/manual/es/dateinterval.createfromdatestring.php
// ENUNCIADO EJEMPLO: leer la duración de una oferta desde un fichero de configuración.
// ---------------------------------------------------------------------------------------------
titulo('createFromDateString');
$duracion = DateInterval::createFromDateString('3 days 2 hours');
ver('3 days 2 hours', $duracion);                      // d = 3, h = 2
ver('1 week', DateInterval::createFromDateString('1 week'));      // d = 7

// ---------------------------------------------------------------------------------------------
// add / sub — Sumar y restar intervalos
// ENLACE: https://www.php.net/manual/es/datetimeimmutable.add.php
// ENLACE: https://www.php.net/manual/es/datetimeimmutable.sub.php
// ENUNCIADO EJEMPLO: calcular la hora de fin de una reserva, el recordatorio y el fin de una oferta.
// NOTA: con DateTimeImmutable devuelven un objeto NUEVO. Con DateTime modifican el original:
//       en el material de referencia, $maria->add($diferencia) cambia la fecha de María.
// ---------------------------------------------------------------------------------------------
titulo('add y sub');
$reserva = new DateTimeImmutable('2026-10-16 21:30');
ver('fin (2 horas)', $reserva->add(new DateInterval('PT2H')));                  // 2026-10-16 23:30
ver('recordatorio (1 día antes)', $reserva->sub(new DateInterval('P1D')));      // 2026-10-15 21:30
ver('fin de la oferta (2 semanas)', $reserva->add(new DateInterval('P2W')));    // 2026-10-30 21:30
// Sumar la diferencia entre María y Carlos (con invert = 1, "sumar" es restar):
ver('María + diferencia', $maria->add($maria->diff($carlos)));                  // 1982-08-15 (la fecha de Carlos)
ver('María no ha cambiado', $maria);                                             // 1985-04-05

// ---------------------------------------------------------------------------------------------
// El problema del fin de mes
// ENLACE: https://www.php.net/manual/es/datetime.examples-arithmetic.php
// ENUNCIADO EJEMPLO: una suscripción mensual que empieza el 31 de enero.
// NOTA: +1 mes al 31 de enero = "31 de febrero", que PHP desborda al 3 de marzo. Para "el
//       último día del mes siguiente" usa modify('last day of next month').
// ---------------------------------------------------------------------------------------------
titulo('Fin de mes');
$alta = new DateTimeImmutable('2026-01-31');
ver('31/01 + P1M', $alta->add(new DateInterval('P1M'))->format('Y-m-d'));             // "2026-03-03" ¡se salta febrero!
ver('last day of next month', $alta->modify('last day of next month')->format('Y-m-d'));   // "2026-02-28"
ver('29/02/2028 + 1 año', (new DateTimeImmutable('2028-02-29'))->add(new DateInterval('P1Y'))->format('Y-m-d'));   // "2029-03-01"
// Una forma segura de calcular los cobros mensuales: partir siempre de la fecha de alta
for ($mes = 1; $mes <= 3; $mes++) {
    $cobro = $alta->modify('first day of +' . $mes . ' month');
    $dia = min((int) $alta->format('d'), (int) $cobro->format('t'));   // el día 31, o el último del mes
    ver("cobro $mes", $cobro->setDate((int) $cobro->format('Y'), (int) $cobro->format('m'), $dia)->format('Y-m-d'));
}
// cobro 1 "2026-02-28", cobro 2 "2026-03-31", cobro 3 "2026-04-30"

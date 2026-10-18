<?php
/*
 * e06-DATETIME.php
 *  FECHA Y HORA (6): ZONAS HORARIAS
 *  Clases y métodos: new DateTimeZone, getName, crear fechas en una zona, setTimezone,
 *           getTimezone, getOffset / format('P'), DateTimeZone::listIdentifiers, guardar en UTC,
 *           el cambio de hora (P1D frente a PT24H, días de 23 y 25 horas)
 *  (apartado 7 de los apuntes)
 *
 *  Cómo trabajar este fichero:
 *   1. Pon un punto de ruptura en la primera línea de código y ejecuta en modo depuración.
 *   2. Avanza con F10. Despliega los objetos en el panel VARIABLES y fíjate en el campo
 *      "timezone": con setTimezone cambia la hora que se muestra, pero no el instante
 *      (compara las marcas de tiempo).
 *   3. Los comentarios a la derecha indican el resultado esperado.
 */
require_once __DIR__ . '/utilidades.php';

// ---------------------------------------------------------------------------------------------
// new DateTimeZone — Representar una zona horaria
// ENLACE: https://www.php.net/manual/es/class.datetimezone.php
// ENLACE: https://www.php.net/manual/es/timezones.php  (lista de zonas válidas)
// ENUNCIADO EJEMPLO: crear las zonas horarias de la península, Canarias y Nueva York.
// NOTA: si el identificador no existe, lanza una excepción.
// ---------------------------------------------------------------------------------------------
titulo('DateTimeZone');
$madrid   = new DateTimeZone('Europe/Madrid');
$canarias = new DateTimeZone('Atlantic/Canary');
$nuevaYork = new DateTimeZone('America/New_York');
$utc      = new DateTimeZone('UTC');
ver('zona', $madrid->getName());                    // "Europe/Madrid"
try {
    new DateTimeZone('Madrid');
} catch (Exception $e) {
    ver('zona no válida', $e->getMessage());        // "DateTimeZone::__construct(): Unknown or bad timezone (Madrid)"
}

// ---------------------------------------------------------------------------------------------
// Crear una fecha EN una zona horaria / setTimezone — Convertir a otra zona
// ENLACE: https://www.php.net/manual/es/datetimeimmutable.settimezone.php
// ENUNCIADO EJEMPLO: unos turistas de Nueva York reservan para cenar en Galicia el 16/10 a las
//                   21:30: ¿qué hora es en su país, en Canarias y en UTC en ese momento?
// NOTA: setTimezone NO cambia el instante (la marca de tiempo es la misma), solo la forma de
//       mostrarlo. Con DateTime modifica el objeto (material de referencia); con
//       DateTimeImmutable devuelve uno nuevo.
// ---------------------------------------------------------------------------------------------
titulo('setTimezone');
$cena = new DateTimeImmutable('2026-10-16 21:30', $madrid);
$enNuevaYork = $cena->setTimezone($nuevaYork);
ver('en Madrid', $cena->format('d/m H:i T'));                        // "16/10 21:30 CEST"
ver('en Nueva York', $enNuevaYork->format('d/m H:i T'));             // "16/10 15:30 EDT"
ver('en Canarias', $cena->setTimezone($canarias)->format('H:i T'));  // "20:30 WEST"
ver('en UTC', $cena->setTimezone($utc)->format('H:i T'));            // "19:30 UTC"
ver('en Tokio', $cena->setTimezone(new DateTimeZone('Asia/Tokyo'))->format('d/m H:i'));   // "17/10 04:30" (ya es otro día)
ver('misma marca de tiempo', $cena->getTimestamp() === $enNuevaYork->getTimestamp());     // true
ver('== compara el instante', $cena == $enNuevaYork);                                      // true
ver('getTimezone', $enNuevaYork->getTimezone()->getName());                                // "America/New_York"

// Ejemplo del material de referencia: el mismo texto en dos zonas son instantes DISTINTOS
$carlos = new DateTimeImmutable('1982-08-15 15:23:48');             // en Madrid (zona por defecto)
ver('Carlos en Bogotá', $carlos->setTimezone(new DateTimeZone('America/Bogota'))->format('H:i:s'));   // "08:23:48"
$enBogota = new DateTimeImmutable('1982-08-15 15:23:48', new DateTimeZone('America/Bogota'));
ver('creado en Bogotá, en Madrid', $enBogota->setTimezone($madrid)->format('H:i:s'));                 // "22:23:48"

// ---------------------------------------------------------------------------------------------
// getOffset / format('P') — Diferencia con UTC
// ENLACE: https://www.php.net/manual/es/datetimezone.getoffset.php
// ENUNCIADO EJEMPLO: ¿cuántas horas de diferencia hay con UTC en verano y en invierno?
// NOTA: getOffset recibe una fecha porque la diferencia CAMBIA con el horario de verano.
//       Devuelve segundos.
// ---------------------------------------------------------------------------------------------
titulo('getOffset');
$verano   = new DateTimeImmutable('2026-07-01 12:00');
$invierno = new DateTimeImmutable('2026-01-01 12:00');
ver('Madrid en verano (s)', $madrid->getOffset($verano));            // 7200 (+2 h)
ver('Madrid en invierno (s)', $madrid->getOffset($invierno));        // 3600 (+1 h)
ver('Nueva York en verano (h)', $nuevaYork->getOffset($verano) / 3600);   // -4
ver('format P en verano', $verano->format('P T'));                   // "+02:00 CEST"
ver('format P en invierno', $invierno->format('P T'));               // "+01:00 CET"

// ---------------------------------------------------------------------------------------------
// DateTimeZone::listIdentifiers — Lista de zonas válidas
// ENLACE: https://www.php.net/manual/es/datetimezone.listidentifiers.php
// ENUNCIADO EJEMPLO: rellenar un <select> para que el usuario elija su zona horaria.
// ---------------------------------------------------------------------------------------------
titulo('listIdentifiers');
$todas = DateTimeZone::listIdentifiers();
ver('número de zonas', count($todas) > 400);                          // true (más de 400)
$espana = DateTimeZone::listIdentifiers(DateTimeZone::PER_COUNTRY, 'ES');
ver('zonas de España', $espana);                                      // ['Africa/Ceuta', 'Atlantic/Canary', 'Europe/Madrid']
$opciones = array_map(fn($z) => '<option>' . htmlspecialchars($z) . '</option>', $espana);
ver('<select>', '<select name="zona">' . implode('', $opciones) . '</select>');

// ---------------------------------------------------------------------------------------------
// Guardar en UTC y mostrar en la zona del usuario
// ENLACE: https://www.php.net/manual/es/datetimeimmutable.settimezone.php
// ENUNCIADO EJEMPLO: guardar la hora de cada pedido en UTC (independiente del servidor) y
//                   mostrársela a cada usuario en su zona.
// NOTA: así no importa dónde esté el servidor ni si cambia de zona: el dato guardado es único.
// ---------------------------------------------------------------------------------------------
titulo('Guardar en UTC');
$pedido = new DateTimeImmutable('2026-10-16 21:30', $madrid);
$paraGuardar = $pedido->setTimezone($utc)->format('Y-m-d H:i:s');
ver('se guarda', $paraGuardar);                                       // "2026-10-16 19:30:00"
$leido = new DateTimeImmutable($paraGuardar, $utc);                   // al leerlo, se indica que es UTC
ver('para un usuario de Madrid', $leido->setTimezone($madrid)->format('d/m/Y H:i'));       // "16/10/2026 21:30"
ver('para un usuario de Canarias', $leido->setTimezone($canarias)->format('d/m/Y H:i'));   // "16/10/2026 20:30"
$malLeido = new DateTimeImmutable($paraGuardar);                      // ERROR: se interpreta en Madrid
ver('si se olvida la zona al leer', $malLeido->format('H:i'));         // "19:30" ¡dos horas menos!

// ---------------------------------------------------------------------------------------------
// El cambio de hora: P1D frente a PT24H
// ENLACE: https://www.php.net/manual/es/datetime.examples-arithmetic.php
// ENUNCIADO EJEMPLO: una reserva semanal el sábado 28 de marzo a las 12:00 (esa noche se adelanta
//                   la hora): ¿a qué hora es "un día después"?
// NOTA: el último domingo de marzo a las 02:00 pasan a ser las 03:00 (el día tiene 23 horas) y
//       el último domingo de octubre a las 03:00 vuelven a ser las 02:00 (25 horas).
//       P1D = mismo reloj al día siguiente; PT24H = exactamente 24 horas después.
// ---------------------------------------------------------------------------------------------
titulo('Cambio de hora');
$sabado = new DateTimeImmutable('2026-03-28 12:00');
ver('+ P1D', $sabado->add(new DateInterval('P1D'))->format('D d/m H:i T'));      // "Sun 29/03 12:00 CEST"
ver('+ PT24H', $sabado->add(new DateInterval('PT24H'))->format('D d/m H:i T'));  // "Sun 29/03 13:00 CEST"

$domingo = new DateTimeImmutable('2026-10-25 00:00');
$lunes   = new DateTimeImmutable('2026-10-26 00:00');
ver('horas del 25/10', ($lunes->getTimestamp() - $domingo->getTimestamp()) / 3600);   // 25
ver('diff dice', $domingo->diff($lunes)->format('%a día(s), %h horas'));               // "1 día(s), 0 horas"

$antes = new DateTimeImmutable('2026-10-25 01:30');
ver('01:30 + 1 hora', $antes->add(new DateInterval('PT1H'))->format('H:i T'));   // "02:30 CEST"
ver('01:30 + 2 horas', $antes->add(new DateInterval('PT2H'))->format('H:i T'));  // "02:30 CET" (¡las 02:30 se repiten!)
ver('time() + 86400 no siempre es "mañana a la misma hora"', date('d/m H:i', $sabado->getTimestamp() + 86400));   // "29/03 13:00"

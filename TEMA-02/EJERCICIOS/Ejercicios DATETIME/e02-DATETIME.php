<?php
/*
 * e02-DATETIME.php
 *  FECHA Y HORA (2): LAS CLASES DateTime Y DateTimeImmutable
 *  Métodos: new DateTime / new DateTimeImmutable, format, constantes DATE_*, getTimestamp,
 *           setTimestamp, setDate, setTime, modify, comparar (< > ==), clone, excepciones,
 *           DateTimeInterface, DateTimeImmutable::createFromMutable
 *  (apartado 3 de los apuntes)
 *
 *  Cómo trabajar este fichero:
 *   1. Pon un punto de ruptura en la primera línea de código y ejecuta en modo depuración.
 *   2. Avanza con F10 y despliega los objetos en el panel VARIABLES: verás sus campos "date" y
 *      "timezone". En la sección "mutable frente a inmutable" fíjate en qué variables cambian.
 *   3. Los comentarios a la derecha indican el resultado esperado.
 */
require_once __DIR__ . '/utilidades.php';

// ---------------------------------------------------------------------------------------------
// DateTimeImmutable::__construct — Crear una fecha a partir de un texto
// ENLACE: https://www.php.net/manual/es/datetimeimmutable.construct.php
// ENLACE: https://www.php.net/manual/es/datetime.formats.php  (formatos que entiende)
// ENUNCIADO EJEMPLO: crear las fechas de varias reservas de distintas formas.
// NOTA: entiende los mismos textos que strtotime(). Si no hay hora, pone 00:00:00. Con '@' se
//       crea a partir de una marca de tiempo (y la zona queda en UTC: +00:00).
// ---------------------------------------------------------------------------------------------
titulo('Crear fechas');
$ahora   = new DateTimeImmutable();                     // ahora mismo (depende de cuándo)
$reserva = new DateTimeImmutable('2026-10-16 21:30');
$soloDia = new DateTimeImmutable('2026-10-16');
$deUnix  = new DateTimeImmutable('@1792179000');
ver('ahora', $ahora);
ver('reserva', $reserva);                               // 2026-10-16 21:30:00 Europe/Madrid, Friday
ver('solo día', $soloDia);                              // 2026-10-16 00:00:00
ver('de marca de tiempo', $deUnix);                     // 2026-10-16 19:30:00 +00:00 (la misma, en UTC)
ver('relativas: tomorrow', new DateTimeImmutable('tomorrow'));         // mañana 00:00 (depende de cuándo)
ver('relativas: first monday of next month', new DateTimeImmutable('first monday of next month'));

// ---------------------------------------------------------------------------------------------
// ¡Cuidado con lo que entiende el constructor!
// ENLACE: https://www.php.net/manual/es/datetime.formats.php
// ENUNCIADO EJEMPLO: el ejemplo del material de referencia new DateTime('2015, May 12').
// NOTA: PHP lee "2015" como la HORA 20:15, y como no hay año pone el actual. No es un error,
//       así que no hay excepción: el fallo pasa desapercibido. Usa el formato AAAA-MM-DD o
//       createFromFormat (e03).
// ---------------------------------------------------------------------------------------------
titulo('Textos ambiguos');
$rara = new DateTimeImmutable('2015, May 12');
ver('2015, May 12', $rara->format('Y-m-d H:i:s'));      // "2026-05-12 20:15:00" (el año depende de cuándo)
ver('12 May 2015', (new DateTimeImmutable('12 May 2015'))->format('Y-m-d H:i:s'));   // "2015-05-12 00:00:00"

// ---------------------------------------------------------------------------------------------
// Excepción al crear una fecha que no se entiende
// ENLACE: https://www.php.net/manual/es/datetimeimmutable.construct.php
// ENUNCIADO EJEMPLO: un usuario escribe "el viernes que viene" en el campo de fecha.
// NOTA: en PHP 8.2 lanza Exception; en PHP 8.3, DateMalformedStringException (que hereda de
//       Exception, así que catch (Exception $e) sirve en las dos versiones).
// ---------------------------------------------------------------------------------------------
titulo('Excepciones');
try {
    $mala = new DateTimeImmutable('el viernes que viene');
    ver('no se ejecuta', $mala);
} catch (Exception $e) {
    ver('clase', get_class($e));                // "Exception" (PHP 8.2)
    ver('mensaje', $e->getMessage());           // "Failed to parse time string (el viernes que viene) at position 0 (e): ..."
}

// ---------------------------------------------------------------------------------------------
// format — Formatear una fecha (mismos caracteres que date())
// ENLACE: https://www.php.net/manual/es/datetime.format.php
// ENLACE: https://www.php.net/manual/es/class.datetimeinterface.php  (constantes DATE_ATOM...)
// ENUNCIADO EJEMPLO: mostrar la reserva para el usuario, para la base de datos y para una API.
// ---------------------------------------------------------------------------------------------
titulo('format');
ver('para el usuario', $reserva->format('d/m/Y \a \l\a\s H:i'));    // "16/10/2026 a las 21:30"
ver('para MySQL (DATETIME)', $reserva->format('Y-m-d H:i:s'));     // "2026-10-16 21:30:00"
ver('DATE_ATOM (ISO 8601)', $reserva->format(DATE_ATOM));          // "2026-10-16T21:30:00+02:00"
ver('DATE_RFC2822 (correos)', $reserva->format(DATE_RFC2822));     // "Fri, 16 Oct 2026 21:30:00 +0200"
ver('DATE_COOKIE', $reserva->format(DATE_COOKIE));                 // "Friday, 16-Oct-2026 21:30:00 CEST"
ver('microsegundos de ahora', $ahora->format('H:i:s.u'));          // con objetos SÍ hay microsegundos

// ---------------------------------------------------------------------------------------------
// getTimestamp / setTimestamp — Marca de tiempo de un objeto fecha
// ENLACE: https://www.php.net/manual/es/datetime.gettimestamp.php
// ENLACE: https://www.php.net/manual/es/datetime.settimestamp.php
// ENUNCIADO EJEMPLO: guardar la reserva como número y volver a crear la fecha a partir de él.
// ---------------------------------------------------------------------------------------------
titulo('getTimestamp y setTimestamp');
$ts = $reserva->getTimestamp();
ver('marca de tiempo', $ts);                                    // 1792179000
$recuperada = (new DateTimeImmutable())->setTimestamp($ts);
ver('recuperada', $recuperada);                                 // 2026-10-16 21:30:00 Europe/Madrid
ver('es el mismo instante', $recuperada == $reserva);           // true

// ---------------------------------------------------------------------------------------------
// setDate / setTime — Cambiar la fecha o la hora
// ENLACE: https://www.php.net/manual/es/datetimeimmutable.setdate.php
// ENLACE: https://www.php.net/manual/es/datetimeimmutable.settime.php
// ENUNCIADO EJEMPLO: mover la reserva a Nochebuena a las 14:00.
// NOTA: como en mktime, los valores fuera de rango se desbordan (el material de referencia lo
//       muestra con setDate(2015, 12, 35)).
// ---------------------------------------------------------------------------------------------
titulo('setDate y setTime');
$nochebuena = $reserva->setDate(2026, 12, 24)->setTime(14, 0);
ver('Nochebuena', $nochebuena);                                  // 2026-12-24 14:00:00, Thursday
ver('día 35 de diciembre', $reserva->setDate(2015, 12, 35)->format('Y-m-d'));   // "2016-01-04"
ver('25:70 h', $reserva->setTime(25, 70)->format('Y-m-d H:i'));                 // "2026-10-17 02:10"
ver('la reserva no ha cambiado', $reserva->format('Y-m-d H:i'));                // "2026-10-16 21:30" (es inmutable)

// ---------------------------------------------------------------------------------------------
// modify — Cambiar la fecha con una expresión relativa (en inglés)
// ENLACE: https://www.php.net/manual/es/datetimeimmutable.modify.php
// ENUNCIADO EJEMPLO: calcular el recordatorio (1 día antes), la siguiente sesión semanal y el
//                   último día del mes para la facturación.
// NOTA: las expresiones con nombre de día ("next monday") ponen la hora a 00:00. Si el texto no
//       se entiende, devuelve false y un aviso (PHP 8.2).
// ---------------------------------------------------------------------------------------------
titulo('modify');
ver('-1 day', $reserva->modify('-1 day'));                         // 2026-10-15 21:30
ver('+1 week', $reserva->modify('+1 week'));                       // 2026-10-23 21:30
ver('+90 minutes', $reserva->modify('+90 minutes'));               // 2026-10-16 23:00
ver('next monday', $reserva->modify('next monday'));               // 2026-10-19 00:00
ver('last day of this month', $reserva->modify('last day of this month'));   // 2026-10-31 21:30
ver('first day of next month', $reserva->modify('first day of next month')); // 2026-11-01 21:30
ver('last friday of next month', $reserva->modify('last friday of next month'));   // 2026-11-27 00:00
ver('midnight', $reserva->modify('midnight'));                     // 2026-10-16 00:00

// ---------------------------------------------------------------------------------------------
// DateTime (mutable) frente a DateTimeImmutable
// ENLACE: https://www.php.net/manual/es/class.datetimeimmutable.php
// ENUNCIADO EJEMPLO: calcular la fecha del recordatorio a partir de la fecha de la reserva.
// NOTA: ERROR MUY HABITUAL. Los métodos de DateTime cambian el PROPIO objeto (y lo devuelven):
//       $recordatorio y $reservaM son el MISMO objeto. Con DateTimeImmutable se obtiene uno nuevo.
// ---------------------------------------------------------------------------------------------
titulo('Mutable frente a inmutable');
$reservaM = new DateTime('2026-10-16 21:30');
$recordatorio = $reservaM->modify('-1 day');
ver('reserva (DateTime)', $reservaM);                     // 2026-10-15 ¡ha cambiado!
ver('recordatorio', $recordatorio);                       // 2026-10-15
ver('¿mismo objeto?', $recordatorio === $reservaM);       // true

$reservaI = new DateTimeImmutable('2026-10-16 21:30');
$recordatorio = $reservaI->modify('-1 day');
ver('reserva (Immutable)', $reservaI);                    // 2026-10-16: no ha cambiado
ver('recordatorio', $recordatorio);                       // 2026-10-15
ver('¿mismo objeto?', $recordatorio === $reservaI);       // false

// Con DateTime, para no modificar el original hay que hacer una copia con clone
$reservaM = new DateTime('2026-10-16 21:30');
$copia = (clone $reservaM)->modify('-1 day');
ver('con clone: reserva', $reservaM->format('Y-m-d'));    // "2026-10-16"
ver('con clone: copia', $copia->format('Y-m-d'));         // "2026-10-15"
// ... o convertirlo en inmutable
$inmutable = DateTimeImmutable::createFromMutable($reservaM);
ver('createFromMutable', get_class($inmutable));          // "DateTimeImmutable"

// ---------------------------------------------------------------------------------------------
// Comparar fechas: <, >, ==, <=, >=, <=>
// ENLACE: https://www.php.net/manual/es/datetime.diff.php  (ejemplo "Comparación de objetos DateTime")
// ENUNCIADO EJEMPLO: saber quién es mayor (ejemplo del material de referencia) y ordenar una
//                   lista de reservas por fecha.
// NOTA: se compara el INSTANTE. Con == dos objetos distintos con la misma fecha son iguales;
//       con === solo si son el mismo objeto.
// ---------------------------------------------------------------------------------------------
titulo('Comparar');
$maria  = new DateTimeImmutable('1985-04-05');
$carlos = new DateTimeImmutable('1982-08-15');
ver('María es más joven', $maria > $carlos);                                         // true: nació después
ver('== misma fecha', new DateTimeImmutable('2026-10-16') == new DateTimeImmutable('2026-10-16 00:00:00'));   // true
ver('=== misma fecha', new DateTimeImmutable('2026-10-16') === new DateTimeImmutable('2026-10-16'));          // false
$reservas = [
    ['nombre' => 'Ana', 'fecha' => new DateTimeImmutable('2026-10-17 14:00')],
    ['nombre' => 'Brais', 'fecha' => new DateTimeImmutable('2026-10-16 21:30')],
    ['nombre' => 'Carmen', 'fecha' => new DateTimeImmutable('2026-10-16 13:30')],
];
usort($reservas, fn($a, $b) => $a['fecha'] <=> $b['fecha']);
ver('ordenadas', array_column($reservas, 'nombre'));      // ['Carmen', 'Brais', 'Ana']
ver('¿ya pasó la de Ana?', $reservas[2]['fecha'] < new DateTimeImmutable());   // depende de cuándo

// ---------------------------------------------------------------------------------------------
// DateTimeInterface — Tipo común de DateTime y DateTimeImmutable
// ENLACE: https://www.php.net/manual/es/class.datetimeinterface.php
// ENUNCIADO EJEMPLO: una función que acepte cualquiera de los dos tipos de fecha.
// ---------------------------------------------------------------------------------------------
titulo('DateTimeInterface');
function esFinDeSemana(DateTimeInterface $fecha): bool
{
    return $fecha->format('N') >= 6;          // 6 = sábado, 7 = domingo
}
ver('viernes 16', esFinDeSemana(new DateTimeImmutable('2026-10-16')));   // false
ver('sábado 17', esFinDeSemana(new DateTime('2026-10-17')));            // true

<?php
/*
 * e03-DATETIME.php
 *  FECHA Y HORA (3): LEER Y VALIDAR FECHAS
 *  Métodos y funciones: DateTimeImmutable::createFromFormat (formatos, ! y |),
 *           DateTimeImmutable::getLastErrors, date_parse, validar fechas de formularios
 *           (<input type="date">, "datetime-local", "time"), fechas "dd/mm/aaaa"
 *  (apartado 4 de los apuntes)
 *
 *  Cómo trabajar este fichero:
 *   1. Pon un punto de ruptura en la primera línea de código y ejecuta en modo depuración.
 *   2. Avanza con F10. Después de cada createFromFormat, mira si el resultado es un objeto o
 *      false, y despliega el array que devuelve getLastErrors().
 *   3. Los comentarios a la derecha indican el resultado esperado.
 */
require_once __DIR__ . '/utilidades.php';

// ---------------------------------------------------------------------------------------------
// DateTimeImmutable::createFromFormat — Crear una fecha a partir de un texto con formato conocido
// ENLACE: https://www.php.net/manual/es/datetimeimmutable.createfromformat.php
// ENUNCIADO EJEMPLO: leer la fecha "16/10/2026" escrita por un usuario español (con
//                   new DateTimeImmutable se leería como mes 16 y daría error).
// NOTA: el formato usa los caracteres de date(). Devuelve false si el texto no encaja.
// ---------------------------------------------------------------------------------------------
titulo('createFromFormat');
$fecha = DateTimeImmutable::createFromFormat('d/m/Y', '16/10/2026');
ver('d/m/Y', $fecha);                        // 2026-10-16 ¡con la hora ACTUAL! (depende de cuándo)
$fecha = DateTimeImmutable::createFromFormat('d/m/Y H:i', '16/10/2026 21:30');
ver('d/m/Y H:i', $fecha);                    // 2026-10-16 21:30:00 (los segundos, los actuales)
$fecha = DateTimeImmutable::createFromFormat('d-M-y', '02-Jan-15');   // ejemplo del material de referencia
ver('d-M-y', $fecha);                        // 2015-01-02 con la hora actual
$fecha = DateTimeImmutable::createFromFormat('d/m/Y', 'mañana');
ver('texto que no encaja', $fecha);          // false

// ---------------------------------------------------------------------------------------------
// ! y | en el formato — Poner a cero lo que no aparece
// ENLACE: https://www.php.net/manual/es/datetimeimmutable.createfromformat.php (tabla de formatos: ! y |)
// ENUNCIADO EJEMPLO: leer solo una fecha (sin hora) para compararla con otras fechas.
// NOTA: sin ! los campos que faltan se toman del momento ACTUAL: dos fechas leídas con el mismo
//       texto en segundos distintos NO son iguales. Con ! al principio todo se pone a cero
//       (1970-01-01 00:00:00) antes de leer; con | al final, se ponen a cero solo los que faltan.
// ---------------------------------------------------------------------------------------------
titulo('! y |');
$conHoraActual = DateTimeImmutable::createFromFormat('d/m/Y', '16/10/2026');
$conExclamacion = DateTimeImmutable::createFromFormat('!d/m/Y', '16/10/2026');
$conBarra = DateTimeImmutable::createFromFormat('d/m/Y|', '16/10/2026');
ver('sin !', $conHoraActual->format('Y-m-d H:i:s'));        // "2026-10-16 ..." hora actual
ver('con !', $conExclamacion->format('Y-m-d H:i:s'));       // "2026-10-16 00:00:00"
ver('con |', $conBarra->format('Y-m-d H:i:s'));             // "2026-10-16 00:00:00"
ver('solo hora con !', DateTimeImmutable::createFromFormat('!H:i', '21:30')->format('Y-m-d H:i'));   // "1970-01-01 21:30"
ver('¿igual a medianoche?', $conExclamacion == new DateTimeImmutable('2026-10-16'));             // true

// ---------------------------------------------------------------------------------------------
// DateTimeImmutable::getLastErrors — Avisos y errores de la última lectura
// ENLACE: https://www.php.net/manual/es/datetimeimmutable.getlasterrors.php
// ENUNCIADO EJEMPLO: un cliente escribe "30/02/2026" en el formulario de reserva.
// NOTA: ¡IMPORTANTE! Si el texto encaja en el formato pero la fecha no existe, createFromFormat
//       NO devuelve false: la desborda (2 de marzo) y deja un AVISO ("The parsed date was
//       invalid"). Desde PHP 8.2, getLastErrors() devuelve false si no hubo avisos ni errores.
// ---------------------------------------------------------------------------------------------
titulo('getLastErrors');
$fecha = DateTimeImmutable::createFromFormat('!d/m/Y', '30/02/2026');
ver('30/02/2026 se convierte en', $fecha->format('Y-m-d'));    // "2026-03-02"
$errores = DateTimeImmutable::getLastErrors();
ver('getLastErrors', $errores);              // warning_count 1, warnings [10 => 'The parsed date was invalid']

$fecha = DateTimeImmutable::createFromFormat('!d/m/Y', '16/10/2026');
ver('fecha correcta', DateTimeImmutable::getLastErrors());     // false (no hubo avisos)

$fecha = DateTimeImmutable::createFromFormat('!d/m/Y', '16-10-2026');
ver('separador incorrecto', $fecha);                           // false
ver('errores', DateTimeImmutable::getLastErrors()['errors']);  // [2 => 'Unexpected data found.', 5 => 'Unexpected data found.']

// ---------------------------------------------------------------------------------------------
// Función validarFecha — Validación completa
// ENLACE: https://www.php.net/manual/es/datetimeimmutable.createfromformat.php
// ENUNCIADO EJEMPLO: una función reutilizable que devuelva la fecha si es válida o null si no.
// NOTA: hay dos formas de detectar las fechas imposibles: mirar getLastErrors() o volver a
//       formatear y comparar con el texto original. Aquí se hacen las dos para que las veas.
// ---------------------------------------------------------------------------------------------
titulo('validarFecha');
function validarFecha(string $texto, string $formato = 'd/m/Y'): ?DateTimeImmutable
{
    $fecha = DateTimeImmutable::createFromFormat('!' . $formato, $texto);
    $avisos = DateTimeImmutable::getLastErrors();
    if ($fecha === false || ($avisos !== false && $avisos['warning_count'] > 0)) {
        return null;
    }
    return $fecha;
}

function validarFechaComparando(string $texto, string $formato = 'd/m/Y'): ?DateTimeImmutable
{
    $fecha = DateTimeImmutable::createFromFormat('!' . $formato, $texto);
    return ($fecha !== false && $fecha->format($formato) === $texto) ? $fecha : null;
}

$pruebas = ['16/10/2026', '30/02/2026', '29/02/2028', '31/04/2026', '1/1/2026', '16/10/26', 'hola', ''];
foreach ($pruebas as $texto) {
    $a = validarFecha($texto);
    $b = validarFechaComparando($texto);
    ver("'$texto'", ($a ? $a->format('Y-m-d') : 'no válida') . ' / ' . ($b ? $b->format('Y-m-d') : 'no válida'));
}
// 16/10/2026 y 29/02/2028 válidas en las dos; 30/02/2026, 31/04/2026, hola y '' no válidas en las
// dos; '1/1/2026' es válida con getLastErrors (d acepta 1 dígito) pero NO comparando (sería
// "01/01/2026"); '16/10/26': Y acepta "26" como el año 0026 (¡no 2026!): válida con la primera,
// no válida comparando. Elige la forma que se ajuste a lo que quieres aceptar.

// ---------------------------------------------------------------------------------------------
// date_parse — Información detallada sobre una fecha (sin crear objetos)
// ENLACE: https://www.php.net/manual/es/function.date-parse.php
// ENUNCIADO EJEMPLO: ver cómo entiende PHP un texto con fecha y hora.
// NOTA: devuelve un array con year, month, day, hour... y los avisos y errores. Útil para
//       depurar: ¿qué ha entendido PHP?
// ---------------------------------------------------------------------------------------------
titulo('date_parse');
$info = date_parse('2026-10-16 21:30');
ver('año, mes, día, hora', [$info['year'], $info['month'], $info['day'], $info['hour'], $info['minute']]);   // [2026, 10, 16, 21, 30]
$info = date_parse('2026-02-30');
ver('2026-02-30: avisos', $info['warnings']);       // [11 => 'The parsed date was invalid']
$info = date_parse('2015, May 12');
ver('2015, May 12', ['year' => $info['year'], 'month' => $info['month'], 'day' => $info['day'], 'hour' => $info['hour'], 'minute' => $info['minute']]);
// year false (no hay año), month 5, day 12, hour 20, minute 15: el "2015" es la hora

// ---------------------------------------------------------------------------------------------
// Fechas de formularios: <input type="date">, "time" y "datetime-local"
// ENLACE: https://developer.mozilla.org/es/docs/Web/HTML/Element/input/date
// ENUNCIADO EJEMPLO: leer y validar los datos que envía el formulario de reservas.
// NOTA: el navegador ENVÍA siempre el formato ISO (aunque MUESTRE 16/10/2026):
//         date -> "2026-10-16"   time -> "21:30"   datetime-local -> "2026-10-16T21:30"
//       Aun así hay que validarlo: cualquiera puede enviar otra cosa (con Postman, curl...).
// ---------------------------------------------------------------------------------------------
titulo('Fechas de formularios');
$_POST = ['fecha' => '2026-10-16', 'hora' => '21:30', 'llegada' => '2026-10-16T21:30'];   // simulación
$fecha   = validarFecha($_POST['fecha'] ?? '', 'Y-m-d');
$hora    = validarFecha($_POST['hora'] ?? '', 'H:i');
$llegada = validarFecha($_POST['llegada'] ?? '', 'Y-m-d\TH:i');      // \T: la T es un literal
ver('fecha', $fecha);              // 2026-10-16 00:00:00
ver('hora', $hora);                // 1970-01-01 21:30:00 (solo interesa la hora)
ver('llegada', $llegada);          // 2026-10-16 21:30:00
// Juntar fecha y hora en un solo objeto:
$completa = $fecha->setTime((int) $hora->format('H'), (int) $hora->format('i'));
ver('fecha + hora', $completa);    // 2026-10-16 21:30:00
ver('hora 25:00', validarFecha('25:00', 'H:i'));     // NULL (no existe esa hora)

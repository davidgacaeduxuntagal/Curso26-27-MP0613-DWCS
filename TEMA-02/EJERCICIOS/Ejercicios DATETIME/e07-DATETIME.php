<?php
/*
 * e07-DATETIME.php
 *  FECHA Y HORA (7): FECHAS EN ESPAÑOL Y EN OTROS FORMATOS DE INTERCAMBIO
 *  Clases y funciones: IntlDateFormatter (estilos, patrones ICU, setPattern, formatObject,
 *           parse), por qué no usar setlocale + strftime, alternativa sin intl (arrays de
 *           nombres), fechas para bases de datos y JSON
 *  (apartados 8 y 9 de los apuntes)
 *
 *  Cómo trabajar este fichero:
 *   1. Pon un punto de ruptura en la primera línea de código y ejecuta en modo depuración.
 *   2. Avanza con F10 y compara los textos generados con distintos locales, estilos y patrones.
 *   3. Los comentarios a la derecha indican el resultado esperado.
 *  REQUISITO: extensión intl activada (en XAMPP: extension=intl en php.ini).
 */
require_once __DIR__ . '/utilidades.php';

$reserva = new DateTimeImmutable('2026-10-16 21:30');

// ---------------------------------------------------------------------------------------------
// IntlDateFormatter con estilos predefinidos
// ENLACE: https://www.php.net/manual/es/class.intldateformatter.php
// ENLACE: https://www.php.net/manual/es/intldateformatter.create.php
// ENUNCIADO EJEMPLO: mostrar la fecha de una reserva en español con distintos niveles de detalle.
// NOTA: new IntlDateFormatter($locale, $estiloFecha, $estiloHora). Estilos: FULL, LONG, MEDIUM,
//       SHORT y NONE (no mostrar esa parte). Si no se indica zona, usa la de PHP.
// ---------------------------------------------------------------------------------------------
titulo('Estilos');
$estilos = ['FULL' => IntlDateFormatter::FULL, 'LONG' => IntlDateFormatter::LONG,
            'MEDIUM' => IntlDateFormatter::MEDIUM, 'SHORT' => IntlDateFormatter::SHORT];
foreach ($estilos as $nombre => $estilo) {
    $f = new IntlDateFormatter('es_ES', $estilo, IntlDateFormatter::NONE);
    ver("fecha $nombre", $f->format($reserva));
}
// FULL "viernes, 16 de octubre de 2026"; LONG "16 de octubre de 2026"; MEDIUM "16 oct 2026"; SHORT "16/10/26"
$f = new IntlDateFormatter('es_ES', IntlDateFormatter::FULL, IntlDateFormatter::SHORT);
ver('fecha y hora', $f->format($reserva));         // "viernes, 16 de octubre de 2026, 21:30"

// ---------------------------------------------------------------------------------------------
// IntlDateFormatter con otros locales
// ENLACE: https://www.php.net/manual/es/class.intldateformatter.php
// ENUNCIADO EJEMPLO: la web del bar está en español, gallego, inglés y francés.
// NOTA: el locale es idioma_PAÍS: es_ES, gl_ES, en_US, en_GB, fr_FR... El país cambia el orden
//       y los separadores (en_US pone el mes delante).
// ---------------------------------------------------------------------------------------------
titulo('Locales');
foreach (['es_ES', 'gl_ES', 'en_US', 'en_GB', 'fr_FR'] as $locale) {
    $largo = new IntlDateFormatter($locale, IntlDateFormatter::FULL, IntlDateFormatter::NONE);
    $corto = new IntlDateFormatter($locale, IntlDateFormatter::SHORT, IntlDateFormatter::NONE);
    ver($locale, $largo->format($reserva) . '  |  ' . $corto->format($reserva));
}
// gl_ES "venres, 16 de outubro de 2026"; en_US "Friday, October 16, 2026 | 10/16/26"

// ---------------------------------------------------------------------------------------------
// Patrones a medida (ICU): sexto parámetro o setPattern
// ENLACE: https://www.php.net/manual/es/intldateformatter.setpattern.php
// ENLACE: https://unicode-org.github.io/icu/userguide/format_parse/datetime/  (tabla de patrones)
// ENUNCIADO EJEMPLO: "Reserva para el viernes 16 de octubre a las 21:30".
// NOTA: los patrones son DISTINTOS de los de date(): EEEE día de la semana, EEE abreviado,
//       d día, MMMM mes, MMM abreviado, MM mes con 2 cifras, y año, HH hora (00-23), mm minutos.
//       El texto literal va entre comillas simples ('de'). ¡mm son minutos y MM meses!
// ---------------------------------------------------------------------------------------------
titulo('Patrones');
$f = new IntlDateFormatter('es_ES', IntlDateFormatter::NONE, IntlDateFormatter::NONE,
    'Europe/Madrid', IntlDateFormatter::GREGORIAN, "EEEE d 'de' MMMM 'a las' HH:mm");
ver('reserva', 'Reserva para el ' . $f->format($reserva));   // "Reserva para el viernes 16 de octubre a las 21:30"
$f->setPattern('EEE d MMM');
ver('abreviado', $f->format($reserva));                       // "vie 16 oct"
$f->setPattern("MMMM 'de' y");
ver('mes y año', $f->format($reserva));                       // "octubre de 2026"
$f->setPattern('dd/MM/yyyy');
ver('dd/MM/yyyy', $f->format($reserva));                      // "16/10/2026"
$f->setPattern('dd/mm/yyyy');
ver('dd/mm/yyyy (error típico)', $f->format($reserva));       // "16/30/2026": mm son los MINUTOS
ver('mayúscula inicial', ucfirst(IntlDateFormatter::formatObject($reserva, 'EEEE', 'es_ES')));   // "Viernes"

// ---------------------------------------------------------------------------------------------
// IntlDateFormatter::formatObject — Formatear en una sola línea
// ENLACE: https://www.php.net/manual/es/intldateformatter.formatobject.php
// ENUNCIADO EJEMPLO: obtener solo el nombre del mes en español para un título.
// ---------------------------------------------------------------------------------------------
titulo('formatObject');
ver('mes', IntlDateFormatter::formatObject($reserva, 'MMMM', 'es_ES'));              // "octubre"
ver('día de la semana', IntlDateFormatter::formatObject($reserva, 'EEEE', 'gl_ES')); // "venres"
ver('estilo', IntlDateFormatter::formatObject($reserva, IntlDateFormatter::LONG, 'es_ES'));   // "16 de octubre de 2026, 21:30:00 CEST"

// ---------------------------------------------------------------------------------------------
// IntlDateFormatter::parse — Leer una fecha escrita en español
// ENLACE: https://www.php.net/manual/es/intldateformatter.parse.php
// ENUNCIADO EJEMPLO: leer la fecha "16 de octubre de 2026" de un documento.
// NOTA: devuelve una marca de tiempo (o false si no puede). Para los formularios es mucho más
//       sencillo usar createFromFormat con 'd/m/Y' o el formato ISO (e03).
// ---------------------------------------------------------------------------------------------
titulo('parse');
$lector = new IntlDateFormatter('es_ES', IntlDateFormatter::LONG, IntlDateFormatter::NONE);
$ts = $lector->parse('16 de octubre de 2026');
ver('marca de tiempo', $ts);                                  // 1792101600
ver('como fecha', (new DateTimeImmutable())->setTimestamp($ts)->format('Y-m-d'));   // "2026-10-16"
ver('texto que no entiende', $lector->parse('el viernes'));   // false

// ---------------------------------------------------------------------------------------------
// setlocale + strftime — LO QUE NO HAY QUE HACER (material de referencia)
// ENLACE: https://www.php.net/manual/es/function.strftime.php  (OBSOLETA desde PHP 8.1)
// ENLACE: https://www.php.net/manual/es/function.setlocale.php
// ENUNCIADO EJEMPLO: ver por qué el código del material de referencia ya no se debe usar.
// NOTA: strftime da un aviso "Deprecated" en PHP 8.1+ y se eliminará. setlocale depende de los
//       idiomas instalados en el sistema: en Windows 'es_ES' no existe (se llama
//       'Spanish_Spain') y el resultado sale en Windows-1252, no en UTF-8.
// ---------------------------------------------------------------------------------------------
titulo('setlocale y strftime (obsoletas para esto)');
$localeAnterior = setlocale(LC_TIME, '0');                    // '0' = consultar sin cambiar
$conseguido = setlocale(LC_TIME, 'es_ES.UTF-8', 'es_ES', 'Spanish_Spain', 'es');
ver('locale conseguido', $conseguido);       // "es_ES.UTF-8" en Linux; "Spanish_Spain.1252" en Windows
$texto = @strftime('%A %d de %B', $reserva->getTimestamp());   // @ oculta el aviso Deprecated
ver('strftime', $texto);                     // "viernes 16 de octubre"
$sabado = @strftime('%A', (new DateTimeImmutable('2026-10-17'))->getTimestamp());
ver('strftime de un sábado', $sabado);       // en Windows "s�bado": la á está en Windows-1252, no en UTF-8
ver('¿es UTF-8 válido?', mb_check_encoding($sabado, 'UTF-8'));   // false en Windows (true en Linux con es_ES.UTF-8)
setlocale(LC_TIME, $localeAnterior);         // lo dejamos como estaba

// ---------------------------------------------------------------------------------------------
// Alternativa sin la extensión intl: arrays con los nombres
// ENLACE: https://www.php.net/manual/es/datetime.format.php
// ENUNCIADO EJEMPLO: mostrar "viernes, 16 de octubre de 2026" en un servidor sin intl.
// NOTA: format('N') da el día de la semana (1 = lunes) y format('n') el mes (1-12): se usan como
//       índice de los arrays.
// ---------------------------------------------------------------------------------------------
titulo('Sin intl');
function fechaEnEspanol(DateTimeInterface $fecha): string
{
    $dias = [1 => 'lunes', 'martes', 'miércoles', 'jueves', 'viernes', 'sábado', 'domingo'];
    $meses = [1 => 'enero', 'febrero', 'marzo', 'abril', 'mayo', 'junio', 'julio', 'agosto',
              'septiembre', 'octubre', 'noviembre', 'diciembre'];
    return $dias[(int) $fecha->format('N')] . ', ' . $fecha->format('j') . ' de '
        . $meses[(int) $fecha->format('n')] . ' de ' . $fecha->format('Y');
}
ver('a mano', fechaEnEspanol($reserva));                         // "viernes, 16 de octubre de 2026"
ver('un sábado', fechaEnEspanol(new DateTimeImmutable('2026-10-17')));   // "sábado, 17 de octubre de 2026"

// ---------------------------------------------------------------------------------------------
// Fechas para bases de datos y JSON
// ENLACE: https://dev.mysql.com/doc/refman/8.0/en/datetime.html
// ENLACE: https://www.php.net/manual/es/class.datetimeinterface.php
// ENUNCIADO EJEMPLO: guardar la reserva en MySQL y enviarla a la app del móvil en JSON.
// NOTA: MySQL usa 'Y-m-d' (DATE) y 'Y-m-d H:i:s' (DATETIME). En JSON, texto ISO 8601 (DATE_ATOM):
//       json_encode de un objeto DateTime produce un formato propio de PHP.
// ---------------------------------------------------------------------------------------------
titulo('Bases de datos y JSON');
$paraMysql = $reserva->format('Y-m-d H:i:s');
ver('para MySQL', $paraMysql);                                   // "2026-10-16 21:30:00"
$deMysql = DateTimeImmutable::createFromFormat('Y-m-d H:i:s', '2026-10-16 21:30:00');
ver('leído de MySQL', $deMysql);                                 // 2026-10-16 21:30:00
ver('json_encode del objeto', json_encode($reserva));            // {"date":"2026-10-16 21:30:00.000000","timezone_type":3,...}
ver('json correcto', json_encode(['reserva' => $reserva->format(DATE_ATOM)]));   // {"reserva":"2026-10-16T21:30:00+02:00"}
$recibida = json_decode('{"reserva":"2026-10-16T21:30:00+02:00"}', true);
ver('leído del JSON', DateTimeImmutable::createFromFormat(DATE_ATOM, $recibida['reserva']));   // 2026-10-16 21:30:00 +02:00

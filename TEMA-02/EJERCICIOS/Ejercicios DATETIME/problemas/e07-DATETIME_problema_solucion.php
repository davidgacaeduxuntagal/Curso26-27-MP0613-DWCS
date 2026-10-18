<?php
/*
 * e07-DATETIME_problema_solucion.php
 *  (FICHERO CON LAS SOLUCIONES)
 *  Problemas de: IntlDateFormatter (estilos, locales, patrones, formatObject), fechas en
 *                español sin intl, fechas para bases de datos y JSON
 *  (repasa los ejemplos de ../e07-DATETIME.php)
 *
 *  Resuelve cada problema donde se indica y ejecútalo en modo depuración para comprobarlo.
 */
require_once __DIR__ . '/../utilidades.php';

$fecha = new DateTimeImmutable('2026-12-24 21:00');

// =============================================================================================
// PROBLEMA 1. Cabecera de la carta del día
//  Con IntlDateFormatter y un patrón, genera exactamente:
//   a) "Jueves, 24 de diciembre"   b) "dic 2026"   c) "24/12/2026 21:00"   d) "Xoves, 24 de decembro"
// =============================================================================================
titulo('Problema 1');
// completa aquí tu solución
// ---- SOLUCIÓN ----
ver('a', ucfirst(IntlDateFormatter::formatObject($fecha, "EEEE, d 'de' MMMM", 'es_ES')));   // "Jueves, 24 de diciembre"
ver('b', IntlDateFormatter::formatObject($fecha, 'MMM y', 'es_ES'));                       // "dic 2026"
ver('c', IntlDateFormatter::formatObject($fecha, 'dd/MM/yyyy HH:mm', 'es_ES'));            // "24/12/2026 21:00"
ver('d', ucfirst(IntlDateFormatter::formatObject($fecha, "EEEE, d 'de' MMMM", 'gl_ES')));   // "Xoves, 24 de decembro"

// =============================================================================================
// PROBLEMA 2. Web en varios idiomas
//  Escribe fechaLocal(DateTimeInterface $fecha, string $idioma): string que muestre la fecha
//  con estilo LONG para 'es', 'gl', 'en' y 'fr' (usa es_ES, gl_ES, en_GB y fr_FR). Si el idioma
//  no está en la lista, usa el español.
// =============================================================================================
titulo('Problema 2');
// completa aquí tu solución
// ---- SOLUCIÓN ----
function fechaLocal(DateTimeInterface $fecha, string $idioma): string
{
    $locales = ['es' => 'es_ES', 'gl' => 'gl_ES', 'en' => 'en_GB', 'fr' => 'fr_FR'];
    $f = new IntlDateFormatter($locales[$idioma] ?? 'es_ES', IntlDateFormatter::LONG, IntlDateFormatter::NONE);
    return $f->format($fecha);
}
foreach (['es', 'gl', 'en', 'fr', 'de'] as $idioma) {
    ver($idioma, fechaLocal($fecha, $idioma));
}
// es "24 de diciembre de 2026", gl "24 de decembro de 2026", en "24 December 2026", fr "24 décembre 2026", de -> español

// =============================================================================================
// PROBLEMA 3. Sin la extensión intl
//  Completa la función fechaCorta(DateTimeInterface $fecha): string para que devuelva, sin
//  usar IntlDateFormatter, el formato "jue 24 dic" (tres letras del día y del mes en español).
// =============================================================================================
titulo('Problema 3');
function fechaCorta(DateTimeInterface $fecha): string
{
    // completa aquí la función
    // ---- SOLUCIÓN ----
    $dias = [1 => 'lun', 'mar', 'mié', 'jue', 'vie', 'sáb', 'dom'];
    $meses = [1 => 'ene', 'feb', 'mar', 'abr', 'may', 'jun', 'jul', 'ago', 'sep', 'oct', 'nov', 'dic'];
    return $dias[(int) $fecha->format('N')] . ' ' . $fecha->format('j') . ' ' . $meses[(int) $fecha->format('n')];
}
// pruébala aquí con $fecha y con el sábado 17/10/2026 (si la llamas sin completarla, da un TypeError)
// ---- SOLUCIÓN ----
ver('fecha corta', fechaCorta($fecha));                                 // "jue 24 dic"
ver('un sábado', fechaCorta(new DateTimeImmutable('2026-10-17')));     // "sáb 17 oct"

// =============================================================================================
// PROBLEMA 4. De la base de datos a la página y a la API
//  $fila es una fila de la tabla de reservas (MySQL devuelve las fechas como texto). Muestra la
//  fecha para la página con el formato FULL + SHORT en español, y genera el JSON para la app con
//  la fecha en ISO 8601 (DATE_ATOM) y la marca de tiempo.
// =============================================================================================
titulo('Problema 4');
$fila = ['id' => 7, 'nombre' => 'Brais', 'fecha' => '2026-12-24 21:00:00'];
// completa aquí tu solución
// ---- SOLUCIÓN ----
$reserva = DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $fila['fecha']);
$f = new IntlDateFormatter('es_ES', IntlDateFormatter::FULL, IntlDateFormatter::SHORT);
ver('página', 'Reserva de ' . $fila['nombre'] . ': ' . $f->format($reserva));   // "... jueves, 24 de diciembre de 2026, 21:00"
$json = json_encode(['id' => $fila['id'], 'fecha' => $reserva->format(DATE_ATOM), 'timestamp' => $reserva->getTimestamp()]);
ver('JSON', $json);       // {"id":7,"fecha":"2026-12-24T21:00:00+01:00","timestamp":1798142400}

// =============================================================================================
// PROBLEMA 5. "Hace un rato"
//  En las opiniones de la web se muestra cuánto tiempo hace que se escribieron: "hace unos
//  segundos" (menos de 1 minuto), "hace X minutos", "hace X horas", "ayer" o, si es más
//  antigua, la fecha en español "d 'de' MMMM". ¿Cómo harías la función haceCuanto()?
// =============================================================================================
titulo('Problema 5');
$ahora = new DateTimeImmutable('2026-10-16 21:30');
$opiniones = ['2026-10-16 21:29:40', '2026-10-16 21:05', '2026-10-16 18:10', '2026-10-15 22:00', '2026-09-30 13:00'];
// completa aquí tu solución
// ---- SOLUCIÓN ----
function haceCuanto(DateTimeImmutable $fecha, DateTimeImmutable $ahora): string
{
    $segundos = $ahora->getTimestamp() - $fecha->getTimestamp();
    if ($segundos < 60) {
        return 'hace unos segundos';
    }
    if ($segundos < 3600) {
        return 'hace ' . intdiv($segundos, 60) . ' minutos';
    }
    if ($fecha->format('Y-m-d') === $ahora->format('Y-m-d')) {
        return 'hace ' . intdiv($segundos, 3600) . ' horas';
    }
    if ($fecha->format('Y-m-d') === $ahora->modify('-1 day')->format('Y-m-d')) {
        return 'ayer';
    }
    return 'el ' . IntlDateFormatter::formatObject($fecha, "d 'de' MMMM", 'es_ES');
}
foreach ($opiniones as $texto) {
    ver($texto, haceCuanto(new DateTimeImmutable($texto), $ahora));
}
// "hace unos segundos", "hace 25 minutos", "hace 3 horas", "ayer", "el 30 de septiembre"

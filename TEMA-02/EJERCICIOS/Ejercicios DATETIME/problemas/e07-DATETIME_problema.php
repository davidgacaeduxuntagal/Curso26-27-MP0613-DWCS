<?php
/*
 * e07-DATETIME_problema.php
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

// =============================================================================================
// PROBLEMA 2. Web en varios idiomas
//  Escribe fechaLocal(DateTimeInterface $fecha, string $idioma): string que muestre la fecha
//  con estilo LONG para 'es', 'gl', 'en' y 'fr' (usa es_ES, gl_ES, en_GB y fr_FR). Si el idioma
//  no está en la lista, usa el español.
// =============================================================================================
titulo('Problema 2');
// completa aquí tu solución

// =============================================================================================
// PROBLEMA 3. Sin la extensión intl
//  Completa la función fechaCorta(DateTimeInterface $fecha): string para que devuelva, sin
//  usar IntlDateFormatter, el formato "jue 24 dic" (tres letras del día y del mes en español).
// =============================================================================================
titulo('Problema 3');
function fechaCorta(DateTimeInterface $fecha): string
{
    // completa aquí la función
}
// pruébala aquí con $fecha y con el sábado 17/10/2026 (si la llamas sin completarla, da un TypeError)

// =============================================================================================
// PROBLEMA 4. De la base de datos a la página y a la API
//  $fila es una fila de la tabla de reservas (MySQL devuelve las fechas como texto). Muestra la
//  fecha para la página con el formato FULL + SHORT en español, y genera el JSON para la app con
//  la fecha en ISO 8601 (DATE_ATOM) y la marca de tiempo.
// =============================================================================================
titulo('Problema 4');
$fila = ['id' => 7, 'nombre' => 'Brais', 'fecha' => '2026-12-24 21:00:00'];
// completa aquí tu solución

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

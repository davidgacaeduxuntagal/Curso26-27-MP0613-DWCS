<?php
/*
 * e06-DATETIME_problema_solucion.php
 *  (FICHERO CON LAS SOLUCIONES)
 *  Problemas de: DateTimeZone, setTimezone, getOffset, listIdentifiers, guardar en UTC,
 *                cambio de hora
 *  (repasa los ejemplos de ../e06-DATETIME.php)
 *
 *  Resuelve cada problema donde se indica y ejecútalo en modo depuración para comprobarlo.
 */
require_once __DIR__ . '/../utilidades.php';

// =============================================================================================
// PROBLEMA 1. Videollamada internacional
//  La reunión con los proveedores es el 20/10/2026 a las 17:00 hora de Madrid. Muestra a qué
//  hora (y qué día) es para cada participante de $participantes.
// =============================================================================================
titulo('Problema 1');
$participantes = ['Lucía' => 'America/Mexico_City', 'Tom' => 'Europe/London', 'Kenji' => 'Asia/Tokyo', 'Ana' => 'Atlantic/Canary'];
// completa aquí tu solución
// ---- SOLUCIÓN ----
$reunion = new DateTimeImmutable('2026-10-20 17:00', new DateTimeZone('Europe/Madrid'));
foreach ($participantes as $nombre => $zona) {
    ver($nombre, $reunion->setTimezone(new DateTimeZone($zona))->format('D d/m H:i T'));
}
// Lucía "Tue 20/10 09:00 CST", Tom "Tue 20/10 16:00 BST", Kenji "Wed 21/10 00:00 JST", Ana "Tue 20/10 16:00 WEST"

// =============================================================================================
// PROBLEMA 2. Leer fechas guardadas en UTC
//  La base de datos guarda la hora de los pedidos en UTC ("Y-m-d H:i:s"). Escribe
//  mostrarEnZona(string $utc, string $zona): string que devuelva la hora en la zona del
//  usuario con el formato "d/m/Y H:i". Comprueba que en Madrid cambia la diferencia en verano
//  y en invierno.
// =============================================================================================
titulo('Problema 2');
$guardados = ['2026-07-15 19:30:00', '2026-12-15 19:30:00'];
// completa aquí tu solución
// ---- SOLUCIÓN ----
function mostrarEnZona(string $utc, string $zona): string
{
    $fecha = new DateTimeImmutable($utc, new DateTimeZone('UTC'));    // ¡indicar que es UTC!
    return $fecha->setTimezone(new DateTimeZone($zona))->format('d/m/Y H:i');
}
foreach ($guardados as $utc) {
    ver($utc, mostrarEnZona($utc, 'Europe/Madrid'));   // "15/07/2026 21:30" (+2) y "15/12/2026 20:30" (+1)
}

// =============================================================================================
// PROBLEMA 3. Selector de zonas horarias
//  Genera un <select> con las zonas horarias de Europa (DateTimeZone::EUROPE) mostrando la
//  diferencia actual con UTC, por ejemplo "Europe/Madrid (+02:00)". Marca como seleccionada
//  Europe/Madrid. Muestra solo las 3 primeras opciones y el número total.
// =============================================================================================
titulo('Problema 3');
$referencia = new DateTimeImmutable('2026-10-16 12:00', new DateTimeZone('UTC'));
// completa aquí tu solución
// ---- SOLUCIÓN ----
$opciones = [];
foreach (DateTimeZone::listIdentifiers(DateTimeZone::EUROPE) as $zona) {
    $diferencia = $referencia->setTimezone(new DateTimeZone($zona))->format('P');
    $marcada = $zona === 'Europe/Madrid' ? ' selected' : '';
    $opciones[] = '<option value="' . htmlspecialchars($zona) . '"' . $marcada . '>'
        . htmlspecialchars("$zona ($diferencia)") . '</option>';
}
ver('primeras', array_slice($opciones, 0, 3));   // Europe/Amsterdam (+02:00), Europe/Andorra (+02:00), Europe/Astrakhan (+04:00)
ver('total', count($opciones) > 50);            // true

// =============================================================================================
// PROBLEMA 4. El día del cambio de hora
//  El 25 de octubre de 2026 se retrasa la hora en España. Un trabajador entra a las 22:00 del
//  sábado 24 y sale a las 06:00 del domingo 25. ¿Cuántas horas ha trabajado de verdad? Calcúlalo
//  con marcas de tiempo y compáralo con lo que daría restar las horas del reloj.
// =============================================================================================
titulo('Problema 4');
$entrada = new DateTimeImmutable('2026-10-24 22:00');
$salida = new DateTimeImmutable('2026-10-25 06:00');
// completa aquí tu solución
// ---- SOLUCIÓN ----
$horasReales = ($salida->getTimestamp() - $entrada->getTimestamp()) / 3600;
ver('horas reales', $horasReales);                  // 9 (a las 03:00 volvieron a ser las 02:00)
ver('horas de reloj', 24 - 22 + 6);                 // 8
ver('diff', $entrada->diff($salida)->format('%h horas'));   // diff también da "9 horas"

// =============================================================================================
// PROBLEMA 5. ¿Cómo harías...?
//  Una alarma tiene que sonar TODOS los días a las 08:00 (hora de Madrid) durante la semana
//  del 26 al 31 de marzo de 2026 (el 29 cambia la hora). ¿Cómo generarías las marcas de tiempo
//  para que siempre suene a las 08:00 de reloj? Compara sumar P1D con sumar 86400 segundos.
// =============================================================================================
titulo('Problema 5');
$primera = new DateTimeImmutable('2026-03-26 08:00');
// completa aquí tu solución
// ---- SOLUCIÓN ----
$conP1D = [];
$con86400 = [];
foreach (new DatePeriod($primera, new DateInterval('P1D'), 5) as $i => $alarma) {
    $conP1D[] = $alarma->format('d/m H:i');
    $con86400[] = date('d/m H:i', $primera->getTimestamp() + $i * 86400);
}
ver('con P1D', $conP1D);         // siempre 08:00
ver('con 86400 s', $con86400);   // desde el 29/03, a las 09:00

<?php
/*
 * e06-DATETIME_problema.php
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

// =============================================================================================
// PROBLEMA 3. Selector de zonas horarias
//  Genera un <select> con las zonas horarias de Europa (DateTimeZone::EUROPE) mostrando la
//  diferencia actual con UTC, por ejemplo "Europe/Madrid (+02:00)". Marca como seleccionada
//  Europe/Madrid. Muestra solo las 3 primeras opciones y el número total.
// =============================================================================================
titulo('Problema 3');
$referencia = new DateTimeImmutable('2026-10-16 12:00', new DateTimeZone('UTC'));
// completa aquí tu solución

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

// =============================================================================================
// PROBLEMA 5. ¿Cómo harías...?
//  Una alarma tiene que sonar TODOS los días a las 08:00 (hora de Madrid) durante la semana
//  del 26 al 31 de marzo de 2026 (el 29 cambia la hora). ¿Cómo generarías las marcas de tiempo
//  para que siempre suene a las 08:00 de reloj? Compara sumar P1D con sumar 86400 segundos.
// =============================================================================================
titulo('Problema 5');
$primera = new DateTimeImmutable('2026-03-26 08:00');
// completa aquí tu solución

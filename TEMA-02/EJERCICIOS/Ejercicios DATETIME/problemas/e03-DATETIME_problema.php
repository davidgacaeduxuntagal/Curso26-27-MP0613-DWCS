<?php
/*
 * e03-DATETIME_problema.php
 *  Problemas de: createFromFormat (! y |), getLastErrors, date_parse, validar fechas de
 *                formularios
 *  (repasa los ejemplos de ../e03-DATETIME.php)
 *
 *  Resuelve cada problema donde se indica y ejecútalo en modo depuración para comprobarlo.
 */
require_once __DIR__ . '/../utilidades.php';

// =============================================================================================
// PROBLEMA 1. Leer fechas con distintos formatos
//  Convierte cada texto de $textos (y su formato) en un DateTimeImmutable sin hora (00:00:00)
//  y muéstralo como "Y-m-d". Todas son la misma fecha: compruébalo con ==.
// =============================================================================================
titulo('Problema 1');
$textos = [
    ['16/10/2026', 'd/m/Y'],
    ['2026-10-16', 'Y-m-d'],
    ['16.10.26', 'd.m.y'],
    ['20261016', 'Ymd'],
    ['Oct 16, 2026', 'M d, Y'],
];
// completa aquí tu solución

// =============================================================================================
// PROBLEMA 2. ¿Por qué no son iguales?
//  Un compañero quiere comprobar si la fecha que escribe el usuario es el día de la fiesta del
//  bar. Las dos fechas son el 16/10/2026, pero la comparación con == le da false. Explica el
//  motivo en un comentario y corrígelo.
// =============================================================================================
titulo('Problema 2');
$fiesta = new DateTimeImmutable('2026-10-16');
$escrita = DateTimeImmutable::createFromFormat('d/m/Y', '16/10/2026');
ver('fiesta', $fiesta);
ver('escrita', $escrita);
ver('¿es el día de la fiesta? (compañero)', $escrita == $fiesta);   // false (salvo a las 00:00:00 en punto)
// completa aquí tu solución

// =============================================================================================
// PROBLEMA 3. Validar con mensajes
//  Escribe validarFechaReserva(string $texto): string que devuelva:
//   "formato incorrecto" si no encaja en d/m/Y, "la fecha no existe" si el formato es correcto
//   pero la fecha es imposible (usa getLastErrors), o la fecha como "Y-m-d" si es válida.
// =============================================================================================
titulo('Problema 3');
$pruebas = ['16/10/2026', '31/11/2026', '2026-10-16', '16/10', '29/02/2028', '00/10/2026'];
// completa aquí tu solución

// =============================================================================================
// PROBLEMA 4. El formulario de una cita médica
//  El formulario envía "fecha" (input date) y "hora" (input time). Junta los dos campos en un
//  solo DateTimeImmutable (con createFromFormat y el formato adecuado) y valida que: la fecha y
//  la hora existen, los minutos son 00, 15, 30 o 45, y no es sábado ni domingo. Devuelve un
//  array de errores (vacío si es correcta).
// =============================================================================================
titulo('Problema 4');
$envios = [
    ['fecha' => '2026-10-19', 'hora' => '10:30'],
    ['fecha' => '2026-10-18', 'hora' => '10:30'],
    ['fecha' => '2026-10-19', 'hora' => '10:20'],
    ['fecha' => '2026-13-01', 'hora' => '10:30'],
];
// completa aquí tu solución

// =============================================================================================
// PROBLEMA 5. Leer un registro (log) del servidor
//  Cada línea de $log empieza por una fecha con el formato de Apache "[16/Oct/2026:21:30:05
//  +0200]". Extrae la fecha de cada línea (con preg_match o substr) y conviértela en un
//  DateTimeImmutable con createFromFormat. Muestra cuántos segundos pasaron entre la primera
//  y la última petición.
// =============================================================================================
titulo('Problema 5');
$log = [
    '[16/Oct/2026:21:30:05 +0200] "GET /carta.php HTTP/1.1" 200',
    '[16/Oct/2026:21:30:47 +0200] "POST /reserva.php HTTP/1.1" 201',
    '[16/Oct/2026:21:32:10 +0200] "GET /gracias.php HTTP/1.1" 200',
];
// completa aquí tu solución

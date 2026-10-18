<?php
/*
 * e01-DATETIME.php
 *  FECHA Y HORA (1): MARCA DE TIEMPO, ZONA HORARIA Y FUNCIONES CLÁSICAS
 *  Funciones: time, date_default_timezone_get, date_default_timezone_set, date, mktime,
 *             checkdate, strtotime, microtime, hrtime
 *  (apartados 1 y 2 de los apuntes)
 *
 *  Cómo trabajar este fichero:
 *   1. Pon un punto de ruptura en la primera línea de código y ejecuta en modo depuración.
 *   2. Avanza con F10 y mira en el panel VARIABLES los enteros (marcas de tiempo) y los textos.
 *   3. Los comentarios a la derecha indican el resultado esperado. Los que dependen del momento
 *      en que ejecutas el ejemplo (time(), "now"...) están marcados con "depende de cuándo".
 *  NOTA: utilidades.php fija la zona horaria Europe/Madrid para todos los ejemplos.
 */
require_once __DIR__ . '/utilidades.php';

// ---------------------------------------------------------------------------------------------
// time — Devuelve la fecha Unix actual (marca de tiempo)
// ENLACE: https://www.php.net/manual/es/function.time.php
// ENUNCIADO EJEMPLO: obtener el instante actual como número de segundos desde el 1/1/1970 UTC.
// NOTA: es un int. El mismo número representa el mismo instante en todo el mundo; la zona
//       horaria solo influye al MOSTRARLO.
// ---------------------------------------------------------------------------------------------
titulo('time');
$ahora = time();
ver('time()', $ahora);                          // un número de 10 cifras (depende de cuándo)
ver('tipo', gettype($ahora));                   // "integer"
ver('dentro de una hora', $ahora + 3600);       // sumar segundos es fácil...
ver('dentro de un día', $ahora + 24 * 60 * 60); // ...pero ojo con los cambios de hora (e06)

// ---------------------------------------------------------------------------------------------
// date_default_timezone_get / date_default_timezone_set — Zona horaria por defecto
// ENLACE: https://www.php.net/manual/es/function.date-default-timezone-get.php
// ENLACE: https://www.php.net/manual/es/function.date-default-timezone-set.php
// ENUNCIADO EJEMPLO: ver qué hora es "ahora" en Madrid y en Canarias.
// NOTA: afecta a TODAS las funciones de fecha del script. Usa identificadores Continente/Ciudad.
//       set devuelve false (y un aviso) si el identificador no existe.
// ---------------------------------------------------------------------------------------------
titulo('Zona horaria por defecto');
$instante = mktime(21, 30, 0, 10, 16, 2026);           // 16/10/2026 21:30 en Madrid
ver('zona actual', date_default_timezone_get());       // "Europe/Madrid"
ver('en Madrid', date('H:i', $instante));              // "21:30"
date_default_timezone_set('Atlantic/Canary');
ver('en Canarias', date('H:i', $instante));            // "20:30": mismo instante, otra hora
date_default_timezone_set('UTC');
ver('en UTC', date('H:i', $instante));                 // "19:30"
date_default_timezone_set('Europe/Madrid');            // la dejamos como estaba
ver('zona no válida', @date_default_timezone_set('Madrid'));   // false (con @ ocultamos el aviso)

// ---------------------------------------------------------------------------------------------
// date — Dar formato a la fecha/hora local
// ENLACE: https://www.php.net/manual/es/function.date.php  (tabla completa de formatos)
// ENUNCIADO EJEMPLO: mostrar la fecha de una reserva de varias formas.
// NOTA: sin segundo parámetro usa el instante actual. Los nombres de días y meses salen SIEMPRE
//       en inglés (para español: IntlDateFormatter, en e07). Para escribir una letra literal se
//       escapa con \ (dentro de comillas simples).
// ---------------------------------------------------------------------------------------------
titulo('date');
$reserva = mktime(21, 5, 9, 10, 16, 2026);             // viernes 16/10/2026 21:05:09
ver('d/m/Y', date('d/m/Y', $reserva));                 // "16/10/2026"
ver('j/n/y', date('j/n/y', $reserva));                 // "16/10/26" (sin ceros iniciales)
ver('H:i:s', date('H:i:s', $reserva));                 // "21:05:09"
ver('g:i a', date('g:i a', $reserva));                 // "9:05 pm"
ver('l, F jS', date('l, F jS', $reserva));             // "Friday, October 16th"
ver('día de la semana (N)', date('N', $reserva));      // "5" (1 = lunes ... 7 = domingo)
ver('día del año (z)', date('z', $reserva));           // "288" (empieza en 0)
ver('semana ISO (W)', date('W', $reserva));            // "42"
ver('días del mes (t)', date('t', $reserva));          // "31"
ver('¿bisiesto? (L)', date('L', $reserva));            // "0"
ver('zona (e T P)', date('e T P', $reserva));          // "Europe/Madrid CEST +02:00"
ver('ISO 8601 (c)', date('c', $reserva));              // "2026-10-16T21:05:09+02:00"
ver('con texto', date('\H\o\y \e\s l', $reserva));     // "Hoy es Friday"
ver('pie de página', '© ' . date('Y') . ' Bar Baio');  // "© 2026 Bar Baio" (depende de cuándo)
ver('tipo del resultado', gettype(date('d')));         // "string": "05", no 5

// ---------------------------------------------------------------------------------------------
// mktime — Obtener la marca de tiempo Unix de una fecha
// ENLACE: https://www.php.net/manual/es/function.mktime.php
// ENUNCIADO EJEMPLO: calcular la marca de tiempo de la cena de Nochebuena y de fechas "raras".
// NOTA: el orden es hora, minuto, segundo, MES, DÍA, año. Los valores fuera de rango se
//       DESBORDAN (no da error): día 0 = último día del mes anterior; mes 13 = enero siguiente.
// ---------------------------------------------------------------------------------------------
titulo('mktime');
$nochebuena = mktime(21, 0, 0, 12, 24, 2026);
ver('Nochebuena', date('Y-m-d H:i', $nochebuena));                      // "2026-12-24 21:00"
ver('30 de febrero', date('Y-m-d', mktime(0, 0, 0, 2, 30, 2026)));      // "2026-03-02" ¡desbordado!
ver('día 0 de marzo', date('Y-m-d', mktime(0, 0, 0, 3, 0, 2026)));      // "2026-02-28" (último de febrero)
ver('mes 13 de 2026', date('Y-m-d', mktime(0, 0, 0, 13, 1, 2026)));     // "2027-01-01"
ver('día 1 + 45', date('Y-m-d', mktime(0, 0, 0, 10, 1 + 45, 2026)));    // "2026-11-15" (sumar días)

// ---------------------------------------------------------------------------------------------
// checkdate — Validar una fecha gregoriana
// ENLACE: https://www.php.net/manual/es/function.checkdate.php
// ENUNCIADO EJEMPLO: comprobar el día, mes y año que llegan de tres <select> de un formulario.
// NOTA: el orden es MES, DÍA, AÑO (como en EE. UU.). Recibe enteros: convierte antes los textos
//       con (int) y comprueba que eran números.
// ---------------------------------------------------------------------------------------------
titulo('checkdate');
ver('29/02/2026', checkdate(2, 29, 2026));      // false: 2026 no es bisiesto
ver('29/02/2028', checkdate(2, 29, 2028));      // true
ver('31/04/2026', checkdate(4, 31, 2026));      // false: abril tiene 30 días
ver('16/10/2026', checkdate(10, 16, 2026));     // true
$formulario = ['dia' => '31', 'mes' => '06', 'anio' => '2026'];   // como llega en $_POST
$valida = ctype_digit($formulario['dia']) && ctype_digit($formulario['mes']) && ctype_digit($formulario['anio'])
    && checkdate((int) $formulario['mes'], (int) $formulario['dia'], (int) $formulario['anio']);
ver('31/06/2026 del formulario', $valida);      // false: junio tiene 30 días

// ---------------------------------------------------------------------------------------------
// strtotime — Convierte una descripción textual (en inglés) en una marca de tiempo
// ENLACE: https://www.php.net/manual/es/function.strtotime.php
// ENLACE: https://www.php.net/manual/es/datetime.formats.php  (formatos que entiende)
// ENUNCIADO EJEMPLO: calcular fechas relativas a la fecha de una reserva.
// NOTA: el segundo parámetro es la fecha de referencia (por defecto, ahora). Devuelve false si
//       no entiende el texto: compruébalo siempre con === false.
// ---------------------------------------------------------------------------------------------
titulo('strtotime');
$base = strtotime('2026-10-16 21:30');
ver('base', date('Y-m-d H:i (D)', $base));                                       // "2026-10-16 21:30 (Fri)"
ver('+1 day', date('Y-m-d H:i', strtotime('+1 day', $base)));                   // "2026-10-17 21:30"
ver('+2 weeks', date('Y-m-d', strtotime('+2 weeks', $base)));                   // "2026-10-30"
ver('next monday', date('Y-m-d H:i', strtotime('next monday', $base)));         // "2026-10-19 00:00"
ver('last day of next month', date('Y-m-d', strtotime('last day of next month', $base)));   // "2026-11-30"
ver('tomorrow', date('Y-m-d H:i', strtotime('tomorrow', $base)));               // "2026-10-17 00:00"
ver('16/10/2026', strtotime('16/10/2026'));     // false: con / lo lee como mes/día/año (y no hay mes 16)
ver('10/16/2026', date('Y-m-d', strtotime('10/16/2026')));    // "2026-10-16" (formato de EE. UU.)
ver('16-10-2026', date('Y-m-d', strtotime('16-10-2026')));    // "2026-10-16" (con - lo lee como día-mes-año)
ver('mañana', strtotime('mañana'));             // false: solo entiende inglés

// ---------------------------------------------------------------------------------------------
// microtime / hrtime — Tiempo con más precisión (para medir cuánto tarda algo)
// ENLACE: https://www.php.net/manual/es/function.microtime.php
// ENLACE: https://www.php.net/manual/es/function.hrtime.php
// ENUNCIADO EJEMPLO: medir cuánto tarda en calcularse una suma de un millón de números.
// NOTA: microtime(true) da los segundos con decimales (float). hrtime(true) da nanosegundos (int)
//       desde un momento arbitrario: solo sirve para MEDIR intervalos, no para saber la fecha.
// ---------------------------------------------------------------------------------------------
titulo('microtime y hrtime');
$inicio = hrtime(true);
$suma = array_sum(range(1, 1_000_000));
$fin = hrtime(true);
ver('suma', $suma);                                               // 500000500000
ver('milisegundos', round(($fin - $inicio) / 1e6, 2));            // unos pocos ms (depende del equipo)
ver('microtime(true)', microtime(true));                          // p. ej. 1792177509.1234 (depende de cuándo)

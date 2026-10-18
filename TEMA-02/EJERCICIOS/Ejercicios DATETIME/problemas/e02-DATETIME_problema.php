<?php
/*
 * e02-DATETIME_problema.php
 *  Problemas de: DateTime / DateTimeImmutable, format, getTimestamp, setDate, setTime, modify,
 *                comparar fechas, mutable frente a inmutable
 *  (repasa los ejemplos de ../e02-DATETIME.php)
 *
 *  Resuelve cada problema donde se indica y ejecútalo en modo depuración para comprobarlo.
 */
require_once __DIR__ . '/../utilidades.php';

// =============================================================================================
// PROBLEMA 1. Encuentra el fallo
//  Este código quiere mostrar la fecha de un evento y la fecha del aviso (3 días antes), pero
//  las dos salen iguales. Explica el fallo en un comentario y corrígelo de DOS formas: con
//  clone y con DateTimeImmutable.
// =============================================================================================
titulo('Problema 1');
$evento = new DateTime('2026-11-11 18:00');      // magosto
$aviso = $evento->modify('-3 days');
ver('evento (mal)', $evento->format('d/m H:i'));
ver('aviso (mal)', $aviso->format('d/m H:i'));
// completa aquí tu solución

// =============================================================================================
// PROBLEMA 2. Turnos del bar
//  El bar abre a las 13:00 y cierra a las 16:00 para la comida, y de 20:30 a 23:30 para la
//  cena. A partir de $dia, crea con setTime los cuatro objetos (apertura y cierre de cada
//  turno) y escribe la función estaAbierto(DateTimeImmutable $momento): bool. Pruébala con los
//  momentos de $pruebas.
// =============================================================================================
titulo('Problema 2');
$pruebas = ['2026-10-16 12:59', '2026-10-16 13:00', '2026-10-16 17:00', '2026-10-16 22:15', '2026-10-16 23:30'];
// completa aquí tu solución

// =============================================================================================
// PROBLEMA 3. Próximas reservas
//  Con la lista $reservas (fechas en texto), muestra solo las que son posteriores a $ahora,
//  ordenadas de la más cercana a la más lejana, con el formato "D d/m H:i nombre".
// =============================================================================================
titulo('Problema 3');
$ahora = new DateTimeImmutable('2026-10-16 15:00');
$reservas = [
    ['nombre' => 'Ana', 'fecha' => '2026-10-17 14:00'],
    ['nombre' => 'Brais', 'fecha' => '2026-10-16 13:30'],
    ['nombre' => 'Carmen', 'fecha' => '2026-10-16 21:30'],
    ['nombre' => 'Diego', 'fecha' => '2026-10-20 21:00'],
];
// completa aquí tu solución

// =============================================================================================
// PROBLEMA 4. Fechas de facturación
//  Para la fecha de una factura ($factura), calcula con modify: el primer y el último día de
//  su mes, el último viernes del mes (día de pago) y el mismo día del mes siguiente. Comprueba
//  qué pasa si la factura es del 31 de enero.
// =============================================================================================
titulo('Problema 4');
foreach (['2026-10-16', '2026-01-31'] as $texto) {
    $factura = new DateTimeImmutable($texto);
    // completa aquí tu solución
}

// =============================================================================================
// PROBLEMA 5. Caducidad de una sesión
//  Una sesión caduca tras 20 minutos sin actividad. Guarda la marca de tiempo del último acceso
//  ($ultimoAcceso) y escribe la función haCaducado(int $ultimoAcceso, DateTimeImmutable
//  $ahora): bool. Muestra también a qué hora exacta caduca.
// =============================================================================================
titulo('Problema 5');
$ultimoAcceso = (new DateTimeImmutable('2026-10-16 21:30:15'))->getTimestamp();
// completa aquí tu solución

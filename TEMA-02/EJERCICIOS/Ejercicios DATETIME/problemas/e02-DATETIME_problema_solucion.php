<?php
/*
 * e02-DATETIME_problema_solucion.php
 *  (FICHERO CON LAS SOLUCIONES)
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
// ---- SOLUCIÓN ----
// Fallo: modify cambia el PROPIO objeto DateTime y lo devuelve: $aviso y $evento son el mismo
// objeto, así que el evento también se ha movido al día 8.
$evento = new DateTime('2026-11-11 18:00');
$aviso = (clone $evento)->modify('-3 days');
ver('con clone', $evento->format('d/m H:i') . ' / aviso ' . $aviso->format('d/m H:i'));   // "11/11 18:00 / aviso 08/11 18:00"
$evento = new DateTimeImmutable('2026-11-11 18:00');
$aviso = $evento->modify('-3 days');
ver('con Immutable', $evento->format('d/m H:i') . ' / aviso ' . $aviso->format('d/m H:i'));   // igual

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
// ---- SOLUCIÓN ----
function estaAbierto(DateTimeImmutable $momento): bool
{
    $dia = $momento->setTime(0, 0);
    $turnos = [
        [$dia->setTime(13, 0), $dia->setTime(16, 0)],
        [$dia->setTime(20, 30), $dia->setTime(23, 30)],
    ];
    foreach ($turnos as [$abre, $cierra]) {
        if ($momento >= $abre && $momento < $cierra) {    // a la hora de cierre ya está cerrado
            return true;
        }
    }
    return false;
}
foreach ($pruebas as $texto) {
    ver($texto, estaAbierto(new DateTimeImmutable($texto)));   // false, true, false, true, false
}

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
// ---- SOLUCIÓN ----
$proximas = [];
foreach ($reservas as $r) {
    $fecha = new DateTimeImmutable($r['fecha']);
    if ($fecha > $ahora) {
        $proximas[] = ['nombre' => $r['nombre'], 'fecha' => $fecha];
    }
}
usort($proximas, fn($a, $b) => $a['fecha'] <=> $b['fecha']);
ver('próximas', array_map(fn($r) => $r['fecha']->format('D d/m H:i ') . $r['nombre'], $proximas));
// ['Fri 16/10 21:30 Carmen', 'Sat 17/10 14:00 Ana', 'Tue 20/10 21:00 Diego']

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
    // ---- SOLUCIÓN ----
    ver("$texto: primer día", $factura->modify('first day of this month')->format('Y-m-d'));
    ver("$texto: último día", $factura->modify('last day of this month')->format('Y-m-d'));
    ver("$texto: día de pago", $factura->modify('last friday of this month')->format('Y-m-d'));
    ver("$texto: +1 month", $factura->modify('+1 month')->format('Y-m-d'));
    // 2026-10-16: 2026-10-01, 2026-10-31, 2026-10-30, 2026-11-16
    // 2026-01-31: 2026-01-01, 2026-01-31, 2026-01-30, 2026-03-03 (¡el 31/02 no existe y se desborda!)
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
// ---- SOLUCIÓN ----
function haCaducado(int $ultimoAcceso, DateTimeImmutable $ahora): bool
{
    $caduca = (new DateTimeImmutable())->setTimestamp($ultimoAcceso)->modify('+20 minutes');
    return $ahora >= $caduca;
}
ver('caduca a las', (new DateTimeImmutable())->setTimestamp($ultimoAcceso)->modify('+20 minutes')->format('H:i:s'));   // "21:50:15"
ver('a las 21:45', haCaducado($ultimoAcceso, new DateTimeImmutable('2026-10-16 21:45')));   // false
ver('a las 21:51', haCaducado($ultimoAcceso, new DateTimeImmutable('2026-10-16 21:51')));   // true

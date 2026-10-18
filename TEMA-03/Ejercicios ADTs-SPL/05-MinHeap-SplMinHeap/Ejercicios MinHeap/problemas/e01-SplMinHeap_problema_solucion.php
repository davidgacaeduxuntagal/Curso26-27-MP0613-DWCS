<?php
/*
 * e01-SplMinHeap_problema_solucion.php
 *  (FICHERO CON LAS SOLUCIONES)
 *  Problemas de: SplMinHeap (insert, extract, top, count, foreach, clone, arrays y fechas)
 *  (repasa los ejemplos de ../e01-SplMinHeap.php)
 *
 *  Resuelve cada problema donde se indica y ejecútalo en modo depuración para comprobarlo.
 */
require_once __DIR__ . '/../../../utilidades.php';

// =============================================================================================
// PROBLEMA 1. El producto más barato
//  Mete en un SplMinHeap los precios de $ofertas y muestra el más barato con top(). Después saca
//  los 3 más baratos (extract) y comprueba cuántos quedan.
// =============================================================================================
titulo('Problema 1');
$ofertas = [24.99, 9.95, 15.50, 7.99, 32.00, 12.49];
// completa aquí tu solución
// ---- SOLUCIÓN ----
$baratos = new SplMinHeap();
foreach ($ofertas as $precio) {
    $baratos->insert($precio);
}
ver('el más barato', $baratos->top());                                       // 7.99
ver('los 3 más baratos', [$baratos->extract(), $baratos->extract(), $baratos->extract()]);   // [7.99, 9.95, 12.49]
ver('quedan', count($baratos));                                              // 3

// =============================================================================================
// PROBLEMA 2. Las próximas citas del médico
//  Mete las citas de $citas (texto "Y-m-d H:i" => paciente) en un SplMinHeap como arrays
//  [DateTimeImmutable, paciente], y muestra las 2 próximas a partir de $ahora (descarta las que
//  ya han pasado) SIN vaciar el montículo original.
// =============================================================================================
titulo('Problema 2');
$citas = ['2026-10-16 12:30' => 'Ana', '2026-10-16 09:15' => 'Brais', '2026-10-17 10:00' => 'Carmen', '2026-10-16 18:00' => 'Diego'];
$ahora = new DateTimeImmutable('2026-10-16 11:00');
// completa aquí tu solución
// ---- SOLUCIÓN ----
$agenda = new SplMinHeap();
foreach ($citas as $cuando => $paciente) {
    $agenda->insert([new DateTimeImmutable($cuando), $paciente]);
}
$proximas = [];
foreach (clone $agenda as [$fecha, $paciente]) {      // sobre una copia
    if ($fecha < $ahora) {
        continue;                                        // ya pasó
    }
    $proximas[] = $fecha->format('d/m H:i') . " $paciente";
    if (count($proximas) === 2) {
        break;
    }
}
ver('próximas', $proximas);               // ['16/10 12:30 Ana', '16/10 18:00 Diego']
ver('agenda completa', count($agenda));   // 4

// =============================================================================================
// PROBLEMA 3. ¿Qué devuelve?
//  Sin ejecutarlo, escribe en un comentario qué mostrará cada ver() y por qué. Compruébalo.
// =============================================================================================
titulo('Problema 3');
$h = new SplMinHeap();
foreach (['pera', 'Manzana', 'kiwi', 'Uva'] as $fruta) {
    $h->insert($fruta);
}
// completa aquí tu solución
// ---- SOLUCIÓN ----
// top: "Manzana" — los textos se comparan byte a byte y las mayúsculas (M=77, U=85) van antes que
// las minúsculas (k=107, p=112). Orden de salida: Manzana, Uva, kiwi, pera.
ver('top', $h->top());                                   // "Manzana"
ver('orden', iterator_to_array(clone $h, false));        // ['Manzana', 'Uva', 'kiwi', 'pera']
// Para ignorar mayúsculas habría que insertar [mb_strtolower($fruta), $fruta].

// =============================================================================================
// PROBLEMA 4. Ordenar de mayor a menor con un SplMinHeap
//  ¿Cómo obtendrías las notas de $notas de MAYOR a MENOR usando solo un SplMinHeap (sin
//  SplMaxHeap ni rsort)? Escribe dos formas distintas.
// =============================================================================================
titulo('Problema 4');
$notas = [6.5, 9.0, 4.25, 7.75, 8.0];
// completa aquí tu solución
// ---- SOLUCIÓN ----
// Forma 1: insertar los valores con el signo cambiado y volver a cambiarlo al sacarlos
$h1 = new SplMinHeap();
foreach ($notas as $n) {
    $h1->insert(-$n);
}
ver('con -nota', array_map(fn($n) => -$n, iterator_to_array($h1, false)));   // [9.0, 8.0, 7.75, 6.5, 4.25]
// Forma 2: sacarlos en orden creciente y darles la vuelta
$h2 = new SplMinHeap();
foreach ($notas as $n) {
    $h2->insert($n);
}
ver('con array_reverse', array_reverse(iterator_to_array($h2, false)));      // [9.0, 8.0, 7.75, 6.5, 4.25]

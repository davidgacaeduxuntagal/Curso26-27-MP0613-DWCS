<?php
/*
 * e01-SplMinHeap_problema.php
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

// =============================================================================================
// PROBLEMA 4. Ordenar de mayor a menor con un SplMinHeap
//  ¿Cómo obtendrías las notas de $notas de MAYOR a MENOR usando solo un SplMinHeap (sin
//  SplMaxHeap ni rsort)? Escribe dos formas distintas.
// =============================================================================================
titulo('Problema 4');
$notas = [6.5, 9.0, 4.25, 7.75, 8.0];
// completa aquí tu solución

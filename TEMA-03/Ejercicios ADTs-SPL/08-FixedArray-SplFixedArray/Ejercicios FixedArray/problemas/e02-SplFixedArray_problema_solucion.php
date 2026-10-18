<?php
/*
 * e02-SplFixedArray_problema_solucion.php
 *  (FICHERO CON LAS SOLUCIONES)
 *  Problemas de: aplicaciones de SplFixedArray (matrices, tableros, contadores, buffers
 *                circulares)
 *  (repasa los ejemplos de ../e02-SplFixedArray.php)
 *
 *  Resuelve cada problema donde se indica y ejecútalo en modo depuración para comprobarlo.
 */
require_once __DIR__ . '/../../../utilidades.php';

// =============================================================================================
// PROBLEMA 1. Hundir la flota
//  Crea un tablero 5x5 (SplFixedArray de SplFixedArray) lleno de '~' (agua). Coloca un barco de
//  3 casillas en horizontal en la fila 2 (columnas 1 a 3) con 'B' y escribe disparar(int $f,
//  int $c): string que devuelva "agua", "tocado" o "repetido" y marque la casilla con 'o' (agua
//  disparada) o 'X' (tocado).
// =============================================================================================
titulo('Problema 1');
// completa aquí tu solución
// ---- SOLUCIÓN ----
$tablero = new SplFixedArray(5);
for ($f = 0; $f < 5; $f++) {
    $tablero[$f] = SplFixedArray::fromArray(array_fill(0, 5, '~'));
}
for ($c = 1; $c <= 3; $c++) {
    $tablero[2][$c] = 'B';
}
function disparar(SplFixedArray $tablero, int $f, int $c): string
{
    $casilla = $tablero[$f][$c];
    if ($casilla === 'B') {
        $tablero[$f][$c] = 'X';
        return 'tocado';
    }
    if ($casilla === '~') {
        $tablero[$f][$c] = 'o';
        return 'agua';
    }
    return 'repetido';
}
ver('disparos', [disparar($tablero, 0, 0), disparar($tablero, 2, 2), disparar($tablero, 2, 2)]);   // ['agua', 'tocado', 'repetido']
foreach ($tablero as $fila) {
    echo '  ', implode(' ', $fila->toArray()), nl();
}

// =============================================================================================
// PROBLEMA 2. ¿Cuántas veces sale cada número?
//  Lanza un dado 600 veces (mt_rand con la semilla 7) y cuenta cuántas veces sale cada cara en
//  un SplFixedArray de 6 casillas (cara 1 = casilla 0). Comprueba que la suma es 600.
// =============================================================================================
titulo('Problema 2');
mt_srand(7);
// completa aquí tu solución
// ---- SOLUCIÓN ----
$caras = SplFixedArray::fromArray(array_fill(0, 6, 0));
for ($i = 0; $i < 600; $i++) {
    $cara = mt_rand(1, 6);
    $caras[$cara - 1] = $caras[$cara - 1] + 1;
}
ver('veces', $caras);                         // unas 100 de cada
ver('suma', array_sum($caras->toArray()));    // 600

// =============================================================================================
// PROBLEMA 3. La media móvil de las temperaturas
//  Usa la idea del BufferCircular del ejemplo: escribe la función mediasMoviles(array $valores,
//  int $n): array que devuelva, para cada valor, la media de los últimos $n (o de los que haya
//  si aún no hay $n), usando un SplFixedArray de tamaño $n y una posición que da la vuelta.
// =============================================================================================
titulo('Problema 3');
$temperaturas = [12, 14, 16, 18, 15, 11];
// completa aquí tu solución
// ---- SOLUCIÓN ----
function mediasMoviles(array $valores, int $n): array
{
    $buffer = new SplFixedArray($n);
    $pos = 0;
    $guardados = 0;
    $medias = [];
    foreach ($valores as $v) {
        $buffer[$pos] = $v;
        $pos = ($pos + 1) % $n;
        $guardados = min($guardados + 1, $n);
        $medias[] = round(array_sum(array_slice($buffer->toArray(), 0, $guardados)) / $guardados, 2);
    }
    return $medias;
}
ver('medias de 3', mediasMoviles($temperaturas, 3));   // [12.0, 13.0, 14.0, 16.0, 16.33, 14.67]

// =============================================================================================
// PROBLEMA 4. ¿Cómo harías...?
//  ¿SplFixedArray o un array normal? Para cada caso, contesta en un comentario.
//   a) Las 64 casillas de un tablero de ajedrez.
//   b) Los productos del carrito, que se añaden y se quitan.
//   c) Un millón de lecturas de un sensor, que se cargan una vez y se analizan.
//   d) Los datos de un cliente (nombre, email, teléfono).
// =============================================================================================
titulo('Problema 4');
// completa aquí tu solución
// ---- SOLUCIÓN ----
// a) SplFixedArray (o de 8 SplFixedArray de 8): el tamaño no cambia nunca e índices enteros.
// b) Array normal (o SplDoublyLinkedList): el tamaño cambia continuamente.
// c) SplFixedArray: tamaño conocido, índices 0..n-1 y ahorra mucha memoria.
// d) Array asociativo (o mejor, un objeto Cliente): las claves son textos, no posiciones.
ver('respuesta', 'a) fijo, b) array, c) fijo, d) asociativo u objeto');

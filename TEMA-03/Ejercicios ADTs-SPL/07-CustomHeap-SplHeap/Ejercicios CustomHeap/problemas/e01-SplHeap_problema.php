<?php
/*
 * e01-SplHeap_problema.php
 *  Problemas de: heredar de SplHeap / SplMinHeap / SplMaxHeap y redefinir compare(),
 *                montículos de objetos, comparador como parámetro, montículos corruptos
 *  (repasa los ejemplos de ../e01-SplHeap.php)
 *
 *  Resuelve cada problema donde se indica y ejecútalo en modo depuración para comprobarlo.
 */
require_once __DIR__ . '/../../../utilidades.php';

final class Alumno
{
    public function __construct(public readonly string $nombre, public readonly float $nota, public readonly int $faltas)
    {
    }
}
$clase = [
    new Alumno('Ana', 8.5, 2),
    new Alumno('Brais', 9.25, 6),
    new Alumno('Carmen', 8.5, 0),
    new Alumno('Diego', 6.0, 1),
];

// =============================================================================================
// PROBLEMA 1. Matrícula de honor
//  Crea la clase PorMerito (hereda de SplHeap) que saque primero la nota MAYOR y, a igual nota,
//  el alumno con MENOS faltas. Mete $clase y muestra el orden.
// =============================================================================================
titulo('Problema 1');
// completa aquí tu solución

// =============================================================================================
// PROBLEMA 2. Los que más faltan
//  Sin escribir otra subclase, usa la clase HeapConComparador (copiada del ejemplo) para obtener
//  el alumno con MÁS faltas de $clase y, en otro montículo, el de MENOR nota.
// =============================================================================================
titulo('Problema 2');
final class HeapConComparador extends SplHeap
{
    public function __construct(private Closure $comparador)
    {
    }

    protected function compare(mixed $a, mixed $b): int
    {
        return ($this->comparador)($a, $b);
    }
}
// completa aquí tu solución

// =============================================================================================
// PROBLEMA 3. Corregir el sentido
//  Esta clase quería sacar primero la palabra MÁS CORTA, pero saca la más larga. Explica el fallo
//  y corrígelo en la clase PalabraCorta.
// =============================================================================================
titulo('Problema 3');
final class PalabraCortaMal extends SplHeap
{
    protected function compare(mixed $a, mixed $b): int
    {
        return mb_strlen($a) <=> mb_strlen($b);
    }
}
$mal = new PalabraCortaMal();
foreach (['pulpo', 'pan', 'empanada', 'té'] as $p) {
    $mal->insert($p);
}
ver('sale primero (mal)', $mal->top());    // "empanada"
// completa aquí tu solución

// =============================================================================================
// PROBLEMA 4. ¿Cómo harías...?
//  En el ejemplo, el montículo PorFecha quedaba corrupto al insertar un pedido sin fecha. ¿Cómo
//  harías para que eso NO pueda pasar? Escribe la clase PorFechaSegura y demuéstralo.
// =============================================================================================
titulo('Problema 4');
// completa aquí tu solución

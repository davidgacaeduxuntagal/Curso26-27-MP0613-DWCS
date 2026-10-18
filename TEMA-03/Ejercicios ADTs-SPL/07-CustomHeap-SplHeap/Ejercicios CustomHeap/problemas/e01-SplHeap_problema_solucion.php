<?php
/*
 * e01-SplHeap_problema_solucion.php
 *  (FICHERO CON LAS SOLUCIONES)
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
// ---- SOLUCIÓN ----
final class PorMerito extends SplHeap
{
    protected function compare(mixed $a, mixed $b): int
    {
        return [$a->nota, $b->faltas] <=> [$b->nota, $a->faltas];
    }
}
$merito = new PorMerito();
foreach ($clase as $alumno) {
    $merito->insert($alumno);
}
ver('por mérito', array_map(fn($a) => "$a->nombre ($a->nota, $a->faltas faltas)", iterator_to_array($merito, false)));
// ['Brais (9.25, 6 faltas)', 'Carmen (8.5, 0 faltas)', 'Ana (8.5, 2 faltas)', 'Diego (6, 1 faltas)']

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
// ---- SOLUCIÓN ----
$masFaltas = new HeapConComparador(fn($a, $b) => $a->faltas <=> $b->faltas);
$peorNota = new HeapConComparador(fn($a, $b) => $b->nota <=> $a->nota);
foreach ($clase as $alumno) {
    $masFaltas->insert($alumno);
    $peorNota->insert($alumno);
}
ver('más faltas', $masFaltas->top()->nombre);     // "Brais"
ver('peor nota', $peorNota->top()->nombre);       // "Diego"

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
// ---- SOLUCIÓN ----
// Fallo: en SplHeap sale primero el elemento para el que compare($a, $b) es POSITIVO cuando $a va
// antes. Con mb_strlen($a) <=> mb_strlen($b), el positivo es para el MÁS LARGO. Hay que invertirlo.
final class PalabraCorta extends SplHeap
{
    protected function compare(mixed $a, mixed $b): int
    {
        return mb_strlen($b) <=> mb_strlen($a);
    }
}
$bien = new PalabraCorta();
foreach (['pulpo', 'pan', 'empanada', 'té'] as $p) {
    $bien->insert($p);
}
ver('sale primero (bien)', $bien->top());  // "té"

// =============================================================================================
// PROBLEMA 4. ¿Cómo harías...?
//  En el ejemplo, el montículo PorFecha quedaba corrupto al insertar un pedido sin fecha. ¿Cómo
//  harías para que eso NO pueda pasar? Escribe la clase PorFechaSegura y demuéstralo.
// =============================================================================================
titulo('Problema 4');
// completa aquí tu solución
// ---- SOLUCIÓN ----
final class PorFechaSegura extends SplHeap
{
    public function insert(mixed $pedido): true
    {
        if (!is_array($pedido) || !isset($pedido['fecha']) || !preg_match('/^\d{4}-\d{2}-\d{2}$/', $pedido['fecha'])) {
            throw new InvalidArgumentException('Pedido sin fecha válida');   // ANTES de llegar a compare()
        }
        return parent::insert($pedido);
    }

    protected function compare(mixed $a, mixed $b): int
    {
        return strcmp($b['fecha'], $a['fecha']);
    }
}
$segura = new PorFechaSegura();
$segura->insert(['n' => 1, 'fecha' => '2026-10-20']);
verError('sin fecha', fn() => $segura->insert(['n' => 2]));   // InvalidArgumentException: Pedido sin fecha válida
ver('¿corrupto?', $segura->isCorrupted());                    // false
$segura->insert(['n' => 3, 'fecha' => '2026-10-18']);
ver('siguiente', $segura->top()['n']);                        // 3

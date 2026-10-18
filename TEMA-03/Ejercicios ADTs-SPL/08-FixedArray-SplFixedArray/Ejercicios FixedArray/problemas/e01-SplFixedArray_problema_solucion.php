<?php
/*
 * e01-SplFixedArray_problema_solucion.php
 *  (FICHERO CON LAS SOLUCIONES)
 *  Problemas de: SplFixedArray (crear, leer y escribir, getSize, setSize, toArray, fromArray,
 *                foreach, errores)
 *  (repasa los ejemplos de ../e01-SplFixedArray.php)
 *
 *  Resuelve cada problema donde se indica y ejecútalo en modo depuración para comprobarlo.
 */
require_once __DIR__ . '/../../../utilidades.php';

// =============================================================================================
// PROBLEMA 1. Ventas por mes
//  Crea un SplFixedArray de 12 casillas con las ventas de cada mes (0 = enero), inicializadas a
//  0. Registra las ventas de $operaciones (mes 1..12 => importe; puede haber varias por mes) y
//  muestra el mes con más ventas.
// =============================================================================================
titulo('Problema 1');
$operaciones = [[1, 120.0], [3, 80.5], [1, 45.0], [12, 300.0], [3, 99.5], [7, 60.0]];
$meses = ['ene', 'feb', 'mar', 'abr', 'may', 'jun', 'jul', 'ago', 'sep', 'oct', 'nov', 'dic'];
// completa aquí tu solución
// ---- SOLUCIÓN ----
$ventas = SplFixedArray::fromArray(array_fill(0, 12, 0.0));
foreach ($operaciones as [$mes, $importe]) {
    $ventas[$mes - 1] = $ventas[$mes - 1] + $importe;     // (con += no funcionaría)
}
$maximo = array_keys($ventas->toArray(), max($ventas->toArray()))[0];
ver('ventas', $ventas);
ver('mejor mes', $meses[$maximo]);       // "dic"

// =============================================================================================
// PROBLEMA 2. Invertir sin otro array
//  Escribe invertir(SplFixedArray $a): void que dé la vuelta al contenido intercambiando la
//  casilla i con la casilla tamaño-1-i (sin toArray ni array_reverse).
// =============================================================================================
titulo('Problema 2');
$dorsales = SplFixedArray::fromArray([7, 12, 3, 25, 18]);
// completa aquí tu solución
// ---- SOLUCIÓN ----
function invertir(SplFixedArray $a): void
{
    $n = $a->getSize();
    for ($i = 0; $i < intdiv($n, 2); $i++) {
        $tmp = $a[$i];
        $a[$i] = $a[$n - 1 - $i];
        $a[$n - 1 - $i] = $tmp;
    }
}
invertir($dorsales);
ver('invertido', $dorsales);   // [0 => 18, 1 => 25, 2 => 3, 3 => 12, 4 => 7]

// =============================================================================================
// PROBLEMA 3. Leer con seguridad
//  Escribe leer(SplFixedArray $a, int $i, mixed $porDefecto = null): mixed que devuelva el valor
//  de la casilla $i o $porDefecto si el índice está fuera de rango, SIN usar try/catch (usa
//  getSize o isset).
// =============================================================================================
titulo('Problema 3');
$plazas = SplFixedArray::fromArray(['Ana', null, 'Carmen']);
// completa aquí tu solución
// ---- SOLUCIÓN ----
function leer(SplFixedArray $a, int $i, mixed $porDefecto = null): mixed
{
    return ($i >= 0 && $i < $a->getSize()) ? ($a[$i] ?? $porDefecto) : $porDefecto;
}
ver('plaza 0', leer($plazas, 0, 'libre'));    // "Ana"
ver('plaza 1', leer($plazas, 1, 'libre'));    // "libre" (vale null)
ver('plaza 9', leer($plazas, 9, 'libre'));    // "libre" (no existe)
ver('isset($plazas[9])', isset($plazas[9]));  // false: isset tampoco da error

// =============================================================================================
// PROBLEMA 4. ¿Cómo harías...?
//  Un aula tiene 30 ordenadores numerados del 1 al 30. ¿Cómo guardarías en un SplFixedArray qué
//  alumno usa cada uno, de forma que "ordenador 1" sea la casilla 0? Escribe asignar() y
//  quienUsa() y comprueba que asignar el ordenador 31 da un error claro.
// =============================================================================================
titulo('Problema 4');
// completa aquí tu solución
// ---- SOLUCIÓN ----
final class Aula
{
    private SplFixedArray $puestos;

    public function __construct(int $ordenadores)
    {
        $this->puestos = new SplFixedArray($ordenadores);
    }

    public function asignar(int $ordenador, string $alumno): void
    {
        if ($ordenador < 1 || $ordenador > $this->puestos->getSize()) {
            throw new OutOfRangeException("No existe el ordenador $ordenador");
        }
        $this->puestos[$ordenador - 1] = $alumno;
    }

    public function quienUsa(int $ordenador): ?string
    {
        return $this->puestos[$ordenador - 1];
    }
}
$aula = new Aula(30);
$aula->asignar(1, 'Ana');
$aula->asignar(30, 'Xiana');
ver('ordenador 30', $aula->quienUsa(30));               // "Xiana"
verError('ordenador 31', fn() => $aula->asignar(31, 'Brais'));   // OutOfRangeException: No existe el ordenador 31

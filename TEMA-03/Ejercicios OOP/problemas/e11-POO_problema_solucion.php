<?php
/*
 * e11-POO_problema_solucion.php
 *  (FICHERO CON LAS SOLUCIONES)
 *  Problemas de: Iterator, IteratorAggregate, ArrayAccess, Countable, iteradores de la SPL,
 *                SplStack / SplQueue / SplObjectStorage, generadores (yield, yield from)
 *  (repasa los ejemplos de ../Ejercicios OOP/e11-POO.php)
 *
 *  Resuelve cada problema donde se indica y ejecútalo en modo depuración para comprobarlo.
 */
require_once __DIR__ . '/../Ejercicios OOP/utilidades.php';

// =============================================================================================
// PROBLEMA 1. Un rango de fechas recorrible
//  Escribe la clase RangoFechas (desde y hasta, DateTimeImmutable) que implemente
//  IteratorAggregate: su getIterator() debe ser un GENERADOR (con yield) que entregue cada día
//  del rango (incluidos los dos extremos) con la clave "Y-m-d". Recorre la semana del 12 al 18
//  de octubre de 2026 y muestra los días que no son fin de semana.
// =============================================================================================
titulo('Problema 1');
// completa aquí tu solución
// ---- SOLUCIÓN ----
class RangoFechas implements IteratorAggregate
{
    public function __construct(private DateTimeImmutable $desde, private DateTimeImmutable $hasta)
    {
    }

    public function getIterator(): Generator
    {
        for ($dia = $this->desde; $dia <= $this->hasta; $dia = $dia->modify('+1 day')) {
            yield $dia->format('Y-m-d') => $dia;
        }
    }
}
$laborables = [];
foreach (new RangoFechas(new DateTimeImmutable('2026-10-12'), new DateTimeImmutable('2026-10-18')) as $clave => $dia) {
    if ($dia->format('N') < 6) {
        $laborables[] = $clave;
    }
}
ver('laborables', $laborables);     // ['2026-10-12', ..., '2026-10-16'] (5 días)

// =============================================================================================
// PROBLEMA 2. Un inventario que se usa como un array
//  Escribe la clase Inventario que implemente ArrayAccess y Countable: $inv['RAT-01'] = 5 fija
//  el stock (entero >= 0; si no, InvalidArgumentException), $inv['RAT-01'] lo lee (0 si no
//  existe), isset y unset funcionan, y count() devuelve el total de UNIDADES (no de referencias).
// =============================================================================================
titulo('Problema 2');
// completa aquí tu solución
// ---- SOLUCIÓN ----
class Inventario implements ArrayAccess, Countable
{
    private array $stock = [];

    public function offsetExists(mixed $ref): bool
    {
        return isset($this->stock[$ref]);
    }

    public function offsetGet(mixed $ref): int
    {
        return $this->stock[$ref] ?? 0;
    }

    public function offsetSet(mixed $ref, mixed $unidades): void
    {
        if (!is_string($ref) || !is_int($unidades) || $unidades < 0) {
            throw new InvalidArgumentException('Referencia de texto y unidades enteras no negativas');
        }
        $this->stock[$ref] = $unidades;
    }

    public function offsetUnset(mixed $ref): void
    {
        unset($this->stock[$ref]);
    }

    public function count(): int
    {
        return array_sum($this->stock);
    }
}
$inv = new Inventario();
$inv['RAT-01'] = 5;
$inv['TEC-02'] = 3;
$inv['RAT-01'] += 2;                         // offsetGet + offsetSet
ver('RAT-01', $inv['RAT-01']);               // 7
ver('total de unidades', count($inv));       // 10
ver('¿existe MON-03?', isset($inv['MON-03']));   // false
verError('stock negativo', function () use ($inv) {
    $inv['TEC-02'] = -1;
});   // InvalidArgumentException

// =============================================================================================
// PROBLEMA 3. Leer un log enorme con un generador
//  Escribe el generador lineasConError(iterable $lineas): Generator que entregue solo las líneas
//  que contienen "ERROR", con su número de línea (empezando en 1) como clave. Pruébalo con el
//  array $log (en la vida real sería un SplFileObject o un fgets de un fichero de varios GB).
// =============================================================================================
titulo('Problema 3');
$log = ['10:00 INFO arranque', '10:01 ERROR sin conexión a la BD', '10:02 INFO reintento', '10:03 ERROR tiempo agotado', '10:04 INFO ok'];
// completa aquí tu solución
// ---- SOLUCIÓN ----
function lineasConError(iterable $lineas): Generator
{
    $numero = 0;
    foreach ($lineas as $linea) {
        $numero++;
        if (str_contains($linea, 'ERROR')) {
            yield $numero => $linea;
        }
    }
}
ver('errores', iterator_to_array(lineasConError($log)));
// [2 => '10:01 ERROR sin conexión a la BD', 4 => '10:03 ERROR tiempo agotado']

// =============================================================================================
// PROBLEMA 4. Comprobar los paréntesis con una pila
//  Escribe equilibrado(string $expresion): bool que use SplStack para comprobar que los
//  paréntesis, corchetes y llaves están bien emparejados.
// =============================================================================================
titulo('Problema 4');
$pruebas = ['(a + b) * [c - {d / e}]', '(a + b]', '((a)', 'f(x) = {[ ]}'];
// completa aquí tu solución
// ---- SOLUCIÓN ----
function equilibrado(string $expresion): bool
{
    $pares = [')' => '(', ']' => '[', '}' => '{'];
    $pila = new SplStack();
    foreach (str_split($expresion) as $caracter) {
        if (in_array($caracter, $pares, true)) {
            $pila->push($caracter);
        } elseif (isset($pares[$caracter])) {
            if ($pila->isEmpty() || $pila->pop() !== $pares[$caracter]) {
                return false;
            }
        }
    }
    return $pila->isEmpty();
}
foreach ($pruebas as $p) {
    ver($p, equilibrado($p));      // true, false, false, true
}

// =============================================================================================
// PROBLEMA 5. Paginar con iteradores de la SPL
//  ¿Cómo mostrarías la página 2 (de 3 elementos por página) de los productos con precio menor
//  de 50 €, SIN crear arrays intermedios? Usa CallbackFilterIterator y LimitIterator.
// =============================================================================================
titulo('Problema 5');
$productos = new ArrayIterator([
    'Ratón' => 19.95, 'Monitor' => 179.0, 'Teclado' => 49.5, 'Cable' => 8.99, 'Webcam' => 39.9,
    'Altavoces' => 65.0, 'Funda' => 12.0, 'Hub USB' => 24.5, 'Cascos' => 59.0, 'Alfombrilla' => 7.5,
]);
// completa aquí tu solución
// ---- SOLUCIÓN ----
$porPagina = 3;
$pagina = 2;
$baratos = new CallbackFilterIterator($productos, fn(float $precio) => $precio < 50);
$paginaDos = new LimitIterator($baratos, ($pagina - 1) * $porPagina, $porPagina);
ver('página 2', iterator_to_array($paginaDos));    // ['Webcam' => 39.9, 'Funda' => 12.0, 'Hub USB' => 24.5]
// (los baratos son Ratón, Teclado, Cable | Webcam, Funda, Hub USB | Alfombrilla)

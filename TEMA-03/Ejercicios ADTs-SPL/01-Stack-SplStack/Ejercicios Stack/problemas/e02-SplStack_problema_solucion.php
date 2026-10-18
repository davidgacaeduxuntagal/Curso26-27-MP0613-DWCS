<?php
/*
 * e02-SplStack_problema_solucion.php
 *  (FICHERO CON LAS SOLUCIONES)
 *  Problemas de: aplicaciones de las pilas (deshacer, símbolos equilibrados, expresiones,
 *                recorridos en profundidad)
 *  (repasa los ejemplos de ../e02-SplStack.php)
 *
 *  Resuelve cada problema donde se indica y ejecútalo en modo depuración para comprobarlo.
 */
require_once __DIR__ . '/../../../utilidades.php';

// =============================================================================================
// PROBLEMA 1. Paréntesis, corchetes y llaves
//  Escribe equilibrado(string $codigo): bool que compruebe con una SplStack que (), [] y {}
//  están bien emparejados en un trozo de código.
// =============================================================================================
titulo('Problema 1');
$pruebas = ['if ($a[0] > 1) { echo "ok"; }', 'f(x[1)]', '{ [ ( ) ] ', 'array_map(fn($x) => [$x], $y);'];
// completa aquí tu solución
// ---- SOLUCIÓN ----
function equilibrado(string $codigo): bool
{
    $pares = [')' => '(', ']' => '[', '}' => '{'];
    $pila = new SplStack();
    foreach (str_split($codigo) as $c) {
        if (in_array($c, $pares, true)) {
            $pila->push($c);
        } elseif (isset($pares[$c]) && ($pila->isEmpty() || $pila->pop() !== $pares[$c])) {
            return false;
        }
    }
    return $pila->isEmpty();
}
foreach ($pruebas as $p) {
    ver($p, equilibrado($p));   // true, false, false, true
}

// =============================================================================================
// PROBLEMA 2. El botón "Atrás" del navegador
//  Escribe la clase Navegador con visitar(string $url), atras(): ?string y actual(): ?string.
//  Usa una SplStack para las páginas anteriores. Si no hay página anterior, atras() devuelve
//  null y no cambia la actual. Simula la navegación de abajo.
// =============================================================================================
titulo('Problema 2');
// completa aquí tu solución
// ---- SOLUCIÓN ----
final class Navegador
{
    private SplStack $anteriores;
    private ?string $actual = null;

    public function __construct()
    {
        $this->anteriores = new SplStack();
    }

    public function visitar(string $url): void
    {
        if ($this->actual !== null) {
            $this->anteriores->push($this->actual);
        }
        $this->actual = $url;
    }

    public function atras(): ?string
    {
        if ($this->anteriores->isEmpty()) {
            return null;
        }
        $this->actual = $this->anteriores->pop();
        return $this->actual;
    }

    public function actual(): ?string
    {
        return $this->actual;
    }
}
$nav = new Navegador();
$nav->visitar('/');
$nav->visitar('/productos');
$nav->visitar('/productos/27');
ver('atrás', $nav->atras());           // "/productos"
$nav->visitar('/carrito');
ver('atrás', $nav->atras());           // "/productos"
ver('atrás', $nav->atras());           // "/"
ver('atrás', $nav->atras());           // NULL
ver('actual', $nav->actual());         // "/"

// =============================================================================================
// PROBLEMA 3. Calculadora con variables
//  Amplía la idea de evaluarRpn() del ejemplo: escribe evaluar(string $rpn, array $variables)
//  que admita también nombres de variables (por ejemplo "precio unidades * 1.21 *").
// =============================================================================================
titulo('Problema 3');
// completa aquí tu solución
// ---- SOLUCIÓN ----
function evaluar(string $rpn, array $variables = []): float
{
    $pila = new SplStack();
    foreach (preg_split('/\s+/', trim($rpn)) as $t) {
        if (is_numeric($t)) {
            $pila->push((float) $t);
        } elseif (array_key_exists($t, $variables)) {
            $pila->push((float) $variables[$t]);
        } else {
            $b = $pila->pop();
            $a = $pila->pop();
            $pila->push(match ($t) {
                '+' => $a + $b, '-' => $a - $b, '*' => $a * $b, '/' => $a / $b,
                default => throw new InvalidArgumentException("Desconocido: $t"),
            });
        }
    }
    return round($pila->pop(), 2);
}
ver('total con IVA', evaluar('precio unidades * 1.21 *', ['precio' => 19.95, 'unidades' => 3]));   // 72.42
ver('media', evaluar('a b + c + 3 /', ['a' => 7, 'b' => 8.5, 'c' => 6]));                          // 7.17

// =============================================================================================
// PROBLEMA 4. ¿Cómo harías...?
//  Un laberinto es una cuadrícula ('#' = muro, '.' = pasillo, 'S' = salida). ¿Cómo comprobarías,
//  con una pila (búsqueda en profundidad), si desde la casilla (1, 1) se llega a la salida?
//  Escribe hayCamino(array $mapa, int $fila, int $columna): bool.
// =============================================================================================
titulo('Problema 4');
$laberinto = [
    '#######',
    '#..#..#',
    '#.##.##',
    '#....S#',
    '#######',
];
$cerrado = ['#####', '#..##', '###S#', '#####'];
// completa aquí tu solución
// ---- SOLUCIÓN ----
function hayCamino(array $mapa, int $fila, int $columna): bool
{
    $pendientes = new SplStack();
    $pendientes->push([$fila, $columna]);
    $visitadas = [];
    while (!$pendientes->isEmpty()) {
        [$f, $c] = $pendientes->pop();
        if (isset($visitadas["$f,$c"]) || ($mapa[$f][$c] ?? '#') === '#') {
            continue;
        }
        if ($mapa[$f][$c] === 'S') {
            return true;
        }
        $visitadas["$f,$c"] = true;
        foreach ([[1, 0], [-1, 0], [0, 1], [0, -1]] as [$df, $dc]) {   // abajo, arriba, derecha, izquierda
            $pendientes->push([$f + $df, $c + $dc]);
        }
    }
    return false;
}
ver('laberinto', hayCamino($laberinto, 1, 1));   // true
ver('cerrado', hayCamino($cerrado, 1, 1));       // false

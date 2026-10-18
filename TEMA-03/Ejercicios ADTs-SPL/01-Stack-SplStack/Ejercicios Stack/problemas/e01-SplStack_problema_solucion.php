<?php
/*
 * e01-SplStack_problema_solucion.php
 *  (FICHERO CON LAS SOLUCIONES)
 *  Problemas de: SplStack (push, pop, top, isEmpty, count, foreach, IT_MODE_DELETE)
 *  (repasa los ejemplos de ../e01-SplStack.php)
 *
 *  Resuelve cada problema donde se indica y ejecútalo en modo depuración para comprobarlo.
 */
require_once __DIR__ . '/../../../utilidades.php';

// =============================================================================================
// PROBLEMA 1. Invertir una palabra
//  Escribe invertir(string $texto): string que meta cada carácter en una SplStack y luego los
//  saque para construir el texto al revés (usa mb_str_split para que funcione con tildes y ñ).
// =============================================================================================
titulo('Problema 1');
// completa aquí tu solución
// ---- SOLUCIÓN ----
function invertir(string $texto): string
{
    $pila = new SplStack();
    foreach (mb_str_split($texto) as $caracter) {
        $pila->push($caracter);
    }
    $resultado = '';
    while (!$pila->isEmpty()) {
        $resultado .= $pila->pop();
    }
    return $resultado;
}
ver('Baiona', invertir('Baiona'));          // "anoiaB"
ver('Coruña', invertir('Coruña'));          // "añuroC"

// =============================================================================================
// PROBLEMA 2. ¿Palíndromo?
//  Usando invertir() del problema 1 (o una pila), escribe esPalindromo(string $frase): bool que
//  no tenga en cuenta mayúsculas, espacios ni signos de puntuación.
// =============================================================================================
titulo('Problema 2');
$frases = ['Anita lava la tina', 'Dábale arroz a la zorra el abad', 'Bar Baio'];
// completa aquí tu solución
// ---- SOLUCIÓN ----
function esPalindromo(string $frase): bool
{
    $limpia = strtr(mb_strtolower($frase), ['á' => 'a', 'é' => 'e', 'í' => 'i', 'ó' => 'o', 'ú' => 'u']);
    $limpia = preg_replace('/[^a-zñ0-9]/u', '', $limpia);
    return $limpia === invertir($limpia);
}
foreach ($frases as $f) {
    ver($f, esPalindromo($f));              // true, true, false
}

// =============================================================================================
// PROBLEMA 3. De decimal a binario
//  Escribe aBinario(int $n): string que convierta un número a binario con una pila: se apilan
//  los restos de dividir entre 2 y después se sacan. Compara con decbin().
// =============================================================================================
titulo('Problema 3');
// completa aquí tu solución
// ---- SOLUCIÓN ----
function aBinario(int $n): string
{
    if ($n === 0) {
        return '0';
    }
    $restos = new SplStack();
    while ($n > 0) {
        $restos->push($n % 2);
        $n = intdiv($n, 2);
    }
    return implode('', iterator_to_array($restos, false));   // foreach de una pila: de la cima al fondo
}
foreach ([0, 5, 13, 255] as $n) {
    ver("$n", [aBinario($n), decbin($n)]);  // ['0','0'], ['101','101'], ['1101','1101'], ['11111111','11111111']
}

// =============================================================================================
// PROBLEMA 4. La pila de platos con límite
//  Escribe la clase PilaLimitada que envuelva una SplStack y tenga un tamaño máximo: apilar()
//  lanza OverflowException si está llena y desapilar() lanza UnderflowException si está vacía.
//  Añade cima(), estaLlena() y cuantos(). Pruébala con un máximo de 3 platos.
// =============================================================================================
titulo('Problema 4');
// completa aquí tu solución
// ---- SOLUCIÓN ----
final class PilaLimitada
{
    private SplStack $pila;

    public function __construct(private int $maximo)
    {
        $this->pila = new SplStack();
    }

    public function apilar(mixed $elemento): void
    {
        if ($this->estaLlena()) {
            throw new OverflowException("La pila está llena ($this->maximo)");
        }
        $this->pila->push($elemento);
    }

    public function desapilar(): mixed
    {
        if ($this->pila->isEmpty()) {
            throw new UnderflowException('La pila está vacía');
        }
        return $this->pila->pop();
    }

    public function cima(): mixed
    {
        return $this->pila->isEmpty() ? null : $this->pila->top();
    }

    public function estaLlena(): bool
    {
        return count($this->pila) >= $this->maximo;
    }

    public function cuantos(): int
    {
        return count($this->pila);
    }
}
$platos = new PilaLimitada(3);
$platos->apilar('plato azul');
$platos->apilar('plato rojo');
$platos->apilar('plato verde');
verError('cuarto plato', fn() => $platos->apilar('plato blanco'));   // OverflowException: La pila está llena (3)
ver('cima', $platos->cima());              // "plato verde"
$platos->desapilar();
$platos->desapilar();
$platos->desapilar();
verError('desapilar vacía', fn() => $platos->desapilar());          // UnderflowException: La pila está vacía

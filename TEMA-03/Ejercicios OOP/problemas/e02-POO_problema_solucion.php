<?php
/*
 * e02-POO_problema_solucion.php
 *  (FICHERO CON LAS SOLUCIONES)
 *  Problemas de: asignación de objetos, paso a funciones, referencias, clone, __clone,
 *                comparación == y ===
 *  (repasa los ejemplos de ../Ejercicios OOP/e02-POO.php)
 *
 *  Resuelve cada problema donde se indica y ejecútalo en modo depuración para comprobarlo.
 */
require_once __DIR__ . '/../Ejercicios OOP/utilidades.php';

class Punto
{
    public function __construct(public float $x, public float $y)
    {
    }
}

// =============================================================================================
// PROBLEMA 1. ¿Qué se muestra?
//  Sin ejecutarlo, escribe en un comentario qué valor tendrán $a->x, $b->x y $c->x al final, y
//  explica por qué. Después ejecútalo y comprueba tu respuesta.
// =============================================================================================
titulo('Problema 1');
$a = new Punto(1, 1);
$b = $a;
$c = clone $a;
$b->x = 5;
$c->x = 9;
// completa aquí tu solución
// ---- SOLUCIÓN ----
// $a->x = 5 y $b->x = 5: $b = $a no copia el objeto, las dos variables apuntan al mismo.
// $c->x = 9: clone crea un objeto nuevo e independiente.
ver('x de a, b y c', [$a->x, $b->x, $c->x]);     // [5.0, 5.0, 9.0]

// =============================================================================================
// PROBLEMA 2. Mover sin modificar
//  La función moverMal() modifica el punto original. Escribe mover(Punto $p, float $dx, float
//  $dy): Punto que devuelva un punto NUEVO desplazado y deje el original intacto.
// =============================================================================================
titulo('Problema 2');
function moverMal(Punto $p, float $dx, float $dy): Punto
{
    $p->x += $dx;
    $p->y += $dy;
    return $p;
}
$origen = new Punto(0, 0);
// completa aquí tu solución
// ---- SOLUCIÓN ----
function mover(Punto $p, float $dx, float $dy): Punto
{
    return new Punto($p->x + $dx, $p->y + $dy);      // o: $nuevo = clone $p; y modificar $nuevo
}
$destino = mover($origen, 3, 4);
ver('origen', $origen);          // x 0.0, y 0.0
ver('destino', $destino);        // x 3.0, y 4.0

// =============================================================================================
// PROBLEMA 3. Copia profunda
//  Una Ruta tiene un nombre y un array de objetos Punto. Escribe la clase con un __clone que
//  copie también los puntos, de forma que al mover un punto de la copia no cambie la ruta
//  original. Compruébalo.
// =============================================================================================
titulo('Problema 3');
// completa aquí tu solución
// ---- SOLUCIÓN ----
class Ruta
{
    public function __construct(public string $nombre, public array $puntos)
    {
    }

    public function __clone(): void
    {
        $this->puntos = array_map(fn(Punto $p) => clone $p, $this->puntos);
    }
}
$camino = new Ruta('Camino Inglés', [new Punto(43.37, -8.40), new Punto(43.10, -8.45)]);
$variante = clone $camino;
$variante->nombre = 'Variante';
$variante->puntos[0]->x = 0;
ver('primer punto del original', $camino->puntos[0]->x);    // 43.37
ver('¿comparten el punto?', $camino->puntos[0] === $variante->puntos[0]);   // false

// =============================================================================================
// PROBLEMA 4. Comparar
//  Escribe la función mismaPosicion(Punto $a, Punto $b): bool que diga si dos puntos están en
//  el mismo sitio, y mismoObjeto(Punto $a, Punto $b): bool que diga si son el mismo objeto.
//  Pruébalas con los casos de abajo.
// =============================================================================================
titulo('Problema 4');
$p1 = new Punto(2, 3);
$p2 = new Punto(2, 3);
$p3 = $p1;
// completa aquí tu solución
// ---- SOLUCIÓN ----
function mismaPosicion(Punto $a, Punto $b): bool
{
    return $a == $b;
}
function mismoObjeto(Punto $a, Punto $b): bool
{
    return $a === $b;
}
ver('p1 y p2', [mismaPosicion($p1, $p2), mismoObjeto($p1, $p2)]);   // [true, false]
ver('p1 y p3', [mismaPosicion($p1, $p3), mismoObjeto($p1, $p3)]);   // [true, true]

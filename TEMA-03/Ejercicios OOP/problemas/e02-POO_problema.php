<?php
/*
 * e02-POO_problema.php
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

// =============================================================================================
// PROBLEMA 3. Copia profunda
//  Una Ruta tiene un nombre y un array de objetos Punto. Escribe la clase con un __clone que
//  copie también los puntos, de forma que al mover un punto de la copia no cambie la ruta
//  original. Compruébalo.
// =============================================================================================
titulo('Problema 3');
// completa aquí tu solución

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

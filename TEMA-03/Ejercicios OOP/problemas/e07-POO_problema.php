<?php
/*
 * e07-POO_problema.php
 *  Problemas de: __toString, __get / __set / __isset, __call, __invoke, __destruct,
 *                __serialize / __unserialize
 *  (repasa los ejemplos de ../Ejercicios OOP/e07-POO.php)
 *
 *  Resuelve cada problema donde se indica y ejecútalo en modo depuración para comprobarlo.
 */
require_once __DIR__ . '/../Ejercicios OOP/utilidades.php';

// =============================================================================================
// PROBLEMA 1. Una fracción que se muestra sola
//  Escribe la clase Fraccion (numerador y denominador int, denominador distinto de 0) que se
//  simplifique al crearse (usa el máximo común divisor) y que con __toString se muestre como
//  "3/4" (o solo "2" si el denominador es 1). Añade sumar(Fraccion $otra): Fraccion.
// =============================================================================================
titulo('Problema 1');
// completa aquí tu solución

// =============================================================================================
// PROBLEMA 2. Propiedades de solo lectura con __get
//  Escribe la clase Sensor que reciba en el constructor un array de lecturas (temperaturas) y
//  ofrezca las propiedades virtuales (calculadas en __get) minima, maxima y media. Si se intenta
//  ESCRIBIR cualquier propiedad, __set debe lanzar LogicException. Implementa también __isset.
// =============================================================================================
titulo('Problema 2');
// completa aquí tu solución

// =============================================================================================
// PROBLEMA 3. Getters y setters automáticos con __call
//  Escribe la clase Ficha que guarde sus datos en un array privado y entienda, con __call,
//  getAlgo() y setAlgo($valor) para cualquier campo de la lista CAMPOS (nombre, email, ciudad).
//  Para cualquier otro método, lanza BadMethodCallException. setAlgo debe devolver $this.
// =============================================================================================
titulo('Problema 3');
// completa aquí tu solución

// =============================================================================================
// PROBLEMA 4. Un objeto "callable" para ordenar
//  Escribe la clase OrdenarPor con __invoke(array $a, array $b): int, que reciba en el
//  constructor el campo y el sentido ('asc' o 'desc'), de forma que se pueda usar directamente
//  en usort. Ordena $productos por precio descendente y después por nombre ascendente.
// =============================================================================================
titulo('Problema 4');
$productos = [
    ['nombre' => 'Teclado', 'precio' => 49.5],
    ['nombre' => 'Ratón', 'precio' => 19.95],
    ['nombre' => 'Monitor', 'precio' => 179.0],
];
// completa aquí tu solución

// =============================================================================================
// PROBLEMA 5. Guardar una partida
//  La clase Partida tiene jugador, puntos y un dado (un objeto Dado que NO se debe guardar).
//  Añádele __serialize y __unserialize para que al serializar solo se guarden jugador y puntos,
//  y al recuperar se cree un dado nuevo. Comprueba que el texto serializado no contiene "Dado".
// =============================================================================================
titulo('Problema 5');
final class Dado
{
    public function tirar(): int
    {
        return random_int(1, 6);
    }
}
class Partida
{
    private Dado $dado;

    public function __construct(public string $jugador, public int $puntos = 0)
    {
        $this->dado = new Dado();
    }

    public function jugar(): void
    {
        $this->puntos += $this->dado->tirar();
    }
    // completa aquí: añade __serialize y __unserialize
}
// completa aquí tu solución

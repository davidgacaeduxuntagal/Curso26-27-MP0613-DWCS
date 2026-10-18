<?php
/*
 * e06-POO_problema.php
 *  Problemas de: self:: frente a static::, new static, tipo de retorno static, static::class,
 *                propiedades estáticas y herencia
 *  (repasa los ejemplos de ../Ejercicios OOP/e06-POO.php)
 *
 *  Resuelve cada problema donde se indica y ejecútalo en modo depuración para comprobarlo.
 */
require_once __DIR__ . '/../Ejercicios OOP/utilidades.php';

// =============================================================================================
// PROBLEMA 1. Encuentra el fallo
//  Moneda::crear() debería devolver un objeto de la clase con la que se llama (Euro::crear(5)
//  debería dar un Euro), pero siempre da una Moneda y el símbolo sale mal. Explica el fallo en
//  un comentario y corrígelo en la clase MonedaBien (y sus hijas EuroBien y DolarBien).
// =============================================================================================
titulo('Problema 1');
class Moneda
{
    public const SIMBOLO = '¤';

    public function __construct(public float $cantidad)
    {
    }

    public static function crear(float $cantidad): self
    {
        return new self($cantidad);
    }

    public function formato(): string
    {
        return number_format($this->cantidad, 2, ',', '.') . ' ' . self::SIMBOLO;
    }
}
class Euro extends Moneda
{
    public const SIMBOLO = '€';
}
ver('Euro::crear(5) (mal)', [get_class(Euro::crear(5)), Euro::crear(5)->formato()]);   // ['Moneda', '5,00 ¤']
// completa aquí tu solución

// =============================================================================================
// PROBLEMA 2. Un registro (registry) por clase
//  Escribe la clase abstracta Entidad con un método estático registrar(string $nombre): static
//  que cree un objeto de la clase llamante, lo guarde en un array estático indexado por clase y
//  lo devuelva, y otro método estático todos(): array que devuelva solo los objetos de esa
//  clase. Pruébalo con Alumno y Profesor.
// =============================================================================================
titulo('Problema 2');
// completa aquí tu solución

// =============================================================================================
// PROBLEMA 3. ¿Qué devuelve?
//  Sin ejecutarlo, escribe qué devuelve cada llamada y por qué. Después compruébalo.
// =============================================================================================
titulo('Problema 3');
class A
{
    public static function nombre(): string
    {
        return 'A';
    }

    public static function conSelf(): string
    {
        return self::nombre();
    }

    public static function conStatic(): string
    {
        return static::nombre();
    }
}
class B extends A
{
    public static function nombre(): string
    {
        return 'B';
    }
}
// completa aquí tu solución

// =============================================================================================
// PROBLEMA 4. Métodos encadenables en clases hijas
//  La clase ConsultaBase tiene métodos donde() y limite() que devuelven $this. La hija
//  ConsultaProductos añade soloEnStock(). ¿Qué tipo de retorno deben tener donde() y limite()
//  para que el IDE (y PHP) sepan que ConsultaProductos::...->donde() sigue siendo una
//  ConsultaProductos y se pueda encadenar soloEnStock()? Escribe las clases y genera la SQL.
// =============================================================================================
titulo('Problema 4');
// completa aquí tu solución

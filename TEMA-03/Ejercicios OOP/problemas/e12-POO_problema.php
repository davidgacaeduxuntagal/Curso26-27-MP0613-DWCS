<?php
/*
 * e12-POO_problema.php
 *  Problemas de: ReflectionClass, getProperties, getMethods, ReflectionProperty,
 *                ReflectionMethod, ReflectionParameter, newInstanceArgs, getValue / setValue
 *  (repasa los ejemplos de ../Ejercicios OOP/e12-POO.php)
 *
 *  Resuelve cada problema donde se indica y ejecútalo en modo depuración para comprobarlo.
 */
require_once __DIR__ . '/../Ejercicios OOP/utilidades.php';

// Clases de ejemplo para los problemas
class Vehiculo
{
    public function __construct(protected string $matricula, protected int $anio)
    {
    }

    public function antiguedad(int $anioActual = 2026): int
    {
        return $anioActual - $this->anio;
    }
}
final class Coche extends Vehiculo
{
    private int $kilometros = 0;

    public function __construct(string $matricula, int $anio, public string $marca, public ?string $color = null)
    {
        parent::__construct($matricula, $anio);
    }

    public function conducir(int $km): void
    {
        $this->kilometros += $km;
    }

    private function revisar(): string
    {
        return 'revisado';
    }
}

// =============================================================================================
// PROBLEMA 1. Ficha de una clase
//  Con ReflectionClass, muestra de la clase Coche: su clase padre, si es final, y la lista de
//  sus métodos PÚBLICOS indicando, para cada uno, en qué clase está declarado.
// =============================================================================================
titulo('Problema 1');
// completa aquí tu solución

// =============================================================================================
// PROBLEMA 2. Volcado completo de un objeto
//  Escribe la función volcado(object $obj): array que devuelva TODAS las propiedades del objeto
//  (también las protected, private y heredadas) como nombre => valor, usando Reflection.
//  Compárala con get_object_vars desde fuera de la clase.
// =============================================================================================
titulo('Problema 2');
$coche = new Coche('1234 KLM', 2019, 'Seat', 'rojo');
$coche->conducir(150);
// completa aquí tu solución

// =============================================================================================
// PROBLEMA 3. Crear objetos a partir de un array (como hacen los frameworks)
//  Escribe la función crearDesdeArray(string $clase, array $datos): object que lea los
//  parámetros del constructor de $clase y los rellene con los valores de $datos (por nombre); si
//  falta uno, usa su valor por defecto, y si no tiene, lanza InvalidArgumentException.
// =============================================================================================
titulo('Problema 3');
// completa aquí tu solución

// =============================================================================================
// PROBLEMA 4. Ejecutar un método privado en una prueba
//  Para una prueba automática hay que comprobar que el método privado revisar() de Coche
//  devuelve "revisado". ¿Cómo lo llamarías sin cambiar la clase? (Y, ¿por qué no conviene hacer
//  esto en el código normal de la aplicación? Contesta en un comentario.)
// =============================================================================================
titulo('Problema 4');
// completa aquí tu solución

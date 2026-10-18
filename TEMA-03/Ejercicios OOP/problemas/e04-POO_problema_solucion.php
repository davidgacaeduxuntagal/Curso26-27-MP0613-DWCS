<?php
/*
 * e04-POO_problema_solucion.php
 *  (FICHERO CON LAS SOLUCIONES)
 *  Problemas de: herencia (extends, parent::), redefinir métodos, protected, final, clases y
 *                métodos abstractos, polimorfismo, instanceof
 *  (repasa los ejemplos de ../Ejercicios OOP/e04-POO.php)
 *
 *  Resuelve cada problema donde se indica y ejecútalo en modo depuración para comprobarlo.
 */
require_once __DIR__ . '/../Ejercicios OOP/utilidades.php';

// =============================================================================================
// PROBLEMA 1. Empleados de un hospital
//  Crea la clase abstracta Empleado (nombre y sueldoBase protected, método abstracto
//  sueldo(): float y un método ficha(): string con "nombre: sueldo €"), y las hijas:
//   - Medico: sueldo base + 600 € por cada guardia (guardias en el constructor),
//   - Enfermero: sueldo base + 15 % si trabaja de noche (bool en el constructor),
//   - Administrativo: el sueldo base.
//  Muestra la ficha de un empleado de cada tipo.
// =============================================================================================
titulo('Problema 1');
// completa aquí tu solución
// ---- SOLUCIÓN ----
abstract class Empleado
{
    public function __construct(protected string $nombre, protected float $sueldoBase)
    {
    }

    abstract public function sueldo(): float;

    public function ficha(): string
    {
        return $this->nombre . ': ' . number_format($this->sueldo(), 2, ',', '.') . ' €';
    }
}
class Medico extends Empleado
{
    public function __construct(string $nombre, float $sueldoBase, private int $guardias)
    {
        parent::__construct($nombre, $sueldoBase);
    }

    public function sueldo(): float
    {
        return $this->sueldoBase + 600 * $this->guardias;
    }
}
class Enfermero extends Empleado
{
    public function __construct(string $nombre, float $sueldoBase, private bool $noche = false)
    {
        parent::__construct($nombre, $sueldoBase);
    }

    public function sueldo(): float
    {
        return round($this->sueldoBase * ($this->noche ? 1.15 : 1), 2);
    }
}
class Administrativo extends Empleado
{
    public function sueldo(): float
    {
        return $this->sueldoBase;
    }
}
$plantilla = [new Medico('Dra. Lago', 3200, 3), new Enfermero('Brais', 1900, true), new Administrativo('Eva', 1500)];
ver('fichas', array_map(fn(Empleado $e) => $e->ficha(), $plantilla));
// ['Dra. Lago: 5.000,00 €', 'Brais: 2.185,00 €', 'Eva: 1.500,00 €']

// =============================================================================================
// PROBLEMA 2. Polimorfismo
//  Usando las clases del problema 1, calcula el gasto total en nóminas de $plantilla SIN usar
//  instanceof ni if: solo llamando al método polimórfico.
// =============================================================================================
titulo('Problema 2');
// completa aquí tu solución
// ---- SOLUCIÓN ----
$gasto = array_sum(array_map(fn(Empleado $e) => $e->sueldo(), $plantilla));
ver('gasto total', $gasto);          // 8685.0

// =============================================================================================
// PROBLEMA 3. Redefinir y reutilizar
//  Crea MedicoJefe, que hereda de Medico: cobra además un complemento de 800 €. Redefine sueldo()
//  reutilizando el cálculo del padre con parent::, y redefine ficha() para añadir " (jefe)".
// =============================================================================================
titulo('Problema 3');
// completa aquí tu solución
// ---- SOLUCIÓN ----
class MedicoJefe extends Medico
{
    public function sueldo(): float
    {
        return parent::sueldo() + 800;
    }

    public function ficha(): string
    {
        return parent::ficha() . ' (jefe)';
    }
}
$jefe = new MedicoJefe('Dr. Otero', 3500, 2);
ver('ficha del jefe', $jefe->ficha());                 // "Dr. Otero: 5.500,00 € (jefe)"
ver('¿es Medico? ¿es Empleado?', [$jefe instanceof Medico, $jefe instanceof Empleado]);   // [true, true]

// =============================================================================================
// PROBLEMA 4. instanceof con moderación
//  De $plantilla (y el jefe), obtén los nombres de los empleados que hacen guardias (los Medico,
//  incluidos los jefes). ¿Por qué aquí sí tiene sentido instanceof? Contesta en un comentario.
// =============================================================================================
titulo('Problema 4');
// completa aquí tu solución
// ---- SOLUCIÓN ----
$todos = [...$plantilla, $jefe];
$conGuardias = array_filter($todos, fn(Empleado $e) => $e instanceof Medico);
ver('hacen guardias', array_map(fn(Empleado $e) => explode(':', $e->ficha())[0], array_values($conGuardias)));
// ['Dra. Lago', 'Dr. Otero']
// Tiene sentido porque no se aplica un comportamiento que cada clase haga a su manera, sino que
// se SELECCIONAN los objetos de un tipo. Si para cada tipo hubiera que calcular algo distinto,
// sería mejor un método polimórfico.

// =============================================================================================
// PROBLEMA 5. ¿Cómo harías...?
//  ¿Cómo harías para que ninguna clase hija pudiera cambiar la forma en que ficha() presenta los
//  datos, pero sí el sueldo? ¿Y para impedir que alguien herede de Administrativo? Explícalo en
//  un comentario con el código necesario (no lo ejecutes: daría error con MedicoJefe).
// =============================================================================================
titulo('Problema 5');
// completa aquí tu solución
// ---- SOLUCIÓN ----
// - Declarar ficha() como final en Empleado:   final public function ficha(): string { ... }
//   Así las hijas pueden redefinir sueldo() (que ficha usa), pero no ficha(). MedicoJefe, que
//   redefine ficha(), daría "Fatal error: Cannot override final method Empleado::ficha()".
// - Declarar la clase como final:   final class Administrativo extends Empleado { ... }
//   Así "class Becario extends Administrativo" daría "Class Becario cannot extend final class
//   Administrativo".
ver('¿ficha es final ahora?', (new ReflectionMethod(Empleado::class, 'ficha'))->isFinal());   // false (no lo hemos cambiado)

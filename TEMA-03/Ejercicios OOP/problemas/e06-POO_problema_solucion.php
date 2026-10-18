<?php
/*
 * e06-POO_problema_solucion.php
 *  (FICHERO CON LAS SOLUCIONES)
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
// ---- SOLUCIÓN ----
// Fallo: new self y self::SIMBOLO se refieren SIEMPRE a la clase donde está escrito el código
// (Moneda). Con new static y static::SIMBOLO se usa la clase con la que se ha llamado (late
// static binding), y el tipo de retorno debe ser static.
class MonedaBien
{
    public const SIMBOLO = '¤';

    public function __construct(public float $cantidad)
    {
    }

    public static function crear(float $cantidad): static
    {
        return new static($cantidad);
    }

    public function formato(): string
    {
        return number_format($this->cantidad, 2, ',', '.') . ' ' . static::SIMBOLO;
    }
}
class EuroBien extends MonedaBien
{
    public const SIMBOLO = '€';
}
class DolarBien extends MonedaBien
{
    public const SIMBOLO = '$';
}
ver('EuroBien::crear(5)', [get_class(EuroBien::crear(5)), EuroBien::crear(5)->formato()]);   // ['EuroBien', '5,00 €']
ver('DolarBien::crear(1234.5)', DolarBien::crear(1234.5)->formato());                        // "1.234,50 $"

// =============================================================================================
// PROBLEMA 2. Un registro (registry) por clase
//  Escribe la clase abstracta Entidad con un método estático registrar(string $nombre): static
//  que cree un objeto de la clase llamante, lo guarde en un array estático indexado por clase y
//  lo devuelva, y otro método estático todos(): array que devuelva solo los objetos de esa
//  clase. Pruébalo con Alumno y Profesor.
// =============================================================================================
titulo('Problema 2');
// completa aquí tu solución
// ---- SOLUCIÓN ----
abstract class Entidad
{
    private static array $registro = [];

    final public function __construct(public string $nombre)
    {
    }

    public static function registrar(string $nombre): static
    {
        $objeto = new static($nombre);
        self::$registro[static::class][] = $objeto;
        return $objeto;
    }

    public static function todos(): array
    {
        return self::$registro[static::class] ?? [];
    }
}
class Alumno extends Entidad
{
}
class Profesor extends Entidad
{
}
Alumno::registrar('Ana');
Alumno::registrar('Brais');
Profesor::registrar('María');
ver('alumnos', array_map(fn($a) => $a->nombre, Alumno::todos()));         // ['Ana', 'Brais']
ver('profesores', array_map(fn($p) => $p->nombre, Profesor::todos()));    // ['María']

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
// ---- SOLUCIÓN ----
// B::conSelf()   -> "A": self:: es la clase donde está escrito conSelf (A).
// B::conStatic() -> "B": static:: es la clase con la que se ha llamado (B).
// A::conStatic() -> "A": se ha llamado con A.
ver('respuestas', [B::conSelf(), B::conStatic(), A::conStatic()]);   // ['A', 'B', 'A']

// =============================================================================================
// PROBLEMA 4. Métodos encadenables en clases hijas
//  La clase ConsultaBase tiene métodos donde() y limite() que devuelven $this. La hija
//  ConsultaProductos añade soloEnStock(). ¿Qué tipo de retorno deben tener donde() y limite()
//  para que el IDE (y PHP) sepan que ConsultaProductos::...->donde() sigue siendo una
//  ConsultaProductos y se pueda encadenar soloEnStock()? Escribe las clases y genera la SQL.
// =============================================================================================
titulo('Problema 4');
// completa aquí tu solución
// ---- SOLUCIÓN ----
class ConsultaBase
{
    protected array $condiciones = [];
    protected ?int $limite = null;

    public function __construct(protected string $tabla)
    {
    }

    public function donde(string $condicion): static      // static: "la clase del objeto"
    {
        $this->condiciones[] = $condicion;
        return $this;
    }

    public function limite(int $n): static
    {
        $this->limite = $n;
        return $this;
    }

    public function sql(): string
    {
        $sql = "SELECT * FROM $this->tabla";
        if ($this->condiciones !== []) {
            $sql .= ' WHERE ' . implode(' AND ', $this->condiciones);
        }
        return $sql . ($this->limite !== null ? " LIMIT $this->limite" : '');
    }
}
class ConsultaProductos extends ConsultaBase
{
    public function __construct()
    {
        parent::__construct('productos');
    }

    public function soloEnStock(): static
    {
        return $this->donde('stock > 0');
    }
}
$sql = (new ConsultaProductos())->donde("categoria = 'tv'")->soloEnStock()->limite(10)->sql();
ver('sql', $sql);    // "SELECT * FROM productos WHERE categoria = 'tv' AND stock > 0 LIMIT 10"

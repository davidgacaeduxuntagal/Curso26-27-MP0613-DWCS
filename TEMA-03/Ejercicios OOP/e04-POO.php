<?php
/*
 * e04-POO.php
 *  POO (4): HERENCIA Y POLIMORFISMO
 *  Contenidos: extends, herencia de propiedades y métodos, redefinir (override) métodos,
 *              parent::, parent::__construct, protected, final (métodos y clases), clases y
 *              métodos abstract, polimorfismo, instanceof, get_parent_class, is_subclass_of
 *  (apartado 6 de los apuntes)
 *
 *  Cómo trabajar este fichero:
 *   1. Pon un punto de ruptura en la primera línea de código y ejecuta en modo depuración.
 *   2. En el bucle polimórfico (sección "Polimorfismo"), usa F11 en $producto->precioFinal():
 *      verás que PHP entra en un método distinto según la clase REAL de cada objeto.
 *   3. En VARIABLES, fíjate en que los objetos de las clases hijas tienen también las propiedades
 *      heredadas (las protected aparecen marcadas).
 */
require_once __DIR__ . '/utilidades.php';

// ---------------------------------------------------------------------------------------------
// abstract class — Una clase que solo sirve para heredar de ella
// ENLACE: https://www.php.net/manual/es/language.oop5.abstract.php
// ENUNCIADO EJEMPLO: en la tienda hay libros, alimentos y aparatos electrónicos. Todos tienen
//                   nombre y precio, pero cada tipo calcula el IVA y los portes a su manera.
// NOTA: "Producto" a secas no existe en la tienda: por eso es abstracta (no se puede hacer
//       new Producto). Un método abstract solo tiene la firma: cada hija está OBLIGADA a
//       implementarlo. protected: accesible desde la clase y sus hijas, no desde fuera.
// ---------------------------------------------------------------------------------------------
titulo('Clase abstracta');
abstract class Producto
{
    public function __construct(protected string $nombre, protected float $precioBase)
    {
    }

    abstract public function tipoIva(): float;            // cada hija dice su IVA

    public function getNombre(): string
    {
        return $this->nombre;
    }

    // Método "plantilla": usa tipoIva() y portes(), que cada hija puede cambiar. Es final:
    // ninguna hija puede cambiar CÓMO se calcula el precio final, solo sus piezas.
    final public function precioFinal(): float
    {
        return round($this->precioBase * (1 + $this->tipoIva()) + $this->portes(), 2);
    }

    public function portes(): float                       // comportamiento por defecto
    {
        return 3.95;
    }

    public function descripcion(): string
    {
        return $this->nombre . ' (' . $this->precioFinal() . ' €)';
    }
}
verError('new Producto', fn() => new Producto('X', 1));   // Error: Cannot instantiate abstract class Producto

// ---------------------------------------------------------------------------------------------
// extends — Clases hijas; override; parent::
// ENLACE: https://www.php.net/manual/es/language.oop5.inheritance.php
// ENUNCIADO EJEMPLO: las tres clases de producto concretas.
// NOTA: la hija hereda todo lo public y protected. Puede añadir propiedades y métodos y REDEFINIR
//       (override) los heredados. Con parent:: llama a la versión del padre. Si la hija tiene
//       constructor propio, debe llamar a parent::__construct (PHP no lo hace solo).
// ---------------------------------------------------------------------------------------------
titulo('extends');
class Libro extends Producto
{
    public function __construct(string $nombre, float $precioBase, private string $isbn)
    {
        parent::__construct($nombre, $precioBase);       // inicializa la parte heredada
    }

    public function tipoIva(): float
    {
        return 0.04;                                     // IVA superreducido
    }

    public function portes(): float                      // override: los libros no pagan portes
    {
        return 0.0;
    }

    public function descripcion(): string                // override reutilizando el del padre
    {
        return parent::descripcion() . ' ISBN ' . $this->isbn;
    }
}

class Alimento extends Producto
{
    public function tipoIva(): float
    {
        return 0.10;
    }
}

class Electronica extends Producto
{
    public function __construct(string $nombre, float $precioBase, private int $mesesGarantia = 36)
    {
        parent::__construct($nombre, $precioBase);
    }

    public function tipoIva(): float
    {
        return 0.21;
    }

    public function portes(): float
    {
        return $this->precioBase >= 100 ? 0.0 : parent::portes();   // gratis desde 100 €
    }

    public function getMesesGarantia(): int
    {
        return $this->mesesGarantia;
    }
}

$libro = new Libro('El Quijote', 14.90, '978-84-376-0494-7');
$queso = new Alimento('Queso de tetilla', 9.50);
$tele = new Electronica('Televisor 50"', 399.00);
$cable = new Electronica('Cable HDMI', 8.99, 24);
ver('libro', $libro);                    // nombre:protected, precioBase:protected, isbn:Libro:private
ver('libro: precio final', $libro->precioFinal());    // 15.5 (14.90 + 4 %, sin portes)
ver('queso: precio final', $queso->precioFinal());    // 14.4 (9.50 + 10 % + 3.95)
ver('tele: precio final', $tele->precioFinal());      // 482.79 (sin portes)
ver('cable: precio final', $cable->precioFinal());    // 14.83 (8.99 + 21 % + 3.95)
ver('descripción del libro', $libro->descripcion());  // "El Quijote (15.5 €) ISBN 978-84-376-0494-7"
verError('acceder a protected', fn() => $libro->nombre);   // Error: Cannot access protected property Libro::$nombre

// ---------------------------------------------------------------------------------------------
// Polimorfismo — El mismo mensaje, distinto comportamiento
// ENLACE: https://www.php.net/manual/es/language.oop5.abstract.php
// ENUNCIADO EJEMPLO: calcular el total de un carrito con productos de distintos tipos.
// NOTA: el código del carrito solo sabe que son "Producto" y llama a precioFinal(): cada objeto
//       responde según su clase. Si mañana se añade la clase Medicamento, este bucle no cambia.
// ---------------------------------------------------------------------------------------------
titulo('Polimorfismo');
$carrito = [$libro, $queso, $tele, $cable];
$total = 0;
foreach ($carrito as $producto) {
    $total += $producto->precioFinal();              // pon aquí un punto de ruptura y usa F11
}
ver('total del carrito', round($total, 2));          // 527.52
ver('descripciones', array_map(fn(Producto $p) => $p->descripcion(), $carrito));

// ---------------------------------------------------------------------------------------------
// instanceof, get_parent_class, is_subclass_of
// ENLACE: https://www.php.net/manual/es/language.operators.type.php
// ENLACE: https://www.php.net/manual/es/function.get-parent-class.php
// ENUNCIADO EJEMPLO: calcular la garantía solo de los productos electrónicos.
// NOTA: un objeto de una clase hija ES TAMBIÉN de la clase padre (un Libro es un Producto).
//       Usa instanceof con moderación: si tienes muchos if (instanceof), quizá deberías usar
//       polimorfismo (un método en cada clase).
// ---------------------------------------------------------------------------------------------
titulo('instanceof');
ver('¿libro es Producto?', $libro instanceof Producto);         // true
ver('¿libro es Electronica?', $libro instanceof Electronica);   // false
ver('padre de Libro', get_parent_class($libro));                // "Producto"
ver('is_subclass_of', is_subclass_of($tele, 'Producto'));       // true
$garantias = [];
foreach ($carrito as $producto) {
    if ($producto instanceof Electronica) {
        $garantias[$producto->getNombre()] = $producto->getMesesGarantia();   // solo existe en Electronica
    }
}
ver('garantías', $garantias);           // ['Televisor 50"' => 36, 'Cable HDMI' => 24]

// ---------------------------------------------------------------------------------------------
// final — Impedir que se herede o se redefina
// ENLACE: https://www.php.net/manual/es/language.oop5.final.php
// ENUNCIADO EJEMPLO: que nadie pueda cambiar la fórmula del precio final.
// NOTA: un método final no se puede redefinir; una clase final no se puede heredar. PHP lo
//       comprueba al COMPILAR la clase, así que el error es "Fatal error" y detiene el script: no
//       se puede capturar con try/catch. Para verlo, DESCOMENTA una de las líneas de abajo,
//       ejecuta, lee el mensaje y vuelve a comentarla.
// ---------------------------------------------------------------------------------------------
titulo('final');
// class Oferta extends Alimento { public function precioFinal(): float { return 0.0; } }
//    -> Fatal error: Cannot override final method Producto::precioFinal()
// final class Caja {}  class CajaGrande extends Caja {}
//    -> Fatal error: Class CajaGrande cannot extend final class Caja
// class Regalo extends Producto {}
//    -> Fatal error: Class Regalo contains 1 abstract method and must therefore be declared
//       abstract or implement the remaining methods (Producto::tipoIva)
ver('¿precioFinal es final?', (new ReflectionMethod('Producto', 'precioFinal'))->isFinal());   // true (Reflection: e12)
ver('¿Producto es abstracta?', (new ReflectionClass('Producto'))->isAbstract());               // true

<?php
// e10-src/Tienda/Servicio/Carrito.php
namespace Tienda\Servicio;

use Tienda\Modelo\Cliente;      // importamos las clases de OTRO espacio de nombres
use Tienda\Modelo\Producto;

class Carrito implements \Countable    // con \ delante: la interfaz Countable del espacio global
{
    private array $productos = [];

    public function __construct(private Cliente $cliente)
    {
    }

    public function anadir(Producto $producto): void
    {
        $this->productos[] = $producto;
    }

    public function count(): int
    {
        return count($this->productos);    // count() de PHP: si no existe Tienda\Servicio\count, se usa la global
    }

    public function resumen(): string
    {
        $total = array_sum(array_map(fn(Producto $p) => $p->precio, $this->productos));
        return $this->cliente->nombre . ': ' . count($this) . ' productos, ' . $total . ' €';
    }
}

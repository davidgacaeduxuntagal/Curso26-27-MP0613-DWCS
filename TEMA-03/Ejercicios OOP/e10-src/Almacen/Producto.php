<?php
// e10-src/Almacen/Producto.php
// Otra clase llamada Producto, en otro espacio de nombres: no hay conflicto con Tienda\Modelo\Producto
namespace Almacen;

class Producto
{
    public function __construct(public readonly string $referencia, public readonly int $stock)
    {
    }
}

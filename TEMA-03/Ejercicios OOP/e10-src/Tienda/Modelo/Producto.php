<?php
// e10-src/Tienda/Modelo/Producto.php
// PSR-4: el espacio de nombres Tienda\Modelo corresponde a la carpeta Tienda/Modelo y la clase
// Producto al fichero Producto.php. Una clase por fichero, y el fichero no hace nada más.
namespace Tienda\Modelo;

class Producto
{
    public function __construct(public readonly string $nombre, public readonly float $precio)
    {
    }
}

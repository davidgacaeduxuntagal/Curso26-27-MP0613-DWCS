<?php
/*
 * Libro.php
 */
require_once __DIR__ . '/Producto.php';
require_once __DIR__ . '/Enviable.php';

class Libro extends Producto implements Enviable
{
    private string $autor;
    private float $peso;

    public function __construct(string $codigo, string $nombre, float $precio, string $autor, float $peso)
    {
        parent::__construct($codigo, $nombre, $precio);
        $this->autor = $autor;
        $this->peso  = $peso;
    }

    public function tipoIva(): float
    {
        return 0.04;          // IVA superreducido
    }

    public function categoria(): string
    {
        return 'Libros';
    }

    public function detalles(): string
    {
        return "de {$this->autor}";
    }

    public function peso(): float
    {
        return $this->peso;
    }
}

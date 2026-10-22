<?php
/*
 * Electronica.php
 *  Productos electrónicos: IVA general y un recargo fijo por reciclaje (ecotasa) que se suma al
 *  precio sin IVA.
 */
require_once __DIR__ . '/Producto.php';
require_once __DIR__ . '/Enviable.php';

class Electronica extends Producto implements Enviable
{
    public const ECOTASA = 1.50;

    private int $garantiaAnios;
    private float $peso;

    public function __construct(string $codigo, string $nombre, float $precio, int $garantiaAnios, float $peso)
    {
        parent::__construct($codigo, $nombre, $precio);
        $this->garantiaAnios = $garantiaAnios;
        $this->peso          = $peso;
    }

    public function tipoIva(): float
    {
        return 0.21;          // IVA general
    }

    public function categoria(): string
    {
        return 'Electrónica';
    }

    /**
     * El método es FINAL: ninguna subclase de Electronica podrá saltarse la ecotasa.
     */
    final public function precioSinIva(): float
    {
        return parent::precioSinIva() + self::ECOTASA;
    }

    public function detalles(): string
    {
        return "garantía {$this->garantiaAnios} años, incluye ecotasa de " . self::formato(self::ECOTASA) . " €";
    }

    public function peso(): float
    {
        return $this->peso;
    }
}

<?php
/*
 * Producto.php
 *  Clase ABSTRACTA con lo común a todos los productos de la tienda. Cada tipo de producto decide
 *  su tipo de IVA, su categoría y la información extra que muestra.
 */
abstract class Producto
{
    protected string $codigo;
    protected string $nombre;
    protected float $precio;       // precio de venta SIN IVA

    public function __construct(string $codigo, string $nombre, float $precio)
    {
        if (preg_match('/^[A-Z]{3}\d{3}$/', $codigo) !== 1) {
            throw new InvalidArgumentException("Código de producto no válido: «{$codigo}» (formato AAA999)");
        }
        if ($precio <= 0) {
            throw new InvalidArgumentException("El precio de «{$nombre}» debe ser mayor que 0");
        }
        $this->codigo = $codigo;
        $this->nombre = $nombre;
        $this->precio = $precio;
    }

    public function getCodigo(): string
    {
        return $this->codigo;
    }

    public function getNombre(): string
    {
        return $this->nombre;
    }

    // ---------------------------------------------------------------------- métodos abstractos

    /**
     * Tipo de IVA en tanto por uno (0.21 = 21 %).
     */
    abstract public function tipoIva(): float;

    abstract public function categoria(): string;

    // ---------------------------------------------------------------------- métodos que las subclases pueden redefinir

    /**
     * Precio unitario sin IVA. Las subclases lo redefinen si aplican descuentos o recargos.
     */
    public function precioSinIva(): float
    {
        return $this->precio;
    }

    /**
     * Información propia de cada tipo de producto (autor, caducidad, garantía...).
     */
    public function detalles(): string
    {
        return '';
    }

    // ---------------------------------------------------------------------- métodos comunes

    public function precioConIva(): float
    {
        return round($this->precioSinIva() * (1 + $this->tipoIva()), 2);
    }

    public function __toString(): string
    {
        return "{$this->nombre} ({$this->categoria()}) - " . self::formato($this->precioConIva()) . " €";
    }

    /**
     * Importe con 2 decimales y coma decimal.
     */
    public static function formato(float $importe): string
    {
        return number_format($importe, 2, ',', '.');
    }
}

<?php
/*
 * Alimento.php
 *  Alimento fresco: NO es Enviable (solo recogida en tienda) y tiene un 30 % de descuento cuando
 *  le quedan 3 días o menos para caducar.
 */
require_once __DIR__ . '/Producto.php';

class Alimento extends Producto
{
    public const DIAS_OFERTA      = 3;
    public const DESCUENTO_OFERTA = 0.30;

    private DateTimeImmutable $caducidad;

    public function __construct(string $codigo, string $nombre, float $precio, string $caducidad)
    {
        parent::__construct($codigo, $nombre, $precio);
        $fecha = DateTimeImmutable::createFromFormat('!Y-m-d', $caducidad);
        if ($fecha === false) {
            throw new InvalidArgumentException("Fecha de caducidad no válida: «{$caducidad}»");
        }
        $this->caducidad = $fecha;
    }

    public function diasParaCaducar(): int
    {
        $hoy = new DateTimeImmutable('today');
        return (int) $hoy->diff($this->caducidad)->format('%r%a');   // %r: signo (negativo si ya caducó)
    }

    public function estaEnOferta(): bool
    {
        return $this->diasParaCaducar() <= self::DIAS_OFERTA;
    }

    public function tipoIva(): float
    {
        return 0.10;          // IVA reducido
    }

    public function categoria(): string
    {
        return 'Alimentación';
    }

    /**
     * Redefinimos el precio: con la oferta se aplica el descuento.
     */
    public function precioSinIva(): float
    {
        $precio = parent::precioSinIva();
        return $this->estaEnOferta() ? round($precio * (1 - self::DESCUENTO_OFERTA), 2) : $precio;
    }

    public function detalles(): string
    {
        $texto = "caduca el " . $this->caducidad->format('d/m/Y');
        if ($this->estaEnOferta()) {
            $texto .= " (¡oferta -" . (self::DESCUENTO_OFERTA * 100) . " %!)";
        }
        return $texto;
    }
}

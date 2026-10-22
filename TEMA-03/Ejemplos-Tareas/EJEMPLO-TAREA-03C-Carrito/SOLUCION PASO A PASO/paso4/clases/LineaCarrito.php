<?php
/*
 * LineaCarrito.php
 *  Una línea del carrito: un producto y la cantidad de unidades.
 *
 *  SERIALIZACIÓN: el carrito viaja entre peticiones serializado en un campo oculto. Si
 *  serializáramos el objeto Producto completo, el usuario podría cambiar el PRECIO en el campo
 *  oculto. Por eso:
 *    - __sleep() indica que SOLO se guarden el código y la cantidad
 *    - __wakeup() vuelve a buscar el producto en el catálogo (con su precio real) al deserializar
 */
require_once __DIR__ . '/Catalogo.php';

class LineaCarrito
{
    public const MAX_UNIDADES = 20;

    private string $codigo;
    private int $cantidad;
    private ?Producto $producto = null;

    public function __construct(Producto $producto, int $cantidad)
    {
        $this->producto = $producto;
        $this->codigo   = $producto->getCodigo();
        $this->setCantidad($cantidad);
    }

    public function getCodigo(): string
    {
        return $this->codigo;
    }

    public function getProducto(): Producto
    {
        return $this->producto;
    }

    public function getCantidad(): int
    {
        return $this->cantidad;
    }

    public function setCantidad(int $cantidad): void
    {
        if ($cantidad < 1 || $cantidad > self::MAX_UNIDADES) {
            throw new InvalidArgumentException("La cantidad debe estar entre 1 y " . self::MAX_UNIDADES . " unidades");
        }
        $this->cantidad = $cantidad;
    }

    // ---------------------------------------------------------------------- importes

    public function subtotalSinIva(): float
    {
        return round($this->producto->precioSinIva() * $this->cantidad, 2);
    }

    public function cuotaIva(): float
    {
        return round($this->subtotalSinIva() * $this->producto->tipoIva(), 2);
    }

    public function subtotal(): float
    {
        return $this->subtotalSinIva() + $this->cuotaIva();
    }

    // ---------------------------------------------------------------------- serialización

    /**
     * Se ejecuta ANTES de serializar: devuelve los nombres de los atributos que se guardan.
     */
    public function __sleep(): array
    {
        return ['codigo', 'cantidad'];
    }

    /**
     * Se ejecuta DESPUÉS de deserializar: reconstruimos lo que no se guardó y revalidamos.
     * Si el código no existe en el catálogo o la cantidad no es válida, lanzamos una excepción.
     */
    public function __wakeup(): void
    {
        $producto = Catalogo::buscar($this->codigo);
        if ($producto === null) {
            throw new UnexpectedValueException("El producto «{$this->codigo}» ya no existe en el catálogo");
        }
        $this->producto = $producto;
        $this->setCantidad($this->cantidad);
    }
}

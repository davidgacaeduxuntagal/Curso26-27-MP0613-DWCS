<?php
/*
 * Carrito.php
 *  Carrito de la compra. Implementa la interfaz Countable de PHP: así se puede usar count($carrito)
 *  igual que con un array (devuelve el número total de UNIDADES).
 */
require_once __DIR__ . '/LineaCarrito.php';

class Carrito implements Countable
{
    public const ENVIO_GRATIS_DESDE = 50.00;   // € (productos con IVA)
    public const ENVIO_BASE         = 3.99;
    public const ENVIO_POR_KG       = 0.50;

    /** @var LineaCarrito[]  código del producto => línea */
    private array $lineas = [];

    // ---------------------------------------------------------------------- operaciones

    /**
     * Añade unidades de un producto (si ya estaba en el carrito, se suman).
     */
    public function anadir(string $codigo, int $cantidad): void
    {
        $producto = Catalogo::buscar($codigo);
        if ($producto === null) {
            throw new InvalidArgumentException("No existe ningún producto con el código «{$codigo}»");
        }
        if (isset($this->lineas[$codigo])) {
            $this->lineas[$codigo]->setCantidad($this->lineas[$codigo]->getCantidad() + $cantidad);
        } else {
            $this->lineas[$codigo] = new LineaCarrito($producto, $cantidad);
        }
    }

    /**
     * Suma (o resta, si $diferencia es negativa) unidades. Si llega a 0, se quita la línea.
     */
    public function cambiarCantidad(string $codigo, int $diferencia): void
    {
        if (!isset($this->lineas[$codigo])) {
            return;
        }
        $nueva = $this->lineas[$codigo]->getCantidad() + $diferencia;
        if ($nueva <= 0) {
            $this->quitar($codigo);
        } else {
            $this->lineas[$codigo]->setCantidad($nueva);
        }
    }

    public function quitar(string $codigo): void
    {
        unset($this->lineas[$codigo]);
    }

    public function vaciar(): void
    {
        $this->lineas = [];
    }

    /**
     * @return LineaCarrito[]
     */
    public function getLineas(): array
    {
        return $this->lineas;
    }

    public function estaVacio(): bool
    {
        return count($this->lineas) === 0;
    }

    /**
     * Método exigido por la interfaz Countable: número total de unidades.
     */
    public function count(): int
    {
        return array_sum(array_map(fn(LineaCarrito $l) => $l->getCantidad(), $this->lineas));
    }

    // ---------------------------------------------------------------------- importes

    public function totalSinIva(): float
    {
        return array_sum(array_map(fn(LineaCarrito $l) => $l->subtotalSinIva(), $this->lineas));
    }

    public function totalProductos(): float
    {
        return array_sum(array_map(fn(LineaCarrito $l) => $l->subtotal(), $this->lineas));
    }

    /**
     * Base imponible y cuota de IVA agrupadas por tipo de IVA:
     *   ['4 %' => ['base' => 43.25, 'cuota' => 1.73], '21 %' => [...], ...]
     */
    public function desgloseIva(): array
    {
        $desglose = [];
        foreach ($this->lineas as $linea) {
            $tipo = (int) round($linea->getProducto()->tipoIva() * 100);
            $desglose[$tipo]['base']  = ($desglose[$tipo]['base'] ?? 0) + $linea->subtotalSinIva();
            $desglose[$tipo]['cuota'] = ($desglose[$tipo]['cuota'] ?? 0) + $linea->cuotaIva();
        }
        ksort($desglose);
        return $desglose;
    }

    // ---------------------------------------------------------------------- envío

    /**
     * El pedido se puede enviar si TODOS los productos implementan la interfaz Enviable.
     */
    public function esEnviable(): bool
    {
        foreach ($this->lineas as $linea) {
            if (!($linea->getProducto() instanceof Enviable)) {
                return false;
            }
        }
        return true;
    }

    public function pesoTotal(): float
    {
        $peso = 0.0;
        foreach ($this->lineas as $linea) {
            $producto = $linea->getProducto();
            if ($producto instanceof Enviable) {
                $peso += $producto->peso() * $linea->getCantidad();
            }
        }
        return $peso;
    }

    /**
     * Gastos de envío, o null si el pedido no se puede enviar (solo recogida en tienda).
     */
    public function gastosEnvio(): ?float
    {
        if ($this->estaVacio() || !$this->esEnviable()) {
            return null;
        }
        if ($this->totalProductos() >= self::ENVIO_GRATIS_DESDE) {
            return 0.0;
        }
        return round(self::ENVIO_BASE + self::ENVIO_POR_KG * ceil($this->pesoTotal()), 2);
    }

    public function total(): float
    {
        return $this->totalProductos() + ($this->gastosEnvio() ?? 0.0);
    }

    // ---------------------------------------------------------------------- guardar / recuperar

    /**
     * Tras deserializar, comprobamos que el array de líneas no se ha manipulado: allowed_classes
     * impide crear objetos de otras clases, pero el array podría contener otros valores.
     * (Las líneas ya se han reconstruido con su propio __wakeup antes que el carrito.)
     */
    public function __wakeup(): void
    {
        foreach ($this->lineas as $codigo => $linea) {
            if (!($linea instanceof LineaCarrito) || $linea->getCodigo() !== $codigo) {
                throw new UnexpectedValueException("El contenido del carrito no es válido");
            }
        }
    }

    /**
     * Convierte el carrito en un texto que se puede guardar en un campo oculto.
     */
    public function serializar(): string
    {
        return base64_encode(serialize($this));
    }

    /**
     * Reconstruye el carrito a partir del texto. Devuelve null si el texto no es válido.
     *  - allowed_classes: SOLO se pueden crear objetos de estas clases al deserializar (seguridad)
     *  - LineaCarrito::__wakeup() vuelve a buscar los productos y valida las cantidades
     */
    public static function desdeTexto(string $texto): ?Carrito
    {
        if ($texto === '') {
            return new Carrito();
        }
        $serializado = base64_decode($texto, true);
        if ($serializado === false) {
            return null;
        }
        try {
            $carrito = @unserialize($serializado, ['allowed_classes' => [Carrito::class, LineaCarrito::class]]);
        } catch (InvalidArgumentException | UnexpectedValueException | TypeError $e) {
            // TypeError: el texto intenta poner, por ejemplo, un string en un atributo de tipo int
            return null;
        }
        return $carrito instanceof Carrito ? $carrito : null;
    }
}

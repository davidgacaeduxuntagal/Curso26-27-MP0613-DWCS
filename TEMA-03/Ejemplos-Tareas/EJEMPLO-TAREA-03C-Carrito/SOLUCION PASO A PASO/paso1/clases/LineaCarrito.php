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
// TODO: require_once __DIR__ . '/Catalogo.php';

// TODO: class LineaCarrito
//   TODO: public const MAX_UNIDADES = 20;
//   TODO: private string $codigo;
//   TODO: private int $cantidad;
//   TODO: private ?Producto $producto = null;
//   TODO: public function __construct(Producto $producto, int $cantidad)
//   TODO: public function getCodigo(): string
//   TODO: public function getProducto(): Producto
//   TODO: public function getCantidad(): int
//   TODO: public function setCantidad(int $cantidad): void
//   TODO: public function subtotalSinIva(): float
//   TODO: public function cuotaIva(): float
//   TODO: public function subtotal(): float
//   TODO: public function __sleep(): array   -> Se ejecuta ANTES de serializar: devuelve los nombres de los atributos que se guardan.
//   TODO: public function __wakeup(): void   -> Se ejecuta DESPUÉS de deserializar: reconstruimos lo que no se guardó y revalidamos.

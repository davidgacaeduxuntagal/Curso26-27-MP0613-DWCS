<?php
/*
 * Carrito.php
 *  Carrito de la compra. Implementa la interfaz Countable de PHP: así se puede usar count($carrito)
 *  igual que con un array (devuelve el número total de UNIDADES).
 */
// TODO: require_once __DIR__ . '/LineaCarrito.php';

// TODO: class Carrito implements Countable
//   TODO: public const ENVIO_GRATIS_DESDE = 50.00;
//   TODO: public const ENVIO_BASE         = 3.99;
//   TODO: public const ENVIO_POR_KG       = 0.50;
//   TODO: private array $lineas = [];
//   TODO: public function anadir(string $codigo, int $cantidad): void   -> Añade unidades de un producto (si ya estaba en el carrito, se suman).
//   TODO: public function cambiarCantidad(string $codigo, int $diferencia): void   -> Suma (o resta, si $diferencia es negativa) unidades. Si llega a 0, se quita la línea.
//   TODO: public function quitar(string $codigo): void
//   TODO: public function vaciar(): void
//   TODO: public function getLineas(): array
//   TODO: public function estaVacio(): bool
//   TODO: public function count(): int   -> Método exigido por la interfaz Countable: número total de unidades.
//   TODO: public function totalSinIva(): float
//   TODO: public function totalProductos(): float
//   TODO: public function desgloseIva(): array   -> Base imponible y cuota de IVA agrupadas por tipo de IVA:
//   TODO: public function esEnviable(): bool   -> El pedido se puede enviar si TODOS los productos implementan la interfaz Enviable.
//   TODO: public function pesoTotal(): float
//   TODO: public function gastosEnvio(): ?float   -> Gastos de envío, o null si el pedido no se puede enviar (solo recogida en tienda).
//   TODO: public function total(): float
//   TODO: public function __wakeup(): void   -> Tras deserializar, comprobamos que el array de líneas no se ha manipulado: allowed_classes
//   TODO: public function serializar(): string   -> Convierte el carrito en un texto que se puede guardar en un campo oculto.
//   TODO: public static function desdeTexto(string $texto): ?Carrito   -> Reconstruye el carrito a partir del texto. Devuelve null si el texto no es válido.

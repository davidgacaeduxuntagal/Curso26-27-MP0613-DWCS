<?php
/*
 * Producto.php
 *  Clase ABSTRACTA con lo común a todos los productos de la tienda. Cada tipo de producto decide
 *  su tipo de IVA, su categoría y la información extra que muestra.
 */
// TODO: abstract class Producto
//   TODO: protected string $codigo;
//   TODO: protected string $nombre;
//   TODO: protected float $precio;
//   TODO: public function __construct(string $codigo, string $nombre, float $precio)
//   TODO: public function getCodigo(): string
//   TODO: public function getNombre(): string
//   TODO: abstract public function tipoIva(): float;   -> Tipo de IVA en tanto por uno (0.21 = 21 %).
//   TODO: abstract public function categoria(): string;
//   TODO: public function precioSinIva(): float   -> Precio unitario sin IVA. Las subclases lo redefinen si aplican descuentos o recargos.
//   TODO: public function detalles(): string   -> Información propia de cada tipo de producto (autor, caducidad, garantía...).
//   TODO: public function precioConIva(): float
//   TODO: public function __toString(): string
//   TODO: public static function formato(float $importe): string   -> Importe con 2 decimales y coma decimal.

<?php
/*
 * Catalogo.php
 *  Catálogo de la tienda. Todo es ESTÁTICO: hay un único catálogo para toda la aplicación y no
 *  tiene sentido crear objetos Catalogo (por eso el constructor es privado y la clase es final).
 *  En un tema posterior estos datos vendrán de una base de datos.
 */
// TODO: require_once __DIR__ . '/Libro.php';
// TODO: require_once __DIR__ . '/Alimento.php';
// TODO: require_once __DIR__ . '/Electronica.php';

// TODO: final class Catalogo
//   TODO: private static ?array $productos = null;
//   TODO: private function __construct()
//   TODO: private static function cargar(): void   -> Crea los productos la primera vez que se necesitan ("carga perezosa").
//   TODO: public static function todos(): array
//   TODO: public static function buscar(string $codigo): ?Producto
//   TODO: public static function porCategoria(): array   -> Productos agrupados por categoría: ['Libros' => [...], 'Alimentación' => [...], ...]

<?php
/*
 * Catalogo.php
 *  Catálogo de la tienda. Todo es ESTÁTICO: hay un único catálogo para toda la aplicación y no
 *  tiene sentido crear objetos Catalogo (por eso el constructor es privado y la clase es final).
 *  En un tema posterior estos datos vendrán de una base de datos.
 */
require_once __DIR__ . '/Libro.php';
require_once __DIR__ . '/Alimento.php';
require_once __DIR__ . '/Electronica.php';

final class Catalogo
{
    /** @var Producto[]|null  código => producto (null hasta que se carga por primera vez) */
    private static ?array $productos = null;

    private function __construct()
    {
    }

    /**
     * Crea los productos la primera vez que se necesitan ("carga perezosa").
     */
    private static function cargar(): void
    {
        if (self::$productos !== null) {
            return;
        }
        $enDosDias   = date('Y-m-d', strtotime('+2 days'));    // para que siempre haya alguna oferta
        $enUnMes     = date('Y-m-d', strtotime('+30 days'));
        $lista = [
            new Libro('LIB001', 'Programación en PHP', 28.85, 'M. García', 0.9),
            new Libro('LIB002', 'Follas novas', 14.40, 'Rosalía de Castro', 0.4),
            new Libro('LIB003', 'Aprende SQL', 22.10, 'L. Pérez', 0.7),
            new Alimento('ALI001', 'Queso de tetilla', 9.09, $enDosDias),
            new Alimento('ALI002', 'Empanada de zamburiñas', 13.50, $enUnMes),
            new Alimento('ALI003', 'Pan de Cea', 2.27, $enDosDias),
            new Electronica('ELE001', 'Ratón inalámbrico', 14.88, 2, 0.1),
            new Electronica('ELE002', 'Teclado mecánico', 49.59, 3, 1.1),
            new Electronica('ELE003', 'Monitor 24"', 123.97, 3, 4.5),
        ];
        self::$productos = [];
        foreach ($lista as $producto) {
            self::$productos[$producto->getCodigo()] = $producto;
        }
    }

    /**
     * @return Producto[]
     */
    public static function todos(): array
    {
        self::cargar();
        return self::$productos;
    }

    public static function buscar(string $codigo): ?Producto
    {
        self::cargar();
        return self::$productos[$codigo] ?? null;
    }

    /**
     * Productos agrupados por categoría: ['Libros' => [...], 'Alimentación' => [...], ...]
     */
    public static function porCategoria(): array
    {
        $grupos = [];
        foreach (self::todos() as $producto) {
            $grupos[$producto->categoria()][] = $producto;
        }
        return $grupos;
    }
}

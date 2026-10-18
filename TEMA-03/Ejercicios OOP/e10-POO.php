<?php
/*
 * e10-POO.php
 *  POO (10): ESPACIOS DE NOMBRES (namespace) Y CARGA AUTOMÁTICA DE CLASES (autoload)
 *  Contenidos: una clase por fichero, require_once, spl_autoload_register, el estándar PSR-4,
 *              namespace, use, alias (use ... as), nombres completos (\Tienda\Modelo\Producto),
 *              ::class con espacios de nombres, el espacio de nombres global (\DateTime),
 *              spl_autoload_functions, class_exists con y sin autoload, Composer
 *  (apartado 14 de los apuntes)
 *
 *  Las clases de este ejemplo están en la carpeta e10-src, organizadas según PSR-4:
 *      e10-src/Tienda/Modelo/Producto.php     ->  clase Tienda\Modelo\Producto
 *      e10-src/Tienda/Modelo/Cliente.php      ->  clase Tienda\Modelo\Cliente
 *      e10-src/Tienda/Servicio/Carrito.php    ->  clase Tienda\Servicio\Carrito
 *      e10-src/Almacen/Producto.php           ->  clase Almacen\Producto (¡otra clase Producto!)
 *
 *  Cómo trabajar este fichero:
 *   1. Pon un punto de ruptura DENTRO de la función de autoload (cargarClase) y ejecuta en modo
 *      depuración: verás que PHP la llama él solo, justo la primera vez que se usa cada clase, y
 *      qué nombre de clase recibe.
 *   2. Mira la salida: los mensajes "[autoload]" indican en qué momento se carga cada fichero.
 */
require_once __DIR__ . '/utilidades.php';

// use se pone al principio: importa nombres para poder escribirlos cortos. NO carga nada todavía.
use Tienda\Modelo\Producto;
use Tienda\Modelo\Cliente;
use Tienda\Servicio\Carrito;
use Almacen\Producto as ProductoAlmacen;      // alias: hay dos clases llamadas Producto

// ---------------------------------------------------------------------------------------------
// spl_autoload_register — Cargar las clases automáticamente (estilo PSR-4)
// ENLACE: https://www.php.net/manual/es/function.spl-autoload-register.php
// ENLACE: https://www.php-fig.org/psr/psr-4/
// ENUNCIADO EJEMPLO: no tener que escribir un require_once por cada clase de la tienda.
// NOTA: cuando el código usa una clase que PHP todavía no conoce, PHP llama a las funciones
//       registradas con spl_autoload_register pasándoles el nombre COMPLETO de la clase
//       (con su espacio de nombres). La función convierte ese nombre en una ruta de fichero y lo
//       incluye. La antigua función __autoload() se ELIMINÓ en PHP 8: no la uses.
//       En proyectos reales, este autoloader lo genera Composer (ver el final del fichero).
// ---------------------------------------------------------------------------------------------
titulo('Autoload');
function cargarClase(string $clase): void
{
    // "Tienda\Modelo\Producto"  ->  ".../e10-src/Tienda/Modelo/Producto.php"
    $fichero = __DIR__ . '/e10-src/' . str_replace('\\', '/', $clase) . '.php';
    echo "  [autoload] $clase -> ", basename(dirname($fichero)), '/', basename($fichero), nl();
    if (is_file($fichero)) {
        require $fichero;
    }
    // Si no existe, no hacemos nada: PHP probará con el siguiente autoloader o dará el error
}
spl_autoload_register('cargarClase');
ver('autoloaders registrados', count(spl_autoload_functions()));    // 1

ver('¿Producto cargada? (sin autoload)', class_exists(Producto::class, false));   // false: aún no se ha usado
$raton = new Producto('Ratón', 19.95);           // aquí se dispara el autoload de Tienda\Modelo\Producto
$teclado = new Producto('Teclado', 49.50);       // ya está cargada: no se vuelve a cargar
ver('¿Producto cargada?', class_exists(Producto::class, false));                  // true

// ---------------------------------------------------------------------------------------------
// Nombres completos, ::class y alias
// ENLACE: https://www.php.net/manual/es/language.namespaces.importing.php
// ENUNCIADO EJEMPLO: usar a la vez el Producto de la tienda y el Producto del almacén.
// NOTA: el nombre COMPLETO de una clase incluye su espacio de nombres. Producto::class devuelve
//       ese nombre completo (sin cargar la clase). Con "use ... as" se le da otro nombre corto.
// ---------------------------------------------------------------------------------------------
titulo('Nombres completos y alias');
ver('Producto::class', Producto::class);                   // "Tienda\Modelo\Producto"
ver('ProductoAlmacen::class', ProductoAlmacen::class);     // "Almacen\Producto"
$enAlmacen = new ProductoAlmacen('RAT-001', 120);          // autoload de Almacen\Producto
ver('get_class($raton)', get_class($raton));               // "Tienda\Modelo\Producto"
ver('get_class($enAlmacen)', get_class($enAlmacen));       // "Almacen\Producto"
$sinUse = new \Tienda\Modelo\Producto('Monitor', 179.0);   // nombre completo, sin necesidad de use
ver('¿misma clase?', $sinUse instanceof Producto);         // true

// ---------------------------------------------------------------------------------------------
// Clases que usan otras clases: la carga en cadena
// ENLACE: https://www.php.net/manual/es/language.namespaces.php
// ENUNCIADO EJEMPLO: el carrito usa Cliente y Producto, que están en otro espacio de nombres.
// NOTA: abre e10-src/Tienda/Servicio/Carrito.php: tiene su propio namespace, sus propios use y
//       usa \Countable (con \ porque está en el espacio de nombres global). Fíjate en el orden en
//       que aparecen los mensajes de autoload.
// ---------------------------------------------------------------------------------------------
titulo('Carga en cadena');
$cliente = new Cliente('Ana');                   // autoload de Tienda\Modelo\Cliente
$carrito = new Carrito($cliente);                // autoload de Tienda\Servicio\Carrito
$carrito->anadir($raton);
$carrito->anadir($teclado);
ver('resumen', $carrito->resumen());             // "Ana: 2 productos, 69.45 €"
ver('carrito', $carrito);
ver('fecha de alta (DateTimeImmutable global)', $cliente->alta->format('d/m/Y'));   // "16/10/2026"

// ---------------------------------------------------------------------------------------------
// Clases que no existen
// ENLACE: https://www.php.net/manual/es/function.class-exists.php
// ENUNCIADO EJEMPLO: comprobar si existe una clase antes de usarla.
// NOTA: class_exists('X') intenta cargarla con el autoload; class_exists('X', false) no.
//       Si se usa una clase que ningún autoloader encuentra: Error "Class ... not found".
// ---------------------------------------------------------------------------------------------
titulo('Clases que no existen');
ver('class_exists Tienda\Modelo\Factura', class_exists('Tienda\Modelo\Factura'));   // false (después de intentar el autoload)
verError('new Factura', fn() => new \Tienda\Modelo\Factura());    // Error: Class "Tienda\Modelo\Factura" not found

// ---------------------------------------------------------------------------------------------
// Composer: el autoload en los proyectos reales
// ENLACE: https://getcomposer.org/doc/01-basic-usage.md#autoloading
// ENUNCIADO EJEMPLO: cómo se haría lo mismo con Composer.
// NOTA: en un proyecto con Composer, el fichero composer.json indica qué espacio de nombres está
//       en qué carpeta:
//           { "autoload": { "psr-4": { "Tienda\\": "src/Tienda/", "Almacen\\": "src/Almacen/" } } }
//       después se ejecuta "composer dump-autoload" y en el código basta con:
//           require __DIR__ . '/vendor/autoload.php';
//       Composer genera un autoloader equivalente a cargarClase() (y carga también las
//       bibliotecas que instales).
// ---------------------------------------------------------------------------------------------
titulo('Composer');
ver('así se haría', "require __DIR__ . '/vendor/autoload.php';");

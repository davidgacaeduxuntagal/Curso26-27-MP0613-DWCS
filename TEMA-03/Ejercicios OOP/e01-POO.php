<?php
/*
 * e01-POO.php
 *  POO (1): CLASES Y OBJETOS
 *  Contenidos: class, new, propiedades (tipadas y con valor por defecto), métodos, $this,
 *              constructor __construct, promoción de propiedades en el constructor (PHP 8),
 *              argumentos con nombre, operador nullsafe ?->, propiedades dinámicas (obsoletas en
 *              PHP 8.2), stdClass, funciones de utilidad (get_class, method_exists,
 *              property_exists, get_object_vars, get_class_methods, instanceof)
 *  (apartados 1 y 2 de los apuntes)
 *
 *  Cómo trabajar este fichero:
 *   1. Pon un punto de ruptura en la primera línea de código y ejecuta en modo depuración.
 *   2. Avanza con F10. Después de cada "new", despliega el objeto en el panel VARIABLES: verás
 *      su clase y el valor de cada propiedad. Usa F11 para entrar en los métodos y ver $this.
 *   3. Los comentarios a la derecha indican el resultado esperado.
 */
require_once __DIR__ . '/utilidades.php';

// ---------------------------------------------------------------------------------------------
// class — Definir una clase (el "plano") y new — crear objetos (las "casas construidas")
// ENLACE: https://www.php.net/manual/es/language.oop5.basic.php
// ENUNCIADO EJEMPLO: una tienda online necesita representar sus productos.
// NOTA: una clase define las PROPIEDADES (datos) y los MÉTODOS (funciones) que tendrán sus
//       objetos. Cada objeto tiene sus propios valores. Por convenio, el nombre de la clase
//       empieza por mayúscula (PascalCase) y se guarda en un fichero con el mismo nombre.
// ---------------------------------------------------------------------------------------------
titulo('class y new');
class ProductoBasico
{
    public string $nombre = 'Sin nombre';     // propiedad con tipo y valor por defecto
    public float $precio = 0.0;
    public int $unidades = 0;

    public function valorStock(): float          // método: usa los datos DEL objeto con $this
    {
        return round($this->precio * $this->unidades, 2);   // redondeamos: los float no son exactos
    }
}

$movil = new ProductoBasico();                   // primer objeto (instancia)
$movil->nombre = 'Samsung Galaxy A55';           // -> accede a propiedades y métodos
$movil->precio = 379.90;
$movil->unidades = 12;

$cascos = new ProductoBasico();                  // segundo objeto, con sus propios valores
$cascos->nombre = 'Auriculares Bluetooth';
$cascos->precio = 29.99;
$cascos->unidades = 40;

ver('móvil', $movil);
ver('valor del stock del móvil', $movil->valorStock());     // 4558.8
ver('valor del stock de los cascos', $cascos->valorStock()); // 1199.6
ver('clase', get_class($movil));                            // "ProductoBasico"
ver('¿es un ProductoBasico?', $movil instanceof ProductoBasico);   // true

// ---------------------------------------------------------------------------------------------
// __construct — El constructor
// ENLACE: https://www.php.net/manual/es/language.oop5.decon.php
// ENUNCIADO EJEMPLO: obligar a que todo producto se cree con nombre y precio válidos.
// NOTA: se ejecuta automáticamente al hacer new. Sirve para que el objeto nazca en un estado
//       CORRECTO (en el ejemplo anterior, un producto podía existir sin nombre). Si los datos no
//       son válidos, lanza una excepción y el objeto no se crea.
// ---------------------------------------------------------------------------------------------
titulo('__construct');
class Producto
{
    public string $nombre;
    public float $precio;
    public int $unidades;

    public function __construct(string $nombre, float $precio, int $unidades = 0)
    {
        if (trim($nombre) === '' || $precio < 0) {
            throw new InvalidArgumentException('Nombre vacío o precio negativo');
        }
        $this->nombre = trim($nombre);
        $this->precio = $precio;
        $this->unidades = $unidades;
    }

    public function valorStock(): float
    {
        return $this->precio * $this->unidades;
    }

    public function vender(int $cantidad): bool
    {
        if ($cantidad > $this->unidades) {
            return false;                 // no hay suficientes
        }
        $this->unidades -= $cantidad;     // el método cambia el ESTADO del objeto
        return true;
    }
}
$tablet = new Producto(' iPad Air ', 699.0, 5);
ver('tablet', $tablet);                                  // nombre "iPad Air" (sin espacios), unidades 5
ver('vender 2', $tablet->vender(2));                     // true
ver('quedan', $tablet->unidades);                        // 3
ver('vender 10', $tablet->vender(10));                   // false
verError('precio negativo', fn() => new Producto('Funda', -5));   // InvalidArgumentException
verError('sin argumentos', fn() => new Producto());       // ArgumentCountError: Too few arguments...

// ---------------------------------------------------------------------------------------------
// Promoción de propiedades en el constructor (PHP 8.0) y argumentos con nombre
// ENLACE: https://www.php.net/manual/es/language.oop5.decon.php#language.oop5.decon.constructor.promotion
// ENLACE: https://www.php.net/manual/es/functions.arguments.php#functions.named-arguments
// ENUNCIADO EJEMPLO: la clase Cliente, escrita de la forma corta.
// NOTA: poner la visibilidad delante de un parámetro del constructor declara la propiedad Y le
//       asigna el valor: equivale a las 3 partes de la clase anterior (declarar, recibir y
//       asignar). Los argumentos con nombre permiten pasar solo los que interesan, en cualquier
//       orden.
// ---------------------------------------------------------------------------------------------
titulo('Promoción de propiedades');
class Cliente
{
    public function __construct(
        public string $nombre,
        public string $email,
        public ?string $telefono = null,     // ?string: puede ser null
        public bool $vip = false,
    ) {
    }
}
$ana = new Cliente('Ana Pérez', 'ana@example.com');
$brais = new Cliente(email: 'brais@example.com', nombre: 'Brais Otero', vip: true);   // con nombre
ver('ana', $ana);           // telefono null, vip false
ver('brais', $brais);       // telefono null, vip true

// ---------------------------------------------------------------------------------------------
// Objetos dentro de objetos y el operador nullsafe ?->  (PHP 8.0)
// ENLACE: https://www.php.net/manual/es/language.oop5.basic.php#language.oop5.basic.nullsafe
// ENUNCIADO EJEMPLO: un pedido tiene un cliente, y el cliente puede tener (o no) una dirección.
// NOTA: una propiedad puede contener otro objeto (composición). Con ?-> si lo de la izquierda es
//       null, el resultado es null en lugar de un error.
// ---------------------------------------------------------------------------------------------
titulo('Objetos dentro de objetos y ?->');
class Direccion
{
    public function __construct(public string $calle, public string $ciudad, public string $cp)
    {
    }
}
class ClienteConDireccion
{
    public function __construct(public string $nombre, public ?Direccion $direccion = null)
    {
    }
}
class Pedido
{
    public array $lineas = [];

    public function __construct(public int $numero, public ClienteConDireccion $cliente)
    {
    }

    public function anadir(Producto $producto, int $cantidad): void
    {
        $this->lineas[] = ['producto' => $producto, 'cantidad' => $cantidad];
    }

    public function total(): float
    {
        $total = 0;
        foreach ($this->lineas as $linea) {
            $total += $linea['producto']->precio * $linea['cantidad'];
        }
        return round($total, 2);
    }
}
$carmen = new ClienteConDireccion('Carmen', new Direccion('Rúa do Porto, 5', 'Baio', '15147'));
$diego = new ClienteConDireccion('Diego');                       // sin dirección
$pedido = new Pedido(1001, $carmen);
$pedido->anadir(new Producto('Ratón', 19.95, 30), 2);
$pedido->anadir(new Producto('Teclado', 49.50, 10), 1);
ver('pedido', $pedido);                                          // despliégalo en VARIABLES: objetos dentro de objetos
ver('total', $pedido->total());                                  // 89.4
ver('ciudad del cliente', $pedido->cliente->direccion->ciudad);  // "Baio" (encadenando ->)
ver('ciudad de Diego con ?->', $diego->direccion?->ciudad);     // NULL (sin error)
verError('ciudad de Diego con ->', fn() => $diego->direccion->ciudad);   // Warning: Attempt to read property "ciudad" on null

// ---------------------------------------------------------------------------------------------
// Propiedades dinámicas (obsoletas desde PHP 8.2) y stdClass
// ENLACE: https://www.php.net/manual/es/migration82.deprecated.php
// ENLACE: https://www.php.net/manual/es/class.stdclass.php
// ENUNCIADO EJEMPLO: un error de escritura crea una propiedad nueva sin querer.
// NOTA: antes PHP creaba en silencio cualquier propiedad que no estuviera declarada. Desde PHP
//       8.2 avisa con "Deprecated" (y en PHP 9 será un error): así se detectan las erratas.
//       stdClass es una clase vacía de PHP que SÍ admite propiedades dinámicas (es la que crea
//       json_decode). Si de verdad necesitas una clase propia con propiedades dinámicas, márcala
//       con el atributo #[AllowDynamicProperties] (ver e13).
// ---------------------------------------------------------------------------------------------
titulo('Propiedades dinámicas y stdClass');
$funda = new Producto('Funda', 9.95);
verError('errata: $funda->precioo = 12', function () use ($funda) {
    $funda->precioo = 12;                 // ¡precioo con dos oes!
});   // ErrorException: Creation of dynamic property Producto::$precioo is deprecated
// (y además la propiedad SE HA CREADO: despliega $funda en VARIABLES)
ver('¿existe precioo?', property_exists($funda, 'precioo'));   // true
$config = new stdClass();
$config->tienda = 'Tecno Baio';           // en stdClass no hay aviso
$config->iva = 0.21;
ver('stdClass', $config);
ver('desde JSON', json_decode('{"envio": 4.95, "gratisDesde": 50}'));   // stdClass Object

// ---------------------------------------------------------------------------------------------
// Funciones de utilidad para clases y objetos
// ENLACE: https://www.php.net/manual/es/ref.classobj.php
// ENUNCIADO EJEMPLO: averiguar qué tiene un objeto (útil para depurar).
// NOTA: get_object_vars y get_class_methods solo devuelven lo que es ACCESIBLE desde donde se
//       llaman. method_exists y property_exists, en cambio, encuentran también lo privado.
// ---------------------------------------------------------------------------------------------
titulo('Funciones de utilidad');
ver('get_class', get_class($pedido));                          // "Pedido"
ver('Pedido::class', Pedido::class);                           // "Pedido" (sin crear objetos)
ver('class_exists', class_exists('Factura'));                  // false
ver('method_exists', method_exists($pedido, 'total'));         // true
ver('property_exists', property_exists('Producto', 'precio')); // true
ver('get_object_vars', get_object_vars($tablet));              // ['nombre' => 'iPad Air', 'precio' => 699.0, 'unidades' => 3]
ver('get_class_methods', get_class_methods('Producto'));       // ['__construct', 'valorStock', 'vender']

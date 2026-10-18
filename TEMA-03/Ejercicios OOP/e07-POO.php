<?php
/*
 * e07-POO.php
 *  POO (7): MÉTODOS MÁGICOS Y SERIALIZACIÓN
 *  Contenidos: __construct / __destruct, __toString, __get / __set / __isset / __unset,
 *              __call / __callStatic, __invoke, __clone (visto en e02), serialize /
 *              unserialize, __serialize / __unserialize (y __sleep / __wakeup), allowed_classes,
 *              __debugInfo
 *  (apartado 10 de los apuntes)
 *
 *  Cómo trabajar este fichero:
 *   1. Pon un punto de ruptura DENTRO de cada método mágico y ejecuta en modo depuración: nadie
 *      los llama de forma explícita; PHP los llama solo cuando ocurre algo (leer una propiedad
 *      que no existe, usar el objeto como texto, etc.). En la PILA DE LLAMADAS verás desde qué
 *      línea se han disparado.
 *   2. Los comentarios a la derecha indican el resultado esperado.
 */
require_once __DIR__ . '/utilidades.php';

// ---------------------------------------------------------------------------------------------
// __construct y __destruct
// ENLACE: https://www.php.net/manual/es/language.oop5.decon.php
// ENUNCIADO EJEMPLO: un objeto que abre un fichero de log al crearse y lo cierra al destruirse.
// NOTA: __destruct se ejecuta cuando ya no queda ninguna variable que apunte al objeto (al hacer
//       unset, al asignar null o al terminar el script). Se usa poco: para liberar recursos.
// ---------------------------------------------------------------------------------------------
titulo('__construct y __destruct');
class RegistroTemporal
{
    private $fichero;

    public function __construct(private string $nombre)
    {
        $this->fichero = fopen('php://memory', 'w+');
        echo "  (se abre el registro $nombre)", nl();
    }

    public function escribir(string $linea): void
    {
        fwrite($this->fichero, $linea . "\n");
    }

    public function __destruct()
    {
        fclose($this->fichero);
        echo "  (se cierra el registro $this->nombre)", nl();
    }
}
$registro = new RegistroTemporal('ventas');    // "(se abre el registro ventas)"
$registro->escribir('venta 1');
unset($registro);                               // "(se cierra el registro ventas)" ¡en esta línea!
ver('después del unset', 'el objeto ya no existe');

// ---------------------------------------------------------------------------------------------
// __toString — El objeto como texto
// ENLACE: https://www.php.net/manual/es/language.oop5.magic.php#object.tostring
// ENUNCIADO EJEMPLO: mostrar un precio con formato español solo con echo.
// NOTA: se llama al usar el objeto donde se espera un string (echo, concatenar, (string)...).
//       Una clase con __toString implementa automáticamente la interfaz Stringable.
// ---------------------------------------------------------------------------------------------
titulo('__toString');
class Precio
{
    public function __construct(private float $importe)
    {
    }

    public function __toString(): string
    {
        return number_format($this->importe, 2, ',', '.') . ' €';
    }
}
$precio = new Precio(1234.5);
ver('concatenado', 'Total: ' . $precio);              // "Total: 1.234,50 €"
ver('¿Stringable?', $precio instanceof Stringable);  // true

// ---------------------------------------------------------------------------------------------
// __get, __set, __isset, __unset — Propiedades "virtuales"
// ENLACE: https://www.php.net/manual/es/language.oop5.overloading.php#language.oop5.overloading.members
// ENUNCIADO EJEMPLO: la configuración de la tienda, guardada en un array, a la que se accede
//                   como si fueran propiedades ($config->iva), comprobando las claves válidas.
// NOTA: PHP llama a __get/__set cuando se lee/escribe una propiedad que NO existe o NO es
//       accesible desde ese sitio. Son útiles en casos concretos, pero el IDE no puede
//       autocompletarlas y son más lentas: no los uses para sustituir a getters y setters
//       normales.
// ---------------------------------------------------------------------------------------------
titulo('__get, __set, __isset, __unset');
class Configuracion
{
    private const CLAVES = ['iva', 'envio', 'moneda', 'gratisDesde'];
    private array $valores = ['iva' => 0.21, 'moneda' => 'EUR'];

    public function __get(string $clave): mixed
    {
        if (!in_array($clave, self::CLAVES, true)) {
            throw new OutOfRangeException("Clave de configuración desconocida: $clave");
        }
        return $this->valores[$clave] ?? null;
    }

    public function __set(string $clave, mixed $valor): void
    {
        if (!in_array($clave, self::CLAVES, true)) {
            throw new OutOfRangeException("Clave de configuración desconocida: $clave");
        }
        $this->valores[$clave] = $valor;
    }

    public function __isset(string $clave): bool
    {
        return isset($this->valores[$clave]);
    }

    public function __unset(string $clave): void
    {
        unset($this->valores[$clave]);
    }
}
$config = new Configuracion();
$config->envio = 4.95;                           // llama a __set('envio', 4.95)
ver('iva', $config->iva);                        // llama a __get('iva'): 0.21
ver('envio', $config->envio);                    // 4.95
ver('isset gratisDesde', isset($config->gratisDesde));   // false (__isset)
unset($config->envio);                           // __unset
ver('isset envio', isset($config->envio));       // false
verError('clave errónea', fn() => $config->ivaa);        // OutOfRangeException: Clave de configuración desconocida: ivaa
ver('config', $config);

// ---------------------------------------------------------------------------------------------
// __call y __callStatic — Métodos "virtuales"
// ENLACE: https://www.php.net/manual/es/language.oop5.overloading.php#language.oop5.overloading.methods
// ENUNCIADO EJEMPLO: un repositorio de productos que entiende buscarPorMarca('Sony'),
//                   buscarPorCategoria('tv')... sin escribir un método para cada campo.
// NOTA: __call recibe el nombre del método inexistente y un array con los argumentos.
//       __callStatic hace lo mismo con llamadas estáticas (Clase::metodo()).
// ---------------------------------------------------------------------------------------------
titulo('__call y __callStatic');
class RepositorioProductos
{
    public function __construct(private array $productos)
    {
    }

    public function __call(string $metodo, array $argumentos): array
    {
        if (str_starts_with($metodo, 'buscarPor')) {
            $campo = lcfirst(substr($metodo, strlen('buscarPor')));     // buscarPorMarca -> "marca"
            return array_values(array_filter($this->productos, fn($p) => ($p[$campo] ?? null) === $argumentos[0]));
        }
        throw new BadMethodCallException("Método desconocido: $metodo");
    }

    public static function __callStatic(string $metodo, array $argumentos): static
    {
        if ($metodo === 'desdeJson') {
            return new static(json_decode($argumentos[0], true));
        }
        throw new BadMethodCallException("Método estático desconocido: $metodo");
    }
}
$repo = RepositorioProductos::desdeJson('[{"nombre":"TV 50","marca":"Sony","categoria":"tv"},{"nombre":"Cascos","marca":"Sony","categoria":"audio"},{"nombre":"TV 43","marca":"LG","categoria":"tv"}]');
ver('buscarPorMarca("Sony")', array_column($repo->buscarPorMarca('Sony'), 'nombre'));   // ['TV 50', 'Cascos']
ver('buscarPorCategoria("tv")', array_column($repo->buscarPorCategoria('tv'), 'nombre'));   // ['TV 50', 'TV 43']
verError('borrarTodo()', fn() => $repo->borrarTodo());    // BadMethodCallException: Método desconocido: borrarTodo

// ---------------------------------------------------------------------------------------------
// __invoke — Usar un objeto como si fuera una función
// ENLACE: https://www.php.net/manual/es/language.oop5.magic.php#object.invoke
// ENUNCIADO EJEMPLO: un objeto "descuento" configurable que se pasa a array_map.
// NOTA: un objeto con __invoke es "callable": se puede llamar con $obj(...) y pasar donde se
//       espera una función (array_map, usort, array_filter...).
// ---------------------------------------------------------------------------------------------
titulo('__invoke');
class Descuento
{
    public function __construct(private float $porcentaje)
    {
    }

    public function __invoke(float $precio): float
    {
        return round($precio * (1 - $this->porcentaje / 100), 2);
    }
}
$blackFriday = new Descuento(20);
ver('$blackFriday(50)', $blackFriday(50));                   // 40.0
ver('con array_map', array_map($blackFriday, [10, 19.99, 100]));   // [8.0, 15.99, 80.0]
ver('¿callable?', is_callable($blackFriday));                // true

// ---------------------------------------------------------------------------------------------
// serialize / unserialize y __serialize / __unserialize
// ENLACE: https://www.php.net/manual/es/function.serialize.php
// ENLACE: https://www.php.net/manual/es/language.oop5.magic.php#object.serialize
// ENUNCIADO EJEMPLO: guardar el carrito en un campo oculto o en un fichero y recuperarlo, pero
//                   sin guardar la conexión al servicio de pagos (que no se puede serializar).
// NOTA: serialize convierte el objeto en un texto (con su clase y sus propiedades); unserialize
//       lo reconstruye. __serialize devuelve un array con lo que se debe guardar y __unserialize
//       recibe ese array y rehace el objeto (sustituyen a __sleep y __wakeup, más antiguos).
//       SEGURIDAD: no hagas unserialize de datos que vengan del usuario sin allowed_classes: se
//       podrían crear objetos de cualquier clase. Para intercambiar datos, mejor JSON.
// ---------------------------------------------------------------------------------------------
titulo('Serialización');
class CarritoGuardable
{
    private array $lineas = [];
    private $conexionPagos;                         // un recurso: no se puede (ni debe) guardar

    public function __construct(public string $cliente)
    {
        $this->conectar();
    }

    private function conectar(): void
    {
        $this->conexionPagos = fopen('php://memory', 'r');
    }

    public function anadir(string $producto, int $cantidad): void
    {
        $this->lineas[$producto] = ($this->lineas[$producto] ?? 0) + $cantidad;
    }

    public function __serialize(): array            // qué se guarda
    {
        return ['cliente' => $this->cliente, 'lineas' => $this->lineas];
    }

    public function __unserialize(array $datos): void   // cómo se reconstruye
    {
        $this->cliente = $datos['cliente'];
        $this->lineas = $datos['lineas'];
        $this->conectar();                          // se vuelve a abrir lo que no se guardó
    }
}
$carrito = new CarritoGuardable('Ana');
$carrito->anadir('Ratón', 2);
$texto = serialize($carrito);
ver('serializado', $texto);     // O:16:"CarritoGuardable":2:{s:7:"cliente";s:3:"Ana";s:6:"lineas";a:1:{s:6:"Ratón";i:2;}}
$recuperado = unserialize($texto, ['allowed_classes' => [CarritoGuardable::class]]);
ver('recuperado', $recuperado);
ver('¿mismo objeto?', $recuperado === $carrito);    // false: es una copia nueva
$soloArrays = unserialize($texto, ['allowed_classes' => false]);
ver('con allowed_classes false', get_class($soloArrays));   // "__PHP_Incomplete_Class": no se crea la clase real

// ---------------------------------------------------------------------------------------------
// __debugInfo — Qué muestra var_dump
// ENLACE: https://www.php.net/manual/es/language.oop5.magic.php#object.debuginfo
// ENUNCIADO EJEMPLO: que var_dump de un usuario no muestre el hash de su contraseña.
// ---------------------------------------------------------------------------------------------
titulo('__debugInfo');
class Usuario
{
    public function __construct(public string $email, private string $hash)
    {
    }

    public function __debugInfo(): array
    {
        return ['email' => $this->email, 'hash' => '***'];
    }
}
var_dump(new Usuario('ana@example.com', password_hash('secreta', PASSWORD_DEFAULT)));   // hash => "***"

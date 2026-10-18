<?php
/*
 * e05-POO.php
 *  POO (5): INTERFACES Y TRAITS
 *  Contenidos: interface, implements (varias), constantes en interfaces, herencia de
 *              interfaces, interfaces como tipo, interfaces predefinidas (Countable, Stringable,
 *              JsonSerializable), class_implements, trait, use, varios traits, conflictos
 *              (insteadof, as), métodos abstractos y propiedades en traits
 *  (apartados 7 y 8 de los apuntes)
 *
 *  Cómo trabajar este fichero:
 *   1. Pon un punto de ruptura en la primera línea de código y ejecuta en modo depuración.
 *   2. Con F11 sobre las llamadas a métodos de un trait verás que el depurador salta al código
 *      del trait: es como si ese código se hubiera "copiado" dentro de la clase.
 *   3. Los comentarios a la derecha indican el resultado esperado.
 */
require_once __DIR__ . '/utilidades.php';

// ---------------------------------------------------------------------------------------------
// interface e implements — Un "contrato" que las clases se comprometen a cumplir
// ENLACE: https://www.php.net/manual/es/language.oop5.interfaces.php
// ENUNCIADO EJEMPLO: la tienda puede cobrar con tarjeta, con Bizum o con PayPal. El proceso de
//                   compra no debe depender de cuál se use.
// NOTA: una interfaz solo declara métodos públicos (sin código) y constantes; no tiene
//       propiedades. La clase que la implementa está OBLIGADA a escribir todos sus métodos.
//       Clases que no tienen nada que ver entre sí pueden implementar la misma interfaz.
// ---------------------------------------------------------------------------------------------
titulo('interface');
interface MetodoPago
{
    public const MONEDA = 'EUR';                        // las interfaces pueden tener constantes

    public function cobrar(float $importe): string;     // devuelve el código de la operación

    public function nombre(): string;
}

class PagoTarjeta implements MetodoPago
{
    public function __construct(private string $numero)
    {
    }

    public function cobrar(float $importe): string
    {
        return 'TPV-' . substr($this->numero, -4) . '-' . number_format($importe, 2, '', '');
    }

    public function nombre(): string
    {
        return 'Tarjeta acabada en ' . substr($this->numero, -4);
    }
}

class PagoBizum implements MetodoPago
{
    public function __construct(private string $telefono)
    {
    }

    public function cobrar(float $importe): string
    {
        if ($importe > 1000) {
            throw new DomainException('Bizum: máximo 1000 € por operación');
        }
        return 'BZM-' . $this->telefono . '-' . number_format($importe, 2, '', '');
    }

    public function nombre(): string
    {
        return 'Bizum ' . $this->telefono;
    }
}

// La función recibe "cualquier cosa que sea un MetodoPago": no sabe (ni le importa) cuál
function finalizarCompra(float $total, MetodoPago $pago): string
{
    return 'Cobrados ' . $total . ' ' . MetodoPago::MONEDA . ' con ' . $pago->nombre()
        . ' (operación ' . $pago->cobrar($total) . ')';
}
ver('con tarjeta', finalizarCompra(59.90, new PagoTarjeta('4111111111111111')));
// "Cobrados 59.9 EUR con Tarjeta acabada en 1111 (operación TPV-1111-5990)"
ver('con Bizum', finalizarCompra(12.50, new PagoBizum('600123456')));
// "Cobrados 12.5 EUR con Bizum 600123456 (operación BZM-600123456-1250)"
verError('Bizum de 1500 €', fn() => finalizarCompra(1500, new PagoBizum('600123456')));   // DomainException
verError('pasar otra cosa', fn() => finalizarCompra(10, 'efectivo'));   // TypeError: ... must be of type MetodoPago, string given
verError('new MetodoPago', fn() => new MetodoPago());                   // Error: Cannot instantiate interface MetodoPago

// ---------------------------------------------------------------------------------------------
// Varias interfaces e interfaces predefinidas: Countable, Stringable, JsonSerializable
// ENLACE: https://www.php.net/manual/es/class.countable.php
// ENLACE: https://www.php.net/manual/es/class.stringable.php
// ENLACE: https://www.php.net/manual/es/class.jsonserializable.php
// ENUNCIADO EJEMPLO: un carrito al que se le pueda aplicar count(), que se pueda mostrar con echo
//                   y que se pueda enviar en JSON a la app del móvil.
// NOTA: una clase hereda de UNA sola clase, pero puede implementar VARIAS interfaces (separadas
//       por comas). PHP tiene interfaces propias que "enganchan" el objeto con funciones del
//       lenguaje: count($obj), echo $obj, json_encode($obj), foreach (e11)...
// ---------------------------------------------------------------------------------------------
titulo('Varias interfaces');
class Carrito implements Countable, Stringable, JsonSerializable
{
    private array $lineas = [];

    public function anadir(string $producto, float $precio, int $cantidad = 1): static
    {
        $this->lineas[] = compact('producto', 'precio', 'cantidad');
        return $this;                           // devolver $this permite encadenar llamadas
    }

    public function count(): int                // Countable
    {
        return array_sum(array_column($this->lineas, 'cantidad'));
    }

    public function total(): float
    {
        return round(array_sum(array_map(fn($l) => $l['precio'] * $l['cantidad'], $this->lineas)), 2);
    }

    public function __toString(): string        // Stringable
    {
        return count($this) . ' artículos, ' . $this->total() . ' €';
    }

    public function jsonSerialize(): array      // JsonSerializable
    {
        return ['lineas' => $this->lineas, 'total' => $this->total()];
    }
}
$carrito = (new Carrito())->anadir('Ratón', 19.95, 2)->anadir('Alfombrilla', 7.50);
ver('count($carrito)', count($carrito));                 // 3
ver('echo / (string)', (string) $carrito);               // "3 artículos, 47.4 €"
ver('json_encode', json_encode($carrito, JSON_UNESCAPED_UNICODE));
// {"lineas":[{"producto":"Ratón","precio":19.95,"cantidad":2},{"producto":"Alfombrilla","precio":7.5,"cantidad":1}],"total":47.4}
ver('class_implements', class_implements($carrito));     // ['Countable' => 'Countable', 'Stringable' => ..., 'JsonSerializable' => ...]
ver('¿es Countable?', $carrito instanceof Countable);    // true

// ---------------------------------------------------------------------------------------------
// Herencia de interfaces
// ENLACE: https://www.php.net/manual/es/language.oop5.interfaces.php
// ENUNCIADO EJEMPLO: algunos métodos de pago permiten además devolver el dinero.
// NOTA: una interfaz puede extender a otra (o a varias). Quien implementa la hija debe cumplir
//       los métodos de las dos.
// ---------------------------------------------------------------------------------------------
titulo('Herencia de interfaces');
interface MetodoPagoReembolsable extends MetodoPago
{
    public function reembolsar(string $operacion): bool;
}
class PagoPayPal implements MetodoPagoReembolsable
{
    public function __construct(private string $email)
    {
    }

    public function cobrar(float $importe): string
    {
        return 'PP-' . md5($this->email . $importe);
    }

    public function nombre(): string
    {
        return 'PayPal ' . $this->email;
    }

    public function reembolsar(string $operacion): bool
    {
        return str_starts_with($operacion, 'PP-');
    }
}
$paypal = new PagoPayPal('ana@example.com');
ver('¿es MetodoPago?', $paypal instanceof MetodoPago);          // true (por herencia de interfaces)
ver('reembolso', $paypal->reembolsar($paypal->cobrar(20)));     // true

// ---------------------------------------------------------------------------------------------
// trait y use — Reutilizar código en clases que no están emparentadas
// ENLACE: https://www.php.net/manual/es/language.oop5.traits.php
// ENUNCIADO EJEMPLO: clientes, productos y pedidos deben guardar cuándo se crearon y se
//                   modificaron, y poder registrar mensajes en un log.
// NOTA: un trait es un "trozo de clase" (propiedades y métodos) que se copia en las clases que lo
//       usan con use. Resuelve la falta de herencia múltiple: Producto ya hereda de otra clase,
//       pero puede usar todos los traits que quiera. Un trait NO se puede instanciar ni es un tipo
//       (no sirve en instanceof ni como tipo de parámetro: para eso están las interfaces).
// ---------------------------------------------------------------------------------------------
titulo('trait');
trait ConFechas
{
    private ?string $creado = null;
    private ?string $modificado = null;

    public function marcarCreado(string $fecha): void
    {
        $this->creado = $fecha;
        $this->modificado = $fecha;
    }

    public function marcarModificado(string $fecha): void
    {
        $this->modificado = $fecha;
    }

    public function fechas(): string
    {
        return "creado $this->creado, modificado $this->modificado";
    }
}

trait ConLog
{
    private array $log = [];

    public function registrar(string $mensaje): void
    {
        $this->log[] = static::class . ': ' . $mensaje;    // static::class = clase real del objeto
    }

    public function getLog(): array
    {
        return $this->log;
    }

    public function fechas(): string                       // ¡mismo nombre que en ConFechas!
    {
        return count($this->log) . ' entradas en el log';
    }
}

class ClienteTienda
{
    use ConFechas;                                         // un trait

    public function __construct(public string $nombre)
    {
    }
}

class PedidoTienda
{
    use ConFechas, ConLog {
        ConFechas::fechas insteadof ConLog;                // conflicto: se usa el de ConFechas...
        ConLog::fechas as resumenLog;                      // ...y el de ConLog con otro nombre
    }

    public function __construct(public int $numero)
    {
        $this->registrar("pedido $numero creado");
    }
}
$cliente = new ClienteTienda('Ana');
$cliente->marcarCreado('2026-10-16 10:00');
ver('cliente', $cliente);                              // tiene las propiedades del trait
ver('fechas del cliente', $cliente->fechas());         // "creado 2026-10-16 10:00, modificado 2026-10-16 10:00"

$pedido = new PedidoTienda(1001);
$pedido->marcarCreado('2026-10-16 10:05');
$pedido->marcarModificado('2026-10-16 11:30');
$pedido->registrar('pagado con Bizum');
ver('fechas del pedido', $pedido->fechas());           // "creado 2026-10-16 10:05, modificado 2026-10-16 11:30"
ver('resumenLog', $pedido->resumenLog());              // "2 entradas en el log"
ver('log', $pedido->getLog());                         // ['PedidoTienda: pedido 1001 creado', 'PedidoTienda: pagado con Bizum']
ver('class_uses', class_uses($pedido));                // ['ConFechas' => 'ConFechas', 'ConLog' => 'ConLog']
ver('¿instanceof ConLog?', $pedido instanceof ConLog); // false: un trait no es un tipo

// ---------------------------------------------------------------------------------------------
// Métodos abstractos en un trait
// ENLACE: https://www.php.net/manual/es/language.oop5.traits.php#language.oop5.traits.abstract
// ENUNCIADO EJEMPLO: un trait que genera un "slug" para la URL a partir del título que le dé
//                   cada clase.
// NOTA: el trait puede exigir que la clase tenga cierto método (abstract). Así el trait usa datos
//       de la clase sin conocerla.
// ---------------------------------------------------------------------------------------------
titulo('Trait con método abstracto');
trait ConSlug
{
    abstract public function titulo(): string;

    public function slug(): string
    {
        $texto = strtr(mb_strtolower($this->titulo()), ['á' => 'a', 'é' => 'e', 'í' => 'i', 'ó' => 'o', 'ú' => 'u', 'ü' => 'u', 'ñ' => 'n']);
        return trim(preg_replace('/[^a-z0-9]+/', '-', $texto), '-');
    }
}
class Articulo
{
    use ConSlug;

    public function __construct(private string $titulo)
    {
    }

    public function titulo(): string
    {
        return $this->titulo;
    }
}
ver('slug', (new Articulo('Las 10 mejores tapas de A Coruña'))->slug());   // "las-10-mejores-tapas-de-a-coruna"

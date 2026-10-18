<?php
/*
 * e08-POO.php
 *  POO (8): DECLARACIONES DE TIPOS Y EXCEPCIONES
 *  Contenidos: tipos en parámetros, retornos y propiedades (clases, interfaces, escalares,
 *              ?nullable, unión A|B, intersección A&B, mixed, void, never, iterable);
 *              declare(strict_types=1) y la conversión automática; TypeError; excepciones:
 *              throw, try / catch / finally, jerarquía Throwable (Exception y Error), excepciones
 *              de la SPL, excepciones propias, varios catch, relanzar con "previous"
 *  (apartados 11 y 12 de los apuntes)
 *
 *  Cómo trabajar este fichero:
 *   1. Pon un punto de ruptura en la primera línea de código y ejecuta en modo depuración.
 *   2. En los bloques try, avanza con F10 y observa a qué línea salta la ejecución cuando se
 *      lanza una excepción, y cuándo se ejecuta finally.
 *   3. En VSC, en el panel PUNTOS DE INTERRUPCIÓN, puedes marcar "Everything" o "Exceptions" para
 *      que el depurador se detenga en cuanto se lance una excepción.
 */
declare(strict_types=1);      // ¡debe ser la PRIMERA instrucción del fichero! (ver la sección 2)

require_once __DIR__ . '/utilidades.php';

// ---------------------------------------------------------------------------------------------
// 1. Declaraciones de tipos ("type hinting")
// ENLACE: https://www.php.net/manual/es/language.types.declarations.php
// ENUNCIADO EJEMPLO: la ficha de un empleado de la tienda y una función que calcula su nómina.
// NOTA: los tipos documentan el código y PHP los COMPRUEBA: si no se cumplen, lanza TypeError.
//       ?string = string o null. int|float = uno de los dos (unión, PHP 8.0). void = no devuelve
//       nada. never = nunca termina normalmente (siempre lanza una excepción o hace exit).
// ---------------------------------------------------------------------------------------------
titulo('Declaraciones de tipos');
interface ConNombre
{
    public function nombre(): string;
}
interface ConSueldo
{
    public function sueldoBase(): float;
}
final class Empleado implements ConNombre, ConSueldo
{
    public function __construct(
        private string $nombre,
        private float $sueldo,
        private ?string $departamento = null,      // nullable
        private int|float $horasExtra = 0,         // unión
    ) {
    }

    public function nombre(): string
    {
        return $this->nombre;
    }

    public function sueldoBase(): float
    {
        return $this->sueldo;
    }

    public function horasExtra(): int|float
    {
        return $this->horasExtra;
    }
}

// Intersección (PHP 8.1): el objeto debe implementar LAS DOS interfaces
function nomina(ConNombre&ConSueldo $persona, float $precioHoraExtra = 15.0): string
{
    $total = $persona->sueldoBase();
    if ($persona instanceof Empleado) {
        $total += $persona->horasExtra() * $precioHoraExtra;
    }
    return $persona->nombre() . ': ' . number_format($total, 2, ',', '.') . ' €';
}

function registrar(mixed $dato): void             // mixed: cualquier cosa; void: no devuelve nada
{
    echo '  log: ', json_encode($dato, JSON_UNESCAPED_UNICODE), nl();
}

function errorFatal(string $mensaje): never       // never: siempre lanza o termina
{
    throw new RuntimeException($mensaje);
}

$carmen = new Empleado('Carmen', 1850.0, 'Ventas', 4.5);
ver('nómina', nomina($carmen));                    // "Carmen: 1.917,50 €"
registrar(['evento' => 'nómina', 'empleado' => 'Carmen']);
verError('errorFatal', fn() => errorFatal('Sin conexión'));   // RuntimeException: Sin conexión
verError('nomina("Carmen")', fn() => nomina('Carmen'));      // TypeError: nomina(): Argument #1 ($persona) must be of type ConNombre&ConSueldo, string given...

// ---------------------------------------------------------------------------------------------
// 2. declare(strict_types=1) — Sin conversiones automáticas
// ENLACE: https://www.php.net/manual/es/language.types.declarations.php#language.types.declarations.strict
// ENUNCIADO EJEMPLO: pasar el sueldo como texto "1850" (como llega de un formulario).
// NOTA: SIN strict_types, PHP convierte "1850" en 1850.0 sin avisar (modo coercitivo). CON
//       strict_types=1 (al principio de ESTE fichero), los tipos escalares deben coincidir
//       exactamente: hay que convertir los datos del formulario a propósito, con (float), filter_var...
//       Solo se permite pasar un int donde se espera un float.
// ---------------------------------------------------------------------------------------------
titulo('strict_types');
verError('sueldo "1850" (string)', fn() => new Empleado('Diego', '1850'));   // TypeError: ... must be of type float, string given
ver('sueldo (float) "1850"', (new Empleado('Diego', (float) '1850'))->sueldoBase());   // 1850.0
ver('sueldo 1850 (int)', (new Empleado('Diego', 1850))->sueldoBase());                // 1850.0 (int -> float sí se permite)

// ---------------------------------------------------------------------------------------------
// 3. throw, try, catch y finally
// ENLACE: https://www.php.net/manual/es/language.exceptions.php
// ENUNCIADO EJEMPLO: procesar un pago: si falla, avisar al usuario; pase lo que pase, cerrar la
//                   conexión con la pasarela.
// NOTA: throw lanza una excepción: la función se interrumpe y PHP busca el catch más cercano del
//       tipo adecuado. finally se ejecuta SIEMPRE (haya excepción o no, e incluso con return).
//       Si nadie la captura: "Fatal error: Uncaught ...".
// ---------------------------------------------------------------------------------------------
titulo('try, catch, finally');
function cobrar(float $importe): string
{
    if ($importe <= 0) {
        throw new InvalidArgumentException("Importe no válido: $importe");
    }
    if ($importe > 500) {
        throw new RuntimeException('La pasarela ha rechazado el pago');
    }
    return 'OK-' . random_int(1000, 9999);
}

$traza = [];
foreach ([49.90, -5.0, 900.0] as $importe) {
    try {
        $traza[] = "intento $importe";
        $codigo = cobrar($importe);
        $traza[] = 'cobrado (esta línea se salta si hay excepción)';
    } catch (InvalidArgumentException $e) {
        $traza[] = 'dato incorrecto: ' . $e->getMessage();
    } catch (RuntimeException $e) {
        $traza[] = 'fallo externo: ' . $e->getMessage();
    } finally {
        $traza[] = 'se cierra la conexión';            // siempre
    }
}
ver('traza', $traza);

// ---------------------------------------------------------------------------------------------
// 4. Jerarquía: Throwable, Exception, Error y las excepciones de la SPL
// ENLACE: https://www.php.net/manual/es/class.throwable.php
// ENLACE: https://www.php.net/manual/es/spl.exceptions.php
// ENUNCIADO EJEMPLO: ver de qué tipo es cada problema.
// NOTA: Exception = problemas que el programa puede prever y tratar (datos incorrectos, un
//       fichero que no existe...). Error = fallos de programación (TypeError, DivisionByZeroError,
//       llamar a un método que no existe...). Las dos implementan Throwable. La SPL trae
//       excepciones con nombre para los casos habituales: úsalas en vez de "new Exception".
// ---------------------------------------------------------------------------------------------
titulo('Jerarquía');
$casos = [
    'InvalidArgumentException' => fn() => cobrar(0),
    'TypeError' => fn() => strlen([]),
    'DivisionByZeroError' => fn() => intdiv(1, 0),
    'Error' => fn() => (new Empleado('X', 1.0))->metodoQueNoExiste(),
    'JsonException' => fn() => json_decode('{mal}', flags: JSON_THROW_ON_ERROR),
];
$jerarquia = [];
foreach ($casos as $nombre => $codigo) {
    try {
        $codigo();
    } catch (Throwable $e) {                           // Throwable captura TODO
        $clases = [...array_reverse(array_values(class_parents($e))), get_class($e)];   // de la más general a la concreta
        $jerarquia[$nombre] = implode(' > ', $clases) . (($e instanceof Exception) ? '  (Exception)' : '  (Error)');
    }
}
ver('jerarquía', $jerarquia);
// InvalidArgumentException: Exception > LogicException > InvalidArgumentException; TypeError: Error > TypeError...

// ---------------------------------------------------------------------------------------------
// 5. Excepciones propias
// ENLACE: https://www.php.net/manual/es/language.exceptions.extending.php
// ENUNCIADO EJEMPLO: un error de stock con información extra (producto y unidades disponibles)
//                   para poder mostrar un mensaje útil.
// NOTA: se crean heredando de Exception (o de una de la SPL). Pueden tener propiedades y métodos
//       propios. Un catch de la clase padre captura también las hijas.
// ---------------------------------------------------------------------------------------------
titulo('Excepciones propias');
class TiendaException extends Exception
{
}
class StockInsuficienteException extends TiendaException
{
    public function __construct(public readonly string $producto, public readonly int $disponibles, int $pedidas)
    {
        parent::__construct("No hay $pedidas unidades de $producto (quedan $disponibles)");
    }
}
function reservar(string $producto, int $unidades): void
{
    $stock = ['Ratón' => 3, 'Teclado' => 0];
    if (($stock[$producto] ?? 0) < $unidades) {
        throw new StockInsuficienteException($producto, $stock[$producto] ?? 0, $unidades);
    }
}
try {
    reservar('Ratón', 5);
} catch (StockInsuficienteException $e) {
    ver('mensaje', $e->getMessage());               // "No hay 5 unidades de Ratón (quedan 3)"
    ver('disponibles', $e->disponibles);            // 3
    ver('¿es TiendaException?', $e instanceof TiendaException);   // true
    ver('línea', $e->getLine());                    // la línea del throw
}

// ---------------------------------------------------------------------------------------------
// 6. Varios tipos en un catch y relanzar con la excepción anterior ("previous")
// ENLACE: https://www.php.net/manual/es/exception.getprevious.php
// ENUNCIADO EJEMPLO: al guardar un pedido falla el JSON o el fichero; la capa de arriba solo
//                   quiere saber que "no se pudo guardar el pedido", pero sin perder la causa.
// NOTA: catch (A | B $e) captura cualquiera de los dos tipos. Al lanzar una excepción nueva se
//       puede pasar la original como tercer parámetro: queda accesible con getPrevious().
// ---------------------------------------------------------------------------------------------
titulo('Relanzar');
function guardarPedido(array $pedido, string $fichero): void
{
    try {
        $json = json_encode($pedido, JSON_THROW_ON_ERROR);
        if (@file_put_contents($fichero, $json) === false) {
            throw new RuntimeException("No se puede escribir en $fichero");
        }
    } catch (JsonException | RuntimeException $e) {
        throw new TiendaException('No se pudo guardar el pedido', 500, $e);   // $e = previous
    }
}
try {
    guardarPedido(['total' => NAN], __DIR__ . '/pedido.json');      // NAN no se puede pasar a JSON
} catch (TiendaException $e) {
    ver('mensaje', $e->getMessage());                               // "No se pudo guardar el pedido"
    ver('código', $e->getCode());                                   // 500
    ver('causa', get_class($e->getPrevious()) . ': ' . $e->getPrevious()->getMessage());
    // "JsonException: Inf and NaN cannot be JSON encoded"
}

<?php
/*
 * e09-POO.php
 *  POO (9): ENUMERACIONES (enum, PHP 8.1)
 *  Contenidos: enum puros, enum con valor (backed: string o int), cases(), from / tryFrom,
 *              métodos y constantes en un enum, enum que implementa una interfaz, match con
 *              enums, comparar casos, enums como tipo de parámetro y de propiedad
 *  (apartado 13 de los apuntes)
 *
 *  Cómo trabajar este fichero:
 *   1. Pon un punto de ruptura en la primera línea de código y ejecuta en modo depuración.
 *   2. En el panel VARIABLES, un caso de un enum aparece como un objeto con las propiedades
 *      name (y value, si tiene valor): son objetos únicos (EstadoPedido::Enviado siempre es el
 *      mismo objeto).
 */
require_once __DIR__ . '/utilidades.php';

// ---------------------------------------------------------------------------------------------
// enum puro — Un tipo con un conjunto CERRADO de valores
// ENLACE: https://www.php.net/manual/es/language.enumerations.basics.php
// ENUNCIADO EJEMPLO: los palos de la baraja española.
// NOTA: antes se usaban constantes (const OROS = 'oros') y una función podía recibir cualquier
//       string. Con un enum, el parámetro solo admite uno de sus casos: es imposible pasar un
//       valor no válido.
// ---------------------------------------------------------------------------------------------
titulo('enum puro');
enum Palo
{
    case Oros;
    case Copas;
    case Espadas;
    case Bastos;
}
function describirCarta(int $numero, Palo $palo): string
{
    return "$numero de " . strtolower($palo->name);    // name: el nombre del caso
}
ver('carta', describirCarta(7, Palo::Oros));            // "7 de oros"
ver('palos', array_map(fn(Palo $p) => $p->name, Palo::cases()));   // ['Oros', 'Copas', 'Espadas', 'Bastos']
ver('¿iguales?', Palo::Copas === Palo::Copas);          // true (cada caso es un objeto único)
ver('¿instanceof?', Palo::Copas instanceof Palo);       // true
verError('pasar un string', fn() => describirCarta(7, 'Oros'));   // TypeError
verError('new Palo', fn() => new Palo());               // Error: Cannot instantiate enum Palo

// ---------------------------------------------------------------------------------------------
// enum con valor (backed enum) — from y tryFrom
// ENLACE: https://www.php.net/manual/es/language.enumerations.backed.php
// ENUNCIADO EJEMPLO: el estado de un pedido, que se guarda en la base de datos o en JSON como texto.
// NOTA: cada caso tiene un valor (string o int, todos del mismo tipo). from('valor') devuelve el
//       caso o lanza ValueError; tryFrom devuelve null si no existe (ideal para datos de fuera).
// ---------------------------------------------------------------------------------------------
titulo('enum con valor');
enum EstadoPedido: string
{
    case Pendiente = 'pendiente';
    case Pagado = 'pagado';
    case Enviado = 'enviado';
    case Entregado = 'entregado';
    case Cancelado = 'cancelado';

    // Un enum puede tener métodos...
    public function etiqueta(): string
    {
        return match ($this) {
            self::Pendiente => 'Pendiente de pago',
            self::Pagado => 'Pagado, preparando el envío',
            self::Enviado => 'En camino',
            self::Entregado => 'Entregado',
            self::Cancelado => 'Cancelado',
        };
    }

    // ...que describen las transiciones válidas (máquina de estados)
    public function puedePasarA(self $nuevo): bool
    {
        return in_array($nuevo, match ($this) {
            self::Pendiente => [self::Pagado, self::Cancelado],
            self::Pagado => [self::Enviado, self::Cancelado],
            self::Enviado => [self::Entregado],
            self::Entregado, self::Cancelado => [],
        }, true);
    }

    // ...métodos estáticos y constantes
    public const INICIAL = self::Pendiente;

    public static function activos(): array
    {
        return array_filter(self::cases(), fn(self $e) => !in_array($e, [self::Entregado, self::Cancelado], true));
    }
}
$estado = EstadoPedido::from('pagado');                  // de la base de datos al enum
ver('estado', $estado);                                  // enum EstadoPedido::Pagado
ver('value', $estado->value);                            // "pagado" (para guardar)
ver('etiqueta', $estado->etiqueta());                    // "Pagado, preparando el envío"
ver('¿puede pasar a Enviado?', $estado->puedePasarA(EstadoPedido::Enviado));    // true
ver('¿puede pasar a Pendiente?', $estado->puedePasarA(EstadoPedido::Pendiente)); // false
ver('tryFrom("perdido")', EstadoPedido::tryFrom('perdido'));                     // NULL
verError('from("perdido")', fn() => EstadoPedido::from('perdido'));              // ValueError: "perdido" is not a valid backing value for enum EstadoPedido
ver('INICIAL', EstadoPedido::INICIAL);                   // enum EstadoPedido::Pendiente
ver('activos', array_map(fn($e) => $e->value, array_values(EstadoPedido::activos())));   // ['pendiente', 'pagado', 'enviado']
ver('en JSON', json_encode(['pedido' => 1001, 'estado' => $estado]));                   // {"pedido":1001,"estado":"pagado"}

// ---------------------------------------------------------------------------------------------
// Un enum como propiedad de una clase
// ENLACE: https://www.php.net/manual/es/language.enumerations.backed.php
// ENUNCIADO EJEMPLO: un pedido que solo cambia de estado si la transición es válida.
// ---------------------------------------------------------------------------------------------
titulo('enum en una clase');
class Pedido
{
    private EstadoPedido $estado = EstadoPedido::INICIAL;
    private array $historial = [];

    public function __construct(public readonly int $numero)
    {
    }

    public function cambiarA(EstadoPedido $nuevo): void
    {
        if (!$this->estado->puedePasarA($nuevo)) {
            throw new LogicException("El pedido $this->numero no puede pasar de {$this->estado->value} a {$nuevo->value}");
        }
        $this->historial[] = $this->estado->value . ' -> ' . $nuevo->value;
        $this->estado = $nuevo;
    }

    public function estado(): EstadoPedido
    {
        return $this->estado;
    }

    public function historial(): array
    {
        return $this->historial;
    }
}
$pedido = new Pedido(1001);
$pedido->cambiarA(EstadoPedido::Pagado);
$pedido->cambiarA(EstadoPedido::Enviado);
ver('pedido', $pedido);
verError('Enviado -> Cancelado', fn() => $pedido->cambiarA(EstadoPedido::Cancelado));
// LogicException: El pedido 1001 no puede pasar de enviado a cancelado
ver('historial', $pedido->historial());                  // ['pendiente -> pagado', 'pagado -> enviado']

// ---------------------------------------------------------------------------------------------
// enum que implementa una interfaz, con valor int
// ENLACE: https://www.php.net/manual/es/language.enumerations.interfaces.php
// ENUNCIADO EJEMPLO: los tipos de IVA, que se pueden usar donde se espere "algo con porcentaje".
// ---------------------------------------------------------------------------------------------
titulo('enum con interfaz');
interface ConPorcentaje
{
    public function porcentaje(): float;
}
enum TipoIva: int implements ConPorcentaje
{
    case General = 21;
    case Reducido = 10;
    case Superreducido = 4;

    public function porcentaje(): float
    {
        return $this->value / 100;
    }
}
function conIva(float $base, ConPorcentaje $tipo): float
{
    return round($base * (1 + $tipo->porcentaje()), 2);
}
ver('libro de 20 €', conIva(20, TipoIva::Superreducido));     // 20.8
ver('tele de 400 €', conIva(400, TipoIva::General));          // 484.0
ver('from(10)', TipoIva::from(10));                           // enum TipoIva::Reducido

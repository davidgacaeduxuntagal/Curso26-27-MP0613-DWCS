<?php
/*
 * e05-JSON.php
 *  JSON (5): OBJETOS Y JSON
 *  Funciones: JsonSerializable::jsonSerialize, get_object_vars, DateTime / DateTimeImmutable y
 *             DateTimeInterface::format, enum con valor (BackedEnum::from / tryFrom),
 *             crear objetos a partir de JSON (fromArray)
 *  (apartado 2.2 de los apuntes; la POO se estudia a fondo en el tema 3)
 *
 *  Cómo trabajar este fichero:
 *   1. Pon un punto de ruptura en la primera línea de código y ejecuta en modo depuración.
 *   2. Usa F11 (Step Into) en las llamadas a json_encode de objetos que implementan
 *      JsonSerializable: verás que PHP entra en jsonSerialize() automáticamente.
 *   3. Los comentarios a la derecha indican el resultado esperado.
 */
require_once __DIR__ . '/utilidades.php';

date_default_timezone_set('Europe/Madrid');

// ---------------------------------------------------------------------------------------------
// json_encode de un objeto normal — solo los atributos públicos
// ENLACE: https://www.php.net/manual/es/function.json-encode.php
// ENUNCIADO EJEMPLO: enviar los datos de un cliente... sin enviar su contraseña.
// NOTA: aunque la contraseña sea private y no salga en el JSON, depender de eso es frágil (basta
//       con que alguien la haga public). Es mejor decidir EXPLÍCITAMENTE qué se envía.
// ---------------------------------------------------------------------------------------------
titulo('Objeto normal');
class ClienteSimple
{
    public function __construct(
        public string $nombre,
        public string $email,
        private string $hashPassword,
    ) {
    }
}
$cliente = new ClienteSimple('Ana', 'ana@example.com', password_hash('secreta', PASSWORD_DEFAULT));
ver('json', json_encode($cliente));                     // {"nombre":"Ana","email":"ana@example.com"}
ver('get_object_vars desde fuera', get_object_vars($cliente));   // solo nombre y email (los públicos)

// ---------------------------------------------------------------------------------------------
// JsonSerializable — la clase decide qué se convierte a JSON
// ENLACE: https://www.php.net/manual/es/class.jsonserializable.php
// ENLACE: https://www.php.net/manual/es/jsonserializable.jsonserialize.php
// ENUNCIADO EJEMPLO: un Plato que, al pasar a JSON, incluye el precio con IVA (dato calculado)
//                   y oculta el coste (dato interno).
// NOTA: jsonSerialize() devuelve cualquier valor que json_encode sepa codificar (normalmente un
//       array asociativo). json_encode lo llama solo; tú no lo llamas nunca directamente.
// ---------------------------------------------------------------------------------------------
titulo('JsonSerializable');
class Plato implements JsonSerializable
{
    private const IVA = 0.10;

    public function __construct(
        private string $nombre,
        private float $precio,
        private float $coste,
        private array $alergenos = [],
    ) {
    }

    public function precioConIva(): float
    {
        return round($this->precio * (1 + self::IVA), 2);
    }

    public function jsonSerialize(): array
    {
        return [
            'nombre'    => $this->nombre,
            'precio'    => $this->precioConIva(),        // calculado
            'alergenos' => $this->alergenos,             // el coste NO se envía
        ];
    }
}
$pulpo = new Plato('Pulpo á feira', 12.5, 6.0, ['moluscos']);
ver('plato', json_encode($pulpo, JSON_UNESCAPED_UNICODE));   // {"nombre":"Pulpo á feira","precio":13.75,"alergenos":["moluscos"]}
$carta = [$pulpo, new Plato('Tortilla', 9.0, 3.0, ['huevo'])];
ver('lista de platos', json_encode($carta, JSON_UNESCAPED_UNICODE));   // también funciona dentro de arrays

// ---------------------------------------------------------------------------------------------
// Fechas: DateTime en JSON
// ENLACE: https://www.php.net/manual/es/datetime.format.php
// ENLACE: https://www.php.net/manual/es/class.datetimeinterface.php (constantes ATOM, etc.)
// ENUNCIADO EJEMPLO: enviar la fecha y hora de una reserva.
// NOTA: un objeto DateTime se codifica con un formato propio de PHP que otros lenguajes no
//       entienden directamente. Envía la fecha como texto ISO 8601 (DATE_ATOM) o como marca
//       de tiempo Unix (getTimestamp). NO uses strftime(): está obsoleta desde PHP 8.1.
// ---------------------------------------------------------------------------------------------
titulo('Fechas');
$fecha = new DateTimeImmutable('2026-10-16 21:30:00');
ver('DateTime directo', json_encode($fecha));
// {"date":"2026-10-16 21:30:00.000000","timezone_type":3,"timezone":"Europe\/Madrid"}
ver('ISO 8601', json_encode(['reserva' => $fecha->format(DATE_ATOM)]));   // {"reserva":"2026-10-16T21:30:00+02:00"}
ver('timestamp', json_encode(['reserva' => $fecha->getTimestamp()]));     // {"reserva":1792179000}
ver('solo fecha', json_encode(['dia' => $fecha->format('Y-m-d')]));       // {"dia":"2026-10-16"}
// Y al revés: de texto ISO 8601 a objeto
$leida = new DateTimeImmutable(json_decode('"2026-10-16T21:30:00+02:00"'));
ver('leída', $leida->format('d/m/Y H:i'));                                 // "16/10/2026 21:30"

// ---------------------------------------------------------------------------------------------
// enum con valor (backed enum, PHP 8.1) en JSON
// ENLACE: https://www.php.net/manual/es/language.enumerations.backed.php
// ENUNCIADO EJEMPLO: el estado de un pedido (pendiente, en cocina, servido).
// NOTA: un enum con valor se codifica con su valor. Al decodificar llega un string, que se
//       convierte de nuevo en enum con from() (excepción si no existe) o tryFrom() (null).
//       Un enum SIN valor (enum Estado { case ... }) no se puede codificar: json_encode falla.
// ---------------------------------------------------------------------------------------------
titulo('enum con valor');
enum EstadoPedido: string
{
    case Pendiente = 'pendiente';
    case EnCocina = 'en_cocina';
    case Servido = 'servido';
}
$pedido = ['mesa' => 7, 'estado' => EstadoPedido::EnCocina];
$json = json_encode($pedido);
ver('json', $json);                                            // {"mesa":7,"estado":"en_cocina"}
$datos = json_decode($json, true);
ver('llega como', gettype($datos['estado']));                 // "string"
$estado = EstadoPedido::from($datos['estado']);
ver('convertido a enum', $estado === EstadoPedido::EnCocina);  // true
ver('valor desconocido', EstadoPedido::tryFrom('cancelado'));  // NULL

// ---------------------------------------------------------------------------------------------
// De JSON a OBJETO de una clase (hidratar): método estático fromArray
// ENLACE: https://www.php.net/manual/es/language.oop5.static.php
// ENUNCIADO EJEMPLO: leer una reserva en JSON y crear un objeto Reserva, comprobando los datos.
// NOTA: json_decode nunca crea objetos de TUS clases (solo stdClass o arrays). Lo habitual es
//       decodificar a array y pasar el array a un "constructor con nombre" (fromArray) que
//       valida y convierte cada campo (fechas, enums, números...).
// ---------------------------------------------------------------------------------------------
titulo('De JSON a objeto');
class Reserva implements JsonSerializable
{
    public function __construct(
        public readonly string $nombre,
        public readonly int $personas,
        public readonly DateTimeImmutable $fecha,
    ) {
    }

    public static function fromArray(array $datos): self
    {
        if (!isset($datos['nombre'], $datos['personas'], $datos['fecha'])) {
            throw new InvalidArgumentException('Faltan datos de la reserva');
        }
        if (!is_int($datos['personas']) || $datos['personas'] < 1 || $datos['personas'] > 20) {
            throw new InvalidArgumentException('Número de personas no válido');
        }
        $fecha = DateTimeImmutable::createFromFormat(DATE_ATOM, $datos['fecha']);
        if ($fecha === false) {
            throw new InvalidArgumentException('Fecha no válida');
        }
        return new self(trim($datos['nombre']), $datos['personas'], $fecha);
    }

    public function jsonSerialize(): array
    {
        return ['nombre' => $this->nombre, 'personas' => $this->personas, 'fecha' => $this->fecha->format(DATE_ATOM)];
    }
}
$json = '{"nombre": " Brais ", "personas": 4, "fecha": "2026-10-16T21:30:00+02:00"}';
$reserva = Reserva::fromArray(json_decode($json, true, 512, JSON_THROW_ON_ERROR));
ver('clase', get_class($reserva));                  // "Reserva"
ver('nombre', $reserva->nombre);                    // "Brais"
ver('día', $reserva->fecha->format('l d/m'));       // "Friday 16/10"
ver('de vuelta a JSON', json_encode($reserva));     // {"nombre":"Brais","personas":4,"fecha":"2026-10-16T21:30:00+02:00"}

foreach (['{"nombre": "Eva", "personas": "4", "fecha": "2026-10-16T21:30:00+02:00"}',
          '{"nombre": "Eva", "personas": 2, "fecha": "16/10/2026"}'] as $json) {
    try {
        Reserva::fromArray(json_decode($json, true, 512, JSON_THROW_ON_ERROR));
    } catch (InvalidArgumentException $e) {
        ver('rechazada', $e->getMessage());         // "Número de personas no válido" / "Fecha no válida"
    }
}

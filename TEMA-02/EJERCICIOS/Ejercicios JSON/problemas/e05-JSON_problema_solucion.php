<?php
/*
 * e05-JSON_problema_solucion.php
 *  (FICHERO CON LAS SOLUCIONES)
 *  Problemas de: JsonSerializable, fechas en JSON (DATE_ATOM, createFromFormat), enum con
 *                valor (from / tryFrom), crear objetos a partir de JSON (fromArray)
 *  (repasa los ejemplos de ../e05-JSON.php)
 *
 *  Resuelve cada problema donde se indica y ejecútalo en modo depuración para comprobarlo.
 */
require_once __DIR__ . '/../utilidades.php';

date_default_timezone_set('Europe/Madrid');

// =============================================================================================
// PROBLEMA 1. Un usuario que no enseña su contraseña
//  Haz que la clase Usuario implemente JsonSerializable para que su JSON sea:
//   {"usuario":"carlos","nombre":"Carlos Pérez","roles":["camarero","caja"],"esAdmin":false}
//  (esAdmin se calcula: es true si entre los roles está "admin"). El hash de la contraseña no
//  debe aparecer nunca.
// =============================================================================================
titulo('Problema 1');
// completa aquí tu solución
// ---- SOLUCIÓN ----
class Usuario implements JsonSerializable
{
    public function __construct(
        private string $usuario,
        private string $nombre,
        private string $hash,
        private array $roles,
    ) {
    }

    public function jsonSerialize(): array
    {
        return [
            'usuario' => $this->usuario,
            'nombre'  => $this->nombre,
            'roles'   => $this->roles,
            'esAdmin' => in_array('admin', $this->roles, true),
        ];
    }
}
$carlos = new Usuario('carlos', 'Carlos Pérez', password_hash('1234', PASSWORD_DEFAULT), ['camarero', 'caja']);
$json = json_encode($carlos, JSON_UNESCAPED_UNICODE);
ver('JSON', $json);
ver('contiene el hash', str_contains($json, '$2y$'));   // false

// =============================================================================================
// PROBLEMA 2. Fechas de los eventos
//  Convierte $eventos a JSON de forma que cada fecha sea un texto ISO 8601 (DATE_ATOM). Después
//  decodifica el JSON y, para cada evento, muestra la fecha en formato español "d/m/Y H:i".
// =============================================================================================
titulo('Problema 2');
$eventos = [
    ['titulo' => 'Magosto', 'inicio' => new DateTimeImmutable('2026-11-11 18:00')],
    ['titulo' => 'Cea de Nadal', 'inicio' => new DateTimeImmutable('2026-12-24 21:30')],
];
// completa aquí tu solución
// ---- SOLUCIÓN ----
$paraJson = array_map(
    fn($e) => ['titulo' => $e['titulo'], 'inicio' => $e['inicio']->format(DATE_ATOM)],
    $eventos
);
$json = json_encode($paraJson);
ver('JSON', $json);   // [{"titulo":"Magosto","inicio":"2026-11-11T18:00:00+01:00"},{...,"inicio":"2026-12-24T21:30:00+01:00"}]
foreach (json_decode($json, true) as $evento) {
    $fecha = DateTimeImmutable::createFromFormat(DATE_ATOM, $evento['inicio']);
    ver($evento['titulo'], $fecha->format('d/m/Y H:i'));    // "11/11/2026 18:00" / "24/12/2026 21:30"
}

// =============================================================================================
// PROBLEMA 3. Estados de las mesas
//  Crea un enum con valor EstadoMesa (libre, ocupada, reservada) y úsalo para leer $jsonMesas.
//  Las mesas con un estado desconocido se consideran "libre". Muestra cuántas mesas hay en
//  cada estado.
// =============================================================================================
titulo('Problema 3');
$jsonMesas = '[{"mesa":1,"estado":"ocupada"},{"mesa":2,"estado":"libre"},{"mesa":3,"estado":"reservada"},{"mesa":4,"estado":"ocupada"},{"mesa":5,"estado":"rota"}]';
// completa aquí tu solución
// ---- SOLUCIÓN ----
enum EstadoMesa: string
{
    case Libre = 'libre';
    case Ocupada = 'ocupada';
    case Reservada = 'reservada';
}
$cuenta = [];
foreach (json_decode($jsonMesas, true) as $mesa) {
    $estado = EstadoMesa::tryFrom($mesa['estado']) ?? EstadoMesa::Libre;
    $cuenta[$estado->value] = ($cuenta[$estado->value] ?? 0) + 1;
}
ver('mesas por estado', $cuenta);    // ['ocupada' => 2, 'libre' => 2, 'reservada' => 1]
ver('enum a JSON', json_encode(['mesa' => 1, 'estado' => EstadoMesa::Ocupada]));   // {"mesa":1,"estado":"ocupada"}

// =============================================================================================
// PROBLEMA 4. Productos desde JSON
//  Completa el método estático Producto::fromArray para crear un Producto a partir de un array
//  decodificado: nombre (texto no vacío), precio (int o float mayor que 0) y stock (entero >= 0,
//  0 si no viene). Si algo no es correcto, lanza InvalidArgumentException. Carga $jsonProductos
//  y muestra los productos válidos y los errores.
// =============================================================================================
titulo('Problema 4');
class Producto
{
    public function __construct(public string $nombre, public float $precio, public int $stock)
    {
    }

    public static function fromArray(array $datos): self
    {
        // completa aquí el método
        // ---- SOLUCIÓN ----
        $nombre = $datos['nombre'] ?? null;
        $precio = $datos['precio'] ?? null;
        $stock = $datos['stock'] ?? 0;
        if (!is_string($nombre) || trim($nombre) === '') {
            throw new InvalidArgumentException('nombre no válido');
        }
        if (!(is_int($precio) || is_float($precio)) || $precio <= 0) {
            throw new InvalidArgumentException("precio no válido en $nombre");
        }
        if (!is_int($stock) || $stock < 0) {
            throw new InvalidArgumentException("stock no válido en $nombre");
        }
        return new self(trim($nombre), (float) $precio, $stock);
    }
}
$jsonProductos = '[{"nombre":"Café","precio":1.3,"stock":200},{"nombre":"Queixo","precio":"barato"},{"nombre":"Mel","precio":6},{"nombre":"","precio":2},{"nombre":"Viño","precio":9.5,"stock":-3}]';
// completa aquí tu solución
// ---- SOLUCIÓN ----
$productos = [];
$errores = [];
foreach (json_decode($jsonProductos, true, 512, JSON_THROW_ON_ERROR) as $i => $datos) {
    try {
        $productos[] = Producto::fromArray($datos);
    } catch (InvalidArgumentException $e) {
        $errores[$i] = $e->getMessage();
    }
}
ver('válidos', array_map(fn($p) => "$p->nombre ($p->precio €, stock $p->stock)", $productos));
// ['Café (1.3 €, stock 200)', 'Mel (6 €, stock 0)']
ver('errores', $errores);   // [1 => 'precio no válido en Queixo', 3 => 'nombre no válido', 4 => 'stock no válido en Viño']

// =============================================================================================
// PROBLEMA 5. Ida y vuelta de una comanda
//  Haz que Comanda implemente JsonSerializable (mesa, fecha en DATE_ATOM y platos) y añade un
//  método estático fromJson(string $json): self que haga lo contrario. Comprueba que, tras
//  convertir una comanda a JSON y de nuevo a objeto, los datos son los mismos.
// =============================================================================================
titulo('Problema 5');
// completa aquí tu solución
// ---- SOLUCIÓN ----
class Comanda implements JsonSerializable
{
    public function __construct(
        public readonly int $mesa,
        public readonly DateTimeImmutable $fecha,
        public readonly array $platos,
    ) {
    }

    public function jsonSerialize(): array
    {
        return ['mesa' => $this->mesa, 'fecha' => $this->fecha->format(DATE_ATOM), 'platos' => $this->platos];
    }

    public static function fromJson(string $json): self
    {
        $datos = json_decode($json, true, 512, JSON_THROW_ON_ERROR);
        return new self($datos['mesa'], new DateTimeImmutable($datos['fecha']), $datos['platos']);
    }
}
$original = new Comanda(7, new DateTimeImmutable('2026-10-16 14:05'), ['pulpo', 'pan']);
$json = json_encode($original);
$copia = Comanda::fromJson($json);
ver('JSON', $json);       // {"mesa":7,"fecha":"2026-10-16T14:05:00+02:00","platos":["pulpo","pan"]}
ver('misma mesa', $copia->mesa === $original->mesa);                          // true
ver('misma fecha', $copia->fecha == $original->fecha);                        // true (== compara el instante)
ver('mismos platos', $copia->platos === $original->platos);                   // true

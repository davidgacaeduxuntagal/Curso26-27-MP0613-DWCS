<?php
/*
 * e07-POO_problema_solucion.php
 *  (FICHERO CON LAS SOLUCIONES)
 *  Problemas de: __toString, __get / __set / __isset, __call, __invoke, __destruct,
 *                __serialize / __unserialize
 *  (repasa los ejemplos de ../Ejercicios OOP/e07-POO.php)
 *
 *  Resuelve cada problema donde se indica y ejecútalo en modo depuración para comprobarlo.
 */
require_once __DIR__ . '/../Ejercicios OOP/utilidades.php';

// =============================================================================================
// PROBLEMA 1. Una fracción que se muestra sola
//  Escribe la clase Fraccion (numerador y denominador int, denominador distinto de 0) que se
//  simplifique al crearse (usa el máximo común divisor) y que con __toString se muestre como
//  "3/4" (o solo "2" si el denominador es 1). Añade sumar(Fraccion $otra): Fraccion.
// =============================================================================================
titulo('Problema 1');
// completa aquí tu solución
// ---- SOLUCIÓN ----
final class Fraccion
{
    private int $num;
    private int $den;

    public function __construct(int $numerador, int $denominador)
    {
        if ($denominador === 0) {
            throw new DivisionByZeroError('El denominador no puede ser 0');
        }
        $mcd = self::mcd(abs($numerador), abs($denominador)) ?: 1;
        $signo = $denominador < 0 ? -1 : 1;
        $this->num = $signo * intdiv($numerador, $mcd);
        $this->den = abs(intdiv($denominador, $mcd));
    }

    private static function mcd(int $a, int $b): int
    {
        return $b === 0 ? $a : self::mcd($b, $a % $b);
    }

    public function sumar(Fraccion $otra): Fraccion
    {
        return new Fraccion($this->num * $otra->den + $otra->num * $this->den, $this->den * $otra->den);
    }

    public function __toString(): string
    {
        return $this->den === 1 ? (string) $this->num : "$this->num/$this->den";
    }
}
ver('6/8', (string) new Fraccion(6, 8));                                   // "3/4"
ver('1/2 + 1/4', (string) (new Fraccion(1, 2))->sumar(new Fraccion(1, 4)));   // "3/4"
ver('3/4 + 5/4', (string) (new Fraccion(3, 4))->sumar(new Fraccion(5, 4)));   // "2"
verError('1/0', fn() => new Fraccion(1, 0));                              // DivisionByZeroError

// =============================================================================================
// PROBLEMA 2. Propiedades de solo lectura con __get
//  Escribe la clase Sensor que reciba en el constructor un array de lecturas (temperaturas) y
//  ofrezca las propiedades virtuales (calculadas en __get) minima, maxima y media. Si se intenta
//  ESCRIBIR cualquier propiedad, __set debe lanzar LogicException. Implementa también __isset.
// =============================================================================================
titulo('Problema 2');
// completa aquí tu solución
// ---- SOLUCIÓN ----
class Sensor
{
    public function __construct(private array $lecturas)
    {
    }

    public function __get(string $nombre): float
    {
        return match ($nombre) {
            'minima' => min($this->lecturas),
            'maxima' => max($this->lecturas),
            'media' => round(array_sum($this->lecturas) / count($this->lecturas), 2),
            default => throw new OutOfRangeException("No existe la propiedad $nombre"),
        };
    }

    public function __set(string $nombre, mixed $valor): void
    {
        throw new LogicException('Las propiedades del sensor son de solo lectura');
    }

    public function __isset(string $nombre): bool
    {
        return in_array($nombre, ['minima', 'maxima', 'media'], true);
    }
}
$sensor = new Sensor([14.5, 17.0, 21.25, 19.0]);
ver('min, max, media', [$sensor->minima, $sensor->maxima, $sensor->media]);   // [14.5, 21.25, 17.94]
ver('isset media / humedad', [isset($sensor->media), isset($sensor->humedad)]);   // [true, false]
verError('escribir', function () use ($sensor) {
    $sensor->media = 0;
});   // LogicException: Las propiedades del sensor son de solo lectura

// =============================================================================================
// PROBLEMA 3. Getters y setters automáticos con __call
//  Escribe la clase Ficha que guarde sus datos en un array privado y entienda, con __call,
//  getAlgo() y setAlgo($valor) para cualquier campo de la lista CAMPOS (nombre, email, ciudad).
//  Para cualquier otro método, lanza BadMethodCallException. setAlgo debe devolver $this.
// =============================================================================================
titulo('Problema 3');
// completa aquí tu solución
// ---- SOLUCIÓN ----
class Ficha
{
    private const CAMPOS = ['nombre', 'email', 'ciudad'];
    private array $datos = [];

    public function __call(string $metodo, array $argumentos): mixed
    {
        $prefijo = substr($metodo, 0, 3);
        $campo = lcfirst(substr($metodo, 3));
        if (!in_array($campo, self::CAMPOS, true) || !in_array($prefijo, ['get', 'set'], true)) {
            throw new BadMethodCallException("Método desconocido: $metodo");
        }
        if ($prefijo === 'get') {
            return $this->datos[$campo] ?? null;
        }
        $this->datos[$campo] = $argumentos[0];
        return $this;
    }
}
$ficha = (new Ficha())->setNombre('Carmen')->setCiudad('Lugo');
ver('getNombre', $ficha->getNombre());        // "Carmen"
ver('getEmail', $ficha->getEmail());          // NULL
verError('setEdad', fn() => $ficha->setEdad(30));   // BadMethodCallException: Método desconocido: setEdad

// =============================================================================================
// PROBLEMA 4. Un objeto "callable" para ordenar
//  Escribe la clase OrdenarPor con __invoke(array $a, array $b): int, que reciba en el
//  constructor el campo y el sentido ('asc' o 'desc'), de forma que se pueda usar directamente
//  en usort. Ordena $productos por precio descendente y después por nombre ascendente.
// =============================================================================================
titulo('Problema 4');
$productos = [
    ['nombre' => 'Teclado', 'precio' => 49.5],
    ['nombre' => 'Ratón', 'precio' => 19.95],
    ['nombre' => 'Monitor', 'precio' => 179.0],
];
// completa aquí tu solución
// ---- SOLUCIÓN ----
class OrdenarPor
{
    public function __construct(private string $campo, private string $sentido = 'asc')
    {
    }

    public function __invoke(array $a, array $b): int
    {
        $resultado = $a[$this->campo] <=> $b[$this->campo];
        return $this->sentido === 'desc' ? -$resultado : $resultado;
    }
}
usort($productos, new OrdenarPor('precio', 'desc'));
ver('por precio desc', array_column($productos, 'nombre'));   // ['Monitor', 'Teclado', 'Ratón']
usort($productos, new OrdenarPor('nombre'));
ver('por nombre asc', array_column($productos, 'nombre'));    // ['Monitor', 'Ratón', 'Teclado']

// =============================================================================================
// PROBLEMA 5. Guardar una partida
//  La clase Partida tiene jugador, puntos y un dado (un objeto Dado que NO se debe guardar).
//  Añádele __serialize y __unserialize para que al serializar solo se guarden jugador y puntos,
//  y al recuperar se cree un dado nuevo. Comprueba que el texto serializado no contiene "Dado".
// =============================================================================================
titulo('Problema 5');
final class Dado
{
    public function tirar(): int
    {
        return random_int(1, 6);
    }
}
class Partida
{
    private Dado $dado;

    public function __construct(public string $jugador, public int $puntos = 0)
    {
        $this->dado = new Dado();
    }

    public function jugar(): void
    {
        $this->puntos += $this->dado->tirar();
    }
    // completa aquí: añade __serialize y __unserialize
    // ---- SOLUCIÓN ----
    public function __serialize(): array
    {
        return ['jugador' => $this->jugador, 'puntos' => $this->puntos];
    }

    public function __unserialize(array $datos): void
    {
        $this->jugador = $datos['jugador'];
        $this->puntos = $datos['puntos'];
        $this->dado = new Dado();
    }
}
// completa aquí tu solución
// ---- SOLUCIÓN ----
$partida = new Partida('Diego');
$partida->jugar();
$texto = serialize($partida);
ver('serializado', $texto);                                       // O:7:"Partida":2:{s:7:"jugador";s:5:"Diego";s:6:"puntos";i:...;}
ver('¿contiene Dado?', str_contains($texto, 'Dado'));             // false
$recuperada = unserialize($texto, ['allowed_classes' => [Partida::class]]);
$recuperada->jugar();                                             // funciona: tiene un dado nuevo
ver('puntos tras seguir jugando', $recuperada->puntos > $partida->puntos);   // true

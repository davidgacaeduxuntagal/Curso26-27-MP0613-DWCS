<?php
/*
 * e03-POO.php
 *  POO (3): ENCAPSULACIÓN, CONSTANTES Y MIEMBROS ESTÁTICOS
 *  Contenidos: public, private, protected; getters y setters con validación; propiedades
 *              readonly (PHP 8.1); propiedades tipadas sin inicializar; constantes de clase
 *              (const, self::, visibilidad); propiedades y métodos static; constructores con
 *              nombre (métodos de fábrica); el operador ::
 *  (apartados 4 y 5 de los apuntes)
 *
 *  Cómo trabajar este fichero:
 *   1. Pon un punto de ruptura en la primera línea de código y ejecuta en modo depuración.
 *   2. En el panel VARIABLES, las propiedades private y protected aparecen marcadas. Fíjate en
 *      que desde fuera no se pueden tocar, pero el depurador sí te las enseña.
 *   3. Las propiedades estáticas NO están dentro de los objetos (no las verás al desplegarlos):
 *      para seguirlas, añade en INSPECCIÓN (WATCH) la expresión CuentaBancaria::getNumeroCuentas().
 */
require_once __DIR__ . '/utilidades.php';

// ---------------------------------------------------------------------------------------------
// private + getters y setters — Proteger el estado del objeto
// ENLACE: https://www.php.net/manual/es/language.oop5.visibility.php
// ENUNCIADO EJEMPLO: una cuenta bancaria cuyo saldo no se pueda cambiar "a mano", solo con
//                   ingresos y retiradas que comprueban los datos.
// NOTA: ENCAPSULAR es ocultar los datos (private) y ofrecer métodos públicos que garantizan que
//       el objeto siempre es correcto. Si el saldo fuera public, cualquiera podría escribir
//       $cuenta->saldo = -5000 sin pasar por ninguna comprobación.
// ---------------------------------------------------------------------------------------------
titulo('private, getters y setters');
class CuentaBancaria
{
    public const COMISION_RETIRADA = 0.50;       // constante de clase (pública)
    private const SALDO_MINIMO = 0.0;            // constante privada: solo para uso interno

    private static int $numeroCuentas = 0;       // ESTÁTICA: una sola para toda la clase

    private float $saldo = 0.0;
    private array $movimientos = [];

    public function __construct(private string $titular, private readonly string $iban)
    {
        self::$numeroCuentas++;                  // self:: = esta clase
    }

    // GETTERS: permiten leer
    public function getTitular(): string
    {
        return $this->titular;
    }

    public function getSaldo(): float
    {
        return $this->saldo;
    }

    public function getIban(): string
    {
        return $this->iban;
    }

    // SETTER con validación
    public function setTitular(string $titular): void
    {
        if (mb_strlen(trim($titular)) < 3) {
            throw new InvalidArgumentException('El titular debe tener al menos 3 caracteres');
        }
        $this->titular = trim($titular);
    }

    // Métodos con "lógica de negocio" (no un simple setSaldo)
    public function ingresar(float $importe): void
    {
        if ($importe <= 0) {
            throw new InvalidArgumentException('El importe debe ser positivo');
        }
        $this->saldo += $importe;
        $this->registrar('ingreso', $importe);
    }

    public function retirar(float $importe): void
    {
        $total = $importe + self::COMISION_RETIRADA;
        if ($this->saldo - $total < self::SALDO_MINIMO) {
            throw new RuntimeException('Saldo insuficiente');
        }
        $this->saldo -= $total;
        $this->registrar('retirada', -$total);
    }

    public function getMovimientos(): array
    {
        return $this->movimientos;     // devuelve una COPIA del array: desde fuera no se puede alterar
    }

    private function registrar(string $tipo, float $importe): void   // método privado: auxiliar
    {
        $this->movimientos[] = ['tipo' => $tipo, 'importe' => $importe, 'saldo' => $this->saldo];
    }

    public static function getNumeroCuentas(): int   // método ESTÁTICO: no usa $this
    {
        return self::$numeroCuentas;
    }
}

$cuenta = new CuentaBancaria('Ana Pérez', 'ES91 2100 0418 4502 0005 1332');
$cuenta->ingresar(100);
$cuenta->retirar(30);
ver('cuenta', $cuenta);                     // saldo:CuentaBancaria:private => 69.5 ...
ver('saldo', $cuenta->getSaldo());          // 69.5 (100 - 30 - 0.50 de comisión)
ver('movimientos', $cuenta->getMovimientos());
verError('leer $cuenta->saldo', fn() => $cuenta->saldo);           // Error: Cannot access private property CuentaBancaria::$saldo
verError('escribir $cuenta->saldo', fn() => $cuenta->saldo = 1e6); // Error: Cannot access private property...
verError('retirar 100', fn() => $cuenta->retirar(100));            // RuntimeException: Saldo insuficiente
verError('setTitular("Al")', fn() => $cuenta->setTitular('Al'));   // InvalidArgumentException
verError('método privado', fn() => $cuenta->registrar('x', 1));    // Error: Call to private method...

// ---------------------------------------------------------------------------------------------
// readonly (PHP 8.1) — Propiedades que solo se asignan una vez
// ENLACE: https://www.php.net/manual/es/language.oop5.properties.php#language.oop5.properties.readonly-properties
// ENUNCIADO EJEMPLO: el IBAN de una cuenta no puede cambiar nunca.
// NOTA: una propiedad readonly se inicializa una vez (normalmente en el constructor) y después no
//       se puede modificar, ni siquiera desde dentro de la clase. Si además es public, se puede
//       LEER desde fuera sin getter. (Con dinero real conviene trabajar en céntimos, con int:
//       los float no son exactos, por eso aquí se redondea.)
// ---------------------------------------------------------------------------------------------
titulo('readonly');
final class Dinero
{
    public function __construct(public readonly float $cantidad, public readonly string $moneda = 'EUR')
    {
    }

    public function sumar(Dinero $otro): Dinero      // en lugar de cambiar el objeto, crea otro
    {
        return new Dinero(round($this->cantidad + $otro->cantidad, 2), $this->moneda);
    }
}
$precio = new Dinero(19.99);
$envio = new Dinero(4.95);
$total = $precio->sumar($envio);
ver('total', $total);                            // Dinero { cantidad 24.94, moneda 'EUR' }
ver('se lee sin getter', $total->cantidad);      // 24.94
verError('modificar readonly', fn() => $total->cantidad = 0);   // Error: Cannot modify readonly property Dinero::$cantidad
ver('iban (readonly privada)', $cuenta->getIban());

// ---------------------------------------------------------------------------------------------
// Propiedades tipadas sin inicializar
// ENLACE: https://www.php.net/manual/es/language.oop5.properties.php#language.oop5.properties.typed-properties
// ENUNCIADO EJEMPLO: leer una propiedad con tipo a la que todavía no se ha dado valor.
// NOTA: una propiedad tipada sin valor por defecto está "sin inicializar" (no vale null): leerla
//       es un error. Por eso conviene asignar todas en el constructor.
// ---------------------------------------------------------------------------------------------
titulo('Propiedades sin inicializar');
class Ficha
{
    public string $nombre;
    public ?string $nota = null;
}
$ficha = new Ficha();
ver('ficha', $ficha);                                       // solo aparece nota => NULL
verError('leer $ficha->nombre', fn() => $ficha->nombre);    // Error: Typed property Ficha::$nombre must not be accessed before initialization

// ---------------------------------------------------------------------------------------------
// Constantes de clase
// ENLACE: https://www.php.net/manual/es/language.oop5.constants.php
// ENUNCIADO EJEMPLO: la comisión de las retiradas, accesible sin crear cuentas.
// NOTA: se declaran con const (sin $), su valor no cambia y se usan con Clase::NOMBRE (dentro de
//       la clase, self::NOMBRE). Pueden ser private, protected o public (desde PHP 7.1).
// ---------------------------------------------------------------------------------------------
titulo('Constantes de clase');
ver('CuentaBancaria::COMISION_RETIRADA', CuentaBancaria::COMISION_RETIRADA);   // 0.5
$clase = 'CuentaBancaria';
ver('con el nombre en una variable', $clase::COMISION_RETIRADA);               // 0.5
ver('desde un objeto', $cuenta::COMISION_RETIRADA);                            // 0.5
verError('constante privada', fn() => CuentaBancaria::SALDO_MINIMO);          // Error: Cannot access private constant...

// ---------------------------------------------------------------------------------------------
// static — Propiedades y métodos de la CLASE (no de cada objeto)
// ENLACE: https://www.php.net/manual/es/language.oop5.static.php
// ENUNCIADO EJEMPLO: contar cuántas cuentas se han abierto.
// NOTA: una propiedad estática es compartida por todos los objetos (hay UNA para la clase). Un
//       método estático se llama con Clase::metodo() y no tiene $this. No abuses de ellos: un
//       método estático que usa datos globales es difícil de probar.
// ---------------------------------------------------------------------------------------------
titulo('static');
ver('cuentas abiertas', CuentaBancaria::getNumeroCuentas());     // 1
$otra = new CuentaBancaria('Brais Otero', 'ES79 2100 0813 6101 2345 6789');
ver('cuentas abiertas', CuentaBancaria::getNumeroCuentas());     // 2
ver('desde un objeto también', $otra::getNumeroCuentas());       // 2

// ---------------------------------------------------------------------------------------------
// Métodos de fábrica (constructores con nombre)
// ENLACE: https://www.php.net/manual/es/language.oop5.static.php
// ENUNCIADO EJEMPLO: crear temperaturas a partir de grados Celsius o Fahrenheit.
// NOTA: PHP no permite varios constructores (no hay sobrecarga). Un método estático que devuelve
//       new self(...) hace de "constructor con nombre" y deja el código más claro.
// ---------------------------------------------------------------------------------------------
titulo('Métodos de fábrica');
final class Temperatura
{
    private function __construct(private float $celsius)    // constructor privado: hay que usar las fábricas
    {
    }

    public static function desdeCelsius(float $grados): self
    {
        return new self($grados);
    }

    public static function desdeFahrenheit(float $grados): self
    {
        return new self(($grados - 32) * 5 / 9);
    }

    public function enCelsius(): float
    {
        return round($this->celsius, 1);
    }
}
ver('20 ºC', Temperatura::desdeCelsius(20)->enCelsius());        // 20.0
ver('98.6 ºF', Temperatura::desdeFahrenheit(98.6)->enCelsius());  // 37.0
verError('new Temperatura(20)', fn() => new Temperatura(20));    // Error: Call to private Temperatura::__construct()...

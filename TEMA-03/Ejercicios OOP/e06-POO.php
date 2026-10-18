<?php
/*
 * e06-POO.php
 *  POO (6): LATE STATIC BINDING (enlace estático en tiempo de ejecución)
 *  Contenidos: self:: frente a static:: frente a parent::, new self / new static, tipo de retorno
 *              static, static::class y get_called_class(), propiedades estáticas y herencia,
 *              llamar estáticamente a un método no estático (error en PHP 8)
 *  (apartado 9 de los apuntes)
 *
 *  Cómo trabajar este fichero:
 *   1. Pon un punto de ruptura en la primera línea de código y ejecuta en modo depuración.
 *   2. Usa F11 para entrar en los métodos estáticos heredados: fíjate en que el código que se
 *      ejecuta está en la clase PADRE, pero static:: se refiere a la clase con la que se llamó.
 *   3. Los comentarios a la derecha indican el resultado esperado.
 */
require_once __DIR__ . '/utilidades.php';

// ---------------------------------------------------------------------------------------------
// self:: frente a static::
// ENLACE: https://www.php.net/manual/es/language.oop5.late-static-bindings.php
// ENUNCIADO EJEMPLO: un sistema de notificaciones: cada tipo (correo, SMS) tiene su canal, pero
//                   el método que describe la notificación está en la clase padre.
// NOTA: self:: se refiere a la clase donde está ESCRITO el código. static:: se refiere a la
//       clase con la que se ha LLAMADO al método (se decide al ejecutar: "late", tarde).
// ---------------------------------------------------------------------------------------------
titulo('self:: frente a static::');
class Notificacion
{
    public static function canal(): string
    {
        return 'genérico';
    }

    public static function describirConSelf(): string
    {
        return 'Canal: ' . self::canal();        // siempre Notificacion::canal()
    }

    public static function describirConStatic(): string
    {
        return 'Canal: ' . static::canal();      // el canal() de la clase que llama
    }
}
class NotificacionEmail extends Notificacion
{
    public static function canal(): string
    {
        return 'correo electrónico';
    }
}
class NotificacionSms extends Notificacion
{
    public static function canal(): string
    {
        return 'SMS';
    }
}
ver('Email con self', NotificacionEmail::describirConSelf());       // "Canal: genérico"
ver('Email con static', NotificacionEmail::describirConStatic());   // "Canal: correo electrónico"
ver('Sms con static', NotificacionSms::describirConStatic());       // "Canal: SMS"

// ---------------------------------------------------------------------------------------------
// new static y tipo de retorno static — Fábricas que funcionan con las clases hijas
// ENLACE: https://www.php.net/manual/es/language.oop5.late-static-bindings.php
// ENLACE: https://www.php.net/manual/es/language.types.declarations.php#language.types.declarations.static
// ENUNCIADO EJEMPLO: un modelo base con un método crear(array $datos) que sirva para Cliente y
//                   para Producto sin escribirlo dos veces.
// NOTA: new self crearía SIEMPRE un objeto de la clase padre; new static crea uno de la clase con
//       la que se llamó. El tipo de retorno static indica justo eso.
// ---------------------------------------------------------------------------------------------
titulo('new static');
abstract class Modelo
{
    protected array $datos = [];
    private static array $contador = [];         // un contador POR CLASE (clave = nombre de clase)

    public static function crear(array $datos): static
    {
        $modelo = new static();                  // ¿Cliente? ¿Producto? Lo decide quien llama
        $modelo->datos = $datos;
        self::$contador[static::class] = (self::$contador[static::class] ?? 0) + 1;
        return $modelo;
    }

    public static function crearMal(array $datos): self
    {
        $modelo = new self();                    // ¡intenta crear un Modelo (abstracto)!
        $modelo->datos = $datos;
        return $modelo;
    }

    public static function cuantos(): int
    {
        return self::$contador[static::class] ?? 0;
    }

    public function get(string $campo): mixed
    {
        return $this->datos[$campo] ?? null;
    }

    public function tabla(): string
    {
        return strtolower(static::class) . 's';  // Cliente -> "clientes"
    }
}
class Cliente extends Modelo
{
}
class Producto extends Modelo
{
}
$ana = Cliente::crear(['nombre' => 'Ana', 'email' => 'ana@example.com']);
$brais = Cliente::crear(['nombre' => 'Brais']);
$raton = Producto::crear(['nombre' => 'Ratón', 'precio' => 19.95]);
ver('$ana', $ana);                                   // Cliente { datos:protected => [...] }
ver('clase de $raton', get_class($raton));           // "Producto"
ver('tabla de $ana', $ana->tabla());                 // "clientes"
ver('clientes creados', Cliente::cuantos());         // 2
ver('productos creados', Producto::cuantos());       // 1
verError('crearMal con new self', fn() => Cliente::crearMal([]));   // Error: Cannot instantiate abstract class Modelo

// ---------------------------------------------------------------------------------------------
// static::class y get_called_class()
// ENLACE: https://www.php.net/manual/es/function.get-called-class.php
// ENUNCIADO EJEMPLO: saber desde qué clase se ha llamado a un método estático heredado.
// NOTA: static::class (o get_called_class()) da el nombre de la clase "real"; self::class y
//       __CLASS__ dan la clase donde está escrito el código.
// ---------------------------------------------------------------------------------------------
titulo('static::class');
class Base
{
    public static function quien(): array
    {
        return ['self' => self::class, '__CLASS__' => __CLASS__, 'static' => static::class, 'get_called_class' => get_called_class()];
    }
}
class Hija extends Base
{
}
ver('Hija::quien()', Hija::quien());    // self 'Base', __CLASS__ 'Base', static 'Hija', get_called_class 'Hija'

// ---------------------------------------------------------------------------------------------
// self, parent y static con métodos de instancia (ejemplo Abuelo / Padre / Nieto del material)
// ENLACE: https://www.php.net/manual/es/language.oop5.late-static-bindings.php
// ENUNCIADO EJEMPLO: ver qué método se ejecuta en cada caso en una jerarquía de tres niveles.
// ---------------------------------------------------------------------------------------------
titulo('Abuelo, Padre y Nieto');
class Abuelo
{
    public function nombreClase(): string
    {
        return __CLASS__;
    }

    public function conSelf(): string
    {
        return self::nombreClase();
    }

    public function conStatic(): string
    {
        return static::nombreClase();
    }
}
class Padre extends Abuelo
{
    public function nombreClase(): string
    {
        return __CLASS__;
    }

    public function conParent(): string
    {
        return parent::nombreClase();
    }
}
class Nieto extends Padre
{
    public function nombreClase(): string
    {
        return __CLASS__;
    }
}
$nieto = new Nieto();
ver('conSelf', $nieto->conSelf());         // "Abuelo"
ver('conParent', $nieto->conParent());     // "Abuelo" (parent de Padre, donde está escrito conParent)
ver('conStatic', $nieto->conStatic());     // "Nieto"

// ---------------------------------------------------------------------------------------------
// Propiedades estáticas y herencia
// ENLACE: https://www.php.net/manual/es/language.oop5.static.php
// ENUNCIADO EJEMPLO: cada tipo de notificación lleva la cuenta de cuántas se han enviado.
// NOTA: si la hija NO redeclara la propiedad estática, comparte la del padre. Si la redeclara,
//       tiene la suya propia, y static:: elige la correcta.
// ---------------------------------------------------------------------------------------------
titulo('Estáticas y herencia');
class Envio
{
    protected static int $enviados = 0;

    public static function enviar(): void
    {
        static::$enviados++;
    }

    public static function enviados(): int
    {
        return static::$enviados;
    }
}
class EnvioUrgente extends Envio
{
    protected static int $enviados = 0;      // redeclarada: contador propio
}
class EnvioNormal extends Envio
{
}                                            // sin redeclarar: usa el de Envio
EnvioUrgente::enviar();
EnvioUrgente::enviar();
EnvioNormal::enviar();
ver('urgentes', EnvioUrgente::enviados());   // 2
ver('normales', EnvioNormal::enviados());    // 1
ver('Envio', Envio::enviados());             // 1 (EnvioNormal comparte el contador de Envio)

// ---------------------------------------------------------------------------------------------
// Llamar estáticamente a un método NO estático (ejemplo del material de referencia)
// ENLACE: https://www.php.net/manual/es/language.oop5.static.php
// ENUNCIADO EJEMPLO: ClaseA::funcionA() siendo funcionA un método normal.
// NOTA: en PHP 5 daba un aviso E_STRICT; en PHP 8 es un Error. Los métodos que usan $this se
//       llaman siempre desde un objeto.
// ---------------------------------------------------------------------------------------------
titulo('Llamada estática a un método no estático');
class ClaseA
{
    public function funcionA(): string
    {
        return '$this es de la clase ' . get_class($this);
    }
}
ver('desde un objeto', (new ClaseA())->funcionA());    // "$this es de la clase ClaseA"
verError('ClaseA::funcionA()', fn() => ClaseA::funcionA());   // Error: Non-static method ClaseA::funcionA() cannot be called statically

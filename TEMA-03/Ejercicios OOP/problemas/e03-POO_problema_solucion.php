<?php
/*
 * e03-POO_problema_solucion.php
 *  (FICHERO CON LAS SOLUCIONES)
 *  Problemas de: private, getters y setters con validación, readonly, constantes de clase,
 *                propiedades y métodos estáticos, métodos de fábrica
 *  (repasa los ejemplos de ../Ejercicios OOP/e03-POO.php)
 *
 *  Resuelve cada problema donde se indica y ejecútalo en modo depuración para comprobarlo.
 */
require_once __DIR__ . '/../Ejercicios OOP/utilidades.php';

// =============================================================================================
// PROBLEMA 1. Encapsular
//  Esta clase deja que cualquiera ponga notas imposibles. Reescríbela como NotaSegura: con la
//  nota private, un getter, un setter que solo acepte valores de 0 a 10 (si no, lanza
//  InvalidArgumentException) y un método aprobado(). Prueba a poner un 7.5 y un 12.
// =============================================================================================
titulo('Problema 1');
class NotaInsegura
{
    public float $nota = 0;
}
$mal = new NotaInsegura();
$mal->nota = 15;                 // ¡nadie lo impide!
// completa aquí tu solución
// ---- SOLUCIÓN ----
class NotaSegura
{
    public const MINIMA = 0.0;
    public const MAXIMA = 10.0;
    private float $nota = 0.0;

    public function getNota(): float
    {
        return $this->nota;
    }

    public function setNota(float $nota): void
    {
        if ($nota < self::MINIMA || $nota > self::MAXIMA) {
            throw new InvalidArgumentException("Nota fuera de rango: $nota");
        }
        $this->nota = $nota;
    }

    public function aprobado(): bool
    {
        return $this->nota >= 5;
    }
}
$nota = new NotaSegura();
$nota->setNota(7.5);
ver('nota y aprobado', [$nota->getNota(), $nota->aprobado()]);   // [7.5, true]
verError('nota 12', fn() => $nota->setNota(12));                 // InvalidArgumentException: Nota fuera de rango: 12

// =============================================================================================
// PROBLEMA 2. Objetos inmutables con readonly
//  Escribe la clase final Coordenada con latitud y longitud public readonly (float), que en el
//  constructor compruebe los rangos (-90..90 y -180..180). Añade distanciaKm(Coordenada $otra):
//  float con la fórmula de abajo. Calcula la distancia entre A Coruña y Santiago y comprueba que
//  no se puede cambiar la latitud.
// =============================================================================================
titulo('Problema 2');
// Fórmula (haversine), con R = 6371 km y los ángulos en radianes (deg2rad):
//   a = sin²(Δlat/2) + cos(lat1)·cos(lat2)·sin²(Δlon/2);   d = 2·R·asin(√a)
// completa aquí tu solución
// ---- SOLUCIÓN ----
final class Coordenada
{
    public function __construct(public readonly float $latitud, public readonly float $longitud)
    {
        if (abs($latitud) > 90 || abs($longitud) > 180) {
            throw new InvalidArgumentException('Coordenadas fuera de rango');
        }
    }

    public function distanciaKm(Coordenada $otra): float
    {
        $dLat = deg2rad($otra->latitud - $this->latitud);
        $dLon = deg2rad($otra->longitud - $this->longitud);
        $a = sin($dLat / 2) ** 2 + cos(deg2rad($this->latitud)) * cos(deg2rad($otra->latitud)) * sin($dLon / 2) ** 2;
        return round(2 * 6371 * asin(sqrt($a)), 1);
    }
}
$coruna = new Coordenada(43.3623, -8.4115);
$santiago = new Coordenada(42.8782, -8.5448);
ver('A Coruña - Santiago (km)', $coruna->distanciaKm($santiago));   // 54.9
verError('cambiar la latitud', fn() => $coruna->latitud = 0);       // Error: Cannot modify readonly property Coordenada::$latitud

// =============================================================================================
// PROBLEMA 3. Un generador de identificadores
//  Escribe la clase Ticket con un número que se asigne automáticamente (1, 2, 3...) usando una
//  propiedad static privada, y un método estático total() que diga cuántos tickets se han
//  creado. Crea tres tickets y comprueba los números.
// =============================================================================================
titulo('Problema 3');
// completa aquí tu solución
// ---- SOLUCIÓN ----
class Ticket
{
    private static int $ultimo = 0;
    public readonly int $numero;

    public function __construct(public string $asunto)
    {
        $this->numero = ++self::$ultimo;
    }

    public static function total(): int
    {
        return self::$ultimo;
    }
}
$t1 = new Ticket('No funciona el wifi');
$t2 = new Ticket('Pantalla rota');
$t3 = new Ticket('Falta tóner');
ver('números', [$t1->numero, $t2->numero, $t3->numero]);   // [1, 2, 3]
ver('total', Ticket::total());                             // 3

// =============================================================================================
// PROBLEMA 4. Métodos de fábrica
//  Escribe la clase Duracion que guarde segundos (int) con un constructor PRIVADO y tres
//  fábricas: desdeSegundos(int), desdeMinutos(float) y desdeTexto(string "HH:MM:SS"). Añade
//  formato(): string que devuelva "HH:MM:SS". Comprueba que las tres dan "01:30:00" para hora y
//  media.
// =============================================================================================
titulo('Problema 4');
// completa aquí tu solución
// ---- SOLUCIÓN ----
final class Duracion
{
    private function __construct(private int $segundos)
    {
    }

    public static function desdeSegundos(int $segundos): self
    {
        return new self($segundos);
    }

    public static function desdeMinutos(float $minutos): self
    {
        return new self((int) round($minutos * 60));
    }

    public static function desdeTexto(string $texto): self
    {
        [$h, $m, $s] = array_map('intval', explode(':', $texto));
        return new self($h * 3600 + $m * 60 + $s);
    }

    public function formato(): string
    {
        return sprintf('%02d:%02d:%02d', intdiv($this->segundos, 3600), intdiv($this->segundos % 3600, 60), $this->segundos % 60);
    }
}
ver('desdeSegundos', Duracion::desdeSegundos(5400)->formato());   // "01:30:00"
ver('desdeMinutos', Duracion::desdeMinutos(90)->formato());       // "01:30:00"
ver('desdeTexto', Duracion::desdeTexto('01:30:00')->formato());   // "01:30:00"

// =============================================================================================
// PROBLEMA 5. ¿Cómo harías...?
//  ¿Cómo harías para que una clase Config tuviera unas constantes con los límites de la tienda
//  (MAX_PRODUCTOS_CARRITO = 50, ENVIO_GRATIS_DESDE = 50.0) que se pudieran consultar desde
//  fuera, y una constante CLAVE_API que SOLO se pudiera usar dentro de la clase? Escríbela y
//  demuestra los dos accesos.
// =============================================================================================
titulo('Problema 5');
// completa aquí tu solución
// ---- SOLUCIÓN ----
class Config
{
    public const MAX_PRODUCTOS_CARRITO = 50;
    public const ENVIO_GRATIS_DESDE = 50.0;
    private const CLAVE_API = 'sk_test_123';

    public static function cabeceraApi(): string
    {
        return 'Authorization: Bearer ' . substr(self::CLAVE_API, 0, 3) . '***';   // uso interno
    }
}
ver('desde fuera', [Config::MAX_PRODUCTOS_CARRITO, Config::ENVIO_GRATIS_DESDE]);   // [50, 50.0]
ver('uso interno', Config::cabeceraApi());                                         // "Authorization: Bearer sk_***"
verError('CLAVE_API desde fuera', fn() => Config::CLAVE_API);                      // Error: Cannot access private constant Config::CLAVE_API

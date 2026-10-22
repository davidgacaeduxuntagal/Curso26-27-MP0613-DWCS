<?php
/*
 * Figura.php
 *  Clase ABSTRACTA base de la jerarquía: no se pueden crear objetos "Figura", solo de sus subclases.
 *  Contiene lo que tienen en común todas las figuras (el color, el contador de objetos, el formato
 *  de __toString...) y DECLARA los métodos que cada figura debe implementar a su manera.
 */
require_once __DIR__ . '/Dibujable.php';

abstract class Figura implements Dibujable
{
    // Constantes de clase: se usan con Figura::UNIDAD o, dentro de la clase, con self::UNIDAD
    public const UNIDAD  = 'cm';
    public const COLORES = [
        'rojo'    => '#e53935',
        'verde'   => '#43a047',
        'azul'    => '#1e88e5',
        'naranja' => '#fb8c00',
        'morado'  => '#8e24aa',
    ];

    // Atributo ESTÁTICO: pertenece a la clase, no a cada objeto. Guarda cuántos objetos se han
    // creado de cada clase concreta: ['Circulo' => 3, 'Cuadrado' => 1, ...]
    private static array $contadores = [];

    protected string $color;

    public function __construct(string $color)
    {
        $this->setColor($color);

        // static::class es el nombre de la clase REAL del objeto que se está creando (Circulo,
        // Cuadrado...), aunque este código esté escrito en Figura ("late static binding")
        $clase = static::class;
        self::$contadores[$clase] = (self::$contadores[$clase] ?? 0) + 1;
    }

    // ---------------------------------------------------------------------- getters y setters

    public function getColor(): string
    {
        return $this->color;
    }

    /**
     * El setter VALIDA el dato: así un objeto nunca puede quedar con un color incorrecto.
     */
    public function setColor(string $color): void
    {
        if (!array_key_exists($color, self::COLORES)) {
            throw new InvalidArgumentException("Color no válido: «{$color}»");
        }
        $this->color = $color;
    }

    /**
     * Código de color HTML (#rrggbb) correspondiente al color de la figura.
     */
    public function colorHtml(): string
    {
        return self::COLORES[$this->color];
    }

    // ---------------------------------------------------------------------- métodos abstractos
    // Cada subclase está OBLIGADA a implementarlos (si no, PHP da un error al declararla)

    abstract public function nombre(): string;

    abstract public function area(): float;

    abstract public function perimetro(): float;

    /**
     * Texto con las medidas propias de la figura, por ejemplo "r = 3 cm".
     */
    abstract protected function medidas(): string;

    // ---------------------------------------------------------------------- métodos comunes

    /**
     * Gracias al polimorfismo, este método sirve para TODAS las figuras: llama a nombre(), medidas(),
     * area() y perimetro(), y PHP ejecuta en cada caso la versión de la clase real del objeto.
     */
    public function __toString(): string
    {
        return sprintf(
            "%s %s (%s): área %s %s², perímetro %s %s",
            $this->nombre(),
            $this->color,
            $this->medidas(),
            self::formato($this->area()),
            self::UNIDAD,
            self::formato($this->perimetro()),
            self::UNIDAD
        );
    }

    /**
     * Número de objetos creados de una clase concreta (o de todas las figuras, si no se indica).
     *   Figura::numeroCreadas(Circulo::class)   Figura::numeroCreadas()
     */
    public static function numeroCreadas(?string $clase = null): int
    {
        if ($clase === null) {
            return array_sum(self::$contadores);
        }
        return self::$contadores[$clase] ?? 0;
    }

    /**
     * Crea una figura AL AZAR de cualquiera de las clases concretas.
     * Cada subclase redefine este método para crear una figura de su propio tipo.
     */
    public static function generarAlAzar(): Figura
    {
        $clases = [Circulo::class, Rectangulo::class, Cuadrado::class, Triangulo::class];
        $clase  = $clases[array_rand($clases)];
        return $clase::generarAlAzar();       // llamada a un método estático con el nombre en una variable
    }

    /**
     * Función de comparación para usort: de mayor a menor área.
     */
    public static function compararPorArea(Figura $a, Figura $b): int
    {
        return $b->area() <=> $a->area();
    }

    // ---------------------------------------------------------------------- ayudas para las subclases

    /**
     * Medida aleatoria entre $min y $max con un decimal.
     */
    protected static function medidaAlAzar(float $min, float $max): float
    {
        return random_int((int) ($min * 10), (int) ($max * 10)) / 10;
    }

    protected static function colorAlAzar(): string
    {
        return array_rand(self::COLORES);
    }

    /**
     * Comprueba que una medida es positiva.
     */
    protected static function validarMedida(float $valor, string $nombre): float
    {
        if ($valor <= 0) {
            throw new InvalidArgumentException("La medida «{$nombre}» debe ser mayor que 0");
        }
        return $valor;
    }

    /**
     * Número con coma decimal y 2 decimales.
     */
    public static function formato(float $numero): string
    {
        return number_format($numero, 2, ',', '.');
    }

    /**
     * Abre la etiqueta <svg> común a todos los dibujos.
     */
    protected function inicioSvg(int $tamano): string
    {
        return "<svg width='{$tamano}' height='{$tamano}' viewBox='0 0 {$tamano} {$tamano}' role='img' aria-label='{$this->nombre()}'>";
    }
}

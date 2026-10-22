<?php
/*
 * Triangulo.php
 *  Triángulo definido por la longitud de sus tres lados a, b y c.
 */
require_once __DIR__ . '/Figura.php';

class Triangulo extends Figura
{
    private float $a;
    private float $b;
    private float $c;

    public function __construct(float $a, float $b, float $c, string $color = 'rojo')
    {
        $this->setLados($a, $b, $c);          // validamos antes de contar el objeto
        parent::__construct($color);
    }

    /**
     * Los tres lados se cambian a la vez, porque la validez depende de los tres: cada lado debe
     * ser menor que la suma de los otros dos (desigualdad triangular).
     */
    public function setLados(float $a, float $b, float $c): void
    {
        self::validarMedida($a, 'a');
        self::validarMedida($b, 'b');
        self::validarMedida($c, 'c');
        // TODO (paso 3): comprobar la desigualdad triangular (lanzar InvalidArgumentException)
        $this->a = $a;
        $this->b = $b;
        $this->c = $c;
    }

    public function getLados(): array
    {
        return [$this->a, $this->b, $this->c];
    }

    /**
     * Clasificación según sus lados. (Método propio de esta clase: no existe en Figura.)
     */
    public function tipo(): string
    {
        // TODO (paso 3): equilátero, isósceles o escaleno
        return '';
    }

    // ---------------------------------------------------------------------- implementación de los abstractos

    public function nombre(): string
    {
        return 'Triángulo';
    }

    /**
     * Fórmula de Herón: s = semiperímetro, área = raíz(s·(s-a)·(s-b)·(s-c))
     */
    public function area(): float
    {
        // TODO (paso 3): calcular el área
        return 0.0;
    }

    public function perimetro(): float
    {
        // TODO (paso 3): calcular el perímetro
        return 0.0;
    }

    protected function medidas(): string
    {
        return "lados " . self::formato($this->a) . ", " . self::formato($this->b) . ", "
            . self::formato($this->c) . " " . self::UNIDAD;
    }

    // ---------------------------------------------------------------------- Dibujable

    /**
     * Vértices del triángulo: A en (0, 0), B en (c, 0) y C a distancia b de A y a de B.
     * Devuelve [[xA, yA], [xB, yB], [xC, yC]] con y hacia ARRIBA.
     */
    // TODO (paso 3): private function vertices(): array

    public function dimensionMaxima(): float
    {
        // TODO (paso 3): mayor medida (ancho o alto) de la figura
        return 1.0;
    }

    public function svg(float $escala, int $tamano = 140): string
    {
        // TODO (paso 3): devolver el código SVG que dibuja la figura
        return '';
    }

    // ---------------------------------------------------------------------- creación al azar

    public static function generarAlAzar(): Triangulo
    {
        // Generamos dos lados y el tercero entre la diferencia y la suma: siempre forman triángulo
        $a = self::medidaAlAzar(2, 7);
        $b = self::medidaAlAzar(2, 7);
        $c = self::medidaAlAzar(abs($a - $b) + 0.5, $a + $b - 0.5);
        return new Triangulo($a, $b, $c, self::colorAlAzar());
    }
}

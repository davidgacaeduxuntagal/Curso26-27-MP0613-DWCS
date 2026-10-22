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
        if ($a >= $b + $c || $b >= $a + $c || $c >= $a + $b) {
            throw new InvalidArgumentException(
                "Con los lados " . self::formato($a) . ", " . self::formato($b) . " y " . self::formato($c) . " no se puede formar un triángulo"
            );
        }
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
        if ($this->a == $this->b && $this->b == $this->c) {
            return 'equilátero';
        }
        if ($this->a == $this->b || $this->b == $this->c || $this->a == $this->c) {
            return 'isósceles';
        }
        return 'escaleno';
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
        $s = $this->perimetro() / 2;
        return sqrt($s * ($s - $this->a) * ($s - $this->b) * ($s - $this->c));
    }

    public function perimetro(): float
    {
        return $this->a + $this->b + $this->c;
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
    private function vertices(): array
    {
        $xC = ($this->b ** 2 - $this->a ** 2 + $this->c ** 2) / (2 * $this->c);
        $yC = sqrt(max(0, $this->b ** 2 - $xC ** 2));
        return [[0, 0], [$this->c, 0], [$xC, $yC]];
    }

    public function dimensionMaxima(): float
    {
        $xs = array_column($this->vertices(), 0);
        $ys = array_column($this->vertices(), 1);
        return max(max($xs) - min($xs), max($ys) - min($ys));
    }

    public function svg(float $escala, int $tamano = 140): string
    {
        $vertices = $this->vertices();
        $xs       = array_column($vertices, 0);
        $ys       = array_column($vertices, 1);
        // desplazamiento para centrar el dibujo (en SVG la y crece hacia ABAJO: la invertimos)
        $dx       = ($tamano - (max($xs) - min($xs)) * $escala) / 2 - min($xs) * $escala;
        $dy       = ($tamano + (max($ys) - min($ys)) * $escala) / 2;
        $puntos   = [];
        foreach ($vertices as [$x, $y]) {
            $puntos[] = round($dx + $x * $escala, 1) . "," . round($dy - $y * $escala, 1);
        }
        return $this->inicioSvg($tamano)
            . "<polygon points='" . implode(" ", $puntos) . "' fill='{$this->colorHtml()}' /></svg>";
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

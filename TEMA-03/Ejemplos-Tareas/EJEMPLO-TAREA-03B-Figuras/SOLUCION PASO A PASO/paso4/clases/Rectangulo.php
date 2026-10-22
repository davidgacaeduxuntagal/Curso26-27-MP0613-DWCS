<?php
/*
 * Rectangulo.php
 */
require_once __DIR__ . '/Figura.php';

class Rectangulo extends Figura
{
    // protected (y no private) para que la subclase Cuadrado pueda usarlos
    protected float $base;
    protected float $altura;

    public function __construct(float $base, float $altura, string $color = 'verde')
    {
        $this->setBase($base);                // validamos antes de contar el objeto
        $this->setAltura($altura);
        parent::__construct($color);
    }

    public function getBase(): float
    {
        return $this->base;
    }

    public function setBase(float $base): void
    {
        $this->base = self::validarMedida($base, 'base');
    }

    public function getAltura(): float
    {
        return $this->altura;
    }

    public function setAltura(float $altura): void
    {
        $this->altura = self::validarMedida($altura, 'altura');
    }

    // ---------------------------------------------------------------------- implementación de los abstractos

    public function nombre(): string
    {
        return 'Rectángulo';
    }

    public function area(): float
    {
        return $this->base * $this->altura;
    }

    public function perimetro(): float
    {
        return 2 * ($this->base + $this->altura);
    }

    protected function medidas(): string
    {
        return self::formato($this->base) . " x " . self::formato($this->altura) . " " . self::UNIDAD;
    }

    // ---------------------------------------------------------------------- Dibujable

    public function dimensionMaxima(): float
    {
        return max($this->base, $this->altura);
    }

    public function svg(float $escala, int $tamano = 140): string
    {
        $ancho = $this->base * $escala;
        $alto  = $this->altura * $escala;
        $x     = ($tamano - $ancho) / 2;            // centrado
        $y     = ($tamano - $alto) / 2;
        return $this->inicioSvg($tamano)
            . "<rect x='{$x}' y='{$y}' width='{$ancho}' height='{$alto}' fill='{$this->colorHtml()}' /></svg>";
    }

    // ---------------------------------------------------------------------- creación al azar

    public static function generarAlAzar(): Rectangulo
    {
        return new Rectangulo(self::medidaAlAzar(1, 8), self::medidaAlAzar(1, 8), self::colorAlAzar());
    }
}

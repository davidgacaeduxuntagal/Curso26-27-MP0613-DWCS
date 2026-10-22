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
        return self::formato($this->base) . " x " . self::formato($this->altura) . " " . self::UNIDAD;
    }

    // ---------------------------------------------------------------------- Dibujable

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

    public static function generarAlAzar(): Rectangulo
    {
        return new Rectangulo(self::medidaAlAzar(1, 8), self::medidaAlAzar(1, 8), self::colorAlAzar());
    }
}

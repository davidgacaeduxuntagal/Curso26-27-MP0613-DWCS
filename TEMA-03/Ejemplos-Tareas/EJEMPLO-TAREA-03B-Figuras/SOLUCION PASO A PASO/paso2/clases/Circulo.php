<?php
/*
 * Circulo.php
 */
require_once __DIR__ . '/Figura.php';

class Circulo extends Figura
{
    private float $radio;

    public function __construct(float $radio, string $color = 'azul')
    {
        // Primero validamos las medidas y después llamamos al constructor del padre: si la medida
        // es incorrecta se lanza la excepción ANTES de contar el objeto en Figura
        $this->setRadio($radio);
        parent::__construct($color);          // inicializa la parte "Figura" del objeto
    }

    public function getRadio(): float
    {
        return $this->radio;
    }

    public function setRadio(float $radio): void
    {
        $this->radio = self::validarMedida($radio, 'radio');
    }

    // ---------------------------------------------------------------------- implementación de los abstractos

    public function nombre(): string
    {
        return 'Círculo';
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
        return "r = " . self::formato($this->radio) . " " . self::UNIDAD;
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

    public static function generarAlAzar(): Circulo
    {
        return new Circulo(self::medidaAlAzar(0.5, 4), self::colorAlAzar());
    }
}

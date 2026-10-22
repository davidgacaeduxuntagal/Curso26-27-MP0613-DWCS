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
        return M_PI * $this->radio ** 2;
    }

    public function perimetro(): float
    {
        return 2 * M_PI * $this->radio;
    }

    protected function medidas(): string
    {
        return "r = " . self::formato($this->radio) . " " . self::UNIDAD;
    }

    // ---------------------------------------------------------------------- Dibujable

    public function dimensionMaxima(): float
    {
        return 2 * $this->radio;
    }

    public function svg(float $escala, int $tamano = 140): string
    {
        $centro = $tamano / 2;
        $r      = $this->radio * $escala;
        return $this->inicioSvg($tamano)
            . "<circle cx='{$centro}' cy='{$centro}' r='{$r}' fill='{$this->colorHtml()}' /></svg>";
    }

    // ---------------------------------------------------------------------- creación al azar

    public static function generarAlAzar(): Circulo
    {
        return new Circulo(self::medidaAlAzar(0.5, 4), self::colorAlAzar());
    }
}

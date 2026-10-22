<?php
/*
 * Cuadrado.php
 *  Un cuadrado ES UN rectángulo con la base igual a la altura: hereda de Rectangulo y reutiliza
 *  área, perímetro y dibujo. Es una clase FINAL: nadie puede heredar de ella.
 */
require_once __DIR__ . '/Rectangulo.php';

final class Cuadrado extends Rectangulo
{
    public function __construct(float $lado, string $color = 'naranja')
    {
        parent::__construct($lado, $lado, $color);
    }

    public function getLado(): float
    {
        return $this->base;
    }

    public function setLado(float $lado): void
    {
        $this->base   = self::validarMedida($lado, 'lado');
        $this->altura = $lado;
    }

    /**
     * Si alguien cambia SOLO la base de un cuadrado, dejaría de ser un cuadrado. Redefinimos los
     * setters heredados para que cambien los dos lados a la vez.
     */
    public function setBase(float $base): void
    {
        $this->setLado($base);
    }

    public function setAltura(float $altura): void
    {
        $this->setLado($altura);
    }

    public function nombre(): string
    {
        return 'Cuadrado';
    }

    protected function medidas(): string
    {
        return "lado = " . self::formato($this->base) . " " . self::UNIDAD;
    }

    // area(), perimetro(), dimensionMaxima() y svg() se HEREDAN de Rectangulo sin cambios

    /**
     * Tipo de retorno Cuadrado: está permitido porque Cuadrado es un subtipo de Rectangulo
     * (el método que redefinimos devolvía Rectangulo).
     */
    public static function generarAlAzar(): Cuadrado
    {
        return new Cuadrado(self::medidaAlAzar(1, 7), self::colorAlAzar());
    }
}

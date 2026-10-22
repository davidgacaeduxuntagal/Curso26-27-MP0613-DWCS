<?php
/*
 * Dibujable.php
 *  Interfaz: un "contrato" que deben cumplir todas las clases que se pueden dibujar.
 *  Quien use un objeto Dibujable sabe que tiene estos dos métodos, sea cual sea su clase.
 */
interface Dibujable
{
    /**
     * Mayor medida (ancho o alto) que ocupa el objeto, en las unidades de la figura.
     * Sirve para calcular una escala común y dibujar todas las figuras proporcionadas.
     */
    public function dimensionMaxima(): float;

    /**
     * Devuelve el código SVG que dibuja el objeto.
     *  - $escala: píxeles por cada unidad (cm)
     *  - $tamano: ancho y alto del dibujo, en píxeles
     */
    public function svg(float $escala, int $tamano = 140): string;
}

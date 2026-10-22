<?php
/*
 * Figura.php
 *  Clase ABSTRACTA base de la jerarquía: no se pueden crear objetos "Figura", solo de sus subclases.
 *  Contiene lo que tienen en común todas las figuras (el color, el contador de objetos, el formato
 *  de __toString...) y DECLARA los métodos que cada figura debe implementar a su manera.
 */
// TODO: require_once __DIR__ . '/Dibujable.php';

// TODO: abstract class Figura implements Dibujable
//   TODO: public const UNIDAD  = 'cm';
//   TODO: public const COLORES = [ ...];
//   TODO: private static array $contadores = [];
//   TODO: protected string $color;
//   TODO: public function __construct(string $color)
//   TODO: public function getColor(): string
//   TODO: public function setColor(string $color): void   -> El setter VALIDA el dato: así un objeto nunca puede quedar con un color incorrecto.
//   TODO: public function colorHtml(): string   -> Código de color HTML (#rrggbb) correspondiente al color de la figura.
//   TODO: abstract public function nombre(): string;
//   TODO: abstract public function area(): float;
//   TODO: abstract public function perimetro(): float;
//   TODO: abstract protected function medidas(): string;   -> Texto con las medidas propias de la figura, por ejemplo "r = 3 cm".
//   TODO: public function __toString(): string   -> Gracias al polimorfismo, este método sirve para TODAS las figuras: llama a nombre(), medidas(),
//   TODO: public static function numeroCreadas(?string $clase = null): int   -> Número de objetos creados de una clase concreta (o de todas las figuras, si no se indica).
//   TODO: public static function generarAlAzar(): Figura   -> Crea una figura AL AZAR de cualquiera de las clases concretas.
//   TODO: public static function compararPorArea(Figura $a, Figura $b): int   -> Función de comparación para usort: de mayor a menor área.
//   TODO: protected static function medidaAlAzar(float $min, float $max): float   -> Medida aleatoria entre $min y $max con un decimal.
//   TODO: protected static function colorAlAzar(): string
//   TODO: protected static function validarMedida(float $valor, string $nombre): float   -> Comprueba que una medida es positiva.
//   TODO: public static function formato(float $numero): string   -> Número con coma decimal y 2 decimales.
//   TODO: protected function inicioSvg(int $tamano): string   -> Abre la etiqueta <svg> común a todos los dibujos.

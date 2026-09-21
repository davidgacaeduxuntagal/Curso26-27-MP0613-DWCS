<?php
require_once 'Trabajador.php';
class Administrativo extends Trabajador
{
    protected static int $numObjetosCreados = 0;
    public function __construct($nombre, $apellido1, $apellido2, $fechaNacimiento, $dni, $direccion, $telefonos, $sexo, $anhosServicio)
    {
        parent::__construct($nombre, $apellido1, $apellido2, $fechaNacimiento, $dni, $direccion, $telefonos, $sexo, $anhosServicio);
        self::$numObjetosCreados++;
    }

    public static function generarAlAzar(): Administrativo
    {
        $sexo = rand(0, 1);
        return new Administrativo($sexo == 0 ? "Pedro" : "Mónica", "López", "Castilla", date('d/m/Y', strtotime('1994-12-23')), "12345678Z", "López Mora 13C", [rand(600000000, 699999999), rand(600000000, 699999999)], $sexo, rand(0, 30));
    }

    public function trabajar(): string
    {
        $genero = $this->sexo == 0 ? "administrativo" : "administrativa";
        return "Soy $genero.";
    }
    public static function numeroObjetosCreado(): int
    {
        return intval(self::$numObjetosCreados);
    }
}
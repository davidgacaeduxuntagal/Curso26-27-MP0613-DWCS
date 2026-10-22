<?php
require_once 'Alumnado.php';
class AlumnadoBachillerato extends Alumnado
{
    protected static int $numObjetosCreados = 0;
    public function __construct($nombre, $apellido1, $apellido2, $fechaNacimiento, $dni, $direccion, $telefonos, $sexo, $curso, $grupo)
    {
        parent::__construct($nombre, $apellido1, $apellido2, $fechaNacimiento, $dni, $direccion, $telefonos, $sexo, $curso, $grupo);
        self::$numObjetosCreados++;
    }


    public static function generarAlAzar(): AlumnadoBachillerato
    {
        $sexo = rand(0, 1);
        $grupo = rand(0, 1) == 0 ? "A" : "B";
        return new AlumnadoBachillerato($sexo == 0 ? "Pedro" : "Mónica", "López", "Castilla", date('d/m/Y', strtotime('1994-12-23')), "12345678Z", "López Mora 13C", [rand(600000000, 699999999), rand(600000000, 699999999)], $sexo, rand(1, 2), $grupo);
    }

    public function trabajar(): string
    {
        $genero = $this->sexo == 0 ? "alumno" : "alumna";
        return "Soy $genero de bachillerato del $this->curso º curso en el grupo $this->grupo.";
    }
    public static function numeroObjetosCreado(): int
    {
        return intval(self::$numObjetosCreados);
    }
}
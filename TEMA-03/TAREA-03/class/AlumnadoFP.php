<?php
require_once 'Alumnado.php';
class AlumnadoFP extends Alumnado
{
    protected static int $numObjetosCreados = 0;
    protected $cicloFormativo; // El ciclo debería ser otro enum
    public function __construct($nombre, $apellido1, $apellido2, $fechaNacimiento, $dni, $direccion, $telefonos, $sexo, $curso, $grupo, $cicloFormativo)
    {
        parent::__construct($nombre, $apellido1, $apellido2, $fechaNacimiento, $dni, $direccion, $telefonos, $sexo, $curso, $grupo);
        $this->cicloFormativo = $cicloFormativo;
        self::$numObjetosCreados++;
    }

    public static function generarAlAzar(): AlumnadoFP
    {
        $sexo = rand(0, 1);
        $grupo = rand(0, 1) == 0 ? "A" : "B";
        return new AlumnadoFP($sexo == 0 ? "Pedro" : "Mónica", "López", "Castilla", date('d/m/Y', strtotime('1994-12-23')), "12345678Z", "López Mora 13C", [rand(600000000, 699999999), rand(600000000, 699999999)], $sexo, rand(1, 2), $grupo, "DAW");
    }

    public function trabajar(): string
    {
        $genero = $this->sexo == 0 ? "alumno" : "alumna";
        return "Soy $genero de FP del $this->curso º curso en el grupo $this->grupo y estudio el ciclo de $this->cicloFormativo";
    }

    public static function numeroObjetosCreado(): int
    {
        return intval(self::$numObjetosCreados);
    }
}
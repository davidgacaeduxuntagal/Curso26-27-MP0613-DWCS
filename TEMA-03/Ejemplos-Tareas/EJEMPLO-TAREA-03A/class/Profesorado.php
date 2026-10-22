<?php
require_once 'Trabajador.php';
require_once 'CargoDirectivo.php';
class Profesorado extends Trabajador
{
    protected static int $numObjetosCreados = 0;
    protected $materias;
    protected CargoDirectivo $cargoDirectivo;
    public function __construct($nombre, $apellido1, $apellido2, $fechaNacimiento, $dni, $direccion, $telefonos, $sexo, $anhosServicio, CargoDirectivo $cargoDirectivo)
    {
        parent::__construct($nombre, $apellido1, $apellido2, $fechaNacimiento, $dni, $direccion, $telefonos, $sexo, $anhosServicio);
        $this->cargoDirectivo = $cargoDirectivo;
        self::$numObjetosCreados++;
    }

    public function getCargoDirectivo(): CargoDirectivo
    {
        return $this->cargoDirectivo;
    }

    public function setCargoDirectivo(CargoDirectivo $cargoDirectivo)
    {
        $this->cargoDirectivo = $cargoDirectivo;
    }

    public static function generarAlAzar(): Profesorado
    {
        $sexo = rand(0, 1);
        $cargos = CargoDirectivo::cases(); // Obtiene todos los casos del enum para pasarle uno aleatorio en la generación

        return new Profesorado($sexo == 0 ? "Pedro" : "Mónica", "López", "Castilla", date('d/m/Y', strtotime('1994-12-23')), "12345678Z", "López Mora 13C", [rand(600000000, 699999999), rand(600000000, 699999999)], $sexo, rand(0, 30), $cargos[array_rand($cargos)]);
    }

    public function trabajar(): string
    {
        $genero = $this->sexo == 0 ? "profesor" : "profesora";
        $cargo = $this->cargoDirectivo === CargoDirectivo::Ninguno
            ? "no tengo ningún cargo."
            : "mi cargo es " . $this->cargoDirectivo->value;

        return "Soy $genero y $cargo";
    }

    public static function numeroObjetosCreado(): int
    {
        return intval(self::$numObjetosCreados);
    }

}
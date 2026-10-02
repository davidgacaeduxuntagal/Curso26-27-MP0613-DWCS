<?php
require_once 'Persona.php';
abstract class Trabajador extends Persona
{
    protected $anhosServicio;

    public function __construct($nombre, $apellido1, $apellido2, $fechaNacimiento, $dni, $direccion, $telefonos, $sexo, $anhosServicio)
    {
        parent::__construct($nombre, $apellido1, $apellido2, $fechaNacimiento, $dni, $direccion, $telefonos, $sexo);
        $this->anhosServicio = $anhosServicio;
    }

    public function getAnhosServicio()
    {
        return $this->anhosServicio;
    }

    public function setAnhosServicio($anhosServicio)
    {
        $this->anhosServicio = $anhosServicio;
    }

}

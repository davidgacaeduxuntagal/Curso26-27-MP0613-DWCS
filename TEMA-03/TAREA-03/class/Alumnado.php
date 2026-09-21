<?php
require_once 'Persona.php';
abstract class Alumnado extends Persona
{
    protected $curso;
    protected $grupo;

    public function __construct($nombre, $apellido1, $apellido2, $fechaNacimiento, $dni, $direccion, $telefonos, $sexo, $curso, $grupo)
    {
        parent::__construct($nombre, $apellido1, $apellido2, $fechaNacimiento, $dni, $direccion, $telefonos, $sexo);
        $this->curso = $curso;
        $this->grupo = $grupo;
    }

}
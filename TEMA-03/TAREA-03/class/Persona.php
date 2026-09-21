<?php
abstract class Persona
{
    protected static int $numObjetosCreados = 0; // Este contador se declarará en cada clase junto al método asociado
    protected string $nombre;
    protected string $apellido1;
    protected string $apellido2;
    protected string $fechaNacimiento;
    protected string $dni;
    protected string $direccion;
    protected array $telefonos;
    protected int $sexo;

    public function __construct($nombre, $apellido1, $apellido2, $fechaNacimiento, $dni, $direccion, $telefonos, $sexo)
    {
        $this->nombre = $nombre;
        $this->apellido1 = $apellido1;
        $this->apellido2 = $apellido2;
        $this->fechaNacimiento = $fechaNacimiento;
        $this->dni = $dni;
        $this->direccion = $direccion;
        $this->telefonos = $telefonos;
        $this->sexo = $sexo;
        self::$numObjetosCreados++;
    }

    // Setters y getters
    public function getNombre()
    {
        return $this->nombre;
    }
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;
    }

    public function getApellido1()
    {
        return $this->apellido1;
    }

    public function setApellido1($apellido1)
    {
        $this->apellido1 = $apellido1;
    }

    public function getApellido2()
    {
        return $this->apellido2;
    }

    public function setApellido2($apellido2)
    {
        $this->apellido2 = $apellido2;
    }

    public function getFechaNacimiento()
    {
        return $this->fechaNacimiento;
    }

    public function setFechaNacimiento($fechaNacimiento)
    {
        $this->fechaNacimiento = $fechaNacimiento;
    }

    public function getDni()
    {
        return $this->dni;
    }

    public function setDni($dni)
    {
        $this->dni = $dni;
    }


    public function getDireccion()
    {
        return $this->direccion;
    }

    public function setDireccion($direccion)
    {
        $this->direccion = $direccion;
    }

    public function getTelefonos()
    {
        return $this->telefonos;
    }

    public function setTelefonos($telefonos)
    {
        $this->telefonos = $telefonos;
    }
    public function getSexo()
    {
        return $this->sexo;
    }

    public function setSexo($sexo)
    {
        $this->sexo = $sexo;
    }

    // No me da tiempo a generar una aleatoriedad mayor, por lo que algunos valores están hardcodeados
    // También estaría bien añadir una carpeta de utilidades para validar los valores
    // Y declarar mejor las variables y los tipos a devolver

    abstract static public function generarAlAzar();

    // Devuelve el número de objetos creados de la clase
    public static function numeroObjetosCreado(): int
    {
        return intval(self::$numObjetosCreados);
    }

    // Método __toString para mostrar información de la persona
    public function __toString()
    {
        $genero = $this->sexo == 0 ? "masculino" : "femenino";
        return "Soy $this->nombre  y mi sexo es $genero.";
    }
    abstract public function trabajar();


}

<?php
/*
 * e12-POO_problema_solucion.php
 *  (FICHERO CON LAS SOLUCIONES)
 *  Problemas de: ReflectionClass, getProperties, getMethods, ReflectionProperty,
 *                ReflectionMethod, ReflectionParameter, newInstanceArgs, getValue / setValue
 *  (repasa los ejemplos de ../Ejercicios OOP/e12-POO.php)
 *
 *  Resuelve cada problema donde se indica y ejecútalo en modo depuración para comprobarlo.
 */
require_once __DIR__ . '/../Ejercicios OOP/utilidades.php';

// Clases de ejemplo para los problemas
class Vehiculo
{
    public function __construct(protected string $matricula, protected int $anio)
    {
    }

    public function antiguedad(int $anioActual = 2026): int
    {
        return $anioActual - $this->anio;
    }
}
final class Coche extends Vehiculo
{
    private int $kilometros = 0;

    public function __construct(string $matricula, int $anio, public string $marca, public ?string $color = null)
    {
        parent::__construct($matricula, $anio);
    }

    public function conducir(int $km): void
    {
        $this->kilometros += $km;
    }

    private function revisar(): string
    {
        return 'revisado';
    }
}

// =============================================================================================
// PROBLEMA 1. Ficha de una clase
//  Con ReflectionClass, muestra de la clase Coche: su clase padre, si es final, y la lista de
//  sus métodos PÚBLICOS indicando, para cada uno, en qué clase está declarado.
// =============================================================================================
titulo('Problema 1');
// completa aquí tu solución
// ---- SOLUCIÓN ----
$rc = new ReflectionClass(Coche::class);
ver('padre', $rc->getParentClass()->getName());        // "Vehiculo"
ver('¿final?', $rc->isFinal());                        // true
$metodos = [];
foreach ($rc->getMethods(ReflectionMethod::IS_PUBLIC) as $m) {
    $metodos[$m->getName()] = $m->getDeclaringClass()->getName();
}
ver('métodos públicos', $metodos);   // ['__construct' => 'Coche', 'conducir' => 'Coche', 'antiguedad' => 'Vehiculo']

// =============================================================================================
// PROBLEMA 2. Volcado completo de un objeto
//  Escribe la función volcado(object $obj): array que devuelva TODAS las propiedades del objeto
//  (también las protected, private y heredadas) como nombre => valor, usando Reflection.
//  Compárala con get_object_vars desde fuera de la clase.
// =============================================================================================
titulo('Problema 2');
$coche = new Coche('1234 KLM', 2019, 'Seat', 'rojo');
$coche->conducir(150);
// completa aquí tu solución
// ---- SOLUCIÓN ----
function volcado(object $obj): array
{
    $datos = [];
    foreach ((new ReflectionObject($obj))->getProperties() as $p) {
        $datos[$p->getName()] = $p->isInitialized($obj) ? $p->getValue($obj) : '(sin inicializar)';
    }
    return $datos;
}
ver('volcado', volcado($coche));               // kilometros 150, marca, color, matricula, anio
ver('get_object_vars', get_object_vars($coche));   // solo las públicas: marca y color

// =============================================================================================
// PROBLEMA 3. Crear objetos a partir de un array (como hacen los frameworks)
//  Escribe la función crearDesdeArray(string $clase, array $datos): object que lea los
//  parámetros del constructor de $clase y los rellene con los valores de $datos (por nombre); si
//  falta uno, usa su valor por defecto, y si no tiene, lanza InvalidArgumentException.
// =============================================================================================
titulo('Problema 3');
// completa aquí tu solución
// ---- SOLUCIÓN ----
function crearDesdeArray(string $clase, array $datos): object
{
    $rc = new ReflectionClass($clase);
    $argumentos = [];
    foreach ($rc->getConstructor()->getParameters() as $parametro) {
        $nombre = $parametro->getName();
        if (array_key_exists($nombre, $datos)) {
            $argumentos[] = $datos[$nombre];
        } elseif ($parametro->isDefaultValueAvailable()) {
            $argumentos[] = $parametro->getDefaultValue();
        } else {
            throw new InvalidArgumentException("Falta el dato obligatorio: $nombre");
        }
    }
    return $rc->newInstanceArgs($argumentos);
}
$desdeFormulario = ['marca' => 'Renault', 'matricula' => '5678 BCD', 'anio' => 2022];
$nuevo = crearDesdeArray(Coche::class, $desdeFormulario);
ver('coche creado', $nuevo);                      // color NULL (valor por defecto)
verError('sin año', fn() => crearDesdeArray(Coche::class, ['marca' => 'Kia', 'matricula' => '1']));
// InvalidArgumentException: Falta el dato obligatorio: anio

// =============================================================================================
// PROBLEMA 4. Ejecutar un método privado en una prueba
//  Para una prueba automática hay que comprobar que el método privado revisar() de Coche
//  devuelve "revisado". ¿Cómo lo llamarías sin cambiar la clase? (Y, ¿por qué no conviene hacer
//  esto en el código normal de la aplicación? Contesta en un comentario.)
// =============================================================================================
titulo('Problema 4');
// completa aquí tu solución
// ---- SOLUCIÓN ----
$metodo = new ReflectionMethod(Coche::class, 'revisar');
ver('revisar()', $metodo->invoke($coche));       // "revisado" (desde PHP 8.1 no hace falta setAccessible)
// No conviene en la aplicación porque se salta la encapsulación: si la clase cambia sus métodos
// privados (que no son parte de su "contrato" público), el código que los usa se rompe. Además,
// Reflection es más lento. Está bien para herramientas: pruebas, depuración, serializadores...

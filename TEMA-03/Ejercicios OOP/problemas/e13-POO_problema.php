<?php
/*
 * e13-POO_problema.php
 *  Problemas de: atributos (#[...]), crear atributos propios, TARGET_* e IS_REPEATABLE, leerlos
 *                con Reflection (getAttributes, getArguments, newInstance, IS_INSTANCEOF),
 *                atributos predefinidos
 *  (repasa los ejemplos de ../Ejercicios OOP/e13-POO.php)
 *
 *  Resuelve cada problema donde se indica y ejecútalo en modo depuración para comprobarlo.
 */
require_once __DIR__ . '/../Ejercicios OOP/utilidades.php';

// =============================================================================================
// PROBLEMA 1. Leer los atributos de una clase
//  La clase Producto tiene atributos que nadie ha definido como clases. Con Reflection, muestra
//  el nombre y los argumentos de cada atributo de la clase y de su propiedad precio. ¿Por qué no
//  da ningún error? Contesta en un comentario.
// =============================================================================================
titulo('Problema 1');
#[Coleccion('productos'), Cacheable(minutos: 30)]
class Producto
{
    public function __construct(
        public string $nombre,
        #[CampoBd('precio_venta'), Moneda('EUR')]
        public float $precio,
    ) {
    }
}
// completa aquí tu solución

// =============================================================================================
// PROBLEMA 2. Un mapeo objeto-tabla (como un mini ORM)
//  Crea los atributos Tabla (TARGET_CLASS, con el nombre de la tabla) y Columna
//  (TARGET_PROPERTY, con el nombre de la columna) y la función sqlInsert(object $obj): string
//  que, leyendo esos atributos con Reflection, genere la sentencia INSERT del objeto. Las
//  propiedades sin #[Columna] no se guardan.
// =============================================================================================
titulo('Problema 2');
// completa aquí tu solución

// =============================================================================================
// PROBLEMA 3. Una regla de validación nueva
//  Partiendo de la interfaz Regla y la función validar() (copiadas del ejemplo), crea el
//  atributo EnLista(array $valores) que compruebe que el valor está en la lista, y úsalo en la
//  clase Matricula para el campo ciclo ('DAW', 'DAM', 'ASIR').
// =============================================================================================
titulo('Problema 3');
interface Regla
{
    public function comprobar(mixed $valor): ?string;
}
function validar(object $objeto): array
{
    $errores = [];
    foreach ((new ReflectionObject($objeto))->getProperties() as $propiedad) {
        foreach ($propiedad->getAttributes(Regla::class, ReflectionAttribute::IS_INSTANCEOF) as $atributo) {
            $mensaje = $atributo->newInstance()->comprobar($propiedad->getValue($objeto));
            if ($mensaje !== null) {
                $errores[$propiedad->getName()][] = $mensaje;
            }
        }
    }
    return $errores;
}
// completa aquí tu solución

// =============================================================================================
// PROBLEMA 4. Comandos de consola con atributos
//  Crea el atributo repetible Comando(string $nombre, string $ayuda) para MÉTODOS y una clase
//  Herramientas con dos métodos marcados (uno de ellos con dos nombres: "limpiar" y "clean").
//  Escribe ayuda(string $clase): array que devuelva nombre => texto de ayuda de todos los
//  comandos, y ejecutar(object $obj, string $comando): string que llame al método adecuado.
// =============================================================================================
titulo('Problema 4');
// completa aquí tu solución

// =============================================================================================
// PROBLEMA 5. ¿Cómo harías...?
//  Una función conectar(string $host, string $usuario, string $clave) lanza excepciones cuyos
//  mensajes y trazas se guardan en un log. ¿Cómo evitarías que la contraseña aparezca en la
//  traza? Escríbelo y compruébalo con getTraceAsString().
// =============================================================================================
titulo('Problema 5');
// completa aquí tu solución

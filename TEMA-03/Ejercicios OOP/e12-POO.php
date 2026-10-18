<?php
/*
 * e12-POO.php
 *  POO (12): REFLECTION API (introspección)
 *  Contenidos: ReflectionClass (nombre, padre, interfaces, abstracta, final...), getProperties,
 *              getMethods, ReflectionProperty (tipo, visibilidad, getValue / setValue),
 *              ReflectionMethod (parámetros, tipos, invoke), ReflectionNamedType,
 *              ReflectionParameter, newInstanceArgs, ReflectionObject, ReflectionFunction;
 *              "reflexión indirecta" (call_user_func, $obj->$metodo())
 *  (apartado 16 de los apuntes)
 *
 *  Cómo trabajar este fichero:
 *   1. Pon un punto de ruptura en la primera línea de código y ejecuta en modo depuración.
 *   2. Despliega los objetos Reflection* en el panel VARIABLES: solo muestran "name" (y "class"),
 *      el resto de la información se obtiene llamando a sus métodos.
 *   3. Los comentarios a la derecha indican el resultado esperado.
 */
require_once __DIR__ . '/utilidades.php';

// Clases de ejemplo (un pequeño blog, como en el material de referencia)
interface Publicable
{
    public function publicar(): bool;
}
abstract class Contenido
{
    protected ?string $publicadoEl = null;

    public function estaPublicado(): bool
    {
        return $this->publicadoEl !== null;
    }
}
final class Articulo extends Contenido implements Publicable, Countable
{
    public const ESTADOS = ['borrador', 'publicado'];
    private static int $creados = 0;

    public function __construct(
        public string $titulo,
        private string $autor,
        protected array $etiquetas = [],
        public ?int $id = null,
    ) {
        self::$creados++;
    }

    public function publicar(): bool
    {
        $this->publicadoEl = '2026-10-16 12:00';
        return true;
    }

    public function count(): int
    {
        return count($this->etiquetas);
    }

    public function etiquetar(string $etiqueta, bool $principal = false): static
    {
        $principal ? array_unshift($this->etiquetas, $etiqueta) : $this->etiquetas[] = $etiqueta;
        return $this;
    }

    private function slug(): string
    {
        return strtolower(str_replace(' ', '-', $this->titulo));
    }
}

// ---------------------------------------------------------------------------------------------
// ReflectionClass — Información sobre una clase
// ENLACE: https://www.php.net/manual/es/class.reflectionclass.php
// ENUNCIADO EJEMPLO: generar automáticamente una "ficha" de documentación de la clase Articulo.
// NOTA: Reflection permite que el programa examine su propio código mientras se ejecuta: qué
//       propiedades, métodos, parámetros y tipos tiene una clase. Lo usan los frameworks, las
//       herramientas de pruebas (PHPUnit), los generadores de documentación... y los atributos (e13).
// ---------------------------------------------------------------------------------------------
titulo('ReflectionClass');
$clase = new ReflectionClass(Articulo::class);       // también vale new ReflectionClass($objeto)
ver('nombre', $clase->getName());                    // "Articulo"
ver('padre', $clase->getParentClass()->getName());   // "Contenido"
ver('interfaces', $clase->getInterfaceNames());      // ['Publicable', 'Countable']
ver('¿final?', $clase->isFinal());                   // true
ver('¿abstracta la padre?', $clase->getParentClass()->isAbstract());   // true
ver('¿implementa Countable?', $clase->implementsInterface(Countable::class));   // true
ver('constantes', $clase->getConstants());           // ['ESTADOS' => ['borrador', 'publicado']]
ver('fichero y líneas', basename($clase->getFileName()) . ' ' . $clase->getStartLine() . '-' . $clase->getEndLine());

// ---------------------------------------------------------------------------------------------
// getProperties y ReflectionProperty
// ENLACE: https://www.php.net/manual/es/class.reflectionproperty.php
// ENUNCIADO EJEMPLO: listar todas las propiedades con su visibilidad y su tipo.
// NOTA: a diferencia de get_object_vars, Reflection ve TODO (también lo privado y lo estático).
// ---------------------------------------------------------------------------------------------
titulo('Propiedades');
$propiedades = [];
foreach ($clase->getProperties() as $propiedad) {
    $visibilidad = $propiedad->isPublic() ? 'public' : ($propiedad->isProtected() ? 'protected' : 'private');
    $propiedades[$propiedad->getName()] = trim(
        $visibilidad . ($propiedad->isStatic() ? ' static' : '') . ' ' . $propiedad->getType()
        . ($propiedad->isPromoted() ? ' (promocionada)' : '')
        . ' — declarada en ' . $propiedad->getDeclaringClass()->getName()
    );
}
ver('propiedades', $propiedades);
// creados: private static int; titulo: public string (promocionada); autor: private string
// (promocionada); ...; publicadoEl: protected ?string — declarada en Contenido (heredada)

// ---------------------------------------------------------------------------------------------
// getMethods, ReflectionMethod y ReflectionParameter
// ENLACE: https://www.php.net/manual/es/class.reflectionmethod.php
// ENLACE: https://www.php.net/manual/es/class.reflectionparameter.php
// ENUNCIADO EJEMPLO: escribir la "firma" de cada método público, como en la documentación.
// ---------------------------------------------------------------------------------------------
titulo('Métodos y parámetros');
$firmas = [];
foreach ($clase->getMethods(ReflectionMethod::IS_PUBLIC) as $metodo) {
    $parametros = array_map(function (ReflectionParameter $p) {
        $texto = $p->getType() . ' $' . $p->getName();
        if ($p->isDefaultValueAvailable()) {
            $texto .= ' = ' . json_encode($p->getDefaultValue());
        }
        return $texto;
    }, $metodo->getParameters());
    $retorno = $metodo->hasReturnType() ? ': ' . $metodo->getReturnType() : '';
    $firmas[] = $metodo->getName() . '(' . implode(', ', $parametros) . ')' . $retorno;
}
ver('firmas', $firmas);
// ['__construct(string $titulo, string $autor, array $etiquetas = [], ?int $id = null)', 'publicar(): bool', ...,
//  'estaPublicado(): bool' (heredado de Contenido)]
$tipo = (new ReflectionMethod(Articulo::class, 'etiquetar'))->getParameters()[1]->getType();
ver('tipo del 2º parámetro de etiquetar', [get_class($tipo), $tipo->getName(), $tipo->allowsNull(), $tipo->isBuiltin()]);
// ['ReflectionNamedType', 'bool', false, true]

// ---------------------------------------------------------------------------------------------
// newInstanceArgs, getValue / setValue e invoke — MODIFICAR y EJECUTAR con Reflection
// ENLACE: https://www.php.net/manual/es/reflectionclass.newinstanceargs.php
// ENLACE: https://www.php.net/manual/es/reflectionproperty.getvalue.php
// ENLACE: https://www.php.net/manual/es/reflectionmethod.invoke.php
// ENUNCIADO EJEMPLO: crear un artículo a partir de un array de datos y leer/cambiar su autor
//                   privado (el ejemplo del material de referencia cambia "Diego" por "Helena").
// NOTA: desde PHP 8.1 ya NO hace falta setAccessible(true) para acceder a lo privado con
//       Reflection. Saltarse la encapsulación así solo está justificado en herramientas
//       (pruebas, serializadores...), nunca en el código normal de la aplicación.
// ---------------------------------------------------------------------------------------------
titulo('Modificar y ejecutar');
$articulo = $clase->newInstanceArgs(['titulo' => 'Mi primer post', 'autor' => 'Diego']);   // con nombres
ver('articulo', $articulo);
$autor = $clase->getProperty('autor');
ver('autor (privado)', $autor->getValue($articulo));     // "Diego"
$autor->setValue($articulo, 'Helena');
ver('autor cambiado', $autor->getValue($articulo));      // "Helena"
$slug = $clase->getMethod('slug');                       // método privado
ver('slug() con invoke', $slug->invoke($articulo));      // "mi-primer-post"
ver('creados (estática privada)', $clase->getStaticPropertyValue('creados'));   // 1

// ---------------------------------------------------------------------------------------------
// ReflectionObject y ReflectionFunction
// ENLACE: https://www.php.net/manual/es/class.reflectionobject.php
// ENLACE: https://www.php.net/manual/es/class.reflectionfunction.php
// ENUNCIADO EJEMPLO: inspeccionar un objeto concreto (incluidas sus propiedades dinámicas) y una
//                   función anónima.
// ---------------------------------------------------------------------------------------------
titulo('ReflectionObject y ReflectionFunction');
$config = json_decode('{"tienda": "Tecno Baio", "iva": 0.21}');      // stdClass
$objeto = new ReflectionObject($config);
ver('propiedades del stdClass', array_map(fn($p) => $p->getName(), $objeto->getProperties()));   // ['tienda', 'iva']
$conIva = fn(float $precio, float $iva = 0.21): float => round($precio * (1 + $iva), 2);
$funcion = new ReflectionFunction($conIva);
ver('parámetros', $funcion->getNumberOfParameters());            // 2
ver('obligatorios', $funcion->getNumberOfRequiredParameters());  // 1
ver('retorno', (string) $funcion->getReturnType());              // "float"

// ---------------------------------------------------------------------------------------------
// "Reflexión indirecta": llamar a métodos cuyo nombre está en una variable
// ENLACE: https://www.php.net/manual/es/function.call-user-func.php
// ENUNCIADO EJEMPLO: ejecutar la acción que llega en la URL (?accion=publicar) solo si está en una
//                   lista blanca.
// NOTA: $obj->$metodo() o call_user_func([$obj, $metodo]) llaman al método cuyo nombre está en
//       la variable. ¡Nunca con un nombre que llegue del usuario sin comprobarlo antes!
// ---------------------------------------------------------------------------------------------
titulo('Llamadas dinámicas');
$accion = 'publicar';                                   // imagina que viene de $_GET['accion']
$permitidas = ['publicar', 'count'];
if (in_array($accion, $permitidas, true) && method_exists($articulo, $accion)) {
    ver("\$articulo->$accion()", $articulo->$accion());     // true
}
ver('call_user_func', call_user_func([$articulo, 'estaPublicado']));   // true
ver('call_user_func_array', call_user_func_array([$articulo, 'etiquetar'], ['php', true])->count());   // 1

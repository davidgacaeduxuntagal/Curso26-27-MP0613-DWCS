<?php
/*
 * e13-POO.php
 *  POO (13): ATRIBUTOS (PHP 8.0) Y SU RELACIÓN CON LA REFLECTION API
 *  Contenidos: sintaxis #[...], atributos predefinidos (#[Attribute], #[AllowDynamicProperties],
 *              #[SensitiveParameter]), crear atributos propios, destinos (TARGET_*) y
 *              IS_REPEATABLE, leer atributos con Reflection (getAttributes, getName,
 *              getArguments, newInstance, filtrar por clase o interfaz), un validador declarativo
 *              y un enrutador (router) basados en atributos
 *  (apartado 17 de los apuntes)
 *
 *  IDEA CLAVE: un atributo es una ETIQUETA con datos que se pega a una clase, propiedad, método,
 *  parámetro, función o constante. Por sí solo NO HACE NADA: es información ("metadatos") que
 *  otro código lee con la Reflection API y usa para decidir qué hacer. Así trabajan Symfony,
 *  Doctrine, PHPUnit, Laravel...
 *
 *  Cómo trabajar este fichero:
 *   1. Pon un punto de ruptura en la primera línea de código y ejecuta en modo depuración.
 *   2. Pon otro punto de ruptura dentro del constructor de un atributo (por ejemplo, Longitud):
 *      verás que NO se ejecuta al crear el objeto Cliente, sino solo cuando el validador llama a
 *      newInstance().
 */
require_once __DIR__ . '/utilidades.php';

// ---------------------------------------------------------------------------------------------
// 1. Atributos predefinidos de PHP
// ENLACE: https://www.php.net/manual/es/language.attributes.overview.php
// ENLACE: https://www.php.net/manual/es/class.allowdynamicproperties.php
// ENLACE: https://www.php.net/manual/es/class.sensitiveparameter.php
// ENUNCIADO EJEMPLO: permitir propiedades dinámicas en una clase concreta y ocultar la
//                   contraseña en las trazas de error.
// NOTA: los atributos que PHP conoce sí tienen efecto, porque el propio PHP los lee.
//       #[AllowDynamicProperties] (8.2) evita el aviso de propiedades dinámicas (e01).
//       #[SensitiveParameter] (8.2) sustituye el valor del parámetro en las trazas de las
//       excepciones (que podrían acabar en un log). Otros: #[ReturnTypeWillChange] (8.1),
//       #[Override] (8.3) y #[Deprecated] (8.4), que no existen en PHP 8.2.
// ---------------------------------------------------------------------------------------------
titulo('Atributos predefinidos');
#[AllowDynamicProperties]
class FilaCsv
{
}
$fila = new FilaCsv();
$fila->nombre = 'Ana';                          // sin aviso Deprecated gracias al atributo
$fila->email = 'ana@example.com';
ver('fila', $fila);

function iniciarSesion(string $usuario, #[SensitiveParameter] string $clave): bool
{
    throw new RuntimeException('Servidor de autenticación caído');
}
try {
    iniciarSesion('ana', 'MiClaveSecreta123');
} catch (RuntimeException $e) {
    $argumentos = $e->getTrace()[0]['args'];
    ver('argumentos en la traza', [$argumentos[0], get_class($argumentos[1])]);   // ['ana', 'SensitiveParameterValue']
    ver('¿aparece la clave en la traza?', str_contains($e->getTraceAsString(), 'MiClaveSecreta123'));   // false
}

// ---------------------------------------------------------------------------------------------
// 2. Un atributo por sí solo no hace nada
// ENLACE: https://www.php.net/manual/es/language.attributes.syntax.php
// ENUNCIADO EJEMPLO: poner un atributo inventado a una clase y ver qué pasa.
// NOTA: PHP NO comprueba los atributos al compilar: se puede escribir #[LoQueSea(1, 2)] y no
//       pasa nada. Con Reflection se lee su nombre y sus argumentos; solo al llamar a
//       newInstance() PHP necesita que exista una clase con ese nombre marcada con #[Attribute].
// ---------------------------------------------------------------------------------------------
titulo('Un atributo no hace nada solo');
#[Inventado('hola', numero: 42)]
class ClaseEtiquetada
{
}
$atributo = (new ReflectionClass(ClaseEtiquetada::class))->getAttributes()[0];
ver('getName', $atributo->getName());             // "Inventado"
ver('getArguments', $atributo->getArguments());   // [0 => 'hola', 'numero' => 42]
verError('newInstance', fn() => $atributo->newInstance());   // Error: Attribute class "Inventado" not found

// ---------------------------------------------------------------------------------------------
// 3. Crear atributos propios: un validador declarativo
// ENLACE: https://www.php.net/manual/es/language.attributes.classes.php
// ENUNCIADO EJEMPLO: en lugar de escribir a mano un if para cada campo de cada formulario, poner
//                   las reglas JUNTO a cada propiedad y que un validador genérico las aplique.
// NOTA: un atributo es una clase normal marcada con #[Attribute]. Con Attribute::TARGET_* se
//       indica dónde se puede usar y con Attribute::IS_REPEATABLE si se puede poner varias veces
//       en el mismo sitio. Aquí todas implementan una interfaz común, Regla.
// ---------------------------------------------------------------------------------------------
titulo('Atributos propios');
interface Regla
{
    /** Devuelve el mensaje de error, o null si el valor cumple la regla. */
    public function comprobar(mixed $valor): ?string;
}

#[Attribute(Attribute::TARGET_PROPERTY)]
final class Requerido implements Regla
{
    public function comprobar(mixed $valor): ?string
    {
        return ($valor === null || $valor === '') ? 'es obligatorio' : null;
    }
}

#[Attribute(Attribute::TARGET_PROPERTY)]
final class Longitud implements Regla
{
    public function __construct(public int $min = 0, public int $max = PHP_INT_MAX)
    {
        // pon aquí un punto de ruptura: ¿cuándo se ejecuta?
    }

    public function comprobar(mixed $valor): ?string
    {
        $n = mb_strlen((string) $valor);
        return ($n < $this->min || $n > $this->max) ? "debe tener entre $this->min y $this->max caracteres" : null;
    }
}

#[Attribute(Attribute::TARGET_PROPERTY)]
final class Email implements Regla
{
    public function comprobar(mixed $valor): ?string
    {
        return ($valor !== '' && filter_var($valor, FILTER_VALIDATE_EMAIL) === false) ? 'no es un correo válido' : null;
    }
}

#[Attribute(Attribute::TARGET_PROPERTY | Attribute::IS_REPEATABLE)]
final class Patron implements Regla
{
    public function __construct(public string $regex, public string $mensaje)
    {
    }

    public function comprobar(mixed $valor): ?string
    {
        return ($valor !== '' && !preg_match($this->regex, (string) $valor)) ? $this->mensaje : null;
    }
}

#[Attribute(Attribute::TARGET_PROPERTY)]
final class Rango implements Regla
{
    public function __construct(public int|float $min, public int|float $max)
    {
    }

    public function comprobar(mixed $valor): ?string
    {
        return ($valor < $this->min || $valor > $this->max) ? "debe estar entre $this->min y $this->max" : null;
    }
}

// La clase de datos: las reglas se LEEN junto a cada propiedad
final class AltaCliente
{
    public function __construct(
        #[Requerido, Longitud(min: 2, max: 40)]
        public string $nombre = '',
        #[Requerido, Email]
        public string $email = '',
        #[Patron('/^\d{9}$/', 'debe tener 9 cifras'), Patron('/^[6789]/', 'debe empezar por 6, 7, 8 o 9')]
        public string $telefono = '',
        #[Rango(18, 120)]
        public int $edad = 18,
    ) {
    }
}

// ---------------------------------------------------------------------------------------------
// 4. Leer los atributos con Reflection: el validador genérico
// ENLACE: https://www.php.net/manual/es/reflectionproperty.getattributes.php
// ENLACE: https://www.php.net/manual/es/class.reflectionattribute.php
// ENUNCIADO EJEMPLO: validar() sirve para CUALQUIER clase que use estas reglas.
// NOTA: getAttributes(Regla::class, ReflectionAttribute::IS_INSTANCEOF) devuelve solo los
//       atributos cuya clase implementa Regla. newInstance() crea el objeto del atributo (con los
//       argumentos escritos en #[...]) y entonces se puede llamar a sus métodos.
// ---------------------------------------------------------------------------------------------
titulo('Validador con Reflection');
function validar(object $objeto): array
{
    $errores = [];
    foreach ((new ReflectionObject($objeto))->getProperties() as $propiedad) {
        $atributos = $propiedad->getAttributes(Regla::class, ReflectionAttribute::IS_INSTANCEOF);
        foreach ($atributos as $atributo) {
            $regla = $atributo->newInstance();                 // aquí se ejecuta el constructor del atributo
            $mensaje = $regla->comprobar($propiedad->getValue($objeto));
            if ($mensaje !== null) {
                $errores[$propiedad->getName()][] = $mensaje;
            }
        }
    }
    return $errores;
}

$correcto = new AltaCliente('Ana Pérez', 'ana@example.com', '600123456', 34);
ver('alta correcta', validar($correcto));     // []
$incorrecto = new AltaCliente('A', 'ana@', '12345', 15);
ver('alta incorrecta', validar($incorrecto));
// nombre: entre 2 y 40; email: no es un correo válido; telefono: 9 cifras y empezar por 6-9; edad: entre 18 y 120
ver('vacío', validar(new AltaCliente()));     // nombre y email: obligatorio (y longitud del nombre)

// Ver los atributos de una propiedad "en crudo"
$telefono = new ReflectionProperty(AltaCliente::class, 'telefono');
foreach ($telefono->getAttributes() as $a) {
    ver('atributo de telefono', $a->getName() . '(' . implode(', ', array_map(fn($x) => "'$x'", $a->getArguments())) . ')');
}
// "Patron('/^\d{9}$/', 'debe tener 9 cifras')" y "Patron('/^[6789]/', 'debe empezar por 6, 7, 8 o 9')"

// ---------------------------------------------------------------------------------------------
// 5. Los errores de los atributos aparecen al hacer newInstance()
// ENLACE: https://www.php.net/manual/es/language.attributes.classes.php
// ENUNCIADO EJEMPLO: usar un atributo donde no se permite, o repetir uno que no es repetible.
// ---------------------------------------------------------------------------------------------
titulo('Destino y repetición');
#[Requerido]                                   // Requerido solo admite TARGET_PROPERTY
final class MalUso
{
    #[Email, Email]                            // Email no es IS_REPEATABLE
    public string $correo = '';
}
verError('Requerido en una clase', fn() => (new ReflectionClass(MalUso::class))->getAttributes()[0]->newInstance());
// Error: Attribute "Requerido" cannot target class (allowed targets: property)
verError('Email repetido', fn() => (new ReflectionProperty(MalUso::class, 'correo'))->getAttributes()[0]->newInstance());
// Error: Attribute "Email" must not be repeated

// ---------------------------------------------------------------------------------------------
// 6. Otro uso real: un enrutador (router) con atributos en los métodos
// ENLACE: https://www.php.net/manual/es/reflectionmethod.getattributes.php
// ENUNCIADO EJEMPLO: decidir qué método de un controlador atiende cada URL, como hace Symfony
//                   con #[Route('/productos')].
// NOTA: cada método lleva un #[Ruta] con el método HTTP y la URL. El enrutador recorre los
//       métodos con Reflection, construye la tabla de rutas y llama al método adecuado.
// ---------------------------------------------------------------------------------------------
titulo('Enrutador con atributos');
#[Attribute(Attribute::TARGET_METHOD | Attribute::IS_REPEATABLE)]
final class Ruta
{
    public function __construct(public string $metodo, public string $ruta)
    {
    }
}

final class ControladorProductos
{
    #[Ruta('GET', '/productos')]
    public function listar(): string
    {
        return 'Listado de productos';
    }

    #[Ruta('GET', '/productos/{id}')]
    public function ver(string $id): string
    {
        return "Ficha del producto $id";
    }

    #[Ruta('POST', '/productos')]
    #[Ruta('PUT', '/productos')]                 // repetible: el mismo método atiende dos rutas
    public function guardar(): string
    {
        return 'Producto guardado';
    }

    public function auxiliar(): void             // sin atributo: no es una ruta
    {
    }
}

function tablaDeRutas(string $controlador): array
{
    $rutas = [];
    foreach ((new ReflectionClass($controlador))->getMethods() as $metodo) {
        foreach ($metodo->getAttributes(Ruta::class) as $atributo) {
            $ruta = $atributo->newInstance();
            $rutas[] = ['metodo' => $ruta->metodo, 'ruta' => $ruta->ruta, 'accion' => $metodo->getName()];
        }
    }
    return $rutas;
}

function despachar(array $rutas, object $controlador, string $metodoHttp, string $url): string
{
    foreach ($rutas as $r) {
        // "/productos/{id}" -> expresión regular "#^/productos/(?<id>[^/]+)$#"
        $regex = '#^' . preg_replace('#\{(\w+)\}#', '(?<$1>[^/]+)', $r['ruta']) . '$#';
        if ($r['metodo'] === $metodoHttp && preg_match($regex, $url, $coincidencias)) {
            $parametros = array_filter($coincidencias, 'is_string', ARRAY_FILTER_USE_KEY);   // solo los con nombre
            return $controlador->{$r['accion']}(...$parametros);                          // argumentos con nombre
        }
    }
    return '404 No encontrado';
}

$rutas = tablaDeRutas(ControladorProductos::class);
ver('tabla de rutas', $rutas);
$controlador = new ControladorProductos();
ver('GET /productos', despachar($rutas, $controlador, 'GET', '/productos'));         // "Listado de productos"
ver('GET /productos/27', despachar($rutas, $controlador, 'GET', '/productos/27'));   // "Ficha del producto 27"
ver('PUT /productos', despachar($rutas, $controlador, 'PUT', '/productos'));         // "Producto guardado"
ver('DELETE /productos', despachar($rutas, $controlador, 'DELETE', '/productos'));   // "404 No encontrado"

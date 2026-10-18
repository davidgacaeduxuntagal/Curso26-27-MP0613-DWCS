<?php
/*
 * e13-POO_problema_solucion.php
 *  (FICHERO CON LAS SOLUCIONES)
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
// ---- SOLUCIÓN ----
$deClase = (new ReflectionClass(Producto::class))->getAttributes();
$dePrecio = (new ReflectionProperty(Producto::class, 'precio'))->getAttributes();
ver('de la clase', array_map(fn($a) => [$a->getName(), $a->getArguments()], $deClase));
// [['Coleccion', ['productos']], ['Cacheable', ['minutos' => 30]]]
ver('de precio', array_map(fn($a) => [$a->getName(), $a->getArguments()], $dePrecio));
// [['CampoBd', ['precio_venta']], ['Moneda', ['EUR']]]
// No da error porque PHP no comprueba los atributos: solo son datos. Daría error al llamar a
// newInstance(), porque no existen clases Coleccion, Cacheable, CampoBd ni Moneda.

// =============================================================================================
// PROBLEMA 2. Un mapeo objeto-tabla (como un mini ORM)
//  Crea los atributos Tabla (TARGET_CLASS, con el nombre de la tabla) y Columna
//  (TARGET_PROPERTY, con el nombre de la columna) y la función sqlInsert(object $obj): string
//  que, leyendo esos atributos con Reflection, genere la sentencia INSERT del objeto. Las
//  propiedades sin #[Columna] no se guardan.
// =============================================================================================
titulo('Problema 2');
// completa aquí tu solución
// ---- SOLUCIÓN ----
#[Attribute(Attribute::TARGET_CLASS)]
final class Tabla
{
    public function __construct(public string $nombre)
    {
    }
}
#[Attribute(Attribute::TARGET_PROPERTY)]
final class Columna
{
    public function __construct(public string $nombre)
    {
    }
}
#[Tabla('clientes')]
class Cliente
{
    public function __construct(
        #[Columna('nombre_completo')] public string $nombre,
        #[Columna('correo')] public string $email,
        public bool $seleccionado = false,              // sin #[Columna]: no se guarda
    ) {
    }
}
function sqlInsert(object $obj): string
{
    $rc = new ReflectionObject($obj);
    $tabla = $rc->getAttributes(Tabla::class)[0]->newInstance()->nombre;
    $columnas = [];
    foreach ($rc->getProperties() as $propiedad) {
        $atributos = $propiedad->getAttributes(Columna::class);
        if ($atributos !== []) {
            $columnas[$atributos[0]->newInstance()->nombre] = $propiedad->getValue($obj);
        }
    }
    $valores = array_map(fn($v) => "'" . str_replace("'", "''", (string) $v) . "'", $columnas);
    return "INSERT INTO $tabla (" . implode(', ', array_keys($columnas)) . ') VALUES (' . implode(', ', $valores) . ')';
}
ver('sql', sqlInsert(new Cliente("Xosé O'Neill", 'xose@example.com')));
// "INSERT INTO clientes (nombre_completo, correo) VALUES ('Xosé O''Neill', 'xose@example.com')"
// (En una aplicación real se usarían consultas preparadas de PDO, tema 4, no valores dentro del texto.)

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
// ---- SOLUCIÓN ----
#[Attribute(Attribute::TARGET_PROPERTY)]
final class EnLista implements Regla
{
    public function __construct(public array $valores)
    {
    }

    public function comprobar(mixed $valor): ?string
    {
        return in_array($valor, $this->valores, true) ? null : 'debe ser uno de: ' . implode(', ', $this->valores);
    }
}
class Matricula
{
    public function __construct(
        public string $alumno,
        #[EnLista(['DAW', 'DAM', 'ASIR'])] public string $ciclo,
    ) {
    }
}
ver('DAW', validar(new Matricula('Ana', 'DAW')));      // []
ver('SMR', validar(new Matricula('Brais', 'SMR')));    // ['ciclo' => ['debe ser uno de: DAW, DAM, ASIR']]

// =============================================================================================
// PROBLEMA 4. Comandos de consola con atributos
//  Crea el atributo repetible Comando(string $nombre, string $ayuda) para MÉTODOS y una clase
//  Herramientas con dos métodos marcados (uno de ellos con dos nombres: "limpiar" y "clean").
//  Escribe ayuda(string $clase): array que devuelva nombre => texto de ayuda de todos los
//  comandos, y ejecutar(object $obj, string $comando): string que llame al método adecuado.
// =============================================================================================
titulo('Problema 4');
// completa aquí tu solución
// ---- SOLUCIÓN ----
#[Attribute(Attribute::TARGET_METHOD | Attribute::IS_REPEATABLE)]
final class Comando
{
    public function __construct(public string $nombre, public string $ayuda)
    {
    }
}
class Herramientas
{
    #[Comando('limpiar', 'Borra la caché')]
    #[Comando('clean', 'Alias en inglés de limpiar')]
    public function limpiarCache(): string
    {
        return 'Caché borrada';
    }

    #[Comando('version', 'Muestra la versión de PHP')]
    public function version(): string
    {
        return 'PHP ' . PHP_MAJOR_VERSION . '.' . PHP_MINOR_VERSION;
    }
}
function comandos(string $clase): array
{
    $lista = [];
    foreach ((new ReflectionClass($clase))->getMethods() as $metodo) {
        foreach ($metodo->getAttributes(Comando::class) as $atributo) {
            $comando = $atributo->newInstance();
            $lista[$comando->nombre] = ['ayuda' => $comando->ayuda, 'metodo' => $metodo->getName()];
        }
    }
    return $lista;
}
function ayuda(string $clase): array
{
    return array_map(fn($c) => $c['ayuda'], comandos($clase));
}
function ejecutar(object $obj, string $comando): string
{
    $lista = comandos(get_class($obj));
    return isset($lista[$comando]) ? $obj->{$lista[$comando]['metodo']}() : "Comando desconocido: $comando";
}
ver('ayuda', ayuda(Herramientas::class));     // ['limpiar' => 'Borra la caché', 'clean' => ..., 'version' => ...]
$h = new Herramientas();
ver('clean', ejecutar($h, 'clean'));          // "Caché borrada"
ver('version', ejecutar($h, 'version'));      // "PHP 8.2"
ver('borrar', ejecutar($h, 'borrar'));        // "Comando desconocido: borrar"

// =============================================================================================
// PROBLEMA 5. ¿Cómo harías...?
//  Una función conectar(string $host, string $usuario, string $clave) lanza excepciones cuyos
//  mensajes y trazas se guardan en un log. ¿Cómo evitarías que la contraseña aparezca en la
//  traza? Escríbelo y compruébalo con getTraceAsString().
// =============================================================================================
titulo('Problema 5');
// completa aquí tu solución
// ---- SOLUCIÓN ----
function conectar(string $host, string $usuario, #[SensitiveParameter] string $clave): void
{
    throw new RuntimeException("No se puede conectar a $host");
}
try {
    conectar('db.example.com', 'tienda', 'P4ssw0rd!');
} catch (RuntimeException $e) {
    ver('¿aparece la clave?', str_contains($e->getTraceAsString(), 'P4ssw0rd!'));   // false
    ver('traza', $e->getTraceAsString());   // ...conectar('db.example.com', 'tienda', Object(SensitiveParameterValue))...
}

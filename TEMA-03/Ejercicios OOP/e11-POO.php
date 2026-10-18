<?php
/*
 * e11-POO.php
 *  POO (11): SPL (Standard PHP Library) Y GENERADORES
 *  Contenidos: Iterator, IteratorAggregate, ArrayAccess, Countable; iteradores de la SPL
 *              (ArrayIterator, FilterIterator / CallbackFilterIterator, LimitIterator,
 *              AppendIterator); estructuras de datos (SplStack, SplQueue, SplObjectStorage,
 *              SplFileObject); generadores (yield, claves, yield from, send, ahorro de memoria)
 *  (apartado 15 de los apuntes)
 *
 *  Cómo trabajar este fichero:
 *   1. Pon puntos de ruptura DENTRO de los métodos rewind, valid, current, key y next del
 *      iterador, y DENTRO de las funciones generadoras: verás en qué orden los llama foreach.
 *   2. Los comentarios a la derecha indican el resultado esperado.
 */
require_once __DIR__ . '/utilidades.php';

// ---------------------------------------------------------------------------------------------
// Iterator — Un objeto que se recorre con foreach, controlando cada paso
// ENLACE: https://www.php.net/manual/es/class.iterator.php
// ENUNCIADO EJEMPLO: recorrer las páginas de resultados de un catálogo: cada vuelta devuelve una
//                   página (un array de productos).
// NOTA: foreach llama a rewind(), y después, en cada vuelta: valid() -> current() -> key() ->
//       (cuerpo del bucle) -> next(). El ejemplo guarda la secuencia de llamadas para verla.
// ---------------------------------------------------------------------------------------------
titulo('Iterator');
class Paginador implements Iterator
{
    private int $pagina = 0;
    public array $llamadas = [];               // solo para ver el orden de las llamadas

    public function __construct(private array $elementos, private int $porPagina)
    {
    }

    public function rewind(): void
    {
        $this->llamadas[] = 'rewind';
        $this->pagina = 0;
    }

    public function valid(): bool
    {
        $this->llamadas[] = 'valid';
        return $this->pagina * $this->porPagina < count($this->elementos);
    }

    public function current(): array
    {
        $this->llamadas[] = 'current';
        return array_slice($this->elementos, $this->pagina * $this->porPagina, $this->porPagina);
    }

    public function key(): int
    {
        $this->llamadas[] = 'key';
        return $this->pagina + 1;              // las páginas empiezan en 1
    }

    public function next(): void
    {
        $this->llamadas[] = 'next';
        $this->pagina++;
    }
}
$catalogo = ['Ratón', 'Teclado', 'Monitor', 'Cascos', 'Webcam'];
$paginas = new Paginador($catalogo, 2);
foreach ($paginas as $numero => $productos) {
    echo "  página $numero: ", implode(', ', $productos), nl();     // 1: Ratón, Teclado / 2: Monitor, Cascos / 3: Webcam
}
ver('orden de las llamadas', implode(' ', $paginas->llamadas));
// "rewind valid current key next valid current key next valid current key next valid"

// ---------------------------------------------------------------------------------------------
// IteratorAggregate, ArrayAccess y Countable — Una colección que se comporta como un array
// ENLACE: https://www.php.net/manual/es/class.iteratoraggregate.php
// ENLACE: https://www.php.net/manual/es/class.arrayaccess.php
// ENUNCIADO EJEMPLO: una lista de la compra que se usa con foreach, con [] y con count(), pero
//                   que solo admite cantidades positivas.
// NOTA: IteratorAggregate es más sencilla que Iterator: un solo método que devuelve un iterador
//       (aquí, un ArrayIterator del array interno). ArrayAccess "engancha" la sintaxis $obj[...].
// ---------------------------------------------------------------------------------------------
titulo('Colección con IteratorAggregate, ArrayAccess y Countable');
class ListaCompra implements IteratorAggregate, ArrayAccess, Countable
{
    private array $articulos = [];

    public function getIterator(): Iterator
    {
        return new ArrayIterator($this->articulos);
    }

    public function offsetExists(mixed $clave): bool          // isset($lista['pan'])
    {
        return isset($this->articulos[$clave]);
    }

    public function offsetGet(mixed $clave): mixed            // $lista['pan']
    {
        return $this->articulos[$clave] ?? 0;
    }

    public function offsetSet(mixed $clave, mixed $cantidad): void   // $lista['pan'] = 2
    {
        if ($clave === null || !is_int($cantidad) || $cantidad <= 0) {
            throw new InvalidArgumentException('Usa $lista["artículo"] = cantidad positiva');
        }
        $this->articulos[$clave] = $cantidad;
    }

    public function offsetUnset(mixed $clave): void           // unset($lista['pan'])
    {
        unset($this->articulos[$clave]);
    }

    public function count(): int
    {
        return count($this->articulos);
    }
}
$lista = new ListaCompra();
$lista['pan'] = 2;                         // offsetSet: pon un punto de ruptura dentro
$lista['leche'] = 6;
$lista['huevos'] = 12;
unset($lista['leche']);                    // offsetUnset
ver('pan', $lista['pan']);                 // 2
ver('¿hay leche?', isset($lista['leche'])); // false
ver('artículos distintos', count($lista)); // 2
foreach ($lista as $articulo => $cantidad) {
    echo "  $articulo: $cantidad", nl();   // pan: 2 / huevos: 12
}
verError('cantidad negativa', function () use ($lista) {
    $lista['arroz'] = -1;
});   // InvalidArgumentException

// ---------------------------------------------------------------------------------------------
// Iteradores de la SPL: CallbackFilterIterator, LimitIterator, AppendIterator
// ENLACE: https://www.php.net/manual/es/spl.iterators.php
// ENUNCIADO EJEMPLO: de todos los productos, los baratos; y de esos, solo los 2 primeros para la
//                   portada. Después, unir los productos de dos proveedores.
// NOTA: los iteradores se "encadenan": cada uno envuelve al anterior. No se crea ningún array
//       intermedio: cada elemento pasa por la cadena cuando foreach lo pide.
// ---------------------------------------------------------------------------------------------
titulo('Iteradores de la SPL');
$precios = new ArrayIterator(['Ratón' => 19.95, 'Monitor' => 179.0, 'Alfombrilla' => 7.5, 'Cable' => 8.99, 'Cascos' => 59.0]);
$baratos = new CallbackFilterIterator($precios, fn(float $precio) => $precio < 20);
$portada = new LimitIterator($baratos, 0, 2);             // desde la posición 0, 2 elementos
ver('baratos', iterator_to_array($baratos));              // ['Ratón' => 19.95, 'Alfombrilla' => 7.5, 'Cable' => 8.99]
ver('portada', iterator_to_array($portada));              // ['Ratón' => 19.95, 'Alfombrilla' => 7.5]
$todos = new AppendIterator();
$todos->append(new ArrayIterator(['Ratón', 'Teclado']));
$todos->append(new ArrayIterator(['Funda', 'Cargador']));
ver('AppendIterator', iterator_to_array($todos, false));  // ['Ratón', 'Teclado', 'Funda', 'Cargador'] (false: sin claves)

// ---------------------------------------------------------------------------------------------
// Estructuras de datos de la SPL: SplStack, SplQueue, SplObjectStorage
// ENLACE: https://www.php.net/manual/es/spl.datastructures.php
// ENUNCIADO EJEMPLO: una pila para "deshacer" cambios, una cola de pedidos por preparar y un
//                   conjunto de clientes conectados (sin repetidos).
// ---------------------------------------------------------------------------------------------
titulo('SplStack, SplQueue, SplObjectStorage');
$deshacer = new SplStack();                // pila: el último en entrar es el primero en salir
$deshacer->push('añadir Ratón');
$deshacer->push('cambiar cantidad');
$deshacer->push('borrar Cable');
ver('deshacer', $deshacer->pop());         // "borrar Cable"
ver('quedan en la pila', count($deshacer)); // 2

$cola = new SplQueue();                    // cola: el primero en entrar es el primero en salir
$cola->enqueue('pedido 1001');
$cola->enqueue('pedido 1002');
$cola->enqueue('pedido 1003');
ver('preparar', $cola->dequeue());         // "pedido 1001"

class Sesion
{
    public function __construct(public string $usuario)
    {
    }
}
$ana = new Sesion('ana');
$brais = new Sesion('brais');
$conectados = new SplObjectStorage();      // conjunto de OBJETOS (cada objeto, una vez)
$conectados->attach($ana, ['desde' => '10:00']);   // se puede asociar un dato a cada objeto
$conectados->attach($brais, ['desde' => '10:05']);
$conectados->attach($ana, ['desde' => '10:07']);   // ya estaba: no se duplica (se actualiza el dato)
ver('conectados', count($conectados));     // 2
ver('dato de ana', $conectados[$ana]);     // ['desde' => '10:07']
ver('¿está brais?', $conectados->contains($brais));   // true

// ---------------------------------------------------------------------------------------------
// SplFileObject — Un fichero como objeto que se recorre con foreach
// ENLACE: https://www.php.net/manual/es/class.splfileobject.php
// ENUNCIADO EJEMPLO: leer un CSV de productos línea a línea.
// ---------------------------------------------------------------------------------------------
titulo('SplFileObject');
$csv = new SplFileObject('php://memory', 'w+');
$csv->fwrite("nombre;precio\nRatón;19.95\nTeclado;49.50\n");
$csv->rewind();
$csv->setFlags(SplFileObject::READ_CSV | SplFileObject::SKIP_EMPTY | SplFileObject::READ_AHEAD);
$csv->setCsvControl(';');
$filas = [];
foreach ($csv as $numero => $fila) {
    $filas[$numero] = $fila;
}
ver('filas', $filas);    // [0 => ['nombre', 'precio'], 1 => ['Ratón', '19.95'], 2 => ['Teclado', '49.50']]

// ---------------------------------------------------------------------------------------------
// Generadores: yield
// ENLACE: https://www.php.net/manual/es/language.generators.overview.php
// ENUNCIADO EJEMPLO: procesar un listado enorme de ventas sin cargarlo entero en memoria.
// NOTA: una función con yield es un GENERADOR: al llamarla no se ejecuta, devuelve un objeto
//       Generator. Cada vez que foreach pide un valor, la función avanza hasta el siguiente yield,
//       lo entrega y se queda "en pausa" ahí. Es una forma muy sencilla de crear un iterador.
// ---------------------------------------------------------------------------------------------
titulo('Generadores');
function ventas(int $cuantas): Generator
{
    echo '  (el generador empieza)', nl();
    for ($i = 1; $i <= $cuantas; $i++) {
        yield "V$i" => round(9.99 * $i, 2);          // clave => valor (V1 => 9.99, V2 => 19.98...)
    }
    echo '  (el generador termina)', nl();
}
$generador = ventas(5);                    // ¡todavía no se ha ejecutado nada!
ver('tipo', get_class($generador));        // "Generator"
$total = 0;
foreach ($generador as $codigo => $importe) {   // ahora sí: pon un punto de ruptura dentro de ventas()
    echo "  $codigo: $importe", nl();
    $total += $importe;
}
ver('total', round($total, 2));            // 149.85

// yield from: delegar en otro generador (o en un array)
function todasLasVentas(): Generator
{
    yield from ['tienda-1' => 120.0, 'tienda-2' => 80.5];
    yield 'online' => 300.25;
}
ver('yield from', iterator_to_array(todasLasVentas()));     // ['tienda-1' => 120.0, 'tienda-2' => 80.5, 'online' => 300.25]

// send(): enviar valores AL generador (ejemplo del material de referencia)
function contador(): Generator
{
    for ($i = 0; $i < 10; $i++) {
        $orden = yield $i;                 // send() hace que yield "devuelva" ese valor
        if ($orden === 'parar') {
            return;
        }
    }
}
$vistos = [];
$gen = contador();
foreach ($gen as $valor) {
    $vistos[] = $valor;
    if ($valor === 3) {
        $gen->send('parar');
    }
}
ver('con send', $vistos);                  // [0, 1, 2, 3]

// Memoria: array completo frente a generador
$antes = memory_get_usage();
$array = range(1, 200000);
$conArray = memory_get_usage() - $antes;
unset($array);
function rango(int $desde, int $hasta): Generator
{
    for ($i = $desde; $i <= $hasta; $i++) {
        yield $i;
    }
}
$antes = memory_get_usage();
$generadorRango = rango(1, 200000);
$conGenerador = memory_get_usage() - $antes;
ver('memoria con range()', $conArray > 1_000_000 ? 'más de 1 MB' : 'menos de 1 MB');          // "más de 1 MB"
ver('memoria con el generador', $conGenerador < 10_000 ? 'menos de 10 KB' : 'más de 10 KB');  // "menos de 10 KB"
ver('pero suman lo mismo', array_sum(range(1, 200000)) === array_sum(iterator_to_array($generadorRango)));   // true

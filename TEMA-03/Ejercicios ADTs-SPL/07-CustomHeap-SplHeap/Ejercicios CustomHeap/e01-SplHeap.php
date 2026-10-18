<?php
/*
 * e01-SplHeap.php
 *  MONTÍCULO PERSONALIZADO con SplHeap (1): CREAR TU PROPIO CRITERIO
 *  Contenidos: SplHeap es una clase ABSTRACTA: se hereda e implementa compare($valor1, $valor2);
 *              montículos de objetos ordenados por una o varias propiedades; heredar de
 *              SplMinHeap / SplMaxHeap y redefinir compare; un montículo con un comparador que se
 *              pasa al construirlo (closure); montículos "corruptos" (isCorrupted,
 *              recoverFromCorruption)
 *
 *  REGLA: compare($valor1, $valor2) debe devolver un número POSITIVO si $valor1 debe estar MÁS
 *  ARRIBA (salir antes) que $valor2, 0 si son equivalentes y negativo en otro caso. Con <=>:
 *      return $valor1 <=> $valor2;   -> sale primero el MAYOR (como SplMaxHeap)
 *      return $valor2 <=> $valor1;   -> sale primero el MENOR (como SplMinHeap)
 *
 *  Cómo trabajar este fichero:
 *   1. Pon un punto de ruptura DENTRO de compare() y ejecuta en modo depuración: verás que PHP lo
 *      llama al insertar y al extraer, comparando el nuevo elemento con su padre o sus hijos.
 */
require_once __DIR__ . '/../../utilidades.php';

// ---------------------------------------------------------------------------------------------
// Heredar de SplHeap: un montículo de objetos
// ENLACE: https://www.php.net/manual/es/class.splheap.php
// ENLACE: https://www.php.net/manual/es/splheap.compare.php
// ENUNCIADO EJEMPLO: los pedidos de la tienda se preparan por fecha de entrega (la más próxima
//                   primero) y, a igual fecha, el de mayor importe primero.
// NOTA: SplMinHeap compararía los objetos Pedido propiedad a propiedad, en el orden en que están
//       declaradas, lo que casi nunca es lo que queremos. Con compare() decidimos el criterio.
// ---------------------------------------------------------------------------------------------
titulo('Montículo de pedidos');
final class Pedido
{
    public function __construct(
        public readonly int $numero,
        public readonly string $entrega,      // "Y-m-d"
        public readonly float $importe,
    ) {
    }
}
final class ColaPedidos extends SplHeap
{
    protected function compare(mixed $a, mixed $b): int
    {
        // 1.º la fecha MÁS PRÓXIMA (por eso $b antes que $a); 2.º el importe MAYOR
        return [$b->entrega, $a->importe] <=> [$a->entrega, $b->importe];
    }
}
$pedidos = new ColaPedidos();
$pedidos->insert(new Pedido(1001, '2026-10-20', 45.90));
$pedidos->insert(new Pedido(1002, '2026-10-18', 12.50));
$pedidos->insert(new Pedido(1003, '2026-10-20', 210.00));
$pedidos->insert(new Pedido(1004, '2026-10-18', 89.99));
ver('siguiente', $pedidos->top()->numero);       // 1004 (18/10, el de más importe)
$orden = [];
foreach (clone $pedidos as $p) {
    $orden[] = "$p->numero ($p->entrega, $p->importe €)";
}
ver('orden de preparación', $orden);
// ['1004 (2026-10-18, 89.99 €)', '1002 (2026-10-18, 12.5 €)', '1003 (2026-10-20, 210 €)', '1001 (2026-10-20, 45.9 €)']

// ---------------------------------------------------------------------------------------------
// Heredar de SplMinHeap o SplMaxHeap
// ENLACE: https://www.php.net/manual/es/splminheap.compare.php
// ENUNCIADO EJEMPLO: ordenar nombres sin distinguir mayúsculas ni tildes (como en un listado de
//                   clase), cosa que SplMinHeap no hace (pone "Óscar" después de "Zoe").
// NOTA: al heredar de SplMinHeap, compare() mantiene su sentido (sale primero el "menor"), pero
//       el criterio de comparación es el nuestro. Collator (extensión intl) compara según el idioma.
// ---------------------------------------------------------------------------------------------
titulo('Nombres en orden alfabético español');
$sinCriterio = new SplMinHeap();
foreach (['Óscar', 'ana', 'Zoe', 'Ángela', 'brais'] as $n) {
    $sinCriterio->insert($n);
}
ver('SplMinHeap tal cual', iterator_to_array($sinCriterio, false));   // ['Zoe', 'ana', 'brais', 'Ángela', 'Óscar']

final class NombresEnEspanol extends SplMinHeap
{
    private Collator $collator;

    public function __construct()
    {
        $this->collator = new Collator('es_ES');
    }

    protected function compare(mixed $a, mixed $b): int
    {
        return $this->collator->compare($b, $a);   // en SplMinHeap: positivo si $a es MENOR
    }
}
$conCriterio = new NombresEnEspanol();
foreach (['Óscar', 'ana', 'Zoe', 'Ángela', 'brais'] as $n) {
    $conCriterio->insert($n);
}
ver('con Collator', iterator_to_array($conCriterio, false));          // ['ana', 'Ángela', 'brais', 'Óscar', 'Zoe']

// ---------------------------------------------------------------------------------------------
// Un montículo con el comparador como parámetro
// ENLACE: https://www.php.net/manual/es/class.closure.php
// ENUNCIADO EJEMPLO: una sola clase que sirva para ordenar productos por precio, por valoración o
//                   por lo que se quiera, pasando la función de comparación al construirla.
// ---------------------------------------------------------------------------------------------
titulo('Comparador como parámetro');
final class HeapConComparador extends SplHeap
{
    public function __construct(private Closure $comparador)
    {
    }

    protected function compare(mixed $a, mixed $b): int
    {
        return ($this->comparador)($a, $b);
    }
}
$productos = [
    ['nombre' => 'Ratón', 'precio' => 19.95, 'estrellas' => 4.6],
    ['nombre' => 'Teclado', 'precio' => 49.50, 'estrellas' => 4.8],
    ['nombre' => 'Webcam', 'precio' => 39.90, 'estrellas' => 3.9],
];
$porPrecio = new HeapConComparador(fn($a, $b) => $b['precio'] <=> $a['precio']);         // el más barato
$porEstrellas = new HeapConComparador(fn($a, $b) => $a['estrellas'] <=> $b['estrellas']); // el mejor valorado
foreach ($productos as $p) {
    $porPrecio->insert($p);
    $porEstrellas->insert($p);
}
ver('más barato', $porPrecio->top()['nombre']);           // "Ratón"
ver('mejor valorado', $porEstrellas->top()['nombre']);    // "Teclado"

// ---------------------------------------------------------------------------------------------
// Montículo corrupto: si compare() lanza una excepción
// ENLACE: https://www.php.net/manual/es/splheap.iscorrupted.php
// ENLACE: https://www.php.net/manual/es/splheap.recoverfromcorruption.php
// ENUNCIADO EJEMPLO: un pedido mal construido (sin fecha) hace fallar la comparación.
// NOTA: si compare() lanza una excepción a mitad de una inserción, el montículo puede quedar
//       desordenado y PHP lo marca como CORRUPTO: cualquier operación posterior lanza
//       RuntimeException. recoverFromCorruption() quita la marca, pero el orden ya no está
//       garantizado. Lo correcto es validar los datos ANTES de insertarlos.
// ---------------------------------------------------------------------------------------------
titulo('Montículo corrupto');
final class PorFecha extends SplHeap
{
    protected function compare(mixed $a, mixed $b): int
    {
        if (!isset($a['fecha'], $b['fecha'])) {
            throw new InvalidArgumentException('Pedido sin fecha');
        }
        return strcmp($b['fecha'], $a['fecha']);
    }
}
$heap = new PorFecha();
$heap->insert(['n' => 1, 'fecha' => '2026-10-20']);
$heap->insert(['n' => 2, 'fecha' => '2026-10-18']);
verError('insertar sin fecha', fn() => $heap->insert(['n' => 3]));   // InvalidArgumentException: Pedido sin fecha
ver('isCorrupted', $heap->isCorrupted());                            // true
verError('insertar otro', fn() => $heap->insert(['n' => 4, 'fecha' => '2026-10-19']));
// RuntimeException: Heap is corrupted, heap properties are no longer ensured.
$heap->recoverFromCorruption();
ver('tras recoverFromCorruption', [$heap->isCorrupted(), count($heap)]);   // [false, 3] (¡el pedido 3 se quedó dentro!)

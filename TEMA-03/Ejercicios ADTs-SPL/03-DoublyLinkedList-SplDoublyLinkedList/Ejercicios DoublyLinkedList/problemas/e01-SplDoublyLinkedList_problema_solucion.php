<?php
/*
 * e01-SplDoublyLinkedList_problema_solucion.php
 *  (FICHERO CON LAS SOLUCIONES)
 *  Problemas de: SplDoublyLinkedList (push, pop, shift, unshift, add, offsetGet / offsetSet /
 *                offsetUnset, modos de recorrido, rewind / next / prev)
 *  (repasa los ejemplos de ../e01-SplDoublyLinkedList.php)
 *
 *  Resuelve cada problema donde se indica y ejecútalo en modo depuración para comprobarlo.
 */
require_once __DIR__ . '/../../../utilidades.php';

// =============================================================================================
// PROBLEMA 1. Rotar una lista
//  Escribe rotar(SplDoublyLinkedList $lista, int $n): void que mueva los $n primeros elementos
//  al final (con $n negativo, los $n últimos al principio). Usa solo shift/push y pop/unshift.
// =============================================================================================
titulo('Problema 1');
$dias = new SplDoublyLinkedList();
foreach (['luns', 'martes', 'mércores', 'xoves', 'venres'] as $d) {
    $dias->push($d);
}
// completa aquí tu solución
// ---- SOLUCIÓN ----
function rotar(SplDoublyLinkedList $lista, int $n): void
{
    for ($i = 0; $i < abs($n); $i++) {
        $n > 0 ? $lista->push($lista->shift()) : $lista->unshift($lista->pop());
    }
}
rotar($dias, 2);
ver('rotar 2', $dias);       // ['mércores', 'xoves', 'venres', 'luns', 'martes']
rotar($dias, -2);
ver('rotar -2', $dias);      // ['luns', 'martes', 'mércores', 'xoves', 'venres']

// =============================================================================================
// PROBLEMA 2. Insertar y borrar por posición
//  En la lista de la compra $compra: inserta 'aceite' en la posición 1, cambia 'pan' por
//  'pan integral', y borra 'leche' (búscala; no sabes en qué posición está). Muestra la lista.
// =============================================================================================
titulo('Problema 2');
$compra = new SplDoublyLinkedList();
foreach (['pan', 'leche', 'huevos', 'tomates'] as $p) {
    $compra->push($p);
}
// completa aquí tu solución
// ---- SOLUCIÓN ----
$compra->add(1, 'aceite');
foreach ($compra as $i => $producto) {
    if ($producto === 'pan') {
        $compra[$i] = 'pan integral';
    }
}
foreach ($compra as $i => $producto) {
    if ($producto === 'leche') {
        $compra->offsetUnset($i);
        break;                       // tras borrar, las posiciones cambian: salimos del bucle
    }
}
ver('compra', $compra);              // ['pan integral', 'aceite', 'huevos', 'tomates']

// =============================================================================================
// PROBLEMA 3. Del revés sin copiar
//  Muestra los elementos de $ultimos (las últimas visitas a la web) del más reciente al más
//  antiguo SIN crear otra lista ni un array al revés: cambia el modo de recorrido.
// =============================================================================================
titulo('Problema 3');
$ultimos = new SplDoublyLinkedList();
foreach (['/inicio', '/productos', '/carrito', '/pago'] as $url) {
    $ultimos->push($url);
}
// completa aquí tu solución
// ---- SOLUCIÓN ----
$ultimos->setIteratorMode(SplDoublyLinkedList::IT_MODE_LIFO);
$recientes = [];
foreach ($ultimos as $url) {
    $recientes[] = $url;
}
ver('de reciente a antiguo', $recientes);   // ['/pago', '/carrito', '/productos', '/inicio']
$ultimos->setIteratorMode(SplDoublyLinkedList::IT_MODE_FIFO);   // la dejamos como estaba

// =============================================================================================
// PROBLEMA 4. Un carrusel de imágenes
//  Escribe la clase Carrusel (con una SplDoublyLinkedList de imágenes) con los métodos actual(),
//  siguiente() y anterior(), que den la vuelta al llegar a un extremo (después de la última, la
//  primera; antes de la primera, la última). Pruébalo con la secuencia de abajo.
// =============================================================================================
titulo('Problema 4');
// completa aquí tu solución
// ---- SOLUCIÓN ----
final class Carrusel
{
    private SplDoublyLinkedList $imagenes;

    public function __construct(array $imagenes)
    {
        $this->imagenes = new SplDoublyLinkedList();
        foreach ($imagenes as $img) {
            $this->imagenes->push($img);
        }
        $this->imagenes->rewind();
    }

    public function actual(): string
    {
        return $this->imagenes->current();
    }

    public function siguiente(): string
    {
        $this->imagenes->next();
        if (!$this->imagenes->valid()) {
            $this->imagenes->rewind();                     // de la última a la primera
        }
        return $this->actual();
    }

    public function anterior(): string
    {
        $this->imagenes->prev();
        if (!$this->imagenes->valid()) {                   // antes de la primera: a la última
            $this->imagenes->rewind();
            for ($i = 1; $i < count($this->imagenes); $i++) {
                $this->imagenes->next();
            }
        }
        return $this->actual();
    }
}
$carrusel = new Carrusel(['playa.jpg', 'faro.jpg', 'monte.jpg']);
ver('secuencia', [$carrusel->actual(), $carrusel->siguiente(), $carrusel->siguiente(), $carrusel->siguiente(), $carrusel->anterior(), $carrusel->anterior()]);
// ['playa.jpg', 'faro.jpg', 'monte.jpg', 'playa.jpg', 'monte.jpg', 'faro.jpg']

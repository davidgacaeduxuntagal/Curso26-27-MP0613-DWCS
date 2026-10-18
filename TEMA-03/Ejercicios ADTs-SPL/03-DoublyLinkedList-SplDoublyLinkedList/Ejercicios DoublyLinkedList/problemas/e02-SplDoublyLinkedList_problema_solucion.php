<?php
/*
 * e02-SplDoublyLinkedList_problema_solucion.php
 *  (FICHERO CON LAS SOLUCIONES)
 *  Problemas de: aplicaciones de las listas doblemente enlazadas (deque, historial con atrás y
 *                adelante, listas ordenadas, ventana deslizante)
 *  (repasa los ejemplos de ../e02-SplDoublyLinkedList.php)
 *
 *  Resuelve cada problema donde se indica y ejecútalo en modo depuración para comprobarlo.
 */
require_once __DIR__ . '/../../../utilidades.php';

// =============================================================================================
// PROBLEMA 1. Atrás y adelante
//  Escribe la clase Historial con visitar(string $url), atras(): ?string, adelante(): ?string y
//  actual(). Usa UNA SplDoublyLinkedList y un índice con la posición actual. Al visitar una
//  página nueva después de ir atrás, se borran las páginas "de delante" (como en un navegador).
// =============================================================================================
titulo('Problema 1');
// completa aquí tu solución
// ---- SOLUCIÓN ----
final class Historial
{
    private SplDoublyLinkedList $paginas;
    private int $posicion = -1;

    public function __construct()
    {
        $this->paginas = new SplDoublyLinkedList();
    }

    public function visitar(string $url): void
    {
        while (count($this->paginas) > $this->posicion + 1) {
            $this->paginas->pop();                      // fuera las páginas "de delante"
        }
        $this->paginas->push($url);
        $this->posicion++;
    }

    public function atras(): ?string
    {
        if ($this->posicion <= 0) {
            return null;
        }
        return $this->paginas[--$this->posicion];
    }

    public function adelante(): ?string
    {
        if ($this->posicion >= count($this->paginas) - 1) {
            return null;
        }
        return $this->paginas[++$this->posicion];
    }

    public function actual(): ?string
    {
        return $this->posicion >= 0 ? $this->paginas[$this->posicion] : null;
    }
}
$h = new Historial();
$h->visitar('/');
$h->visitar('/blog');
$h->visitar('/blog/post-1');
ver('atrás', $h->atras());           // "/blog"
ver('atrás', $h->atras());           // "/"
ver('adelante', $h->adelante());     // "/blog"
$h->visitar('/contacto');            // borra '/blog/post-1'
ver('adelante', $h->adelante());     // NULL
ver('atrás', $h->atras());           // "/blog"

// =============================================================================================
// PROBLEMA 2. Máximo de una ventana deslizante
//  Dadas las temperaturas de cada hora, calcula la máxima de cada ventana de 3 horas seguidas.
//  Hazlo con una deque (SplDoublyLinkedList) de POSICIONES en la que las temperaturas estén en
//  orden decreciente: al llegar una nueva, se quitan por el final las menores; por el principio,
//  las que ya han salido de la ventana. El máximo está siempre al principio.
// =============================================================================================
titulo('Problema 2');
$temperaturas = [12, 14, 13, 18, 17, 15, 11, 16];
// completa aquí tu solución
// ---- SOLUCIÓN ----
function maximosVentana(array $valores, int $k): array
{
    $deque = new SplDoublyLinkedList();     // posiciones; sus valores, en orden decreciente
    $maximos = [];
    foreach ($valores as $i => $v) {
        while (!$deque->isEmpty() && $valores[$deque->top()] <= $v) {
            $deque->pop();                   // los menores que el nuevo ya nunca serán el máximo
        }
        $deque->push($i);
        if ($deque->bottom() <= $i - $k) {
            $deque->shift();                 // el del principio ha salido de la ventana
        }
        if ($i >= $k - 1) {
            $maximos[] = $valores[$deque->bottom()];
        }
    }
    return $maximos;
}
ver('máximas de cada 3 horas', maximosVentana($temperaturas, 3));   // [14, 18, 18, 18, 17, 16]

// =============================================================================================
// PROBLEMA 3. Fusionar dos listas ordenadas
//  $a y $b son listas de precios ordenadas de menor a mayor. Escribe fusionar() que devuelva una
//  nueva SplDoublyLinkedList ordenada con los elementos de las dos, sacando siempre el menor de
//  los dos primeros (bottom + shift). Las listas originales pueden quedar vacías.
// =============================================================================================
titulo('Problema 3');
$a = new SplDoublyLinkedList();
$b = new SplDoublyLinkedList();
foreach ([3.5, 9.9, 12.0] as $p) {
    $a->push($p);
}
foreach ([1.2, 4.0, 15.5, 20.0] as $p) {
    $b->push($p);
}
// completa aquí tu solución
// ---- SOLUCIÓN ----
function fusionar(SplDoublyLinkedList $a, SplDoublyLinkedList $b): SplDoublyLinkedList
{
    $resultado = new SplDoublyLinkedList();
    while (!$a->isEmpty() && !$b->isEmpty()) {
        $resultado->push($a->bottom() <= $b->bottom() ? $a->shift() : $b->shift());
    }
    foreach ([$a, $b] as $resto) {
        while (!$resto->isEmpty()) {
            $resultado->push($resto->shift());
        }
    }
    return $resultado;
}
ver('fusionada', fusionar($a, $b));   // [1.2, 3.5, 4.0, 9.9, 12.0, 15.5, 20.0]

// =============================================================================================
// PROBLEMA 4. ¿Cómo harías...?
//  ¿Qué estructura usarías en cada caso: SplStack, SplQueue o SplDoublyLinkedList? Contesta en
//  un comentario y justifícalo.
//   a) Las acciones de "deshacer" de un editor.
//   b) Los pedidos de una tienda, que se atienden en orden de llegada.
//   c) Un reproductor con "siguiente", "anterior" y la posibilidad de añadir una canción "a
//      continuación de la actual".
// =============================================================================================
titulo('Problema 4');
// completa aquí tu solución
// ---- SOLUCIÓN ----
// a) SplStack: lo último que se hizo es lo primero que se deshace (LIFO).
// b) SplQueue: el primero que llega es el primero que se atiende (FIFO).
// c) SplDoublyLinkedList: se recorre en los dos sentidos (next / prev) y permite insertar en
//    cualquier posición con add($posicionActual + 1, $cancion). Ni la pila ni la cola lo permiten.
ver('respuesta', 'a) SplStack, b) SplQueue, c) SplDoublyLinkedList');

<?php
/*
 * e02-SplMaxHeap_problema_solucion.php
 *  (FICHERO CON LAS SOLUCIONES)
 *  Problemas de: aplicaciones de los montículos máximos (K menores, mediana, planificación,
 *                combinar SplMaxHeap y SplMinHeap)
 *  (repasa los ejemplos de ../e02-SplMaxHeap.php)
 *
 *  Resuelve cada problema donde se indica y ejecútalo en modo depuración para comprobarlo.
 */
require_once __DIR__ . '/../../../utilidades.php';

// =============================================================================================
// PROBLEMA 1. Los hoteles más cercanos
//  De $hoteles (nombre => km al centro), obtén los 2 más cercanos recorriendo la lista una sola
//  vez con un SplMaxHeap que nunca tenga más de 2 elementos.
// =============================================================================================
titulo('Problema 1');
$hoteles = ['Atlántico' => 1.2, 'Riazor' => 2.8, 'Plaza' => 0.4, 'Faro' => 5.5, 'Puerto' => 0.9];
// completa aquí tu solución
// ---- SOLUCIÓN ----
$cercanos = new SplMaxHeap();
foreach ($hoteles as $hotel => $km) {
    $cercanos->insert([$km, $hotel]);
    if (count($cercanos) > 2) {
        $cercanos->extract();                // el más lejano de los 3
    }
}
ver('los 2 más cercanos', array_reverse(iterator_to_array($cercanos, false)));   // [[0.4, 'Plaza'], [0.9, 'Puerto']]

// =============================================================================================
// PROBLEMA 2. Mediana de los tiempos de respuesta
//  Usando la idea de la clase Mediana del ejemplo (un SplMaxHeap y un SplMinHeap), escribe
//  medianaDe(array $valores): float y calcula la mediana de los tiempos de respuesta (ms) de un
//  servidor. Compruébala ordenando el array.
// =============================================================================================
titulo('Problema 2');
$tiempos = [120, 95, 3000, 110, 101, 99, 130, 105];
// completa aquí tu solución
// ---- SOLUCIÓN ----
function medianaDe(array $valores): float
{
    $inferior = new SplMaxHeap();
    $superior = new SplMinHeap();
    foreach ($valores as $x) {
        $inferior->insert($x);
        $superior->insert($inferior->extract());          // el mayor de los pequeños pasa arriba
        if (count($superior) > count($inferior)) {
            $inferior->insert($superior->extract());      // equilibrar
        }
    }
    return count($inferior) > count($superior)
        ? $inferior->top()
        : ($inferior->top() + $superior->top()) / 2;
}
ver('mediana', medianaDe($tiempos));                      // 107.5
$ordenados = $tiempos;
sort($ordenados);
ver('comprobación', ($ordenados[3] + $ordenados[4]) / 2); // 107.5

// =============================================================================================
// PROBLEMA 3. Atender primero el pedido más grande
//  Un almacén con 3 empleados prepara los pedidos de $pedidos (número => artículos). Para acabar
//  cuanto antes, cada empleado libre coge el pedido con MÁS artículos pendiente. Simúlalo con un
//  SplMaxHeap (pedidos) y un SplMinHeap (empleados: [minutos ocupado, nombre]); cada artículo
//  tarda 1 minuto. ¿Cuándo termina el último?
// =============================================================================================
titulo('Problema 3');
$pedidos = [101 => 8, 102 => 3, 103 => 12, 104 => 5, 105 => 7, 106 => 2];
// completa aquí tu solución
// ---- SOLUCIÓN ----
$porTamano = new SplMaxHeap();
foreach ($pedidos as $numero => $articulos) {
    $porTamano->insert([$articulos, $numero]);
}
$empleados = new SplMinHeap();
foreach (['Ana', 'Brais', 'Carmen'] as $nombre) {
    $empleados->insert([0, $nombre]);
}
while (!$porTamano->isEmpty()) {
    [$articulos] = $porTamano->extract();
    [$ocupado, $nombre] = $empleados->extract();
    $empleados->insert([$ocupado + $articulos, $nombre]);
}
$fin = max(array_column(iterator_to_array($empleados, false), 0));
ver('el último termina en el minuto', $fin);   // 13 (12 artículos de 37 entre 3 empleados)

// =============================================================================================
// PROBLEMA 4. ¿Cómo harías...?
//  ¿Cómo implementarías la clase Mediana del ejemplo usando SOLO SplMinHeap (sin SplMaxHeap)?
//  Pista: un montículo mínimo de valores con el signo cambiado se comporta como uno máximo.
// =============================================================================================
titulo('Problema 4');
// completa aquí tu solución
// ---- SOLUCIÓN ----
final class MedianaSoloMin
{
    private SplMinHeap $inferior;      // guarda -x: su top() es el -(mayor de los pequeños)
    private SplMinHeap $superior;

    public function __construct()
    {
        $this->inferior = new SplMinHeap();
        $this->superior = new SplMinHeap();
    }

    public function anadir(float $x): void
    {
        $this->inferior->insert(-$x);
        $this->superior->insert(-$this->inferior->extract());
        if (count($this->superior) > count($this->inferior)) {
            $this->inferior->insert(-$this->superior->extract());
        }
    }

    public function valor(): float
    {
        return count($this->inferior) > count($this->superior)
            ? -$this->inferior->top()
            : (-$this->inferior->top() + $this->superior->top()) / 2;
    }
}
$m = new MedianaSoloMin();
foreach ([650, 720, 590, 3200, 700, 680] as $x) {
    $m->anadir($x);
}
ver('mediana', $m->valor());   // 690 (la misma que con SplMaxHeap)

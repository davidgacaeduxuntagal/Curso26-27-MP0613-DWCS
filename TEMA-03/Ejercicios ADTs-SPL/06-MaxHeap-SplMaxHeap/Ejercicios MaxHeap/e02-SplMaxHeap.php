<?php
/*
 * e02-SplMaxHeap.php
 *  MONTÍCULO MÁXIMO con SplMaxHeap (2): APLICACIONES REALES
 *  Contenidos: los K menores con un max-heap de tamaño K (los vuelos más baratos), la mediana en
 *              tiempo real con dos montículos (uno máximo y otro mínimo), repartir trabajos
 *              empezando por el más largo (heurística LPT)
 *
 *  Cómo trabajar este fichero:
 *   1. Pon un punto de ruptura DENTRO de los bucles y ejecuta en modo depuración.
 *   2. En la mediana, observa cómo los dos montículos se mantienen siempre equilibrados.
 */
require_once __DIR__ . '/../../utilidades.php';

// ---------------------------------------------------------------------------------------------
// Los K MENORES con un montículo MÁXIMO de tamaño K
// ENLACE: https://www.php.net/manual/es/class.splmaxheap.php
// ENUNCIADO EJEMPLO: un comparador de vuelos recibe miles de precios de distintas aerolíneas y
//                   debe mostrar los 3 más baratos.
// NOTA: es el problema "top K" al revés: el max-heap guarda los K más baratos vistos y su raíz es
//       el MÁS CARO de ellos; si llega uno más barato que la raíz, la sustituye.
// ---------------------------------------------------------------------------------------------
titulo('Los K más baratos');
function masBaratos(iterable $vuelos, int $k): array
{
    $mejores = new SplMaxHeap();
    foreach ($vuelos as $vuelo => $precio) {
        if (count($mejores) < $k) {
            $mejores->insert([$precio, $vuelo]);
        } elseif ($precio < $mejores->top()[0]) {      // más barato que el más caro de los K
            $mejores->extract();
            $mejores->insert([$precio, $vuelo]);
        }
    }
    return array_reverse(iterator_to_array($mejores, false));   // del más barato al más caro
}
$vuelos = ['IB3021' => 89.0, 'VY1110' => 45.5, 'FR8822' => 29.99, 'UX7154' => 120.0, 'V7231' => 39.9, 'IB3025' => 59.0];
ver('3 más baratos', masBaratos($vuelos, 3));
// [[29.99, 'FR8822'], [39.9, 'V7231'], [45.5, 'VY1110']]

// ---------------------------------------------------------------------------------------------
// La mediana en tiempo real con dos montículos
// ENLACE: https://es.wikipedia.org/wiki/Mediana_(estad%C3%ADstica)
// ENUNCIADO EJEMPLO: una web de alquileres muestra el precio MEDIANO de los pisos a medida que
//                   se publican anuncios (la mediana no se deja engañar por un ático carísimo).
// NOTA: la mitad inferior de los precios va en un montículo MÁXIMO (su raíz es el mayor de los
//       pequeños) y la mitad superior en uno MÍNIMO (su raíz es el menor de los grandes). Se
//       mantienen equilibrados (como mucho uno más en el inferior); la mediana está en las raíces.
// ---------------------------------------------------------------------------------------------
titulo('Mediana en tiempo real');
final class Mediana
{
    private SplMaxHeap $inferior;
    private SplMinHeap $superior;

    public function __construct()
    {
        $this->inferior = new SplMaxHeap();
        $this->superior = new SplMinHeap();
    }

    public function anadir(float $x): void
    {
        if ($this->inferior->isEmpty() || $x <= $this->inferior->top()) {
            $this->inferior->insert($x);
        } else {
            $this->superior->insert($x);
        }
        // reequilibrar: el inferior puede tener, como mucho, uno más que el superior
        if (count($this->inferior) > count($this->superior) + 1) {
            $this->superior->insert($this->inferior->extract());
        } elseif (count($this->superior) > count($this->inferior)) {
            $this->inferior->insert($this->superior->extract());
        }
    }

    public function valor(): float
    {
        if (count($this->inferior) > count($this->superior)) {
            return $this->inferior->top();                          // número impar de datos
        }
        return ($this->inferior->top() + $this->superior->top()) / 2;   // número par
    }
}
$mediana = new Mediana();
$evolucion = [];
foreach ([650, 720, 590, 3200, 700, 680] as $alquiler) {
    $mediana->anadir($alquiler);                             // pon aquí un punto de ruptura
    $evolucion[] = "$alquiler -> mediana " . $mediana->valor();
}
ver('evolución', $evolucion);
// 650 -> 650, 720 -> 685, 590 -> 650, 3200 -> 685, 700 -> 700, 680 -> 690
ver('media (para comparar)', round(array_sum([650, 720, 590, 3200, 700, 680]) / 6, 2));   // 1090: el ático la dispara

// ---------------------------------------------------------------------------------------------
// Repartir trabajos: primero el más largo (LPT)
// ENLACE: https://es.wikipedia.org/wiki/Planificaci%C3%B3n_de_procesos
// ENUNCIADO EJEMPLO: hay que renderizar 6 vídeos en 2 ordenadores. Para terminar cuanto antes,
//                   se asignan del más largo al más corto, cada uno al ordenador menos cargado.
// NOTA: se usan los DOS tipos de montículo: un SplMaxHeap con los trabajos (sale el más largo) y
//       un SplMinHeap con los ordenadores (sale el menos cargado).
// ---------------------------------------------------------------------------------------------
titulo('El más largo primero');
$videos = new SplMaxHeap();
foreach (['intro' => 3, 'entrevista' => 12, 'tráiler' => 5, 'making-of' => 9, 'créditos' => 2, 'reportaje' => 7] as $nombre => $min) {
    $videos->insert([$min, $nombre]);
}
$ordenadores = new SplMinHeap();
$ordenadores->insert([0, 'PC-1', []]);
$ordenadores->insert([0, 'PC-2', []]);
while (!$videos->isEmpty()) {
    [$min, $nombre] = $videos->extract();                    // el vídeo más largo
    [$carga, $pc, $lista] = $ordenadores->extract();         // el ordenador menos cargado
    $lista[] = "$nombre ($min)";
    $ordenadores->insert([$carga + $min, $pc, $lista]);
}
foreach ($ordenadores as [$carga, $pc, $lista]) {
    ver("$pc ($carga min)", $lista);
}
// PC-1 (19 min): entrevista, tráiler, créditos / PC-2 (19 min): making-of, reportaje, intro

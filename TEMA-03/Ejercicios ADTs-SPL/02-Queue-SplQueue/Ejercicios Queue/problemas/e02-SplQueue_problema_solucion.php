<?php
/*
 * e02-SplQueue_problema_solucion.php
 *  (FICHERO CON LAS SOLUCIONES)
 *  Problemas de: aplicaciones de las colas (trabajos pendientes, simulaciones, búsqueda en
 *                anchura, buffers)
 *  (repasa los ejemplos de ../e02-SplQueue.php)
 *
 *  Resuelve cada problema donde se indica y ejecútalo en modo depuración para comprobarlo.
 */
require_once __DIR__ . '/../../../utilidades.php';

// =============================================================================================
// PROBLEMA 1. Tiempo de espera en la ventanilla
//  Cada cliente de $clientes llega en el minuto 'llega' y necesita 'dura' minutos. Solo hay una
//  ventanilla y se atiende por orden de llegada. Calcula con una SplQueue cuánto espera cada
//  cliente (minutos desde que llega hasta que empiezan a atenderle) y la espera media.
// =============================================================================================
titulo('Problema 1');
$clientes = [
    ['nombre' => 'Ana', 'llega' => 0, 'dura' => 5],
    ['nombre' => 'Brais', 'llega' => 2, 'dura' => 3],
    ['nombre' => 'Carmen', 'llega' => 4, 'dura' => 4],
    ['nombre' => 'Diego', 'llega' => 15, 'dura' => 2],
];
// completa aquí tu solución
// ---- SOLUCIÓN ----
$cola = new SplQueue();
foreach ($clientes as $c) {
    $cola->enqueue($c);
}
$libreEn = 0;                         // minuto en que la ventanilla queda libre
$esperas = [];
while (!$cola->isEmpty()) {
    $c = $cola->dequeue();
    $empieza = max($libreEn, $c['llega']);
    $esperas[$c['nombre']] = $empieza - $c['llega'];
    $libreEn = $empieza + $c['dura'];
}
ver('esperas', $esperas);             // ['Ana' => 0, 'Brais' => 3, 'Carmen' => 4, 'Diego' => 0]
ver('espera media', array_sum($esperas) / count($esperas));   // 1.75

// =============================================================================================
// PROBLEMA 2. Grados de separación
//  $amigos es una red social (quién es amigo de quién). Con una búsqueda en anchura, escribe
//  separacion(array $red, string $de, string $a): ?int que diga a cuántos "saltos" de amistad
//  está una persona de otra (null si no están conectadas).
// =============================================================================================
titulo('Problema 2');
$amigos = [
    'Ana' => ['Brais', 'Carmen'],
    'Brais' => ['Ana', 'Diego'],
    'Carmen' => ['Ana', 'Diego', 'Eva'],
    'Diego' => ['Brais', 'Carmen', 'Fran'],
    'Eva' => ['Carmen'],
    'Fran' => ['Diego'],
    'Xiana' => [],
];
// completa aquí tu solución
// ---- SOLUCIÓN ----
function separacion(array $red, string $de, string $a): ?int
{
    $cola = new SplQueue();
    $cola->enqueue([$de, 0]);
    $visitados = [$de => true];
    while (!$cola->isEmpty()) {
        [$persona, $saltos] = $cola->dequeue();
        if ($persona === $a) {
            return $saltos;
        }
        foreach ($red[$persona] ?? [] as $amigo) {
            if (!isset($visitados[$amigo])) {
                $visitados[$amigo] = true;
                $cola->enqueue([$amigo, $saltos + 1]);
            }
        }
    }
    return null;
}
ver('Ana -> Fran', separacion($amigos, 'Ana', 'Fran'));     // 3
ver('Eva -> Brais', separacion($amigos, 'Eva', 'Brais'));   // 3
ver('Ana -> Xiana', separacion($amigos, 'Ana', 'Xiana'));   // NULL

// =============================================================================================
// PROBLEMA 3. Media móvil de las ventas
//  Escribe la clase MediaMovil que guarde en una SplQueue solo los últimos N valores y tenga
//  anadir(float $valor): float, que devuelve la media de los últimos N. Calcula la media móvil
//  de 3 días de las ventas de la semana.
// =============================================================================================
titulo('Problema 3');
$ventas = [120, 80, 100, 160, 140, 90, 200];
// completa aquí tu solución
// ---- SOLUCIÓN ----
final class MediaMovil
{
    private SplQueue $valores;
    private float $suma = 0;

    public function __construct(private int $n)
    {
        $this->valores = new SplQueue();
    }

    public function anadir(float $valor): float
    {
        $this->valores->enqueue($valor);
        $this->suma += $valor;
        if (count($this->valores) > $this->n) {
            $this->suma -= $this->valores->dequeue();      // sale el más antiguo
        }
        return round($this->suma / count($this->valores), 2);
    }
}
$media = new MediaMovil(3);
ver('medias', array_map(fn($v) => $media->anadir($v), $ventas));
// [120.0, 100.0, 100.0, 113.33, 133.33, 130.0, 143.33]

// =============================================================================================
// PROBLEMA 4. ¿Cómo harías...?
//  En el ejemplo de la cola de correos, ¿cómo harías para que un correo que falla no se
//  reintente inmediatamente, sino como mínimo 2 "vueltas" después? Pista: guarda en el trabajo
//  la vuelta a partir de la cual se puede reintentar y, si aún no ha llegado, devuélvelo al final.
// =============================================================================================
titulo('Problema 4');
// completa aquí tu solución
// ---- SOLUCIÓN ----
$cola = new SplQueue();
foreach (['ana@example.com', 'brais@caido.test'] as $para) {
    $cola->enqueue(['para' => $para, 'intentos' => 0, 'desde' => 0]);
}
$vuelta = 0;
$log = [];
while (!$cola->isEmpty() && $vuelta < 20) {
    $vuelta++;
    $t = $cola->dequeue();
    if ($vuelta < $t['desde']) {
        $cola->enqueue($t);                         // todavía no le toca: al final
        continue;
    }
    $t['intentos']++;
    if (!str_ends_with($t['para'], '.test')) {
        $log[] = "vuelta $vuelta: enviado a {$t['para']}";
    } elseif ($t['intentos'] < 3) {
        $t['desde'] = $vuelta + 2;                  // reintentar como pronto dentro de 2 vueltas
        $cola->enqueue($t);
        $log[] = "vuelta $vuelta: fallo, reintento desde la vuelta {$t['desde']}";
    } else {
        $log[] = "vuelta $vuelta: descartado {$t['para']}";
    }
}
ver('log', $log);

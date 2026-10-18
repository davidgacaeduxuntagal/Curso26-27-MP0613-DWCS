<?php
/*
 * e02-SplPriorityQueue.php
 *  COLA DE PRIORIDAD con SplPriorityQueue (2): APLICACIONES REALES
 *  Contenidos: triaje de urgencias (gravedad + orden de llegada) en una clase propia, mensajes
 *              programados que se envían por fecha (primero el más próximo), camino más corto
 *              entre ciudades con el algoritmo de Dijkstra
 *
 *  Cómo trabajar este fichero:
 *   1. Pon un punto de ruptura DENTRO de los bucles y ejecuta en modo depuración.
 *   2. En Dijkstra, mira cómo la cola siempre entrega la ciudad pendiente MÁS CERCANA.
 */
require_once __DIR__ . '/../../utilidades.php';

// ---------------------------------------------------------------------------------------------
// Triaje de urgencias
// ENLACE: https://www.php.net/manual/es/class.splpriorityqueue.php
// ENUNCIADO EJEMPLO: en urgencias se atiende primero al más grave (nivel 1 = resucitación ...
//                   5 = no urgente) y, a igual gravedad, al que llegó antes.
// NOTA: la prioridad es [-nivel, -llegada]: el nivel 1 es el MÁS urgente, por eso se cambia el
//       signo; y a igual nivel, el número de llegada menor (más antiguo) debe salir antes.
// ---------------------------------------------------------------------------------------------
titulo('Triaje de urgencias');
final class Urgencias implements Countable
{
    private SplPriorityQueue $espera;
    private int $llegadas = 0;

    public function __construct()
    {
        $this->espera = new SplPriorityQueue();
    }

    public function llega(string $paciente, int $nivel): void
    {
        if ($nivel < 1 || $nivel > 5) {
            throw new InvalidArgumentException('El nivel de triaje va de 1 a 5');
        }
        $this->espera->insert(['paciente' => $paciente, 'nivel' => $nivel], [-$nivel, -$this->llegadas++]);
    }

    public function siguiente(): ?array
    {
        return $this->espera->isEmpty() ? null : $this->espera->extract();
    }

    public function count(): int
    {
        return count($this->espera);
    }
}
$urgencias = new Urgencias();
$urgencias->llega('Ana (esguince)', 4);
$urgencias->llega('Brais (dolor torácico)', 2);
$urgencias->llega('Carmen (fiebre)', 4);
$urgencias->llega('Diego (accidente de tráfico)', 1);
$urgencias->llega('Eva (corte profundo)', 2);
$orden = [];
while (($p = $urgencias->siguiente()) !== null) {
    $orden[] = "nivel {$p['nivel']}: {$p['paciente']}";
}
ver('orden de atención', $orden);
// Diego (1), Brais (2), Eva (2), Ana (4), Carmen (4): a igual nivel, por orden de llegada

// ---------------------------------------------------------------------------------------------
// Mensajes programados: primero el más próximo
// ENLACE: https://www.php.net/manual/es/splpriorityqueue.top.php
// ENUNCIADO EJEMPLO: la tienda programa recordatorios por correo. Cada minuto, un proceso envía
//                   los que ya han vencido (fecha <= ahora) y deja los demás para después.
// NOTA: con prioridad = -timestamp, el mensaje con la fecha MÁS PRÓXIMA sale primero. top()
//       permite mirar si ya ha vencido sin sacarlo.
// ---------------------------------------------------------------------------------------------
titulo('Mensajes programados');
$programados = new SplPriorityQueue();
$mensajes = [
    ['2026-10-16 10:00', 'Tu pedido 1001 ha salido'],
    ['2026-10-16 09:30', 'Recuerda completar tu compra'],
    ['2026-10-17 08:00', 'Valora tu pedido 998'],
    ['2026-10-16 09:45', 'Oferta flash: 20 % en auriculares'],
];
foreach ($mensajes as [$cuando, $texto]) {
    $programados->insert($texto, -strtotime($cuando));
}
$ahora = strtotime('2026-10-16 09:50');
$enviados = [];
while (!$programados->isEmpty()) {
    $programados->setExtractFlags(SplPriorityQueue::EXTR_PRIORITY);
    $cuando = -$programados->top();                 // ¿ha vencido el más próximo?
    if ($cuando > $ahora) {
        break;                                       // los demás son aún más tardíos
    }
    $programados->setExtractFlags(SplPriorityQueue::EXTR_DATA);
    $enviados[] = date('H:i', $cuando) . ' ' . $programados->extract();
}
ver('enviados a las 09:50', $enviados);      // ['09:30 Recuerda completar tu compra', '09:45 Oferta flash: 20 % en auriculares']
ver('pendientes', count($programados));      // 2

// ---------------------------------------------------------------------------------------------
// Algoritmo de Dijkstra: la ruta más corta
// ENLACE: https://es.wikipedia.org/wiki/Algoritmo_de_Dijkstra
// ENUNCIADO EJEMPLO: la furgoneta de reparto sale de A Coruña. ¿Cuál es la distancia más corta
//                   a cada ciudad y por dónde se va?
// NOTA: la cola de prioridad entrega siempre la ciudad pendiente MÁS CERCANA al origen (prioridad
//       = -distancia). Desde ella se mejoran las distancias de sus vecinas. Si una ciudad sale de
//       la cola con una distancia peor que la ya conocida, es una entrada antigua: se ignora.
// ---------------------------------------------------------------------------------------------
titulo('Dijkstra');
$carreteras = [                                        // km aproximados
    'A Coruña' => ['Santiago' => 73, 'Lugo' => 97, 'Ferrol' => 55],
    'Ferrol' => ['A Coruña' => 55, 'Lugo' => 114],
    'Santiago' => ['A Coruña' => 73, 'Pontevedra' => 62, 'Lugo' => 106, 'Ourense' => 111],
    'Lugo' => ['A Coruña' => 97, 'Ferrol' => 114, 'Santiago' => 106, 'Ourense' => 96],
    'Pontevedra' => ['Santiago' => 62, 'Vigo' => 27, 'Ourense' => 100],
    'Vigo' => ['Pontevedra' => 27, 'Ourense' => 101],
    'Ourense' => ['Santiago' => 111, 'Lugo' => 96, 'Pontevedra' => 100, 'Vigo' => 101],
];
function dijkstra(array $grafo, string $origen): array
{
    $distancia = [$origen => 0];
    $anterior = [];
    $cola = new SplPriorityQueue();
    $cola->setExtractFlags(SplPriorityQueue::EXTR_BOTH);
    $cola->insert($origen, 0);
    while (!$cola->isEmpty()) {
        ['data' => $ciudad, 'priority' => $p] = $cola->extract();   // la más cercana pendiente
        if (-$p > $distancia[$ciudad]) {
            continue;                                                 // entrada antigua
        }
        foreach ($grafo[$ciudad] as $vecina => $km) {
            $nueva = $distancia[$ciudad] + $km;
            if ($nueva < ($distancia[$vecina] ?? PHP_INT_MAX)) {
                $distancia[$vecina] = $nueva;
                $anterior[$vecina] = $ciudad;
                $cola->insert($vecina, -$nueva);
            }
        }
    }
    asort($distancia);
    return [$distancia, $anterior];
}
[$km, $anterior] = dijkstra($carreteras, 'A Coruña');
ver('distancias desde A Coruña', $km);
// A Coruña 0, Ferrol 55, Santiago 73, Lugo 97, Pontevedra 135, Vigo 162, Ourense 184
$ruta = ['Vigo'];
while (isset($anterior[$ruta[0]])) {
    array_unshift($ruta, $anterior[$ruta[0]]);
}
ver('ruta a Vigo', implode(' -> ', $ruta));   // "A Coruña -> Santiago -> Pontevedra -> Vigo"

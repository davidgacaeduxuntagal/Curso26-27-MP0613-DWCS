<?php
/*
 * e02-SplQueue.php
 *  COLA (Queue) con SplQueue (2): APLICACIONES REALES
 *  Contenidos: cola de envío de correos con reintentos, planificador "round-robin" (turnos de
 *              CPU), búsqueda en anchura (BFS) para el camino más corto en un mapa, buffer con
 *              los N últimos mensajes de un chat
 *
 *  Cómo trabajar este fichero:
 *   1. Pon un punto de ruptura DENTRO de los bucles while y ejecuta en modo depuración.
 *   2. Observa con ver() cómo los elementos entran por el final y salen por el frente.
 */
require_once __DIR__ . '/../../utilidades.php';

// ---------------------------------------------------------------------------------------------
// Cola de trabajos con reintentos
// ENLACE: https://www.php.net/manual/es/class.splqueue.php
// ENUNCIADO EJEMPLO: la tienda envía los correos de confirmación desde una cola. Si un envío
//                   falla, el correo vuelve al FINAL de la cola para reintentarlo más tarde
//                   (como mucho 3 intentos).
// NOTA: así funcionan los sistemas de colas reales (RabbitMQ, Redis, las "jobs" de Laravel): la
//       web encola el trabajo y responde enseguida; otro proceso los va atendiendo en orden.
// ---------------------------------------------------------------------------------------------
titulo('Cola de envío con reintentos');
$correos = new SplQueue();
foreach (['ana@example.com', 'brais@caido.test', 'carmen@example.com'] as $destino) {
    $correos->enqueue(['para' => $destino, 'intentos' => 0]);
}
$registro = [];
while (!$correos->isEmpty()) {
    $trabajo = $correos->dequeue();                       // pon aquí un punto de ruptura
    $trabajo['intentos']++;
    $ok = !str_ends_with($trabajo['para'], '.test');     // simulación: el dominio .test siempre falla
    if ($ok) {
        $registro[] = "enviado a {$trabajo['para']} (intento {$trabajo['intentos']})";
    } elseif ($trabajo['intentos'] < 3) {
        $correos->enqueue($trabajo);                      // al final de la cola: se reintenta después
        $registro[] = "fallo con {$trabajo['para']}, se reintentará";
    } else {
        $registro[] = "descartado {$trabajo['para']} tras 3 intentos";
    }
}
ver('registro', $registro);
// ana enviado / fallo brais / carmen enviado / fallo brais / descartado brais tras 3 intentos

// ---------------------------------------------------------------------------------------------
// Planificador round-robin
// ENLACE: https://es.wikipedia.org/wiki/Planificaci%C3%B3n_Round-robin
// ENUNCIADO EJEMPLO: el sistema operativo reparte la CPU entre procesos: cada uno trabaja como
//                   mucho un "quantum" de 3 ms y, si no ha terminado, vuelve al final de la cola.
// ---------------------------------------------------------------------------------------------
titulo('Round-robin');
$procesos = new SplQueue();
foreach (['navegador' => 7, 'editor' => 3, 'música' => 5] as $nombre => $ms) {
    $procesos->enqueue([$nombre, $ms]);
}
$quantum = 3;
$reloj = 0;
$finales = [];
while (!$procesos->isEmpty()) {
    [$nombre, $pendiente] = $procesos->dequeue();
    $usa = min($quantum, $pendiente);
    $reloj += $usa;
    if ($pendiente - $usa > 0) {
        $procesos->enqueue([$nombre, $pendiente - $usa]);
    } else {
        $finales[$nombre] = $reloj;                       // en qué milisegundo terminó
    }
}
ver('terminan en el ms', $finales);    // ['editor' => 6, 'música' => 14, 'navegador' => 15]

// ---------------------------------------------------------------------------------------------
// Búsqueda en anchura (BFS): el camino más corto
// ENLACE: https://es.wikipedia.org/wiki/B%C3%BAsqueda_en_anchura
// ENUNCIADO EJEMPLO: el repartidor sale del almacén (A) y debe llegar a la casa del cliente (C)
//                   por las calles del mapa ('#' = edificio). ¿Cuántas manzanas debe recorrer?
// NOTA: la cola hace que se exploren primero todas las casillas a distancia 1, luego las de
//       distancia 2... Por eso, la primera vez que se llega al destino, el camino es el MÁS CORTO.
//       (Con una pila, en cambio, se obtendría un camino cualquiera.)
// ---------------------------------------------------------------------------------------------
titulo('Camino más corto (BFS)');
$mapa = [
    'A..#....',
    '.#.#.##.',
    '.#...#..',
    '.####.#.',
    '......#C',
];
function distanciaMinima(array $mapa): ?int
{
    foreach ($mapa as $f => $fila) {
        if (($c = strpos($fila, 'A')) !== false) {
            $inicio = [$f, $c];
        }
    }
    $cola = new SplQueue();
    $cola->enqueue([...$inicio, 0]);                       // fila, columna, distancia
    $visitadas = ["{$inicio[0]},{$inicio[1]}" => true];
    while (!$cola->isEmpty()) {
        [$f, $c, $d] = $cola->dequeue();
        if ($mapa[$f][$c] === 'C') {
            return $d;
        }
        foreach ([[0, 1], [1, 0], [0, -1], [-1, 0]] as [$df, $dc]) {
            [$nf, $nc] = [$f + $df, $c + $dc];
            // ¡$nc >= 0! En PHP, $texto[-1] es el ÚLTIMO carácter: sin esta comprobación el
            // repartidor "atravesaría" el borde del mapa y aparecería por el otro lado.
            if ($nf >= 0 && $nc >= 0 && isset($mapa[$nf][$nc]) && $mapa[$nf][$nc] !== '#' && !isset($visitadas["$nf,$nc"])) {
                $visitadas["$nf,$nc"] = true;
                $cola->enqueue([$nf, $nc, $d + 1]);
            }
        }
    }
    return null;                                           // no hay camino
}
ver('distancia mínima', distanciaMinima($mapa));          // 15 (por arriba: el camino de abajo está cortado)

// ---------------------------------------------------------------------------------------------
// Buffer con los N últimos elementos
// ENLACE: https://www.php.net/manual/es/class.splqueue.php
// ENUNCIADO EJEMPLO: el chat de soporte muestra solo los 3 últimos mensajes.
// NOTA: al llegar uno nuevo con el buffer lleno, se descarta el más antiguo (el del frente).
// ---------------------------------------------------------------------------------------------
titulo('Últimos N mensajes');
final class UltimosMensajes implements IteratorAggregate, Countable
{
    private SplQueue $cola;

    public function __construct(private int $maximo)
    {
        $this->cola = new SplQueue();
    }

    public function anadir(string $mensaje): void
    {
        $this->cola->enqueue($mensaje);
        if (count($this->cola) > $this->maximo) {
            $this->cola->dequeue();                         // fuera el más antiguo
        }
    }

    public function getIterator(): Iterator
    {
        return $this->cola;                                 // SplQueue ya es un Iterator
    }

    public function count(): int
    {
        return count($this->cola);
    }
}
$chat = new UltimosMensajes(3);
foreach (['Hola', '¿Hay talla M?', 'Sí, en azul', '¿Y en rojo?', 'Mañana llega'] as $m) {
    $chat->anadir($m);
}
ver('últimos', iterator_to_array($chat, false));          // ['Sí, en azul', '¿Y en rojo?', 'Mañana llega']

<?php
/*
 * e02-SplMinHeap.php
 *  MONTÍCULO MÍNIMO con SplMinHeap (2): APLICACIONES REALES
 *  Contenidos: los K mayores de un flujo de datos con un min-heap de tamaño K, fusionar varias
 *              listas ordenadas (registros de varios servidores), la próxima máquina libre en un
 *              taller (asignar trabajos al que antes termina)
 *
 *  Cómo trabajar este fichero:
 *   1. Pon un punto de ruptura DENTRO de los bucles y ejecuta en modo depuración.
 *   2. En el problema de los K mayores, fíjate en que el montículo nunca tiene más de K elementos.
 */
require_once __DIR__ . '/../../utilidades.php';

// ---------------------------------------------------------------------------------------------
// Los K mayores con un montículo MÍNIMO de tamaño K
// ENLACE: https://www.php.net/manual/es/class.splminheap.php
// ENUNCIADO EJEMPLO: de las puntuaciones de miles de partidas de un juego online, mostrar el top 3
//                   sin guardar ni ordenar todas.
// NOTA: el montículo guarda los K mejores vistos hasta ahora; su raíz (top) es el PEOR de esos K.
//       Si llega una puntuación mejor que la raíz, se saca la raíz y se mete la nueva. Así solo se
//       usa memoria para K elementos y cada paso cuesta O(log K).
// ---------------------------------------------------------------------------------------------
titulo('Top K con un min-heap');
function topK(iterable $puntuaciones, int $k): array
{
    $mejores = new SplMinHeap();
    foreach ($puntuaciones as $p) {
        if (count($mejores) < $k) {
            $mejores->insert($p);
        } elseif ($p > $mejores->top()) {      // mejor que el peor de los K
            $mejores->extract();
            $mejores->insert($p);
        }
    }
    return array_reverse(iterator_to_array($mejores, false));   // de mayor a menor
}
function partidas(): Generator                  // simula un flujo de datos muy largo
{
    mt_srand(2026);                             // semilla fija: siempre los mismos "aleatorios"
    for ($i = 0; $i < 10_000; $i++) {
        yield mt_rand(0, 99_999);
    }
    yield 100_500;                              // un récord al final
}
$top = topK(partidas(), 3);
ver('top 3', $top);                             // [100500, 99989, 99984] (con la semilla 2026)
ver('¿ordenado?', $top[0] >= $top[1] && $top[1] >= $top[2]);   // true

// ---------------------------------------------------------------------------------------------
// Fusionar varias listas ordenadas
// ENLACE: https://es.wikipedia.org/wiki/Algoritmo_de_mezcla_k-v%C3%ADas
// ENUNCIADO EJEMPLO: tres servidores web generan cada uno su registro ordenado por hora; hay que
//                   unirlos en uno solo ordenado.
// NOTA: el montículo guarda el PRIMER elemento pendiente de cada lista, como [hora, servidor,
//       posición]. Se saca el menor, se añade al resultado y se mete el siguiente de esa misma
//       lista. Los arrays se comparan elemento a elemento, así que ordena por la hora.
// ---------------------------------------------------------------------------------------------
titulo('Fusionar registros');
$registros = [
    'web1' => ['09:00 GET /', '09:05 GET /carta', '09:20 POST /pedido'],
    'web2' => ['09:02 GET /', '09:30 GET /contacto'],
    'web3' => ['08:59 GET /admin', '09:06 GET /carta', '09:07 GET /carta', '09:31 GET /'],
];
$frente = new SplMinHeap();
foreach ($registros as $servidor => $lineas) {
    $frente->insert([$lineas[0], $servidor, 0]);           // el primero de cada servidor
}
$fusion = [];
while (!$frente->isEmpty()) {
    [$linea, $servidor, $i] = $frente->extract();          // pon aquí un punto de ruptura
    $fusion[] = "$linea ($servidor)";
    if (isset($registros[$servidor][$i + 1])) {
        $frente->insert([$registros[$servidor][$i + 1], $servidor, $i + 1]);
    }
}
ver('registro unificado', $fusion);
// 08:59 web3, 09:00 web1, 09:02 web2, 09:05 web1, 09:06 web3, 09:07 web3, 09:20 web1, 09:30 web2, 09:31 web3

// ---------------------------------------------------------------------------------------------
// Asignar trabajos a la máquina que antes queda libre
// ENLACE: https://www.php.net/manual/es/splheap.top.php
// ENUNCIADO EJEMPLO: una imprenta tiene 2 máquinas. Cada trabajo se manda a la máquina que antes
//                   termina lo que está haciendo. ¿A qué hora acaba todo?
// NOTA: el montículo guarda [minuto en que queda libre, máquina]. top() es la que antes se libera.
// ---------------------------------------------------------------------------------------------
titulo('Máquinas libres');
$maquinas = new SplMinHeap();
$maquinas->insert([0, 'M1']);
$maquinas->insert([0, 'M2']);
$asignacion = [];
foreach (['carteles' => 30, 'folletos' => 20, 'tarjetas' => 10, 'libros' => 45, 'sobres' => 5] as $trabajo => $min) {
    [$libre, $maquina] = $maquinas->extract();             // la que antes termina
    $asignacion[] = "$trabajo en $maquina (min $libre - " . ($libre + $min) . ')';
    $maquinas->insert([$libre + $min, $maquina]);
}
ver('asignación', $asignacion);
$fin = max(array_map(fn($m) => $m[0], iterator_to_array($maquinas, false)));
ver('todo termina en el minuto', $fin);                   // 75

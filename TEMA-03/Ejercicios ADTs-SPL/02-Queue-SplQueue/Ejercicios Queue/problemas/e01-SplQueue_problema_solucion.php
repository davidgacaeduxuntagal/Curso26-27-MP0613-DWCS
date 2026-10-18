<?php
/*
 * e01-SplQueue_problema_solucion.php
 *  (FICHERO CON LAS SOLUCIONES)
 *  Problemas de: SplQueue (enqueue, dequeue, bottom, top, isEmpty, count, foreach)
 *  (repasa los ejemplos de ../e01-SplQueue.php)
 *
 *  Resuelve cada problema donde se indica y ejecútalo en modo depuración para comprobarlo.
 */
require_once __DIR__ . '/../../../utilidades.php';

// =============================================================================================
// PROBLEMA 1. Dispensador de turnos
//  Escribe la clase Turnos con pedirTurno(string $nombre): int (da un número correlativo: 1, 2,
//  3...), siguiente(): ?array (saca al siguiente: ['numero' => ..., 'nombre' => ...] o null si no
//  hay nadie) y enEspera(): int. Usa una SplQueue.
// =============================================================================================
titulo('Problema 1');
// completa aquí tu solución
// ---- SOLUCIÓN ----
final class Turnos
{
    private SplQueue $cola;
    private int $ultimo = 0;

    public function __construct()
    {
        $this->cola = new SplQueue();
    }

    public function pedirTurno(string $nombre): int
    {
        $this->cola->enqueue(['numero' => ++$this->ultimo, 'nombre' => $nombre]);
        return $this->ultimo;
    }

    public function siguiente(): ?array
    {
        return $this->cola->isEmpty() ? null : $this->cola->dequeue();
    }

    public function enEspera(): int
    {
        return count($this->cola);
    }
}
$carniceria = new Turnos();
$carniceria->pedirTurno('Ana');
$carniceria->pedirTurno('Brais');
ver('turno de Carmen', $carniceria->pedirTurno('Carmen'));   // 3
ver('siguiente', $carniceria->siguiente());                  // ['numero' => 1, 'nombre' => 'Ana']
ver('en espera', $carniceria->enEspera());                   // 2

// =============================================================================================
// PROBLEMA 2. Dos cajas en el supermercado
//  Reparte los clientes de $llegadas entre dos SplQueue (caja 1 y caja 2), mandando cada cliente
//  a la cola con MENOS gente (si empatan, a la 1). Muestra cómo quedan las dos colas.
// =============================================================================================
titulo('Problema 2');
$llegadas = ['Ana', 'Brais', 'Carmen', 'Diego', 'Eva'];
$caja1 = new SplQueue();
$caja2 = new SplQueue();
$caja1->enqueue('Xoán');      // la caja 1 ya tiene a alguien
// completa aquí tu solución
// ---- SOLUCIÓN ----
foreach ($llegadas as $cliente) {
    $destino = count($caja1) <= count($caja2) ? $caja1 : $caja2;
    $destino->enqueue($cliente);
}
ver('caja 1', $caja1);        // frente -> ['Xoán', 'Brais', 'Diego'] <- final
ver('caja 2', $caja2);        // frente -> ['Ana', 'Carmen', 'Eva'] <- final

// =============================================================================================
// PROBLEMA 3. El problema de Josefo (juego de las sillas)
//  N personas en círculo; empezando por la primera, se cuenta hasta K y la K-ésima queda
//  eliminada; se sigue contando desde la siguiente. Simúlalo con una cola: se saca a la persona
//  del frente y, si no le toca, vuelve al final. Devuelve el orden de eliminación.
// =============================================================================================
titulo('Problema 3');
// completa aquí tu solución
// ---- SOLUCIÓN ----
function josefo(array $personas, int $k): array
{
    $cola = new SplQueue();
    foreach ($personas as $p) {
        $cola->enqueue($p);
    }
    $eliminados = [];
    while (!$cola->isEmpty()) {
        for ($i = 1; $i < $k; $i++) {
            $cola->enqueue($cola->dequeue());    // las K-1 primeras pasan al final
        }
        $eliminados[] = $cola->dequeue();         // la K-ésima, fuera
    }
    return $eliminados;
}
ver('orden', josefo(['A', 'B', 'C', 'D', 'E', 'F', 'G'], 3));   // ['C', 'F', 'B', 'G', 'E', 'A', 'D']: gana D

// =============================================================================================
// PROBLEMA 4. ¿Cómo harías...?
//  ¿Cómo comprobarías si una SplQueue tiene un elemento SIN sacar a nadie de la cola? ¿Y cómo
//  quitarías de la cola a una persona concreta (se cansa de esperar) conservando el orden de
//  los demás? Escribe estaEnCola() y abandonar().
// =============================================================================================
titulo('Problema 4');
$cola = new SplQueue();
foreach (['Ana', 'Brais', 'Carmen', 'Diego'] as $p) {
    $cola->enqueue($p);
}
// completa aquí tu solución
// ---- SOLUCIÓN ----
function estaEnCola(SplQueue $cola, string $nombre): bool
{
    foreach ($cola as $persona) {              // foreach no saca a nadie (modo KEEP)
        if ($persona === $nombre) {
            return true;
        }
    }
    return false;
}
function abandonar(SplQueue $cola, string $nombre): void
{
    $total = count($cola);
    for ($i = 0; $i < $total; $i++) {          // damos una vuelta completa a la cola
        $persona = $cola->dequeue();
        if ($persona !== $nombre) {
            $cola->enqueue($persona);           // los demás vuelven al final, en el mismo orden
        }
    }
}
ver('¿está Carmen?', estaEnCola($cola, 'Carmen'));   // true
abandonar($cola, 'Brais');
ver('cola', $cola);                                   // frente -> ['Ana', 'Carmen', 'Diego'] <- final

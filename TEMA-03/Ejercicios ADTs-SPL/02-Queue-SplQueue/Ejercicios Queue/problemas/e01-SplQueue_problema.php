<?php
/*
 * e01-SplQueue_problema.php
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

// =============================================================================================
// PROBLEMA 3. El problema de Josefo (juego de las sillas)
//  N personas en círculo; empezando por la primera, se cuenta hasta K y la K-ésima queda
//  eliminada; se sigue contando desde la siguiente. Simúlalo con una cola: se saca a la persona
//  del frente y, si no le toca, vuelve al final. Devuelve el orden de eliminación.
// =============================================================================================
titulo('Problema 3');
// completa aquí tu solución

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

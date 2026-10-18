<?php
/*
 * e09-POO_problema.php
 *  Problemas de: enum puros y con valor, cases(), from / tryFrom, métodos y constantes en enums,
 *                match, enums con interfaces, enums como propiedades
 *  (repasa los ejemplos de ../Ejercicios OOP/e09-POO.php)
 *
 *  Resuelve cada problema donde se indica y ejecútalo en modo depuración para comprobarlo.
 */
require_once __DIR__ . '/../Ejercicios OOP/utilidades.php';

// =============================================================================================
// PROBLEMA 1. Días de la semana
//  Crea el enum DiaSemana con valor int (Lunes = 1 ... Domingo = 7) y los métodos
//  esFinDeSemana(): bool y nombreGallego(): string (luns, martes, mércores, xoves, venres,
//  sábado, domingo). Muestra el nombre en gallego de los días de fin de semana usando cases().
// =============================================================================================
titulo('Problema 1');
// completa aquí tu solución

// =============================================================================================
// PROBLEMA 2. Del formulario al enum
//  El formulario envía la talla de una camiseta en $_POST['talla']. Crea el enum Talla con valor
//  string (s, m, l, xl) y escribe leerTalla(string $valor): ?Talla que devuelva null si la talla
//  no existe (sin lanzar excepciones). Pruébala con $envios.
// =============================================================================================
titulo('Problema 2');
$envios = ['m', 'XL', 'xxl', ''];
// completa aquí tu solución

// =============================================================================================
// PROBLEMA 3. El semáforo
//  Crea el enum Semaforo (Verde, Ambar, Rojo) con un método siguiente(): self (Verde -> Ambar ->
//  Rojo -> Verde) y un método segundos(): int (Verde 30, Ambar 5, Rojo 25) usando match. Simula
//  un ciclo completo empezando en Verde y calcula cuánto dura.
// =============================================================================================
titulo('Problema 3');
// completa aquí tu solución

// =============================================================================================
// PROBLEMA 4. Prioridades de las incidencias
//  Crea el enum Prioridad con valor int (Baja = 1, Media = 2, Alta = 3, Critica = 4) que
//  implemente una interfaz ConColor con color(): string. Ordena $incidencias de mayor a menor
//  prioridad y muéstralas como "[CRITICA] Servidor caído (rojo)".
// =============================================================================================
titulo('Problema 4');
// completa aquí tu solución

// =============================================================================================
// PROBLEMA 5. ¿Cómo harías...?
//  Antes de PHP 8.1, el estado de una reserva se guardaba con constantes:
//      class Reserva { const PENDIENTE = 'p'; const CONFIRMADA = 'c'; const ANULADA = 'a'; ... }
//  y el método cambiarEstado(string $estado) aceptaba cualquier texto. ¿Cómo lo harías con un
//  enum para que fuera imposible poner un estado que no existe? Escríbelo y demuestra que
//  pasar 'x' da un error.
// =============================================================================================
titulo('Problema 5');
// completa aquí tu solución

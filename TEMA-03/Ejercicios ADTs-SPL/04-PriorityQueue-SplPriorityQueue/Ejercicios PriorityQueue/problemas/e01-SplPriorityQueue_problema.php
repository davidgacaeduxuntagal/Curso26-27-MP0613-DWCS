<?php
/*
 * e01-SplPriorityQueue_problema.php
 *  Problemas de: SplPriorityQueue (insert, extract, top, setExtractFlags, desempate con arrays,
 *                clone, compare)
 *  (repasa los ejemplos de ../e01-SplPriorityQueue.php)
 *
 *  Resuelve cada problema donde se indica y ejecútalo en modo depuración para comprobarlo.
 */
require_once __DIR__ . '/../../../utilidades.php';

// =============================================================================================
// PROBLEMA 1. Embarque por grupos
//  En el aeropuerto embarcan primero los pasajeros de los grupos prioritarios: 'familias' (3),
//  'business' (2) y 'general' (1). Mete $pasajeros en una SplPriorityQueue y muestra el orden de
//  embarque. A igual grupo, debe embarcar antes el que llegó antes a la puerta.
// =============================================================================================
titulo('Problema 1');
$grupos = ['familias' => 3, 'business' => 2, 'general' => 1];
$pasajeros = [['Ana', 'general'], ['Brais', 'business'], ['Carmen', 'familias'], ['Diego', 'general'], ['Eva', 'business']];
// completa aquí tu solución

// =============================================================================================
// PROBLEMA 2. Con su prioridad
//  Con la cola $tareas, muestra cada tarea con su prioridad ("[5] Copia de seguridad") en el
//  orden en que se harán, SIN vaciar la cola original. Comprueba al final que sigue entera.
// =============================================================================================
titulo('Problema 2');
$tareas = new SplPriorityQueue();
$tareas->insert('Actualizar PHP', 3);
$tareas->insert('Copia de seguridad', 5);
$tareas->insert('Revisar logs', 1);
// completa aquí tu solución

// =============================================================================================
// PROBLEMA 3. Primero lo que antes caduca
//  En el almacén de un supermercado hay que vender primero los productos que caducan antes.
//  Crea la clase ColaCaducidad (hereda de SplPriorityQueue y redefine compare) para que salga
//  primero la fecha MÁS PRÓXIMA ("Y-m-d", que se puede comparar como texto).
// =============================================================================================
titulo('Problema 3');
// completa aquí tu solución

// =============================================================================================
// PROBLEMA 4. ¿Cómo harías...?
//  ¿Cómo obtendrías los 3 productos más vendidos de $ventas (producto => unidades) usando una
//  SplPriorityQueue? ¿Y sin ella? Compara las dos soluciones.
// =============================================================================================
titulo('Problema 4');
$ventas = ['ratón' => 120, 'teclado' => 85, 'monitor' => 40, 'cascos' => 150, 'webcam' => 60, 'cable' => 300];
// completa aquí tu solución

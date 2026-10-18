<?php
/*
 * e02-SplObjectStorage_problema.php
 *  Problemas de: aplicaciones de SplObjectStorage (mapas objeto => dato, el patrón Observador
 *                con SplSubject / SplObserver, grafos de objetos, WeakMap)
 *  (repasa los ejemplos de ../e02-SplObjectStorage.php)
 *
 *  Resuelve cada problema donde se indica y ejecútalo en modo depuración para comprobarlo.
 */
require_once __DIR__ . '/../../../utilidades.php';

// =============================================================================================
// PROBLEMA 1. Tiempo en cada tarea
//  Un empleado puede empezar() y parar() el cronómetro de varias tareas (objetos Tarea). Guarda
//  en un SplObjectStorage, para cada tarea, los minutos acumulados y el minuto en que se empezó
//  (null si está parada). Usa los minutos "simulados" que se pasan como parámetro.
// =============================================================================================
titulo('Problema 1');
final class Tarea
{
    public function __construct(public readonly string $titulo)
    {
    }
}
// completa aquí tu solución

// =============================================================================================
// PROBLEMA 2. Un boletín de noticias con observadores
//  Escribe la clase Boletin (implementa SplSubject) que guarde sus suscriptores en un
//  SplObjectStorage, y la clase Suscriptor (implementa SplObserver) que guarde los titulares
//  recibidos. publicar(string $titular) debe avisar a todos. Un suscriptor que se da de baja ya
//  no recibe nada.
// =============================================================================================
titulo('Problema 2');
// completa aquí tu solución

// =============================================================================================
// PROBLEMA 3. ¿Qué pasa aquí?
//  Este código quiere dar de baja a todos los suscriptores inactivos recorriendo el
//  SplObjectStorage, pero se deja alguno. Explica el fallo y corrígelo.
// =============================================================================================
titulo('Problema 3');
final class Cuenta
{
    public function __construct(public readonly string $usuario, public readonly bool $activa)
    {
    }
}
$cuentas = new SplObjectStorage();
foreach ([['ana', false], ['brais', false], ['carmen', true], ['diego', false]] as [$u, $activa]) {
    $cuentas->attach(new Cuenta($u, $activa));
}
foreach ($cuentas as $c) {
    if (!$c->activa) {
        $cuentas->detach($c);
    }
}
ver('quedan (mal)', array_map(fn($c) => $c->usuario, iterator_to_array($cuentas, false)));   // ['brais', 'carmen']: ¡brais sigue!
// completa aquí tu solución

// =============================================================================================
// PROBLEMA 4. ¿Cómo harías...?
//  ¿SplObjectStorage o WeakMap? Para cada caso, contesta en un comentario.
//   a) Las conexiones abiertas de un chat, que el servidor debe recorrer para enviar mensajes.
//   b) Guardar, para cada objeto Producto, su precio con impuestos ya calculado (una caché).
//   c) Calcular la unión y la intersección de dos grupos de alumnos.
// =============================================================================================
titulo('Problema 4');
// completa aquí tu solución

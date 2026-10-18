<?php
/*
 * e02-SplObjectStorage_problema_solucion.php
 *  (FICHERO CON LAS SOLUCIONES)
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
// ---- SOLUCIÓN ----
final class Cronometro
{
    private SplObjectStorage $tiempos;      // Tarea => ['acumulado' => int, 'desde' => ?int]

    public function __construct()
    {
        $this->tiempos = new SplObjectStorage();
    }

    public function empezar(Tarea $t, int $minuto): void
    {
        $datos = $this->tiempos->contains($t) ? $this->tiempos[$t] : ['acumulado' => 0, 'desde' => null];
        $datos['desde'] = $minuto;
        $this->tiempos[$t] = $datos;
    }

    public function parar(Tarea $t, int $minuto): void
    {
        $datos = $this->tiempos[$t];
        $datos['acumulado'] += $minuto - $datos['desde'];
        $datos['desde'] = null;
        $this->tiempos[$t] = $datos;          // (los arrays se copian: hay que volver a guardarlo)
    }

    public function resumen(): array
    {
        $r = [];
        foreach ($this->tiempos as $t) {
            $r[$t->titulo] = $this->tiempos->getInfo()['acumulado'];
        }
        return $r;
    }
}
$web = new Tarea('Maquetar la web');
$bd = new Tarea('Diseñar la base de datos');
$crono = new Cronometro();
$crono->empezar($web, 0);
$crono->parar($web, 45);
$crono->empezar($bd, 50);
$crono->parar($bd, 80);
$crono->empezar($web, 90);
$crono->parar($web, 110);
ver('minutos', $crono->resumen());   // ['Maquetar la web' => 65, 'Diseñar la base de datos' => 30]

// =============================================================================================
// PROBLEMA 2. Un boletín de noticias con observadores
//  Escribe la clase Boletin (implementa SplSubject) que guarde sus suscriptores en un
//  SplObjectStorage, y la clase Suscriptor (implementa SplObserver) que guarde los titulares
//  recibidos. publicar(string $titular) debe avisar a todos. Un suscriptor que se da de baja ya
//  no recibe nada.
// =============================================================================================
titulo('Problema 2');
// completa aquí tu solución
// ---- SOLUCIÓN ----
final class Boletin implements SplSubject
{
    private SplObjectStorage $suscriptores;
    public ?string $ultimo = null;

    public function __construct()
    {
        $this->suscriptores = new SplObjectStorage();
    }

    public function attach(SplObserver $s): void
    {
        $this->suscriptores->attach($s);
    }

    public function detach(SplObserver $s): void
    {
        $this->suscriptores->detach($s);
    }

    public function notify(): void
    {
        foreach (clone $this->suscriptores as $s) {
            $s->update($this);
        }
    }

    public function publicar(string $titular): void
    {
        $this->ultimo = $titular;
        $this->notify();
    }
}
final class Suscriptor implements SplObserver
{
    public array $recibidos = [];

    public function update(SplSubject $boletin): void
    {
        $this->recibidos[] = $boletin->ultimo;
    }
}
$boletin = new Boletin();
$ana = new Suscriptor();
$brais = new Suscriptor();
$boletin->attach($ana);
$boletin->attach($brais);
$boletin->publicar('Abre la matrícula de FP');
$boletin->detach($brais);
$boletin->publicar('Nuevo horario de la biblioteca');
ver('Ana', $ana->recibidos);         // ['Abre la matrícula de FP', 'Nuevo horario de la biblioteca']
ver('Brais', $brais->recibidos);     // ['Abre la matrícula de FP']

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
// ---- SOLUCIÓN ----
// Fallo: al borrar (detach) un elemento del SplObjectStorage que se está recorriendo, el
// recorrido se desplaza y se SALTA el siguiente. Solución: recorrer una copia (clone) y borrar
// del original; o guardar primero los que hay que borrar y quitarlos después con removeAll.
$inactivas = new SplObjectStorage();
foreach ($cuentas as $c) {
    if (!$c->activa) {
        $inactivas->attach($c);
    }
}
$cuentas->removeAll($inactivas);
ver('quedan (bien)', array_map(fn($c) => $c->usuario, iterator_to_array($cuentas, false)));  // ['carmen']

// =============================================================================================
// PROBLEMA 4. ¿Cómo harías...?
//  ¿SplObjectStorage o WeakMap? Para cada caso, contesta en un comentario.
//   a) Las conexiones abiertas de un chat, que el servidor debe recorrer para enviar mensajes.
//   b) Guardar, para cada objeto Producto, su precio con impuestos ya calculado (una caché).
//   c) Calcular la unión y la intersección de dos grupos de alumnos.
// =============================================================================================
titulo('Problema 4');
// completa aquí tu solución
// ---- SOLUCIÓN ----
// a) SplObjectStorage: el chat necesita "retener" las conexiones y recorrerlas; se quitan con
//    detach al desconectarse.
// b) WeakMap: es una caché; si el producto ya no se usa, su entrada debe desaparecer sola y no
//    ocupar memoria.
// c) SplObjectStorage: tiene addAll, removeAll y removeAllExcept para las operaciones de conjuntos.
ver('respuesta', 'a) SplObjectStorage, b) WeakMap, c) SplObjectStorage');

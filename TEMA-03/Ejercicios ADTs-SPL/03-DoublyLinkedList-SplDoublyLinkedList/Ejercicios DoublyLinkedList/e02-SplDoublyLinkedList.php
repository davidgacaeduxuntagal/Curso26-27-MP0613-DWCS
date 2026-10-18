<?php
/*
 * e02-SplDoublyLinkedList.php
 *  LISTA DOBLEMENTE ENLAZADA con SplDoublyLinkedList (2): APLICACIONES REALES
 *  Contenidos: cola de doble extremo (deque) para pedidos normales y urgentes, comprobar
 *              palíndromos sacando por los dos extremos, una lista que se mantiene ordenada
 *              (insertar con add), una caché LRU ("los usados más recientemente"), una lista
 *              de reproducción circular
 *
 *  Cómo trabajar este fichero:
 *   1. Pon un punto de ruptura DENTRO de los métodos y bucles y ejecuta en modo depuración.
 *   2. Observa con ver() cómo cambia la lista por los dos extremos.
 */
require_once __DIR__ . '/../../utilidades.php';

// ---------------------------------------------------------------------------------------------
// Cola de doble extremo (deque)
// ENLACE: https://www.php.net/manual/es/class.spldoublylinkedlist.php
// ENUNCIADO EJEMPLO: la cocina del restaurante atiende los pedidos por orden de llegada, pero los
//                   pedidos de los clientes con alergias (urgentes) se ponen DELANTE.
// NOTA: una deque permite añadir y quitar por los dos extremos: push / unshift / shift.
// ---------------------------------------------------------------------------------------------
titulo('Deque: pedidos normales y urgentes');
$cocina = new SplDoublyLinkedList();
$cocina->push('mesa 4: pulpo');
$cocina->push('mesa 7: tortilla');
$cocina->unshift('mesa 2: menú sin gluten (URGENTE)');    // delante de todos
$cocina->push('mesa 1: filloas');
ver('cocina', $cocina);
ver('se prepara', $cocina->shift());                       // "mesa 2: menú sin gluten (URGENTE)"
ver('se prepara', $cocina->shift());                       // "mesa 4: pulpo"
$cancelado = $cocina->pop();                               // la mesa 1 se marcha: sale por el final
ver('cancelado', $cancelado);                              // "mesa 1: filloas"

// ---------------------------------------------------------------------------------------------
// Palíndromos sacando por los dos extremos
// ENLACE: https://www.php.net/manual/es/spldoublylinkedlist.shift.php
// ENUNCIADO EJEMPLO: comprobar si una matrícula de coche o una palabra es capicúa.
// NOTA: se compara el primero con el último (shift y pop) hasta que queda 0 o 1 elemento.
// ---------------------------------------------------------------------------------------------
titulo('Palíndromos');
function esCapicua(string $texto): bool
{
    $letras = new SplDoublyLinkedList();
    foreach (mb_str_split(mb_strtolower(preg_replace('/[^\p{L}\d]/u', '', $texto))) as $c) {
        $letras->push($c);
    }
    while (count($letras) > 1) {
        if ($letras->shift() !== $letras->pop()) {      // primero contra último
            return false;
        }
    }
    return true;
}
ver('reconocer', esCapicua('reconocer'));       // true
ver('12321', esCapicua('12321'));               // true
ver('Baiona', esCapicua('Baiona'));             // false

// ---------------------------------------------------------------------------------------------
// Una lista que se mantiene ordenada
// ENLACE: https://www.php.net/manual/es/spldoublylinkedlist.add.php
// ENUNCIADO EJEMPLO: la clasificación de una carrera se actualiza al llegar cada corredor.
// NOTA: se busca la posición donde debe ir y se inserta con add (sin reordenar todo). Buscar es
//       O(n), pero la inserción en sí es inmediata.
// ---------------------------------------------------------------------------------------------
titulo('Lista ordenada');
function insertarOrdenado(SplDoublyLinkedList $lista, array $corredor): void
{
    $posicion = 0;
    foreach ($lista as $i => $otro) {
        if ($otro['tiempo'] > $corredor['tiempo']) {
            break;
        }
        $posicion = $i + 1;
    }
    $lista->add($posicion, $corredor);
}
$clasificacion = new SplDoublyLinkedList();
foreach ([['Ana', 42.5], ['Brais', 39.8], ['Carmen', 45.1], ['Diego', 41.0]] as [$nombre, $tiempo]) {
    insertarOrdenado($clasificacion, ['nombre' => $nombre, 'tiempo' => $tiempo]);
}
$podio = [];
foreach ($clasificacion as $i => $c) {
    $podio[] = ($i + 1) . 'º ' . $c['nombre'] . ' (' . $c['tiempo'] . ' min)';
}
ver('clasificación', $podio);     // ['1º Brais (39.8 min)', '2º Diego (41 min)', '3º Ana (42.5 min)', '4º Carmen (45.1 min)']

// ---------------------------------------------------------------------------------------------
// Caché LRU (Least Recently Used)
// ENLACE: https://es.wikipedia.org/wiki/Algoritmo_de_reemplazo_de_p%C3%A1ginas#LRU
// ENUNCIADO EJEMPLO: guardar en memoria las fichas de los 3 productos consultados más
//                   recientemente; al consultar uno nuevo con la caché llena, se descarta el que
//                   hace más tiempo que no se usa.
// NOTA: al principio de la lista, el usado más recientemente; al final, el más antiguo. Al usar
//       uno, se mueve al principio. (Una LRU profesional combina la lista con un array para no
//       tener que buscar: aquí buscar es O(n), pero la idea es la misma.)
// ---------------------------------------------------------------------------------------------
titulo('Caché LRU');
final class CacheLru
{
    private SplDoublyLinkedList $orden;      // claves, de la más reciente a la más antigua
    private array $datos = [];
    public array $log = [];

    public function __construct(private int $capacidad)
    {
        $this->orden = new SplDoublyLinkedList();
    }

    public function obtener(string $clave, callable $cargar): mixed
    {
        if (array_key_exists($clave, $this->datos)) {
            $this->log[] = "acierto $clave";
            $this->quitarDelOrden($clave);
        } else {
            $this->log[] = "fallo $clave";
            if (count($this->orden) === $this->capacidad) {
                $viejo = $this->orden->pop();          // el usado hace más tiempo
                unset($this->datos[$viejo]);
                $this->log[] = "  descartado $viejo";
            }
            $this->datos[$clave] = $cargar($clave);
        }
        $this->orden->unshift($clave);                 // ahora es el más reciente
        return $this->datos[$clave];
    }

    private function quitarDelOrden(string $clave): void
    {
        foreach ($this->orden as $i => $c) {
            if ($c === $clave) {
                $this->orden->offsetUnset($i);
                return;
            }
        }
    }

    public function claves(): array
    {
        return iterator_to_array($this->orden, false);
    }
}
$cache = new CacheLru(3);
$desdeBaseDeDatos = fn(string $ref) => "ficha de $ref";
foreach (['RAT', 'TEC', 'MON', 'RAT', 'CAS', 'TEC'] as $ref) {
    $cache->obtener($ref, $desdeBaseDeDatos);
}
ver('log', $cache->log);
// fallo RAT, fallo TEC, fallo MON, acierto RAT, fallo CAS (descartado TEC), fallo TEC (descartado MON)
ver('en caché (reciente -> antiguo)', $cache->claves());   // ['TEC', 'CAS', 'RAT']

// ---------------------------------------------------------------------------------------------
// Lista de reproducción circular ("repetir todo")
// ENLACE: https://www.php.net/manual/es/spldoublylinkedlist.next.php
// ENUNCIADO EJEMPLO: al terminar la última canción, vuelve a sonar la primera.
// ---------------------------------------------------------------------------------------------
titulo('Lista circular');
$playlist = new SplDoublyLinkedList();
foreach (['Pandeirada', 'Alalá', 'Xota'] as $c) {
    $playlist->push($c);
}
$suenan = [];
$playlist->rewind();
for ($i = 0; $i < 7; $i++) {
    $suenan[] = $playlist->current();
    $playlist->next();
    if (!$playlist->valid()) {
        $playlist->rewind();                       // se ha salido por el final: vuelta al principio
    }
}
ver('7 canciones seguidas', $suenan);   // ['Pandeirada', 'Alalá', 'Xota', 'Pandeirada', 'Alalá', 'Xota', 'Pandeirada']

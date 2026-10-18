<?php
/*
 * e09-POO_problema_solucion.php
 *  (FICHERO CON LAS SOLUCIONES)
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
// ---- SOLUCIÓN ----
enum DiaSemana: int
{
    case Lunes = 1;
    case Martes = 2;
    case Miercoles = 3;
    case Jueves = 4;
    case Viernes = 5;
    case Sabado = 6;
    case Domingo = 7;

    public function esFinDeSemana(): bool
    {
        return $this->value >= 6;
    }

    public function nombreGallego(): string
    {
        return ['luns', 'martes', 'mércores', 'xoves', 'venres', 'sábado', 'domingo'][$this->value - 1];
    }
}
$finde = array_filter(DiaSemana::cases(), fn(DiaSemana $d) => $d->esFinDeSemana());
ver('fin de semana', array_values(array_map(fn(DiaSemana $d) => $d->nombreGallego(), $finde)));   // ['sábado', 'domingo']

// =============================================================================================
// PROBLEMA 2. Del formulario al enum
//  El formulario envía la talla de una camiseta en $_POST['talla']. Crea el enum Talla con valor
//  string (s, m, l, xl) y escribe leerTalla(string $valor): ?Talla que devuelva null si la talla
//  no existe (sin lanzar excepciones). Pruébala con $envios.
// =============================================================================================
titulo('Problema 2');
$envios = ['m', 'XL', 'xxl', ''];
// completa aquí tu solución
// ---- SOLUCIÓN ----
enum Talla: string
{
    case S = 's';
    case M = 'm';
    case L = 'l';
    case XL = 'xl';
}
function leerTalla(string $valor): ?Talla
{
    return Talla::tryFrom(strtolower(trim($valor)));
}
foreach ($envios as $envio) {
    ver("'$envio'", leerTalla($envio));     // Talla::M, Talla::XL, NULL, NULL
}

// =============================================================================================
// PROBLEMA 3. El semáforo
//  Crea el enum Semaforo (Verde, Ambar, Rojo) con un método siguiente(): self (Verde -> Ambar ->
//  Rojo -> Verde) y un método segundos(): int (Verde 30, Ambar 5, Rojo 25) usando match. Simula
//  un ciclo completo empezando en Verde y calcula cuánto dura.
// =============================================================================================
titulo('Problema 3');
// completa aquí tu solución
// ---- SOLUCIÓN ----
enum Semaforo
{
    case Verde;
    case Ambar;
    case Rojo;

    public function siguiente(): self
    {
        return match ($this) {
            self::Verde => self::Ambar,
            self::Ambar => self::Rojo,
            self::Rojo => self::Verde,
        };
    }

    public function segundos(): int
    {
        return match ($this) {
            self::Verde => 30,
            self::Ambar => 5,
            self::Rojo => 25,
        };
    }
}
$luz = Semaforo::Verde;
$secuencia = [];
$total = 0;
do {
    $secuencia[] = $luz->name;
    $total += $luz->segundos();
    $luz = $luz->siguiente();
} while ($luz !== Semaforo::Verde);
ver('ciclo', $secuencia);       // ['Verde', 'Ambar', 'Rojo']
ver('duración', $total);        // 60

// =============================================================================================
// PROBLEMA 4. Prioridades de las incidencias
//  Crea el enum Prioridad con valor int (Baja = 1, Media = 2, Alta = 3, Critica = 4) que
//  implemente una interfaz ConColor con color(): string. Ordena $incidencias de mayor a menor
//  prioridad y muéstralas como "[CRITICA] Servidor caído (rojo)".
// =============================================================================================
titulo('Problema 4');
// completa aquí tu solución
// ---- SOLUCIÓN ----
interface ConColor
{
    public function color(): string;
}
enum Prioridad: int implements ConColor
{
    case Baja = 1;
    case Media = 2;
    case Alta = 3;
    case Critica = 4;

    public function color(): string
    {
        return match ($this) {
            self::Baja => 'gris',
            self::Media => 'azul',
            self::Alta => 'naranja',
            self::Critica => 'rojo',
        };
    }
}
$incidencias = [
    ['titulo' => 'Cambiar tóner', 'prioridad' => Prioridad::Baja],
    ['titulo' => 'Servidor caído', 'prioridad' => Prioridad::Critica],
    ['titulo' => 'Wifi lento', 'prioridad' => Prioridad::Media],
];
usort($incidencias, fn($a, $b) => $b['prioridad']->value <=> $a['prioridad']->value);
ver('ordenadas', array_map(
    fn($i) => '[' . strtoupper($i['prioridad']->name) . '] ' . $i['titulo'] . ' (' . $i['prioridad']->color() . ')',
    $incidencias
));   // ['[CRITICA] Servidor caído (rojo)', '[MEDIA] Wifi lento (azul)', '[BAJA] Cambiar tóner (gris)']

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
// ---- SOLUCIÓN ----
enum EstadoReserva: string
{
    case Pendiente = 'p';
    case Confirmada = 'c';
    case Anulada = 'a';
}
class Reserva
{
    public EstadoReserva $estado = EstadoReserva::Pendiente;

    public function cambiarEstado(EstadoReserva $estado): void     // el tipo solo admite los 3 casos
    {
        $this->estado = $estado;
    }
}
$reserva = new Reserva();
$reserva->cambiarEstado(EstadoReserva::Confirmada);
ver('estado', $reserva->estado);                                  // enum EstadoReserva::Confirmada
verError("cambiarEstado('x')", fn() => $reserva->cambiarEstado('x'));   // TypeError

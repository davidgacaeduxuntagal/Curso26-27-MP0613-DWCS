<?php
/*
 * e02-SplHeap.php
 *  MONTÍCULO PERSONALIZADO con SplHeap (2): APLICACIONES REALES
 *  Contenidos: gestor de tareas con prioridad (enum) y plazo, ranking de productos por una
 *              puntuación calculada (top K con un montículo propio), un montículo "con tipo" que
 *              solo admite objetos de una clase
 *
 *  Cómo trabajar este fichero:
 *   1. Pon un punto de ruptura DENTRO de compare() y en los bucles, y ejecuta en modo depuración.
 */
require_once __DIR__ . '/../../utilidades.php';

// ---------------------------------------------------------------------------------------------
// Gestor de tareas: prioridad (enum) y plazo
// ENLACE: https://www.php.net/manual/es/class.splheap.php
// ENUNCIADO EJEMPLO: el equipo de desarrollo trabaja primero en las tareas más prioritarias; a
//                   igual prioridad, en la que vence antes; y a igualdad de todo, en la más corta.
// NOTA: el criterio con varios niveles se escribe comparando arrays con <=>. Fíjate en el orden
//       de $a y $b en cada posición: según queramos el mayor o el menor primero.
// ---------------------------------------------------------------------------------------------
titulo('Gestor de tareas');
enum Prioridad: int
{
    case Baja = 1;
    case Media = 2;
    case Alta = 3;
}
final class Tarea
{
    public function __construct(
        public readonly string $titulo,
        public readonly Prioridad $prioridad,
        public readonly DateTimeImmutable $plazo,
        public readonly int $horas,
    ) {
    }
}
final class ColaTareas extends SplHeap
{
    protected function compare(mixed $a, mixed $b): int
    {
        return [$a->prioridad->value, $b->plazo, $b->horas]      // prioridad MAYOR, plazo MENOR, horas MENOS
           <=> [$b->prioridad->value, $a->plazo, $a->horas];
    }
}
$tareas = new ColaTareas();
$tareas->insert(new Tarea('Actualizar dependencias', Prioridad::Media, new DateTimeImmutable('2026-10-30'), 2));
$tareas->insert(new Tarea('Arreglar el pago con Bizum', Prioridad::Alta, new DateTimeImmutable('2026-10-17'), 5));
$tareas->insert(new Tarea('Nuevo diseño de la portada', Prioridad::Baja, new DateTimeImmutable('2026-11-15'), 20));
$tareas->insert(new Tarea('Error en el carrito', Prioridad::Alta, new DateTimeImmutable('2026-10-17'), 3));
$tareas->insert(new Tarea('Copia de seguridad', Prioridad::Alta, new DateTimeImmutable('2026-10-20'), 1));
$plan = [];
while (!$tareas->isEmpty()) {
    $t = $tareas->extract();
    $plan[] = "[{$t->prioridad->name}] {$t->titulo} (vence {$t->plazo->format('d/m')}, {$t->horas} h)";
}
ver('plan de trabajo', $plan);
// Alta: Error en el carrito (17/10, 3 h), Arreglar el pago (17/10, 5 h), Copia de seguridad (20/10);
// Media: Actualizar dependencias; Baja: Nuevo diseño

// ---------------------------------------------------------------------------------------------
// Ranking por una puntuación calculada (top K)
// ENLACE: https://www.php.net/manual/es/function.log.php
// ENUNCIADO EJEMPLO: la tienda muestra los 3 "productos mejor valorados", pero un producto con
//                   un solo voto de 5 estrellas no debe ganar a uno con 4,7 y 900 votos.
// NOTA: puntuación = estrellas * log10(votos + 1). El montículo guarda los K mejores y es un
//       montículo "mínimo por puntuación" (su raíz es el peor de los K), como en SplMinHeap.
// ---------------------------------------------------------------------------------------------
titulo('Ranking ponderado');
function puntuacion(array $p): float
{
    return round($p['estrellas'] * log10($p['votos'] + 1), 2);
}
final class PeorPrimero extends SplHeap
{
    protected function compare(mixed $a, mixed $b): int
    {
        return puntuacion($b) <=> puntuacion($a);     // sale antes el de MENOR puntuación
    }
}
$catalogo = [
    ['nombre' => 'Ratón inalámbrico', 'estrellas' => 4.7, 'votos' => 900],
    ['nombre' => 'Cable USB-C', 'estrellas' => 5.0, 'votos' => 1],
    ['nombre' => 'Teclado mecánico', 'estrellas' => 4.8, 'votos' => 350],
    ['nombre' => 'Webcam HD', 'estrellas' => 4.2, 'votos' => 1200],
    ['nombre' => 'Funda portátil', 'estrellas' => 4.9, 'votos' => 12],
];
$top = new PeorPrimero();
foreach ($catalogo as $p) {
    $top->insert($p);
    if (count($top) > 3) {
        $top->extract();                              // fuera el peor de los 4
    }
}
$mejores = array_reverse(iterator_to_array($top, false));
ver('top 3', array_map(fn($p) => $p['nombre'] . ' (' . puntuacion($p) . ')', $mejores));
// ['Ratón inalámbrico (13.89)', 'Webcam HD (12.93)', 'Teclado mecánico (12.22)']; el cable (1,51) no entra

// ---------------------------------------------------------------------------------------------
// Un montículo que solo admite un tipo de objeto
// ENLACE: https://www.php.net/manual/es/splheap.insert.php
// ENUNCIADO EJEMPLO: evitar que alguien meta por error un array o un string en la cola de
//                   tareas (y la deje "corrupta" al fallar compare()).
// NOTA: se redefine insert() para comprobar el tipo ANTES de que llegue a compare(). Así el
//       error es claro y el montículo no se corrompe.
// ---------------------------------------------------------------------------------------------
titulo('Montículo con tipo');
final class ColaTareasSegura extends SplHeap
{
    public function insert(mixed $valor): true
    {
        if (!$valor instanceof Tarea) {
            throw new TypeError('ColaTareasSegura solo admite objetos Tarea, no ' . get_debug_type($valor));
        }
        return parent::insert($valor);
    }

    protected function compare(mixed $a, mixed $b): int
    {
        return $a->prioridad->value <=> $b->prioridad->value;
    }
}
$segura = new ColaTareasSegura();
$segura->insert(new Tarea('Revisar logs', Prioridad::Baja, new DateTimeImmutable('2026-10-25'), 1));
verError('insertar un string', fn() => $segura->insert('hacer algo'));   // TypeError: ... no string
ver('¿corrupto?', $segura->isCorrupted());                               // false
ver('tareas', count($segura));                                           // 1

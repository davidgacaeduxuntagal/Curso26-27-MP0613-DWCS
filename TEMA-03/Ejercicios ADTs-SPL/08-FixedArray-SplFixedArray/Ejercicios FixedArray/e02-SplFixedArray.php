<?php
/*
 * e02-SplFixedArray.php
 *  ARRAY DE TAMAÑO FIJO con SplFixedArray (2): APLICACIONES REALES
 *  Contenidos: un tablero de tres en raya (SplFixedArray de SplFixedArray), la ocupación de las
 *              butacas de un cine, un histograma de notas (casillas 0..10), un buffer circular
 *              con las últimas N lecturas de un sensor
 *
 *  Cómo trabajar este fichero:
 *   1. Pon un punto de ruptura DENTRO de los métodos y ejecuta en modo depuración.
 *   2. Fíjate en que, como el tamaño es fijo, cualquier índice fuera del tablero da un error en
 *      lugar de crear una casilla nueva en silencio.
 */
require_once __DIR__ . '/../../utilidades.php';

// ---------------------------------------------------------------------------------------------
// Tablero de tres en raya: un SplFixedArray de SplFixedArray
// ENLACE: https://www.php.net/manual/es/class.splfixedarray.php
// ENUNCIADO EJEMPLO: guardar las jugadas y detectar si alguien ha hecho tres en raya.
// NOTA: un tablero 3x3 es un SplFixedArray de 3 filas, y cada fila otro SplFixedArray de 3.
//       $tablero[$fila][$columna] funciona como con arrays, pero sin poder salirse del tablero.
// ---------------------------------------------------------------------------------------------
titulo('Tres en raya');
final class TresEnRaya
{
    private SplFixedArray $casillas;

    public function __construct()
    {
        $this->casillas = new SplFixedArray(3);
        for ($f = 0; $f < 3; $f++) {
            $this->casillas[$f] = new SplFixedArray(3);
        }
    }

    public function jugar(int $fila, int $columna, string $ficha): void
    {
        if ($this->casillas[$fila][$columna] !== null) {     // fuera del tablero: RuntimeException
            throw new LogicException("La casilla ($fila, $columna) ya está ocupada");
        }
        $this->casillas[$fila][$columna] = $ficha;
    }

    public function ganador(): ?string
    {
        $lineas = [];
        for ($i = 0; $i < 3; $i++) {
            $lineas[] = [[$i, 0], [$i, 1], [$i, 2]];          // filas
            $lineas[] = [[0, $i], [1, $i], [2, $i]];          // columnas
        }
        $lineas[] = [[0, 0], [1, 1], [2, 2]];                 // diagonales
        $lineas[] = [[0, 2], [1, 1], [2, 0]];
        foreach ($lineas as [[$f1, $c1], [$f2, $c2], [$f3, $c3]]) {
            $a = $this->casillas[$f1][$c1];
            if ($a !== null && $a === $this->casillas[$f2][$c2] && $a === $this->casillas[$f3][$c3]) {
                return $a;
            }
        }
        return null;
    }

    public function dibujo(): string
    {
        $filas = [];
        foreach ($this->casillas as $fila) {
            $filas[] = implode('|', array_map(fn($c) => $c ?? ' ', $fila->toArray()));
        }
        return implode("\n-+-+-\n", $filas);
    }
}
$partida = new TresEnRaya();
foreach ([[1, 1, 'X'], [0, 0, 'O'], [0, 2, 'X'], [2, 0, 'O'], [1, 0, 'X'], [1, 2, 'X']] as [$f, $c, $ficha]) {
    $partida->jugar($f, $c, $ficha);
}
echo $partida->dibujo(), nl();
ver('ganador', $partida->ganador());                       // "X" (fila del medio)
verError('casilla ocupada', fn() => $partida->jugar(1, 1, 'O'));   // LogicException
verError('fuera del tablero', fn() => $partida->jugar(3, 0, 'O')); // RuntimeException: Index invalid or out of range

// ---------------------------------------------------------------------------------------------
// Butacas de un cine
// ENLACE: https://www.php.net/manual/es/splfixedarray.toarray.php
// ENUNCIADO EJEMPLO: la sala 2 tiene 8 filas de 12 butacas. Vender entradas, contar las libres
//                   y buscar dos butacas juntas en una fila.
// NOTA: con un SplFixedArray de bool el tamaño de la sala no puede cambiar por error.
// ---------------------------------------------------------------------------------------------
titulo('Butacas de un cine');
final class Sala
{
    private SplFixedArray $filas;

    public function __construct(int $filas, int $butacas)
    {
        $this->filas = new SplFixedArray($filas);
        for ($f = 0; $f < $filas; $f++) {
            $this->filas[$f] = SplFixedArray::fromArray(array_fill(0, $butacas, false));   // false = libre
        }
    }

    public function vender(int $fila, int $butaca): bool
    {
        if ($this->filas[$fila - 1][$butaca - 1]) {      // las personas cuentan desde 1
            return false;
        }
        $this->filas[$fila - 1][$butaca - 1] = true;
        return true;
    }

    public function libres(): int
    {
        $libres = 0;
        foreach ($this->filas as $fila) {
            $libres += count(array_filter($fila->toArray(), fn($ocupada) => !$ocupada));
        }
        return $libres;
    }

    public function dosJuntas(int $fila): ?array
    {
        $butacas = $this->filas[$fila - 1];
        for ($b = 0; $b < $butacas->getSize() - 1; $b++) {
            if (!$butacas[$b] && !$butacas[$b + 1]) {
                return [$b + 1, $b + 2];
            }
        }
        return null;
    }
}
$sala2 = new Sala(8, 12);
foreach ([[5, 1], [5, 2], [5, 3], [5, 5], [5, 7]] as [$f, $b]) {
    $sala2->vender($f, $b);
}
ver('libres', $sala2->libres());              // 91 (96 - 5)
ver('vender otra vez la 5-1', $sala2->vender(5, 1));   // false
ver('dos juntas en la fila 5', $sala2->dosJuntas(5));  // [8, 9]

// ---------------------------------------------------------------------------------------------
// Histograma de notas
// ENLACE: https://www.php.net/manual/es/splfixedarray.construct.php
// ENUNCIADO EJEMPLO: cuántos alumnos han sacado cada nota entera de 0 a 10.
// NOTA: un contador por casilla: el índice ES la nota. 11 casillas, ni una más.
//       ¡CUIDADO! $histograma[$i]++ (o +=) NO funciona con SplFixedArray: da el aviso "Indirect
//       modification of overloaded element of SplFixedArray has no effect" y no suma nada. Hay
//       que leer y escribir por separado: $h[$i] = $h[$i] + 1.
// ---------------------------------------------------------------------------------------------
titulo('Histograma');
$notas = [5.5, 7.25, 3.0, 9.5, 5.0, 6.75, 7.0, 4.5, 8.0, 10.0, 5.25, 6.0];
$histograma = SplFixedArray::fromArray(array_fill(0, 11, 0));
verError('$histograma[5]++', function () use ($histograma) {
    $histograma[5]++;
});   // ErrorException: Indirect modification of overloaded element of SplFixedArray has no effect
foreach ($notas as $nota) {
    $i = (int) floor($nota);
    $histograma[$i] = $histograma[$i] + 1;
}
foreach ($histograma as $nota => $cuantos) {
    if ($cuantos > 0) {
        echo sprintf('  %2d | %s %d', $nota, str_repeat('#', $cuantos), $cuantos), nl();
    }
}
// 3 | # / 4 | # / 5 | ### / 6 | ## / 7 | ## / 8 | # / 9 | # / 10 | #

// ---------------------------------------------------------------------------------------------
// Buffer circular: las últimas N lecturas
// ENLACE: https://es.wikipedia.org/wiki/Buffer_circular
// ENUNCIADO EJEMPLO: un sensor de calidad del aire manda una lectura por minuto; solo se guardan
//                   las últimas 5 para calcular la media.
// NOTA: con un array de tamaño fijo y una posición que da la vuelta (% N), la nueva lectura
//       sobrescribe la más antigua. No se mueve ningún elemento: escribir siempre es O(1).
// ---------------------------------------------------------------------------------------------
titulo('Buffer circular');
final class BufferCircular
{
    private SplFixedArray $datos;
    private int $siguiente = 0;
    private int $guardados = 0;

    public function __construct(int $capacidad)
    {
        $this->datos = new SplFixedArray($capacidad);
    }

    public function anadir(float $valor): void
    {
        $this->datos[$this->siguiente] = $valor;
        $this->siguiente = ($this->siguiente + 1) % $this->datos->getSize();   // da la vuelta
        $this->guardados = min($this->guardados + 1, $this->datos->getSize());
    }

    public function media(): float
    {
        return round(array_sum(array_slice($this->datos->toArray(), 0, $this->guardados)) / $this->guardados, 1);
    }

    public function enOrden(): array                  // de la más antigua a la más reciente
    {
        $n = $this->datos->getSize();
        $inicio = $this->guardados < $n ? 0 : $this->siguiente;
        $resultado = [];
        for ($i = 0; $i < $this->guardados; $i++) {
            $resultado[] = $this->datos[($inicio + $i) % $n];
        }
        return $resultado;
    }
}
$aire = new BufferCircular(5);
foreach ([42, 45, 51, 48, 60, 72, 68] as $lectura) {
    $aire->anadir($lectura);                         // pon aquí un punto de ruptura
}
ver('últimas 5', $aire->enOrden());                  // [51.0, 48.0, 60.0, 72.0, 68.0]
ver('media', $aire->media());                        // 59.8

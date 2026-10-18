<?php
/*
 * Cronometro.php
 *  Clase para medir cuánto tarda un trozo de código, con "vueltas" para saber cuánto tarda cada parte.
 *  Usa hrtime(true): un reloj MONOTÓNICO en nanosegundos (no le afectan los cambios de hora del sistema).
 *
 *  $crono = (new Cronometro())->iniciar();
 *  ... leer datos ...        $crono->vuelta('leer');
 *  ... calcular ...          $crono->vuelta('calcular');
 *  echo $crono->informe();   // leer: 12,40 ms · calcular: 3,10 ms · total: 15,50 ms
 */

final class Cronometro
{
    private ?int $inicio = null;        // hrtime(true) al iniciar
    private int $ultimaMarca = 0;       // hrtime(true) de la última vuelta
    private ?int $fin = null;           // hrtime(true) al parar
    /** @var array<string, int> nombre de la vuelta => nanosegundos que ha durado */
    private array $vueltas = [];

    /** Pone el cronómetro a cero y empieza a contar. Devuelve el propio objeto para encadenar. */
    public function iniciar(): static
    {
        $this->vueltas = [];
        $this->fin = null;
        $this->inicio = $this->ultimaMarca = hrtime(true);
        return $this;
    }

    /** Cierra una vuelta: guarda lo que ha pasado desde la vuelta anterior y lo devuelve en milisegundos. */
    public function vuelta(string $nombre): float
    {
        $this->comprobarIniciado();
        $ahora = hrtime(true);
        $duracion = $ahora - $this->ultimaMarca;
        $this->vueltas[$nombre] = ($this->vueltas[$nombre] ?? 0) + $duracion;   // mismo nombre otra vez: se suma
        $this->ultimaMarca = $ahora;
        return $duracion / 1e6;
    }

    /** Para el cronómetro y devuelve el tiempo total en milisegundos. */
    public function parar(): float
    {
        $this->comprobarIniciado();
        $this->fin = hrtime(true);
        return $this->totalMs();
    }

    /** Milisegundos desde que se inició (hasta ahora o hasta que se paró). */
    public function totalMs(): float
    {
        $this->comprobarIniciado();
        return (($this->fin ?? hrtime(true)) - $this->inicio) / 1e6;
    }

    /** @return array<string, float> nombre de la vuelta => milisegundos */
    public function vueltas(): array
    {
        return array_map(fn(int $ns) => $ns / 1e6, $this->vueltas);
    }

    /** Texto con cada vuelta y el total: "leer: 12,40 ms · calcular: 3,10 ms · total: 15,50 ms" */
    public function informe(): string
    {
        $partes = [];
        foreach ($this->vueltas() as $nombre => $ms) {
            $partes[] = "$nombre: " . self::formatear($ms);
        }
        $partes[] = 'total: ' . self::formatear($this->totalMs());
        return implode(' · ', $partes);
    }

    /**
     * Ejecuta $funcion con sus argumentos y devuelve [resultado, milisegundos].
     *  [$total, $ms] = Cronometro::medir('array_sum', $numeros);
     */
    public static function medir(callable $funcion, mixed ...$argumentos): array
    {
        $inicio = hrtime(true);
        $resultado = $funcion(...$argumentos);
        return [$resultado, (hrtime(true) - $inicio) / 1e6];
    }

    /** Milisegundos en la unidad más cómoda de leer: "850 µs", "12,40 ms", "1,25 s". */
    public static function formatear(float $ms): string
    {
        return match (true) {
            $ms < 1 => number_format($ms * 1000, 0, ',', '.') . ' µs',
            $ms < 1000 => number_format($ms, 2, ',', '.') . ' ms',
            default => number_format($ms / 1000, 2, ',', '.') . ' s',
        };
    }

    private function comprobarIniciado(): void
    {
        if ($this->inicio === null) {
            throw new LogicException('Primero hay que llamar a iniciar()');
        }
    }
}

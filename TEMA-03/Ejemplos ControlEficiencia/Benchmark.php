<?php
/*
 * Benchmark.php
 *  Clase para hacer MICROBENCHMARKS: medir bien cuánto tarda una función muy rápida y comparar varias formas de
 *  hacer lo mismo.
 *
 *  Por qué no basta con medir una vez:
 *   - una llamada que tarda menos de un microsegundo está por debajo de lo que el reloj distingue: se mide un
 *     LOTE de muchas llamadas y se divide;
 *   - las primeras ejecuciones son más lentas (cachés frías): se hace un CALENTAMIENTO que no se cuenta;
 *   - el sistema operativo interrumpe de vez en cuando (otros programas, el antivirus...): se toman varias
 *     MUESTRAS y se usa la MEDIANA, que no se deja arrastrar por una muestra anormalmente lenta.
 */

final class Benchmark
{
    /**
     * Mide $funcion: $muestras lotes de $porLote llamadas cada uno.
     * @return array{porLlamada: float, mediana: float, minimo: float, maximo: float, muestras: int, porLote: int}
     *         tiempos POR LLAMADA en microsegundos (µs)
     */
    public static function medir(callable $funcion, int $porLote = 1000, int $muestras = 7): array
    {
        for ($i = 0; $i < min(100, $porLote); $i++) {      // calentamiento: no se cuenta
            $funcion();
        }
        $tiempos = [];
        for ($m = 0; $m < $muestras; $m++) {
            $inicio = hrtime(true);
            for ($i = 0; $i < $porLote; $i++) {
                $funcion();
            }
            $tiempos[] = (hrtime(true) - $inicio) / 1e3 / $porLote;   // ns -> µs, y por llamada
        }
        sort($tiempos);
        return [
            'porLlamada' => array_sum($tiempos) / $muestras,         // media
            'mediana' => $tiempos[intdiv($muestras, 2)],             // con $muestras impar, el del medio
            'minimo' => $tiempos[0],
            'maximo' => $tiempos[$muestras - 1],
            'muestras' => $muestras,
            'porLote' => $porLote,
        ];
    }

    /**
     * Mide varias funciones con las mismas condiciones y las ordena de la más rápida a la más lenta.
     * @param array<string, callable> $candidatas nombre => función
     * @return array<string, array> nombre => resultado de medir() + 'veces' (cuántas veces más lenta que la mejor)
     */
    public static function comparar(array $candidatas, int $porLote = 1000, int $muestras = 7): array
    {
        $resultados = [];
        foreach ($candidatas as $nombre => $funcion) {
            $resultados[$nombre] = self::medir($funcion, $porLote, $muestras);
        }
        uasort($resultados, fn($a, $b) => $a['mediana'] <=> $b['mediana']);
        $mejor = reset($resultados)['mediana'];
        foreach ($resultados as &$r) {
            $r['veces'] = $mejor > 0 ? $r['mediana'] / $mejor : 1.0;
        }
        unset($r);                                                // la referencia del foreach, fuera
        return $resultados;
    }

    /** Tabla de texto con el resultado de comparar(). */
    public static function tabla(array $resultados): string
    {
        $ancho = max(array_map('mb_strlen', array_keys($resultados)));
        $lineas = [];
        foreach ($resultados as $nombre => $r) {
            $lineas[] = sprintf('  %s  %10s µs/llamada  x%s',
                str_pad($nombre, $ancho + (strlen($nombre) - mb_strlen($nombre))),   // str_pad cuenta bytes
                number_format($r['mediana'], 3, ',', '.'),
                number_format($r['veces'], 1, ',', '.'));
        }
        return implode(PHP_EOL, $lineas);
    }
}

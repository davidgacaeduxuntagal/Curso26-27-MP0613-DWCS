<?php
/*
 * e05-EFICIENCIA.php
 *  MEDIR UNA PÁGINA ENTERA: $_SERVER['REQUEST_TIME_FLOAT'], "Página generada en X ms", la cabecera
 *  Server-Timing (se ve en las herramientas del navegador), tiempo de CPU frente a tiempo real (getrusage) y
 *  registro de las peticiones lentas con register_shutdown_function y error_log
 *  (apartado 6 de los apuntes)
 *
 *  Este fichero es una PÁGINA: se entiende mejor en el navegador. Desde esta carpeta:
 *      php -d xdebug.mode=off -S localhost:8000
 *  y abre http://localhost:8000/e05-EFICIENCIA.php. Pulsa F12 → pestaña Red (Network) → la petición →
 *  Tiempos (Timing): abajo aparecen las medidas que envía la cabecera Server-Timing.
 *  También funciona en la terminal (allí no hay cabeceras: se muestra el texto que se enviaría).
 *
 *  Cómo trabajar este fichero:
 *   1. Pon un punto de ruptura en la primera línea y avanza con F10 (los tiempos saldrán mayores: es normal).
 *   2. Recarga varias veces la página y mira cómo cambian los números y el fichero datos/lentas.log.
 */
require_once __DIR__ . '/utilidades.php';
require_once __DIR__ . '/Cronometro.php';

const UMBRAL_LENTA_MS = 50;           // por encima de esto, la petición se apunta en datos/lentas.log

/** Segundos de CPU que ha gastado el proceso hasta ahora (usuario + sistema). */
function segundosCpu(): float
{
    $uso = getrusage();
    return $uso['ru_utime.tv_sec'] + $uso['ru_utime.tv_usec'] / 1e6
        + $uso['ru_stime.tv_sec'] + $uso['ru_stime.tv_usec'] / 1e6;
}

// ---------------------------------------------------------------------------------------------
// register_shutdown_function — Medir y registrar al TERMINAR la petición
// ENLACE: https://www.php.net/manual/es/function.register-shutdown-function.php
// ENLACE: https://www.php.net/manual/es/function.error-log.php
// ENUNCIADO EJEMPLO: en producción no se ven los tiempos, pero se quiere saber qué páginas van lentas.
// NOTA: la función registrada se ejecuta al final de todo (incluso si el script termina con exit o con un error
//       fatal). Mide desde REQUEST_TIME_FLOAT, así que cuenta la petición completa. error_log($texto, 3, $fichero)
//       añade una línea a ese fichero. En un servidor real se apuntarían solo las lentas para no llenar el disco.
// ---------------------------------------------------------------------------------------------
register_shutdown_function(function (): void {
    $ms = (microtime(true) - $_SERVER['REQUEST_TIME_FLOAT']) * 1000;
    if ($ms > UMBRAL_LENTA_MS) {
        $pagina = $_SERVER['REQUEST_URI'] ?? $_SERVER['SCRIPT_NAME'];
        $linea = sprintf("%s %s %.1f ms %s\n", date('Y-m-d H:i:s'), $pagina, $ms,
            number_format(memory_get_peak_usage() / 1024 ** 2, 1) . ' MB');
        error_log($linea, 3, rutaDatos('lentas.log'));
    }
});

// ---------------------------------------------------------------------------------------------
// Medir las partes de la página — Un Cronometro con una vuelta por parte
// ENLACE: https://www.php.net/manual/es/function.hrtime.php
// ENUNCIADO EJEMPLO: la página de inicio de la tienda consulta los productos, calcula las ofertas y pinta el HTML.
// NOTA: aquí la "consulta" se simula con usleep(30 ms): ESPERAR a la base de datos o a una API no gasta CPU,
//       pero el usuario espera igual. El cálculo sí gasta CPU. Por eso se miden las dos cosas (ver abajo).
// ---------------------------------------------------------------------------------------------
$crono = (new Cronometro())->iniciar();
$cpuInicio = segundosCpu();

usleep(30_000);                                               // 1) "consulta" lenta: se espera, no se calcula
$productos = array_map(fn($i) => ['id' => $i, 'precio' => 10 + $i % 90], range(1, 2000));
$crono->vuelta('consulta');

$ofertas = 0;                                                 // 2) cálculo que sí gasta CPU
for ($vuelta = 0; $vuelta < 300; $vuelta++) {
    foreach ($productos as $p) {
        if ($p['precio'] * 0.8 < 20) {
            $ofertas++;
        }
    }
}
$crono->vuelta('calculo');

$filas = '';                                                  // 3) pintar la tabla
foreach (array_slice($productos, 0, 5) as $p) {
    $filas .= "<tr><td>{$p['id']}</td><td>{$p['precio']} €</td></tr>";
}
$crono->vuelta('html');
$cpuMs = (segundosCpu() - $cpuInicio) * 1000;

// ---------------------------------------------------------------------------------------------
// Server-Timing — Enviar las medidas al navegador
// ENLACE: https://developer.mozilla.org/es/docs/Web/HTTP/Headers/Server-Timing
// ENLACE: https://www.php.net/manual/es/function.header.php
// ENUNCIADO EJEMPLO: ver en las herramientas del navegador cuánto tardó cada parte en el servidor.
// NOTA: el formato es nombre;dur=milisegundos;desc="texto", separados por comas. Como cualquier header(), hay que
//       enviarlo ANTES de escribir nada en la página. ¡Cuidado en producción!: da pistas a un atacante; se suele
//       enviar solo a los administradores o en el entorno de desarrollo.
// ---------------------------------------------------------------------------------------------
$medidas = [];
foreach ($crono->vueltas() as $nombre => $ms) {
    $medidas[] = sprintf('%s;dur=%.1f', $nombre, $ms);
}
$medidas[] = sprintf('cpu;dur=%.1f;desc="CPU"', $cpuMs);
$serverTiming = 'Server-Timing: ' . implode(', ', $medidas);
header($serverTiming);                                        // en la terminal no hace nada (no hay cabeceras)

// ---------------------------------------------------------------------------------------------
// Tiempo real frente a tiempo de CPU — getrusage
// ENLACE: https://www.php.net/manual/es/function.getrusage.php
// ENUNCIADO EJEMPLO: la página tarda 40 ms; ¿es que el código es lento o que espera a la base de datos?
// NOTA: el tiempo real ("de reloj de pared") incluye las esperas; el tiempo de CPU, solo lo que el procesador
//       ha trabajado para este script. Si el real es mucho mayor que el de CPU, el problema no es el código
//       PHP sino algo a lo que espera (consultas, ficheros, red). En Windows el tiempo de CPU va a saltos de
//       unos 15,6 ms, así que solo sirve para trozos que tarden bastante más que eso.
// ---------------------------------------------------------------------------------------------
$totalMs = (microtime(true) - $_SERVER['REQUEST_TIME_FLOAT']) * 1000;

if (PHP_SAPI !== 'cli'): ?>
<!DOCTYPE html>
<html lang="es">
<head><meta charset="UTF-8"><title>Tienda · medir una página</title></head>
<body style="font-family: Arial, sans-serif">
    <h1>Productos</h1>
    <p><?= $ofertas / 300 ?> productos en oferta. Los primeros:</p>
    <table border="1" cellpadding="4"><?= $filas ?></table>
    <h2>Medidas</h2>
    <pre><?= htmlspecialchars($crono->informe()) ?>

CPU: <?= Cronometro::formatear($cpuMs) ?>  (el resto del tiempo, esperando)
<?= htmlspecialchars($serverTiming) ?></pre>
    <footer><small>Página generada en <?= number_format($totalMs, 1, ',', '.') ?> ms
        con <?= number_format(memory_get_peak_usage() / 1024 ** 2, 1, ',', '.') ?> MB de memoria (pico)</small></footer>
</body>
</html>
<?php else:
    titulo('Partes de la página');
    ver('ofertas', $ofertas / 300);                           // 344 (siempre el mismo)
    ver('vueltas', $crono->informe());                       // "consulta: 31,18 ms · calculo: 21,04 ms · html: 24 µs · total: 52,35 ms" (aprox.)
    titulo('Tiempo real frente a CPU');
    ver('real (ms)', round($crono->totalMs(), 1));            // 52.4 (aprox.)
    ver('CPU (ms)', round($cpuMs, 1));                        // 15.6 o 31.3: en Windows va a saltos de 15,6 ms; no cuenta los 30 ms de espera
    titulo('Server-Timing');
    ver('cabecera', $serverTiming);                           // "Server-Timing: consulta;dur=31.2, calculo;dur=21.0, html;dur=0.0, cpu;dur=31.2;desc="CPU"" (aprox.)
    ver('desde REQUEST_TIME_FLOAT (ms)', round($totalMs, 1)); // 53.4 (aprox.: algo más que el cronómetro, porque cuenta también los require)
endif;

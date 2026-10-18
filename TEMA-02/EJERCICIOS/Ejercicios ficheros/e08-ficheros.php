<?php
/*
 * e08-ficheros.php
 *  FICHEROS (8): acceso concurrente (varios usuarios a la vez) y seguridad
 *  Funciones: flock (LOCK_SH, LOCK_EX, LOCK_UN, LOCK_NB), file_put_contents con LOCK_EX,
 *             escritura "atómica" (fichero temporal + rename), defensa contra el recorrido de
 *             directorios (basename + realpath)
 *
 *  En un servidor web se ejecutan MUCHOS scripts a la vez. Si dos escriben el mismo fichero al
 *  mismo tiempo, los datos se mezclan o se pierden. Ejecuta en modo depuración (F10).
 */
require_once __DIR__ . '/utilidades.php';

// ---------------------------------------------------------------------------------------------
// flock — bloquea un fichero abierto
//   LOCK_SH compartido (para leer)   LOCK_EX exclusivo (para escribir)   LOCK_UN liberar
// ENLACE: https://www.php.net/manual/es/function.flock.php
// ENUNCIADO EJEMPLO: contador de visitas de la web del Bar Baio que no pierde visitas aunque dos
//   personas entren a la vez: leer, sumar 1 y guardar se hace con el fichero bloqueado.
// NOTA: modo "c+": lo crea si no existe y NO lo vacía al abrirlo (con "w" lo vaciaríamos ANTES
//   de tener el bloqueo). Los bloqueos son "cooperativos": todos los scripts deben usar flock.
// ---------------------------------------------------------------------------------------------
titulo('flock: contador de visitas');
$contador      = rutaDatos('visitas.txt');
for ($visita = 1; $visita <= 3; $visita++) {        // simulamos 3 visitas seguidas
    $fp = fopen($contador, 'c+');
    if (flock($fp, LOCK_EX)) {                       // espera hasta conseguir el bloqueo
        $actual = (int) stream_get_contents($fp);    // lee el número actual (vacío -> 0)
        ftruncate($fp, 0);                           // vaciamos
        rewind($fp);
        fwrite($fp, (string) ($actual + 1));
        fflush($fp);                                 // nos aseguramos de que se escribe antes de liberar
        flock($fp, LOCK_UN);
    }
    fclose($fp);
}
ver('visitas', file_get_contents($contador));

// ---------------------------------------------------------------------------------------------
// LOCK_NB — no esperar: si el fichero está bloqueado por otro, flock devuelve false al momento
// ENUNCIADO EJEMPLO: una tarea programada (copia de seguridad) que no debe ejecutarse dos veces a
//   la vez: si ya hay otra en marcha, se avisa y se termina.
// NOTA: aquí simulamos "otro proceso" abriendo el mismo fichero dos veces en este script.
// ---------------------------------------------------------------------------------------------
titulo('flock con LOCK_NB');
$cerrojo       = rutaDatos('copia.lock');
$proceso1      = fopen($cerrojo, 'c');
$bloqueo1      = flock($proceso1, LOCK_EX | LOCK_NB);    // true: el primero lo consigue
$proceso2      = fopen($cerrojo, 'c');
$bloqueo2      = flock($proceso2, LOCK_EX | LOCK_NB);    // false: ya está bloqueado
ver('¿el primer proceso tiene el bloqueo?', $bloqueo1);
ver('¿el segundo proceso lo consigue?', $bloqueo2);
flock($proceso1, LOCK_UN);
fclose($proceso1);
fclose($proceso2);

// ---------------------------------------------------------------------------------------------
// file_put_contents con LOCK_EX — bloqueo exclusivo mientras escribe, en una sola línea
// ENLACE: https://www.php.net/manual/es/function.file-put-contents.php
// ENUNCIADO EJEMPLO: añadir opiniones al libro de visitas sin que dos usuarios mezclen sus líneas.
// ---------------------------------------------------------------------------------------------
titulo('file_put_contents con LOCK_EX');
$libro         = rutaDatos('libro_visitas.txt');
@unlink($libro);
foreach (['Ana: genial', 'Brais: volveré', 'Carmen: buen ambiente'] as $opinion) {
    file_put_contents($libro, date('H:i:s') . " $opinion\n", FILE_APPEND | LOCK_EX);
}
ver('libro de visitas', file($libro, FILE_IGNORE_NEW_LINES));

// ---------------------------------------------------------------------------------------------
// ESCRITURA ATÓMICA — escribir en un temporal y después rename()
// ENLACE: https://www.php.net/manual/es/function.rename.php
// ENUNCIADO EJEMPLO: guardar el catálogo de productos de forma que nunca exista a medias: si el
//   script falla mientras escribe, el catálogo antiguo sigue intacto, y quien lo lea ve el
//   antiguo o el nuevo, pero nunca uno a medio escribir.
// NOTA: el temporal debe estar en la MISMA carpeta (en el mismo disco) para que rename sea atómico.
// ---------------------------------------------------------------------------------------------
titulo('escritura atómica');
$catalogo      = rutaDatos('catalogo.json');
$productos     = [['codigo' => 'CAF01', 'nombre' => 'Café', 'precio' => 1.5], ['codigo' => 'TOR01', 'nombre' => 'Tortilla', 'precio' => 3.2]];
$temporal      = $catalogo . '.' . uniqid() . '.tmp';
file_put_contents($temporal, json_encode($productos, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE), LOCK_EX);
rename($temporal, $catalogo);                        // el cambio se produce de golpe
ver('catálogo', json_decode(file_get_contents($catalogo), true));
ver('¿queda el temporal?', file_exists($temporal));  // false

// ---------------------------------------------------------------------------------------------
// SEGURIDAD: recorrido de directorios (path traversal) — basename + realpath
// ENLACE: https://www.php.net/manual/es/function.realpath.php
// ENUNCIADO EJEMPLO: una página muestra el fichero que se pide en la URL (ver.php?fichero=...). Un
//   atacante pide "../utilidades.php" o "../../../Windows/win.ini" para leer otros ficheros.
//   La función ficheroPermitido() solo devuelve rutas DENTRO de la carpeta "publicos".
// NOTA: basename() quita cualquier carpeta del nombre; realpath() resuelve la ruta final y
//   comprobamos que empieza por la carpeta permitida. La mejor opción, si se puede, es una LISTA
//   BLANCA de nombres permitidos.
// ---------------------------------------------------------------------------------------------
titulo('path traversal');
$publicos      = rutaDatos('publicos');
if (!is_dir($publicos)) {
    mkdir($publicos);
}
file_put_contents($publicos . '/carta.txt', "Café 1,50 € · Tortilla 3,20 €\n");

function ficheroPermitido(string $pedido, string $carpeta): ?string
{
    $carpeta = realpath($carpeta);
    $ruta    = realpath($carpeta . DIRECTORY_SEPARATOR . basename($pedido));
    if ($ruta === false || !str_starts_with($ruta, $carpeta . DIRECTORY_SEPARATOR) || !is_file($ruta)) {
        return null;
    }
    return $ruta;
}

foreach (['carta.txt', '../utilidades.php', '../../e01-ficheros.php', 'no_existe.txt'] as $pedido) {
    $ruta = ficheroPermitido($pedido, $publicos);
    ver("pedido «{$pedido}»", $ruta === null ? 'DENEGADO' : 'permitido: ' . basename($ruta));
}
ver('sin protección se leería', basename(realpath($publicos . '/../../utilidades.php')));   // "utilidades.php" ¡peligro!

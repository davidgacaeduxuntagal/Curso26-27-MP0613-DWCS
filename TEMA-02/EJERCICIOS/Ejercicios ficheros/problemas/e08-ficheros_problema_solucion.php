<?php
/*
 * e08-ficheros_problema_solucion.php
 *  (FICHERO CON LAS SOLUCIONES)
 *  Problemas de: flock (LOCK_SH, LOCK_EX, LOCK_UN, LOCK_NB), file_put_contents con LOCK_EX,
 *                escritura atómica, defensa contra el recorrido de directorios
 *  (repasa los ejemplos de ../e08-ficheros.php)
 *
 *  Resuelve cada problema donde se indica y ejecútalo en modo depuración para comprobarlo.
 */
require_once __DIR__ . '/../utilidades.php';

// =============================================================================================
// PROBLEMA 1. Contador de descargas por fichero
//  Escribe una función contarDescarga(string $fichero): int que lleve en p08_descargas.json un
//  contador por nombre de fichero (["menu.pdf" => 3, ...]), con bloqueo exclusivo mientras lee y
//  escribe, y devuelva el nuevo valor. Simula 3 descargas de menu.pdf y 1 de carta.pdf.
// =============================================================================================
titulo('Problema 1');
@unlink(rutaDatos('p08_descargas.json'));
// completa aquí tu solución
// ---- SOLUCIÓN ----
function contarDescarga(string $fichero): int
{
    $fp = fopen(rutaDatos('p08_descargas.json'), 'c+');
    flock($fp, LOCK_EX);
    $contenido  = stream_get_contents($fp);
    $contadores = $contenido === '' ? [] : json_decode($contenido, true);
    $contadores[$fichero] = ($contadores[$fichero] ?? 0) + 1;
    ftruncate($fp, 0);
    rewind($fp);
    fwrite($fp, json_encode($contadores));
    fflush($fp);
    flock($fp, LOCK_UN);
    fclose($fp);
    return $contadores[$fichero];
}
contarDescarga('menu.pdf');
contarDescarga('menu.pdf');
contarDescarga('carta.pdf');
ver('descargas de menu.pdf', contarDescarga('menu.pdf'));    // 3
ver('fichero', file_get_contents(rutaDatos('p08_descargas.json')));

// =============================================================================================
// PROBLEMA 2. Lectura con bloqueo compartido
//  Escribe una función leerConBloqueo(string $ruta): string que abra el fichero, lo bloquee con
//  LOCK_SH (varios lectores a la vez, pero ningún escritor), lo lea entero y lo libere.
// =============================================================================================
titulo('Problema 2');
// completa aquí tu solución
// ---- SOLUCIÓN ----
function leerConBloqueo(string $ruta): string
{
    $fp = fopen($ruta, 'r');
    flock($fp, LOCK_SH);
    $contenido = stream_get_contents($fp);
    flock($fp, LOCK_UN);
    fclose($fp);
    return $contenido;
}
ver('contenido leído con LOCK_SH', leerConBloqueo(rutaDatos('p08_descargas.json')));

// =============================================================================================
// PROBLEMA 3. ¿Ya se está ejecutando?
//  Una tarea de envío de boletines no debe ejecutarse dos veces a la vez. Escribe una función
//  intentarIniciar(string $nombre) que devuelva el recurso bloqueado (LOCK_EX | LOCK_NB) si lo
//  consigue, o null si ya hay otra ejecución en marcha. Pruébala llamándola dos veces.
// =============================================================================================
titulo('Problema 3');
// completa aquí tu solución
// ---- SOLUCIÓN ----
function intentarIniciar(string $nombre)
{
    $fp = fopen(rutaDatos("p08_$nombre.lock"), 'c');
    if (!flock($fp, LOCK_EX | LOCK_NB)) {
        fclose($fp);
        return null;
    }
    return $fp;
}
$primera  = intentarIniciar('boletin');
$segunda  = intentarIniciar('boletin');
ver('¿primera ejecución iniciada?', $primera !== null);    // true
ver('¿segunda ejecución iniciada?', $segunda !== null);    // false
flock($primera, LOCK_UN);
fclose($primera);

// =============================================================================================
// PROBLEMA 4. Guardado atómico
//  Escribe una función guardarAtomico(string $ruta, string $contenido): bool que escriba primero
//  en un fichero temporal de la misma carpeta y después lo renombre al nombre definitivo.
// =============================================================================================
titulo('Problema 4');
// completa aquí tu solución
// ---- SOLUCIÓN ----
function guardarAtomico(string $ruta, string $contenido): bool
{
    $temporal = $ruta . '.' . uniqid() . '.tmp';
    if (file_put_contents($temporal, $contenido, LOCK_EX) === false) {
        return false;
    }
    return rename($temporal, $ruta);
}
ver('guardado', guardarAtomico(rutaDatos('p08_carta.txt'), "Café 1,50 €\nTortilla 3,20 €\n"));
ver('contenido', file_get_contents(rutaDatos('p08_carta.txt')));
ver('temporales que quedan', count(glob(rutaDatos('p08_carta.txt.*.tmp'))));   // 0

// =============================================================================================
// PROBLEMA 5. Visor de ficheros con lista blanca
//  La página ver.php?doc=... solo debe mostrar los documentos de la lista blanca. Escribe una
//  función rutaDocumento(string $pedido): ?string que devuelva la ruta del documento si está en
//  la lista, o null en cualquier otro caso. Pruébala con los pedidos de abajo.
// =============================================================================================
titulo('Problema 5');
$permitidos = ['carta' => 'p08_carta.txt', 'descargas' => 'p08_descargas.json'];
$pedidos    = ['carta', 'descargas', '../utilidades.php', 'carta.txt', ''];
// completa aquí tu solución
// ---- SOLUCIÓN ----
function rutaDocumento(string $pedido, array $permitidos): ?string
{
    return array_key_exists($pedido, $permitidos) ? rutaDatos($permitidos[$pedido]) : null;
}
foreach ($pedidos as $pedido) {
    $ruta = rutaDocumento($pedido, $permitidos);
    ver("doc=«{$pedido}»", $ruta === null ? 'DENEGADO' : basename($ruta));
}
// Con una lista blanca, el usuario elige una CLAVE y nosotros decidimos el fichero: no hay forma
// de llegar a otro fichero, se escriba lo que se escriba en la URL.

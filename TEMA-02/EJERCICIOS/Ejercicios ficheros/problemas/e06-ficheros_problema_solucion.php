<?php
/*
 * e06-ficheros_problema_solucion.php
 *  (FICHERO CON LAS SOLUCIONES)
 *  Problemas de: is_file, is_dir, is_readable, is_writable, filemtime, stat, clearstatcache,
 *                pathinfo, basename, dirname, realpath, finfo
 *  (repasa los ejemplos de ../e06-ficheros.php)
 *
 *  Resuelve cada problema donde se indica y ejecútalo en modo depuración para comprobarlo.
 */
require_once __DIR__ . '/../utilidades.php';

// =============================================================================================
// PROBLEMA 1. Diagnóstico de una ruta
//  Escribe una función diagnostico(string $ruta): string que devuelva "no existe", "directorio",
//  "fichero (solo lectura)" o "fichero (lectura y escritura)".
// =============================================================================================
titulo('Problema 1');
$fichero = rutaDatos('p06_prueba.txt');
file_put_contents($fichero, "hola");
// completa aquí tu solución
// ---- SOLUCIÓN ----
function diagnostico(string $ruta): string
{
    clearstatcache();
    if (!file_exists($ruta)) {
        return "no existe";
    }
    if (is_dir($ruta)) {
        return "directorio";
    }
    return is_writable($ruta) ? "fichero (lectura y escritura)" : "fichero (solo lectura)";
}
ver('fichero', diagnostico($fichero));
ver('carpeta datos', diagnostico(rutaDatos()));
ver('inexistente', diagnostico(rutaDatos('p06_nada.txt')));
chmod($fichero, 0444);
ver('tras chmod 0444', diagnostico($fichero));
chmod($fichero, 0644);

// =============================================================================================
// PROBLEMA 2. ¿Hay que regenerar la caché?
//  Una página guarda en p06_cache.html el HTML generado. Escribe una función cacheValida(string
//  $ruta, int $segundos): bool que diga si el fichero existe y se modificó hace menos de
//  $segundos segundos.
// =============================================================================================
titulo('Problema 2');
$cache = rutaDatos('p06_cache.html');
file_put_contents($cache, "<p>Carta del día</p>");
// completa aquí tu solución
// ---- SOLUCIÓN ----
function cacheValida(string $ruta, int $segundos): bool
{
    clearstatcache();
    return is_file($ruta) && (time() - filemtime($ruta)) < $segundos;
}
ver('¿válida durante 60 s?', cacheValida($cache, 60));          // true
touch($cache, time() - 3600);                                    // simulamos que se creó hace una hora
ver('¿válida tras una hora?', cacheValida($cache, 60));          // false

// =============================================================================================
// PROBLEMA 3. Nombre seguro para un fichero subido
//  Un usuario sube un fichero con el nombre $nombreOriginal. Genera un nombre seguro: solo la
//  extensión original en minúsculas (si es jpg, jpeg, png o gif) y un nombre propio único
//  ("foto_" + uniqid()). Si la extensión no está permitida, el resultado es null.
// =============================================================================================
titulo('Problema 3');
$nombres = ["../../Mi Foto Playa.JPG", "logo.png", "virus.php", "sin_extension"];
// completa aquí tu solución
// ---- SOLUCIÓN ----
$seguros = [];
foreach ($nombres as $nombreOriginal) {
    $extension = strtolower(pathinfo(basename($nombreOriginal), PATHINFO_EXTENSION));
    $seguros[$nombreOriginal] = in_array($extension, ['jpg', 'jpeg', 'png', 'gif'], true)
        ? 'foto_' . uniqid() . '.' . $extension
        : null;
}
ver('nombres seguros', $seguros);

// =============================================================================================
// PROBLEMA 4. Informe de una carpeta
//  Para cada FICHERO de la carpeta "datos" (no las subcarpetas), obtén con stat su tamaño y su
//  fecha de modificación, y muestra los 5 más grandes ordenados de mayor a menor.
// =============================================================================================
titulo('Problema 4');
// completa aquí tu solución
// ---- SOLUCIÓN ----
$informe = [];
foreach (scandir(rutaDatos()) as $nombre) {
    $ruta = rutaDatos($nombre);
    if (is_file($ruta)) {
        $info = stat($ruta);
        $informe[$nombre] = ['bytes' => $info['size'], 'modificado' => date('d/m H:i', $info['mtime'])];
    }
}
uasort($informe, fn($a, $b) => $b['bytes'] <=> $a['bytes']);
ver('los 5 más grandes', array_slice($informe, 0, 5, true));

// =============================================================================================
// PROBLEMA 5. ¿Es de verdad una imagen?
//  Crea dos ficheros: uno PNG de verdad (los bytes en base64 de abajo) llamado p06_texto.png, y un
//  fichero de texto llamado p06_falso.png. Con finfo, di cuál es realmente una imagen.
// =============================================================================================
titulo('Problema 5');
file_put_contents(rutaDatos('p06_texto.png'), base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNk+M9QDwADhgGAWjR9awAAAABJRU5ErkJggg=='));
file_put_contents(rutaDatos('p06_falso.png'), "esto no es una imagen");
// completa aquí tu solución
// ---- SOLUCIÓN ----
$finfo = new finfo(FILEINFO_MIME_TYPE);
foreach (['p06_texto.png', 'p06_falso.png'] as $nombre) {
    $tipo = $finfo->file(rutaDatos($nombre));
    ver($nombre, $tipo . (str_starts_with($tipo, 'image/') ? ' (imagen)' : ' (NO es una imagen)'));
}

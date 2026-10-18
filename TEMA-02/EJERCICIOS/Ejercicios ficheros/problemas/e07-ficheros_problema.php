<?php
/*
 * e07-ficheros_problema.php
 *  Problemas de: mkdir, copy, rename, unlink, touch, scandir, glob, opendir / readdir / closedir,
 *                rmdir, tempnam, tmpfile
 *  (repasa los ejemplos de ../e07-ficheros.php)
 *
 *  Resuelve cada problema donde se indica y ejecútalo en modo depuración para comprobarlo.
 *  Todo se hace dentro de la carpeta ../datos/p07 (que el problema 5 borra al final).
 */
require_once __DIR__ . '/../utilidades.php';

$base = rutaDatos('p07');
if (!is_dir($base)) {
    mkdir($base);        // la carpeta base del problema (las subcarpetas las crea el problema 1)
}

// =============================================================================================
// PROBLEMA 1. Estructura de carpetas de un proyecto
//  Crea, dentro de $base, las carpetas "subidas/imagenes", "subidas/documentos" y "logs" (con un
//  solo mkdir para cada ruta, aunque falten las intermedias) sin que aparezcan avisos si ya existen.
// =============================================================================================
titulo('Problema 1');
// completa aquí tu solución

// =============================================================================================
// PROBLEMA 2. Copias de seguridad numeradas
//  Escribe una función copiaSeguridad(string $fichero): string que copie el fichero añadiendo
//  ".bak1", ".bak2"... al nombre (el primer número libre) y devuelva el nombre de la copia.
//  Haz tres copias del fichero de notas.
// =============================================================================================
titulo('Problema 2');
$notas = "$base/notas.csv";
file_put_contents($notas, "dni;nota\n12345678Z;8.5\n");
// completa aquí tu solución

// =============================================================================================
// PROBLEMA 3. Clasificar ficheros subidos
//  En "subidas" hay ficheros de varios tipos (los creamos con touch). Muévelos con rename a
//  "subidas/imagenes" (jpg, png) o "subidas/documentos" (pdf, txt) según su extensión, usando glob.
// =============================================================================================
titulo('Problema 3');
if (!is_dir("$base/subidas")) {
    mkdir("$base/subidas");
}
foreach (['playa.jpg', 'logo.png', 'menu.pdf', 'notas.txt'] as $nombre) {
    touch("$base/subidas/$nombre");
}
// completa aquí tu solución

// =============================================================================================
// PROBLEMA 4. Listado recursivo
//  Escribe una función recursiva listar(string $dir, int $nivel = 0): array que devuelva todas las
//  rutas (relativas a $base) de los ficheros y carpetas que hay dentro, usando opendir / readdir.
// =============================================================================================
titulo('Problema 4');
// completa aquí tu solución

// =============================================================================================
// PROBLEMA 5. Borrar una carpeta con todo su contenido
//  rmdir solo borra carpetas vacías. Escribe una función recursiva borrarCarpeta(string $dir): void
//  que borre primero los ficheros (unlink) y las subcarpetas, y después la carpeta. Úsala con $base.
// =============================================================================================
titulo('Problema 5');
// completa aquí tu solución

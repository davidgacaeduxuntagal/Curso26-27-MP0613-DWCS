<?php
/*
 * e01-ficheros_problema.php
 *  Problemas de: file_put_contents, file_get_contents, file, file_exists, filesize, readfile
 *  (repasa los ejemplos de ../e01-ficheros.php)
 *
 *  Resuelve cada problema donde se indica y ejecútalo en modo depuración para comprobarlo.
 *  Los ficheros se crean en la carpeta ../datos (con el prefijo p01_).
 */
require_once __DIR__ . '/../utilidades.php';

// =============================================================================================
// PROBLEMA 1. Diario de clase
//  Guarda en p01_diario.txt una línea con la fecha de hoy y el texto "Hoy hemos visto ficheros".
//  Cada vez que ejecutes el script se debe AÑADIR una línea nueva (no borrar las anteriores).
//  Muestra cuántas líneas tiene el diario.
// =============================================================================================
titulo('Problema 1');
$diario = rutaDatos('p01_diario.txt');
// completa aquí tu solución

// =============================================================================================
// PROBLEMA 2. Lectura segura
//  Escribe una función leerTexto(string $ruta): string que devuelva el contenido del fichero, o el
//  texto "(el fichero no existe)" si no existe, SIN que aparezca ningún aviso (Warning).
// =============================================================================================
titulo('Problema 2');
// completa aquí tu solución

// =============================================================================================
// PROBLEMA 3. Palabra más larga
//  El fichero p01_palabras.txt tiene una palabra por línea (lo creamos aquí). Léelo con file() y
//  obtén la palabra más larga (en caracteres, no en bytes).
// =============================================================================================
titulo('Problema 3');
$palabras = rutaDatos('p01_palabras.txt');
file_put_contents($palabras, "sol\n\ncarballeira\nmar\nesmorga\n\n");
// completa aquí tu solución

// =============================================================================================
// PROBLEMA 4. Tamaño legible
//  Escribe una función tamanoLegible(string $ruta): string que devuelva el tamaño de un fichero
//  como "123 bytes", "4,5 KB" o "1,2 MB" (1 KB = 1024 bytes). Pruébala con este propio fichero
//  (__FILE__) y con un fichero de 3000 bytes que crees con str_repeat.
// =============================================================================================
titulo('Problema 4');
// completa aquí tu solución

// =============================================================================================
// PROBLEMA 5. Plantilla de correo
//  En p01_plantilla.txt hay una plantilla con marcadores {nombre} y {pedido}. Léela, sustituye los
//  marcadores y guarda el resultado en p01_correo_ana.txt. Después muéstralo con readfile.
// =============================================================================================
titulo('Problema 5');
$plantilla = rutaDatos('p01_plantilla.txt');
file_put_contents($plantilla, "Hola {nombre}:\nTu pedido {pedido} ya está en camino.\nGracias por comprar en el Bar Baio.\n");
// completa aquí tu solución

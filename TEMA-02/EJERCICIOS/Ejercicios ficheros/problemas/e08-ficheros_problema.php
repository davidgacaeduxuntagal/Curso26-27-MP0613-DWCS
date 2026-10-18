<?php
/*
 * e08-ficheros_problema.php
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

// =============================================================================================
// PROBLEMA 2. Lectura con bloqueo compartido
//  Escribe una función leerConBloqueo(string $ruta): string que abra el fichero, lo bloquee con
//  LOCK_SH (varios lectores a la vez, pero ningún escritor), lo lea entero y lo libere.
// =============================================================================================
titulo('Problema 2');
// completa aquí tu solución

// =============================================================================================
// PROBLEMA 3. ¿Ya se está ejecutando?
//  Una tarea de envío de boletines no debe ejecutarse dos veces a la vez. Escribe una función
//  intentarIniciar(string $nombre) que devuelva el recurso bloqueado (LOCK_EX | LOCK_NB) si lo
//  consigue, o null si ya hay otra ejecución en marcha. Pruébala llamándola dos veces.
// =============================================================================================
titulo('Problema 3');
// completa aquí tu solución

// =============================================================================================
// PROBLEMA 4. Guardado atómico
//  Escribe una función guardarAtomico(string $ruta, string $contenido): bool que escriba primero
//  en un fichero temporal de la misma carpeta y después lo renombre al nombre definitivo.
// =============================================================================================
titulo('Problema 4');
// completa aquí tu solución

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

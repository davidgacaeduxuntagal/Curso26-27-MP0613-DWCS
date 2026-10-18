<?php
/*
 * e04-ficheros_problema.php
 *  Problemas de: ftell, fseek (SEEK_SET, SEEK_CUR, SEEK_END), rewind, ftruncate, modificar con r+
 *  (repasa los ejemplos de ../e04-ficheros.php)
 *
 *  Resuelve cada problema donde se indica y ejecútalo en modo depuración para comprobarlo.
 */
require_once __DIR__ . '/../utilidades.php';

// =============================================================================================
// PROBLEMA 1. Últimos bytes de un registro
//  Muestra los 20 últimos bytes del fichero de log sin leer el fichero entero (fseek con SEEK_END).
// =============================================================================================
titulo('Problema 1');
$log = rutaDatos('p04_servidor.log');
file_put_contents($log, "10:00 arranque\n10:05 petición /index.php\n10:06 petición /carta.php\n10:07 ERROR disco lleno\n");
// completa aquí tu solución

// =============================================================================================
// PROBLEMA 2. Ficheros de registros de longitud fija ("acceso directo")
//  En p04_socios.dat cada socio ocupa EXACTAMENTE 30 bytes: número (4), nombre (20) y cuota (6),
//  rellenados con espacios (str_pad). Escribe los socios y después lee DIRECTAMENTE el tercero
//  con fseek, sin leer los anteriores.
// =============================================================================================
titulo('Problema 2');
const TAM_REGISTRO = 30;
$socios = [[1, 'Ana Pérez', 30], [2, 'Brais Otero', 25], [3, 'Carmen Nunez', 30], [4, 'Diego Lago', 15]];
$fichero = rutaDatos('p04_socios.dat');
// completa aquí tu solución

// =============================================================================================
// PROBLEMA 3. Modificar la cuota de un socio
//  Cambia la cuota del socio 2 a 40 SOBRESCRIBIENDO solo esos 6 bytes del fichero (modo r+ y
//  fseek), sin reescribir el resto. Comprueba el resultado leyendo todo el fichero.
// =============================================================================================
titulo('Problema 3');
// completa aquí tu solución

// =============================================================================================
// PROBLEMA 4. Contar líneas y volver a empezar
//  Con el fichero de log del problema 1: cuenta las líneas que contienen "petición" y, con rewind,
//  vuelve a recorrerlo para obtener la posición (ftell) en la que empieza cada línea.
// =============================================================================================
titulo('Problema 4');
// completa aquí tu solución

// =============================================================================================
// PROBLEMA 5. Rotar un log
//  Cuando el log pasa de 60 bytes, se conservan solo las 2 últimas líneas: lee las líneas, vacía
//  el fichero con ftruncate y vuelve a escribir las 2 últimas (sin cerrarlo entre medias).
// =============================================================================================
titulo('Problema 5');
// completa aquí tu solución

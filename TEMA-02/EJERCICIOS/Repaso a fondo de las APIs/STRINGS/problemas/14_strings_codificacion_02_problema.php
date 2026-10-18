<?php
/*
 * 14_strings_codificacion_02_problema.php
 *  Problemas de: md5, sha1, crc32, md5_file, sha1_file, crypt
 *  (repasa los ejemplos de ../14_strings_codificacion_02.php)
 *
 *  Resuelve cada problema donde se indica y ejecútalo en modo depuración para comprobarlo.
 */
require_once __DIR__ . '/../utilidades.php';

// =============================================================================================
// PROBLEMA 1. Detectar ficheros duplicados
//  Varios "ficheros" (aquí, su contenido en un array) pueden estar repetidos con nombres
//  distintos. Agrúpalos por su md5 para saber cuáles son iguales.
// =============================================================================================
titulo('Problema 1');
$ficheros = [
    'foto1.txt'       => "contenido A",
    'copia_foto1.txt' => "contenido A",
    'foto2.txt'       => "contenido B",
];
// completa aquí tu solución

// =============================================================================================
// PROBLEMA 2. Enlace de descarga firmado
//  Para que nadie cambie el número de factura en un enlace de descarga, se añade una "firma":
//  sha1(numeroFactura . clave secreta). Genera el enlace y después comprueba si un enlace
//  recibido (con la factura cambiada) es válido.
// =============================================================================================
titulo('Problema 2');
$claveSecreta = "clave-solo-en-el-servidor";
$factura      = "F2026-00037";
// completa aquí tu solución

// =============================================================================================
// PROBLEMA 3. Comprobar la integridad de los bloques de un fichero
//  Un fichero se envió en bloques, cada uno con su CRC32. Indica qué bloques han llegado
//  dañados comparando el CRC calculado con el recibido.
// =============================================================================================
titulo('Problema 3');
$bloques = [
    ["datos del bloque 1", crc32("datos del bloque 1")],
    ["datos del bloqXe 2", crc32("datos del bloque 2")],   // ¡llegó dañado!
    ["datos del bloque 3", crc32("datos del bloque 3")],
];
// completa aquí tu solución

// =============================================================================================
// PROBLEMA 4. Huella de los ficheros de este proyecto
//  Muestra el md5 y el sha1 de los ficheros de ejemplo 01 y 02 (están en la carpeta de arriba:
//  __DIR__ . '/../01_strings_basicas_01.php'). ¿Qué longitud tiene cada resumen?
// =============================================================================================
titulo('Problema 4');
$rutas = [__DIR__ . '/../01_strings_basicas_01.php', __DIR__ . '/../02_strings_basicas_02.php'];
// completa aquí tu solución

// =============================================================================================
// PROBLEMA 5. Registro e inicio de sesión
//  Simula el registro de un usuario guardando el hash de su contraseña con password_hash y
//  después dos intentos de inicio de sesión (uno correcto y otro incorrecto) con password_verify.
//  ¿Por qué dos usuarios con la misma contraseña tienen hashes distintos? (responde en un comentario)
// =============================================================================================
titulo('Problema 5');
$passwordRegistro = "Rodeira2026!";
// completa aquí tu solución

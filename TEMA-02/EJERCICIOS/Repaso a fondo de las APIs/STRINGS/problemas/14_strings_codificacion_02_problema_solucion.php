<?php
/*
 * 14_strings_codificacion_02_problema_solucion.php
 *  (FICHERO CON LAS SOLUCIONES)
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
// ---- SOLUCIÓN ----
$grupos = [];
foreach ($ficheros as $nombre => $contenido) {
    $grupos[md5($contenido)][] = $nombre;
}
ver('agrupados por md5', $grupos);

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
// ---- SOLUCIÓN ----
$firma  = sha1($factura . $claveSecreta);
$enlace = "descargar.php?factura=$factura&firma=$firma";
ver('enlace', $enlace);

// Llega un enlace manipulado: misma firma, otra factura
$facturaRecibida = "F2026-00038";
$firmaRecibida   = $firma;
$esValido        = hash_equals(sha1($facturaRecibida . $claveSecreta), $firmaRecibida);
ver('¿enlace recibido válido?', $esValido);    // false
// (en un caso real se usaría hash_hmac('sha256', ...), que está pensado para esto)

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
// ---- SOLUCIÓN ----
$danados = [];
foreach ($bloques as $i => [$datos, $crcRecibido]) {
    if (crc32($datos) !== $crcRecibido) {
        $danados[] = $i + 1;
    }
}
ver('bloques dañados', $danados);     // [2]

// =============================================================================================
// PROBLEMA 4. Huella de los ficheros de este proyecto
//  Muestra el md5 y el sha1 de los ficheros de ejemplo 01 y 02 (están en la carpeta de arriba:
//  __DIR__ . '/../01_strings_basicas_01.php'). ¿Qué longitud tiene cada resumen?
// =============================================================================================
titulo('Problema 4');
$rutas = [__DIR__ . '/../01_strings_basicas_01.php', __DIR__ . '/../02_strings_basicas_02.php'];
// completa aquí tu solución
// ---- SOLUCIÓN ----
$huellas = [];
foreach ($rutas as $ruta) {
    $huellas[basename($ruta)] = ['md5' => md5_file($ruta), 'sha1' => sha1_file($ruta)];
}
ver('huellas', $huellas);
ver('longitudes md5 / sha1', [strlen($huellas['01_strings_basicas_01.php']['md5']), strlen($huellas['01_strings_basicas_01.php']['sha1'])]);   // [32, 40]

// =============================================================================================
// PROBLEMA 5. Registro e inicio de sesión
//  Simula el registro de un usuario guardando el hash de su contraseña con password_hash y
//  después dos intentos de inicio de sesión (uno correcto y otro incorrecto) con password_verify.
//  ¿Por qué dos usuarios con la misma contraseña tienen hashes distintos? (responde en un comentario)
// =============================================================================================
titulo('Problema 5');
$passwordRegistro = "Rodeira2026!";
// completa aquí tu solución
// ---- SOLUCIÓN ----
$hashGuardado = password_hash($passwordRegistro, PASSWORD_DEFAULT);
ver('hash guardado', $hashGuardado);
ver('intento "Rodeira2026!"', password_verify("Rodeira2026!", $hashGuardado));   // true
ver('intento "rodeira2026!"', password_verify("rodeira2026!", $hashGuardado));   // false
ver('otro hash de la misma contraseña', password_hash($passwordRegistro, PASSWORD_DEFAULT));
// Porque cada hash lleva una SAL aleatoria distinta (se guarda dentro del propio hash). Así,
// aunque dos usuarios usen la misma contraseña, sus hashes no coinciden.

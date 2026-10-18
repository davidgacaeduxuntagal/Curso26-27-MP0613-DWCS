<?php
/*
 * 14_strings_codificacion_02.php
 *  STRINGS - CODIFICACIÓN (2): "huellas" de los datos (funciones hash y sumas de control)
 *  Funciones: md5, sha1, crc32, md5_file, sha1_file, crypt
 *
 *  Una función hash convierte unos datos de cualquier tamaño en un resumen de tamaño fijo:
 *    - el mismo dato da SIEMPRE el mismo resumen
 *    - un cambio mínimo en el dato cambia totalmente el resumen
 *    - no se puede "volver atrás" (no hay función inversa)
 *
 *  Ejecuta en modo depuración paso a paso (F10). Compara los resúmenes de textos casi iguales.
 */
require_once __DIR__ . '/utilidades.php';

// ---------------------------------------------------------------------------------------------
// md5 — resumen MD5 de un string: 32 caracteres hexadecimales (128 bits)
// ENLACE: https://www.php.net/manual/es/function.md5.php
// ENUNCIADO EJEMPLO: generar la URL del avatar de Gravatar de un usuario, que se calcula con el
//   md5 de su email en minúsculas y sin espacios; y ver que un cambio mínimo cambia el resumen.
// NOTA: MD5 ya NO es seguro para contraseñas (es rapidísimo de calcular y hay tablas con
//   millones de resúmenes conocidos). Sirve para identificadores, cachés, etc.
// ---------------------------------------------------------------------------------------------
titulo('md5');
$email         = "  Ana.Perez@Example.com ";
$hashEmail     = md5(strtolower(trim($email)));
$gravatar      = "https://www.gravatar.com/avatar/" . $hashEmail;
ver('md5 del email', $hashEmail);
ver('URL de Gravatar', $gravatar);

$h1            = md5("Hola");
$h2            = md5("hola");                 // solo cambia la mayúscula... y el resumen cambia entero
ver('md5("Hola")', $h1);
ver('md5("hola")', $h2);

// ---------------------------------------------------------------------------------------------
// sha1 — resumen SHA-1: 40 caracteres hexadecimales (160 bits)
// ENLACE: https://www.php.net/manual/es/function.sha1.php
// ENUNCIADO EJEMPLO: generar un identificador para guardar en caché el resultado de una búsqueda
//   (el mismo texto de búsqueda produce siempre el mismo nombre de fichero de caché).
// NOTA: tampoco es seguro para contraseñas. Para otros algoritmos (sha256...) usa hash('sha256', $texto).
// ---------------------------------------------------------------------------------------------
titulo('sha1');
$busqueda      = "zapatillas running talla 42";
$ficheroCache  = "cache/" . sha1($busqueda) . ".json";
ver('fichero de caché', $ficheroCache);
ver('sha256 (con hash())', hash('sha256', $busqueda));

// ---------------------------------------------------------------------------------------------
// crc32 — suma de control CRC32: un número entero (32 bits)
// ENLACE: https://www.php.net/manual/es/function.crc32.php
// ENUNCIADO EJEMPLO: detectar si un mensaje se ha estropeado durante una transmisión (como hacen
//   los ficheros ZIP): el emisor envía el mensaje y su CRC; el receptor recalcula el CRC y compara.
// NOTA: devuelve un ENTERO; con sprintf("%08x") se ve en hexadecimal, como suele mostrarse.
//   Detecta errores accidentales, pero no sirve como protección frente a modificaciones intencionadas.
// ---------------------------------------------------------------------------------------------
titulo('crc32');
$enviado       = "Transferencia de 100 euros";
$crcEnviado    = crc32($enviado);
$recibido      = "Transferencia de 900 euros";        // ¡ha cambiado un carácter!
$integro       = crc32($recibido) === $crcEnviado;     // false
ver('crc32', $crcEnviado);
ver('crc32 en hexadecimal', sprintf("%08x", $crcEnviado));
ver('¿mensaje íntegro?', $integro);

// ---------------------------------------------------------------------------------------------
// md5_file / sha1_file — resumen del CONTENIDO de un fichero
// ENLACE: https://www.php.net/manual/es/function.md5-file.php
// ENLACE: https://www.php.net/manual/es/function.sha1-file.php
// ENUNCIADO EJEMPLO: comprobar que un fichero descargado no está corrupto comparando su resumen
//   con el que publica la web de descarga. Aquí usamos este mismo fichero (__FILE__).
// NOTA: devuelven false (y un aviso) si el fichero no existe. Modifica este fichero (añade un
//   espacio) y vuelve a ejecutar: el resumen cambiará.
// ---------------------------------------------------------------------------------------------
titulo('md5_file / sha1_file');
$fichero       = __FILE__;
$md5Fichero    = md5_file($fichero);
$sha1Fichero   = sha1_file($fichero);
ver('fichero', basename($fichero));
ver('md5_file', $md5Fichero);
ver('sha1_file', $sha1Fichero);
ver('¿igual que md5 del contenido?', $md5Fichero === md5(file_get_contents($fichero)));   // true

// ---------------------------------------------------------------------------------------------
// crypt — calcula un hash de contraseña con "sal" usando el algoritmo indicado en la propia sal
// ENLACE: https://www.php.net/manual/es/function.crypt.php
// ENUNCIADO EJEMPLO: guardar la contraseña de un usuario de forma segura y comprobarla al
//   iniciar sesión. La sal "$2y$10$..." indica el algoritmo bcrypt con coste 10.
// NOTA: la sal aleatoria hace que dos usuarios con la misma contraseña tengan hashes distintos.
//   En la práctica NO se usa crypt directamente sino password_hash() y password_verify(), que
//   generan la sal y eligen el algoritmo por ti (se muestra al final).
// ---------------------------------------------------------------------------------------------
titulo('crypt');
$password      = "MiClave2026";
$sal           = '$2y$10$' . substr(strtr(base64_encode(random_bytes(16)), '+', '.'), 0, 22);
$hashGuardado  = crypt($password, $sal);              // empieza por "$2y$10$" + sal + resumen
ver('hash bcrypt', $hashGuardado);

// Al iniciar sesión: se aplica crypt a lo escrito usando el hash guardado como sal y se compara
$escrito       = "MiClave2026";
$correcta      = hash_equals($hashGuardado, crypt($escrito, $hashGuardado));   // true
ver('¿contraseña correcta?', $correcta);

// Forma recomendada:
$hashModerno   = password_hash($password, PASSWORD_DEFAULT);
ver('password_hash', $hashModerno);
ver('password_verify', password_verify($escrito, $hashModerno));              // true

<?php
/*
 * e06-ficheros.php
 *  FICHEROS (6): comprobaciones e información de ficheros y rutas
 *  Funciones: is_file, is_dir, is_readable, is_writable, filemtime, stat, clearstatcache,
 *             pathinfo, basename, dirname, realpath, finfo (tipo MIME)
 *
 *  Ejecuta en modo depuración (F10). Despliega los arrays que devuelven stat() y pathinfo().
 */
require_once __DIR__ . '/utilidades.php';

$informe = rutaDatos('informe.txt');
file_put_contents($informe, "Ventas de octubre: 1.250 €\n");

// ---------------------------------------------------------------------------------------------
// is_file / is_dir — ¿es un fichero? ¿es un directorio?
// ENLACE: https://www.php.net/manual/es/function.is-file.php
// ENLACE: https://www.php.net/manual/es/function.is-dir.php
// ENUNCIADO EJEMPLO: comprobar el tipo de varias rutas antes de usarlas. A diferencia de
//   file_exists, distinguen ficheros de directorios.
// ---------------------------------------------------------------------------------------------
titulo('is_file / is_dir');
$rutas         = [$informe, rutaDatos(), rutaDatos('no_existe.txt')];
$tipos         = [];
foreach ($rutas as $ruta) {
    $tipos[basename($ruta)] = ['file_exists' => file_exists($ruta), 'is_file' => is_file($ruta), 'is_dir' => is_dir($ruta)];
}
ver('tipos', $tipos);

// ---------------------------------------------------------------------------------------------
// is_readable / is_writable — ¿puede el script leer / escribir el fichero?
// ENLACE: https://www.php.net/manual/es/function.is-readable.php
// ENLACE: https://www.php.net/manual/es/function.is-writable.php
// ENUNCIADO EJEMPLO: antes de guardar el informe, comprobar que se puede escribir; y ver qué
//   pasa si lo marcamos como "solo lectura" con chmod.
// NOTA: en un servidor Linux depende del usuario de Apache (www-data). En Windows, chmod solo
//   cambia el atributo "solo lectura".
// ---------------------------------------------------------------------------------------------
titulo('is_readable / is_writable');
ver('¿se puede leer?', is_readable($informe));      // true
ver('¿se puede escribir?', is_writable($informe));  // true
chmod($informe, 0444);                              // solo lectura
clearstatcache();
ver('¿se puede escribir tras chmod 0444?', is_writable($informe));   // false
chmod($informe, 0644);                              // lo dejamos como estaba
clearstatcache();

// ---------------------------------------------------------------------------------------------
// filemtime — fecha de la última modificación (marca de tiempo Unix)
// ENLACE: https://www.php.net/manual/es/function.filemtime.php
// ENUNCIADO EJEMPLO: mostrar "Actualizado el dd/mm/aaaa a las hh:mm" para el informe, y saber
//   hace cuántos segundos se modificó.
// NOTA: hermanas: fileatime (último acceso) y filectime (último cambio de permisos o contenido).
// ---------------------------------------------------------------------------------------------
titulo('filemtime');
$modificado    = filemtime($informe);
ver('marca de tiempo', $modificado);
ver('fecha', date('d/m/Y \a \l\a\s H:i', $modificado));
ver('segundos desde la modificación', time() - $modificado);

// ---------------------------------------------------------------------------------------------
// stat — toda la información del fichero en un array (con índices numéricos y con nombre)
// ENLACE: https://www.php.net/manual/es/function.stat.php
// ENUNCIADO EJEMPLO: obtener el tamaño, la fecha de modificación y los permisos del informe.
// NOTA: las claves más útiles: 'size', 'mtime', 'atime', 'mode'. Los permisos en octal:
//   substr(sprintf('%o', $info['mode']), -4)
// ---------------------------------------------------------------------------------------------
titulo('stat');
$info          = stat($informe);
ver('tamaño (size)', $info['size']);                // 29 bytes (el € ocupa 3)
ver('modificado (mtime)', date('Y-m-d H:i:s', $info['mtime']));
ver('permisos', substr(sprintf('%o', $info['mode']), -4));

// ---------------------------------------------------------------------------------------------
// clearstatcache — borra la caché de información de ficheros
// ENLACE: https://www.php.net/manual/es/function.clearstatcache.php
// ENUNCIADO EJEMPLO: añadir una línea al informe y ver que, SIN clearstatcache, filesize sigue
//   devolviendo el tamaño antiguo.
// ---------------------------------------------------------------------------------------------
titulo('clearstatcache');
$antes         = filesize($informe);
file_put_contents($informe, "Ventas de noviembre: 980 €\n", FILE_APPEND);
$sinLimpiar    = filesize($informe);                // ¡el mismo valor que antes!
clearstatcache();
$trasLimpiar   = filesize($informe);                // ahora sí, el nuevo tamaño
ver('antes / sin clearstatcache / con clearstatcache', [$antes, $sinLimpiar, $trasLimpiar]);

// ---------------------------------------------------------------------------------------------
// pathinfo / basename / dirname — descomponer una ruta (sin necesidad de que exista)
// ENLACE: https://www.php.net/manual/es/function.pathinfo.php
// ENLACE: https://www.php.net/manual/es/function.basename.php
// ENLACE: https://www.php.net/manual/es/function.dirname.php
// ENUNCIADO EJEMPLO: de la ruta de una foto subida obtener la carpeta, el nombre completo, la
//   extensión y el nombre sin extensión (para generar la miniatura "playa_mini.jpg").
// ---------------------------------------------------------------------------------------------
titulo('pathinfo / basename / dirname');
$foto          = '/var/www/html/fotos/2026/playa.de.samil.JPG';
$partes        = pathinfo($foto);
ver('pathinfo', $partes);           // dirname, basename, extension ("JPG"), filename ("playa.de.samil")
ver('basename', basename($foto));                    // "playa.de.samil.JPG"
ver('basename sin la extensión', basename($foto, '.JPG'));
ver('dirname', dirname($foto));                      // "/var/www/html/fotos/2026"
ver('dirname 2 niveles', dirname($foto, 2));          // "/var/www/html/fotos"
ver('miniatura', $partes['filename'] . '_mini.' . strtolower($partes['extension']));

// ---------------------------------------------------------------------------------------------
// realpath — ruta absoluta "limpia" (resuelve ".", ".." y enlaces), o false si no existe
// ENLACE: https://www.php.net/manual/es/function.realpath.php
// ENUNCIADO EJEMPLO: ver a dónde apunta realmente una ruta con ".." (la base de la defensa contra
//   el ataque de recorrido de directorios que verás en e08).
// ---------------------------------------------------------------------------------------------
titulo('realpath');
ver('ruta con ..', rutaDatos() . '/../datos/./informe.txt');
ver('realpath', realpath(rutaDatos() . '/../datos/./informe.txt'));
ver('realpath de algo que no existe', realpath(rutaDatos('no_existe.txt')));   // false

// ---------------------------------------------------------------------------------------------
// finfo — tipo MIME REAL según el contenido (extensión fileinfo)
// ENLACE: https://www.php.net/manual/es/class.finfo.php
// ENUNCIADO EJEMPLO: un usuario sube "foto.jpg" que en realidad es un fichero de texto con otra
//   extensión. Comprobar su tipo real antes de aceptarlo.
// NOTA: no te fíes nunca de la extensión ni del tipo que envía el navegador ($_FILES['...']['type']).
// ---------------------------------------------------------------------------------------------
titulo('finfo');
$falsaFoto     = rutaDatos('foto.jpg');
file_put_contents($falsaFoto, "<?php echo 'no soy una foto'; ?>");
$finfo         = new finfo(FILEINFO_MIME_TYPE);
ver('tipo de informe.txt', $finfo->file($informe));     // "text/plain"
ver('tipo de foto.jpg', $finfo->file($falsaFoto));      // "text/x-php" o "text/plain": ¡no es image/jpeg!
ver('¿es una imagen?', str_starts_with($finfo->file($falsaFoto), 'image/'));   // false

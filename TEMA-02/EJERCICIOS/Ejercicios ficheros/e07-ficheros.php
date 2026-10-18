<?php
/*
 * e07-ficheros.php
 *  FICHEROS (7): gestionar ficheros y directorios
 *  Funciones: mkdir, copy, rename, unlink, touch, scandir, glob, opendir / readdir / closedir,
 *             rmdir, tempnam + sys_get_temp_dir, tmpfile
 *
 *  Ejecuta en modo depuración (F10) con la carpeta "datos" abierta en el explorador de VSC: verás
 *  aparecer, cambiar de nombre y desaparecer ficheros y carpetas.
 */
require_once __DIR__ . '/utilidades.php';

$base = rutaDatos('copias');

// ---------------------------------------------------------------------------------------------
// mkdir — crea un directorio. Con el tercer parámetro a true crea también los intermedios.
// ENLACE: https://www.php.net/manual/es/function.mkdir.php
// ENUNCIADO EJEMPLO: crear la estructura de carpetas para las copias de seguridad por año y mes
//   (copias/2026/10). Si ya existe, mkdir da un Warning: lo comprobamos antes con is_dir.
// NOTA: el segundo parámetro son los permisos (en Linux); en Windows se ignora.
// ---------------------------------------------------------------------------------------------
titulo('mkdir');
$carpetaMes    = $base . '/' . date('Y') . '/' . date('m');
if (!is_dir($carpetaMes)) {
    $creada    = mkdir($carpetaMes, 0777, true);
    ver('¿creada?', $creada);
}
ver('¿existe?', is_dir($carpetaMes));

// ---------------------------------------------------------------------------------------------
// copy — copia un fichero (si el destino existe, lo SOBRESCRIBE)
// ENLACE: https://www.php.net/manual/es/function.copy.php
// ENUNCIADO EJEMPLO: hacer una copia de seguridad del fichero de notas añadiendo la fecha y la
//   hora al nombre: notas_2026-10-16_10-30-05.csv
// ---------------------------------------------------------------------------------------------
titulo('copy');
$original      = rutaDatos('notas_curso.csv');
file_put_contents($original, "dni;nombre;nota\n12345678Z;Ana;8.5\n");
$copia         = $carpetaMes . '/notas_' . date('Y-m-d_H-i-s') . '.csv';
ver('copy', copy($original, $copia));
ver('¿son iguales?', file_get_contents($original) === file_get_contents($copia));   // true

// ---------------------------------------------------------------------------------------------
// rename — cambia el nombre de un fichero o directorio, o lo MUEVE a otra carpeta
// ENLACE: https://www.php.net/manual/es/function.rename.php
// ENUNCIADO EJEMPLO: (1) renombrar un borrador como versión definitiva; (2) mover un fichero
//   "procesado" a la carpeta de copias.
// NOTA: si el destino existe, se sobrescribe (en Windows puede fallar).
// ---------------------------------------------------------------------------------------------
titulo('rename');
$borrador      = rutaDatos('informe_BORRADOR.txt');
$definitivo    = rutaDatos('informe_FINAL.txt');
@unlink($definitivo);
file_put_contents($borrador, "Informe de ventas\n");
ver('renombrar', rename($borrador, $definitivo));
ver('¿existe el borrador? / ¿y el final?', [file_exists($borrador), file_exists($definitivo)]);   // [false, true]

ver('mover', rename($definitivo, $carpetaMes . '/informe_FINAL.txt'));

// ---------------------------------------------------------------------------------------------
// touch — crea un fichero vacío o actualiza su fecha de modificación
// ENLACE: https://www.php.net/manual/es/function.touch.php
// ENUNCIADO EJEMPLO: crear un fichero "marca" vacío que indica que la copia de seguridad de hoy
//   ya está hecha.
// ---------------------------------------------------------------------------------------------
titulo('touch');
$marca         = $carpetaMes . '/COPIA_HECHA';
touch($marca);
ver('¿existe la marca? tamaño', [file_exists($marca), filesize($marca)]);   // [true, 0]

// ---------------------------------------------------------------------------------------------
// scandir — array con los nombres del contenido de un directorio (incluye "." y "..")
// ENLACE: https://www.php.net/manual/es/function.scandir.php
// ENUNCIADO EJEMPLO: listar lo que hay en la carpeta del mes, quitando "." y "..".
// NOTA: el segundo parámetro ordena: SCANDIR_SORT_ASCENDING (por defecto) o SCANDIR_SORT_DESCENDING.
// ---------------------------------------------------------------------------------------------
titulo('scandir');
$contenido     = scandir($carpetaMes);
ver('scandir', $contenido);
ver('sin . ni ..', array_values(array_diff($contenido, ['.', '..'])));

// ---------------------------------------------------------------------------------------------
// glob — rutas que cumplen un PATRÓN (* cualquier cosa, ? un carácter, {a,b} alternativas)
// ENLACE: https://www.php.net/manual/es/function.glob.php
// ENUNCIADO EJEMPLO: obtener solo los ficheros .csv de la carpeta de copias y el más reciente.
// NOTA: devuelve RUTAS completas (no solo nombres) y ya ordenadas alfabéticamente.
// ---------------------------------------------------------------------------------------------
titulo('glob');
$csvs          = glob($carpetaMes . '/*.csv');
ver('número de copias CSV', count($csvs));
ver('la más reciente', basename(end($csvs)));
ver('todos los .txt de datos', array_map('basename', glob(rutaDatos('*.txt'))));

// ---------------------------------------------------------------------------------------------
// opendir / readdir / closedir — recorrer un directorio entrada a entrada
// ENLACE: https://www.php.net/manual/es/function.opendir.php
// ENLACE: https://www.php.net/manual/es/function.readdir.php
// ENLACE: https://www.php.net/manual/es/function.closedir.php
// ENUNCIADO EJEMPLO: calcular cuánto ocupan en total los ficheros de la carpeta "datos" (útil con
//   carpetas enormes, porque no carga todos los nombres en un array).
// NOTA: readdir devuelve false al terminar: compara con !== false (un fichero llamado "0" es falso).
// ---------------------------------------------------------------------------------------------
titulo('opendir / readdir / closedir');
$dir           = opendir(rutaDatos());
$total         = 0;
$ficheros      = 0;
while (($entrada = readdir($dir)) !== false) {
    $ruta = rutaDatos($entrada);
    if (is_file($ruta)) {
        $total += filesize($ruta);
        $ficheros++;
    }
}
closedir($dir);
ver('ficheros y bytes en datos', [$ficheros, $total]);

// ---------------------------------------------------------------------------------------------
// tempnam + sys_get_temp_dir / tmpfile — ficheros temporales
// ENLACE: https://www.php.net/manual/es/function.tempnam.php
// ENLACE: https://www.php.net/manual/es/function.sys-get-temp-dir.php
// ENLACE: https://www.php.net/manual/es/function.tmpfile.php
// ENUNCIADO EJEMPLO: generar un informe temporal con un nombre único que no choque con el de otro
//   usuario; y un temporal que se borra solo al cerrarlo.
// ---------------------------------------------------------------------------------------------
titulo('tempnam / tmpfile');
$temporal      = tempnam(sys_get_temp_dir(), 'informe_');
ver('carpeta temporal del sistema', sys_get_temp_dir());
ver('nombre único', basename($temporal));            // p.ej. "informe_A1B2" (en Windows solo usa 3 letras del prefijo: "inf2B6A.tmp")
file_put_contents($temporal, "datos temporales");
unlink($temporal);                                   // los temporales de tempnam hay que borrarlos

$tmp           = tmpfile();                          // ya abierto en modo w+
fwrite($tmp, "se borra al cerrar");
$rutaTmp       = stream_get_meta_data($tmp)['uri'];
ver('¿existe mientras está abierto?', file_exists($rutaTmp));   // true
fclose($tmp);
ver('¿existe tras fclose?', file_exists($rutaTmp));             // false

// ---------------------------------------------------------------------------------------------
// unlink / rmdir — borrar un fichero / borrar un directorio VACÍO
// ENLACE: https://www.php.net/manual/es/function.unlink.php
// ENLACE: https://www.php.net/manual/es/function.rmdir.php
// ENUNCIADO EJEMPLO: limpiar las copias de seguridad: rmdir falla si la carpeta tiene ficheros,
//   así que primero borramos su contenido con unlink (¡no hay papelera!).
// ---------------------------------------------------------------------------------------------
titulo('unlink / rmdir');
$fallo         = @rmdir($carpetaMes);
ver('rmdir con ficheros dentro', $fallo);            // false: no está vacía
foreach (glob($carpetaMes . '/*') as $fichero) {
    unlink($fichero);
}
ver('rmdir del mes', rmdir($carpetaMes));            // true
ver('rmdir del año', rmdir(dirname($carpetaMes)));   // true
ver('rmdir de copias', rmdir($base));                // true
ver('¿existe todavía copias?', is_dir($base));       // false

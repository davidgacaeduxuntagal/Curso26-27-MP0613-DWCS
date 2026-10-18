<?php
/*
 * e02-ficheros.php
 *  FICHEROS (2): abrir, escribir y cerrar con el PUNTERO. Los modos de apertura.
 *  Funciones: fopen (modos r, w, a, x, c), fwrite / fputs, fclose, error_get_last
 *
 *  Ejecuta en modo depuración (F10). Despliega la variable $fp en el panel VARIABLES: es un
 *  "resource" (recurso). Después de cada fwrite, abre el fichero en VSC para ver su contenido.
 */
require_once __DIR__ . '/utilidades.php';

$log = rutaDatos('accesos.log');
@unlink($log);                    // empezamos desde cero

// ---------------------------------------------------------------------------------------------
// fopen — abre un fichero y devuelve un recurso (el "puntero"), o false si no puede
// ENLACE: https://www.php.net/manual/es/function.fopen.php
// ENUNCIADO EJEMPLO: abrir el registro de accesos de una web en modo "w" (escritura).
// NOTA: modos principales:
//   r  leer (el fichero debe existir)          w  escribir: CREA o VACÍA el fichero
//   a  añadir al final (crea si no existe)     x  crear: falla si ya existe
//   c  escribir sin vaciar (crea si no existe) +  añade lectura/escritura: r+, w+, a+, x+, c+
// ---------------------------------------------------------------------------------------------
titulo('fopen (w)');
$fp            = fopen($log, 'w');
ver('$fp', $fp);                                   // resource(...) of type (stream)
ver('¿se ha abierto?', $fp !== false);

// ---------------------------------------------------------------------------------------------
// fwrite (alias: fputs) — escribe en la posición actual del puntero. Devuelve los bytes escritos
// ENLACE: https://www.php.net/manual/es/function.fwrite.php
// ENLACE: https://www.php.net/manual/es/function.fputs.php
// ENUNCIADO EJEMPLO: escribir dos líneas en el registro con la hora y el usuario.
// NOTA: no añade el salto de línea: tienes que escribirlo tú. El tercer parámetro (opcional)
//   limita el número de bytes que se escriben.
// ---------------------------------------------------------------------------------------------
titulo('fwrite / fputs');
$b1            = fwrite($fp, "[09:00:01] ana ha iniciado sesión\n");
$b2            = fputs($fp, "[09:05:12] brais ha iniciado sesión\n");   // fputs es exactamente lo mismo
$b3            = fwrite($fp, "[09:10:00] carmen-ESTO-NO-SE-ESCRIBE", 20);   // solo 20 bytes
fwrite($fp, "\n");
ver('bytes escritos', [$b1, $b2, $b3]);           // [35, 37, 20]  (la ó de "sesión" ocupa 2 bytes)

// ---------------------------------------------------------------------------------------------
// fclose — cierra el fichero y libera el recurso
// ENLACE: https://www.php.net/manual/es/function.fclose.php
// ENUNCIADO EJEMPLO: cerrar el registro. Después de cerrarlo, $fp ya no se puede usar.
// NOTA: PHP cierra los ficheros al terminar el script, pero ciérralos tú en cuanto acabes con
//   ellos. Fíjate en el panel VARIABLES: tras fclose, $fp pasa a ser "resource (closed)".
// ---------------------------------------------------------------------------------------------
titulo('fclose');
$cerrado       = fclose($fp);
ver('fclose', $cerrado);                          // true
ver('contenido', file_get_contents($log));

// ---------------------------------------------------------------------------------------------
// MODO "a" — añadir al final sin borrar lo anterior
// ENUNCIADO EJEMPLO: cada vez que un usuario entra, se añade una línea al registro.
// ---------------------------------------------------------------------------------------------
titulo('fopen (a)');
$fp            = fopen($log, 'a');
fwrite($fp, "[09:15:30] diego ha iniciado sesión\n");
fclose($fp);
ver('líneas tras añadir', count(file($log)));    // 4

// ---------------------------------------------------------------------------------------------
// MODO "w" otra vez — ¡vacía el fichero al abrirlo!
// ENUNCIADO EJEMPLO: ver el error típico de abrir con "w" queriendo añadir: se pierde todo.
// ---------------------------------------------------------------------------------------------
titulo('fopen (w) borra el contenido');
$fp            = fopen($log, 'w');               // en este momento el fichero ya tiene 0 bytes
clearstatcache();                                 // (para que filesize no use el dato en caché)
ver('tamaño justo después de abrir con w', filesize($log));   // 0
fclose($fp);

// ---------------------------------------------------------------------------------------------
// MODO "x" — crear un fichero SOLO si no existe
// ENUNCIADO EJEMPLO: crear la factura número 37; si ya existe, no la sobrescribimos.
// NOTA: si el fichero existe, fopen devuelve false y da un Warning. Con @ ocultamos el aviso y
//   consultamos el motivo con error_get_last().
// ---------------------------------------------------------------------------------------------
titulo('fopen (x)');
$factura       = rutaDatos('factura_0037.txt');
@unlink($factura);
$fp            = fopen($factura, 'x');           // la primera vez: se crea
fwrite($fp, "Factura 37: 3 cafés = 4,50 €\n");
fclose($fp);

$fp2           = @fopen($factura, 'x');          // la segunda vez: ya existe -> false
ver('segundo fopen con x', $fp2);                 // false
ver('motivo', error_get_last()['message']);       // "...failed to open stream: File exists"

// ---------------------------------------------------------------------------------------------
// MODO "r" con un fichero que no existe — el error más habitual
// ENUNCIADO EJEMPLO: intentar leer la configuración de un fichero que todavía no se ha creado,
//   comprobando el resultado antes de usarlo.
// ---------------------------------------------------------------------------------------------
titulo('fopen (r) de un fichero inexistente');
$fp            = @fopen(rutaDatos('config_que_no_existe.ini'), 'r');
if ($fp === false) {
    ver('error', error_get_last()['message']);    // "...No such file or directory"
} else {
    fclose($fp);
}

// ---------------------------------------------------------------------------------------------
// MODO "c" — escribir SIN vaciar el fichero (y crearlo si no existe)
// ENUNCIADO EJEMPLO: cambiar las primeras letras de un fichero sin borrar el resto.
// NOTA: escribe desde el PRINCIPIO y sobrescribe los bytes que hubiera (no inserta).
// ---------------------------------------------------------------------------------------------
titulo('fopen (c)');
$estado        = rutaDatos('estado.txt');
file_put_contents($estado, "ABIERTO hasta las 23:00\n");
$fp            = fopen($estado, 'c');
fwrite($fp, "CERRADO");                           // sobrescribe "ABIERTO" (7 letras cada uno)
fclose($fp);
ver('estado', file_get_contents($estado));        // "CERRADO hasta las 23:00\n"

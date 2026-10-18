<?php
/*
 * e03-ficheros_problema_solucion.php
 *  (FICHERO CON LAS SOLUCIONES)
 *  Problemas de: fgets, feof, fgetc, fread, fpassthru, stream_get_contents
 *  (repasa los ejemplos de ../e03-ficheros.php)
 *
 *  Resuelve cada problema donde se indica y ejecútalo en modo depuración para comprobarlo.
 */
require_once __DIR__ . '/../utilidades.php';

// Fichero de notas: DNI;nombre;nota (una línea por alumno)
$notas = rutaDatos('p03_notas.txt');
file_put_contents($notas, "12345678Z;Ana Pérez;8.5\n87654321X;Brais Otero;4\n11223344B;Carmen Núñez;9.25\n55667788C;Diego Lago;5\n");

// =============================================================================================
// PROBLEMA 1. Buscar la nota por DNI (práctica 2 del material base)
//  Recorre el fichero con fgets hasta encontrar el DNI buscado (y deja de leer en cuanto lo
//  encuentres). Muestra el nombre y la nota, o "no encontrado".
// =============================================================================================
titulo('Problema 1');
$buscado = "11223344B";
// completa aquí tu solución
// ---- SOLUCIÓN ----
$resultado = "no encontrado";
$fp = fopen($notas, 'r');
while (($linea = fgets($fp)) !== false) {
    [$dni, $nombre, $nota] = explode(';', rtrim($linea, "\r\n"));
    if ($dni === $buscado) {
        $resultado = "$nombre: $nota";
        break;
    }
}
fclose($fp);
ver("nota de $buscado", $resultado);     // "Carmen Núñez: 9.25"

// =============================================================================================
// PROBLEMA 2. Media y aprobados
//  Lee el fichero línea a línea y calcula la nota media y cuántos alumnos han aprobado (>= 5).
// =============================================================================================
titulo('Problema 2');
// completa aquí tu solución
// ---- SOLUCIÓN ----
$suma = 0;
$total = 0;
$aprobados = 0;
$fp = fopen($notas, 'r');
while (($linea = fgets($fp)) !== false) {
    $nota = (float) explode(';', rtrim($linea))[2];
    $suma += $nota;
    $total++;
    if ($nota >= 5) {
        $aprobados++;
    }
}
fclose($fp);
ver('media', round($suma / $total, 2));     // 6.69
ver('aprobados', $aprobados);               // 3

// =============================================================================================
// PROBLEMA 3. ¿Dónde está el error?
//  Este bucle cuenta las líneas del fichero, pero dice que hay 5 en lugar de 4. ¿Por qué?
//  Corrígelo en $correcto.
// =============================================================================================
titulo('Problema 3');
$fp = fopen($notas, 'r');
$mal = 0;
while (!feof($fp)) {
    fgets($fp);
    $mal++;
}
fclose($fp);
ver('líneas (mal)', $mal);       // 5
// ¿por qué falla? corrígelo
// ---- SOLUCIÓN ----
// feof solo es true DESPUÉS de que una lectura intente pasar del final: la quinta vuelta fgets
// devuelve false, pero el contador ya se ha incrementado.
$fp = fopen($notas, 'r');
$correcto = 0;
while (fgets($fp) !== false) {
    $correcto++;
}
fclose($fp);
ver('líneas (bien)', $correcto);  // 4

// =============================================================================================
// PROBLEMA 4. Contar saltos de línea con fread
//  Para un fichero muy grande no conviene usar file(). Cuenta las líneas leyendo en bloques de
//  8 bytes con fread y contando los "\n" de cada bloque (substr_count).
// =============================================================================================
titulo('Problema 4');
// completa aquí tu solución
// ---- SOLUCIÓN ----
$fp = fopen($notas, 'r');
$saltos = 0;
while (!feof($fp)) {
    $saltos += substr_count(fread($fp, 8), "\n");
}
fclose($fp);
ver('líneas', $saltos);           // 4

// =============================================================================================
// PROBLEMA 5. Cabecera y cuerpo de un mensaje
//  En p03_mensaje.txt, la primera línea es el asunto y el resto es el cuerpo. Lee el asunto con
//  fgets y el cuerpo con stream_get_contents. Cuenta los caracteres del cuerpo con fgetc...
//  ¿coincide con mb_strlen? ¿Por qué?
// =============================================================================================
titulo('Problema 5');
$mensaje = rutaDatos('p03_mensaje.txt');
file_put_contents($mensaje, "Reserva para el sábado\nMesa para 4 personas a las 21:30.\n¿Podéis guardarnos la de la terraza?\n");
// completa aquí tu solución
// ---- SOLUCIÓN ----
$fp     = fopen($mensaje, 'r');
$asunto = rtrim(fgets($fp));
$inicio = ftell($fp);
$cuerpo = stream_get_contents($fp);
fseek($fp, $inicio);
$conFgetc = 0;
while (fgetc($fp) !== false) {
    $conFgetc++;
}
fclose($fp);
ver('asunto', $asunto);
ver('fgetc / mb_strlen del cuerpo', [$conFgetc, mb_strlen($cuerpo)]);   // [73, 71]
// No coinciden: fgetc lee BYTES, y "¿" y "é" (de "Podéis") ocupan 2 bytes cada uno en UTF-8.

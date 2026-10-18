<?php
/*
 * 10_strings_formato_01.php
 *  STRINGS - FORMATO: generar texto con formato y leer texto con formato
 *  Funciones: printf, sprintf, vprintf, vsprintf, fprintf, vfprintf, sscanf, str_getcsv
 *
 *  Especificadores de formato más usados (%[relleno][-][ancho][.precisión]tipo):
 *      %s string    %d entero    %f decimal    %05d entero con ceros hasta 5 cifras
 *      %.2f 2 decimales    %10s ancho 10 alineado a la derecha    %-10s alineado a la izquierda
 *      %%  el símbolo %    %1$s, %2$s  argumentos por número de posición
 *
 *  Ejecuta en modo depuración paso a paso (F10).
 */
require_once __DIR__ . '/utilidades.php';

// ---------------------------------------------------------------------------------------------
// printf — escribe en la salida un texto con formato. Devuelve el nº de bytes escritos
// ENLACE: https://www.php.net/manual/es/function.printf.php
// ENUNCIADO EJEMPLO: imprimir las líneas de un ticket de compra con columnas alineadas y los
//   precios con 2 decimales.
// NOTA: el ancho (%-15s) se cuenta en BYTES: "Café molido" lleva una "é" (2 bytes) y su línea
//   sale desalineada un espacio. Compruébalo en la salida.
// ---------------------------------------------------------------------------------------------
titulo('printf');
$productos     = [["Pan", 2, 1.2], ["Leche", 6, 0.95], ["Café molido", 1, 4.5]];
foreach ($productos as [$nombre, $cantidad, $precio]) {
    printf("%-15s %3d x %6.2f = %7.2f" . PHP_EOL, $nombre, $cantidad, $precio, $cantidad * $precio);
}
$bytes         = printf("IVA: %d%%" . PHP_EOL, 21);   // %% escribe un % literal
ver('bytes escritos por printf', $bytes);

// ---------------------------------------------------------------------------------------------
// sprintf — igual que printf pero DEVUELVE el string en lugar de escribirlo
// ENLACE: https://www.php.net/manual/es/function.sprintf.php
// ENUNCIADO EJEMPLO: generar números de factura con el formato "F2026-00037", una hora "09:05"
//   y un color HTML en hexadecimal a partir de sus componentes RGB.
// ---------------------------------------------------------------------------------------------
titulo('sprintf');
$numFactura    = sprintf("F%d-%05d", 2026, 37);            // "F2026-00037"
$hora          = sprintf("%02d:%02d", 9, 5);                // "09:05"
$colorHtml     = sprintf("#%02X%02X%02X", 255, 128, 0);     // "#FF8000"
$orden         = sprintf('%2$s, %1$s', "Ana", "Pérez");     // "Pérez, Ana" (argumentos por posición)
ver('factura', $numFactura);
ver('hora', $hora);
ver('color', $colorHtml);
ver('argumentos por posición', $orden);

// ---------------------------------------------------------------------------------------------
// vprintf / vsprintf — como printf / sprintf pero los valores se pasan en un ARRAY
// ENLACE: https://www.php.net/manual/es/function.vprintf.php
// ENLACE: https://www.php.net/manual/es/function.vsprintf.php
// ENUNCIADO EJEMPLO: formatear una fecha guardada como array [año, mes, día] y mostrar una fila
//   de datos que viene de una consulta.
// ---------------------------------------------------------------------------------------------
titulo('vprintf / vsprintf');
$fechaArray    = [2026, 10, 16];
$fechaTexto    = vsprintf("%3\$02d/%2\$02d/%1\$04d", $fechaArray);   // "16/10/2026"
ver('vsprintf', $fechaTexto);

$fila          = ["12345678Z", "Ana Pérez", 8.25];
vprintf("DNI: %s | Alumna: %s | Nota: %.2f" . PHP_EOL, $fila);

// ---------------------------------------------------------------------------------------------
// fprintf / vfprintf — escriben con formato en un "stream" (fichero, memoria, salida...)
// ENLACE: https://www.php.net/manual/es/function.fprintf.php
// ENLACE: https://www.php.net/manual/es/function.vfprintf.php
// ENUNCIADO EJEMPLO: escribir líneas de un registro (log) con fecha. Para no crear ficheros,
//   escribimos en un stream de memoria (php://memory) y luego lo leemos.
// NOTA: con fopen('fichero.log', 'a') se escribiría en un fichero de verdad.
// ---------------------------------------------------------------------------------------------
titulo('fprintf / vfprintf');
$log           = fopen("php://memory", "w+");
fprintf($log, "[%s] %s: %s\n", "2026-10-16 10:00", "INFO", "Usuario ana ha iniciado sesión");
vfprintf($log, "[%s] %s: %s\n", ["2026-10-16 10:05", "ERROR", "Fallo al conectar con la BD"]);
rewind($log);                                  // volvemos al principio del stream para leerlo
$contenidoLog  = stream_get_contents($log);
fclose($log);
ver('contenido del log', $contenidoLog);

// ---------------------------------------------------------------------------------------------
// sscanf — lo contrario de sprintf: LEE valores de un string según un formato
// ENLACE: https://www.php.net/manual/es/function.sscanf.php
// ENUNCIADO EJEMPLO: extraer día, mes y año de una fecha "16/10/2026" y los datos de una
//   matrícula de coche "1234-BCD".
// NOTA: devuelve un array con los valores (ya convertidos a número con %d).
// ---------------------------------------------------------------------------------------------
titulo('sscanf');
$partes        = sscanf("16/10/2026", "%d/%d/%d");      // [16, 10, 2026] (enteros)
ver('sscanf fecha', $partes);
[$dia, $mes, $anio] = $partes;

$matricula     = sscanf("1234-BCD", "%4d-%3s");          // [1234, "BCD"]
ver('sscanf matrícula', $matricula);

// ---------------------------------------------------------------------------------------------
// str_getcsv — convierte una línea en formato CSV en un array
// ENLACE: https://www.php.net/manual/es/function.str-getcsv.php
// ENUNCIADO EJEMPLO: leer una línea exportada desde una hoja de cálculo, donde un campo
//   contiene el separador entre comillas ("Pérez, Ana").
// NOTA: a diferencia de explode, respeta los campos entre comillas. Parámetros: separador,
//   carácter de comillas y carácter de escape.
// ---------------------------------------------------------------------------------------------
titulo('str_getcsv');
$lineaCsv      = '12345678Z,"Pérez, Ana",DAW2,"8,25"';
$conExplode    = explode(",", $lineaCsv);                // ¡6 trozos, mal!
$conCsv        = str_getcsv($lineaCsv);                  // 4 campos, bien
ver('explode (mal)', $conExplode);
ver('str_getcsv', $conCsv);

$lineaExcel    = 'Ana;Pérez;"Calle Mayor; 5"';           // Excel en español usa ";"
$campos        = str_getcsv($lineaExcel, ";");
ver('str_getcsv con ";"', $campos);

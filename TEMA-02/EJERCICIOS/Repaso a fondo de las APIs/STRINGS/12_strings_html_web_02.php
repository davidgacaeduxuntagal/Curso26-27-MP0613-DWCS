<?php
/*
 * 12_strings_html_web_02.php
 *  STRINGS - HTML y WEB (2): barras invertidas (secuencias de escape)
 *  Funciones: addslashes, stripslashes, addcslashes, stripcslashes
 *
 *  Una barra invertida \ delante de un carácter le quita su significado especial (por ejemplo,
 *  \' es una comilla dentro de un texto delimitado por comillas). Estas funciones añaden o
 *  quitan esas barras.
 *
 *  Ejecuta en modo depuración paso a paso (F10). En el panel VARIABLES verás las barras \ reales
 *  que contiene cada string.
 */
require_once __DIR__ . '/utilidades.php';

// ---------------------------------------------------------------------------------------------
// addslashes — añade \ delante de las comillas simples, dobles, la barra \ y el byte nulo
// ENLACE: https://www.php.net/manual/es/function.addslashes.php
// ENUNCIADO EJEMPLO: generar el código de una cadena JavaScript o de un fichero de
//   configuración que contiene un texto con comillas.
// NOTA: ¡NO la uses para proteger consultas SQL! Para eso se usan consultas preparadas (PDO),
//   que veremos en el tema de bases de datos.
// ---------------------------------------------------------------------------------------------
titulo('addslashes');
$titulo        = "Rock'n'Roll \"en directo\"";
$escapado      = addslashes($titulo);                       // Rock\'n\'Roll \"en directo\"
ver('original', $titulo);
ver('addslashes', $escapado);

$lineaConfig   = "TITULO='" . addslashes("L'Oréal") . "'";  // TITULO='L\'Oréal'
ver('línea de configuración', $lineaConfig);

// ---------------------------------------------------------------------------------------------
// stripslashes — quita las barras añadidas por addslashes
// ENLACE: https://www.php.net/manual/es/function.stripslashes.php
// ENUNCIADO EJEMPLO: recuperar el texto original de un dato que se guardó con barras.
// NOTA: "\\" (dos barras) se convierte en una sola barra.
// ---------------------------------------------------------------------------------------------
titulo('stripslashes');
$recuperado    = stripslashes($escapado);
ver('stripslashes', $recuperado);
ver('¿igual al original?', $recuperado === $titulo);       // true

$ruta          = stripslashes('C:\\\\xampp\\\\htdocs');     // las dobles barras pasan a ser simples
ver('stripslashes de una ruta', $ruta);

// ---------------------------------------------------------------------------------------------
// addcslashes — añade \ delante de los caracteres QUE TÚ INDIQUES (al estilo del lenguaje C)
// ENLACE: https://www.php.net/manual/es/function.addcslashes.php
// ENUNCIADO EJEMPLO: escapar los comodines % y _ de lo que escribe un usuario para usarlo en una
//   búsqueda SQL con LIKE (si busca "50%" queremos el texto "50%", no "50 y lo que sea"); y ver
//   los caracteres de control (saltos de línea, tabuladores) de un texto.
// NOTA: se pueden indicar rangos: "A..Z". Los caracteres no imprimibles se escriben como \n, \t
//   o en octal.
// ---------------------------------------------------------------------------------------------
titulo('addcslashes');
$busqueda      = "descuento del 50%_ya";
$paraLike      = addcslashes($busqueda, "%_");              // "descuento del 50\%\_ya"
ver('addcslashes("%_")', $paraLike);

$conControl    = "Nombre:\tAna\nCiudad:\tVigo";
$visible       = addcslashes($conControl, "\0..\37");       // escapa los caracteres de control (0-31)
ver('caracteres de control visibles', $visible);           // 'Nombre:\tAna\nCiudad:\tVigo'

// ---------------------------------------------------------------------------------------------
// stripcslashes — interpreta las secuencias de escape estilo C (\n, \t, \x41, \101...)
// ENLACE: https://www.php.net/manual/es/function.stripcslashes.php
// ENUNCIADO EJEMPLO: un fichero de configuración guarda el texto "Línea1\nLínea2" con una barra
//   y una n literales (2 caracteres). Lo convertimos en un salto de línea de verdad.
// ---------------------------------------------------------------------------------------------
titulo('stripcslashes');
$leidoDeFichero = 'Línea1\nLínea2\tfin \x41\x42\x43';      // comillas simples: \n son 2 caracteres
$interpretado   = stripcslashes($leidoDeFichero);           // salto de línea, tabulador y "ABC"
ver('leído (literal)', $leidoDeFichero);
ver('stripcslashes', $interpretado);
ver('longitud antes / después', [strlen($leidoDeFichero), strlen($interpretado)]);

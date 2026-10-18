<?php
/*
 * 10_arrays_combinar_01.php
 *  ARRAYS - COMBINAR: unir arrays, sustituir valores y extraer columnas
 *  Funciones: array_merge (y el operador +), array_merge_recursive, array_replace,
 *             array_replace_recursive, array_combine, array_column
 *
 *  Ejecuta en modo depuración paso a paso (F10). Fíjate en qué pasa con las claves REPETIDAS en
 *  cada función: es la gran diferencia entre ellas.
 */
require_once __DIR__ . '/utilidades.php';

// ---------------------------------------------------------------------------------------------
// array_merge — une varios arrays en uno
// ENLACE: https://www.php.net/manual/es/function.array-merge.php
// ENUNCIADO EJEMPLO: (1) unir las listas de alumnos de dos grupos; (2) combinar la configuración
//   por defecto de una aplicación con la configuración del usuario (la del usuario gana).
// NOTA: claves NUMÉRICAS -> se añaden todas y se renumeran.
//       claves de TEXTO   -> si se repiten, gana el valor del ÚLTIMO array.
//   El operador + funciona distinto: con claves repetidas gana el PRIMERO y no renumera.
// ---------------------------------------------------------------------------------------------
titulo('array_merge');
$grupoA        = ["Ana", "Brais"];
$grupoB        = ["Carmen", "Diego"];
$todos         = array_merge($grupoA, $grupoB);          // [Ana, Brais, Carmen, Diego]
ver('array_merge de listas', $todos);
ver('con el operador +', $grupoA + $grupoB);            // [Ana, Brais]  <- ¡las claves 0 y 1 ya existían!

$porDefecto    = ['idioma' => "es", 'tema' => "claro", 'notificaciones' => true];
$delUsuario    = ['tema' => "oscuro"];
$config        = array_merge($porDefecto, $delUsuario); // tema => oscuro
ver('configuración final', $config);

$conMas        = $delUsuario + $porDefecto;             // mismo resultado con + (el primero gana)
ver('con + (usuario primero)', $conMas);

// ---------------------------------------------------------------------------------------------
// array_merge_recursive — une arrays y, si una clave de texto se repite, junta los valores en un array
// ENLACE: https://www.php.net/manual/es/function.array-merge-recursive.php
// ENUNCIADO EJEMPLO: juntar los errores de validación de dos formularios: si los dos tienen
//   errores en "email", queremos conservar ambos mensajes.
// ---------------------------------------------------------------------------------------------
titulo('array_merge_recursive');
$errores1      = ['email' => "obligatorio", 'nombre' => "demasiado corto"];
$errores2      = ['email' => "formato incorrecto"];
$conMerge      = array_merge($errores1, $errores2);              // email => "formato incorrecto" (se pierde uno)
$conRecursive  = array_merge_recursive($errores1, $errores2);    // email => ["obligatorio", "formato incorrecto"]
ver('array_merge', $conMerge);
ver('array_merge_recursive', $conRecursive);

// ---------------------------------------------------------------------------------------------
// array_replace — sustituye valores por CLAVE (también las numéricas, sin renumerar)
// ENLACE: https://www.php.net/manual/es/function.array-replace.php
// ENUNCIADO EJEMPLO: en un tablero de 3 en raya (posiciones 0 a 8), aplicar las jugadas
//   [4 => "X", 0 => "O"] sin cambiar el resto de posiciones.
// NOTA: con claves numéricas se comporta distinto que array_merge (que añadiría al final).
// ---------------------------------------------------------------------------------------------
titulo('array_replace');
$tablero       = array_fill(0, 9, " ");
$jugadas       = [4 => "X", 0 => "O"];
$conReplace    = array_replace($tablero, $jugadas);              // posiciones 0 y 4 cambiadas
$conMerge      = array_merge($tablero, $jugadas);                // ¡11 elementos!
ver('array_replace', implode("|", $conReplace));
ver('array_merge (mal)', count($conMerge));

// ---------------------------------------------------------------------------------------------
// array_replace_recursive — como array_replace pero entra en los arrays interiores
// ENLACE: https://www.php.net/manual/es/function.array-replace-recursive.php
// ENUNCIADO EJEMPLO: la configuración de la base de datos por defecto tiene host, puerto,
//   usuario...; el entorno de producción solo cambia el host y el usuario.
// ---------------------------------------------------------------------------------------------
titulo('array_replace_recursive');
$base          = ['db' => ['host' => "localhost", 'puerto' => 3306, 'usuario' => "root"], 'debug' => true];
$produccion    = ['db' => ['host' => "10.0.0.5", 'usuario' => "app"], 'debug' => false];
$noRecursivo   = array_replace($base, $produccion);              // ¡se pierde el puerto!
$recursivo     = array_replace_recursive($base, $produccion);    // conserva el puerto
ver('array_replace', $noRecursivo);
ver('array_replace_recursive', $recursivo);

// ---------------------------------------------------------------------------------------------
// array_combine — crea un array usando un array como CLAVES y otro como VALORES
// ENLACE: https://www.php.net/manual/es/function.array-combine.php
// ENUNCIADO EJEMPLO: la primera línea de un CSV tiene los nombres de las columnas; las siguientes,
//   los datos. Convertimos cada línea en un array asociativo.
// NOTA: los dos arrays deben tener el MISMO número de elementos (si no, lanza un ValueError).
// ---------------------------------------------------------------------------------------------
titulo('array_combine');
$cabecera      = ["dni", "nombre", "nota"];
$fila          = ["12345678Z", "Ana", "8.5"];
$alumno        = array_combine($cabecera, $fila);          // ['dni' => ..., 'nombre' => 'Ana', 'nota' => '8.5']
ver('array_combine', $alumno);

// ---------------------------------------------------------------------------------------------
// array_column — extrae una "columna" de un array de arrays (por ejemplo, del resultado de una consulta)
// ENLACE: https://www.php.net/manual/es/function.array-column.php
// ENUNCIADO EJEMPLO: de una lista de usuarios, obtener solo los emails; un array id => nombre
//   para un <select>; e indexar los usuarios por su id.
// NOTA: parámetros (array, columna_valor, columna_clave). Con null como columna_valor devuelve
//   las filas completas.
// ---------------------------------------------------------------------------------------------
titulo('array_column');
$usuarios      = [
    ['id' => 7,  'nombre' => "Ana",    'email' => "ana@x.gal"],
    ['id' => 12, 'nombre' => "Brais",  'email' => "brais@x.gal"],
    ['id' => 15, 'nombre' => "Carmen", 'email' => "carmen@x.gal"],
];
$emails        = array_column($usuarios, 'email');            // [ana@x.gal, brais@x.gal, carmen@x.gal]
$opciones      = array_column($usuarios, 'nombre', 'id');     // [7 => Ana, 12 => Brais, 15 => Carmen]
$porId         = array_column($usuarios, null, 'id');         // [7 => [...], 12 => [...], 15 => [...]]
ver('emails', $emails);
ver('opciones del <select>', $opciones);
ver('email del usuario 12', $porId[12]['email']);

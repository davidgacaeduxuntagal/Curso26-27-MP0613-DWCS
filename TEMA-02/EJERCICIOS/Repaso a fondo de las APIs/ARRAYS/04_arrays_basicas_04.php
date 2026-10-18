<?php
/*
 * 04_arrays_basicas_04.php
 *  ARRAYS - BÁSICAS (4): trozos de un array, azar y cálculos
 *  Funciones: array_slice, array_splice, array_chunk, array_rand, shuffle, array_sum, array_product
 *
 *  Ejecuta en modo depuración paso a paso (F10). Distingue qué funciones DEVUELVEN un array nuevo
 *  y cuáles MODIFICAN el array original (se pasa por referencia).
 */
require_once __DIR__ . '/utilidades.php';

// ---------------------------------------------------------------------------------------------
// array_slice — devuelve un trozo del array (NO modifica el original)
// ENLACE: https://www.php.net/manual/es/function.array-slice.php
// ENUNCIADO EJEMPLO: paginación de resultados: mostrar los productos de la página 2 si se
//   muestran 3 por página; obtener los 2 últimos mensajes.
// NOTA: parámetros (array, inicio, longitud, conservar_claves). Las claves numéricas se
//   renumeran salvo que el 4º parámetro sea true. Inicio negativo = desde el final.
// ---------------------------------------------------------------------------------------------
titulo('array_slice');
$productos     = ["ratón", "teclado", "monitor", "webcam", "altavoces", "micrófono", "alfombrilla"];
$porPagina     = 3;
$pagina        = 2;
$enPagina      = array_slice($productos, ($pagina - 1) * $porPagina, $porPagina);   // webcam, altavoces, micrófono
ver('página 2', $enPagina);

$ultimos       = array_slice($productos, -2);                 // micrófono, alfombrilla
$conClaves     = array_slice($productos, -2, null, true);     // [5 => micrófono, 6 => alfombrilla]
ver('2 últimos', $ultimos);
ver('2 últimos conservando claves', $conClaves);

// ---------------------------------------------------------------------------------------------
// array_splice — QUITA un trozo del array original (y opcionalmente pone otros elementos en su lugar)
// ENLACE: https://www.php.net/manual/es/function.array-splice.php
// ENUNCIADO EJEMPLO: en una lista de tareas, eliminar la tarea 2 y sustituirla por dos subtareas;
//   insertar una tarea en la posición 1 sin borrar nada.
// NOTA: devuelve los elementos eliminados. Con longitud 0 INSERTA.
// ---------------------------------------------------------------------------------------------
titulo('array_splice');
$tareas        = ["diseñar BD", "crear formularios", "validar datos", "publicar"];
$quitadas      = array_splice($tareas, 1, 1, ["formulario alta", "formulario edición"]);
ver('eliminadas', $quitadas);                     // ["crear formularios"]
ver('tareas', $tareas);                           // diseñar BD, formulario alta, formulario edición, validar datos, publicar

array_splice($tareas, 1, 0, "hacer bocetos");     // insertar en la posición 1
ver('tras insertar', $tareas);

// ---------------------------------------------------------------------------------------------
// array_chunk — divide un array en trozos de N elementos
// ENLACE: https://www.php.net/manual/es/function.array-chunk.php
// ENUNCIADO EJEMPLO: repartir a los alumnos en grupos de 3 para un proyecto; mostrar una galería
//   de fotos en filas de 4.
// NOTA: el último trozo puede tener menos elementos. Con true conserva las claves originales.
// ---------------------------------------------------------------------------------------------
titulo('array_chunk');
$alumnos       = ["Ana", "Brais", "Carmen", "Diego", "Eva", "Fran", "Gael"];
$grupos        = array_chunk($alumnos, 3);         // [[Ana, Brais, Carmen], [Diego, Eva, Fran], [Gael]]
ver('grupos de 3', $grupos);
ver('número de grupos', count($grupos));           // 3

// ---------------------------------------------------------------------------------------------
// array_rand — elige al azar una o varias CLAVES de un array
// ENLACE: https://www.php.net/manual/es/function.array-rand.php
// ENUNCIADO EJEMPLO: elegir al azar el alumno que sale a la pizarra y 2 ganadores de un sorteo.
// NOTA: devuelve CLAVES, no valores. Si se piden varias, devuelve un array de claves en el
//   orden original. Cada ejecución da un resultado distinto.
// ---------------------------------------------------------------------------------------------
titulo('array_rand');
$clave         = array_rand($alumnos);             // p.ej. 4
$elegido       = $alumnos[$clave];                 // p.ej. "Eva"
ver('sale a la pizarra', $elegido);

$clavesGanadores = array_rand($alumnos, 2);        // p.ej. [1, 5]
$ganadores     = [$alumnos[$clavesGanadores[0]], $alumnos[$clavesGanadores[1]]];
ver('ganadores del sorteo', $ganadores);

// ---------------------------------------------------------------------------------------------
// shuffle — desordena el array al azar (MODIFICA el original y renumera las claves)
// ENLACE: https://www.php.net/manual/es/function.shuffle.php
// ENUNCIADO EJEMPLO: barajar las preguntas de un examen tipo test para que cada alumno las vea en
//   distinto orden; barajar una baraja de cartas.
// NOTA: devuelve true; el resultado queda en la propia variable. Pierde las claves de texto.
// ---------------------------------------------------------------------------------------------
titulo('shuffle');
$preguntas     = ["P1", "P2", "P3", "P4", "P5"];
shuffle($preguntas);
ver('preguntas barajadas', $preguntas);

$cartas        = [];
foreach (["oros", "copas"] as $palo) {
    foreach (range(1, 3) as $numero) {
        $cartas[] = "$numero de $palo";
    }
}
shuffle($cartas);
ver('mano de 2 cartas', array_slice($cartas, 0, 2));

// ---------------------------------------------------------------------------------------------
// array_sum / array_product — suma / producto de los valores
// ENLACE: https://www.php.net/manual/es/function.array-sum.php
// ENLACE: https://www.php.net/manual/es/function.array-product.php
// ENUNCIADO EJEMPLO: total de un ticket de compra y nota media de un alumno; el factorial de 5
//   (1·2·3·4·5) y el número de combinaciones de una contraseña de 4 posiciones.
// NOTA: array_sum de un array vacío es 0; array_product de un array vacío es 1.
// ---------------------------------------------------------------------------------------------
titulo('array_sum / array_product');
$ticket        = [2.40, 5.70, 4.50];
$total         = array_sum($ticket);                 // 12.6
ver('total del ticket', $total);

$notas         = [6.5, 8, 7.25, 9];
$media         = array_sum($notas) / count($notas);  // 7.6875
ver('nota media', round($media, 2));

$factorial     = array_product(range(1, 5));         // 120
$combinaciones = array_product([10, 10, 10, 10]);    // 10000 (4 dígitos)
ver('5!', $factorial);
ver('combinaciones de un PIN de 4 dígitos', $combinaciones);

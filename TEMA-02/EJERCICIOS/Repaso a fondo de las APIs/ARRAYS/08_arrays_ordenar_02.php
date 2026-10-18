<?php
/*
 * 08_arrays_ordenar_02.php
 *  ARRAYS - ORDENAR (2): ordenar con criterio propio, orden natural y varias columnas
 *  Funciones: usort, uasort, uksort, natsort, natcasesort, array_multisort
 *
 *  Las funciones u... (user) reciben una FUNCIÓN DE COMPARACIÓN escrita por nosotros:
 *      function ($a, $b) { return ...; }   // < 0 si $a va antes, 0 si da igual, > 0 si $a va después
 *  El operador "nave espacial" <=> hace justo eso:  $a <=> $b  (de menor a mayor)
 *                                                   $b <=> $a  (de mayor a menor)
 *
 *  Ejecuta en modo depuración paso a paso (F11 = Step Into) y entra en la función de comparación:
 *  verás qué parejas ($a, $b) compara PHP y en qué orden.
 */
require_once __DIR__ . '/utilidades.php';

$alumnos = [
    ['nombre' => "Uxía",   'curso' => "DAW2", 'nota' => 8.5],
    ['nombre' => "Brais",  'curso' => "DAW1", 'nota' => 6],
    ['nombre' => "Carmen", 'curso' => "DAW2", 'nota' => 9.75],
    ['nombre' => "Antón",  'curso' => "DAW1", 'nota' => 6],
];

// ---------------------------------------------------------------------------------------------
// usort — ordena por VALOR con una función de comparación propia (renumera las claves)
// ENLACE: https://www.php.net/manual/es/function.usort.php
// ENUNCIADO EJEMPLO: ordenar un listado de alumnos (array de arrays) por nota de mayor a menor y,
//   a igualdad de nota, por nombre.
// NOTA: puedes pasar una función anónima, una función flecha fn() o el nombre de una función.
// ---------------------------------------------------------------------------------------------
titulo('usort');
$porNota       = $alumnos;
usort($porNota, function ($a, $b) {
    return $b['nota'] <=> $a['nota'];                 // de mayor a menor nota
});
ver('por nota', array_column($porNota, 'nota', 'nombre'));

$porNotaYNombre = $alumnos;
usort($porNotaYNombre, fn($a, $b) => [$b['nota'], $a['nombre']] <=> [$a['nota'], $b['nombre']]);
ver('por nota y, si empatan, por nombre', array_column($porNotaYNombre, 'nombre'));   // Carmen, Uxía, Antón, Brais

$palabras      = ["sol", "luna", "estrellas", "mar"];
usort($palabras, fn($a, $b) => strlen($a) <=> strlen($b));   // por longitud
ver('por longitud', $palabras);                              // sol, mar, luna, estrellas

// ---------------------------------------------------------------------------------------------
// uasort — como usort pero CONSERVANDO las claves
// ENLACE: https://www.php.net/manual/es/function.uasort.php
// ENUNCIADO EJEMPLO: un inventario código => [nombre, stock]: ordenarlo por stock (de menos a
//   más) sin perder el código de cada producto.
// ---------------------------------------------------------------------------------------------
titulo('uasort');
$inventario    = [
    'P01' => ['nombre' => "Ratón",   'stock' => 25],
    'P02' => ['nombre' => "Teclado", 'stock' => 3],
    'P03' => ['nombre' => "Monitor", 'stock' => 10],
];
uasort($inventario, fn($a, $b) => $a['stock'] <=> $b['stock']);
ver('inventario por stock', $inventario);              // P02, P03, P01 (claves conservadas)

// ---------------------------------------------------------------------------------------------
// uksort — ordena por CLAVE con una función de comparación propia
// ENLACE: https://www.php.net/manual/es/function.uksort.php
// ENUNCIADO EJEMPLO: las claves son tallas de ropa ("S", "M", "L", "XL") y queremos ordenarlas
//   por su tamaño real, no alfabéticamente.
// ---------------------------------------------------------------------------------------------
titulo('uksort');
$stockTallas   = ['XL' => 4, 'S' => 10, 'L' => 7, 'M' => 12];
$ordenTallas   = ['S' => 1, 'M' => 2, 'L' => 3, 'XL' => 4];
uksort($stockTallas, fn($a, $b) => $ordenTallas[$a] <=> $ordenTallas[$b]);
ver('tallas en orden', $stockTallas);                  // S, M, L, XL

// ---------------------------------------------------------------------------------------------
// natsort — orden "natural" de los valores (los números dentro del texto se comparan como números)
// ENLACE: https://www.php.net/manual/es/function.natsort.php
// ENUNCIADO EJEMPLO: ordenar los ficheros de una carpeta "img1.png", "img10.png", "img2.png"...
// NOTA: CONSERVA las claves. Distingue mayúsculas.
// ---------------------------------------------------------------------------------------------
titulo('natsort');
$ficheros      = ["img12.png", "img10.png", "img2.png", "img1.png"];
$normal        = $ficheros;
sort($normal);                                         // img1, img10, img12, img2 (orden de texto)
natsort($ficheros);                                    // img1, img2, img10, img12
ver('con sort', $normal);
ver('con natsort (claves conservadas)', $ficheros);

// ---------------------------------------------------------------------------------------------
// natcasesort — orden natural SIN distinguir mayúsculas
// ENLACE: https://www.php.net/manual/es/function.natcasesort.php
// ENUNCIADO EJEMPLO: ordenar los apartados de un documento escritos con mayúsculas y minúsculas
//   mezcladas.
// ---------------------------------------------------------------------------------------------
titulo('natcasesort');
$apartados     = ["Apartado10", "apartado2", "APARTADO1"];
natcasesort($apartados);
ver('natcasesort', $apartados);                        // APARTADO1, apartado2, Apartado10

// ---------------------------------------------------------------------------------------------
// array_multisort — ordena VARIOS arrays a la vez (o un array por varias "columnas")
// ENLACE: https://www.php.net/manual/es/function.array-multisort.php
// ENUNCIADO EJEMPLO: ordenar los alumnos por curso (ascendente) y, dentro de cada curso, por
//   nota (descendente). Extraemos cada columna con array_column y ordenamos el listado con ellas.
// NOTA: el último array recibe el orden de los anteriores. Se modifican TODOS los arrays.
//   Si hay empate en todas las columnas (Brais y Antón: DAW1 y 6), decide la comparación del
//   último array: compara las filas elemento a elemento y "Antón" < "Brais".
// ---------------------------------------------------------------------------------------------
titulo('array_multisort');
$cursos        = array_column($alumnos, 'curso');
$notas         = array_column($alumnos, 'nota');
$listado       = $alumnos;
array_multisort($cursos, SORT_ASC, $notas, SORT_DESC, $listado);
foreach ($listado as $a) {
    echo "{$a['curso']}  {$a['nota']}  {$a['nombre']}", PHP_EOL;   // DAW1 6 Antón / DAW1 6 Brais / DAW2 9.75 Carmen / DAW2 8.5 Uxía
}

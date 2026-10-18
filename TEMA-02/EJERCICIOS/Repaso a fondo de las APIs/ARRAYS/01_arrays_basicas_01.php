<?php
/*
 * 01_arrays_basicas_01.php
 *  ARRAYS - BÁSICAS (1): crear arrays, contar elementos y pasar de variables a arrays (y al revés)
 *  Funciones: array, list, count / sizeof, range, array_fill, array_fill_keys, array_pad, compact, extract
 *
 *  Cómo trabajar este fichero:
 *    1. Pon un punto de ruptura en la primera línea de código de cada ejemplo.
 *    2. Ejecuta en modo depuración (F5) y avanza línea a línea con F10.
 *    3. Despliega los arrays en el panel VARIABLES: fíjate en las CLAVES y en los VALORES.
 */
require_once __DIR__ . '/utilidades.php';

// ---------------------------------------------------------------------------------------------
// array — crea un array (equivale a la sintaxis corta [...])
// ENLACE: https://www.php.net/manual/es/function.array.php
// ENUNCIADO EJEMPLO: guardar los módulos de un curso (array indexado, claves 0, 1, 2...) y los
//   datos de un alumno (array asociativo, claves de texto).
// NOTA: si no se indica la clave, PHP usa la siguiente clave entera libre. Si se repite una
//   clave, el último valor sustituye al anterior.
// ---------------------------------------------------------------------------------------------
titulo('array');
$modulos       = array("DWCS", "DWCC", "DIW");                // [0 => "DWCS", 1 => "DWCC", 2 => "DIW"]
$alumno        = ['nombre' => "Ana", 'edad' => 19, 'repetidora' => false];
$saltos        = [5 => "cinco", "seis", 10 => "diez", "once"]; // claves 5, 6, 10, 11
$repetida      = ['color' => "rojo", 'color' => "azul"];       // ['color' => "azul"]
ver('indexado', $modulos);
ver('asociativo', $alumno);
ver('claves automáticas tras una clave fija', $saltos);
ver('clave repetida', $repetida);

// ---------------------------------------------------------------------------------------------
// list — asigna los elementos de un array a varias variables de golpe (desestructuración)
// ENLACE: https://www.php.net/manual/es/function.list.php
// ENUNCIADO EJEMPLO: una función devuelve las coordenadas [latitud, longitud] de una ciudad y
//   las guardamos en dos variables; también con claves de un array asociativo.
// NOTA: desde PHP 7.1 se puede escribir con corchetes: [$a, $b] = $array;
//   Se pueden saltar elementos dejando el hueco vacío: [, $segundo] = $array;
// ---------------------------------------------------------------------------------------------
titulo('list');
$vigo          = [42.2406, -8.7207];
list($latitud, $longitud) = $vigo;
ver('latitud / longitud', [$latitud, $longitud]);

[, $soloLongitud] = $vigo;                                    // saltamos el primero
ver('solo la longitud', $soloLongitud);

['nombre' => $nombre, 'edad' => $edad] = $alumno;             // con claves
ver('nombre y edad', "$nombre tiene $edad años");

// ---------------------------------------------------------------------------------------------
// count (alias: sizeof) — número de elementos de un array
// ENLACE: https://www.php.net/manual/es/function.count.php
// ENLACE: https://www.php.net/manual/es/function.sizeof.php
// ENUNCIADO EJEMPLO: saber cuántos productos hay en un carrito, y cuántos productos hay en
//   total en un inventario organizado por categorías (array de arrays).
// NOTA: con COUNT_RECURSIVE cuenta también los elementos de los arrays interiores (¡y los
//   propios arrays interiores!). Desde PHP 8, count() de algo que no es array da un error.
// ---------------------------------------------------------------------------------------------
titulo('count / sizeof');
$carrito       = ["pan", "leche", "huevos"];
$numProductos  = count($carrito);                             // 3
$conSizeof     = sizeof($carrito);                            // 3 (alias)
ver('count', $numProductos);
ver('sizeof', $conSizeof);

$inventario    = ['frutas' => ["manzana", "pera"], 'verduras' => ["tomate", "lechuga", "cebolla"]];
$categorias    = count($inventario);                          // 2
$recursivo     = count($inventario, COUNT_RECURSIVE);         // 7 = 2 categorías + 5 productos
ver('categorías', $categorias);
ver('count recursivo', $recursivo);

// ---------------------------------------------------------------------------------------------
// range — crea un array con una secuencia de números o letras
// ENLACE: https://www.php.net/manual/es/function.range.php
// ENUNCIADO EJEMPLO: generar los años para un desplegable de fecha de nacimiento, los números
//   pares hasta 20 y las letras de la A a la F.
// NOTA: el tercer parámetro es el paso. Si el inicio es mayor que el final, cuenta hacia atrás.
// ---------------------------------------------------------------------------------------------
titulo('range');
$anios         = range(2010, 2000);                           // 2010, 2009, ..., 2000 (hacia atrás)
$pares         = range(0, 20, 2);                             // 0, 2, 4, ..., 20
$letras        = range('A', 'F');                             // A, B, C, D, E, F
ver('años', $anios);
ver('pares', implode(",", $pares));
ver('letras', implode("", $letras));

// ---------------------------------------------------------------------------------------------
// array_fill — crea un array con N elementos iguales, empezando en una clave
// ENLACE: https://www.php.net/manual/es/function.array-fill.php
// ENUNCIADO EJEMPLO: inicializar las 7 casillas de una semana sin reservas ("libre"), con las
//   claves 1 (lunes) a 7 (domingo).
// ---------------------------------------------------------------------------------------------
titulo('array_fill');
$semana        = array_fill(1, 7, "libre");                   // [1 => "libre", ..., 7 => "libre"]
$semana[3]     = "reservado";                                 // reservamos el miércoles
ver('array_fill', $semana);

$tablero       = array_fill(0, 3, array_fill(0, 3, "-"));     // matriz 3 x 3
ver('tablero 3x3', $tablero);

// ---------------------------------------------------------------------------------------------
// array_fill_keys — crea un array con las claves indicadas, todas con el mismo valor
// ENLACE: https://www.php.net/manual/es/function.array-fill-keys.php
// ENUNCIADO EJEMPLO: preparar un contador de votos a 0 para cada candidato de una votación.
// ---------------------------------------------------------------------------------------------
titulo('array_fill_keys');
$candidatos    = ["Ana", "Brais", "Carmen"];
$votos         = array_fill_keys($candidatos, 0);             // ['Ana' => 0, 'Brais' => 0, 'Carmen' => 0]
$votos['Brais']++;
ver('array_fill_keys', $votos);

// ---------------------------------------------------------------------------------------------
// array_pad — rellena un array hasta un tamaño con un valor
// ENLACE: https://www.php.net/manual/es/function.array-pad.php
// ENUNCIADO EJEMPLO: un equipo de fútbol sala necesita 5 jugadores; completamos la alineación
//   con "por asignar". Con tamaño negativo rellena por la IZQUIERDA.
// NOTA: si el array ya tiene ese tamaño o más, no cambia.
// ---------------------------------------------------------------------------------------------
titulo('array_pad');
$jugadores     = ["Uxía", "Xoan", "Iria"];
$alineacion    = array_pad($jugadores, 5, "por asignar");     // 5 elementos
$porDelante    = array_pad([7, 8], -4, 0);                    // [0, 0, 7, 8]
ver('array_pad', $alineacion);
ver('array_pad negativo', $porDelante);

// ---------------------------------------------------------------------------------------------
// compact — crea un array asociativo a partir de VARIABLES (clave = nombre de la variable)
// ENLACE: https://www.php.net/manual/es/function.compact.php
// ENUNCIADO EJEMPLO: tras leer los datos de un formulario en variables sueltas, juntarlos en un
//   array para guardarlos o pasarlos a una función.
// NOTA: se le pasan los NOMBRES de las variables como strings (sin $).
// ---------------------------------------------------------------------------------------------
titulo('compact');
$usuario       = "ana";
$email         = "ana@iesrodeira.gal";
$rol           = "alumna";
$registro      = compact('usuario', 'email', 'rol');          // ['usuario' => 'ana', 'email' => ..., 'rol' => ...]
ver('compact', $registro);

// ---------------------------------------------------------------------------------------------
// extract — lo contrario de compact: crea una VARIABLE por cada clave del array
// ENLACE: https://www.php.net/manual/es/function.extract.php
// ENUNCIADO EJEMPLO: una plantilla HTML usa variables $titulo y $precio; las creamos a partir del
//   array de un producto. Observa en el panel VARIABLES cómo aparecen variables nuevas.
// NOTA: devuelve el número de variables creadas. ¡NUNCA la uses con datos del usuario
//   (extract($_POST))!: podría sobrescribir cualquier variable de tu programa.
// ---------------------------------------------------------------------------------------------
titulo('extract');
$producto      = ['titulo' => "Teclado mecánico", 'precio' => 59.9];
$creadas       = extract($producto);                          // crea $titulo y $precio
ver('variables creadas', $creadas);                           // 2
ver('$titulo y $precio', "$titulo: $precio €");

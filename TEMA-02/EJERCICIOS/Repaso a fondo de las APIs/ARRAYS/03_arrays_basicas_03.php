<?php
/*
 * 03_arrays_basicas_03.php
 *  ARRAYS - BÁSICAS (3): trabajar con las CLAVES y con los valores repetidos
 *  Funciones: array_keys, array_flip, array_change_key_case, array_is_list, array_count_values, array_unique
 *
 *  Ejecuta en modo depuración paso a paso (F10). En cada ejemplo compara las CLAVES del array de
 *  entrada con las del array resultado.
 */
require_once __DIR__ . '/utilidades.php';

// ---------------------------------------------------------------------------------------------
// array_keys — devuelve las claves de un array (o solo las claves de un valor concreto)
// ENLACE: https://www.php.net/manual/es/function.array-keys.php
// ENUNCIADO EJEMPLO: obtener la lista de campos de un formulario recibido y saber qué alumnos
//   han sacado un 10.
// NOTA: el segundo parámetro filtra por valor; el tercero (true) hace la comparación estricta (===).
// ---------------------------------------------------------------------------------------------
titulo('array_keys');
$formulario    = ['nombre' => "Ana", 'email' => "ana@x.gal", 'edad' => "19"];
$campos        = array_keys($formulario);            // ["nombre", "email", "edad"]
ver('campos recibidos', $campos);

$notas         = ['Ana' => 10, 'Brais' => 7, 'Carmen' => 10, 'Diego' => "10"];
$conDiez       = array_keys($notas, 10);             // ["Ana", "Carmen", "Diego"] ("10" == 10)
$conDiezEstricto = array_keys($notas, 10, true);     // ["Ana", "Carmen"]          ("10" !== 10)
ver('alumnos con un 10', $conDiez);
ver('alumnos con un 10 (estricto)', $conDiezEstricto);

// ---------------------------------------------------------------------------------------------
// array_flip — intercambia claves y valores
// ENLACE: https://www.php.net/manual/es/function.array-flip.php
// ENUNCIADO EJEMPLO: tenemos un array código => provincia y necesitamos buscar el código a partir
//   del nombre de la provincia (provincia => código).
// NOTA: los valores deben ser enteros o strings (para poder ser claves). Si hay valores
//   repetidos, solo se conserva el último.
// ---------------------------------------------------------------------------------------------
titulo('array_flip');
$codigos       = [15 => "A Coruña", 27 => "Lugo", 32 => "Ourense", 36 => "Pontevedra"];
$porNombre     = array_flip($codigos);               // ["A Coruña" => 15, "Lugo" => 27, ...]
ver('array_flip', $porNombre);
ver('código de Lugo', $porNombre['Lugo']);           // 27

$conRepetidos  = array_flip(['a' => 1, 'b' => 2, 'c' => 1]);   // [1 => "c", 2 => "b"]
ver('array_flip con repetidos', $conRepetidos);

// ---------------------------------------------------------------------------------------------
// array_change_key_case — pasa todas las claves a minúsculas (CASE_LOWER) o mayúsculas (CASE_UPPER)
// ENLACE: https://www.php.net/manual/es/function.array-change-key-case.php
// ENUNCIADO EJEMPLO: las cabeceras de una petición HTTP pueden llegar como "Content-Type" o
//   "content-type". Las normalizamos a minúsculas para buscarlas siempre igual.
// ---------------------------------------------------------------------------------------------
titulo('array_change_key_case');
$cabeceras     = ['Content-Type' => "text/html", 'ACCEPT' => "*/*", 'User-Agent' => "Firefox"];
$normalizadas  = array_change_key_case($cabeceras, CASE_LOWER);
ver('cabeceras normalizadas', $normalizadas);
ver('content-type', $normalizadas['content-type']);

// ---------------------------------------------------------------------------------------------
// array_is_list — ¿es un array "lista"? (claves 0, 1, 2... consecutivas y en orden) (PHP 8.1)
// ENLACE: https://www.php.net/manual/es/function.array-is-list.php
// ENUNCIADO EJEMPLO: al convertir a JSON, una lista se convierte en [ ] y un array asociativo en
//   { }. Comprobamos qué tipo de array tenemos, por ejemplo después de un unset.
// ---------------------------------------------------------------------------------------------
titulo('array_is_list');
$lista         = ["rojo", "verde", "azul"];
$asociativo    = ['r' => 255, 'g' => 0];
$conHueco      = $lista;
unset($conHueco[1]);                                  // claves 0 y 2
ver('¿lista? ["rojo","verde","azul"]', array_is_list($lista));       // true
ver('¿lista? asociativo', array_is_list($asociativo));               // false
ver('¿lista? tras unset', array_is_list($conHueco));                 // false
ver('JSON de la lista', json_encode($lista));                        // ["rojo","verde","azul"]
ver('JSON tras unset', json_encode($conHueco));                      // {"0":"rojo","2":"azul"}

// ---------------------------------------------------------------------------------------------
// array_count_values — cuenta cuántas veces aparece cada valor
// ENLACE: https://www.php.net/manual/es/function.array-count-values.php
// ENUNCIADO EJEMPLO: recuento de los votos de una encuesta sobre el lenguaje favorito.
// NOTA: solo cuenta valores enteros o strings. Distingue mayúsculas ("PHP" y "php" son distintos).
// ---------------------------------------------------------------------------------------------
titulo('array_count_values');
$respuestas    = ["PHP", "Java", "PHP", "Python", "PHP", "Java"];
$recuento      = array_count_values($respuestas);    // ['PHP' => 3, 'Java' => 2, 'Python' => 1]
ver('recuento', $recuento);

// ---------------------------------------------------------------------------------------------
// array_unique — elimina los valores repetidos (se queda con la PRIMERA aparición y su clave)
// ENLACE: https://www.php.net/manual/es/function.array-unique.php
// ENUNCIADO EJEMPLO: obtener la lista de ciudades distintas de las que proceden los alumnos.
// NOTA: CONSERVA las claves originales (pueden quedar huecos). Compara como strings: 4 y "4"
//   se consideran iguales.
// ---------------------------------------------------------------------------------------------
titulo('array_unique');
$ciudades      = ["Vigo", "Lugo", "Vigo", "Ourense", "Lugo"];
$distintas     = array_unique($ciudades);           // [0 => Vigo, 1 => Lugo, 3 => Ourense]
ver('ciudades distintas', $distintas);
ver('número de ciudades distintas', count($distintas));   // 3

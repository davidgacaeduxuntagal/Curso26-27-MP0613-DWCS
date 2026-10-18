<?php
/*
 * 09_arrays_iterar_01.php
 *  ARRAYS - ITERAR: aplicar una función a cada elemento (programación funcional)
 *  Funciones: array_map, array_filter, array_reduce, array_values, array_walk, array_walk_recursive
 *
 *  Estas funciones sustituyen muchos bucles foreach:
 *      array_map    -> TRANSFORMAR cada elemento (devuelve un array nuevo del mismo tamaño)
 *      array_filter -> QUEDARSE con los elementos que cumplen una condición
 *      array_reduce -> REDUCIR todos los elementos a UN valor (suma, máximo, texto...)
 *      array_walk   -> RECORRER (y, si se quiere, modificar el array original)
 *
 *  Ejecuta en modo depuración con F11 (Step Into) para entrar en las funciones anónimas y ver
 *  qué elemento recibe en cada llamada.
 */
require_once __DIR__ . '/utilidades.php';

$carrito = [
    ['producto' => "Camiseta",  'precio' => 15.0, 'unidades' => 2],
    ['producto' => "Pantalón",  'precio' => 35.5, 'unidades' => 1],
    ['producto' => "Calcetines", 'precio' => 4.0, 'unidades' => 5],
    ['producto' => "Gorra",     'precio' => 12.0, 'unidades' => 0],
];

// ---------------------------------------------------------------------------------------------
// array_map — aplica una función a cada elemento y devuelve un array NUEVO con los resultados
// ENLACE: https://www.php.net/manual/es/function.array-map.php
// ENUNCIADO EJEMPLO: aplicar el IVA (21 %) a una lista de precios; obtener el nombre de cada
//   producto en mayúsculas; limpiar con trim todos los campos de un formulario.
// NOTA: ¡OJO al orden de los parámetros! Primero la FUNCIÓN y después el ARRAY (al revés que
//   array_filter). Se puede pasar el nombre de una función existente como string ('trim').
//   Con varios arrays, la función recibe un elemento de cada uno.
// ---------------------------------------------------------------------------------------------
titulo('array_map');
$precios       = [10, 25.5, 100];
$conIva        = array_map(fn($p) => round($p * 1.21, 2), $precios);       // [12.1, 30.86, 121]
ver('precios con IVA', $conIva);

$nombres       = array_map(fn($linea) => strtoupper($linea['producto']), $carrito);   // "PANTALóN": usa mb_strtoupper con tildes
ver('nombres en mayúsculas', $nombres);

$formulario    = ['nombre' => "  Ana ", 'ciudad' => " Vigo"];
$limpio        = array_map('trim', $formulario);                           // conserva las claves de texto
ver('formulario limpio', $limpio);

$cantidades    = [2, 1, 5];
$unitarios     = [15.0, 35.5, 4.0];
$subtotales    = array_map(fn($c, $p) => $c * $p, $cantidades, $unitarios); // [30, 35.5, 20]
ver('subtotales (dos arrays)', $subtotales);

// ---------------------------------------------------------------------------------------------
// array_filter — se queda con los elementos para los que la función devuelve true
// ENLACE: https://www.php.net/manual/es/function.array-filter.php
// ENUNCIADO EJEMPLO: quitar del carrito las líneas con 0 unidades; obtener los productos de más
//   de 10 €; quitar los valores vacíos de una lista.
// NOTA: primero el ARRAY y después la función. CONSERVA las claves (pueden quedar huecos).
//   Sin función, quita los valores "falsos" (0, "", null, false, "0", []).
//   Con ARRAY_FILTER_USE_KEY la función recibe la clave; con ARRAY_FILTER_USE_BOTH, valor y clave.
// ---------------------------------------------------------------------------------------------
titulo('array_filter');
$conUnidades   = array_filter($carrito, fn($linea) => $linea['unidades'] > 0);   // claves 0, 1, 2
ver('líneas con unidades', array_column($conUnidades, 'producto'));
ver('claves que quedan', array_keys($conUnidades));

$caros         = array_filter($carrito, fn($linea) => $linea['precio'] > 10);
ver('productos de más de 10 €', array_column($caros, 'producto'));

$etiquetas     = ["php", "", "mysql", null, "0", "apache"];
$sinVacios     = array_filter($etiquetas);                       // ¡quita también "0"!
ver('sin vacíos', $sinVacios);

$config        = ['db_host' => "localhost", 'app_name' => "Tienda", 'db_user' => "root"];
$soloDb        = array_filter($config, fn($clave) => str_starts_with($clave, "db_"), ARRAY_FILTER_USE_KEY);
ver('solo la configuración de la BD', $soloDb);

// ---------------------------------------------------------------------------------------------
// array_values — devuelve los valores con claves renumeradas 0, 1, 2... (quita los "huecos")
// ENLACE: https://www.php.net/manual/es/function.array-values.php
// ENUNCIADO EJEMPLO: tras filtrar con array_filter, las claves tienen huecos; las renumeramos
//   para que json_encode genere una lista y para poder recorrer con un for.
// ---------------------------------------------------------------------------------------------
titulo('array_values');
ver('JSON con huecos', json_encode($sinVacios));                  // {"0":"php","2":"mysql","5":"apache"}
$renumerado    = array_values($sinVacios);
ver('array_values', $renumerado);
ver('JSON renumerado', json_encode($renumerado));                 // ["php","mysql","apache"]

// ---------------------------------------------------------------------------------------------
// array_reduce — reduce el array a un único valor acumulando un resultado
// ENLACE: https://www.php.net/manual/es/function.array-reduce.php
// ENUNCIADO EJEMPLO: calcular el total del carrito (suma de precio x unidades) y encontrar el
//   producto más caro.
// NOTA: la función recibe (acumulado, elemento) y devuelve el nuevo acumulado. El tercer
//   parámetro es el valor inicial del acumulado (si no se indica, es null).
// ---------------------------------------------------------------------------------------------
titulo('array_reduce');
$total         = array_reduce($carrito, fn($suma, $linea) => $suma + $linea['precio'] * $linea['unidades'], 0);
ver('total del carrito', $total);                                 // 85.5

$masCaro       = array_reduce($carrito, function ($mejor, $linea) {
    return ($mejor === null || $linea['precio'] > $mejor['precio']) ? $linea : $mejor;
});
ver('producto más caro', $masCaro['producto']);                   // "Pantalón"

// ---------------------------------------------------------------------------------------------
// array_walk — llama a una función para cada elemento (valor y clave); puede MODIFICAR el original
// ENLACE: https://www.php.net/manual/es/function.array-walk.php
// ENUNCIADO EJEMPLO: aplicar un 10 % de descuento a todos los precios del carrito modificando el
//   propio array, y generar las líneas de una lista "clave: valor".
// NOTA: para modificar el elemento, el parámetro debe recibirse POR REFERENCIA (&$valor).
//   Devuelve true (no un array nuevo).
// ---------------------------------------------------------------------------------------------
titulo('array_walk');
array_walk($carrito, function (&$linea) {
    $linea['precio'] = round($linea['precio'] * 0.9, 2);
});
ver('precios con descuento', array_column($carrito, 'precio', 'producto'));

$lineasConfig  = [];
array_walk($config, function ($valor, $clave) use (&$lineasConfig) {
    $lineasConfig[] = "$clave: $valor";
});
ver('líneas de configuración', $lineasConfig);

// ---------------------------------------------------------------------------------------------
// array_walk_recursive — como array_walk pero entra también en los arrays interiores
// ENLACE: https://www.php.net/manual/es/function.array-walk-recursive.php
// ENUNCIADO EJEMPLO: escapar con htmlspecialchars TODOS los textos de un array con datos anidados
//   antes de mostrarlo en una página.
// NOTA: la función solo recibe los valores que NO son arrays (las "hojas").
// ---------------------------------------------------------------------------------------------
titulo('array_walk_recursive');
$pedido        = [
    'cliente'   => "Ana <script>",
    'direccion' => ['calle' => "Mayor & Cía", 'ciudad' => "Vigo"],
    'lineas'    => [["producto" => "Camiseta \"talla M\""]],
];
array_walk_recursive($pedido, function (&$valor) {
    $valor = htmlspecialchars($valor);
});
ver('pedido escapado', $pedido);

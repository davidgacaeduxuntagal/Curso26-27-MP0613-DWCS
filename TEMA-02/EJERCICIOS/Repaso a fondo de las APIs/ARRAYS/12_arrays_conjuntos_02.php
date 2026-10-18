<?php
/*
 * 12_arrays_conjuntos_02.php
 *  ARRAYS - OPERACIONES DE CONJUNTOS (2): diferencia e intersección con funciones de comparación propias
 *  Funciones: array_udiff, array_uintersect, array_diff_ukey, array_intersect_ukey,
 *             array_diff_uassoc, array_udiff_assoc
 *
 *  Son las mismas operaciones que en 11_arrays_conjuntos_01.php, pero la comparación la hace una
 *  función nuestra, que (como en usort) devuelve < 0, 0 o > 0. Solo importa si devuelve 0 (iguales).
 *      array_u...      -> función propia para comparar VALORES
 *      ..._ukey        -> función propia para comparar CLAVES
 *      ..._uassoc      -> compara valores normal y CLAVES con función propia
 *  Existen también array_uintersect_assoc, array_udiff_uassoc, array_uintersect_uassoc e
 *  array_intersect_uassoc, que combinan lo anterior.
 *
 *  Útiles sobre todo cuando los elementos son ARRAYS (filas) y no se pueden comparar directamente,
 *  o cuando queremos comparar sin distinguir mayúsculas.
 *
 *  Ejecuta en modo depuración con F11 para entrar en las funciones de comparación.
 */
require_once __DIR__ . '/utilidades.php';

// ---------------------------------------------------------------------------------------------
// array_udiff — diferencia comparando los VALORES con una función propia
// ENLACE: https://www.php.net/manual/es/function.array-udiff.php
// ENUNCIADO EJEMPLO: tenemos la lista de productos del catálogo y la de productos ya pedidos
//   (arrays de arrays). ¿Qué productos del catálogo no se han pedido nunca? Comparamos por código.
// ---------------------------------------------------------------------------------------------
titulo('array_udiff');
$catalogo      = [
    ['codigo' => "P01", 'nombre' => "Ratón"],
    ['codigo' => "P02", 'nombre' => "Teclado"],
    ['codigo' => "P03", 'nombre' => "Monitor"],
];
$pedidos       = [
    ['codigo' => "P02", 'cantidad' => 5],
    ['codigo' => "P01", 'cantidad' => 1],
];
$sinPedidos    = array_udiff($catalogo, $pedidos, fn($a, $b) => strcmp($a['codigo'], $b['codigo']));
ver('productos nunca pedidos', array_column($sinPedidos, 'nombre'));   // ["Monitor"]

// ---------------------------------------------------------------------------------------------
// array_uintersect — intersección comparando los VALORES con una función propia
// ENLACE: https://www.php.net/manual/es/function.array-uintersect.php
// ENUNCIADO EJEMPLO: los alumnos inscritos en dos actividades extraescolares, pero los nombres se
//   escribieron con mayúsculas distintas: comparamos sin distinguir mayúsculas (strcasecmp).
// ---------------------------------------------------------------------------------------------
titulo('array_uintersect');
$robotica      = ["ana", "BRAIS", "Carmen"];
$teatro        = ["Brais", "Diego", "ANA"];
$enAmbas       = array_uintersect($robotica, $teatro, 'strcasecmp');   // [0 => ana, 1 => BRAIS]
$sinFuncion    = array_intersect($robotica, $teatro);                  // [] (distinguen mayúsculas)
ver('en las dos actividades', $enAmbas);
ver('con array_intersect normal', $sinFuncion);

// ---------------------------------------------------------------------------------------------
// array_diff_ukey — diferencia comparando las CLAVES con una función propia
// ENLACE: https://www.php.net/manual/es/function.array-diff-ukey.php
// ENUNCIADO EJEMPLO: las cabeceras HTTP no distinguen mayúsculas. ¿Qué cabeceras obligatorias
//   faltan en una petición, si "content-type" y "Content-Type" son la misma?
// ---------------------------------------------------------------------------------------------
titulo('array_diff_ukey');
$obligatorias  = ['Content-Type' => true, 'Authorization' => true, 'Accept' => true];
$recibidas     = ['content-type' => "application/json", 'ACCEPT' => "*/*"];
$faltan        = array_diff_ukey($obligatorias, $recibidas, 'strcasecmp');   // ['Authorization' => true]
ver('cabeceras que faltan', array_keys($faltan));

// ---------------------------------------------------------------------------------------------
// array_intersect_ukey — intersección comparando las CLAVES con una función propia
// ENLACE: https://www.php.net/manual/es/function.array-intersect-ukey.php
// ENUNCIADO EJEMPLO: de los parámetros de una URL, quedarnos con los que reconocemos, aunque el
//   usuario los escriba en mayúsculas ("PAGINA=2").
// ---------------------------------------------------------------------------------------------
titulo('array_intersect_ukey');
$parametros    = ['PAGINA' => "2", 'orden' => "precio", 'hack' => "1"];
$conocidos     = ['pagina' => null, 'orden' => null];
$validos       = array_intersect_ukey($parametros, $conocidos, 'strcasecmp');   // PAGINA y orden
ver('parámetros reconocidos', $validos);

// ---------------------------------------------------------------------------------------------
// array_diff_uassoc — diferencia por CLAVE + VALOR, comparando las CLAVES con una función propia
// ENLACE: https://www.php.net/manual/es/function.array-diff-uassoc.php
// ENUNCIADO EJEMPLO: comparar la configuración guardada con la nueva, sabiendo que en los
//   nombres de las opciones no importan las mayúsculas, pero en los valores sí.
// ---------------------------------------------------------------------------------------------
titulo('array_diff_uassoc');
$antes         = ['IDIOMA' => "es", 'Tema' => "claro"];
$despues       = ['idioma' => "es", 'tema' => "oscuro"];
$cambiado      = array_diff_uassoc($despues, $antes, 'strcasecmp');   // ['tema' => "oscuro"]
ver('opciones cambiadas', $cambiado);

// ---------------------------------------------------------------------------------------------
// array_udiff_assoc — diferencia por CLAVE + VALOR, comparando los VALORES con una función propia
// ENLACE: https://www.php.net/manual/es/function.array-udiff-assoc.php
// ENUNCIADO EJEMPLO: comparar los precios de ayer y de hoy (producto => precio) considerando
//   iguales los que se diferencian en menos de 1 céntimo (problemas de redondeo de los decimales).
// ---------------------------------------------------------------------------------------------
titulo('array_udiff_assoc');
$ayer          = ['pan' => 1.20, 'leche' => 0.95, 'café' => 4.50];
$hoy           = ['pan' => 1.2000001, 'leche' => 0.99, 'café' => 4.50];
$casiIguales   = fn($a, $b) => abs($a - $b) < 0.01 ? 0 : ($a <=> $b);
$hanSubido     = array_udiff_assoc($hoy, $ayer, $casiIguales);    // ['leche' => 0.99]
$sinTolerancia = array_diff_assoc($hoy, $ayer);                   // pan y leche (¡el pan "cambió"!)
ver('precios cambiados (con tolerancia)', $hanSubido);
ver('precios cambiados (sin tolerancia)', $sinTolerancia);

<?php
/*
 * e01-JSON.php
 *  JSON (1): CODIFICAR DATOS DE PHP A JSON
 *  Funciones: json_encode (tipos simples, listas, arrays asociativos, arrays anidados, objetos),
 *             array_is_list, array_values
 *  (apartados 1 y 2.1-2.2 de los apuntes)
 *
 *  Cómo trabajar este fichero:
 *   1. Pon un punto de ruptura en la primera línea de código y ejecuta en modo depuración.
 *   2. Avanza con F10 y, en cada paso, compara en el panel VARIABLES el valor de PHP con el
 *      texto JSON que se obtiene. Fíjate sobre todo en cuándo sale [ ... ] y cuándo { ... }.
 *   3. Los comentarios a la derecha indican el resultado esperado.
 */
require_once __DIR__ . '/utilidades.php';

// ---------------------------------------------------------------------------------------------
// json_encode — Retorna la representación JSON del valor dado (TIPOS SIMPLES)
// ENLACE: https://www.php.net/manual/es/function.json-encode.php
// ENUNCIADO EJEMPLO: ver cómo se escribe en JSON cada tipo simple de PHP.
// NOTA: el resultado SIEMPRE es un string (el texto JSON). Los strings salen entre comillas
//       dobles, true/false/null en minúsculas y los float sin decimales "sobrantes".
// ---------------------------------------------------------------------------------------------
titulo('json_encode: tipos simples');
$texto   = json_encode('Bar Baio');      // "Bar Baio"  (el texto JSON lleva las comillas dobles)
$entero  = json_encode(42);              // "42"
$decimal = json_encode(4.6);             // "4.6"
$redondo = json_encode(10.0);            // "10"  ¡pierde el .0! (ver JSON_PRESERVE_ZERO_FRACTION en e03)
$si      = json_encode(true);            // "true"
$nada    = json_encode(null);            // "null"
ver('string', $texto);
ver('int', $entero);
ver('float', $decimal);
ver('float 10.0', $redondo);
ver('bool', $si);
ver('null', $nada);
ver('tipo del resultado', gettype($entero));    // "string"

// ---------------------------------------------------------------------------------------------
// json_encode — caracteres especiales en los strings
// ENLACE: https://www.php.net/manual/es/function.json-encode.php
// ENUNCIADO EJEMPLO: codificar el nombre de un plato con tilde, una frase con comillas y una
//                   ruta con barras.
// NOTA: por defecto, json_encode escapa los caracteres no ASCII (á -> \u00e1), las comillas (\")
//       y las barras (\/). Es JSON correcto: quien lo decodifique recuperará el texto original.
//       En e03 verás cómo evitarlo (JSON_UNESCAPED_UNICODE, JSON_UNESCAPED_SLASHES).
// ---------------------------------------------------------------------------------------------
titulo('json_encode: caracteres especiales');
$plato  = json_encode('Pulpo á feira');            // "Pulpo \u00e1 feira"
$frase  = json_encode('Dijo "¡qué rico!"');        // "Dijo \"\u00a1qu\u00e9 rico!\""
$ruta   = json_encode('img/carta/pulpo.jpg');      // "img\/carta\/pulpo.jpg"
$lineas = json_encode("línea 1\nlínea 2\ttab");    // \n y \t escritos como texto
ver('plato', $plato);
ver('frase', $frase);
ver('ruta', $ruta);
ver('saltos de línea', $lineas);

// ---------------------------------------------------------------------------------------------
// json_encode — LISTAS (arrays con índices 0, 1, 2...)  ->  array JSON [ ... ]
// ENLACE: https://www.php.net/manual/es/function.json-encode.php
// ENUNCIADO EJEMPLO: enviar a la app del móvil la lista de categorías de la carta.
// ---------------------------------------------------------------------------------------------
titulo('json_encode: listas');
$categorias = ['cafés', 'tapas', 'raciones', 'postres'];
$json = json_encode($categorias);
ver('categorías', $json);                         // ["caf\u00e9s","tapas","raciones","postres"]
ver('lista vacía', json_encode([]));             // "[]"
ver('lista mixta', json_encode([1, 'dos', 3.5, true, null]));   // [1,"dos",3.5,true,null]

// ---------------------------------------------------------------------------------------------
// json_encode — ARRAYS ASOCIATIVOS  ->  objeto JSON { "clave": valor, ... }
// ENLACE: https://www.php.net/manual/es/function.json-encode.php
// ENUNCIADO EJEMPLO: enviar los datos de un plato de la carta.
// NOTA: en JSON las claves de un objeto son SIEMPRE strings (aunque en PHP fueran números).
// ---------------------------------------------------------------------------------------------
titulo('json_encode: arrays asociativos');
$plato = ['nombre' => 'Tortilla', 'precio' => 9.5, 'vegetariano' => true, 'alergenos' => ['huevo']];
$json = json_encode($plato);
ver('plato', $json);        // {"nombre":"Tortilla","precio":9.5,"vegetariano":true,"alergenos":["huevo"]}

// ---------------------------------------------------------------------------------------------
// array_is_list — Comprueba si un array es una lista (PHP 8.1)
// ENLACE: https://www.php.net/manual/es/function.array-is-list.php
// ENUNCIADO EJEMPLO: saber de antemano si json_encode va a producir [ ] o { }.
// NOTA: es una lista si las claves son 0, 1, 2... consecutivas y EN ORDEN. Solo en ese caso
//       json_encode genera un array JSON; en cualquier otro caso, un objeto.
// ---------------------------------------------------------------------------------------------
titulo('array_is_list');
$lista        = ['pulpo', 'tortilla'];                 // claves 0, 1
$noEmpiezaEn0 = [1 => 'pulpo', 2 => 'tortilla'];      // claves 1, 2
$desordenado  = [1 => 'tortilla', 0 => 'pulpo'];       // claves 1, 0
$asociativo   = ['a' => 'pulpo'];
ver('lista', array_is_list($lista));                 // true
ver('no empieza en 0', array_is_list($noEmpiezaEn0)); // false
ver('desordenado', array_is_list($desordenado));     // false
ver('asociativo', array_is_list($asociativo));       // false
ver('json lista', json_encode($lista));                  // ["pulpo","tortilla"]
ver('json no empieza en 0', json_encode($noEmpiezaEn0)); // {"1":"pulpo","2":"tortilla"}
ver('json desordenado', json_encode($desordenado));      // {"1":"tortilla","0":"pulpo"}

// ---------------------------------------------------------------------------------------------
// array_values — Devuelve todos los valores de un array (reindexado 0, 1, 2...)
// ENLACE: https://www.php.net/manual/es/function.array-values.php
// ENUNCIADO EJEMPLO: retirar de la lista un plato agotado y enviar la lista a la app.
// NOTA: ERROR MUY HABITUAL. unset() y array_filter() conservan las claves y dejan "huecos", así
//       que la lista deja de serlo y la app recibe un OBJETO en lugar de un array.
// ---------------------------------------------------------------------------------------------
titulo('array_values: arreglar listas con huecos');
$platos = ['pulpo', 'tortilla', 'empanada', 'zorza'];
unset($platos[1]);                                        // agotada la tortilla: claves 0, 2, 3
ver('con hueco', json_encode($platos));                  // {"0":"pulpo","2":"empanada","3":"zorza"}
ver('reindexado', json_encode(array_values($platos)));   // ["pulpo","empanada","zorza"]

$precios = [12.5, 9.5, 4.0, 8.0];
$baratos = array_filter($precios, fn($p) => $p < 9);      // conserva las claves 2 y 3
ver('array_filter', json_encode($baratos));              // {"2":4,"3":8}  (y además 4.0 sale como 4)
ver('array_filter + values', json_encode(array_values($baratos)));   // [4,8]

// ---------------------------------------------------------------------------------------------
// json_encode — ESTRUCTURAS ANIDADAS (lista de arrays asociativos)
// ENLACE: https://www.php.net/manual/es/function.json-encode.php
// ENUNCIADO EJEMPLO: la carta completa: categorías con su lista de platos. Es la forma típica
//                   de la respuesta de una API: un objeto que contiene listas de objetos.
// ---------------------------------------------------------------------------------------------
titulo('json_encode: estructuras anidadas');
$carta = [
    'bar'         => 'Bar Baio',
    'actualizada' => '2026-10-01',
    'categorias'  => [
        ['nombre' => 'Tapas', 'platos' => [
            ['nombre' => 'Pulpo', 'precio' => 12.5],
            ['nombre' => 'Croquetas', 'precio' => 7],
        ]],
        ['nombre' => 'Postres', 'platos' => [
            ['nombre' => 'Filloas', 'precio' => 4.5],
        ]],
    ],
];
$json = json_encode($carta);
ver('carta', $json);
ver('longitud del JSON', strlen($json));     // 223 bytes (en una sola línea, sin espacios)

// ---------------------------------------------------------------------------------------------
// json_encode — OBJETOS (solo los atributos PÚBLICOS)
// ENLACE: https://www.php.net/manual/es/function.json-encode.php
// ENUNCIADO EJEMPLO: convertir a JSON un objeto Plato.
// NOTA: los atributos protected y private NO aparecen. Un stdClass se codifica como un objeto
//       con todas sus propiedades. Los objetos se tratan con detalle en e05.
// ---------------------------------------------------------------------------------------------
titulo('json_encode: objetos');
class Plato
{
    public string $nombre = 'Pulpo á feira';
    public float $precio = 12.5;
    public ?string $foto = null;
    protected string $proveedor = 'Lonja de Ribeira';    // no aparece
    private float $coste = 6.0;                           // no aparece
}
$plato = new Plato();
ver('objeto Plato', json_encode($plato));     // {"nombre":"Pulpo \u00e1 feira","precio":12.5,"foto":null}

$reserva = new stdClass();
$reserva->mesa = 7;
$reserva->personas = 4;
ver('stdClass', json_encode($reserva));       // {"mesa":7,"personas":4}
ver('objeto vacío', json_encode(new stdClass()));   // "{}"

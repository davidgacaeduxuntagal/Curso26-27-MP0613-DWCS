<?php
/*
 * e02-JSON.php
 *  JSON (2): DECODIFICAR JSON A DATOS DE PHP
 *  Funciones: json_decode (objetos stdClass o arrays asociativos, tipos, acceso a datos anidados,
 *             profundidad, JSON_BIGINT_AS_STRING, JSON_OBJECT_AS_ARRAY), ida y vuelta
 *  (apartado 3 de los apuntes)
 *
 *  Cómo trabajar este fichero:
 *   1. Pon un punto de ruptura en la primera línea de código y ejecuta en modo depuración.
 *   2. Avanza con F10. Después de cada json_decode, despliega la variable en el panel VARIABLES:
 *      verás si es un objeto (stdClass) o un array y de qué tipo es cada dato.
 *   3. Los comentarios a la derecha indican el resultado esperado.
 */
require_once __DIR__ . '/utilidades.php';

// Pedido que envía la aplicación del móvil (en la vida real llegaría por la red)
$jsonPedido = '{
    "mesa": 7,
    "camarero": "Carlos",
    "pagado": false,
    "total": 33.5,
    "nota": null,
    "platos": [
        {"nombre": "Pulpo", "cantidad": 2, "precio": 12.5},
        {"nombre": "Pan", "cantidad": 1, "precio": 1.5},
        {"nombre": "Filloas", "cantidad": 2, "precio": 3.5}
    ]
}';

// ---------------------------------------------------------------------------------------------
// json_decode — Decodifica un string JSON (por defecto, a OBJETOS stdClass)
// ENLACE: https://www.php.net/manual/es/function.json-decode.php
// ENUNCIADO EJEMPLO: leer el número de mesa y el primer plato de un pedido.
// NOTA: cada objeto JSON { } se convierte en un objeto stdClass y se accede con ->.
//       Los arrays JSON [ ] se convierten siempre en arrays de PHP (se accede con [ ]).
// ---------------------------------------------------------------------------------------------
titulo('json_decode: como objetos');
$pedido = json_decode($jsonPedido);
ver('clase', get_class($pedido));                    // "stdClass"
ver('mesa', $pedido->mesa);                          // 7
ver('platos es un', gettype($pedido->platos));       // "array"
ver('primer plato', $pedido->platos[0]->nombre);     // "Pulpo"
ver('existe nota', property_exists($pedido, 'nota')); // true (existe, aunque valga null)

// ---------------------------------------------------------------------------------------------
// json_decode con $associative = true — objetos JSON como ARRAYS ASOCIATIVOS
// ENLACE: https://www.php.net/manual/es/function.json-decode.php
// ENUNCIADO EJEMPLO: calcular el total del pedido recorriendo los platos.
// NOTA: es la forma más habitual de trabajar en PHP: puedes usar foreach, array_column,
//       array_sum, isset, ?? ... como con cualquier array.
// ---------------------------------------------------------------------------------------------
titulo('json_decode: como arrays');
$pedido = json_decode($jsonPedido, true);
ver('mesa', $pedido['mesa']);                        // 7
ver('primer plato', $pedido['platos'][0]['nombre']); // "Pulpo"
$total = 0;
foreach ($pedido['platos'] as $plato) {
    $subtotal = $plato['cantidad'] * $plato['precio'];
    $total += $subtotal;
}
ver('total calculado', $total);                      // 33.5
ver('coincide con el enviado', $total === $pedido['total']);    // true
ver('nombres de los platos', array_column($pedido['platos'], 'nombre'));   // ['Pulpo', 'Pan', 'Filloas']
ver('propina (no existe)', $pedido['propina'] ?? 0);  // 0

// ---------------------------------------------------------------------------------------------
// json_decode — TIPOS de los datos decodificados
// ENLACE: https://www.php.net/manual/es/function.json-decode.php
// ENUNCIADO EJEMPLO: comprobar qué tipo de PHP recibe cada tipo JSON antes de validar datos.
// NOTA: 7 llega como int, 33.5 como float, false como bool... ¡y "7" como string! Si la app
//       envía la mesa entre comillas, is_int($pedido['mesa']) será false.
// ---------------------------------------------------------------------------------------------
titulo('json_decode: tipos');
ver('mesa', gettype($pedido['mesa']));               // "integer"
ver('total', gettype($pedido['total']));             // "double" (float)
ver('pagado', gettype($pedido['pagado']));           // "boolean"
ver('nota', gettype($pedido['nota']));               // "NULL"
ver('"7" entre comillas', gettype(json_decode('"7"')));   // "string"
ver('5.0', var_export(json_decode('5.0'), true));    // "5.0" (float)
ver('1e3', json_decode('1e3'));                      // 1000.0 (float)

// ---------------------------------------------------------------------------------------------
// json_decode — documentos que NO son objetos
// ENLACE: https://www.php.net/manual/es/function.json-decode.php
// ENUNCIADO EJEMPLO: decodificar una lista de categorías y valores sueltos.
// NOTA: un documento JSON puede ser cualquier valor: una lista, un número, un string...
// ---------------------------------------------------------------------------------------------
titulo('json_decode: listas y valores sueltos');
$categorias = json_decode('["cafés", "tapas", "postres"]');
ver('categorías', $categorias);                      // ['cafés', 'tapas', 'postres']
ver('número de categorías', count($categorias));     // 3
ver('número suelto', json_decode('42'));             // 42
ver('texto suelto', json_decode('"hola"'));          // "hola"
ver('texto escapado', json_decode('"Pulpo \u00e1 feira"'));   // "Pulpo á feira" (recupera la á)

// ---------------------------------------------------------------------------------------------
// json_decode — objetos (stdClass) frente a arrays: diferencias prácticas
// ENLACE: https://www.php.net/manual/es/language.types.object.php
// ENUNCIADO EJEMPLO: recorrer las propiedades de un objeto y convertirlo en array.
// NOTA: un stdClass también se puede recorrer con foreach, y se convierte en array con (array).
//       Las claves que no son nombres válidos se leen con ->{'clave con espacios'}.
// ---------------------------------------------------------------------------------------------
titulo('stdClass frente a arrays');
$horario = json_decode('{"lunes": "cerrado", "martes": "9-23", "fin de semana": "10-02"}');
foreach ($horario as $dia => $horas) {
    ver($dia, $horas);
}
ver('clave con espacios', $horario->{'fin de semana'});    // "10-02"
$comoArray = (array) $horario;
ver('convertido a array', $comoArray);

// ---------------------------------------------------------------------------------------------
// json_decode — PROFUNDIDAD máxima ($depth)
// ENLACE: https://www.php.net/manual/es/function.json-decode.php
// ENUNCIADO EJEMPLO: limitar la profundidad de los datos que aceptamos de fuera.
// NOTA: la profundidad cuenta los niveles de anidamiento, incluido el de los valores: un valor
//       simple es 1, [1] es 2, {"a":{"b":1}} es 3... Si el documento es más profundo,
//       json_decode devuelve null.
//       El valor por defecto (512) es suficiente casi siempre; limitarlo protege de documentos
//       enormes enviados para colgar el servidor.
// ---------------------------------------------------------------------------------------------
titulo('json_decode: profundidad');
$json = '{"nombre": {"Angelina": {"apellido": "Jolie"}}}';   // 3 objetos + el valor = 4 niveles
ver('profundidad 512', json_decode($json, true));            // se decodifica
ver('profundidad 4', json_decode($json, true, 4) !== null); // true: justo 4 niveles
ver('profundidad 3', json_decode($json, true, 3));           // null: tiene más de 3
ver('error', json_last_error_msg());                         // "Maximum stack depth exceeded"

// ---------------------------------------------------------------------------------------------
// json_decode — opción JSON_BIGINT_AS_STRING
// ENLACE: https://www.php.net/manual/es/json.constants.php
// ENUNCIADO EJEMPLO: recibir el identificador de un pago (de una pasarela de pago) que no cabe
//                   en un int de PHP.
// NOTA: sin la opción, el número se convierte en float y PIERDE PRECISIÓN (las últimas cifras).
// ---------------------------------------------------------------------------------------------
titulo('JSON_BIGINT_AS_STRING');
$json = '{"idPago": 12345678901234567890}';
$sinOpcion = json_decode($json, true);
$conOpcion = json_decode($json, true, 512, JSON_BIGINT_AS_STRING);
ver('PHP_INT_MAX', PHP_INT_MAX);                     // 9223372036854775807
ver('sin opción', $sinOpcion['idPago']);             // 1.2345678901234567E+19 (float: perdió cifras)
ver('con opción', $conOpcion['idPago']);             // "12345678901234567890" (string exacto)

// ---------------------------------------------------------------------------------------------
// json_decode — opción JSON_OBJECT_AS_ARRAY
// ENLACE: https://www.php.net/manual/es/json.constants.php
// ENUNCIADO EJEMPLO: obtener arrays asociativos indicándolo en las opciones.
// NOTA: equivale a pasar true como segundo parámetro. Solo se aplica si el segundo parámetro
//       es null; si pasas false explícitamente, manda el false.
// ---------------------------------------------------------------------------------------------
titulo('JSON_OBJECT_AS_ARRAY');
$a = json_decode('{"mesa": 7}', null, 512, JSON_OBJECT_AS_ARRAY);
$b = json_decode('{"mesa": 7}', false, 512, JSON_OBJECT_AS_ARRAY);
ver('con null', $a);                                 // array ('mesa' => 7)
ver('con false', gettype($b));                       // "object" (manda el false)

// ---------------------------------------------------------------------------------------------
// Ida y vuelta: json_encode(json_decode(...))
// ENLACE: https://www.php.net/manual/es/function.json-decode.php
// ENUNCIADO EJEMPLO: leer una configuración en JSON, modificarla y volver a guardarla.
// NOTA: con arrays asociativos, un objeto vacío {} se convierte en [] y al volver a codificar
//       sale como []. Si el receptor esperaba un objeto, puede fallar. Con stdClass no pasa.
// ---------------------------------------------------------------------------------------------
titulo('Ida y vuelta');
$config = '{"bar":"Bar Baio","ajustes":{},"festivos":[]}';
$comoArray  = json_decode($config, true);
$comoObjeto = json_decode($config);
ver('original      ', $config);
ver('vía arrays    ', json_encode($comoArray));      // {"bar":"Bar Baio","ajustes":[],"festivos":[]}
ver('vía stdClass  ', json_encode($comoObjeto));     // {"bar":"Bar Baio","ajustes":{},"festivos":[]}

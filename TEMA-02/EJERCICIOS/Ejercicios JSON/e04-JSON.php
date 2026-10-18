<?php
/*
 * e04-JSON.php
 *  JSON (4): ERRORES
 *  Funciones: json_last_error, json_last_error_msg, constantes JSON_ERROR_*, JSON_THROW_ON_ERROR,
 *             JsonException, JSON_INVALID_UTF8_SUBSTITUTE, JSON_PARTIAL_OUTPUT_ON_ERROR,
 *             mb_convert_encoding
 *  (apartado 4 de los apuntes)
 *
 *  Cómo trabajar este fichero:
 *   1. Pon un punto de ruptura en la primera línea de código y ejecuta en modo depuración.
 *   2. Avanza con F10. Fíjate en que json_decode / json_encode NO avisan de nada: hay que
 *      preguntar por el error. En los bloques try/catch, mira qué línea se ejecuta después
 *      de la excepción.
 *   3. Los comentarios a la derecha indican el resultado esperado.
 */
require_once __DIR__ . '/utilidades.php';

// ---------------------------------------------------------------------------------------------
// json_last_error — Devuelve el último error que ocurrió
// json_last_error_msg — Devuelve el mensaje de error de la última llamada
// ENLACE: https://www.php.net/manual/es/function.json-last-error.php
// ENLACE: https://www.php.net/manual/es/function.json-last-error-msg.php
// ENUNCIADO EJEMPLO: detectar los pedidos mal escritos que envía una versión antigua de la app.
// NOTA: el error se refiere SOLO a la última llamada a json_encode/json_decode: cada llamada
//       correcta lo "borra" (vuelve a JSON_ERROR_NONE). Consúltalo justo después.
// ---------------------------------------------------------------------------------------------
titulo('json_last_error y json_last_error_msg');
$pedidos = [
    'correcto'          => '{"mesa": 7, "platos": ["pulpo"]}',
    'comillas simples'  => "{'mesa': 7}",
    'coma final'        => '{"mesa": 7, "platos": ["pulpo",]}',
    'clave sin comillas' => '{mesa: 7}',
    'cortado'           => '{"mesa": 7, "platos": ["pul',
    'vacío'             => '',
];
foreach ($pedidos as $caso => $json) {
    $datos = json_decode($json, true);
    $codigo = json_last_error();
    ver($caso, $codigo === JSON_ERROR_NONE ? 'correcto' : "error $codigo: " . json_last_error_msg());
}
// correcto => "correcto"; casi todos => "error 4: Syntax error" (JSON_ERROR_SYNTAX vale 4);
// el texto cortado => "error 3: Control character error..." (JSON_ERROR_CTRL_CHAR): el string
// quedó sin cerrar. El mensaje no siempre describe bien la causa: lo importante es que NO es 0.
ver('JSON_ERROR_SYNTAX', JSON_ERROR_SYNTAX);    // 4

// ---------------------------------------------------------------------------------------------
// null: ¿error o dato? — por qué no basta con comprobar el resultado
// ENLACE: https://www.php.net/manual/es/function.json-decode.php
// ENUNCIADO EJEMPLO: distinguir un JSON mal escrito del JSON "null" (válido).
// NOTA: los dos devuelven null. Lo mismo pasa con "false", "0" o "[]": son JSON válidos cuyo
//       resultado es "falso" en PHP. Por eso if ($resultado) o if ($resultado === null) NO
//       sirven para detectar errores (el JsonHandler del material base tiene este fallo).
// ---------------------------------------------------------------------------------------------
titulo('null: ¿error o dato?');
$a = json_decode('null');
$errorA = json_last_error_msg();
$b = json_decode('nul');
$errorB = json_last_error_msg();
ver('"null" da', $a);            // NULL
ver('y el error es', $errorA);   // "No error"
ver('"nul" da', $b);             // NULL
ver('y el error es', $errorB);   // "Syntax error"
ver('"false" da', json_decode('false'));   // false (y es correcto)
ver('"[]" da', json_decode('[]'));         // array () (y es correcto)

// ---------------------------------------------------------------------------------------------
// JSON_THROW_ON_ERROR y JsonException — los errores como excepciones (PHP 7.3+)
// ENLACE: https://www.php.net/manual/es/class.jsonexception.php
// ENUNCIADO EJEMPLO: procesar un pedido y, si el JSON está mal, responder con un error.
// NOTA: es la forma RECOMENDADA: no puedes olvidarte de comprobar el error (si no lo capturas,
//       el script se detiene con un "Fatal error: Uncaught JsonException"). El código de la
//       excepción ($e->getCode()) es el mismo que devolvería json_last_error().
// ---------------------------------------------------------------------------------------------
titulo('JSON_THROW_ON_ERROR');
$recibido = "{'mesa': 7}";
try {
    $pedido = json_decode($recibido, true, 512, JSON_THROW_ON_ERROR);
    ver('pedido', $pedido);                        // NO se ejecuta: salta al catch
} catch (JsonException $e) {
    ver('mensaje', $e->getMessage());              // "Syntax error"
    ver('código', $e->getCode());                  // 4
}
ver('el script sigue', true);

// También con json_encode (ver más abajo los errores al codificar)
try {
    $json = json_encode(['precio' => NAN], JSON_THROW_ON_ERROR);
} catch (JsonException $e) {
    ver('al codificar NAN', $e->getMessage());     // "Inf and NaN cannot be JSON encoded"
}

// ---------------------------------------------------------------------------------------------
// Función de ayuda para decodificar con seguridad (versión corregida del JsonHandler)
// ENLACE: https://www.php.net/manual/es/language.exceptions.php
// ENUNCIADO EJEMPLO: decodificar con una función que devuelva siempre un array, o lance una
//                   excepción con un mensaje claro, y que funcione bien con "[]" y "0".
// ---------------------------------------------------------------------------------------------
titulo('Función decodificar()');
function decodificar(string $json): array
{
    try {
        $datos = json_decode($json, true, 512, JSON_THROW_ON_ERROR);
    } catch (JsonException $e) {
        throw new InvalidArgumentException('JSON no válido: ' . $e->getMessage(), 0, $e);
    }
    if (!is_array($datos)) {
        throw new InvalidArgumentException('Se esperaba un objeto o una lista JSON');
    }
    return $datos;
}
foreach (['{"mesa": 7}', '[]', '42', '{mesa: 7}'] as $json) {
    try {
        ver($json, decodificar($json));
    } catch (InvalidArgumentException $e) {
        ver($json, $e->getMessage());
    }
}
// {"mesa": 7} => array ('mesa' => 7); [] => array (); 42 => "Se esperaba..."; {mesa: 7} => "JSON no válido: Syntax error"

// ---------------------------------------------------------------------------------------------
// JSON_ERROR_UTF8 — textos que no están en UTF-8
// ENLACE: https://www.php.net/manual/es/function.mb-convert-encoding.php
// ENUNCIADO EJEMPLO: un fichero CSV antiguo (en ISO-8859-1 / Windows-1252) tiene nombres con
//                   tildes y json_encode devuelve false.
// NOTA: JSON exige UTF-8. Soluciones: convertir el texto a UTF-8 con mb_convert_encoding (lo
//       correcto), o usar JSON_INVALID_UTF8_SUBSTITUTE (sustituye los bytes malos por U+FFFD) o
//       JSON_INVALID_UTF8_IGNORE (los quita). Las dos últimas pierden información.
// ---------------------------------------------------------------------------------------------
titulo('UTF-8 no válido');
$nombreLatin1 = mb_convert_encoding('Begoña', 'ISO-8859-1', 'UTF-8');   // así viene del fichero antiguo
ver('bytes', bin2hex($nombreLatin1));                                   // "4265676ff161" (ñ = f1, 1 solo byte)
$json = json_encode(['nombre' => $nombreLatin1]);
ver('json_encode', $json);                                              // false
ver('error', json_last_error_msg());       // "Malformed UTF-8 characters, possibly incorrectly encoded"
ver('es JSON_ERROR_UTF8', json_last_error() === JSON_ERROR_UTF8);       // true
$sustituido = json_encode(['nombre' => $nombreLatin1], JSON_INVALID_UTF8_SUBSTITUTE | JSON_UNESCAPED_UNICODE);
ver('con SUBSTITUTE', $sustituido);                                     // {"nombre":"Bego�a"}
$convertido = mb_convert_encoding($nombreLatin1, 'UTF-8', 'ISO-8859-1');
ver('convertido a UTF-8', json_encode(['nombre' => $convertido], JSON_UNESCAPED_UNICODE));   // {"nombre":"Begoña"}

// ---------------------------------------------------------------------------------------------
// Errores al CODIFICAR: INF / NAN, recursos y JSON_PARTIAL_OUTPUT_ON_ERROR
// ENLACE: https://www.php.net/manual/es/json.constants.php
// ENUNCIADO EJEMPLO: calcular la nota media de un plato sin valoraciones (0 / 0) y enviarla.
// NOTA: JSON no admite INF ni NAN, ni recursos (ficheros abiertos, conexiones). Con
//       JSON_PARTIAL_OUTPUT_ON_ERROR se pone null en su lugar y se codifica el resto, pero el
//       error sigue anotado en json_last_error().
// ---------------------------------------------------------------------------------------------
titulo('Errores al codificar');
$valoraciones = [];
$media = count($valoraciones) > 0 ? array_sum($valoraciones) / count($valoraciones) : NAN;
$resumen = ['plato' => 'Zorza', 'media' => $media];
ver('json_encode', json_encode($resumen));                          // false
ver('error', json_last_error_msg());                                // "Inf and NaN cannot be JSON encoded"
ver('es JSON_ERROR_INF_OR_NAN', json_last_error() === JSON_ERROR_INF_OR_NAN);   // true
ver('parcial', json_encode($resumen, JSON_PARTIAL_OUTPUT_ON_ERROR)); // {"plato":"Zorza","media":0}
ver('error sigue anotado', json_last_error_msg());                  // "Inf and NaN cannot be JSON encoded"

$fp = fopen('php://memory', 'r');
ver('recurso', json_encode(['fichero' => $fp]));                    // false
ver('error', json_last_error_msg());                                // "Type is not supported"
fclose($fp);

// Lo correcto: no generar datos que JSON no admite (null = "sin dato")
$resumen['media'] = count($valoraciones) > 0 ? array_sum($valoraciones) / count($valoraciones) : null;
ver('con null', json_encode($resumen));                             // {"plato":"Zorza","media":null}

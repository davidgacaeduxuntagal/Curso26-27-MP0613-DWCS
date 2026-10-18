<?php
/*
 * e07-JSON.php
 *  JSON (7): JSON EN LA WEB (APIs)
 *  Funciones: header, http_response_code, file_get_contents('php://input'), validar peticiones,
 *             stream_context_create, http_build_query, consumir una API, htmlspecialchars,
 *             incrustar JSON en HTML (JSON_HEX_*)
 *  (apartado 6 de los apuntes)
 *
 *  Cómo trabajar este fichero:
 *   1. Pon un punto de ruptura en la primera línea de código y ejecuta en modo depuración.
 *      Para que se pueda depurar desde la terminal, las peticiones y las respuestas de red se
 *      SIMULAN con flujos data:// (los viste en e09-ficheros). En un servidor de verdad, solo
 *      cambia el origen de los datos.
 *   2. Hay una API de verdad en e07-api.php. Para probarla, abre una terminal en esta carpeta:
 *        php -S localhost:8000
 *      y visita http://localhost:8000/e07-api.php (o usa Postman / Thunder Client / curl:
 *        curl -X POST -H "Content-Type: application/json" -d "{\"mesa\":7,\"platos\":[\"pulpo\"]}" http://localhost:8000/e07-api.php
 *   3. Los comentarios a la derecha indican el resultado esperado.
 */
require_once __DIR__ . '/utilidades.php';

// ---------------------------------------------------------------------------------------------
// header y http_response_code — Responder con JSON
// ENLACE: https://www.php.net/manual/es/function.header.php
// ENLACE: https://www.php.net/manual/es/function.http-response-code.php
// ENUNCIADO EJEMPLO: preparar la respuesta de la API de la carta, tanto si va bien como si hay
//                   un error, siempre con el mismo formato: {"ok": true, ...} / {"ok": false, "error": ...}
// NOTA: en una API real la función haría header(...), http_response_code(...), echo y exit (mira
//       e07-api.php). Aquí devuelve la respuesta en un array para poder verla en el depurador.
//       Las cabeceras se envían ANTES que cualquier salida (ni un espacio antes de <?php).
// ---------------------------------------------------------------------------------------------
titulo('Preparar respuestas JSON');
function respuestaJson(int $codigo, array $datos): array
{
    return [
        'codigo'   => $codigo,
        'cabecera' => 'Content-Type: application/json; charset=utf-8',
        'cuerpo'   => json_encode($datos, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_THROW_ON_ERROR),
    ];
}
$carta = [['id' => 1, 'nombre' => 'Pulpo á feira', 'precio' => 12.5], ['id' => 2, 'nombre' => 'Tortilla', 'precio' => 9.5]];
$correcta = respuestaJson(200, ['ok' => true, 'carta' => $carta]);
$noEncontrado = respuestaJson(404, ['ok' => false, 'error' => 'No existe el plato 99']);
ver('respuesta 200', $correcta);
ver('respuesta 404', $noEncontrado);

// ---------------------------------------------------------------------------------------------
// file_get_contents('php://input') — Leer el cuerpo (body) de la petición
// ENLACE: https://www.php.net/manual/es/wrappers.php.php
// ENUNCIADO EJEMPLO: la app del móvil envía un pedido en JSON por POST; leerlo y decodificarlo.
// NOTA: $_POST solo se rellena con los datos de un FORMULARIO (application/x-www-form-urlencoded
//       o multipart/form-data). Si el cuerpo es JSON, $_POST está VACÍO y hay que leer
//       php://input. Aquí lo simulamos: el origen es un parámetro que en la API real vale
//       'php://input'.
// ---------------------------------------------------------------------------------------------
titulo('Leer el cuerpo de la petición');
function leerCuerpoJson(string $origen = 'php://input'): array
{
    $cuerpo = file_get_contents($origen);
    if ($cuerpo === false || $cuerpo === '') {
        throw new InvalidArgumentException('La petición no tiene cuerpo');
    }
    if (strlen($cuerpo) > 10_000) {
        throw new InvalidArgumentException('La petición es demasiado grande');
    }
    try {
        $datos = json_decode($cuerpo, true, 10, JSON_THROW_ON_ERROR);   // profundidad limitada
    } catch (JsonException $e) {
        throw new InvalidArgumentException('JSON no válido: ' . $e->getMessage());
    }
    if (!is_array($datos)) {
        throw new InvalidArgumentException('Se esperaba un objeto JSON');
    }
    return $datos;
}
// Simulación: lo que llegaría por php://input
$cuerpoRecibido = '{"mesa": 7, "platos": [{"id": 1, "cantidad": 2}, {"id": 2, "cantidad": 1}], "nota": "sin cebolla"}';
$simulado = 'data://application/json;base64,' . base64_encode($cuerpoRecibido);
$pedido = leerCuerpoJson($simulado);
ver('pedido recibido', $pedido);
ver('$_POST', $_POST);                         // array () -> el JSON NO llega en $_POST

try {
    leerCuerpoJson('data://application/json,' . rawurlencode("{'mesa': 7}"));
} catch (InvalidArgumentException $e) {
    ver('cuerpo mal formado', $e->getMessage());   // "JSON no válido: Syntax error"
}

// ---------------------------------------------------------------------------------------------
// Validar los datos recibidos
// ENLACE: https://www.php.net/manual/es/function.is-int.php
// ENUNCIADO EJEMPLO: comprobar que el pedido tiene una mesa entre 1 y 20 y una lista de platos
//                   con id y cantidad enteros y positivos, y que los platos existen en la carta.
// NOTA: devolver TODOS los errores a la vez ayuda a quien programa la app. Nunca confíes en los
//       datos que llegan: aunque los envíe "tu" app, cualquiera puede enviar otra cosa.
// ---------------------------------------------------------------------------------------------
titulo('Validar la petición');
function validarPedido(array $pedido, array $carta): array
{
    $errores = [];
    $mesa = $pedido['mesa'] ?? null;
    if (!is_int($mesa) || $mesa < 1 || $mesa > 20) {
        $errores['mesa'] = 'Debe ser un entero entre 1 y 20';
    }
    $platos = $pedido['platos'] ?? null;
    if (!is_array($platos) || !array_is_list($platos) || count($platos) === 0) {
        $errores['platos'] = 'Debe ser una lista con al menos un plato';
    } else {
        $idsCarta = array_column($carta, 'id');
        foreach ($platos as $i => $plato) {
            $id = $plato['id'] ?? null;
            $cantidad = $plato['cantidad'] ?? null;
            if (!in_array($id, $idsCarta, true)) {
                $errores["platos[$i].id"] = 'El plato no existe';
            }
            if (!is_int($cantidad) || $cantidad < 1) {
                $errores["platos[$i].cantidad"] = 'Debe ser un entero mayor que 0';
            }
        }
    }
    if (isset($pedido['nota']) && !is_string($pedido['nota'])) {
        $errores['nota'] = 'Debe ser un texto';
    }
    return $errores;
}
ver('pedido correcto', validarPedido($pedido, $carta));     // array () -> sin errores
$malo = json_decode('{"mesa": "7", "platos": [{"id": 1, "cantidad": 0}, {"id": 99, "cantidad": 1}]}', true);
$errores = validarPedido($malo, $carta);
ver('pedido incorrecto', $errores);    // mesa, platos[0].cantidad, platos[1].id
ver('respuesta', respuestaJson(400, ['ok' => false, 'errores' => $errores])['cuerpo']);

// ---------------------------------------------------------------------------------------------
// stream_context_create + file_get_contents — Consumir una API externa
// ENLACE: https://www.php.net/manual/es/function.stream-context-create.php
// ENLACE: https://www.php.net/manual/es/context.http.php
// ENLACE: https://www.php.net/manual/es/function.http-build-query.php
// ENUNCIADO EJEMPLO: pedir el tiempo de Baio a un servicio meteorológico para mostrarlo en la web.
// NOTA: con el contexto se indican el método, las cabeceras y un tiempo máximo de espera (si
//       no, una API lenta deja colgada tu página). La URL se construye con http_build_query
//       para codificar bien los parámetros. Aquí la respuesta se simula con data://; para una
//       API real cambia solo la URL (y necesitas allow_url_fopen = On, o la extensión curl).
// ---------------------------------------------------------------------------------------------
titulo('Consumir una API');
$url = 'https://api.example.com/tiempo?' . http_build_query(['ciudad' => 'Baio', 'unidades' => 'métricas']);
ver('URL', $url);        // "https://api.example.com/tiempo?ciudad=Baio&unidades=m%C3%A9tricas"
$contexto = stream_context_create([
    'http' => [
        'method'  => 'GET',
        'header'  => "Accept: application/json\r\n",
        'timeout' => 5,
    ],
]);
function pedirJson(string $url, $contexto): ?array
{
    $respuesta = @file_get_contents($url, false, $contexto);   // @: el error lo tratamos nosotros
    if ($respuesta === false) {
        return null;                                            // sin conexión, tiempo agotado...
    }
    $datos = json_decode($respuesta, true);
    return is_array($datos) ? $datos : null;
}
$simulada = 'data://application/json,' . rawurlencode('{"ciudad":"Baio","temperatura":14.5,"cielo":"chuvia","viento":{"kmh":22,"dir":"SW"}}');
$tiempo = pedirJson($simulada, $contexto);
ver('temperatura', $tiempo['temperatura'] ?? 'sin datos');      // 14.5
ver('viento', ($tiempo['viento']['kmh'] ?? '?') . ' km/h');     // "22 km/h"
$sinRespuesta = pedirJson(rutaDatos('no-existe.json'), $contexto);
ver('si la API no responde', $sinRespuesta);                    // NULL -> la página sigue funcionando

// ---------------------------------------------------------------------------------------------
// htmlspecialchars — Mostrar en una página datos que vienen de un JSON
// ENLACE: https://www.php.net/manual/es/function.htmlspecialchars.php
// ENUNCIADO EJEMPLO: pintar en una tabla HTML las opiniones recibidas de la app.
// NOTA: los textos que vienen de un JSON son datos de fuera: escápalos SIEMPRE antes de
//       mostrarlos, o una opinión con <script> se ejecutaría en el navegador (XSS).
// ---------------------------------------------------------------------------------------------
titulo('Mostrar datos de un JSON en HTML');
$opiniones = json_decode('[{"autor":"Ana","texto":"Muy rico"},{"autor":"<b>Hacker</b>","texto":"<script>alert(1)</script>"}]', true);
$filas = '';
foreach ($opiniones as $o) {
    $filas .= '<tr><td>' . htmlspecialchars($o['autor']) . '</td><td>' . htmlspecialchars($o['texto']) . "</td></tr>\n";
}
ver('tabla', $filas);    // <b> y <script> aparecen como &lt;b&gt; y &lt;script&gt;

// ---------------------------------------------------------------------------------------------
// Incrustar JSON en una página HTML (para el JavaScript de la página)
// ENLACE: https://www.php.net/manual/es/json.constants.php
// ENUNCIADO EJEMPLO: pasar la carta al JavaScript de la página sin hacer otra petición.
// NOTA: dentro de <script>, usa JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT:
//       así ningún dato puede cerrar la etiqueta. En un atributo (data-...), además, escápalo
//       con htmlspecialchars. NUNCA uses eval() para "ejecutar" un JSON.
// ---------------------------------------------------------------------------------------------
titulo('Incrustar JSON en HTML');
$carta[] = ['id' => 3, 'nombre' => "Plato </script><script>alert('XSS')</script>", 'precio' => 1.0];
$jsonSeguro = json_encode($carta, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_UNESCAPED_UNICODE);
$html = "<script>\n  const carta = $jsonSeguro;\n  console.log(carta.length);\n</script>";
ver('HTML generado', $html);
ver('solo hay un </script> (el nuestro)', substr_count($html, '</script>') === 1);    // true: solo el nuestro
$atributo = '<div id="carta" data-carta="' . htmlspecialchars(json_encode($carta, JSON_UNESCAPED_UNICODE)) . '"></div>';
ver('en un atributo', $atributo);

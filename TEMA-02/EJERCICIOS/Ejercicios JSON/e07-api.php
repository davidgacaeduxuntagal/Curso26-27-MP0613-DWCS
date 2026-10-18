<?php
/*
 * e07-api.php
 *  Una API web DE VERDAD (complemento de e07-JSON.php): la carta y los pedidos del Bar Baio.
 *
 *   GET  e07-api.php              -> {"ok":true,"carta":[...]}
 *   GET  e07-api.php?id=2         -> {"ok":true,"plato":{...}}   o 404 si no existe
 *   POST e07-api.php  (cuerpo JSON {"mesa":7,"platos":[{"id":1,"cantidad":2}]})
 *                                 -> 201 {"ok":true,"pedido":{...}}  o 400 con los errores
 *   otro método                   -> 405
 *
 *  Cómo probarla (abre una terminal en esta carpeta):
 *    php -S localhost:8000
 *  y en otra terminal (o con Postman / Thunder Client):
 *    curl http://localhost:8000/e07-api.php
 *    curl "http://localhost:8000/e07-api.php?id=2"
 *    curl -X POST -H "Content-Type: application/json" -d "{\"mesa\":7,\"platos\":[{\"id\":1,\"cantidad\":2}]}" http://localhost:8000/e07-api.php
 *  Los pedidos se añaden a datos/pedidos-api.jsonl (JSON Lines, como en e06).
 *
 *  Para depurarla con VSC, pon un punto de ruptura y lanza la petición desde el navegador o curl
 *  con el depurador escuchando ("Listen for Xdebug").
 */
require_once __DIR__ . '/utilidades.php';

const CARTA = [
    ['id' => 1, 'nombre' => 'Pulpo á feira', 'precio' => 12.5],
    ['id' => 2, 'nombre' => 'Tortilla', 'precio' => 9.5],
    ['id' => 3, 'nombre' => 'Filloas', 'precio' => 4.5],
];

// Envía la respuesta en JSON con su código HTTP y termina el script.
function responder(int $codigo, array $datos): never
{
    http_response_code($codigo);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($datos, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    exit;
}

function buscarPlato(int $id): ?array
{
    foreach (CARTA as $plato) {
        if ($plato['id'] === $id) {
            return $plato;
        }
    }
    return null;
}

$metodo = $_SERVER['REQUEST_METHOD'] ?? 'GET';

if ($metodo === 'GET') {
    if (!isset($_GET['id'])) {
        responder(200, ['ok' => true, 'carta' => CARTA]);
    }
    $id = filter_var($_GET['id'], FILTER_VALIDATE_INT);
    $plato = $id === false ? null : buscarPlato($id);
    if ($plato === null) {
        responder(404, ['ok' => false, 'error' => 'No existe el plato']);
    }
    responder(200, ['ok' => true, 'plato' => $plato]);
}

if ($metodo === 'POST') {
    try {
        $pedido = json_decode(file_get_contents('php://input'), true, 10, JSON_THROW_ON_ERROR);
    } catch (JsonException $e) {
        responder(400, ['ok' => false, 'error' => 'JSON no válido: ' . $e->getMessage()]);
    }
    $errores = [];
    if (!is_array($pedido)) {
        responder(400, ['ok' => false, 'error' => 'Se esperaba un objeto JSON']);
    }
    $mesa = $pedido['mesa'] ?? null;
    if (!is_int($mesa) || $mesa < 1 || $mesa > 20) {
        $errores['mesa'] = 'Debe ser un entero entre 1 y 20';
    }
    $lineas = [];
    $total = 0;
    $platos = $pedido['platos'] ?? null;
    if (!is_array($platos) || !array_is_list($platos) || $platos === []) {
        $errores['platos'] = 'Debe ser una lista con al menos un plato';
    } else {
        foreach ($platos as $i => $linea) {
            $plato = is_int($linea['id'] ?? null) ? buscarPlato($linea['id']) : null;
            $cantidad = $linea['cantidad'] ?? null;
            if ($plato === null) {
                $errores["platos[$i].id"] = 'El plato no existe';
            } elseif (!is_int($cantidad) || $cantidad < 1) {
                $errores["platos[$i].cantidad"] = 'Debe ser un entero mayor que 0';
            } else {
                $lineas[] = ['nombre' => $plato['nombre'], 'cantidad' => $cantidad];
                $total += $plato['precio'] * $cantidad;
            }
        }
    }
    if ($errores !== []) {
        responder(400, ['ok' => false, 'errores' => $errores]);
    }
    $registro = ['fecha' => date(DATE_ATOM), 'mesa' => $mesa, 'platos' => $lineas, 'total' => $total];
    file_put_contents(rutaDatos('pedidos-api.jsonl'), json_encode($registro, JSON_UNESCAPED_UNICODE) . "\n", FILE_APPEND | LOCK_EX);
    responder(201, ['ok' => true, 'pedido' => $registro]);
}

header('Allow: GET, POST');
responder(405, ['ok' => false, 'error' => 'Método no permitido']);

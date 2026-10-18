<?php
/*
 * e07-JSON_problema.php
 *  Problemas de: respuestas JSON de una API (códigos HTTP), leer el cuerpo de la petición,
 *                validar datos recibidos, consumir una API, paginación, incrustar JSON en HTML
 *  (repasa los ejemplos de ../e07-JSON.php y ../e07-api.php)
 *
 *  Resuelve cada problema donde se indica y ejecútalo en modo depuración para comprobarlo.
 *  Como en e07-JSON.php, las peticiones y respuestas de red se simulan (arrays y data://) para
 *  poder depurar desde la terminal.
 */
require_once __DIR__ . '/../utilidades.php';

$carta = [
    ['id' => 1, 'nombre' => 'Pulpo á feira', 'categoria' => 'tapas', 'precio' => 12.5],
    ['id' => 2, 'nombre' => 'Tortilla', 'categoria' => 'tapas', 'precio' => 9.5],
    ['id' => 3, 'nombre' => 'Filloas', 'categoria' => 'postres', 'precio' => 4.5],
    ['id' => 4, 'nombre' => 'Tarta de Santiago', 'categoria' => 'postres', 'precio' => 5.0],
];

// Devuelve la respuesta en un array (en una API real: http_response_code, header y echo)
function respuesta(int $codigo, array $datos): array
{
    return ['codigo' => $codigo, 'cuerpo' => json_encode($datos, JSON_UNESCAPED_UNICODE)];
}

// =============================================================================================
// PROBLEMA 1. El "enrutador" de la API de la carta
//  Escribe apiCarta(array $get, array $carta): array, que reciba los parámetros de la URL
//  (lo que sería $_GET) y devuelva con respuesta():
//   - sin parámetros: 200 y toda la carta          {"ok":true,"carta":[...]}
//   - ?categoria=postres: 200 y solo esa categoría (una LISTA, aunque se filtre)
//   - ?id=3: 200 y ese plato                         {"ok":true,"plato":{...}}
//   - ?id=99 o ?id=abc: 404                          {"ok":false,"error":"..."}
// =============================================================================================
titulo('Problema 1');
// completa aquí tu solución

// =============================================================================================
// PROBLEMA 2. Alta de reservas
//  Escribe apiNuevaReserva(string $cuerpo): array que reciba el cuerpo JSON de la petición (lo
//  que sería file_get_contents('php://input')) y lo valide: nombre (texto no vacío), email
//  (válido con filter_var), personas (entero de 1 a 12) y fecha ("AAAA-MM-DD" válida). Debe
//  devolver 400 si el JSON no es válido o hay errores (con TODOS los errores), y 201 con la
//  reserva si todo es correcto.
// =============================================================================================
titulo('Problema 2');
$peticiones = [
    '{"nombre": "Ana", "email": "ana@example.com", "personas": 4, "fecha": "2026-11-20"}',
    '{"nombre": "", "email": "ana@", "personas": "4", "fecha": "2026-02-30"}',
    '{"nombre": "Ana", "personas": 4',
];
// completa aquí tu solución

// =============================================================================================
// PROBLEMA 3. Consumir la API de un proveedor
//  El proveedor de pescado publica sus precios del día en una API. Escribe preciosProveedor(
//  string $url): array que la consulte con un contexto (GET, cabecera Accept: application/json,
//  timeout de 3 segundos) y devuelva un array "producto => precio por kg" SOLO con los
//  productos disponibles. Si la API no responde, el JSON no es válido o falta la clave
//  "productos", debe devolver []. Pruébala con las tres URL simuladas.
// =============================================================================================
titulo('Problema 3');
$urlCorrecta = 'data://application/json,' . rawurlencode('{"fecha":"2026-10-16","productos":[{"nombre":"pulpo","kg":14.9,"disponible":true},{"nombre":"zamburiña","kg":22,"disponible":false},{"nombre":"merluza","kg":11.5,"disponible":true}]}');
$urlRota = 'data://application/json,' . rawurlencode('<html>Error 500</html>');
$urlCaida = rutaDatos('p07_api_que_no_existe.json');
// completa aquí tu solución

// =============================================================================================
// PROBLEMA 4. Paginación
//  Las APIs no devuelven miles de datos de golpe: los dividen en páginas. Escribe
//  paginar(array $datos, int $pagina, int $porPagina): array que devuelva
//   ["pagina" => 2, "porPagina" => 10, "total" => 23, "paginas" => 3, "datos" => [...]]
//  Si la página no existe, "datos" es una lista vacía. Pruébala con las 23 opiniones.
// =============================================================================================
titulo('Problema 4');
$opiniones = [];
for ($i = 1; $i <= 23; $i++) {
    $opiniones[] = ['id' => $i, 'nota' => $i % 5 + 1];
}
// completa aquí tu solución

// =============================================================================================
// PROBLEMA 5. ¿Formulario o JSON?
//  Un mismo script recibe datos a veces desde un formulario HTML y a veces desde la app (JSON).
//  ¿Cómo harías una función leerDatos(string $contentType, array $post, string $cuerpo): array
//  que devuelva los datos en los dos casos? (En la API real se llamaría con
//  $_SERVER['CONTENT_TYPE'] ?? '', $_POST y file_get_contents('php://input').)
// =============================================================================================
titulo('Problema 5');
// completa aquí tu solución

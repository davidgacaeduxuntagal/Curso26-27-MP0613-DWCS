<?php
/*
 * e06-JSON.php
 *  JSON (6): JSON Y FICHEROS
 *  Funciones: file_put_contents + json_encode (guardar), file_get_contents + json_decode (cargar),
 *             LOCK_EX, escritura atómica (rename), modificar datos (CRUD), JSON Lines (FILE_APPEND,
 *             file), validar la estructura al cargar
 *  (apartado 5 de los apuntes)
 *
 *  Cómo trabajar este fichero:
 *   1. Pon un punto de ruptura en la primera línea de código y ejecuta en modo depuración.
 *   2. Ten abierta la carpeta "datos" en el explorador de VSC y abre carta.json: verás cómo
 *      cambia el fichero a medida que avanzas con F10.
 *   3. Los comentarios a la derecha indican el resultado esperado.
 */
require_once __DIR__ . '/utilidades.php';

const OPCIONES_FICHERO = JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_THROW_ON_ERROR;

// ---------------------------------------------------------------------------------------------
// Guardar datos en un fichero JSON: json_encode + file_put_contents
// ENLACE: https://www.php.net/manual/es/function.file-put-contents.php
// ENLACE: https://www.php.net/manual/es/function.json-encode.php
// ENUNCIADO EJEMPLO: guardar la carta del bar en datos/carta.json.
// NOTA: JSON_PRETTY_PRINT + JSON_UNESCAPED_UNICODE dejan un fichero fácil de leer y de corregir
//       a mano. LOCK_EX evita que dos peticiones escriban a la vez y lo mezclen.
// ---------------------------------------------------------------------------------------------
titulo('Guardar en un fichero JSON');
function guardarJson(string $fichero, array $datos): void
{
    $json = json_encode($datos, OPCIONES_FICHERO);
    file_put_contents($fichero, $json . "\n", LOCK_EX);
}

$ficheroCarta = rutaDatos('carta.json');
$carta = [
    ['id' => 1, 'nombre' => 'Pulpo á feira', 'categoria' => 'tapas', 'precio' => 12.5],
    ['id' => 2, 'nombre' => 'Tortilla', 'categoria' => 'tapas', 'precio' => 9.0],
    ['id' => 3, 'nombre' => 'Filloas', 'categoria' => 'postres', 'precio' => 4.5],
];
guardarJson($ficheroCarta, $carta);
ver('guardado', is_file($ficheroCarta));                 // true
echo file_get_contents($ficheroCarta);
// Fíjate: el precio 9.0 se ha guardado como 9 (al cargarlo será un int). Si necesitas que siga
// siendo float, añade JSON_PRESERVE_ZERO_FRACTION (e03) o conviértelo con (float) al cargar.

// ---------------------------------------------------------------------------------------------
// Cargar un fichero JSON: file_get_contents + json_decode
// ENLACE: https://www.php.net/manual/es/function.file-get-contents.php
// ENLACE: https://www.php.net/manual/es/function.json-decode.php
// ENUNCIADO EJEMPLO: cargar la carta (si el fichero aún no existe, la carta está vacía).
// NOTA: comprueba que el fichero existe, que el JSON es válido (JSON_THROW_ON_ERROR) y que el
//       resultado es un array: si alguien ha editado el fichero a mano, puede estar roto.
// ---------------------------------------------------------------------------------------------
titulo('Cargar un fichero JSON');
function cargarJson(string $fichero): array
{
    if (!is_file($fichero)) {
        return [];
    }
    $datos = json_decode(file_get_contents($fichero), true, 512, JSON_THROW_ON_ERROR);
    if (!is_array($datos)) {
        throw new UnexpectedValueException("$fichero no contiene un objeto o una lista JSON");
    }
    return $datos;
}
$carta = cargarJson($ficheroCarta);
ver('platos cargados', count($carta));                   // 3
ver('primer plato', $carta[0]['nombre']);                // "Pulpo á feira"
ver('fichero que no existe', cargarJson(rutaDatos('no-existe.json')));   // array ()

// ---------------------------------------------------------------------------------------------
// Modificar los datos: cargar, cambiar el array y guardar (CRUD)
// ENLACE: https://www.php.net/manual/es/function.array-filter.php
// ENUNCIADO EJEMPLO: añadir un plato, subir un precio y retirar un plato de la carta.
// NOTA: con JSON no se puede modificar "un trozo" del fichero: siempre se reescribe entero.
//       Al borrar con array_filter, usa array_values para que siga siendo una lista (e01).
// ---------------------------------------------------------------------------------------------
titulo('Modificar (CRUD)');
// Crear: el nuevo id es el mayor + 1
$carta = cargarJson($ficheroCarta);
$nuevoId = max(array_column($carta, 'id')) + 1;
$carta[] = ['id' => $nuevoId, 'nombre' => 'Empanada de zamburiñas', 'categoria' => 'tapas', 'precio' => 8.0];
guardarJson($ficheroCarta, $carta);
ver('nuevo id', $nuevoId);                                // 4

// Actualizar: el plato con id 2 sube a 9.5
$carta = cargarJson($ficheroCarta);
foreach ($carta as &$plato) {          // & para modificar el elemento del array, no una copia
    if ($plato['id'] === 2) {
        $plato['precio'] = 9.5;
    }
}
unset($plato);                         // ¡importante después de un foreach con &!
guardarJson($ficheroCarta, $carta);

// Borrar: retirar el plato con id 1
$carta = cargarJson($ficheroCarta);
$carta = array_values(array_filter($carta, fn($p) => $p['id'] !== 1));
guardarJson($ficheroCarta, $carta);

$final = cargarJson($ficheroCarta);
ver('nombres', array_column($final, 'nombre'));          // ['Tortilla', 'Filloas', 'Empanada de zamburiñas']
ver('precio tortilla', $final[0]['precio']);             // 9.5
ver('sigue siendo lista', array_is_list($final));        // true

// ---------------------------------------------------------------------------------------------
// Escritura atómica: escribir en un temporal y renombrar
// ENLACE: https://www.php.net/manual/es/function.rename.php
// ENUNCIADO EJEMPLO: que el fichero de la carta nunca quede a medias aunque el servidor falle
//                   mientras se escribe.
// NOTA: rename() dentro de la misma carpeta es atómico: quien lea el fichero verá la versión
//       anterior completa o la nueva completa, nunca una mezcla.
// ---------------------------------------------------------------------------------------------
titulo('Escritura atómica');
function guardarJsonAtomico(string $fichero, array $datos): void
{
    $json = json_encode($datos, OPCIONES_FICHERO);      // si falla, lanza excepción y no se toca nada
    $temporal = $fichero . '.tmp';
    file_put_contents($temporal, $json . "\n");
    rename($temporal, $fichero);
}
$final[] = ['id' => 5, 'nombre' => 'Tarta de Santiago', 'categoria' => 'postres', 'precio' => 5.0];
guardarJsonAtomico($ficheroCarta, $final);
ver('platos', count(cargarJson($ficheroCarta)));          // 4
ver('queda el temporal', is_file($ficheroCarta . '.tmp')); // false

// ---------------------------------------------------------------------------------------------
// Validar la ESTRUCTURA al cargar
// ENLACE: https://www.php.net/manual/es/function.is-array.php
// ENUNCIADO EJEMPLO: el dueño ha editado carta.json a mano y un precio es "doce" y a otro plato
//                   le falta el nombre. Detectarlo y no mostrar platos incorrectos.
// NOTA: que el JSON sea válido no significa que los datos sean correctos. Comprueba cada campo.
// ---------------------------------------------------------------------------------------------
titulo('Validar la estructura');
file_put_contents(rutaDatos('carta-editada.json'), <<<JSON
[
    {"id": 1, "nombre": "Pulpo", "categoria": "tapas", "precio": 12.5},
    {"id": 2, "nombre": "Tortilla", "categoria": "tapas", "precio": "doce"},
    {"id": 3, "categoria": "postres", "precio": 4.5}
]
JSON);
function platoValido(mixed $plato): bool
{
    return is_array($plato)
        && is_int($plato['id'] ?? null)
        && is_string($plato['nombre'] ?? null) && trim($plato['nombre']) !== ''
        && (is_int($plato['precio'] ?? null) || is_float($plato['precio'] ?? null))
        && $plato['precio'] > 0;
}
$editada = cargarJson(rutaDatos('carta-editada.json'));
$validos = array_values(array_filter($editada, 'platoValido'));
$errores = array_keys(array_filter($editada, fn($p) => !platoValido($p)));
ver('platos válidos', array_column($validos, 'nombre'));   // ['Pulpo']
ver('posiciones con errores', $errores);                   // [1, 2]

// Y si el fichero ni siquiera es JSON válido:
file_put_contents(rutaDatos('carta-rota.json'), "[{'id': 1, 'nombre': 'Pulpo'},]");
try {
    cargarJson(rutaDatos('carta-rota.json'));
} catch (JsonException $e) {
    ver('carta-rota.json', 'no se puede cargar: ' . $e->getMessage());   // "...: Syntax error"
}

// ---------------------------------------------------------------------------------------------
// JSON Lines (.jsonl): un documento JSON por línea
// ENLACE: https://jsonlines.org/  (formato)
// ENLACE: https://www.php.net/manual/es/function.file.php
// ENUNCIADO EJEMPLO: guardar un historial de pedidos añadiendo cada pedido al final, sin tener
//                   que leer y reescribir todo el fichero.
// NOTA: cada línea es un JSON completo SIN JSON_PRETTY_PRINT (no puede tener saltos de línea).
//       Se añade con FILE_APPEND | LOCK_EX y se lee línea a línea con file() o fgets().
// ---------------------------------------------------------------------------------------------
titulo('JSON Lines');
$historial = rutaDatos('pedidos.jsonl');
if (is_file($historial)) {
    unlink($historial);                // empezamos de cero en cada ejecución del ejemplo
}
function registrarPedido(string $fichero, array $pedido): void
{
    $linea = json_encode($pedido, JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR) . "\n";
    file_put_contents($fichero, $linea, FILE_APPEND | LOCK_EX);
}
registrarPedido($historial, ['hora' => '13:05', 'mesa' => 3, 'total' => 21.5]);
registrarPedido($historial, ['hora' => '13:20', 'mesa' => 7, 'total' => 33.5]);
registrarPedido($historial, ['hora' => '14:10', 'mesa' => 3, 'total' => 12.0]);
echo file_get_contents($historial);

$pedidos = [];
foreach (file($historial, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $linea) {
    $pedidos[] = json_decode($linea, true, 512, JSON_THROW_ON_ERROR);
}
ver('pedidos', count($pedidos));                                    // 3
ver('caja del día', array_sum(array_column($pedidos, 'total')));    // 67.0

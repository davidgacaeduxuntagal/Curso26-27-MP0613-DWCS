<?php
/*
 * e09-ficheros.php
 *  FICHEROS (9): flujos (streams). Las mismas funciones de ficheros sirven para memoria, la
 *  salida, filtros, datos incrustados o la red.
 *  Funciones / envoltorios: php://memory, php://temp, php://output, php://filter, data://,
 *             stream_get_meta_data, stream_get_wrappers, stream_context_create
 *
 *  Ejecuta en modo depuración (F10). Despliega los arrays de stream_get_meta_data().
 */
require_once __DIR__ . '/utilidades.php';

// ---------------------------------------------------------------------------------------------
// php://memory — un "fichero" que solo existe en memoria
// ENLACE: https://www.php.net/manual/es/wrappers.php.php
// ENUNCIADO EJEMPLO: generar un CSV con fputcsv SIN crear ningún fichero en disco, para obtenerlo
//   como string (por ejemplo, para enviarlo por correo o descargarlo).
// NOTA: después de escribir hay que hacer rewind para leer desde el principio.
// ---------------------------------------------------------------------------------------------
titulo('php://memory');
$memoria       = fopen('php://memory', 'w+');
fputcsv($memoria, ['producto', 'precio'], ';');
fputcsv($memoria, ['Café', '1,50'], ';');
fputcsv($memoria, ['Tortilla', '3,20'], ';');
rewind($memoria);
$csv           = stream_get_contents($memoria);
fclose($memoria);
ver('CSV generado en memoria', $csv);

// ---------------------------------------------------------------------------------------------
// stream_get_meta_data — información de un flujo abierto
// ENLACE: https://www.php.net/manual/es/function.stream-get-meta-data.php
// ENUNCIADO EJEMPLO: comparar la información de un fichero de disco y de php://temp.
// NOTA: php://temp es como php://memory, pero si los datos superan 2 MB pasa a usar un fichero
//   temporal en disco (más seguro para datos grandes).
// ---------------------------------------------------------------------------------------------
titulo('stream_get_meta_data');
$fichero       = fopen(__FILE__, 'r');
$temp          = fopen('php://temp', 'w+');
ver('fichero de disco', stream_get_meta_data($fichero));     // wrapper_type "plainfile", uri = ruta
ver('php://temp', stream_get_meta_data($temp));             // wrapper_type "PHP", stream_type "TEMP"
fclose($fichero);
fclose($temp);

// ---------------------------------------------------------------------------------------------
// php://output — escribir en la SALIDA del script (lo que recibe el navegador) como si fuera un fichero
// ENUNCIADO EJEMPLO: una función que escribe un informe en el fichero que le pasemos: con
//   php://output, el mismo código lo muestra por pantalla en lugar de guardarlo.
// ---------------------------------------------------------------------------------------------
titulo('php://output');
function escribirInforme($destino, array $ventas): void
{
    foreach ($ventas as $mes => $importe) {
        fprintf($destino, "%-10s %8.2f €\n", $mes, $importe);
    }
}
$ventas        = ['octubre' => 1250.5, 'noviembre' => 980];
$salida        = fopen('php://output', 'w');
escribirInforme($salida, $ventas);                   // sale por pantalla
fclose($salida);
$enDisco       = fopen(rutaDatos('informe_ventas.txt'), 'w');
escribirInforme($enDisco, $ventas);                  // el MISMO código, ahora a un fichero
fclose($enDisco);
ver('informe en disco', file_get_contents(rutaDatos('informe_ventas.txt')));

// ---------------------------------------------------------------------------------------------
// php://filter — aplicar filtros al leer o escribir
// ENLACE: https://www.php.net/manual/es/wrappers.php.php
// ENLACE: https://www.php.net/manual/es/filters.php
// ENUNCIADO EJEMPLO: (1) leer un fichero pasándolo a mayúsculas; (2) guardar un fichero codificado
//   en base64 al escribirlo, sin llamar a base64_encode.
// NOTA: filtros habituales: string.toupper, string.tolower, string.rot13, convert.base64-encode,
//   convert.base64-decode.
// ---------------------------------------------------------------------------------------------
titulo('php://filter');
$aviso         = rutaDatos('aviso.txt');
file_put_contents($aviso, "cerrado por vacaciones\n");
ver('en mayúsculas', file_get_contents('php://filter/read=string.toupper/resource=' . $aviso));

file_put_contents('php://filter/write=convert.base64-encode/resource=' . rutaDatos('aviso.b64'), "secreto del chef");
ver('fichero guardado (base64)', file_get_contents(rutaDatos('aviso.b64')));          // "c2VjcmV0byBkZWwgY2hlZg=="
ver('leído decodificando', file_get_contents('php://filter/read=convert.base64-decode/resource=' . rutaDatos('aviso.b64')));

// ---------------------------------------------------------------------------------------------
// data:// — los datos van escritos en la propia "ruta" (RFC 2397)
// ENLACE: https://www.php.net/manual/es/wrappers.data.php
// ENUNCIADO EJEMPLO: probar una función que lee un CSV línea a línea pasándole datos de prueba
//   directamente, sin crear un fichero.
// ---------------------------------------------------------------------------------------------
titulo('data://');
function contarFilasCsv(string $ruta): int
{
    $fp    = fopen($ruta, 'r');
    $filas = 0;
    while (fgetcsv($fp) !== false) {
        $filas++;
    }
    fclose($fp);
    return $filas;
}
ver('filas (datos incrustados)', contarFilasCsv("data://text/plain,a,b\nc,d\ne,f"));                 // 3
ver('filas (base64)', contarFilasCsv('data://text/plain;base64,' . base64_encode("x,y\nz,w")));    // 2

// ---------------------------------------------------------------------------------------------
// stream_get_wrappers — envoltorios (protocolos) disponibles en esta instalación de PHP
// ENLACE: https://www.php.net/manual/es/function.stream-get-wrappers.php
// ENUNCIADO EJEMPLO: comprobar si podemos usar https:// con fopen / file_get_contents (necesita
//   la extensión openssl) y si allow_url_fopen está activado.
// ---------------------------------------------------------------------------------------------
titulo('stream_get_wrappers');
$envoltorios   = stream_get_wrappers();
ver('envoltorios', $envoltorios);
ver('¿https disponible?', in_array('https', $envoltorios, true));
ver('allow_url_fopen', (bool) ini_get('allow_url_fopen'));

// ---------------------------------------------------------------------------------------------
// stream_context_create — opciones para un flujo (cabeceras HTTP, método, tiempo de espera...)
// ENLACE: https://www.php.net/manual/es/function.stream-context-create.php
// ENUNCIADO EJEMPLO: preparar una petición HTTP con un tiempo máximo de 3 segundos y un
//   User-Agent propio. Aquí la lanzamos contra una dirección que no existe para ver cómo se
//   comprueba el error (no hace falta conexión a Internet).
// NOTA: el contexto se pasa como TERCER parámetro de file_get_contents (o cuarto de fopen).
// ---------------------------------------------------------------------------------------------
titulo('stream_context_create');
$contexto      = stream_context_create([
    'http' => [
        'method'  => 'GET',
        'timeout' => 3,
        'header'  => "User-Agent: BarBaio/1.0\r\nAccept: application/json\r\n",
    ],
]);
ver('opciones del contexto', stream_context_get_options($contexto));
$respuesta     = @file_get_contents('http://127.0.0.1:9/no-hay-servidor', false, $contexto);
ver('respuesta', $respuesta);                           // false: no hay ningún servidor escuchando
if ($respuesta === false) {
    ver('error', error_get_last()['message']);
}

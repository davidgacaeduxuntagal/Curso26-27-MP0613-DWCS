<?php
/*
 * 08_regexes_preg_02.php
 *  REGEXES - API PCRE (2): REEMPLAZAR
 *  Funciones: preg_replace, preg_filter, preg_replace_callback, preg_replace_callback_array
 *
 *  En el texto de reemplazo se puede usar lo que capturaron los grupos:
 *      $0 (o \0)  todo el texto encontrado      $1, $2...  grupo 1, 2...     ${1}0  grupo 1 seguido de un "0"
 *  ¡Escribe el reemplazo con COMILLAS SIMPLES! Con dobles, PHP intentaría sustituir $1 como variable.
 *
 *  Ejecuta en modo depuración: con F10 para ver los resultados y con F11 para entrar en las
 *  funciones que se pasan como parámetro (callbacks) y ver qué array $m reciben.
 */
require_once __DIR__ . '/utilidades.php';

// ---------------------------------------------------------------------------------------------
// preg_replace — reemplaza las coincidencias de un patrón
// ENLACE: https://www.php.net/manual/es/function.preg-replace.php
// ENUNCIADO EJEMPLO: (1) convertir fechas "2026-10-16" del texto al formato español "16/10/2026";
//   (2) dejar un solo espacio donde haya varios; (3) varios patrones a la vez con arrays;
//   (4) limitar el número de reemplazos y contar los hechos.
// NOTA: parámetros (patrón, reemplazo, texto, límite, &contador). Patrón y reemplazo pueden ser
//   arrays (el 1º patrón con el 1º reemplazo...). El texto también puede ser un array.
// ---------------------------------------------------------------------------------------------
titulo('preg_replace');
$aviso         = "Examen el 2026-11-20 y entrega el 2026-12-01.";
$espanol       = preg_replace('/(\d{4})-(\d{2})-(\d{2})/', '$3/$2/$1', $aviso);
ver('fechas en español', $espanol);       // "Examen el 20/11/2026 y entrega el 01/12/2026."

$conEspacios   = "Demasiados      espacios   aquí";
ver('un solo espacio', preg_replace('/\s+/', ' ', $conEspacios));

$markdown      = "Esto es **importante** y esto _cursiva_.";
$html          = preg_replace(['/\*\*(.+?)\*\*/', '/_(.+?)_/'], ['<b>$1</b>', '<i>$1</i>'], $markdown);
ver('markdown a HTML', $html);            // "Esto es <b>importante</b> y esto <i>cursiva</i>."

$tarjeta       = "4111 2222 3333 4444";
$oculta        = preg_replace('/\d/', '*', $tarjeta, 12, $cambios);   // solo los 12 primeros dígitos
ver('tarjeta oculta', $oculta);           // "**** **** **** 4444"
ver('reemplazos', $cambios);              // 12

// ---------------------------------------------------------------------------------------------
// preg_filter — como preg_replace, pero con un ARRAY de textos devuelve SOLO los que han cambiado
// ENLACE: https://www.php.net/manual/es/function.preg-filter.php
// ENUNCIADO EJEMPLO: de una lista de teléfonos, formatear los que son móviles (empiezan por 6 o
//   7) como "612 345 678" y descartar el resto.
// ---------------------------------------------------------------------------------------------
titulo('preg_filter');
$telefonos     = ["612345678", "986123456", "722000111"];
$conReplace    = preg_replace('/^([67]\d{2})(\d{3})(\d{3})$/', '$1 $2 $3', $telefonos);
$conFilter     = preg_filter('/^([67]\d{2})(\d{3})(\d{3})$/', '$1 $2 $3', $telefonos);
ver('preg_replace (todos)', $conReplace);   // el fijo se queda igual
ver('preg_filter (solo los cambiados)', $conFilter);   // [0 => "612 345 678", 2 => "722 000 111"]

// ---------------------------------------------------------------------------------------------
// preg_replace_callback — el reemplazo lo calcula una FUNCIÓN que recibe el array de coincidencias
// ENLACE: https://www.php.net/manual/es/function.preg-replace-callback.php
// ENUNCIADO EJEMPLO: (1) aplicar un 10 % de descuento a todos los precios de un texto;
//   (2) rellenar una plantilla {{nombre}} con los datos de un array (si falta un dato, se deja
//   el marcador).
// NOTA: la función recibe $m igual que preg_match ($m[0], $m[1]...) y debe DEVOLVER el string
//   que sustituye a $m[0].
// ---------------------------------------------------------------------------------------------
titulo('preg_replace_callback');
$oferta        = "Camiseta 20,00 €, pantalón 35,50 € y gorra 12,00 €";
$rebajado      = preg_replace_callback('/(\d+),(\d{2}) €/', function ($m) {
    $precio = (float) "$m[1].$m[2]";
    return number_format($precio * 0.9, 2, ',', '.') . ' €';
}, $oferta);
ver('precios rebajados', $rebajado);      // "Camiseta 18,00 €, pantalón 31,95 € y gorra 10,80 €"

$plantilla     = "Hola {{nombre}}, tu nota en {{modulo}} es {{nota}}. {{despedida}}";
$datos         = ['nombre' => "Ana", 'modulo' => "DWCS", 'nota' => "8,5"];
$mensaje       = preg_replace_callback('/\{\{(\w+)\}\}/', fn($m) => $datos[$m[1]] ?? $m[0], $plantilla);
ver('plantilla', $mensaje);               // {{despedida}} se queda tal cual

// ---------------------------------------------------------------------------------------------
// preg_replace_callback_array — varios patrones, cada uno con SU función, en una sola llamada
// ENLACE: https://www.php.net/manual/es/function.preg-replace-callback-array.php
// ENUNCIADO EJEMPLO: "anonimizar" un texto antes de publicarlo: ocultar los emails (dejando el
//   dominio), los teléfonos (dejando los 3 últimos dígitos) y los DNI.
// NOTA: el array es patrón => función. Los patrones se aplican en orden, uno detrás de otro.
// ---------------------------------------------------------------------------------------------
titulo('preg_replace_callback_array');
$texto         = "Contacto: ana.perez@iesrodeira.gal, tel. 612345678, DNI 12345678Z.";
$anonimo       = preg_replace_callback_array([
    '/[\w.+-]+(@[\w.-]+)/'      => fn($m) => '***' . $m[1],
    '/\b\d{6}(\d{3})\b/'        => fn($m) => '******' . $m[1],
    '/\b\d{8}[A-Z]\b/'          => fn($m) => '[DNI oculto]',
], $texto);
ver('texto anonimizado', $anonimo);   // "Contacto: ***@iesrodeira.gal, tel. ******678, DNI [DNI oculto]."

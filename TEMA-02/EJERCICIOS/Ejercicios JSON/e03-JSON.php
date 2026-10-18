<?php
/*
 * e03-JSON.php
 *  JSON (3): OPCIONES DE json_encode
 *  Opciones: JSON_PRETTY_PRINT, JSON_UNESCAPED_UNICODE, JSON_UNESCAPED_SLASHES, combinar con |,
 *            JSON_FORCE_OBJECT, JSON_PRESERVE_ZERO_FRACTION, JSON_NUMERIC_CHECK,
 *            JSON_HEX_TAG / JSON_HEX_AMP / JSON_HEX_APOS / JSON_HEX_QUOT, profundidad
 *  (apartado 2.3 de los apuntes)
 *
 *  Cómo trabajar este fichero:
 *   1. Pon un punto de ruptura en la primera línea de código y ejecuta en modo depuración.
 *   2. Avanza con F10 y compara el texto JSON obtenido con y sin cada opción.
 *   3. Los comentarios a la derecha indican el resultado esperado.
 */
require_once __DIR__ . '/utilidades.php';

$plato = [
    'nombre'    => 'Pulpo á feira',
    'precio'    => 12.5,
    'foto'      => 'https://barbaio.gal/img/pulpo.jpg',
    'alergenos' => ['moluscos'],
];

// ---------------------------------------------------------------------------------------------
// JSON_PRETTY_PRINT — Saltos de línea y sangría (4 espacios)
// ENLACE: https://www.php.net/manual/es/json.constants.php
// ENUNCIADO EJEMPLO: guardar el plato en un fichero que una persona pueda leer y corregir.
// NOTA: ocupa más bytes. Úsalo en ficheros y para depurar; en una API no es necesario
//       (el navegador o Postman ya lo muestran formateado).
// ---------------------------------------------------------------------------------------------
titulo('JSON_PRETTY_PRINT');
$compacto  = json_encode($plato);
$formateado = json_encode($plato, JSON_PRETTY_PRINT);
echo $formateado, nl();
ver('bytes compacto', strlen($compacto));         // 117
ver('bytes formateado', strlen($formateado));     // 156

// ---------------------------------------------------------------------------------------------
// JSON_UNESCAPED_UNICODE y JSON_UNESCAPED_SLASHES
// ENLACE: https://www.php.net/manual/es/json.constants.php
// ENUNCIADO EJEMPLO: que el nombre y la URL de la foto se lean tal cual en el JSON.
// NOTA: sin opciones, "á" se escribe \u00e1 y "/" se escribe \/. Las dos formas son JSON
//       válido y se decodifican igual; la diferencia es la legibilidad (y el tamaño).
// ---------------------------------------------------------------------------------------------
titulo('JSON_UNESCAPED_UNICODE y JSON_UNESCAPED_SLASHES');
ver('sin opciones', json_encode($plato['nombre']) . ' ' . json_encode($plato['foto']));
ver('UNESCAPED_UNICODE', json_encode($plato['nombre'], JSON_UNESCAPED_UNICODE));   // "Pulpo á feira"
ver('UNESCAPED_SLASHES', json_encode($plato['foto'], JSON_UNESCAPED_SLASHES));     // "https://barbaio.gal/img/pulpo.jpg"
$a = json_decode('"Pulpo \u00e1 feira"');
$b = json_decode('"Pulpo á feira"');
ver('se decodifican igual', $a === $b);           // true

// ---------------------------------------------------------------------------------------------
// Combinar opciones con el operador de bits |
// ENLACE: https://www.php.net/manual/es/language.operators.bitwise.php
// ENUNCIADO EJEMPLO: la combinación más habitual para guardar ficheros legibles.
// NOTA: cada opción es un bit de un número entero, y | los junta. NO uses || ni OR: son
//       operadores lógicos, darían true (que vale 1, es decir, JSON_HEX_TAG) y no lo esperado.
// ---------------------------------------------------------------------------------------------
titulo('Combinar opciones con |');
ver('JSON_PRETTY_PRINT', JSON_PRETTY_PRINT);              // 128
ver('JSON_UNESCAPED_SLASHES', JSON_UNESCAPED_SLASHES);    // 64
ver('JSON_UNESCAPED_UNICODE', JSON_UNESCAPED_UNICODE);    // 256
$opciones = JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES;
ver('combinadas', $opciones);                             // 448 (128 + 64 + 256)
echo json_encode($plato, $opciones), nl();
$mal = JSON_PRETTY_PRINT || JSON_UNESCAPED_UNICODE;       // ERROR TÍPICO
ver('con || (mal)', $mal);                                // true
ver('resultado con ||', json_encode('<b>', (int) $mal));  // "\u003Cb\u003E": ¡ha aplicado JSON_HEX_TAG!

// ---------------------------------------------------------------------------------------------
// JSON_FORCE_OBJECT — Convierte también las listas en objetos
// ENLACE: https://www.php.net/manual/es/json.constants.php
// ENUNCIADO EJEMPLO: una aplicación antigua espera siempre objetos, aunque los datos sean listas.
// NOTA: se aplica a TODOS los niveles (también a las listas de dentro).
// ---------------------------------------------------------------------------------------------
titulo('JSON_FORCE_OBJECT');
ver('normal', json_encode(['pulpo', 'pan']));                        // ["pulpo","pan"]
ver('forzado', json_encode(['pulpo', 'pan'], JSON_FORCE_OBJECT));    // {"0":"pulpo","1":"pan"}
ver('vacío forzado', json_encode([], JSON_FORCE_OBJECT));            // {}
ver('anidado', json_encode(['platos' => ['pulpo']], JSON_FORCE_OBJECT));   // {"platos":{"0":"pulpo"}}

// ---------------------------------------------------------------------------------------------
// JSON_PRESERVE_ZERO_FRACTION — Escribe 10.0 en lugar de 10 para los float
// ENLACE: https://www.php.net/manual/es/json.constants.php
// ENUNCIADO EJEMPLO: que el precio 10.0 se siga decodificando como float (y no como int).
// ---------------------------------------------------------------------------------------------
titulo('JSON_PRESERVE_ZERO_FRACTION');
$precio = 10.0;
$sin = json_encode($precio);                                   // "10"
$con = json_encode($precio, JSON_PRESERVE_ZERO_FRACTION);      // "10.0"
ver('sin opción', $sin);
ver('con opción', $con);
ver('tipo al volver (sin)', gettype(json_decode($sin)));      // "integer"
ver('tipo al volver (con)', gettype(json_decode($con)));      // "double"

// ---------------------------------------------------------------------------------------------
// JSON_NUMERIC_CHECK — Convierte en números los strings que lo parecen
// ENLACE: https://www.php.net/manual/es/json.constants.php
// ENUNCIADO EJEMPLO: los datos de un formulario llegan como strings; ¿enviarlos como números?
// NOTA: ¡PELIGROSA! Convierte TODO lo que parece un número: los teléfonos y códigos postales
//       pierden los ceros de la izquierda. Es mejor convertir a mano solo los campos numéricos
//       ((int), (float), filter_var).
// ---------------------------------------------------------------------------------------------
titulo('JSON_NUMERIC_CHECK');
$cliente = ['nombre' => 'Ana', 'edad' => '34', 'telefono' => '0612345678', 'cp' => '08001'];
ver('sin opción', json_encode($cliente));
ver('con opción', json_encode($cliente, JSON_NUMERIC_CHECK));  // ¡"telefono":612345678,"cp":8001!
$correcto = $cliente;
$correcto['edad'] = (int) $cliente['edad'];                    // solo el campo que es un número
ver('a mano', json_encode($correcto));                        // edad 34, teléfono y cp intactos

// ---------------------------------------------------------------------------------------------
// JSON_HEX_TAG, JSON_HEX_AMP, JSON_HEX_APOS, JSON_HEX_QUOT — Escapar < > & ' "
// ENLACE: https://www.php.net/manual/es/json.constants.php
// ENUNCIADO EJEMPLO: meter datos en JSON dentro de una etiqueta <script> o de un atributo HTML.
// NOTA: con las cuatro opciones, el JSON no contiene ningún carácter "peligroso" para HTML, así
//       que un texto como </script> no puede cerrar la etiqueta (lo verás en e07).
//       Por defecto la / ya se escapa (<\/script>), pero en cuanto se usa
//       JSON_UNESCAPED_SLASHES esa protección desaparece.
// ---------------------------------------------------------------------------------------------
titulo('JSON_HEX_*');
$opinion = "Muy bueno </script><script>alert('hack')</script> & \"barato\"";
$seguro = JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT;
ver('normal', json_encode($opinion));
ver('sin escapar /', json_encode($opinion, JSON_UNESCAPED_SLASHES));   // ¡contiene </script>!
ver('seguro', json_encode($opinion, $seguro));
ver('contiene </script>', str_contains(json_encode($opinion, $seguro), '</script>'));   // false
ver('se recupera igual', json_decode(json_encode($opinion, $seguro)) === $opinion);    // true

// ---------------------------------------------------------------------------------------------
// json_encode — profundidad máxima ($depth)
// ENLACE: https://www.php.net/manual/es/function.json-encode.php
// ENUNCIADO EJEMPLO: ver qué pasa al codificar datos con demasiados niveles.
// NOTA: si los datos superan la profundidad, json_encode devuelve false (no un string).
//       Por eso se comprueba con === false (los errores se ven en e04).
// ---------------------------------------------------------------------------------------------
titulo('json_encode: profundidad');
$arbol = ['nivel1' => ['nivel2' => ['nivel3' => 'hoja']]];
ver('profundidad 512', json_encode($arbol));                 // {"nivel1":{"nivel2":{"nivel3":"hoja"}}}
ver('profundidad 2', json_encode($arbol, 0, 2));             // false
ver('error', json_last_error_msg());                         // "Maximum stack depth exceeded"

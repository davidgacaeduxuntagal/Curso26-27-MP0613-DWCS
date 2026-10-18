<?php
/*
 * 11_arrays_conjuntos_01.php
 *  ARRAYS - OPERACIONES DE CONJUNTOS (1): diferencia e intersección
 *  Funciones: array_diff, array_diff_assoc, array_diff_key, array_intersect, array_intersect_assoc,
 *             array_intersect_key
 *
 *      diff      -> elementos del PRIMER array que NO están en los demás   (A - B)
 *      intersect -> elementos del PRIMER array que SÍ están en todos los demás (A ∩ B)
 *  y según qué se compara:
 *      (sin sufijo) -> solo los VALORES
 *      _key         -> solo las CLAVES
 *      _assoc       -> CLAVE y VALOR a la vez
 *  Todas conservan las claves del PRIMER array. La unión (A ∪ B) se hace con
 *  array_unique(array_merge($a, $b)).
 *
 *  Ejecuta en modo depuración paso a paso (F10).
 */
require_once __DIR__ . '/utilidades.php';

// ---------------------------------------------------------------------------------------------
// array_diff — valores del primer array que no están en los demás
// ENLACE: https://www.php.net/manual/es/function.array-diff.php
// ENUNCIADO EJEMPLO: saber qué alumnos matriculados NO han entregado la tarea, y qué productos de
//   la lista de la compra faltan en la despensa.
// NOTA: compara los valores como strings: 1 y "1" se consideran iguales.
// ---------------------------------------------------------------------------------------------
titulo('array_diff');
$matriculados  = ["Ana", "Brais", "Carmen", "Diego", "Eva"];
$entregados    = ["Carmen", "Ana", "Eva"];
$sinEntregar   = array_diff($matriculados, $entregados);      // [1 => Brais, 3 => Diego]
ver('no han entregado', $sinEntregar);

$lista         = ["leche", "pan", "huevos", "café"];
$despensa      = ["café", "arroz", "leche"];
ver('hay que comprar', array_values(array_diff($lista, $despensa)));   // [pan, huevos]

// ---------------------------------------------------------------------------------------------
// array_intersect — valores del primer array que están también en todos los demás
// ENLACE: https://www.php.net/manual/es/function.array-intersect.php
// ENUNCIADO EJEMPLO: los alumnos que están matriculados en DWCS y también en DWCC; las etiquetas
//   comunes a dos artículos (para recomendar artículos relacionados).
// ---------------------------------------------------------------------------------------------
titulo('array_intersect');
$dwcs          = ["Ana", "Brais", "Carmen", "Diego"];
$dwcc          = ["Carmen", "Eva", "Ana", "Fran"];
$ambos         = array_intersect($dwcs, $dwcc);               // [0 => Ana, 2 => Carmen]
ver('matriculados en los dos módulos', $ambos);

$union         = array_values(array_unique(array_merge($dwcs, $dwcc)));
ver('matriculados en alguno (unión)', $union);

// ---------------------------------------------------------------------------------------------
// array_diff_key — elementos cuyas CLAVES no están en los demás arrays
// ENLACE: https://www.php.net/manual/es/function.array-diff-key.php
// ENUNCIADO EJEMPLO: comprobar qué campos obligatorios de un formulario no han llegado en $_POST
//   (las claves del array de obligatorios que no están en los datos recibidos).
// ---------------------------------------------------------------------------------------------
titulo('array_diff_key');
$obligatorios  = ['nombre' => "Nombre", 'email' => "Email", 'password' => "Contraseña"];
$recibidos     = ['nombre' => "Ana", 'telefono' => "612345678"];
$faltan        = array_diff_key($obligatorios, $recibidos);   // ['email' => "Email", 'password' => "Contraseña"]
ver('campos que faltan', $faltan);
ver('mensaje', "Faltan: " . implode(", ", $faltan));

// ---------------------------------------------------------------------------------------------
// array_intersect_key — elementos cuyas CLAVES están en todos los demás arrays
// ENLACE: https://www.php.net/manual/es/function.array-intersect-key.php
// ENUNCIADO EJEMPLO: de todo lo que llega en un formulario, quedarnos SOLO con los campos que
//   esperamos (lista blanca), descartando campos añadidos por un usuario malintencionado.
// NOTA: el truco array_flip convierte una lista de nombres en un array de claves.
// ---------------------------------------------------------------------------------------------
titulo('array_intersect_key');
$post          = ['nombre' => "Ana", 'email' => "ana@x.gal", 'es_admin' => "1"];   // ¡es_admin no debería llegar!
$permitidos    = ["nombre", "email", "telefono"];
$limpio        = array_intersect_key($post, array_flip($permitidos));   // sin es_admin
ver('datos permitidos', $limpio);

// ---------------------------------------------------------------------------------------------
// array_diff_assoc — elementos cuya pareja CLAVE + VALOR no está en los demás arrays
// ENLACE: https://www.php.net/manual/es/function.array-diff-assoc.php
// ENUNCIADO EJEMPLO: saber qué datos del perfil ha cambiado un usuario al editarlo (comparando los
//   datos nuevos con los que había guardados).
// ---------------------------------------------------------------------------------------------
titulo('array_diff_assoc');
$guardado      = ['nombre' => "Ana", 'ciudad' => "Vigo", 'telefono' => "612345678"];
$editado       = ['nombre' => "Ana", 'ciudad' => "Lugo", 'telefono' => "612345678"];
$cambios       = array_diff_assoc($editado, $guardado);   // ['ciudad' => "Lugo"]
ver('campos modificados', $cambios);

$otro          = array_diff(['a' => "x", 'b' => "y"], ['b' => "x", 'a' => "y"]);           // [] (mismos valores)
$otroAssoc     = array_diff_assoc(['a' => "x", 'b' => "y"], ['b' => "x", 'a' => "y"]);    // ['a' => x, 'b' => y]
ver('array_diff con valores intercambiados', $otro);
ver('array_diff_assoc con valores intercambiados', $otroAssoc);

// ---------------------------------------------------------------------------------------------
// array_intersect_assoc — elementos cuya pareja CLAVE + VALOR está en todos los demás arrays
// ENLACE: https://www.php.net/manual/es/function.array-intersect-assoc.php
// ENUNCIADO EJEMPLO: corregir un examen tipo test: las respuestas del alumno (pregunta => opción)
//   que coinciden con la plantilla de soluciones son los aciertos.
// ---------------------------------------------------------------------------------------------
titulo('array_intersect_assoc');
$soluciones    = [1 => "b", 2 => "a", 3 => "d", 4 => "c", 5 => "a"];
$respuestas    = [1 => "b", 2 => "c", 3 => "d", 4 => "a", 5 => "a"];
$aciertos      = array_intersect_assoc($respuestas, $soluciones);   // [1 => b, 3 => d, 5 => a]
ver('aciertos', $aciertos);
ver('nota sobre 10', count($aciertos) * 10 / count($soluciones));   // 6

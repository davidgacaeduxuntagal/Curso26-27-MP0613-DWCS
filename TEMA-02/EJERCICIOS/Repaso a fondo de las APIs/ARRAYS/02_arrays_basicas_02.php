<?php
/*
 * 02_arrays_basicas_02.php
 *  ARRAYS - BÁSICAS (2): añadir y quitar elementos por los extremos (pilas y colas)
 *  Funciones: array_push, array_pop, array_unshift, array_shift, unset, array_key_first, array_key_last
 *
 *    PILA (LIFO, el último en entrar es el primero en salir):  array_push + array_pop
 *    COLA (FIFO, el primero en entrar es el primero en salir): array_push + array_shift
 *
 *  Ejecuta en modo depuración paso a paso (F10). Observa cómo cambian el array y sus CLAVES
 *  después de cada operación.
 */
require_once __DIR__ . '/utilidades.php';

// ---------------------------------------------------------------------------------------------
// array_push — añade uno o varios elementos al FINAL. Devuelve el nuevo número de elementos
// ENLACE: https://www.php.net/manual/es/function.array-push.php
// ENUNCIADO EJEMPLO: un navegador guarda el historial de páginas visitadas en una pila.
// NOTA: para añadir un solo elemento es más habitual (y rápido) escribir $array[] = valor;
// ---------------------------------------------------------------------------------------------
titulo('array_push');
$historial     = ["inicio.php"];
$total         = array_push($historial, "productos.php", "carrito.php");   // 3
$historial[]   = "pago.php";                                               // igual que array_push con 1 elemento
ver('historial', $historial);
ver('elementos tras array_push', $total);

// ---------------------------------------------------------------------------------------------
// array_pop — quita el ÚLTIMO elemento y lo devuelve
// ENLACE: https://www.php.net/manual/es/function.array-pop.php
// ENUNCIADO EJEMPLO: el botón "Atrás" del navegador saca la última página del historial.
// NOTA: devuelve null si el array está vacío. Modifica el array (se pasa por referencia).
// ---------------------------------------------------------------------------------------------
titulo('array_pop');
$actual        = array_pop($historial);          // "pago.php"
$anterior      = end($historial);                // "carrito.php" (la página a la que volvemos)
ver('página que se quita', $actual);
ver('volvemos a', $anterior);
ver('historial', $historial);

$vacio         = [];
ver('array_pop de un array vacío', array_pop($vacio));    // null

// ---------------------------------------------------------------------------------------------
// array_shift — quita el PRIMER elemento y lo devuelve (las claves numéricas se renumeran)
// ENLACE: https://www.php.net/manual/es/function.array-shift.php
// ENUNCIADO EJEMPLO: una cola de impresión: los documentos se imprimen en el orden en que llegan.
// ---------------------------------------------------------------------------------------------
titulo('array_shift');
$colaImpresion = ["informe.pdf", "factura.pdf", "foto.jpg"];
$imprimiendo   = array_shift($colaImpresion);    // "informe.pdf"
ver('se imprime', $imprimiendo);
ver('quedan en cola', $colaImpresion);           // claves renumeradas: 0 => factura.pdf, 1 => foto.jpg

// ---------------------------------------------------------------------------------------------
// array_unshift — añade uno o varios elementos al PRINCIPIO. Devuelve el nuevo número de elementos
// ENLACE: https://www.php.net/manual/es/function.array-unshift.php
// ENUNCIADO EJEMPLO: un documento urgente se cuela al principio de la cola de impresión, y se
//   añade la opción "-- Elige --" al principio de un desplegable.
// ---------------------------------------------------------------------------------------------
titulo('array_unshift');
array_unshift($colaImpresion, "URGENTE.pdf");
ver('cola con urgente', $colaImpresion);

$provincias    = ["A Coruña", "Lugo", "Ourense", "Pontevedra"];
array_unshift($provincias, "-- Elige --");
ver('opciones del desplegable', $provincias);

// ---------------------------------------------------------------------------------------------
// unset — elimina un elemento (o una variable). NO renumera las claves
// ENLACE: https://www.php.net/manual/es/function.unset.php
// ENUNCIADO EJEMPLO: quitar del carrito el producto que el usuario borra. Observa que quedan
//   "huecos" en las claves; array_values los quita (ver 09_arrays_iterar_01.php).
// NOTA: unset no es una función sino una construcción del lenguaje. Eliminar una clave que no
//   existe no da error.
// ---------------------------------------------------------------------------------------------
titulo('unset');
$carrito       = ["pan", "leche", "huevos", "café"];
unset($carrito[1]);                              // quitamos "leche"
ver('tras unset', $carrito);                     // [0 => pan, 2 => huevos, 3 => café]  <- falta la clave 1
$carrito[]     = "azúcar";                       // ¿qué clave recibe? la 4 (siguiente a la mayor)
ver('tras añadir', $carrito);

$alumno        = ['nombre' => "Ana", 'password' => "1234", 'email' => "ana@x.gal"];
unset($alumno['password']);                      // no queremos mostrar la contraseña
ver('alumno sin contraseña', $alumno);

// ---------------------------------------------------------------------------------------------
// array_key_first / array_key_last — primera y última CLAVE de un array (PHP 7.3)
// ENLACE: https://www.php.net/manual/es/function.array-key-first.php
// ENLACE: https://www.php.net/manual/es/function.array-key-last.php
// ENUNCIADO EJEMPLO: en un array de temperaturas por fecha, saber cuál es la primera y la
//   última fecha registradas y sus valores, sin mover el puntero interno del array.
// NOTA: devuelven null si el array está vacío.
// ---------------------------------------------------------------------------------------------
titulo('array_key_first / array_key_last');
$temperaturas  = ['2026-10-14' => 18.5, '2026-10-15' => 17.0, '2026-10-16' => 15.5];
$primera       = array_key_first($temperaturas);         // "2026-10-14"
$ultima        = array_key_last($temperaturas);          // "2026-10-16"
ver('primera fecha', "$primera: {$temperaturas[$primera]} ºC");
ver('última fecha', "$ultima: {$temperaturas[$ultima]} ºC");

ver('última clave del carrito', array_key_last($carrito));   // 4

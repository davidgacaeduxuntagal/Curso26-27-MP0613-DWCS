<?php
/*
 * 07_arrays_ordenar_01.php
 *  ARRAYS - ORDENAR (1): las funciones de ordenación básicas
 *  Funciones: sort, rsort, asort, arsort, ksort, krsort, array_reverse
 *
 *  Cómo recordar los nombres:
 *      sort   -> ordena por VALOR y PIERDE las claves (renumera 0, 1, 2...)
 *      asort  -> ordena por VALOR y CONSERVA las claves ("a" de asociativo)
 *      ksort  -> ordena por CLAVE ("k" de key)
 *      r...   -> en orden inverso (reverse): rsort, arsort, krsort
 *  Todas MODIFICAN el array original (se pasa por referencia) y devuelven true.
 *
 *  Ejecuta en modo depuración paso a paso (F10). Antes de cada ordenación copia el array para
 *  poder comparar el antes y el después en el panel VARIABLES.
 */
require_once __DIR__ . '/utilidades.php';

$notas = ['Uxía' => 8.5, 'Brais' => 6, 'Carmen' => 9.75, 'Antón' => 4];

// ---------------------------------------------------------------------------------------------
// sort — ordena por valor de menor a mayor y renumera las claves
// ENLACE: https://www.php.net/manual/es/function.sort.php
// ENUNCIADO EJEMPLO: ordenar los precios de unos productos para ver el más barato, y ver qué
//   pasa si lo usamos con un array asociativo (¡se pierden los nombres!).
// NOTA: el segundo parámetro cambia el tipo de comparación: SORT_STRING, SORT_NUMERIC,
//   SORT_NATURAL, SORT_FLAG_CASE...
// ---------------------------------------------------------------------------------------------
titulo('sort');
$precios       = [19.99, 5.5, 120, 45];
sort($precios);                               // [5.5, 19.99, 45, 120]
ver('precios ordenados', $precios);
ver('el más barato', $precios[0]);

$copia         = $notas;
sort($copia);                                 // [4, 6, 8.5, 9.75]  <- ¡sin los nombres!
ver('sort con asociativo (se pierden las claves)', $copia);

$palabras      = ["banana", "Manzana", "cereza"];
sort($palabras);                                         // Manzana, banana, cereza (mayúsculas antes)
$sinMayusculas = $palabras;
sort($sinMayusculas, SORT_STRING | SORT_FLAG_CASE);      // banana, cereza, Manzana
ver('sort de palabras', $palabras);
ver('sort sin distinguir mayúsculas', $sinMayusculas);

// ---------------------------------------------------------------------------------------------
// rsort — como sort pero de mayor a menor
// ENLACE: https://www.php.net/manual/es/function.rsort.php
// ENUNCIADO EJEMPLO: las 3 puntuaciones más altas de un juego.
// ---------------------------------------------------------------------------------------------
titulo('rsort');
$puntuaciones  = [1200, 850, 3100, 990, 2500];
rsort($puntuaciones);
ver('top 3', array_slice($puntuaciones, 0, 3));          // [3100, 2500, 1200]

// ---------------------------------------------------------------------------------------------
// asort — ordena por valor de menor a mayor CONSERVANDO las claves
// ENLACE: https://www.php.net/manual/es/function.asort.php
// ENUNCIADO EJEMPLO: listado de alumnos ordenado por nota (de menor a mayor) sin perder sus nombres.
// ---------------------------------------------------------------------------------------------
titulo('asort');
$copia         = $notas;
asort($copia);                                // Antón 4, Brais 6, Uxía 8.5, Carmen 9.75
ver('asort', $copia);

// ---------------------------------------------------------------------------------------------
// arsort — ordena por valor de mayor a menor CONSERVANDO las claves
// ENLACE: https://www.php.net/manual/es/function.arsort.php
// ENUNCIADO EJEMPLO: clasificación de una votación: candidatos ordenados por número de votos.
// ---------------------------------------------------------------------------------------------
titulo('arsort');
$votos         = ['Ana' => 120, 'Brais' => 310, 'Carmen' => 205];
arsort($votos);                               // Brais 310, Carmen 205, Ana 120
ver('clasificación', $votos);
ver('ganador', array_key_first($votos));      // "Brais"

// ---------------------------------------------------------------------------------------------
// ksort — ordena por CLAVE de menor a mayor
// ENLACE: https://www.php.net/manual/es/function.ksort.php
// ENUNCIADO EJEMPLO: listado de alumnos por orden alfabético de nombre; datos por fecha (las
//   fechas en formato AAAA-MM-DD se ordenan bien como texto).
// NOTA: con nombres con tilde ("Antón" frente a "Ángel"), el orden no es el del diccionario
//   (compara bytes). Ver strcoll en las actividades de STRINGS.
// ---------------------------------------------------------------------------------------------
titulo('ksort');
$copia         = $notas;
ksort($copia);                                // Antón, Brais, Carmen, Uxía
ver('ksort', $copia);

$ventas        = ['2026-10-16' => 300, '2026-09-30' => 120, '2026-10-01' => 250];
ksort($ventas);
ver('ventas por fecha', $ventas);

// ---------------------------------------------------------------------------------------------
// krsort — ordena por CLAVE de mayor a menor
// ENLACE: https://www.php.net/manual/es/function.krsort.php
// ENUNCIADO EJEMPLO: mostrar las noticias de la más reciente a la más antigua (clave = fecha).
// ---------------------------------------------------------------------------------------------
titulo('krsort');
$noticias      = ['2026-10-01' => "Comienza el curso", '2026-10-16' => "Excursión", '2026-10-10' => "Charla de seguridad"];
krsort($noticias);
ver('noticias recientes primero', $noticias);

// ---------------------------------------------------------------------------------------------
// array_reverse — devuelve un array NUEVO con los elementos en orden inverso (no ordena)
// ENLACE: https://www.php.net/manual/es/function.array-reverse.php
// ENUNCIADO EJEMPLO: mostrar los últimos mensajes de un chat del más nuevo al más antiguo.
// NOTA: no modifica el original. Las claves numéricas se renumeran salvo con el 2º parámetro true;
//   las claves de texto se conservan siempre.
// ---------------------------------------------------------------------------------------------
titulo('array_reverse');
$chat          = ["Hola", "¿Qué tal?", "Bien, ¿y tú?"];
$alReves       = array_reverse($chat);                    // ["Bien, ¿y tú?", "¿Qué tal?", "Hola"]
$conClaves     = array_reverse($chat, true);              // [2 => ..., 1 => ..., 0 => ...]
ver('array_reverse', $alReves);
ver('array_reverse conservando claves', $conClaves);
ver('el original no cambia', $chat);

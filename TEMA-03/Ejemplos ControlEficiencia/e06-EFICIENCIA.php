<?php
/*
 * e06-EFICIENCIA.php
 *  EJERCICIO RESUELTO: ¿HAY CORREOS REPETIDOS? CUATRO SOLUCIONES Y CÓMO CRECE SU TIEMPO
 *  Bucles anidados, in_array dentro de un bucle, isset con un array de "vistos", array_unique;
 *  medir con tamaños que se duplican para ver la complejidad: O(n²) frente a O(n)
 *  (apartado 8 de los apuntes)
 *
 *  Enunciado: el instituto recibe la lista de inscritos en un curso (un array de correos) y antes de guardarla
 *  hay que comprobar que nadie se ha inscrito dos veces. Programa varias soluciones, comprueba que dan lo mismo
 *  y mide cómo crece el tiempo cuando la lista se duplica: 1000, 2000, 4000 y 8000 correos.
 *
 *  Cómo trabajar este fichero:
 *   1. Ejecútalo en la terminal SIN depurador: php -d xdebug.mode=off e06-EFICIENCIA.php
 *   2. Fíjate en la columna "x" (cuántas veces más tarda que con la mitad de correos): ~4 para las soluciones
 *      cuadráticas y ~2 para las lineales.
 */
require_once __DIR__ . '/utilidades.php';
require_once __DIR__ . '/Cronometro.php';

// ---------------------------------------------------------------------------------------------
// Solución 1 — Comparar cada correo con todos los siguientes (dos bucles anidados)
// ENLACE: https://www.php.net/manual/es/control-structures.for.php
// ENUNCIADO EJEMPLO: la primera idea que se le ocurre a cualquiera.
// NOTA: con n correos hace n·(n-1)/2 comparaciones: con 8000, ¡casi 32 millones! Es O(n²): si la lista se
//       duplica, el tiempo se multiplica por 4.
// ---------------------------------------------------------------------------------------------
function repetidosAnidados(array $correos): bool
{
    $n = count($correos);
    for ($i = 0; $i < $n; $i++) {
        for ($j = $i + 1; $j < $n; $j++) {
            if ($correos[$i] === $correos[$j]) {
                return true;
            }
        }
    }
    return false;
}

// ---------------------------------------------------------------------------------------------
// Solución 2 — Un bucle y in_array con los ya vistos
// ENLACE: https://www.php.net/manual/es/function.in-array.php
// ENUNCIADO EJEMPLO: "solo hay un bucle, así que será rápida"... ¿seguro?
// NOTA: ¡TRAMPA! in_array recorre $vistos por dentro: sigue habiendo dos bucles, uno escondido. También es O(n²).
//       Medir lo descubre aunque el código "parezca" lineal.
// ---------------------------------------------------------------------------------------------
function repetidosInArray(array $correos): bool
{
    $vistos = [];
    foreach ($correos as $correo) {
        if (in_array($correo, $vistos, true)) {
            return true;
        }
        $vistos[] = $correo;
    }
    return false;
}

// ---------------------------------------------------------------------------------------------
// Solución 3 — Un bucle y los vistos como CLAVES de un array (isset)
// ENLACE: https://www.php.net/manual/es/function.isset.php
// ENUNCIADO EJEMPLO: la versión buena para listas grandes.
// NOTA: isset($vistos[$correo]) va directo a la clave, tarde lo mismo haya 10 o 10 000: O(n) en total. Si la
//       lista se duplica, el tiempo solo se duplica.
// ---------------------------------------------------------------------------------------------
function repetidosIsset(array $correos): bool
{
    $vistos = [];
    foreach ($correos as $correo) {
        if (isset($vistos[$correo])) {
            return true;
        }
        $vistos[$correo] = true;
    }
    return false;
}

// ---------------------------------------------------------------------------------------------
// Solución 4 — array_unique: si quita alguno, había repetidos
// ENLACE: https://www.php.net/manual/es/function.array-unique.php
// ENUNCIADO EJEMPLO: la versión más corta, con una función de PHP.
// NOTA: una sola línea y también crece de forma casi lineal (por dentro ordena: O(n log n)). Es la más rápida de
//       las cuatro porque el trabajo lo hace PHP por dentro, en C, sin ejecutar nuestro bucle. Pero siempre
//       recorre la lista entera aunque el repetido esté al principio, y crea un array nuevo (más memoria).
// ---------------------------------------------------------------------------------------------
function repetidosUnique(array $correos): bool
{
    return count(array_unique($correos)) !== count($correos);
}

// ---------------------------------------------------------------------------------------------
// Comprobar que las cuatro dan lo mismo — Antes de medir, que funcionen
// ENLACE: https://www.php.net/manual/es/function.array-map.php
// ENUNCIADO EJEMPLO: una lista con un repetido y otra sin ninguno.
// NOTA: medir una función que da un resultado incorrecto no sirve de nada.
// ---------------------------------------------------------------------------------------------
titulo('¿Dan lo mismo?');
$soluciones = [
    'anidados' => 'repetidosAnidados',
    'in_array' => 'repetidosInArray',
    'isset' => 'repetidosIsset',
    'array_unique' => 'repetidosUnique',
];
$conRepetido = ['ana@iesrodeira.gal', 'brais@iesrodeira.gal', 'uxia@iesrodeira.gal', 'brais@iesrodeira.gal'];
$sinRepetidos = ['ana@iesrodeira.gal', 'brais@iesrodeira.gal', 'uxia@iesrodeira.gal'];
ver('con repetido', array_map(fn($f) => $f($conRepetido), $soluciones));     // las cuatro: true
ver('sin repetidos', array_map(fn($f) => $f($sinRepetidos), $soluciones));   // las cuatro: false

// ---------------------------------------------------------------------------------------------
// Medir con tamaños que se duplican — Ver la complejidad
// ENLACE: https://www.php.net/manual/es/function.str-pad.php
// ENUNCIADO EJEMPLO: ¿qué pasará el día que se inscriban 100 000 personas?
// NOTA: se usa el PEOR caso (sin repetidos: hay que mirarlo todo). Para cada tamaño se mide cada solución y se
//       calcula cuántas veces tarda más que con la mitad.
// ---------------------------------------------------------------------------------------------
titulo('¿Cómo crece el tiempo?');

/** Rellena con espacios hasta $ancho caracteres (str_pad cuenta bytes y la "µ" ocupa 2). */
function columna(string $texto, int $ancho): string
{
    return $texto . str_repeat(' ', max(0, $ancho - mb_strlen($texto)));
}

$tamanios = [1000, 2000, 4000, 8000];
$anterior = [];
echo '  ', columna('n', 6), implode('', array_map(fn($s) => columna($s, 20), array_keys($soluciones))), nl();
foreach ($tamanios as $n) {
    $correos = array_map(fn($i) => "inscrito$i@iesrodeira.gal", range(1, $n));
    $fila = '  ' . columna((string) $n, 6);
    foreach ($soluciones as $nombre => $funcion) {
        [$hay, $ms] = Cronometro::medir($funcion, $correos);
        $factor = isset($anterior[$nombre]) ? ' x' . number_format($ms / $anterior[$nombre], 1, ',', '') : '';
        $fila .= columna(Cronometro::formatear($ms) . $factor, 20);
        $anterior[$nombre] = $ms;
    }
    echo $fila, nl();
}
// n     anidados            in_array            isset               array_unique        (aprox.)
// 1000  9,78 ms             1,85 ms             56 µs               10 µs
// 2000  38,44 ms x3,9       5,79 ms x3,1        99 µs x1,8          20 µs x2,0
// 4000  155,65 ms x4,0      25,57 ms x4,4       219 µs x2,2         42 µs x2,0
// 8000  634,62 ms x4,1      114,22 ms x4,5      445 µs x2,0         125 µs x3,0
// Las dos cuadráticas se multiplican por ~4 al duplicar n; las otras dos, por ~2. Con 100 000 correos, la de
// los bucles anidados tardaría (100000/8000)² = 156 veces más: más de minuto y medio.

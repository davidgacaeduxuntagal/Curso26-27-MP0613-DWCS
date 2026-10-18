<?php
/*
 * e01-SplFixedArray.php
 *  ARRAY DE TAMAÑO FIJO con SplFixedArray (1): LA API
 *  Métodos: new SplFixedArray($tamano), $a[i] (índices enteros de 0 a tamaño-1), getSize /
 *           count, setSize (crecer y encoger), toArray, fromArray (con y sin conservar las
 *           claves), foreach, errores (índice fuera de rango, claves de texto, $a[] = ...),
 *           memoria frente a un array normal
 *
 *  Un ARRAY DE TAMAÑO FIJO es la estructura más básica: N casillas numeradas de 0 a N-1. Su
 *  tamaño se decide al crearlo (aunque se puede cambiar a propósito con setSize) y solo admite
 *  índices ENTEROS. A cambio, ocupa menos memoria que un array de PHP, que en realidad es una
 *  tabla hash ordenada con claves de cualquier tipo.
 *
 *  Cómo trabajar este fichero:
 *   1. Pon un punto de ruptura en la primera línea de código y ejecuta en modo depuración.
 *   2. En el panel VARIABLES, un SplFixedArray se ve como una lista de casillas 0..N-1.
 */
require_once __DIR__ . '/../../utilidades.php';

// ---------------------------------------------------------------------------------------------
// Crear, leer y escribir
// ENLACE: https://www.php.net/manual/es/class.splfixedarray.php
// ENLACE: https://www.php.net/manual/es/splfixedarray.construct.php
// ENUNCIADO EJEMPLO: las 7 temperaturas máximas de la semana (lunes = 0 ... domingo = 6).
// NOTA: las casillas empiezan valiendo null. El tamaño no cambia al escribir.
// ---------------------------------------------------------------------------------------------
titulo('Crear, leer y escribir');
$semana = new SplFixedArray(7);
ver('recién creado', $semana);              // SplFixedArray(7) [0 => NULL, ..., 6 => NULL]
$semana[0] = 14.5;
$semana[1] = 16.0;
$semana[6] = 19.5;
ver('con datos', $semana);
ver('miércoles (sin dato)', $semana[2]);    // NULL
ver('getSize / count', [$semana->getSize(), count($semana)]);   // [7, 7]

// ---------------------------------------------------------------------------------------------
// Errores: solo índices enteros dentro del rango
// ENLACE: https://www.php.net/manual/es/splfixedarray.offsetset.php
// ENUNCIADO EJEMPLO: intentar guardar el "domingo" en la casilla 7 o con una clave de texto.
// NOTA: un array normal aceptaría todo esto en silencio y crecería. SplFixedArray avisa: es más
//       difícil equivocarse. Un string numérico como '3' sí se acepta (se convierte en 3).
// ---------------------------------------------------------------------------------------------
titulo('Errores');
verError('índice 7', fn() => $semana[7] = 20.0);          // RuntimeException: Index invalid or out of range
verError('índice -1', fn() => $semana[-1]);               // RuntimeException: Index invalid or out of range
verError("clave 'lunes'", function () use ($semana) {
    $semana['lunes'] = 20.0;
});                                                        // TypeError: Illegal offset type
verError('$semana[] = ...', function () use ($semana) {
    $semana[] = 20.0;
});                                                        // Error: [] operator not supported for SplFixedArray
$semana['3'] = 17.25;                                      // '3' -> 3: permitido
ver('jueves', $semana[3]);                                 // 17.25

// ---------------------------------------------------------------------------------------------
// setSize — Cambiar el tamaño a propósito
// ENLACE: https://www.php.net/manual/es/splfixedarray.setsize.php
// ENUNCIADO EJEMPLO: ampliar el registro a 10 días y después recortarlo a 3.
// NOTA: al crecer, las casillas nuevas valen null; al encoger, los datos que sobran se PIERDEN.
// ---------------------------------------------------------------------------------------------
titulo('setSize');
$copia = clone $semana;
$copia->setSize(10);
ver('10 casillas', $copia->getSize());          // 10
$copia->setSize(3);
ver('3 casillas', $copia);                      // [0 => 14.5, 1 => 16.0, 2 => NULL]: el domingo se perdió

// ---------------------------------------------------------------------------------------------
// toArray, fromArray y foreach
// ENLACE: https://www.php.net/manual/es/splfixedarray.toarray.php
// ENLACE: https://www.php.net/manual/es/splfixedarray.fromarray.php
// ENUNCIADO EJEMPLO: convertir entre SplFixedArray y array para usar funciones de arrays.
// NOTA: fromArray($array) conserva las claves por defecto (el tamaño será la clave mayor + 1 y
//       los huecos quedan en null); con fromArray($array, false), los valores se colocan seguidos.
//       Las claves deben ser enteros no negativos.
// ---------------------------------------------------------------------------------------------
titulo('toArray, fromArray, foreach');
$conDatos = array_filter($semana->toArray(), fn($t) => $t !== null);
ver('media de los días con dato', round(array_sum($conDatos) / count($conDatos), 2));   // 16.81
$huecos = SplFixedArray::fromArray([3 => 'c', 1 => 'a']);
ver('conservando claves', $huecos);             // [0 => NULL, 1 => 'a', 2 => NULL, 3 => 'c']
ver('sin conservar', SplFixedArray::fromArray([3 => 'c', 1 => 'a'], false));   // [0 => 'c', 1 => 'a']
verError('claves de texto', fn() => SplFixedArray::fromArray(['a' => 1]));   // InvalidArgumentException: array must contain only positive integer keys
$dias = ['L', 'M', 'X', 'J', 'V', 'S', 'D'];
foreach ($semana as $i => $t) {
    echo "  {$dias[$i]}: ", $t ?? '-', nl();    // L: 14.5 / M: 16 / X: - / J: 17.25 / ... / D: 19.5
}

// ---------------------------------------------------------------------------------------------
// Memoria: SplFixedArray frente a array
// ENLACE: https://www.php.net/manual/es/function.memory-get-usage.php
// ENUNCIADO EJEMPLO: guardar 100 000 lecturas de un sensor.
// NOTA: un SplFixedArray guarda solo los valores, uno detrás de otro; un array de PHP guarda
//       además una tabla hash para poder tener cualquier clave (salvo las listas 0, 1, 2...,
//       que PHP optimiza). La diferencia se nota con muchos elementos.
// ---------------------------------------------------------------------------------------------
titulo('Memoria');
$n = 100_000;
$antes = memory_get_usage();
$array = [];
for ($i = 0; $i < $n; $i++) {
    $array["lectura$i"] = $i * 0.5;            // un array "asociativo": tabla hash
}
$memArray = memory_get_usage() - $antes;
$antes = memory_get_usage();
$lista = [];
for ($i = 0; $i < $n; $i++) {
    $lista[] = $i * 0.5;                       // una lista 0, 1, 2...: PHP la guarda "empaquetada"
}
$memLista = memory_get_usage() - $antes;
$antes = memory_get_usage();
$fijo = new SplFixedArray($n);
for ($i = 0; $i < $n; $i++) {
    $fijo[$i] = $i * 0.5;
}
$memFijo = memory_get_usage() - $antes;
ver('¿ocupa menos el SplFixedArray?', $memFijo < $memArray && $memFijo <= $memLista);   // true
printf('  array asociativo: %.1f MB | lista: %.1f MB | SplFixedArray: %.1f MB%s',
    $memArray / 1048576, $memLista / 1048576, $memFijo / 1048576, nl());
// Con PHP 8.2: unos 9,8 MB, 4 MB y 1,5 MB. La lista ocupa más porque, al crecer con [], PHP va
// duplicando su capacidad; el SplFixedArray reserva exactamente las casillas que se piden.

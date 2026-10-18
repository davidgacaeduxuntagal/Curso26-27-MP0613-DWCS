<?php
/*
 * e03-EFICIENCIA.php
 *  MEDIR EL TIEMPO (3): MICROBENCHMARKS BIEN HECHOS Y COMPARAR IMPLEMENTACIONES
 *  Clase Benchmark (Benchmark.php): medir (lotes, calentamiento, muestras, mediana), comparar, tabla;
 *  detectar Xdebug (xdebug_info); in_array frente a isset; str_contains, strpos y preg_match; implode frente a .=;
 *  dos "mitos" (count() en el for y las comillas)
 *  (apartado 4 de los apuntes)
 *
 *  Medir una función muy rápida una sola vez no sirve: el resultado es casi todo ruido. Hay que repetirla
 *  muchas veces, tomar varias muestras y quedarse con la mediana.
 *
 *  Cómo trabajar este fichero:
 *   1. ESTE fichero ejecútalo en la terminal, SIN depurador: php -d xdebug.mode=off e03-EFICIENCIA.php
 *      (con el depurador los tiempos salen varias veces mayores y las comparaciones engañan).
 *   2. Si quieres depurarlo, hazlo para entender Benchmark::medir (pon el punto de ruptura dentro), no para
 *      mirar los tiempos.
 *   3. Los tiempos de los comentarios son orientativos; lo que se debe mantener es el ORDEN y la proporción.
 */
require_once __DIR__ . '/utilidades.php';
require_once __DIR__ . '/Benchmark.php';

// ---------------------------------------------------------------------------------------------
// xdebug_info('mode') — ¿Está Xdebug frenando la ejecución?
// ENLACE: https://xdebug.org/docs/all_functions#xdebug_info
// ENUNCIADO EJEMPLO: antes de medir nada, avisar si los tiempos van a salir falseados.
// NOTA: el XAMPP del aula trae Xdebug en modo "develop,debug": con él, la función recursiva de Fibonacci de
//       e02 tarda unas 8 veces más (fib(27): 19 ms sin Xdebug, 146 ms con él). OJO: con xdebug.mode=off,
//       ini_get('xdebug.mode') devuelve '' y no 'off'; lo fiable es xdebug_info('mode'), que devuelve los modos
//       activos (un array vacío si no hay ninguno).
// ---------------------------------------------------------------------------------------------
titulo('¿Xdebug activo?');
$modos = extension_loaded('xdebug') && function_exists('xdebug_info') ? xdebug_info('mode') : [];
ver('modos de Xdebug activos', $modos);                  // [] con -d xdebug.mode=off; ['debug', 'develop'] si no
if ($modos !== []) {
    ver('AVISO', 'Xdebug está activo: los tiempos saldrán varias veces mayores');
}

// ---------------------------------------------------------------------------------------------
// Benchmark::medir — Lotes, calentamiento, muestras y mediana
// ENLACE: https://www.php.net/manual/es/function.hrtime.php
// ENUNCIADO EJEMPLO: ¿cuánto tarda de verdad calcular el IVA de un precio?
// NOTA: una llamada tarda menos de lo que distingue el reloj, así que se mide un LOTE de 10 000 llamadas y se
//       divide. Se repite 7 veces (muestras). La MEDIANA (el valor del medio) es más fiable que la media: si una
//       muestra coincide con que Windows hace otra cosa, la media sube, pero la mediana casi no se mueve.
//       Lo medido incluye el coste de llamar a la closure, que es el mismo para todas las candidatas.
// ---------------------------------------------------------------------------------------------
titulo('Benchmark::medir');
$conIva = fn(float $precio) => round($precio * 1.21, 2);
$r = Benchmark::medir(fn() => $conIva(19.99), porLote: 10_000, muestras: 7);    // argumentos con nombre (PHP 8)
ver('mediana (µs por llamada)', round($r['mediana'], 3));  // 0.102 (aprox.: 100 nanosegundos; con Xdebug, 1.134)
ver('mínimo / máximo', [round($r['minimo'], 3), round($r['maximo'], 3)]);   // [0.101, 0.103]: a veces el máximo se dispara (0.118)
ver('llamadas medidas', $r['porLote'] * $r['muestras']);   // 70000

// ---------------------------------------------------------------------------------------------
// Buscar en una lista — in_array frente a isset (complejidad O(n) frente a O(1))
// ENLACE: https://www.php.net/manual/es/function.in-array.php
// ENLACE: https://www.php.net/manual/es/function.array-flip.php
// ENUNCIADO EJEMPLO: al registrarse, comprobar si el correo ya está entre 10 000 usuarios.
// NOTA: in_array recorre la lista elemento a elemento (cuanto más larga, más tarda: O(n)). Si se buscan muchas
//       veces, compensa darle la vuelta UNA vez con array_flip (los valores pasan a ser claves) y preguntar con
//       isset, que va directo a la clave (O(1)). array_key_exists hace lo mismo que isset, pero también
//       distingue las claves con valor null. Se suele decir que es más lento por ser una función, pero desde
//       PHP 7.4 el compilador lo convierte en una instrucción especial y tarda lo mismo: ¡por eso hay que medir!
// ---------------------------------------------------------------------------------------------
titulo('in_array frente a isset');
$correos = [];
for ($i = 1; $i <= 10_000; $i++) {
    $correos[] = "usuario$i@iesrodeira.gal";
}
$indice = array_flip($correos);                          // 'usuario1@...' => 0, 'usuario2@...' => 1...
$buscado = 'usuario9999@iesrodeira.gal';                 // casi al final: el peor caso para in_array
ver('los tres dan lo mismo', [in_array($buscado, $correos, true), isset($indice[$buscado]), array_key_exists($buscado, $indice)]);   // [true, true, true]
$resultados = Benchmark::comparar([
    'in_array' => fn() => in_array($buscado, $correos, true),
    'isset' => fn() => isset($indice[$buscado]),
    'array_key_exists' => fn() => array_key_exists($buscado, $indice),
], porLote: 1000);
bloque(Benchmark::tabla($resultados));
// isset                  0,035 µs/llamada  x1,0      (isset y array_key_exists se alternan el primer puesto)
// array_key_exists       0,037 µs/llamada  x1,0
// in_array              35,984 µs/llamada  x1.005,1  (unas mil veces más lento con 10 000 elementos)

// ---------------------------------------------------------------------------------------------
// ¿Contiene un texto? — str_contains, strpos y preg_match
// ENLACE: https://www.php.net/manual/es/function.str-contains.php
// ENLACE: https://www.php.net/manual/es/function.preg-match.php
// ENUNCIADO EJEMPLO: marcar las opiniones del Bar Baio que hablan del pulpo.
// NOTA: str_contains (PHP 8) y strpos(...) !== false son prácticamente iguales; una expresión regular tiene que
//       compilarse y ejecutar su motor, y tarda más. Úsala cuando necesites un patrón, no para buscar un texto fijo.
// ---------------------------------------------------------------------------------------------
titulo('str_contains, strpos, preg_match');
$opinion = str_repeat('Muy buen servicio y un café estupendo. ', 20) . 'El pulpo, espectacular.';
$resultados = Benchmark::comparar([
    'str_contains' => fn() => str_contains($opinion, 'pulpo'),
    'strpos' => fn() => strpos($opinion, 'pulpo') !== false,
    'preg_match' => fn() => preg_match('/pulpo/', $opinion) === 1,
], porLote: 10_000);
bloque(Benchmark::tabla($resultados));
// str_contains       0,186 µs/llamada  x1,0
// strpos             0,188 µs/llamada  x1,0
// preg_match         0,481 µs/llamada  x2,6

// ---------------------------------------------------------------------------------------------
// Construir un texto largo — .= en un bucle frente a implode
// ENLACE: https://www.php.net/manual/es/function.implode.php
// ENUNCIADO EJEMPLO: generar las 1000 filas <tr> de un listado.
// NOTA: en PHP, .= es eficiente (no copia la cadena entera cada vez, como pasa en otros lenguajes). Las dos
//       formas tardan parecido: elige la que se lea mejor. Medir sirve también para NO cambiar código sin motivo.
// ---------------------------------------------------------------------------------------------
titulo('.= frente a implode');
$productos = array_map(fn($i) => ['id' => $i, 'nombre' => "Producto $i"], range(1, 1000));
$resultados = Benchmark::comparar([
    '.= en un bucle' => function () use ($productos) {
        $html = '';
        foreach ($productos as $p) {
            $html .= "<tr><td>{$p['id']}</td><td>{$p['nombre']}</td></tr>";
        }
        return $html;
    },
    'array_map + implode' => fn() => implode('', array_map(
        fn($p) => "<tr><td>{$p['id']}</td><td>{$p['nombre']}</td></tr>", $productos)),
], porLote: 200);
bloque(Benchmark::tabla($resultados));
// .= en un bucle           65,340 µs/llamada  x1,0  (el orden de las dos cambia de una ejecución a otra:
// array_map + implode      66,392 µs/llamada  x1,0   la diferencia es ruido)

// ---------------------------------------------------------------------------------------------
// Dos mitos — count() en la condición del for y las comillas simples
// ENLACE: https://www.php.net/manual/es/function.count.php
// ENUNCIADO EJEMPLO: un compañero dice que hay que cambiar todas las comillas dobles por simples "porque es más
//                   rápido" y sacar count() de todos los for.
// NOTA: count() de un array no recorre nada: PHP guarda el número de elementos, así que llamarlo en cada vuelta
//       cuesta muy poco (una llamada a función). Y las comillas dobles sin variables se compilan igual que las
//       simples. Las diferencias son de nanosegundos por vuelta: no merece la pena "optimizar" eso, salvo en
//       un bucle que se ejecute millones de veces (y aun así, mejor usar foreach, que es más claro).
// ---------------------------------------------------------------------------------------------
titulo('Dos mitos');
$numeros = range(1, 1000);
$resultados = Benchmark::comparar([
    'for con count() en la condición' => function () use ($numeros) {
        $s = 0;
        for ($i = 0; $i < count($numeros); $i++) {
            $s += $numeros[$i];
        }
        return $s;
    },
    'for con $n = count() antes' => function () use ($numeros) {
        $s = 0;
        $n = count($numeros);
        for ($i = 0; $i < $n; $i++) {
            $s += $numeros[$i];
        }
        return $s;
    },
    'foreach' => function () use ($numeros) {
        $s = 0;
        foreach ($numeros as $x) {
            $s += $x;
        }
        return $s;
    },
], porLote: 500);
bloque(Benchmark::tabla($resultados));
// foreach                              10,778 µs/llamada  x1,0
// for con $n = count() antes           11,998 µs/llamada  x1,1
// for con count() en la condición      13,869 µs/llamada  x1,3  (2-3 µs más por cada 1000 vueltas: casi nada)
$resultados = Benchmark::comparar([
    "comillas simples" => fn() => 'Bienvenido a la tienda online',
    "comillas dobles" => fn() => "Bienvenido a la tienda online",
], porLote: 10_000);
bloque(Benchmark::tabla($resultados));
// comillas simples       0,026 µs/llamada  x1,0  (iguales: el orden cambia de una ejecución a otra)
// comillas dobles        0,027 µs/llamada  x1,0

<?php
/*
 * e08-POO_problema_solucion.php
 *  (FICHERO CON LAS SOLUCIONES)
 *  Problemas de: declaraciones de tipos (nullable, unión, void, never), strict_types,
 *                TypeError, try / catch / finally, excepciones de la SPL, excepciones propias,
 *                relanzar con previous
 *  (repasa los ejemplos de ../Ejercicios OOP/e08-POO.php)
 *
 *  Resuelve cada problema donde se indica y ejecútalo en modo depuración para comprobarlo.
 */
declare(strict_types=1);

require_once __DIR__ . '/../Ejercicios OOP/utilidades.php';

// =============================================================================================
// PROBLEMA 1. Añade los tipos
//  Copia la función calcularMedia como calcularMediaTipada añadiendo los tipos adecuados: recibe
//  un array y devuelve un float, o null si el array está vacío. Comprueba que, con strict_types,
//  pasarle un string da TypeError.
// =============================================================================================
titulo('Problema 1');
function calcularMedia($notas)
{
    if (count($notas) === 0) {
        return null;
    }
    return round(array_sum($notas) / count($notas), 2);
}
// completa aquí tu solución
// ---- SOLUCIÓN ----
function calcularMediaTipada(array $notas): ?float
{
    if (count($notas) === 0) {
        return null;
    }
    return round(array_sum($notas) / count($notas), 2);
}
ver('media', calcularMediaTipada([7, 8.5, 6]));          // 7.17
ver('vacío', calcularMediaTipada([]));                    // NULL
verError('con un string', fn() => calcularMediaTipada('7,8,6'));   // TypeError

// =============================================================================================
// PROBLEMA 2. Excepciones de la SPL adecuadas
//  Escribe la función obtenerDia(array $semana, int $indice): string que lance:
//   - InvalidArgumentException si $semana no tiene exactamente 7 elementos,
//   - OutOfRangeException si $indice no está entre 0 y 6.
//  Llámala con los tres casos de $pruebas capturando cada tipo con su propio catch.
// =============================================================================================
titulo('Problema 2');
$semana = ['luns', 'martes', 'mércores', 'xoves', 'venres', 'sábado', 'domingo'];
$pruebas = [[$semana, 4], [$semana, 9], [['luns'], 0]];
// completa aquí tu solución
// ---- SOLUCIÓN ----
function obtenerDia(array $semana, int $indice): string
{
    if (count($semana) !== 7) {
        throw new InvalidArgumentException('La semana debe tener 7 días');
    }
    if ($indice < 0 || $indice > 6) {
        throw new OutOfRangeException("Índice fuera de rango: $indice");
    }
    return $semana[$indice];
}
foreach ($pruebas as [$s, $i]) {
    try {
        ver("índice $i", obtenerDia($s, $i));           // "venres"
    } catch (OutOfRangeException $e) {
        ver('fuera de rango', $e->getMessage());        // "Índice fuera de rango: 9"
    } catch (InvalidArgumentException $e) {
        ver('argumento no válido', $e->getMessage());   // "La semana debe tener 7 días"
    }
}

// =============================================================================================
// PROBLEMA 3. finally
//  Escribe la función procesarFichero(string $contenido): array que "abra" un recurso
//  (php://memory), escriba el contenido, lea las líneas y lance RuntimeException si alguna
//  línea está vacía. El recurso se debe cerrar SIEMPRE (finally). Devuelve también, en una
//  variable global $cerrados, cuántas veces se ha cerrado (para comprobarlo).
// =============================================================================================
titulo('Problema 3');
$cerrados = 0;
// completa aquí tu solución
// ---- SOLUCIÓN ----
function procesarFichero(string $contenido): array
{
    global $cerrados;
    $fp = fopen('php://memory', 'w+');
    try {
        fwrite($fp, $contenido);
        rewind($fp);
        $lineas = [];
        while (($linea = fgets($fp)) !== false) {
            if (trim($linea) === '') {
                throw new RuntimeException('Línea vacía en la posición ' . (count($lineas) + 1));
            }
            $lineas[] = trim($linea);
        }
        return $lineas;                       // ¡finally se ejecuta incluso después de return!
    } finally {
        fclose($fp);
        $cerrados++;
    }
}
ver('correcto', procesarFichero("uno\ndos\ntres"));          // ['uno', 'dos', 'tres']
verError('con línea vacía', fn() => procesarFichero("uno\n\ntres"));   // RuntimeException: Línea vacía en la posición 2
ver('veces que se ha cerrado', $cerrados);                   // 2

// =============================================================================================
// PROBLEMA 4. Una excepción propia con datos
//  Crea la excepción ValidacionException (hereda de InvalidArgumentException) que reciba un
//  array campo => mensaje y lo guarde en una propiedad readonly $errores, con el mensaje general
//  "N errores de validación". Escribe validarUsuario(array $datos): void que la lance si el
//  nombre está vacío o el email no es válido.
// =============================================================================================
titulo('Problema 4');
// completa aquí tu solución
// ---- SOLUCIÓN ----
class ValidacionException extends InvalidArgumentException
{
    public function __construct(public readonly array $errores)
    {
        parent::__construct(count($errores) . ' errores de validación');
    }
}
function validarUsuario(array $datos): void
{
    $errores = [];
    if (trim($datos['nombre'] ?? '') === '') {
        $errores['nombre'] = 'Obligatorio';
    }
    if (filter_var($datos['email'] ?? '', FILTER_VALIDATE_EMAIL) === false) {
        $errores['email'] = 'Correo no válido';
    }
    if ($errores !== []) {
        throw new ValidacionException($errores);
    }
}
try {
    validarUsuario(['nombre' => '', 'email' => 'ana@']);
} catch (ValidacionException $e) {
    ver('mensaje', $e->getMessage());     // "2 errores de validación"
    ver('errores', $e->errores);          // ['nombre' => 'Obligatorio', 'email' => 'Correo no válido']
}

// =============================================================================================
// PROBLEMA 5. ¿Cómo harías...?
//  La función leerConfig() falla con un JsonException poco claro para el usuario. ¿Cómo harías
//  para que lanzara una RuntimeException con el mensaje "Configuración dañada" pero sin perder
//  la excepción original (para el log)? Escribe leerConfigBien() y muestra los dos mensajes.
// =============================================================================================
titulo('Problema 5');
function leerConfig(string $json): array
{
    return json_decode($json, true, 512, JSON_THROW_ON_ERROR);
}
// completa aquí tu solución
// ---- SOLUCIÓN ----
function leerConfigBien(string $json): array
{
    try {
        return leerConfig($json);
    } catch (JsonException $e) {
        throw new RuntimeException('Configuración dañada', 0, $e);    // la original como "previous"
    }
}
try {
    leerConfigBien('{"iva": 0.21,}');
} catch (RuntimeException $e) {
    ver('para el usuario', $e->getMessage());                 // "Configuración dañada"
    ver('para el log', $e->getPrevious()->getMessage());      // "Syntax error"
}

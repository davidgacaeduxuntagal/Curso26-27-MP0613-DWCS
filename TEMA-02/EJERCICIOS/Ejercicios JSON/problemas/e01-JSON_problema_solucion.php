<?php
/*
 * e01-JSON_problema_solucion.php
 *  (FICHERO CON LAS SOLUCIONES)
 *  Problemas de: json_encode (tipos, listas, arrays asociativos, anidados, objetos),
 *                array_is_list, array_values
 *  (repasa los ejemplos de ../e01-JSON.php)
 *
 *  Resuelve cada problema donde se indica y ejecútalo en modo depuración para comprobarlo.
 */
require_once __DIR__ . '/../utilidades.php';

// =============================================================================================
// PROBLEMA 1. ¿Array u objeto?
//  Sin ejecutarlo, escribe en un comentario qué texto JSON producirá cada array de $casos
//  (¿[ ... ] o { ... }?). Después, recorre $casos y muestra, para cada uno, json_encode y
//  array_is_list, y comprueba tus respuestas.
// =============================================================================================
titulo('Problema 1');
$casos = [
    'a' => [10, 20, 30],
    'b' => [0 => 'x', 1 => 'y'],
    'c' => [1 => 'x', 2 => 'y'],
    'd' => ['0' => 'x', '1' => 'y'],
    'e' => [],
];
// completa aquí tu solución
// ---- SOLUCIÓN ----
// a: [10,20,30]   b: ["x","y"]   c: {"1":"x","2":"y"}   d: ["x","y"] (las claves "0" y "1" se
// convierten en los enteros 0 y 1)   e: []
foreach ($casos as $nombre => $array) {
    ver($nombre, json_encode($array) . '   lista: ' . var_export(array_is_list($array), true));
}

// =============================================================================================
// PROBLEMA 2. La lista de alérgenos llega como objeto
//  La app del móvil se queja: "alergenos" a veces le llega como objeto. Encuentra el motivo
//  y corrígelo para que el JSON final tenga "alergenos":["huevo","leche"].
// =============================================================================================
titulo('Problema 2');
$plato = ['nombre' => 'Flan', 'alergenos' => ['gluten', 'huevo', 'leche']];
// El flan ahora se hace sin gluten:
$posicion = array_search('gluten', $plato['alergenos']);
unset($plato['alergenos'][$posicion]);
ver('JSON con el fallo', json_encode($plato));     // {"nombre":"Flan","alergenos":{"1":"huevo","2":"leche"}}
// completa aquí tu solución
// ---- SOLUCIÓN ----
// Motivo: unset deja las claves 1 y 2 (sin la 0), así que ya no es una lista.
$plato['alergenos'] = array_values($plato['alergenos']);
ver('JSON corregido', json_encode($plato));        // {"nombre":"Flan","alergenos":["huevo","leche"]}

// =============================================================================================
// PROBLEMA 3. Construir la respuesta de una API
//  Construye en PHP la estructura necesaria para que json_encode produzca EXACTAMENTE este JSON
//  (compáralo con === para comprobarlo):
//   {"ok":true,"total":2,"reservas":[{"mesa":4,"personas":2,"terraza":false},{"mesa":9,"personas":6,"terraza":true}],"aviso":null}
// =============================================================================================
titulo('Problema 3');
$esperado = '{"ok":true,"total":2,"reservas":[{"mesa":4,"personas":2,"terraza":false},{"mesa":9,"personas":6,"terraza":true}],"aviso":null}';
// completa aquí tu solución
// ---- SOLUCIÓN ----
$reservas = [
    ['mesa' => 4, 'personas' => 2, 'terraza' => false],
    ['mesa' => 9, 'personas' => 6, 'terraza' => true],
];
$respuesta = ['ok' => true, 'total' => count($reservas), 'reservas' => $reservas, 'aviso' => null];
ver('JSON', json_encode($respuesta));
ver('es exactamente igual', json_encode($respuesta) === $esperado);   // true

// =============================================================================================
// PROBLEMA 4. Platos baratos
//  A partir de $carta, obtén el JSON de la LISTA de nombres de los platos que cuestan menos de
//  10 euros. Debe ser un array JSON: ["Tortilla","Filloas"]. (Pista: array_filter conserva las
//  claves.)
// =============================================================================================
titulo('Problema 4');
$carta = [
    ['nombre' => 'Pulpo', 'precio' => 12.5],
    ['nombre' => 'Tortilla', 'precio' => 9.5],
    ['nombre' => 'Chuletón', 'precio' => 24.0],
    ['nombre' => 'Filloas', 'precio' => 4.5],
];
// completa aquí tu solución
// ---- SOLUCIÓN ----
$baratos = array_filter($carta, fn($p) => $p['precio'] < 10);
$nombres = array_values(array_column($baratos, 'nombre'));   // array_column ya reindexa, pero así queda claro
ver('JSON', json_encode($nombres));      // ["Tortilla","Filloas"]

// =============================================================================================
// PROBLEMA 5. ¿Qué se ve de un objeto?
//  Crea un objeto de la clase Empleado y muestra su json_encode. ¿Qué atributos aparecen y por
//  qué? Después, sin modificar la clase, obtén un JSON que incluya también el "puesto" usando
//  el método getPuesto() (construye tú el array).
// =============================================================================================
titulo('Problema 5');
class Empleado
{
    public string $nombre;
    public ?string $email = null;
    private string $puesto;
    private float $sueldo;

    public function __construct(string $nombre, string $puesto, float $sueldo)
    {
        $this->nombre = $nombre;
        $this->puesto = $puesto;
        $this->sueldo = $sueldo;
    }

    public function getPuesto(): string
    {
        return $this->puesto;
    }
}
// completa aquí tu solución
// ---- SOLUCIÓN ----
$juan = new Empleado('Juan', 'cocinero', 1650.0);
ver('json', json_encode($juan));          // {"nombre":"Juan","email":null}
// Solo aparecen los atributos públicos (email sale como null). puesto y sueldo son private.
$datos = ['nombre' => $juan->nombre, 'puesto' => $juan->getPuesto()];
ver('con el puesto', json_encode($datos));   // {"nombre":"Juan","puesto":"cocinero"}

<?php
/*
 * e05-JSON_problema.php
 *  Problemas de: JsonSerializable, fechas en JSON (DATE_ATOM, createFromFormat), enum con
 *                valor (from / tryFrom), crear objetos a partir de JSON (fromArray)
 *  (repasa los ejemplos de ../e05-JSON.php)
 *
 *  Resuelve cada problema donde se indica y ejecútalo en modo depuración para comprobarlo.
 */
require_once __DIR__ . '/../utilidades.php';

date_default_timezone_set('Europe/Madrid');

// =============================================================================================
// PROBLEMA 1. Un usuario que no enseña su contraseña
//  Haz que la clase Usuario implemente JsonSerializable para que su JSON sea:
//   {"usuario":"carlos","nombre":"Carlos Pérez","roles":["camarero","caja"],"esAdmin":false}
//  (esAdmin se calcula: es true si entre los roles está "admin"). El hash de la contraseña no
//  debe aparecer nunca.
// =============================================================================================
titulo('Problema 1');
// completa aquí tu solución

// =============================================================================================
// PROBLEMA 2. Fechas de los eventos
//  Convierte $eventos a JSON de forma que cada fecha sea un texto ISO 8601 (DATE_ATOM). Después
//  decodifica el JSON y, para cada evento, muestra la fecha en formato español "d/m/Y H:i".
// =============================================================================================
titulo('Problema 2');
$eventos = [
    ['titulo' => 'Magosto', 'inicio' => new DateTimeImmutable('2026-11-11 18:00')],
    ['titulo' => 'Cea de Nadal', 'inicio' => new DateTimeImmutable('2026-12-24 21:30')],
];
// completa aquí tu solución

// =============================================================================================
// PROBLEMA 3. Estados de las mesas
//  Crea un enum con valor EstadoMesa (libre, ocupada, reservada) y úsalo para leer $jsonMesas.
//  Las mesas con un estado desconocido se consideran "libre". Muestra cuántas mesas hay en
//  cada estado.
// =============================================================================================
titulo('Problema 3');
$jsonMesas = '[{"mesa":1,"estado":"ocupada"},{"mesa":2,"estado":"libre"},{"mesa":3,"estado":"reservada"},{"mesa":4,"estado":"ocupada"},{"mesa":5,"estado":"rota"}]';
// completa aquí tu solución

// =============================================================================================
// PROBLEMA 4. Productos desde JSON
//  Completa el método estático Producto::fromArray para crear un Producto a partir de un array
//  decodificado: nombre (texto no vacío), precio (int o float mayor que 0) y stock (entero >= 0,
//  0 si no viene). Si algo no es correcto, lanza InvalidArgumentException. Carga $jsonProductos
//  y muestra los productos válidos y los errores.
// =============================================================================================
titulo('Problema 4');
class Producto
{
    public function __construct(public string $nombre, public float $precio, public int $stock)
    {
    }

    public static function fromArray(array $datos): self
    {
        // completa aquí el método
    }
}
$jsonProductos = '[{"nombre":"Café","precio":1.3,"stock":200},{"nombre":"Queixo","precio":"barato"},{"nombre":"Mel","precio":6},{"nombre":"","precio":2},{"nombre":"Viño","precio":9.5,"stock":-3}]';
// completa aquí tu solución

// =============================================================================================
// PROBLEMA 5. Ida y vuelta de una comanda
//  Haz que Comanda implemente JsonSerializable (mesa, fecha en DATE_ATOM y platos) y añade un
//  método estático fromJson(string $json): self que haga lo contrario. Comprueba que, tras
//  convertir una comanda a JSON y de nuevo a objeto, los datos son los mismos.
// =============================================================================================
titulo('Problema 5');
// completa aquí tu solución

<?php
/*
 * e02-POO.php
 *  POO (2): LOS OBJETOS EN MEMORIA
 *  Contenidos: asignar un objeto a otra variable (mismo objeto), pasar objetos a funciones,
 *              asignación por referencia (&), clone (copia superficial), __clone (copia
 *              profunda), comparar objetos (== y ===), spl_object_id
 *  (apartado 3 de los apuntes)
 *
 *  Cómo trabajar este fichero:
 *   1. Pon un punto de ruptura en la primera línea de código y ejecuta en modo depuración.
 *   2. En el panel VARIABLES, fíjate en el número que muestra el depurador junto a cada objeto
 *      (o en spl_object_id): si dos variables muestran el MISMO número, son el MISMO objeto.
 *   3. Los comentarios a la derecha indican el resultado esperado.
 */
require_once __DIR__ . '/utilidades.php';

class Carrito
{
    public array $productos = [];

    public function __construct(public string $cliente)
    {
    }

    public function anadir(string $producto): void
    {
        $this->productos[] = $producto;
    }
}

// ---------------------------------------------------------------------------------------------
// Asignar un objeto a otra variable: ¡NO se copia!
// ENLACE: https://www.php.net/manual/es/language.oop5.references.php
// ENUNCIADO EJEMPLO: guardar "una copia" del carrito antes de modificarlo... que no es una copia.
// NOTA: una variable de objeto guarda un IDENTIFICADOR del objeto, no el objeto. $b = $a copia el
//       identificador: las dos variables apuntan al mismo objeto (como dos mandos de la misma tele).
//       Con los arrays y los demás tipos, en cambio, $b = $a SÍ hace una copia.
// ---------------------------------------------------------------------------------------------
titulo('Asignación');
$carrito = new Carrito('Ana');
$carrito->anadir('Ratón');
$copiaFalsa = $carrito;                 // NO es una copia
$copiaFalsa->anadir('Teclado');
ver('carrito', $carrito->productos);    // ['Ratón', 'Teclado'] ¡también ha cambiado!
ver('¿mismo objeto? (===)', $carrito === $copiaFalsa);          // true
ver('ids', [spl_object_id($carrito), spl_object_id($copiaFalsa)]);   // el mismo número dos veces

$lista = ['Ratón'];                     // con un array es distinto
$copiaLista = $lista;                   // esto SÍ es una copia
$copiaLista[] = 'Teclado';
ver('array original', $lista);          // ['Ratón']

// ---------------------------------------------------------------------------------------------
// Pasar un objeto a una función
// ENLACE: https://www.php.net/manual/es/language.oop5.references.php
// ENUNCIADO EJEMPLO: una función que aplica un regalo al carrito (ejemplo del material de
//                   referencia: cambiarNombre($perro, ...)).
// NOTA: la función recibe el identificador del MISMO objeto, así que puede modificarlo (sin &).
//       Pero si dentro de la función se asigna OTRO objeto a la variable, el de fuera no cambia.
// ---------------------------------------------------------------------------------------------
titulo('Pasar objetos a funciones');
function anadirRegalo(Carrito $c): void
{
    $c->anadir('Alfombrilla (regalo)');      // modifica el objeto de fuera
}
function vaciarMal(Carrito $c): void
{
    $c = new Carrito($c->cliente);           // solo cambia la variable LOCAL $c
}
anadirRegalo($carrito);
ver('después de anadirRegalo', $carrito->productos);   // ['Ratón', 'Teclado', 'Alfombrilla (regalo)']
vaciarMal($carrito);
ver('después de vaciarMal', count($carrito->productos));   // 3: no se ha vaciado

// ---------------------------------------------------------------------------------------------
// Asignación por referencia (&)
// ENLACE: https://www.php.net/manual/es/language.references.php
// ENUNCIADO EJEMPLO: el ejemplo del material de referencia: $asignada, $referencia y null.
// NOTA: $r = &$a hace que $r y $a sean LA MISMA VARIABLE (dos nombres). Si después se asigna null
//       a $a, $r también vale null; pero $b (asignada sin &) sigue apuntando al objeto.
// ---------------------------------------------------------------------------------------------
titulo('Referencias');
$objeto = new Carrito('Brais');
$asignada = $objeto;
$referencia = &$objeto;
$objeto = null;
ver('$objeto', $objeto);                    // NULL
ver('$referencia', $referencia);            // NULL (es la misma variable que $objeto)
ver('$asignada', $asignada);                // Carrito { cliente 'Brais' }: el objeto sigue vivo

// ---------------------------------------------------------------------------------------------
// clone — Copiar un objeto (copia SUPERFICIAL)
// ENLACE: https://www.php.net/manual/es/language.oop5.cloning.php
// ENUNCIADO EJEMPLO: duplicar un carrito para un pedido repetido.
// NOTA: clone crea un objeto NUEVO con una copia de cada propiedad. Los arrays y los valores
//       simples quedan independientes, pero si una propiedad contiene OTRO OBJETO, se copia su
//       identificador: el original y la copia comparten ese objeto interno.
// ---------------------------------------------------------------------------------------------
titulo('clone');
$copia = clone $carrito;
$copia->cliente = 'Carmen';
$copia->anadir('Monitor');
ver('original', [$carrito->cliente, count($carrito->productos)]);   // ['Ana', 3]
ver('copia', [$copia->cliente, count($copia->productos)]);           // ['Carmen', 4]
ver('¿mismo objeto?', $copia === $carrito);                          // false

class Direccion
{
    public function __construct(public string $calle, public string $ciudad)
    {
    }
}
class ClienteSuperficial
{
    public function __construct(public string $nombre, public Direccion $direccion)
    {
    }
}
$diego = new ClienteSuperficial('Diego', new Direccion('Rúa Nova, 1', 'Baio'));
$hermano = clone $diego;
$hermano->nombre = 'Eva';
$hermano->direccion->ciudad = 'Carballo';      // ¡cambia la dirección de los DOS!
ver('ciudad de Diego', $diego->direccion->ciudad);   // "Carballo" (copia superficial)
ver('¿comparten la dirección?', $diego->direccion === $hermano->direccion);   // true

// ---------------------------------------------------------------------------------------------
// __clone — Copia PROFUNDA
// ENLACE: https://www.php.net/manual/es/language.oop5.cloning.php#object.clone
// ENUNCIADO EJEMPLO: que al clonar un cliente se clone también su dirección.
// NOTA: __clone se ejecuta sobre la COPIA justo después de clonar; ahí se clonan los objetos
//       internos. (En el material de referencia, el Tweet clona su Notification.)
// ---------------------------------------------------------------------------------------------
titulo('__clone');
class Cliente
{
    public function __construct(public string $nombre, public Direccion $direccion)
    {
    }

    public function __clone(): void
    {
        $this->direccion = clone $this->direccion;   // $this es la copia
    }
}
$diego = new Cliente('Diego', new Direccion('Rúa Nova, 1', 'Baio'));
$hermano = clone $diego;                            // pon un punto de ruptura dentro de __clone
$hermano->direccion->ciudad = 'Carballo';
ver('ciudad de Diego', $diego->direccion->ciudad);   // "Baio"
ver('ciudad del hermano', $hermano->direccion->ciudad);   // "Carballo"

// ---------------------------------------------------------------------------------------------
// Comparar objetos: == y ===
// ENLACE: https://www.php.net/manual/es/language.oop5.object-comparison.php
// ENUNCIADO EJEMPLO: ¿son iguales dos direcciones?
// NOTA: == es true si son de la misma clase y TODAS sus propiedades son iguales (==).
//       === solo es true si son EL MISMO objeto.
// ---------------------------------------------------------------------------------------------
titulo('Comparar');
$d1 = new Direccion('Rúa Nova, 1', 'Baio');
$d2 = new Direccion('Rúa Nova, 1', 'Baio');
$d3 = $d1;
ver('$d1 == $d2', $d1 == $d2);      // true: mismos valores
ver('$d1 === $d2', $d1 === $d2);    // false: objetos distintos
ver('$d1 === $d3', $d1 === $d3);    // true: el mismo objeto

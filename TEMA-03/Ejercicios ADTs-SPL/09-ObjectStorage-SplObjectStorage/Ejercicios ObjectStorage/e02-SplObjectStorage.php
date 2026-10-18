<?php
/*
 * e02-SplObjectStorage.php
 *  CONJUNTO / MAPA DE OBJETOS con SplObjectStorage (2): APLICACIONES REALES
 *  Contenidos: un carrito objeto Producto => cantidad, el patrón Observador con las interfaces
 *              de la SPL SplSubject y SplObserver (avisos de stock), una red de seguidores entre
 *              objetos (grafo), una caché de cálculos asociada a objetos con WeakMap
 *
 *  Cómo trabajar este fichero:
 *   1. Pon un punto de ruptura DENTRO de los métodos update() y notify() y ejecuta en modo
 *      depuración: verás cómo el producto avisa, uno a uno, a todos sus observadores.
 */
require_once __DIR__ . '/../../utilidades.php';

final class Producto
{
    public function __construct(public readonly string $ref, public readonly string $nombre, public readonly float $precio)
    {
    }
}

// ---------------------------------------------------------------------------------------------
// Carrito: objeto Producto => cantidad
// ENLACE: https://www.php.net/manual/es/class.splobjectstorage.php
// ENUNCIADO EJEMPLO: añadir productos al carrito, sumar unidades si ya estaban y calcular el total.
// NOTA: la clave es el PROPIO objeto Producto (no su referencia en texto): se recupera entero al
//       recorrer el carrito, con su nombre y su precio.
// ---------------------------------------------------------------------------------------------
titulo('Carrito');
final class Carrito implements Countable
{
    private SplObjectStorage $lineas;

    public function __construct()
    {
        $this->lineas = new SplObjectStorage();
    }

    public function anadir(Producto $p, int $unidades = 1): void
    {
        $actual = $this->lineas->contains($p) ? $this->lineas[$p] : 0;
        $this->lineas[$p] = $actual + $unidades;
    }

    public function quitar(Producto $p): void
    {
        $this->lineas->detach($p);
    }

    public function total(): float
    {
        $total = 0;
        foreach ($this->lineas as $producto) {
            $total += $producto->precio * $this->lineas->getInfo();
        }
        return round($total, 2);
    }

    public function count(): int
    {
        return array_sum(array_map(fn($p) => $this->lineas[$p], iterator_to_array($this->lineas, false)));
    }
}
$raton = new Producto('RAT-01', 'Ratón', 19.95);
$teclado = new Producto('TEC-02', 'Teclado', 49.50);
$cable = new Producto('CAB-03', 'Cable HDMI', 8.99);
$carrito = new Carrito();
$carrito->anadir($raton, 2);
$carrito->anadir($teclado);
$carrito->anadir($raton);                 // ya estaba: ahora 3
$carrito->anadir($cable, 2);
$carrito->quitar($cable);
ver('unidades', count($carrito));         // 4
ver('total', $carrito->total());          // 109.35 (3 x 19.95 + 49.50)

// ---------------------------------------------------------------------------------------------
// Patrón Observador: SplSubject y SplObserver
// ENLACE: https://www.php.net/manual/es/class.splsubject.php
// ENLACE: https://www.php.net/manual/es/class.splobserver.php
// ENUNCIADO EJEMPLO: cuando cambia el stock de un producto, se avisa automáticamente a quien esté
//                   interesado: el cliente que pidió "avísame cuando vuelva" y el panel del almacén.
// NOTA: el SUJETO (el producto) guarda a sus OBSERVADORES en un SplObjectStorage (así no se
//       repiten) y, cuando algo cambia, llama a update() de cada uno. El sujeto no sabe qué hace
//       cada observador: se pueden añadir nuevos sin tocar la clase del producto.
// ---------------------------------------------------------------------------------------------
titulo('Observador');
final class ProductoConStock implements SplSubject
{
    private SplObjectStorage $observadores;

    public function __construct(public readonly string $nombre, private int $stock = 0)
    {
        $this->observadores = new SplObjectStorage();
    }

    public function attach(SplObserver $observador): void
    {
        $this->observadores->attach($observador);
    }

    public function detach(SplObserver $observador): void
    {
        $this->observadores->detach($observador);
    }

    public function notify(): void
    {
        // Se recorre una COPIA: un observador puede darse de baja (detach) dentro de update(), y
        // borrar elementos del SplObjectStorage que se está recorriendo hace que foreach se SALTE
        // al siguiente. (Prueba a quitar el clone: el panel solo recibirá un aviso.)
        foreach (clone $this->observadores as $observador) {
            $observador->update($this);                 // pon aquí un punto de ruptura
        }
    }

    public function reponer(int $unidades): void
    {
        $this->stock += $unidades;
        $this->notify();
    }

    public function stock(): int
    {
        return $this->stock;
    }
}
final class AvisoCliente implements SplObserver
{
    public array $correos = [];

    public function __construct(private string $email)
    {
    }

    public function update(SplSubject $producto): void
    {
        if ($producto->stock() > 0) {
            $this->correos[] = "a {$this->email}: ¡{$producto->nombre} vuelve a estar disponible!";
            $producto->detach($this);                    // solo se avisa una vez
        }
    }
}
final class PanelAlmacen implements SplObserver
{
    public array $lineas = [];

    public function update(SplSubject $producto): void
    {
        $this->lineas[] = "{$producto->nombre}: {$producto->stock()} unidades";
    }
}
$consola = new ProductoConStock('Consola XS', 0);
$cliente = new AvisoCliente('ana@example.com');
$panel = new PanelAlmacen();
$consola->attach($cliente);
$consola->attach($panel);
$consola->attach($panel);                                // repetido: SplObjectStorage no lo duplica
$consola->reponer(5);
$consola->reponer(3);
ver('correos enviados', $cliente->correos);              // un solo correo, aunque se repuso dos veces
ver('panel', $panel->lineas);                            // ['Consola XS: 5 unidades', 'Consola XS: 8 unidades']

// ---------------------------------------------------------------------------------------------
// Una red de seguidores (grafo de objetos)
// ENLACE: https://www.php.net/manual/es/splobjectstorage.contains.php
// ENUNCIADO EJEMPLO: en la red social del instituto, cada usuario sigue a otros. ¿A quién sigue
//                   Ana? ¿Se siguen mutuamente Ana y Brais? ¿A quién le podríamos sugerir seguir?
// NOTA: cada usuario tiene un SplObjectStorage con los usuarios que sigue. contains() responde en
//       O(1) si uno sigue a otro.
// ---------------------------------------------------------------------------------------------
titulo('Red de seguidores');
final class Perfil
{
    private SplObjectStorage $sigue;

    public function __construct(public readonly string $nombre)
    {
        $this->sigue = new SplObjectStorage();
    }

    public function seguir(Perfil $otro): void
    {
        if ($otro !== $this) {
            $this->sigue->attach($otro);
        }
    }

    public function sigueA(Perfil $otro): bool
    {
        return $this->sigue->contains($otro);
    }

    public function seguidos(): SplObjectStorage
    {
        return clone $this->sigue;                       // una copia: desde fuera no se puede alterar
    }

    public function sugerencias(): array               // los que siguen mis seguidos y yo no sigo
    {
        $sugeridos = new SplObjectStorage();
        foreach ($this->sigue as $amigo) {
            $sugeridos->addAll($amigo->seguidos());
        }
        $sugeridos->removeAll($this->sigue);
        $sugeridos->detach($this);
        return array_map(fn($p) => $p->nombre, iterator_to_array($sugeridos, false));
    }
}
[$pAna, $pBrais, $pCarmen, $pDiego] = [new Perfil('Ana'), new Perfil('Brais'), new Perfil('Carmen'), new Perfil('Diego')];
$pAna->seguir($pBrais);
$pBrais->seguir($pAna);
$pBrais->seguir($pCarmen);
$pBrais->seguir($pDiego);
$pCarmen->seguir($pDiego);
ver('¿se siguen Ana y Brais?', $pAna->sigueA($pBrais) && $pBrais->sigueA($pAna));   // true
ver('¿Carmen sigue a Ana?', $pCarmen->sigueA($pAna));                              // false
ver('sugerencias para Ana', $pAna->sugerencias());                                // ['Carmen', 'Diego']

// ---------------------------------------------------------------------------------------------
// Caché de cálculos con WeakMap
// ENLACE: https://www.php.net/manual/es/class.weakmap.php
// ENUNCIADO EJEMPLO: calcular el precio final de un producto (impuestos, descuentos, portes) es
//                   costoso; se guarda el resultado por objeto para no repetirlo.
// NOTA: con WeakMap, si el producto deja de usarse en la aplicación, su entrada de la caché
//       desaparece sola y no se acumula memoria.
// ---------------------------------------------------------------------------------------------
titulo('Caché con WeakMap');
final class Calculadora
{
    private WeakMap $cache;
    public int $calculos = 0;

    public function __construct()
    {
        $this->cache = new WeakMap();
    }

    public function precioFinal(Producto $p): float
    {
        if (!isset($this->cache[$p])) {
            $this->calculos++;                           // solo la primera vez
            $this->cache[$p] = round($p->precio * 1.21 + ($p->precio < 50 ? 3.95 : 0), 2);
        }
        return $this->cache[$p];
    }

    public function enCache(): int
    {
        return count($this->cache);
    }
}
$calc = new Calculadora();
$tablet = new Producto('TAB-09', 'Tablet', 299.0);
foreach ([$raton, $tablet, $raton, $raton, $tablet] as $p) {
    $calc->precioFinal($p);
}
ver('precio final del ratón', $calc->precioFinal($raton));   // 28.09
ver('cálculos hechos', $calc->calculos);                     // 2 (uno por producto)
unset($tablet, $p);              // ¡también $p! El foreach dejó en $p el último producto (la tablet)
ver('en caché tras liberar la tablet', $calc->enCache());    // 1

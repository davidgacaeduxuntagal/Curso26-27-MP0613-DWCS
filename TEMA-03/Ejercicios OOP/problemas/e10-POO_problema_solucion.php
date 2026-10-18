<?php
/*
 * e10-POO_problema_solucion.php
 *  (FICHERO CON LAS SOLUCIONES)
 *  Problemas de: namespace, use, alias, nombres completos, ::class, spl_autoload_register, PSR-4
 *  (repasa los ejemplos de ../Ejercicios OOP/e10-POO.php y la carpeta ../Ejercicios OOP/e10-src)
 *
 *  Resuelve cada problema donde se indica y ejecútalo en modo depuración para comprobarlo.
 *  NOTA: en un mismo fichero se pueden escribir varios bloques "namespace Nombre { ... }" (aquí
 *  se hace así para que todo esté en un único fichero, pero en un proyecto real cada clase va en
 *  su propio fichero y se carga con el autoload).
 */

namespace {
    require_once __DIR__ . '/../Ejercicios OOP/utilidades.php';
}

// =============================================================================================
// PROBLEMA 1. Dos clases con el mismo nombre
//  En el espacio de nombres Facturacion hay una clase Cliente (con nif) y en Marketing otra
//  clase Cliente (con email). Úsalas las dos en el espacio global: importa la de Facturacion
//  con use y la de Marketing con el alias ClienteMk. Muestra el nombre completo de cada clase.
// =============================================================================================
namespace Facturacion {
    class Cliente
    {
        public function __construct(public string $nif)
        {
        }
    }
}
namespace Marketing {
    class Cliente
    {
        public function __construct(public string $email)
        {
        }
    }
}
namespace {
    titulo('Problema 1');
    // completa aquí tu solución
    // ---- SOLUCIÓN ----
    use Facturacion\Cliente;                      // Cliente = Facturacion\Cliente
    use Marketing\Cliente as ClienteMk;           // alias para la otra
    $paraFactura = new Cliente('12345678Z');
    $paraNewsletter = new ClienteMk('ana@example.com');
    ver('clases', [get_class($paraFactura), get_class($paraNewsletter)]);   // ['Facturacion\Cliente', 'Marketing\Cliente']
    ver('::class', [Cliente::class, ClienteMk::class]);                     // los nombres completos
}

// =============================================================================================
// PROBLEMA 2. De nombre de clase a fichero (PSR-4)
//  Escribe la función rutaPsr4(string $clase, array $prefijos): ?string que, dado un nombre de
//  clase completo y un array prefijo => carpeta (como el de composer.json), devuelva la ruta del
//  fichero que le corresponde, o null si ningún prefijo coincide.
// =============================================================================================
namespace {
    titulo('Problema 2');
    $prefijos = ['App\\' => 'src/', 'Tests\\' => 'tests/'];
    // completa aquí tu solución
    // ---- SOLUCIÓN ----
    function rutaPsr4(string $clase, array $prefijos): ?string
    {
        foreach ($prefijos as $prefijo => $carpeta) {
            if (str_starts_with($clase, $prefijo)) {
                $resto = substr($clase, strlen($prefijo));                  // "Modelo\Producto"
                return $carpeta . str_replace('\\', '/', $resto) . '.php';
            }
        }
        return null;
    }
    ver('App\Modelo\Producto', rutaPsr4('App\Modelo\Producto', $prefijos));         // "src/Modelo/Producto.php"
    ver('Tests\CarritoTest', rutaPsr4('Tests\CarritoTest', $prefijos));             // "tests/CarritoTest.php"
    ver('Otro\Cosa', rutaPsr4('Otro\Cosa', $prefijos));                             // NULL
}

// =============================================================================================
// PROBLEMA 3. Un autoloader que registre lo que carga
//  Registra con spl_autoload_register una función que cargue las clases de la carpeta
//  ../Ejercicios OOP/e10-src (como en el ejemplo) y que además guarde en el array $cargadas el
//  nombre de cada clase que carga. Crea un Tienda\Servicio\Carrito con su cliente y dos productos
//  y muestra $cargadas: ¿en qué orden se han cargado y por qué?
// =============================================================================================
namespace {
    titulo('Problema 3');
    $cargadas = [];
    // completa aquí tu solución
    // ---- SOLUCIÓN ----
    spl_autoload_register(function (string $clase) use (&$cargadas): void {
        $fichero = __DIR__ . '/../Ejercicios OOP/e10-src/' . str_replace('\\', '/', $clase) . '.php';
        if (is_file($fichero)) {
            require $fichero;
            $cargadas[] = $clase;
        }
    });
    $carrito = new \Tienda\Servicio\Carrito(new \Tienda\Modelo\Cliente('Brais'));
    $carrito->anadir(new \Tienda\Modelo\Producto('Cascos', 59.0));
    $carrito->anadir(new \Tienda\Modelo\Producto('Funda', 9.95));
    ver('cargadas', $cargadas);      // ['Tienda\Servicio\Carrito', 'Tienda\Modelo\Cliente', 'Tienda\Modelo\Producto']
    ver('resumen', $carrito->resumen());   // "Brais: 2 productos, 68.95 €"
    // Orden: Carrito, Cliente, Producto. Al ejecutar new Carrito(...) PHP necesita PRIMERO la
    // clase Carrito (la carga) y después evalúa su argumento (new Cliente). Los "use" de
    // Carrito.php no cargan nada. Producto se carga al crear el primero, y una sola vez.
}

// =============================================================================================
// PROBLEMA 4. ¿Cómo harías...?
//  En un fichero con "namespace Tienda\Servicio;", el código llama a strlen($x), a
//  new DateTime() y a new Producto(). ¿Qué clase o función busca PHP en cada caso y qué habría
//  que escribir para que funcionen? Contesta en un comentario.
// =============================================================================================
namespace {
    titulo('Problema 4');
    // completa aquí tu solución
    // ---- SOLUCIÓN ----
    // - strlen($x): PHP busca primero Tienda\Servicio\strlen y, como no existe, usa la función
    //   global strlen. Las FUNCIONES (y constantes) "caen" al espacio global: funciona.
    // - new DateTime(): las CLASES no caen al global. PHP buscaría Tienda\Servicio\DateTime y
    //   daría "Class not found". Hay que escribir new \DateTime() o poner "use DateTime;".
    // - new Producto(): buscaría Tienda\Servicio\Producto. Si está en Tienda\Modelo, hay que
    //   poner "use Tienda\Modelo\Producto;" (o new \Tienda\Modelo\Producto()).
    ver('respuesta', 'funciones: caen al global; clases: \\ o use');
}

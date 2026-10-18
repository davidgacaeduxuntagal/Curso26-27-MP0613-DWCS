<?php
/*
 * e10-POO_problema.php
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
}

<?php
/*
 * e01-SplObjectStorage.php
 *  CONJUNTO / MAPA DE OBJETOS con SplObjectStorage (1): LA API
 *  Métodos: attach, detach, contains, count, $storage[$obj] (asociar datos: offsetSet / offsetGet
 *           / offsetExists / offsetUnset), recorrer (current = objeto, getInfo / setInfo = dato),
 *           operaciones de conjuntos (addAll = unión, removeAll = diferencia, removeAllExcept =
 *           intersección), getHash (cuándo dos objetos son "el mismo"), WeakMap
 *
 *  SplObjectStorage es a la vez:
 *   - un CONJUNTO de objetos: cada objeto está como mucho una vez,
 *   - un MAPA objeto => dato: a cada objeto se le puede asociar un valor.
 *  Un array de PHP no sirve para esto, porque sus claves solo pueden ser enteros o textos.
 *
 *  Cómo trabajar este fichero:
 *   1. Pon un punto de ruptura en la primera línea de código y ejecuta en modo depuración.
 *   2. ver() muestra cada objeto guardado y su dato. Fíjate en que dos objetos con los mismos
 *      valores son DISTINTOS para SplObjectStorage (salvo que se redefina getHash).
 */
require_once __DIR__ . '/../../utilidades.php';

final class Usuario
{
    public function __construct(public readonly string $nombre, public readonly string $email)
    {
    }
}
$ana = new Usuario('Ana', 'ana@example.com');
$brais = new Usuario('Brais', 'brais@example.com');
$carmen = new Usuario('Carmen', 'carmen@example.com');

// ---------------------------------------------------------------------------------------------
// Como CONJUNTO: attach, detach, contains, count
// ENLACE: https://www.php.net/manual/es/class.splobjectstorage.php
// ENLACE: https://www.php.net/manual/es/splobjectstorage.attach.php
// ENLACE: https://www.php.net/manual/es/splobjectstorage.contains.php
// ENUNCIADO EJEMPLO: los usuarios conectados al chat de soporte.
// NOTA: attach de un objeto que ya está NO lo duplica. contains es O(1), igual que isset en un
//       array (con in_array habría que recorrer la lista).
// ---------------------------------------------------------------------------------------------
titulo('Como conjunto');
$conectados = new SplObjectStorage();
$conectados->attach($ana);
$conectados->attach($brais);
$conectados->attach($ana);                      // ya estaba: no se duplica
ver('conectados', count($conectados));          // 2
ver('¿está Carmen?', $conectados->contains($carmen));   // false
$conectados->detach($brais);                    // se desconecta
ver('¿está Brais?', $conectados->contains($brais));     // false
verError('un array como clave', function () use ($ana) {
    $mapa = [];
    $mapa[$ana] = 'conectada';                  // los arrays NO admiten objetos como clave
});                                             // TypeError: Illegal offset type

// ---------------------------------------------------------------------------------------------
// Como MAPA objeto => dato: $storage[$objeto]
// ENLACE: https://www.php.net/manual/es/splobjectstorage.offsetset.php
// ENLACE: https://www.php.net/manual/es/splobjectstorage.offsetget.php
// ENUNCIADO EJEMPLO: guardar a qué hora se conectó cada usuario.
// NOTA: $s[$obj] = $dato equivale a attach($obj, $dato). Leer un objeto que no está lanza
//       UnexpectedValueException (compruébalo antes con isset o contains).
// ---------------------------------------------------------------------------------------------
titulo('Como mapa');
$horaConexion = new SplObjectStorage();
$horaConexion[$ana] = '10:02';
$horaConexion[$brais] = '10:15';
$horaConexion->attach($carmen, '10:20');        // lo mismo con attach
$horaConexion[$ana] = '10:30';                  // vuelve a conectarse: se actualiza el dato
ver('mapa', $horaConexion);
ver('hora de Ana', $horaConexion[$ana]);        // "10:30"
ver('isset', isset($horaConexion[$brais]));     // true
unset($horaConexion[$brais]);
verError('leer a Brais', fn() => $horaConexion[$brais]);   // UnexpectedValueException: Object not found

// ---------------------------------------------------------------------------------------------
// Recorrer: el objeto es current() y el dato, getInfo()
// ENLACE: https://www.php.net/manual/es/splobjectstorage.getinfo.php
// ENLACE: https://www.php.net/manual/es/splobjectstorage.setinfo.php
// ENUNCIADO EJEMPLO: listar los usuarios conectados con su hora y marcar a los que llevan mucho.
// NOTA: ¡ojo! En foreach, la CLAVE es un número (0, 1, 2...) y el VALOR es el objeto. El dato
//       asociado se obtiene con getInfo() (y se cambia con setInfo()) del objeto actual.
// ---------------------------------------------------------------------------------------------
titulo('Recorrer');
foreach ($horaConexion as $i => $usuario) {
    $hora = $horaConexion->getInfo();
    echo "  $i: {$usuario->nombre} desde las $hora", nl();      // 0: Ana desde las 10:30 / 1: Carmen desde las 10:20
    if ($hora < '10:25') {
        $horaConexion->setInfo("$hora (más de 1 h)");          // cambia el dato del objeto actual
    }
}
ver('Carmen', $horaConexion[$carmen]);          // "10:20 (más de 1 h)"

// ---------------------------------------------------------------------------------------------
// Operaciones de conjuntos: addAll, removeAll, removeAllExcept
// ENLACE: https://www.php.net/manual/es/splobjectstorage.addall.php
// ENLACE: https://www.php.net/manual/es/splobjectstorage.removeall.php
// ENLACE: https://www.php.net/manual/es/splobjectstorage.removeallexcept.php
// ENUNCIADO EJEMPLO: alumnos apuntados a dos talleres: ¿quién va a alguno?, ¿quién va a los dos?,
//                   ¿quién va solo al de robótica?
// NOTA: modifican el propio conjunto: haz una copia (clone) si quieres conservar el original.
// ---------------------------------------------------------------------------------------------
titulo('Unión, intersección y diferencia');
$robotica = new SplObjectStorage();
$robotica->attach($ana);
$robotica->attach($brais);
$teatro = new SplObjectStorage();
$teatro->attach($brais);
$teatro->attach($carmen);
$nombres = fn(SplObjectStorage $s) => array_map(fn($u) => $u->nombre, iterator_to_array($s, false));

$alguno = clone $robotica;
$alguno->addAll($teatro);                       // unión
$ambos = clone $robotica;
$ambos->removeAllExcept($teatro);               // intersección
$soloRobotica = clone $robotica;
$soloRobotica->removeAll($teatro);              // diferencia
ver('a alguno', $nombres($alguno));             // ['Ana', 'Brais', 'Carmen']
ver('a los dos', $nombres($ambos));             // ['Brais']
ver('solo robótica', $nombres($soloRobotica));  // ['Ana']

// ---------------------------------------------------------------------------------------------
// getHash — ¿Cuándo son "el mismo" dos objetos?
// ENLACE: https://www.php.net/manual/es/splobjectstorage.gethash.php
// ENUNCIADO EJEMPLO: dos objetos Usuario distintos con el mismo email deberían contar como uno.
// NOTA: por defecto se identifica cada objeto por su identidad (spl_object_hash): dos objetos con
//       los mismos datos son DISTINTOS. Heredando y redefiniendo getHash() se puede elegir la
//       "clave" (aquí, el email en minúsculas).
// ---------------------------------------------------------------------------------------------
titulo('getHash');
$otraAna = new Usuario('Ana Pérez', 'ANA@example.com');     // otro objeto, mismo email
$normal = new SplObjectStorage();
$normal->attach($ana);
$normal->attach($otraAna);
ver('por identidad', count($normal));                        // 2
final class UsuariosPorEmail extends SplObjectStorage
{
    public function getHash(object $usuario): string
    {
        return mb_strtolower($usuario->email);
    }
}
$porEmail = new UsuariosPorEmail();
$porEmail->attach($ana);
$porEmail->attach($otraAna);
ver('por email', count($porEmail));                          // 1
ver('¿contiene a otraAna?', $porEmail->contains($otraAna));  // true

// ---------------------------------------------------------------------------------------------
// WeakMap (PHP 8.0): un mapa objeto => dato que no "retiene" los objetos
// ENLACE: https://www.php.net/manual/es/class.weakmap.php
// ENUNCIADO EJEMPLO: guardar datos de los usuarios sin impedir que se liberen de memoria.
// NOTA: SplObjectStorage guarda una referencia al objeto: mientras esté dentro, NO se destruye.
//       En un WeakMap, cuando el objeto ya no se usa en ningún otro sitio, su entrada desaparece
//       sola. Útil para cachés de datos asociados a objetos.
// ---------------------------------------------------------------------------------------------
titulo('WeakMap');
$temporal = new Usuario('Diego', 'diego@example.com');
$storage = new SplObjectStorage();
$debil = new WeakMap();
$storage[$temporal] = 'dato';
$debil[$temporal] = 'dato';
unset($temporal);                               // ya nadie más usa a Diego...
ver('en SplObjectStorage', count($storage));    // 1: el storage lo mantiene vivo
ver('en WeakMap', count($debil));               // 1: ¡también! Porque el storage aún lo tiene
$storage = null;                                // ahora sí: nadie apunta a Diego
ver('en WeakMap tras liberar el storage', count($debil));   // 0: la entrada ha desaparecido sola

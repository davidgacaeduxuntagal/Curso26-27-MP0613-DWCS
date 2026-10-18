<?php
/*
 * e01-SplObjectStorage_problema_solucion.php
 *  (FICHERO CON LAS SOLUCIONES)
 *  Problemas de: SplObjectStorage como conjunto y como mapa (attach, detach, contains,
 *                $s[$obj], getInfo / setInfo, addAll / removeAll / removeAllExcept, getHash)
 *  (repasa los ejemplos de ../e01-SplObjectStorage.php)
 *
 *  Resuelve cada problema donde se indica y ejecútalo en modo depuración para comprobarlo.
 */
require_once __DIR__ . '/../../../utilidades.php';

final class Libro
{
    public function __construct(public readonly string $isbn, public readonly string $titulo)
    {
    }
}
$quijote = new Libro('978-84-376-0494-7', 'El Quijote');
$cien = new Libro('978-84-9759-209-1', 'Cien años de soledad');
$memorias = new Libro('978-84-9782-512-0', 'Memorias dun neno labrego');

// =============================================================================================
// PROBLEMA 1. Préstamos de la biblioteca
//  Usa un SplObjectStorage como mapa Libro => nombre del socio que lo tiene. Presta El Quijote a
//  Ana y Memorias a Brais; después Ana lo devuelve. Muestra qué libros están prestados y a quién,
//  y comprueba si Cien años de soledad está prestado.
// =============================================================================================
titulo('Problema 1');
// completa aquí tu solución
// ---- SOLUCIÓN ----
$prestados = new SplObjectStorage();
$prestados[$quijote] = 'Ana';
$prestados[$memorias] = 'Brais';
unset($prestados[$quijote]);                     // devuelto
$lista = [];
foreach ($prestados as $libro) {
    $lista[] = "$libro->titulo -> " . $prestados->getInfo();
}
ver('prestados', $lista);                        // ['Memorias dun neno labrego -> Brais']
ver('¿Cien años prestado?', $prestados->contains($cien));   // false

// =============================================================================================
// PROBLEMA 2. Contar votos sin repetir
//  Cada objeto Votante solo puede votar una vez. Recorre $votos (pares [votante, opción]) y
//  guarda en un SplObjectStorage el voto de cada votante (si vuelve a votar, se ignora). Cuenta
//  los votos de cada opción.
// =============================================================================================
titulo('Problema 2');
final class Votante
{
    public function __construct(public readonly string $dni)
    {
    }
}
[$v1, $v2, $v3] = [new Votante('1A'), new Votante('2B'), new Votante('3C')];
$votos = [[$v1, 'tortilla'], [$v2, 'pulpo'], [$v1, 'pulpo'], [$v3, 'tortilla'], [$v2, 'empanada']];
// completa aquí tu solución
// ---- SOLUCIÓN ----
$urna = new SplObjectStorage();
foreach ($votos as [$votante, $opcion]) {
    if (!$urna->contains($votante)) {
        $urna[$votante] = $opcion;
    }
}
$recuento = [];
foreach ($urna as $votante) {
    $opcion = $urna->getInfo();
    $recuento[$opcion] = ($recuento[$opcion] ?? 0) + 1;
}
ver('recuento', $recuento);      // ['tortilla' => 2, 'pulpo' => 1]

// =============================================================================================
// PROBLEMA 3. Conjuntos de libros
//  Ana ha leído El Quijote y Memorias; Brais, Memorias y Cien años. Con SplObjectStorage y
//  addAll / removeAll / removeAllExcept, obtén: los libros que han leído los dos, los que ha
//  leído alguno y los que ha leído Ana pero no Brais (sin modificar los conjuntos originales).
// =============================================================================================
titulo('Problema 3');
$leidosAna = new SplObjectStorage();
$leidosAna->attach($quijote);
$leidosAna->attach($memorias);
$leidosBrais = new SplObjectStorage();
$leidosBrais->attach($memorias);
$leidosBrais->attach($cien);
// completa aquí tu solución
// ---- SOLUCIÓN ----
$titulos = fn(SplObjectStorage $s) => array_map(fn($l) => $l->titulo, iterator_to_array($s, false));
$losDos = clone $leidosAna;
$losDos->removeAllExcept($leidosBrais);
$alguno = clone $leidosAna;
$alguno->addAll($leidosBrais);
$soloAna = clone $leidosAna;
$soloAna->removeAll($leidosBrais);
ver('los dos', $titulos($losDos));      // ['Memorias dun neno labrego']
ver('alguno', $titulos($alguno));       // ['El Quijote', 'Memorias dun neno labrego', 'Cien años de soledad']
ver('solo Ana', $titulos($soloAna));    // ['El Quijote']
ver('originales intactos', [count($leidosAna), count($leidosBrais)]);   // [2, 2]

// =============================================================================================
// PROBLEMA 4. ¿Cómo harías...?
//  En la biblioteca a veces se crean dos objetos Libro para el mismo libro (mismo ISBN). ¿Cómo
//  harías un SplObjectStorage que los trate como el mismo? Escribe la clase LibrosPorIsbn y
//  compruébalo con $copia (otro objeto con el ISBN de El Quijote).
// =============================================================================================
titulo('Problema 4');
$copia = new Libro('978-84-376-0494-7', 'El ingenioso hidalgo');
// completa aquí tu solución
// ---- SOLUCIÓN ----
final class LibrosPorIsbn extends SplObjectStorage
{
    public function getHash(object $libro): string
    {
        return str_replace('-', '', $libro->isbn);
    }
}
$catalogo = new LibrosPorIsbn();
$catalogo->attach($quijote);
$catalogo->attach($copia);                // mismo ISBN: no se añade otro
ver('libros', count($catalogo));          // 1
ver('¿contiene la copia?', $catalogo->contains($copia));   // true

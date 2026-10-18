<?php
/*
 * e01-SplObjectStorage_problema.php
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

// =============================================================================================
// PROBLEMA 4. ¿Cómo harías...?
//  En la biblioteca a veces se crean dos objetos Libro para el mismo libro (mismo ISBN). ¿Cómo
//  harías un SplObjectStorage que los trate como el mismo? Escribe la clase LibrosPorIsbn y
//  compruébalo con $copia (otro objeto con el ISBN de El Quijote).
// =============================================================================================
titulo('Problema 4');
$copia = new Libro('978-84-376-0494-7', 'El ingenioso hidalgo');
// completa aquí tu solución

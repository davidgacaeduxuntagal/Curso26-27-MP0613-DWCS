<?php
/*
 * ConMana.php
 *  TRAIT: un bloque de código (atributos y métodos) que se "copia" dentro de las clases que lo usan
 *  con la palabra "use". Mago y Sanador NO tienen un padre común con maná (Arquero y Guerrero no
 *  lo usan), así que la herencia no sirve para compartir este código: el trait sí.
 *
 *  El trait aporta la IMPLEMENTACIÓN; la interfaz UsaMana aporta el TIPO (permite preguntar
 *  $personaje instanceof UsaMana). Un trait no se puede usar con instanceof.
 */
// TODO: trait ConMana
//   TODO: protected int $mana = 0;
//   TODO: protected int $manaMax = 0;
//   TODO: protected function inicializarMana(int $maximo): void   -> Debe llamarse desde el constructor de la clase que usa el trait.
//   TODO: public function getMana(): int
//   TODO: public function getManaMax(): int
//   TODO: public function tieneMana(int $coste): bool
//   TODO: public function gastarMana(int $coste): void
//   TODO: public function regenerarMana(int $puntos): void

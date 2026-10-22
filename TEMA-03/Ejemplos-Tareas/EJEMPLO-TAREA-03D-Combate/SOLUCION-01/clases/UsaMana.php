<?php
/*
 * UsaMana.php
 *  Interfaz de los personajes que usan maná (energía mágica) para su habilidad especial.
 *  La implementación de estos métodos la aporta el trait ConMana.
 */
interface UsaMana
{
    public function getMana(): int;

    public function getManaMax(): int;

    public function tieneMana(int $coste): bool;

    public function gastarMana(int $coste): void;

    public function regenerarMana(int $puntos): void;
}

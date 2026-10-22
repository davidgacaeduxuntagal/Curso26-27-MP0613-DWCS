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
trait ConMana
{
    protected int $mana = 0;
    protected int $manaMax = 0;

    /**
     * Debe llamarse desde el constructor de la clase que usa el trait.
     */
    protected function inicializarMana(int $maximo): void
    {
        $this->manaMax = $maximo;
        $this->mana    = $maximo;
    }

    public function getMana(): int
    {
        return $this->mana;
    }

    public function getManaMax(): int
    {
        return $this->manaMax;
    }

    public function tieneMana(int $coste): bool
    {
        return $this->mana >= $coste;
    }

    public function gastarMana(int $coste): void
    {
        if (!$this->tieneMana($coste)) {
            throw new LogicException("No hay maná suficiente ({$this->mana}/{$coste})");
        }
        $this->mana -= $coste;
    }

    public function regenerarMana(int $puntos): void
    {
        $this->mana = min($this->manaMax, $this->mana + $puntos);
    }
}

<?php
/*
 * Sanador.php
 *  Usa maná. Especial: "Curación en grupo", cura a TODOS los aliados vivos.
 */
class Sanador extends Personaje implements UsaMana
{
    use ConMana;

    public const COSTE_ESPECIAL = 20;
    public const CURACION       = 25;
    public const REGENERACION   = 6;

    public function __construct(string $nombre)
    {
        parent::__construct($nombre, self::variar(85), self::variar(9), self::variar(4));
        $this->inicializarMana(50);
    }

    public function clase(): string
    {
        return 'Sanador';
    }

    public function icono(): string
    {
        return '✨';
    }

    public function nombreEspecial(): string
    {
        return 'Curación en grupo';
    }

    public function especialNecesitaObjetivo(): bool
    {
        return false;
    }

    protected function enfriamientoEspecial(): int
    {
        return 1;
    }

    public function puedeUsarEspecial(): bool
    {
        return parent::puedeUsarEspecial() && $this->tieneMana(self::COSTE_ESPECIAL);
    }

    protected function ejecutarEspecial(Equipo $aliados, Equipo $enemigos, ?Personaje $objetivo): string
    {
        $this->gastarMana(self::COSTE_ESPECIAL);
        $partes = [];
        foreach ($aliados->vivos() as $aliado) {
            $partes[] = "{$aliado->nombre} +" . $aliado->curar(self::CURACION);
        }
        return "{$this->nombre} usa Curación en grupo: " . implode(", ", $partes);
    }

    public function finTurno(): void
    {
        parent::finTurno();
        $this->regenerarMana(self::REGENERACION);
    }
}

<?php
/*
 * Mago.php
 *  Poca vida, usa maná. Especial: "Bola de fuego", daña a TODOS los enemigos vivos.
 */
class Mago extends Personaje implements UsaMana
{
    use ConMana;       // aporta $mana, $manaMax y los métodos de la interfaz UsaMana

    public const COSTE_ESPECIAL = 20;
    public const REGENERACION   = 5;

    public function __construct(string $nombre)
    {
        parent::__construct($nombre, self::variar(75), self::variar(10), self::variar(3));
        $this->inicializarMana(40);
    }

    public function clase(): string
    {
        return 'Mago';
    }

    public function icono(): string
    {
        return '🔮';
    }

    public function nombreEspecial(): string
    {
        return 'Bola de fuego';
    }

    public function especialNecesitaObjetivo(): bool
    {
        return false;
    }

    protected function enfriamientoEspecial(): int
    {
        return 1;
    }

    /**
     * Además de la espera, necesita maná suficiente.
     */
    public function puedeUsarEspecial(): bool
    {
        return parent::puedeUsarEspecial() && $this->tieneMana(self::COSTE_ESPECIAL);
    }

    protected function ejecutarEspecial(Equipo $aliados, Equipo $enemigos, ?Personaje $objetivo): string
    {
        $this->gastarMana(self::COSTE_ESPECIAL);
        $partes = [];
        foreach ($enemigos->vivos() as $enemigo) {
            $danio    = $enemigo->recibirDanio($this->ataque + 8);
            $partes[] = "{$enemigo->nombre} −{$danio}" . self::textoCaida($enemigo);
        }
        return "{$this->nombre} lanza una Bola de fuego: " . implode(", ", $partes);
    }

    public function finTurno(): void
    {
        parent::finTurno();
        $this->regenerarMana(self::REGENERACION);
    }
}

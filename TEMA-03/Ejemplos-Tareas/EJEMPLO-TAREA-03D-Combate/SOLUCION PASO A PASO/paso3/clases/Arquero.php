<?php
/*
 * Arquero.php
 *  Ataque alto. Especial: "Disparo certero", 1,5 veces el ataque IGNORANDO la defensa del enemigo.
 */
class Arquero extends Personaje
{
    public function __construct(string $nombre)
    {
        parent::__construct($nombre, self::variar(90), self::variar(15), self::variar(5));
    }

    public function clase(): string
    {
        return 'Arquero';
    }

    public function icono(): string
    {
        return '🏹';
    }

    public function nombreEspecial(): string
    {
        return 'Disparo certero';
    }

    public function especialNecesitaObjetivo(): bool
    {
        return true;
    }

    protected function enfriamientoEspecial(): int
    {
        return 2;
    }

    protected function ejecutarEspecial(Equipo $aliados, Equipo $enemigos, ?Personaje $objetivo): string
    {
        $danio = $objetivo->recibirDanio((int) round($this->ataque * 1.5), true);
        return "{$this->nombre} lanza un Disparo certero a {$objetivo->nombre}: −{$danio}" . self::textoCaida($objetivo);
    }
}

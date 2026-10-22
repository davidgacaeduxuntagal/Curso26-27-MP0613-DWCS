<?php
/*
 * Guerrero.php
 *  Mucha vida y defensa. Especial: "Golpe brutal", doble daño a un enemigo.
 */
class Guerrero extends Personaje
{
    public function __construct(string $nombre)
    {
        parent::__construct($nombre, self::variar(130), self::variar(17), self::variar(8));
    }

    public function clase(): string
    {
        return 'Guerrero';
    }

    public function icono(): string
    {
        return '🛡️';
    }

    public function nombreEspecial(): string
    {
        return 'Golpe brutal';
    }

    public function especialNecesitaObjetivo(): bool
    {
        return true;
    }

    protected function enfriamientoEspecial(): int
    {
        return 3;
    }

    protected function ejecutarEspecial(Equipo $aliados, Equipo $enemigos, ?Personaje $objetivo): string
    {
        $danio = $objetivo->recibirDanio($this->ataque * 2);
        return "{$this->nombre} usa Golpe brutal contra {$objetivo->nombre}: −{$danio}" . self::textoCaida($objetivo);
    }
}

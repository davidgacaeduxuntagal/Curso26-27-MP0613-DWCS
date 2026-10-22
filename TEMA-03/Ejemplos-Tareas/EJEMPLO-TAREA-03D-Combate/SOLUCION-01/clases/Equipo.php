<?php
/*
 * Equipo.php
 *  Un grupo de personajes. Implementa Countable (count($equipo) = número de miembros).
 *
 *  __clone: al clonar un equipo, PHP copia el array $miembros, pero los OBJETOS de dentro serían
 *  los mismos (copia superficial). Para "deshacer" necesitamos una copia PROFUNDA: clonamos
 *  también cada personaje.
 */
class Equipo implements Countable
{
    public const MAX_MIEMBROS = 4;

    private string $nombre;
    /** @var Personaje[] */
    private array $miembros = [];

    /**
     * @param Personaje[] $miembros
     */
    public function __construct(string $nombre, array $miembros)
    {
        if (count($miembros) < 1 || count($miembros) > self::MAX_MIEMBROS) {
            throw new InvalidArgumentException("Un equipo tiene entre 1 y " . self::MAX_MIEMBROS . " personajes");
        }
        foreach ($miembros as $miembro) {
            if (!($miembro instanceof Personaje)) {
                throw new InvalidArgumentException("Un equipo solo puede contener personajes");
            }
        }
        $this->nombre   = $nombre;
        $this->miembros = array_values($miembros);
    }

    public function getNombre(): string
    {
        return $this->nombre;
    }

    /**
     * @return Personaje[]
     */
    public function getMiembros(): array
    {
        return $this->miembros;
    }

    public function miembro(int $indice): ?Personaje
    {
        return $this->miembros[$indice] ?? null;
    }

    public function count(): int
    {
        return count($this->miembros);
    }

    /**
     * Personajes vivos, CONSERVANDO su índice en el equipo.
     * @return Personaje[]
     */
    public function vivos(): array
    {
        return array_filter($this->miembros, fn(Personaje $p) => $p->estaVivo());
    }

    public function estaDerrotado(): bool
    {
        return count($this->vivos()) === 0;
    }

    /**
     * El personaje vivo con menos vida (el objetivo favorito de la IA), o null.
     */
    public function masDebil(): ?Personaje
    {
        $vivos = $this->vivos();
        if (count($vivos) === 0) {
            return null;
        }
        usort($vivos, fn(Personaje $a, Personaje $b) => $a->vida <=> $b->vida);
        return $vivos[0];
    }

    /**
     * ¿Hay algún personaje vivo por debajo del porcentaje de vida indicado?
     */
    public function hayHeridos(int $porcentaje): bool
    {
        foreach ($this->vivos() as $personaje) {
            if ($personaje->porcentajeVida() < $porcentaje) {
                return true;
            }
        }
        return false;
    }

    public function finTurno(): void
    {
        foreach ($this->vivos() as $personaje) {
            $personaje->finTurno();
        }
    }

    /**
     * Copia PROFUNDA: cada personaje se clona también.
     */
    public function __clone(): void
    {
        foreach ($this->miembros as $i => $personaje) {
            $this->miembros[$i] = clone $personaje;
        }
    }

    /**
     * Equipo de $numero personajes al azar.
     */
    public static function aleatorio(string $nombre, int $numero): Equipo
    {
        $miembros = [];
        for ($i = 0; $i < $numero; $i++) {
            $miembros[] = Personaje::aleatorio();
        }
        return new Equipo($nombre, $miembros);
    }
}

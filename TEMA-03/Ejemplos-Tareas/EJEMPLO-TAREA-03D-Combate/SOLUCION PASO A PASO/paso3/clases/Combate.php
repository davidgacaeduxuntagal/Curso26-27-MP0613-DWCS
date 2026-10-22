<?php
/*
 * Combate.php
 *  Reglas del combate por turnos entre el equipo del jugador y el equipo enemigo.
 *
 *  En cada jugada:
 *    1. actúa el personaje ACTIVO del jugador (ataque normal o habilidad especial)
 *    2. si quedan enemigos, actúa UN enemigo (inteligencia artificial sencilla)
 *    3. fin de turno: se reducen las esperas y se regenera el maná
 *    4. pasa a ser activo el siguiente personaje vivo del jugador (al dar la vuelta, nueva ronda)
 *
 *  DESHACER: antes de cada jugada se guarda una COPIA del combate (clone) en $anterior.
 */
class Combate
{
    public const VICTORIA      = 'victoria';
    public const DERROTA       = 'derrota';
    public const MAX_HISTORIAL = 12;

    private Equipo $jugador;
    private Equipo $enemigo;
    private int $ronda = 1;
    private int $activo;                  // índice (en el equipo del jugador) del personaje al que le toca
    private int $turnoEnemigo = 0;        // para que los enemigos actúen por orden
    private array $historial = [];
    private ?string $resultado = null;
    private ?Combate $anterior = null;    // copia del combate antes de la última jugada

    public function __construct(Equipo $jugador, Equipo $enemigo)
    {
        $this->jugador = $jugador;
        $this->enemigo = $enemigo;
        $this->activo  = array_key_first($jugador->vivos());
        $this->registrar("⚔️ ¡Comienza el combate: {$jugador->getNombre()} contra {$enemigo->getNombre()}!");
    }

    // ---------------------------------------------------------------------- consultas

    public function getJugador(): Equipo
    {
        return $this->jugador;
    }

    public function getEnemigo(): Equipo
    {
        return $this->enemigo;
    }

    public function getRonda(): int
    {
        return $this->ronda;
    }

    public function getHistorial(): array
    {
        return $this->historial;
    }

    public function getResultado(): ?string
    {
        return $this->resultado;
    }

    public function estaTerminado(): bool
    {
        return $this->resultado !== null;
    }

    public function personajeActivo(): Personaje
    {
        return $this->jugador->miembro($this->activo);
    }

    public function indiceActivo(): int
    {
        return $this->activo;
    }

    public function puedeDeshacer(): bool
    {
        return $this->anterior !== null;
    }

    // ---------------------------------------------------------------------- jugar

    /**
     * Jugada del jugador: $accion es 'atacar' o 'especial'; $indiceObjetivo, el índice del enemigo.
     * Lanza InvalidArgumentException o LogicException si la jugada no es válida.
     */
    public function jugar(string $accion, ?int $indiceObjetivo): void
    {
        if ($this->estaTerminado()) {
            throw new LogicException("El combate ya ha terminado");
        }
        // TODO (paso 4): guardar una copia (clone) del combate ANTES de la jugada
        $actor    = $this->personajeActivo();
        $objetivo = $indiceObjetivo === null ? null : $this->enemigo->miembro($indiceObjetivo);

        $texto = match ($accion) {
            'atacar'   => $this->atacarCon($actor, $objetivo),
            'especial' => $actor->usarEspecial($this->jugador, $this->enemigo, $objetivo),
            default    => throw new InvalidArgumentException("Acción desconocida: «{$accion}»"),
        };
        $this->registrar("R{$this->ronda} · {$texto}");

        if ($this->enemigo->estaDerrotado()) {
            $this->terminar(self::VICTORIA);
        } else {
            $this->turnoDelEnemigo();
            if ($this->jugador->estaDerrotado()) {
                $this->terminar(self::DERROTA);
            }
        }

        if (!$this->estaTerminado()) {
            $this->jugador->finTurno();
            $this->enemigo->finTurno();
            $this->pasarAlSiguiente();
        }
        // TODO (paso 4): guardar la copia en $this->anterior
    }

    /**
     * Devuelve el combate tal y como estaba antes de la última jugada (o null).
     */
    public function deshacer(): ?Combate
    {
        // TODO (paso 4): devolver la copia guardada antes de la última jugada
        return null;
    }

    // ---------------------------------------------------------------------- auxiliares

    private function atacarCon(Personaje $actor, ?Personaje $objetivo): string
    {
        if ($objetivo === null || !$objetivo->estaVivo()) {
            throw new InvalidArgumentException("Elige un enemigo vivo para atacar");
        }
        return $actor->atacar($objetivo);
    }

    /**
     * IA del enemigo: actúan por turnos; usan la habilidad especial cuando pueden (el sanador solo
     * si hay heridos) y si no, atacan al personaje más débil del jugador.
     */
    private function turnoDelEnemigo(): void
    {
        $vivos  = $this->enemigo->vivos();
        $indices = array_keys($vivos);
        $actor  = $vivos[$indices[$this->turnoEnemigo % count($indices)]];
        $this->turnoEnemigo++;

        $objetivo   = $this->jugador->masDebil();
        $usaEspecial = $actor->puedeUsarEspecial()
            && (!($actor instanceof Sanador) || $this->enemigo->hayHeridos(70));

        $texto = $usaEspecial
            ? $actor->usarEspecial($this->enemigo, $this->jugador, $objetivo)
            : $actor->atacar($objetivo);
        $this->registrar("R{$this->ronda} · 👹 {$texto}");
    }

    /**
     * El siguiente personaje vivo del jugador (dando la vuelta al final: nueva ronda).
     */
    private function pasarAlSiguiente(): void
    {
        $indices = array_keys($this->jugador->vivos());
        foreach ($indices as $indice) {
            if ($indice > $this->activo) {
                $this->activo = $indice;
                return;
            }
        }
        $this->activo = $indices[0];
        $this->ronda++;
    }

    private function terminar(string $resultado): void
    {
        $this->resultado = $resultado;
        $this->registrar($resultado === self::VICTORIA ? "🏆 ¡VICTORIA!" : "☠️ Derrota... tu equipo ha caído");
    }

    private function registrar(string $texto): void
    {
        $this->historial[] = $texto;
        $this->historial   = array_slice($this->historial, -self::MAX_HISTORIAL);
    }

    /**
     * Copia PROFUNDA del combate: se clonan los equipos (y estos clonan sus personajes).
     * La copia no guarda a su vez otra copia anterior: solo se puede deshacer una jugada.
     */
    // TODO (paso 4): public function __clone(): void
}

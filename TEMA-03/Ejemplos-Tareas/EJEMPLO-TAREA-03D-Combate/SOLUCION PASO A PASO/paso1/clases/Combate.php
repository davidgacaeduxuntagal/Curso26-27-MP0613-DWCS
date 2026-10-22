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
// TODO: class Combate
//   TODO: public const VICTORIA      = 'victoria';
//   TODO: public const DERROTA       = 'derrota';
//   TODO: public const MAX_HISTORIAL = 12;
//   TODO: private Equipo $jugador;
//   TODO: private Equipo $enemigo;
//   TODO: private int $ronda = 1;
//   TODO: private int $activo;
//   TODO: private int $turnoEnemigo = 0;
//   TODO: private array $historial = [];
//   TODO: private ?string $resultado = null;
//   TODO: private ?Combate $anterior = null;
//   TODO: public function __construct(Equipo $jugador, Equipo $enemigo)
//   TODO: public function getJugador(): Equipo
//   TODO: public function getEnemigo(): Equipo
//   TODO: public function getRonda(): int
//   TODO: public function getHistorial(): array
//   TODO: public function getResultado(): ?string
//   TODO: public function estaTerminado(): bool
//   TODO: public function personajeActivo(): Personaje
//   TODO: public function indiceActivo(): int
//   TODO: public function puedeDeshacer(): bool
//   TODO: public function jugar(string $accion, ?int $indiceObjetivo): void   -> Jugada del jugador: $accion es 'atacar' o 'especial'; $indiceObjetivo, el índice del enemigo.
//   TODO: public function deshacer(): ?Combate   -> Devuelve el combate tal y como estaba antes de la última jugada (o null).
//   TODO: private function atacarCon(Personaje $actor, ?Personaje $objetivo): string
//   TODO: private function turnoDelEnemigo(): void   -> IA del enemigo: actúan por turnos; usan la habilidad especial cuando pueden (el sanador solo
//   TODO: private function pasarAlSiguiente(): void   -> El siguiente personaje vivo del jugador (dando la vuelta al final: nueva ronda).
//   TODO: private function terminar(string $resultado): void
//   TODO: private function registrar(string $texto): void
//   TODO: public function __clone(): void   -> Copia PROFUNDA del combate: se clonan los equipos (y estos clonan sus personajes).

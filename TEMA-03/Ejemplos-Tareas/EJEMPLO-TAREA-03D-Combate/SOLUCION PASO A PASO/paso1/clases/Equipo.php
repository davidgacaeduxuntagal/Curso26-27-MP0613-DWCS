<?php
/*
 * Equipo.php
 *  Un grupo de personajes. Implementa Countable (count($equipo) = número de miembros).
 *
 *  __clone: al clonar un equipo, PHP copia el array $miembros, pero los OBJETOS de dentro serían
 *  los mismos (copia superficial). Para "deshacer" necesitamos una copia PROFUNDA: clonamos
 *  también cada personaje.
 */
// TODO: class Equipo implements Countable
//   TODO: public const MAX_MIEMBROS = 4;
//   TODO: private string $nombre;
//   TODO: private array $miembros = [];
//   TODO: public function __construct(string $nombre, array $miembros)
//   TODO: public function getNombre(): string
//   TODO: public function getMiembros(): array
//   TODO: public function miembro(int $indice): ?Personaje
//   TODO: public function count(): int
//   TODO: public function vivos(): array   -> Personajes vivos, CONSERVANDO su índice en el equipo.
//   TODO: public function estaDerrotado(): bool
//   TODO: public function masDebil(): ?Personaje   -> El personaje vivo con menos vida (el objetivo favorito de la IA), o null.
//   TODO: public function hayHeridos(int $porcentaje): bool   -> ¿Hay algún personaje vivo por debajo del porcentaje de vida indicado?
//   TODO: public function finTurno(): void
//   TODO: public function __clone(): void   -> Copia PROFUNDA: cada personaje se clona también.
//   TODO: public static function aleatorio(string $nombre, int $numero): Equipo   -> Equipo de $numero personajes al azar.

<?php
/*
 * Personaje.php
 *  Clase ABSTRACTA base de todos los personajes del combate.
 *
 *  Ideas de POO que aparecen aquí:
 *    - atributos protegidos + __get: se pueden LEER desde fuera ($p->vida) pero no modificar
 *      (__set lanza una excepción). La vida solo cambia con recibirDanio() y curar().
 *    - métodos abstractos que cada clase implementa a su manera (habilidad especial, icono...)
 *    - "método plantilla": usarEspecial() es FINAL y hace las comprobaciones comunes; la parte
 *      que cambia la aporta cada subclase en ejecutarEspecial()
 *    - "new static": crearAlAzar() está escrito una sola vez y crea un objeto de la clase desde
 *      la que se llama (Guerrero::crearAlAzar() crea un Guerrero)
 *    - contador estático por clase
 *
 *  (Las clases se cargan todas desde cargar.php, en el orden correcto.)
 */
// TODO: abstract class Personaje
//   TODO: public const CLASES = ['Guerrero', 'Mago', 'Arquero', 'Sanador'];
//   TODO: private const NOMBRES = ['Breogán', 'Uxía', 'Xoán', 'Iria', 'Brais', 'Sabela', 'Antía', 'Roi', ...];
//   TODO: private const SOLO_LECTURA = ['nombre', 'vida', 'vidaMax', 'ataque', 'defensa', 'enfriamiento'];
//   TODO: private static array $creados = [];
//   TODO: protected string $nombre;
//   TODO: protected int $vidaMax;
//   TODO: protected int $vida;
//   TODO: protected int $ataque;
//   TODO: protected int $defensa;
//   TODO: protected int $enfriamiento = 0;
//   TODO: public function __construct(string $nombre, int $vidaMax, int $ataque, int $defensa)
//   TODO: public function __get(string $propiedad): mixed   -> Se ejecuta al LEER un atributo inaccesible desde fuera: $personaje->vida
//   TODO: public function __set(string $propiedad, mixed $valor): void   -> Se ejecuta al ESCRIBIR un atributo inaccesible desde fuera: $personaje->vida = 1000
//   TODO: public function __toString(): string
//   TODO: abstract public function clase(): string;
//   TODO: abstract public function icono(): string;
//   TODO: abstract public function nombreEspecial(): string;
//   TODO: abstract public function especialNecesitaObjetivo(): bool;   -> ¿La habilidad especial se lanza contra UN enemigo concreto (true) o afecta a varios (false)?
//   TODO: abstract protected function enfriamientoEspecial(): int;   -> Turnos de espera después de usar la habilidad especial.
//   TODO: abstract protected function ejecutarEspecial(Equipo $aliados, Equipo $enemigos, ?Personaje $objetivo): string;   -> Lo que hace la habilidad especial. Devuelve el texto para el historial.
//   TODO: public function estaVivo(): bool
//   TODO: public function porcentajeVida(): int
//   TODO: public function puedeUsarEspecial(): bool   -> Las subclases con maná lo redefinen para comprobar además si tienen maná suficiente.
//   TODO: final public function recibirDanio(int $danio, bool $ignorarDefensa = false): int   -> Recibe un golpe. Es FINAL: ninguna subclase puede cambiar cómo se calcula el daño.
//   TODO: public function curar(int $puntos): int   -> Recupera vida sin pasar del máximo. Devuelve los puntos realmente curados.
//   TODO: public function atacar(Personaje $objetivo): string   -> Ataque normal: el daño varía un poco al azar.
//   TODO: final public function usarEspecial(Equipo $aliados, Equipo $enemigos, ?Personaje $objetivo): string   -> MÉTODO PLANTILLA: las comprobaciones son comunes a todos; la habilidad concreta la hace
//   TODO: public function finTurno(): void   -> Al final de cada ronda: se reduce la espera de la habilidad especial.
//   TODO: public static function crearAlAzar(?string $nombre = null): static   -> Crea un personaje DE LA CLASE DESDE LA QUE SE LLAMA, con estadísticas al azar.
//   TODO: public static function aleatorio(?string $nombre = null): Personaje   -> Crea un personaje de una clase cualquiera elegida al azar.
//   TODO: public static function nombreAlAzar(): string
//   TODO: public static function numeroCreados(?string $clase = null): int
//   TODO: protected static function variar(int $valor): int   -> Valor con una variación aleatoria de ±10 %.
//   TODO: protected static function textoCaida(Personaje $objetivo): string

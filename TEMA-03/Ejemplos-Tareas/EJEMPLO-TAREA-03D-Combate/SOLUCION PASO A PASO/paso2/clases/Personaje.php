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
abstract class Personaje
{
    public const CLASES = ['Guerrero', 'Mago', 'Arquero', 'Sanador'];

    private const NOMBRES = ['Breogán', 'Uxía', 'Xoán', 'Iria', 'Brais', 'Sabela', 'Antía', 'Roi',
                             'Navia', 'Ledicia', 'Telmo', 'Xiana', 'Lois', 'Alba', 'Fiz', 'Maruxa'];

    // Atributos que se pueden leer desde fuera con __get
    private const SOLO_LECTURA = ['nombre', 'vida', 'vidaMax', 'ataque', 'defensa', 'enfriamiento'];

    private static array $creados = [];

    protected string $nombre;
    protected int $vidaMax;
    protected int $vida;
    protected int $ataque;
    protected int $defensa;
    protected int $enfriamiento = 0;       // turnos que faltan para poder repetir la habilidad especial

    public function __construct(string $nombre, int $vidaMax, int $ataque, int $defensa)
    {
        $nombre = trim($nombre);
        if ($nombre === '' || mb_strlen($nombre) > 20) {
            throw new InvalidArgumentException("El nombre debe tener entre 1 y 20 caracteres");
        }
        if ($vidaMax <= 0 || $ataque <= 0 || $defensa < 0) {
            throw new InvalidArgumentException("Estadísticas no válidas para {$nombre}");
        }
        $this->nombre  = $nombre;
        $this->vidaMax = $vidaMax;
        $this->vida    = $vidaMax;
        $this->ataque  = $ataque;
        $this->defensa = $defensa;

        self::$creados[static::class] = (self::$creados[static::class] ?? 0) + 1;
    }

    // ---------------------------------------------------------------------- métodos mágicos

    /**
     * Se ejecuta al LEER un atributo inaccesible desde fuera: $personaje->vida
     */
    public function __get(string $propiedad): mixed
    {
        if (in_array($propiedad, self::SOLO_LECTURA, true)) {
            return $this->$propiedad;
        }
        throw new LogicException("El personaje no tiene la propiedad «{$propiedad}»");
    }

    /**
     * Se ejecuta al ESCRIBIR un atributo inaccesible desde fuera: $personaje->vida = 1000
     */
    public function __set(string $propiedad, mixed $valor): void
    {
        throw new LogicException("No se puede modificar «{$propiedad}» directamente: usa los métodos del personaje");
    }

    public function __toString(): string
    {
        return "{$this->icono()} {$this->nombre} ({$this->clase()}) ❤ {$this->vida}/{$this->vidaMax}";
    }

    // ---------------------------------------------------------------------- métodos abstractos

    abstract public function clase(): string;

    abstract public function icono(): string;

    abstract public function nombreEspecial(): string;

    /**
     * ¿La habilidad especial se lanza contra UN enemigo concreto (true) o afecta a varios (false)?
     */
    abstract public function especialNecesitaObjetivo(): bool;

    /**
     * Turnos de espera después de usar la habilidad especial.
     */
    abstract protected function enfriamientoEspecial(): int;

    /**
     * Lo que hace la habilidad especial. Devuelve el texto para el historial.
     */
    abstract protected function ejecutarEspecial(Equipo $aliados, Equipo $enemigos, ?Personaje $objetivo): string;

    // ---------------------------------------------------------------------- estado

    public function estaVivo(): bool
    {
        return $this->vida > 0;
    }

    public function porcentajeVida(): int
    {
        return (int) round($this->vida * 100 / $this->vidaMax);
    }

    /**
     * Las subclases con maná lo redefinen para comprobar además si tienen maná suficiente.
     */
    public function puedeUsarEspecial(): bool
    {
        // TODO (paso 3): vivo y sin enfriamiento
        return false;
    }

    // ---------------------------------------------------------------------- acciones

    /**
     * Recibe un golpe. Es FINAL: ninguna subclase puede cambiar cómo se calcula el daño.
     * Devuelve el daño realmente recibido (al menos 1, y nunca más que la vida que queda).
     */
    final public function recibirDanio(int $danio, bool $ignorarDefensa = false): int
    {
        $real       = max(1, $danio - ($ignorarDefensa ? 0 : $this->defensa));
        $real       = min($real, $this->vida);
        $this->vida -= $real;
        return $real;
    }

    /**
     * Recupera vida sin pasar del máximo. Devuelve los puntos realmente curados.
     */
    public function curar(int $puntos): int
    {
        if (!$this->estaVivo()) {
            return 0;          // los caídos no se pueden curar
        }
        $real        = min($puntos, $this->vidaMax - $this->vida);
        $this->vida += $real;
        return $real;
    }

    /**
     * Ataque normal: el daño varía un poco al azar.
     */
    public function atacar(Personaje $objetivo): string
    {
        $danio = $objetivo->recibirDanio($this->ataque + random_int(-2, 3));
        return "{$this->nombre} ataca a {$objetivo->nombre}: −{$danio}" . self::textoCaida($objetivo);
    }

    /**
     * MÉTODO PLANTILLA: las comprobaciones son comunes a todos; la habilidad concreta la hace
     * ejecutarEspecial(), que cada subclase implementa.
     */
    final public function usarEspecial(Equipo $aliados, Equipo $enemigos, ?Personaje $objetivo): string
    {
        // TODO (paso 3): método plantilla: comprobar puedeUsarEspecial() y el objetivo, ejecutarEspecial() y poner el enfriamiento
        throw new LogicException("La habilidad especial todavía no está programada");
    }

    /**
     * Al final de cada ronda: se reduce la espera de la habilidad especial.
     * Las subclases con maná lo redefinen para regenerarlo (llamando también a parent::finTurno()).
     */
    public function finTurno(): void
    {
        if ($this->enfriamiento > 0) {
            $this->enfriamiento--;
        }
    }

    // ---------------------------------------------------------------------- creación y contadores

    /**
     * Crea un personaje DE LA CLASE DESDE LA QUE SE LLAMA, con estadísticas al azar.
     *   Mago::crearAlAzar()  ->  new static(...) es new Mago(...)
     * (Todas las subclases tienen un constructor que recibe solo el nombre.)
     */
    public static function crearAlAzar(?string $nombre = null): static
    {
        return new static($nombre ?? self::nombreAlAzar());
    }

    /**
     * Crea un personaje de una clase cualquiera elegida al azar.
     */
    public static function aleatorio(?string $nombre = null): Personaje
    {
        $clase = self::CLASES[array_rand(self::CLASES)];
        return $clase::crearAlAzar($nombre);
    }

    public static function nombreAlAzar(): string
    {
        return self::NOMBRES[array_rand(self::NOMBRES)];
    }

    public static function numeroCreados(?string $clase = null): int
    {
        return $clase === null ? array_sum(self::$creados) : (self::$creados[$clase] ?? 0);
    }

    // ---------------------------------------------------------------------- ayudas para las subclases

    /**
     * Valor con una variación aleatoria de ±10 %.
     */
    protected static function variar(int $valor): int
    {
        $margen = intdiv($valor, 10);
        return $valor + random_int(-$margen, $margen);
    }

    protected static function textoCaida(Personaje $objetivo): string
    {
        return $objetivo->estaVivo() ? '' : " 💀 {$objetivo->nombre} cae derrotado";
    }
}

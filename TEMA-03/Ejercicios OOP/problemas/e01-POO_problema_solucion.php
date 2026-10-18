<?php
/*
 * e01-POO_problema_solucion.php
 *  (FICHERO CON LAS SOLUCIONES)
 *  Problemas de: class, new, propiedades tipadas, métodos, $this, __construct, promoción de
 *                propiedades, argumentos con nombre, objetos dentro de objetos, ?->
 *  (repasa los ejemplos de ../Ejercicios OOP/e01-POO.php)
 *
 *  Resuelve cada problema donde se indica y ejecútalo en modo depuración para comprobarlo.
 */
require_once __DIR__ . '/../Ejercicios OOP/utilidades.php';

// =============================================================================================
// PROBLEMA 1. La clase Libro
//  Escribe la clase Libro con las propiedades titulo (string), autor (string), paginas (int) y
//  leidas (int, 0 por defecto), un constructor que reciba las tres primeras y los métodos:
//   - leer(int $paginas): void, que suma páginas leídas sin pasar del total,
//   - porcentaje(): float, el porcentaje leído redondeado a 1 decimal,
//   - terminado(): bool.
//  Crea un libro, lee 120 y luego 300 páginas, y muestra el objeto y su porcentaje.
// =============================================================================================
titulo('Problema 1');
// completa aquí tu solución
// ---- SOLUCIÓN ----
class Libro
{
    public int $leidas = 0;

    public function __construct(public string $titulo, public string $autor, public int $paginas)
    {
    }

    public function leer(int $paginas): void
    {
        $this->leidas = min($this->paginas, $this->leidas + $paginas);
    }

    public function porcentaje(): float
    {
        return round($this->leidas * 100 / $this->paginas, 1);
    }

    public function terminado(): bool
    {
        return $this->leidas === $this->paginas;
    }
}
$libro = new Libro('Os eidos', 'Uxío Novoneyra', 376);
$libro->leer(120);
ver('porcentaje tras 120', $libro->porcentaje());   // 31.9
$libro->leer(300);
ver('libro', $libro);                                 // leidas => 376 (no pasa del total)
ver('¿terminado?', $libro->terminado());              // true

// =============================================================================================
// PROBLEMA 2. Validar en el constructor
//  Escribe la clase Rectangulo con ancho y alto (float), usando promoción de propiedades. El
//  constructor debe lanzar InvalidArgumentException si alguna medida no es positiva. Añade
//  area() y perimetro(). Prueba con 3 x 4 y con -1 x 2 (captura la excepción con try/catch).
// =============================================================================================
titulo('Problema 2');
// completa aquí tu solución
// ---- SOLUCIÓN ----
class Rectangulo
{
    public function __construct(public float $ancho, public float $alto)
    {
        if ($ancho <= 0 || $alto <= 0) {
            throw new InvalidArgumentException("Medidas no válidas: $ancho x $alto");
        }
    }

    public function area(): float
    {
        return $this->ancho * $this->alto;
    }

    public function perimetro(): float
    {
        return 2 * ($this->ancho + $this->alto);
    }
}
$r = new Rectangulo(3, 4);
ver('área y perímetro', [$r->area(), $r->perimetro()]);   // [12.0, 14.0]
try {
    new Rectangulo(-1, 2);
} catch (InvalidArgumentException $e) {
    ver('error', $e->getMessage());                        // "Medidas no válidas: -1 x 2"
}

// =============================================================================================
// PROBLEMA 3. Argumentos con nombre
//  Dada la clase Evento, crea (sin cambiar la clase) un evento "Magosto" en "Praza do Concello"
//  el "2026-11-11" que sea gratuito, pasando SOLO los argumentos necesarios y con nombre.
// =============================================================================================
titulo('Problema 3');
class Evento
{
    public function __construct(
        public string $nombre,
        public string $fecha,
        public string $lugar = 'Por determinar',
        public float $precio = 10.0,
        public int $aforo = 100,
    ) {
    }
}
// completa aquí tu solución
// ---- SOLUCIÓN ----
$magosto = new Evento(nombre: 'Magosto', fecha: '2026-11-11', lugar: 'Praza do Concello', precio: 0);
ver('magosto', $magosto);         // aforo 100 (el valor por defecto)

// =============================================================================================
// PROBLEMA 4. Composición y ?->
//  Un Alumno tiene nombre y, opcionalmente, un Tutor (objeto con nombre y email). Escribe las
//  dos clases y la función emailTutor(Alumno $a): string que devuelva el email del tutor o
//  "sin tutor" si no tiene, usando ?-> y ??. Pruébala con un alumno con tutor y otro sin él.
// =============================================================================================
titulo('Problema 4');
// completa aquí tu solución
// ---- SOLUCIÓN ----
class Tutor
{
    public function __construct(public string $nombre, public string $email)
    {
    }
}
class Alumno
{
    public function __construct(public string $nombre, public ?Tutor $tutor = null)
    {
    }
}
function emailTutor(Alumno $a): string
{
    return $a->tutor?->email ?? 'sin tutor';
}
$tutora = new Tutor('María', 'maria@iesrodeira.gal');
ver('con tutor', emailTutor(new Alumno('Brais', $tutora)));   // "maria@iesrodeira.gal"
ver('sin tutor', emailTutor(new Alumno('Eva')));              // "sin tutor"

// =============================================================================================
// PROBLEMA 5. ¿Cómo harías...?
//  El siguiente código tiene una errata que en PHP 8.2 produce un aviso "Deprecated". ¿Cómo la
//  detectarías y cómo la corregirías? Escribe la explicación en un comentario, corrige la línea
//  y comprueba con get_object_vars que el objeto solo tiene sus propiedades declaradas.
// =============================================================================================
titulo('Problema 5');
class Termostato
{
    public float $temperatura = 20.0;
}
$t = new Termostato();
// completa aquí tu solución
// ---- SOLUCIÓN ----
// La línea original era: $t->temperatrua = 22.5;  (temperatrua: letras cambiadas de orden)
// PHP 8.2 avisa: "Creation of dynamic property Termostato::$temperatrua is deprecated" y crea una
// propiedad nueva en lugar de cambiar la temperatura. Se detecta por el aviso y porque, en el
// panel VARIABLES, el objeto aparece con dos propiedades.
$t->temperatura = 22.5;
ver('propiedades', get_object_vars($t));     // ['temperatura' => 22.5]

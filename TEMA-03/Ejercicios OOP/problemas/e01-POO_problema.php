<?php
/*
 * e01-POO_problema.php
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

// =============================================================================================
// PROBLEMA 2. Validar en el constructor
//  Escribe la clase Rectangulo con ancho y alto (float), usando promoción de propiedades. El
//  constructor debe lanzar InvalidArgumentException si alguna medida no es positiva. Añade
//  area() y perimetro(). Prueba con 3 x 4 y con -1 x 2 (captura la excepción con try/catch).
// =============================================================================================
titulo('Problema 2');
// completa aquí tu solución

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

// =============================================================================================
// PROBLEMA 4. Composición y ?->
//  Un Alumno tiene nombre y, opcionalmente, un Tutor (objeto con nombre y email). Escribe las
//  dos clases y la función emailTutor(Alumno $a): string que devuelva el email del tutor o
//  "sin tutor" si no tiene, usando ?-> y ??. Pruébala con un alumno con tutor y otro sin él.
// =============================================================================================
titulo('Problema 4');
// completa aquí tu solución

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

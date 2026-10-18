<?php
/*
 * e05-POO_problema.php
 *  Problemas de: interface, implements, interfaces como tipo, Countable, Stringable,
 *                JsonSerializable, herencia de interfaces, trait, use, insteadof / as
 *  (repasa los ejemplos de ../Ejercicios OOP/e05-POO.php)
 *
 *  Resuelve cada problema donde se indica y ejecútalo en modo depuración para comprobarlo.
 */
require_once __DIR__ . '/../Ejercicios OOP/utilidades.php';

// =============================================================================================
// PROBLEMA 1. Una interfaz para los envíos
//  Define la interfaz Transportista con los métodos coste(float $pesoKg): float y nombre():
//  string, e impleméntala en Correos (3,50 € + 1,20 € por kg) y Mensajeria (6,90 € fijos hasta
//  5 kg y 0,80 € por cada kg que pase de 5). Escribe la función masBarato(float $peso, array
//  $transportistas): string que devuelva el nombre del más barato.
// =============================================================================================
titulo('Problema 1');
// completa aquí tu solución

// =============================================================================================
// PROBLEMA 2. Una playlist con interfaces de PHP
//  Escribe la clase Playlist (nombre y canciones: array de ['titulo' => ..., 'segundos' => ...])
//  que implemente Countable (número de canciones), Stringable ("Nombre (N canciones, MM:SS)") y
//  JsonSerializable (nombre, canciones y duración total en segundos).
// =============================================================================================
titulo('Problema 2');
// completa aquí tu solución

// =============================================================================================
// PROBLEMA 3. Un trait para "dar me gusta"
//  Escribe el trait ConMeGusta (array de usuarios que han dado "me gusta"; métodos
//  darMeGusta(string $usuario): void, quitarMeGusta(string): void y meGusta(): int, sin
//  repetir usuarios) y úsalo en dos clases sin relación: Foto (url) y Comentario (texto).
// =============================================================================================
titulo('Problema 3');
// completa aquí tu solución

// =============================================================================================
// PROBLEMA 4. Conflicto entre traits
//  Los traits Saludo y Despedida tienen los dos un método mensaje(). Escribe la clase Bot que
//  use los dos, de forma que mensaje() sea el de Saludo y el de Despedida se llame adios().
// =============================================================================================
titulo('Problema 4');
trait Saludo
{
    public function mensaje(): string
    {
        return 'Ola! En que che podo axudar?';
    }
}
trait Despedida
{
    public function mensaje(): string
    {
        return 'Ata logo!';
    }
}
// completa aquí tu solución

// =============================================================================================
// PROBLEMA 5. ¿Interfaz, clase abstracta o trait?
//  Para cada caso, ¿qué usarías y por qué? Responde en un comentario.
//   a) Todos los métodos de pago deben tener cobrar() y reembolsar(), pero cada uno los hace de
//      una forma completamente distinta.
//   b) Coche, Moto y Camión comparten matrícula, marca y el cálculo del impuesto de circulación,
//      y no tiene sentido crear un "Vehículo" a secas.
//   c) Usuarios, productos y pedidos (que no se parecen en nada) necesitan el mismo código para
//      guardar sus fechas de creación y modificación.
// =============================================================================================
titulo('Problema 5');
// completa aquí tu solución

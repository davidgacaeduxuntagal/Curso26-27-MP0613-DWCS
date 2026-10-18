<?php
/*
 * e03-POO_problema.php
 *  Problemas de: private, getters y setters con validación, readonly, constantes de clase,
 *                propiedades y métodos estáticos, métodos de fábrica
 *  (repasa los ejemplos de ../Ejercicios OOP/e03-POO.php)
 *
 *  Resuelve cada problema donde se indica y ejecútalo en modo depuración para comprobarlo.
 */
require_once __DIR__ . '/../Ejercicios OOP/utilidades.php';

// =============================================================================================
// PROBLEMA 1. Encapsular
//  Esta clase deja que cualquiera ponga notas imposibles. Reescríbela como NotaSegura: con la
//  nota private, un getter, un setter que solo acepte valores de 0 a 10 (si no, lanza
//  InvalidArgumentException) y un método aprobado(). Prueba a poner un 7.5 y un 12.
// =============================================================================================
titulo('Problema 1');
class NotaInsegura
{
    public float $nota = 0;
}
$mal = new NotaInsegura();
$mal->nota = 15;                 // ¡nadie lo impide!
// completa aquí tu solución

// =============================================================================================
// PROBLEMA 2. Objetos inmutables con readonly
//  Escribe la clase final Coordenada con latitud y longitud public readonly (float), que en el
//  constructor compruebe los rangos (-90..90 y -180..180). Añade distanciaKm(Coordenada $otra):
//  float con la fórmula de abajo. Calcula la distancia entre A Coruña y Santiago y comprueba que
//  no se puede cambiar la latitud.
// =============================================================================================
titulo('Problema 2');
// Fórmula (haversine), con R = 6371 km y los ángulos en radianes (deg2rad):
//   a = sin²(Δlat/2) + cos(lat1)·cos(lat2)·sin²(Δlon/2);   d = 2·R·asin(√a)
// completa aquí tu solución

// =============================================================================================
// PROBLEMA 3. Un generador de identificadores
//  Escribe la clase Ticket con un número que se asigne automáticamente (1, 2, 3...) usando una
//  propiedad static privada, y un método estático total() que diga cuántos tickets se han
//  creado. Crea tres tickets y comprueba los números.
// =============================================================================================
titulo('Problema 3');
// completa aquí tu solución

// =============================================================================================
// PROBLEMA 4. Métodos de fábrica
//  Escribe la clase Duracion que guarde segundos (int) con un constructor PRIVADO y tres
//  fábricas: desdeSegundos(int), desdeMinutos(float) y desdeTexto(string "HH:MM:SS"). Añade
//  formato(): string que devuelva "HH:MM:SS". Comprueba que las tres dan "01:30:00" para hora y
//  media.
// =============================================================================================
titulo('Problema 4');
// completa aquí tu solución

// =============================================================================================
// PROBLEMA 5. ¿Cómo harías...?
//  ¿Cómo harías para que una clase Config tuviera unas constantes con los límites de la tienda
//  (MAX_PRODUCTOS_CARRITO = 50, ENVIO_GRATIS_DESDE = 50.0) que se pudieran consultar desde
//  fuera, y una constante CLAVE_API que SOLO se pudiera usar dentro de la clase? Escríbela y
//  demuestra los dos accesos.
// =============================================================================================
titulo('Problema 5');
// completa aquí tu solución

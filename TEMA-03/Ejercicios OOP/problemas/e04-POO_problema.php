<?php
/*
 * e04-POO_problema.php
 *  Problemas de: herencia (extends, parent::), redefinir métodos, protected, final, clases y
 *                métodos abstractos, polimorfismo, instanceof
 *  (repasa los ejemplos de ../Ejercicios OOP/e04-POO.php)
 *
 *  Resuelve cada problema donde se indica y ejecútalo en modo depuración para comprobarlo.
 */
require_once __DIR__ . '/../Ejercicios OOP/utilidades.php';

// =============================================================================================
// PROBLEMA 1. Empleados de un hospital
//  Crea la clase abstracta Empleado (nombre y sueldoBase protected, método abstracto
//  sueldo(): float y un método ficha(): string con "nombre: sueldo €"), y las hijas:
//   - Medico: sueldo base + 600 € por cada guardia (guardias en el constructor),
//   - Enfermero: sueldo base + 15 % si trabaja de noche (bool en el constructor),
//   - Administrativo: el sueldo base.
//  Muestra la ficha de un empleado de cada tipo.
// =============================================================================================
titulo('Problema 1');
// completa aquí tu solución

// =============================================================================================
// PROBLEMA 2. Polimorfismo
//  Usando las clases del problema 1, calcula el gasto total en nóminas de $plantilla SIN usar
//  instanceof ni if: solo llamando al método polimórfico.
// =============================================================================================
titulo('Problema 2');
// completa aquí tu solución

// =============================================================================================
// PROBLEMA 3. Redefinir y reutilizar
//  Crea MedicoJefe, que hereda de Medico: cobra además un complemento de 800 €. Redefine sueldo()
//  reutilizando el cálculo del padre con parent::, y redefine ficha() para añadir " (jefe)".
// =============================================================================================
titulo('Problema 3');
// completa aquí tu solución

// =============================================================================================
// PROBLEMA 4. instanceof con moderación
//  De $plantilla (y el jefe), obtén los nombres de los empleados que hacen guardias (los Medico,
//  incluidos los jefes). ¿Por qué aquí sí tiene sentido instanceof? Contesta en un comentario.
// =============================================================================================
titulo('Problema 4');
// completa aquí tu solución

// =============================================================================================
// PROBLEMA 5. ¿Cómo harías...?
//  ¿Cómo harías para que ninguna clase hija pudiera cambiar la forma en que ficha() presenta los
//  datos, pero sí el sueldo? ¿Y para impedir que alguien herede de Administrativo? Explícalo en
//  un comentario con el código necesario (no lo ejecutes: daría error con MedicoJefe).
// =============================================================================================
titulo('Problema 5');
// completa aquí tu solución

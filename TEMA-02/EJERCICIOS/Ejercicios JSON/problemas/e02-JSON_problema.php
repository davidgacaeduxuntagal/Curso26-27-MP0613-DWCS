<?php
/*
 * e02-JSON_problema.php
 *  Problemas de: json_decode (objetos stdClass o arrays, tipos, datos anidados, profundidad,
 *                JSON_BIGINT_AS_STRING), ida y vuelta
 *  (repasa los ejemplos de ../e02-JSON.php)
 *
 *  Resuelve cada problema donde se indica y ejecútalo en modo depuración para comprobarlo.
 */
require_once __DIR__ . '/../utilidades.php';

// Respuesta de una API de reservas de restaurantes (la usan varios problemas)
$json = '{
    "restaurante": "Bar Baio",
    "fecha": "2026-10-16",
    "reservas": [
        {"id": 101, "nombre": "Ana", "personas": 2, "hora": "13:30", "terraza": true},
        {"id": 102, "nombre": "Brais", "personas": 6, "hora": "14:00", "terraza": false},
        {"id": 103, "nombre": "Carmen", "personas": 4, "hora": "21:00", "terraza": true},
        {"id": 104, "nombre": "Diego", "personas": 3, "hora": "21:30", "terraza": false}
    ]
}';

// =============================================================================================
// PROBLEMA 1. Con objetos
//  Decodifica $json SIN el segundo parámetro (objetos stdClass) y muestra el nombre del
//  restaurante, el número de reservas y el nombre de la tercera reserva.
// =============================================================================================
titulo('Problema 1');
// completa aquí tu solución

// =============================================================================================
// PROBLEMA 2. Con arrays: resumen de las reservas
//  Decodifica $json como arrays asociativos y calcula: el total de comensales, los nombres de
//  quienes quieren terraza y las reservas de la cena (hora a partir de las 20:00).
// =============================================================================================
titulo('Problema 2');
// completa aquí tu solución

// =============================================================================================
// PROBLEMA 3. Índice por id
//  Convierte la lista de reservas en un array indexado por id (101 => [...], 102 => [...]) para
//  poder buscar rápido una reserva. Muestra la reserva 103 y qué devuelve json_encode del
//  índice (¿lista u objeto?, ¿por qué?).
// =============================================================================================
titulo('Problema 3');
// completa aquí tu solución

// =============================================================================================
// PROBLEMA 4. ¿Qué tipo llega?
//  La app envía a veces los números entre comillas. Escribe una función leerPersonas(string
//  $json): ?int que devuelva el número de personas SOLO si es un entero de verdad (no "4", ni
//  4.5) y esté entre 1 y 20; si no, null. Pruébala con los casos de $pruebas.
// =============================================================================================
titulo('Problema 4');
$pruebas = ['{"personas": 4}', '{"personas": "4"}', '{"personas": 4.5}', '{"personas": 50}', '{}', 'no es json'];
// completa aquí tu solución

// =============================================================================================
// PROBLEMA 5. Identificadores enormes
//  Una pasarela de pago devuelve el id de cada operación como un número muy grande. Decodifica
//  $pago de forma que el id se conserve EXACTO y comprueba que coincide con $idEsperado.
//  Muestra también qué pasaría si no usas la opción adecuada.
// =============================================================================================
titulo('Problema 5');
$pago = '{"operacion": 98765432109876543210, "importe": 33.5, "estado": "aceptado"}';
$idEsperado = '98765432109876543210';
// completa aquí tu solución

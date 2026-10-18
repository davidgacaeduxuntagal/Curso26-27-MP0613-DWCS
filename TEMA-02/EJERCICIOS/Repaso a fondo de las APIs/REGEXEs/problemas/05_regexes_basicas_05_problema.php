<?php
/*
 * 05_regexes_basicas_05_problema.php
 *  Problemas de: grupos con nombre (?<nombre> ), referencias por nombre \k<nombre>, grupos con
 *                nombre opcionales, (?J)
 *  (repasa los ejemplos de ../05_regexes_basicas_05.php)
 *
 *  Resuelve cada problema donde se indica y ejecútalo en modo depuración para comprobarlo.
 */
require_once __DIR__ . '/../utilidades.php';

// =============================================================================================
// PROBLEMA 1. Matrícula de coche
//  Captura con grupos con nombre los "numeros" y las "letras" de una matrícula "1234-BCD" (el
//  guion es opcional) y muéstralos con $m['numeros'] y $m['letras'].
// =============================================================================================
titulo('Problema 1');
$matricula = "1234-BCD";
// completa aquí tu solución

// =============================================================================================
// PROBLEMA 2. Línea de un fichero de log
//  Extrae con grupos con nombre la fecha, la hora, el nivel (INFO, WARNING o ERROR) y el mensaje
//  de cada línea, y construye un array de arrays solo con los grupos con nombre.
// =============================================================================================
titulo('Problema 2');
$lineas = [
    "2026-10-16 09:15:02 [INFO] Usuario ana conectado",
    "2026-10-16 09:20:45 [ERROR] Fallo al guardar el pedido 37",
];
// completa aquí tu solución

// =============================================================================================
// PROBLEMA 3. Teléfono con extensión opcional
//  Un teléfono de empresa puede llevar extensión: "986123456 ext. 204". Captura "numero" y
//  "extension"; si no hay extensión, muestra "(sin extensión)". Usa PREG_UNMATCHED_AS_NULL y ??.
// =============================================================================================
titulo('Problema 3');
$telefonos = ["986123456 ext. 204", "986654321"];
// completa aquí tu solución

// =============================================================================================
// PROBLEMA 4. Comillas que cierran igual que abren
//  Un texto puede ir entre comillas simples o dobles, pero debe cerrar con el mismo tipo con el
//  que abre. Usa un grupo con nombre para la comilla y \k<nombre> para el cierre, y captura el
//  contenido en otro grupo con nombre.
// =============================================================================================
titulo('Problema 4');
$textos = ['"hola"', "'adiós'", "\"mal'", "'mal\""];
// completa aquí tu solución

// =============================================================================================
// PROBLEMA 5. Dos formatos de hora
//  Las horas pueden venir como "14:30" o como "2:30 PM". Con (?J) y grupos con el mismo nombre
//  en cada alternativa, obtén siempre $m['hora'] y $m['minutos'], y convierte la segunda al
//  formato de 24 horas.
// =============================================================================================
titulo('Problema 5');
$horas = ["14:30", "2:30 PM", "9:05 AM"];
// completa aquí tu solución

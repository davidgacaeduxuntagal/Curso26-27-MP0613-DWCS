<?php
/*
 * e06-JSON_problema.php
 *  Problemas de: guardar y cargar ficheros JSON, modificar datos (CRUD), validar la estructura
 *                al cargar, JSON Lines
 *  (repasa los ejemplos de ../e06-JSON.php)
 *
 *  Resuelve cada problema donde se indica y ejecútalo en modo depuración para comprobarlo.
 *  Los ficheros se crean en ../datos (ábrelos en VSC para ver cómo cambian).
 */
require_once __DIR__ . '/../utilidades.php';

// =============================================================================================
// PROBLEMA 1. Configuración con valores por defecto
//  Escribe una función cargarConfig(string $fichero, array $porDefecto): array que lea un
//  fichero JSON de configuración y lo combine con $porDefecto (lo que esté en el fichero tiene
//  prioridad). Si el fichero no existe, o no es JSON válido, devuelve $porDefecto. Pruébala con
//  los tres casos.
// =============================================================================================
titulo('Problema 1');
$porDefecto = ['idioma' => 'gl', 'iva' => 0.10, 'mesas' => 12, 'terraza' => false];
file_put_contents(rutaDatos('p06_config.json'), '{"mesas": 15, "terraza": true}');
file_put_contents(rutaDatos('p06_config_rota.json'), '{"mesas": 15, terraza: true}');
// completa aquí tu solución

// =============================================================================================
// PROBLEMA 2. Agenda de contactos (CRUD)
//  Con el fichero p06_agenda.json (una lista de contactos con id, nombre y telefono), escribe
//  las funciones agendaAnadir(string $nombre, string $telefono): int (devuelve el nuevo id),
//  agendaCambiarTelefono(int $id, string $telefono): bool y agendaBorrar(int $id): bool.
//  Cada función carga el fichero, hace el cambio y lo guarda (con JSON_PRETTY_PRINT).
// =============================================================================================
titulo('Problema 2');
define('AGENDA', rutaDatos('p06_agenda.json'));
file_put_contents(AGENDA, '[{"id": 1, "nombre": "Proveedor de pan", "telefono": "981000111"}]');
// completa aquí tu solución

// =============================================================================================
// PROBLEMA 3. Revisar un fichero editado a mano
//  p06_horario.json debe tener un objeto con los 7 días de la semana (lunes ... domingo) y,
//  para cada uno, "cerrado" o un horario "HH:MM-HH:MM". Escribe una función que devuelva la
//  lista de errores encontrados (días que faltan, días que sobran y valores incorrectos).
// =============================================================================================
titulo('Problema 3');
file_put_contents(rutaDatos('p06_horario.json'), <<<JSON
{
    "lunes": "cerrado",
    "martes": "09:00-23:00",
    "miercoles": "09:00-23:00",
    "jueves": "9-23",
    "viernes": "09:00-02:00",
    "sabado": "10:00-02:00",
    "festivos": "cerrado"
}
JSON);
$dias = ['lunes', 'martes', 'miercoles', 'jueves', 'viernes', 'sabado', 'domingo'];
// completa aquí tu solución

// =============================================================================================
// PROBLEMA 4. Registro de accesos en JSON Lines
//  Escribe registrarAcceso(string $usuario, bool $correcto): void, que añada al final de
//  p06_accesos.jsonl una línea JSON con la fecha (DATE_ATOM), el usuario y si el acceso fue
//  correcto. Registra los accesos de $intentos y después lee el fichero para contar los
//  intentos fallidos de cada usuario.
// =============================================================================================
titulo('Problema 4');
$intentos = [['ana', true], ['brais', false], ['brais', false], ['ana', false], ['brais', true]];
if (is_file(rutaDatos('p06_accesos.jsonl'))) {
    unlink(rutaDatos('p06_accesos.jsonl'));
}
// completa aquí tu solución

// =============================================================================================
// PROBLEMA 5. De CSV a JSON
//  Convierte el fichero p06_socios.csv (con cabecera) en p06_socios.json: una lista de objetos
//  con las claves de la cabecera, donde "cuota" sea un número y "activo" un booleano ("si" ->
//  true). ¿Cómo harías para que el JSON resultante siga siendo una lista aunque borres algún
//  socio después?
// =============================================================================================
titulo('Problema 5');
file_put_contents(rutaDatos('p06_socios.csv'), "numero;nombre;cuota;activo\n1;Ana Pérez;30;si\n2;Brais Otero;25.5;no\n3;Carmen Núñez;30;si\n");
// completa aquí tu solución

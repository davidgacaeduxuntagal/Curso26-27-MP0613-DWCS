<?php
/*
 * e06-JSON_problema_solucion.php
 *  (FICHERO CON LAS SOLUCIONES)
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
// ---- SOLUCIÓN ----
function cargarConfig(string $fichero, array $porDefecto): array
{
    if (!is_file($fichero)) {
        return $porDefecto;
    }
    try {
        $config = json_decode(file_get_contents($fichero), true, 512, JSON_THROW_ON_ERROR);
    } catch (JsonException) {
        return $porDefecto;
    }
    return is_array($config) ? array_merge($porDefecto, $config) : $porDefecto;
}
ver('fichero correcto', cargarConfig(rutaDatos('p06_config.json'), $porDefecto));        // mesas 15, terraza true
ver('fichero roto', cargarConfig(rutaDatos('p06_config_rota.json'), $porDefecto));       // valores por defecto
ver('no existe', cargarConfig(rutaDatos('p06_no_existe.json'), $porDefecto));           // valores por defecto

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
// ---- SOLUCIÓN ----
function agendaCargar(): array
{
    return is_file(AGENDA) ? json_decode(file_get_contents(AGENDA), true, 512, JSON_THROW_ON_ERROR) : [];
}

function agendaGuardar(array $agenda): void
{
    $json = json_encode(array_values($agenda), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR);
    file_put_contents(AGENDA, $json, LOCK_EX);
}

function agendaAnadir(string $nombre, string $telefono): int
{
    $agenda = agendaCargar();
    $id = $agenda === [] ? 1 : max(array_column($agenda, 'id')) + 1;
    $agenda[] = ['id' => $id, 'nombre' => $nombre, 'telefono' => $telefono];
    agendaGuardar($agenda);
    return $id;
}

function agendaCambiarTelefono(int $id, string $telefono): bool
{
    $agenda = agendaCargar();
    foreach ($agenda as $i => $contacto) {
        if ($contacto['id'] === $id) {
            $agenda[$i]['telefono'] = $telefono;
            agendaGuardar($agenda);
            return true;
        }
    }
    return false;
}

function agendaBorrar(int $id): bool
{
    $agenda = agendaCargar();
    $nueva = array_filter($agenda, fn($c) => $c['id'] !== $id);
    if (count($nueva) === count($agenda)) {
        return false;
    }
    agendaGuardar($nueva);           // agendaGuardar hace array_values: sigue siendo una lista
    return true;
}

ver('nuevo id', agendaAnadir('Lonja de Ribeira', '981222333'));    // 2
ver('nuevo id', agendaAnadir('Técnico cafetera', '600111222'));    // 3
ver('cambiar 1', agendaCambiarTelefono(1, '981000999'));           // true
ver('cambiar 9', agendaCambiarTelefono(9, '000'));                 // false
ver('borrar 2', agendaBorrar(2));                                   // true
ver('agenda', agendaCargar());       // ids 1 (teléfono 981000999) y 3

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
// ---- SOLUCIÓN ----
function erroresHorario(string $fichero, array $dias): array
{
    try {
        $horario = json_decode(file_get_contents($fichero), true, 512, JSON_THROW_ON_ERROR);
    } catch (JsonException $e) {
        return ['JSON no válido: ' . $e->getMessage()];
    }
    if (!is_array($horario) || array_is_list($horario)) {
        return ['Se esperaba un objeto con los días'];
    }
    $errores = [];
    foreach (array_diff($dias, array_keys($horario)) as $dia) {
        $errores[] = "Falta el día $dia";
    }
    foreach (array_diff(array_keys($horario), $dias) as $clave) {
        $errores[] = "Sobra la clave $clave";
    }
    foreach (array_intersect_key($horario, array_flip($dias)) as $dia => $valor) {
        if ($valor !== 'cerrado' && !(is_string($valor) && preg_match('/^\d{2}:\d{2}-\d{2}:\d{2}$/', $valor))) {
            $errores[] = "Valor incorrecto en $dia: " . json_encode($valor);
        }
    }
    return $errores;
}
ver('errores', erroresHorario(rutaDatos('p06_horario.json'), $dias));
// ['Falta el día domingo', 'Sobra la clave festivos', 'Valor incorrecto en jueves: "9-23"']

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
// ---- SOLUCIÓN ----
function registrarAcceso(string $usuario, bool $correcto): void
{
    $linea = json_encode(['fecha' => date(DATE_ATOM), 'usuario' => $usuario, 'correcto' => $correcto]);
    file_put_contents(rutaDatos('p06_accesos.jsonl'), $linea . "\n", FILE_APPEND | LOCK_EX);
}
foreach ($intentos as [$usuario, $correcto]) {
    registrarAcceso($usuario, $correcto);
}
$fallidos = [];
foreach (file(rutaDatos('p06_accesos.jsonl'), FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $linea) {
    $acceso = json_decode($linea, true);
    if (!$acceso['correcto']) {
        $fallidos[$acceso['usuario']] = ($fallidos[$acceso['usuario']] ?? 0) + 1;
    }
}
ver('líneas', count(file(rutaDatos('p06_accesos.jsonl'))));   // 5
ver('fallidos', $fallidos);                                     // ['brais' => 2, 'ana' => 1]

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
// ---- SOLUCIÓN ----
$fp = fopen(rutaDatos('p06_socios.csv'), 'r');
$cabecera = fgetcsv($fp, null, ';');
$socios = [];
while (($fila = fgetcsv($fp, null, ';')) !== false) {
    $socio = array_combine($cabecera, $fila);
    $socio['numero'] = (int) $socio['numero'];
    $socio['cuota'] = (float) $socio['cuota'];
    $socio['activo'] = $socio['activo'] === 'si';
    $socios[] = $socio;
}
fclose($fp);
file_put_contents(rutaDatos('p06_socios.json'), json_encode($socios, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
echo file_get_contents(rutaDatos('p06_socios.json')), nl();
// Para que siga siendo una lista después de borrar: array_values (o array_splice) antes de
// codificar. Por ejemplo, borrar el socio 2:
$sinBrais = array_values(array_filter($socios, fn($s) => $s['numero'] !== 2));
ver('sigue siendo una lista', array_is_list($sinBrais));    // true

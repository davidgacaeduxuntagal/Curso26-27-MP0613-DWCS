<?php
/*
 * funciones.php — Torneo de futbolín del Bar Baio
 * PASO 3: recepción y validación de los datos. La persistencia queda pendiente (TODO paso 4).
 */

const FICHERO_EQUIPOS = __DIR__ . '/datos/equipos.json';
const MAX_EQUIPOS = 16;
const CATEGORIAS = ['mixta' => 'Mixta', 'juvenil' => 'Juvenil (menores de 18)', 'senior' => 'Sénior'];

// ---------------------------------------------------------------------------------------------
// Recepción de datos
// ---------------------------------------------------------------------------------------------

/** Devuelve los 6 campos del formulario, sin espacios al principio ni al final. */
function recogerDatos(): array
{
    $datos = [];
    foreach (['equipo', 'jugador1', 'jugador2', 'email', 'telefono', 'categoria'] as $campo) {
        $valor = $_POST[$campo] ?? '';
        $datos[$campo] = is_string($valor) ? trim($valor) : '';   // si envían un array, lo ignoramos
    }
    return $datos;
}

// ---------------------------------------------------------------------------------------------
// Validación: devuelve un array campo => mensaje de error (vacío si todo es correcto)
// ---------------------------------------------------------------------------------------------
function validarInscripcion(array $datos, array $equipos): array
{
    $errores = [];

    // Nombre del equipo
    if ($datos['equipo'] === '') {
        $errores['equipo'] = 'Escribe el nombre del equipo';
    } elseif (mb_strlen($datos['equipo']) < 3 || mb_strlen($datos['equipo']) > 30) {
        $errores['equipo'] = 'Entre 3 y 30 caracteres';
    } elseif (!preg_match('/^[\p{L}\d ]+$/u', $datos['equipo'])) {
        $errores['equipo'] = 'Solo letras, números y espacios';
    } else {
        foreach ($equipos as $equipo) {
            if (mb_strtolower($equipo['nombre']) === mb_strtolower($datos['equipo'])) {
                $errores['equipo'] = 'Ya hay un equipo con ese nombre';
            }
        }
    }

    // Jugador 1
    if ($datos['jugador1'] === '') {
        $errores['jugador1'] = 'Escribe el nombre del jugador';
    } elseif (!preg_match("/^[\\p{L} '-]{2,40}$/u", $datos['jugador1'])) {
        $errores['jugador1'] = 'De 2 a 40 letras (se admiten espacios, apóstrofos y guiones)';
    }

    // Jugador 2
    if ($datos['jugador2'] === '') {
        $errores['jugador2'] = 'Escribe el nombre del jugador';
    } elseif (!preg_match("/^[\\p{L} '-]{2,40}$/u", $datos['jugador2'])) {
        $errores['jugador2'] = 'De 2 a 40 letras (se admiten espacios, apóstrofos y guiones)';
    } elseif (mb_strtolower($datos['jugador1']) === mb_strtolower($datos['jugador2'])) {
        $errores['jugador2'] = 'Los dos jugadores deben ser distintos';
    }

    // Correo
    if ($datos['email'] === '') {
        $errores['email'] = 'Escribe un correo electrónico';
    } elseif (filter_var($datos['email'], FILTER_VALIDATE_EMAIL) === false) {
        $errores['email'] = 'El correo no es válido';
    }

    // Teléfono (opcional)
    if ($datos['telefono'] !== '' && !preg_match('/^[6789]\d{8}$/', str_replace(' ', '', $datos['telefono']))) {
        $errores['telefono'] = '9 cifras, empezando por 6, 7, 8 o 9';
    }

    // Categoría
    if (!array_key_exists($datos['categoria'], CATEGORIAS)) {
        $errores['categoria'] = 'Elige una categoría';
    }

    return $errores;
}

// ---------------------------------------------------------------------------------------------
// Procesamiento
// ---------------------------------------------------------------------------------------------

/** Convierte los datos del formulario (ya validados) en un equipo listo para guardar. */
function crearEquipo(array $datos): array
{
    // TODO (paso 4): devolver el equipo: nombre, jugadores (lista de 2), email, teléfono sin
    //                espacios, categoría y fecha de inscripción (date(DATE_ATOM))
    return [];
}

// ---------------------------------------------------------------------------------------------
// Persistencia
// ---------------------------------------------------------------------------------------------

/** Lee la lista de equipos del fichero JSON (lista vacía si aún no existe o está mal). */
function cargarEquipos(): array
{
    // TODO (paso 4): si existe el fichero, leerlo con file_get_contents y json_decode(..., true)
    return [];
}

/** Guarda la lista de equipos. Devuelve false si no se ha podido escribir. */
function guardarEquipos(array $equipos): bool
{
    // TODO (paso 4): crear la carpeta datos si no existe; json_encode y file_put_contents con LOCK_EX
    return true;
}

// ---------------------------------------------------------------------------------------------
// Salida
// ---------------------------------------------------------------------------------------------

/** Escapa un texto para mostrarlo en HTML (evita XSS). */
function e(string $texto): string
{
    return htmlspecialchars($texto, ENT_QUOTES, 'UTF-8');
}

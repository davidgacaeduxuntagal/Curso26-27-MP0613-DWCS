<?php
/*
 * funciones.php — Torneo de futbolín del Bar Baio
 * PASO 2: descomposición en funciones. Solo están las constantes y las "firmas" de las
 *         funciones, con un TODO que explica qué tienen que hacer y un valor provisional para
 *         que la página se pueda ejecutar.
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
    // TODO (paso 3): leer equipo, jugador1, jugador2, email, telefono y categoria de $_POST
    //                (con ?? '' por si falta alguno) y aplicarles trim()
    return [];
}

// ---------------------------------------------------------------------------------------------
// Validación: devuelve un array campo => mensaje de error (vacío si todo es correcto)
// ---------------------------------------------------------------------------------------------
function validarInscripcion(array $datos, array $equipos): array
{
    // TODO (paso 3): equipo: obligatorio, de 3 a 30 caracteres (mb_strlen), letras, números y
    //                espacios (preg_match) y no repetido en $equipos
    // TODO (paso 3): jugador1 y jugador2: obligatorios, de 2 a 40 letras (preg_match), distintos
    // TODO (paso 3): email: obligatorio y válido (filter_var con FILTER_VALIDATE_EMAIL)
    // TODO (paso 3): telefono: opcional; si se escribe, 9 cifras empezando por 6, 7, 8 o 9
    // TODO (paso 3): categoria: una de las claves de CATEGORIAS
    return [];
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
    // TODO (paso 3): htmlspecialchars con ENT_QUOTES y UTF-8
    return $texto;
}

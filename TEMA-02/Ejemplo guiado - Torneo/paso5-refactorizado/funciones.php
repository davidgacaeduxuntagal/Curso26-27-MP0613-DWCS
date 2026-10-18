<?php
/*
 * funciones.php — Torneo de futbolín del Bar Baio
 * PASO 5: refactorización. Mismo comportamiento que el paso 4, pero:
 *   - los límites y las expresiones regulares están en constantes (sin "números mágicos"),
 *   - cada campo se valida con su propia función pequeña, y la de los jugadores se reutiliza,
 *   - el teléfono se normaliza en un solo sitio,
 *   - el HTML de los campos de texto lo genera una función (en el paso 4 estaba repetido 5 veces).
 */

const FICHERO_EQUIPOS = __DIR__ . '/datos/equipos.json';
const MAX_EQUIPOS = 16;
const CATEGORIAS = ['mixta' => 'Mixta', 'juvenil' => 'Juvenil (menores de 18)', 'senior' => 'Sénior'];
const EQUIPO_MIN = 3;
const EQUIPO_MAX = 30;
const PATRON_EQUIPO = '/^[\p{L}\d ]+$/u';
const PATRON_JUGADOR = "/^[\\p{L} '-]{2,40}$/u";
const PATRON_TELEFONO = '/^[6789]\d{8}$/';

// Campos de texto del formulario: nombre => [etiqueta, tipo de input, longitud máxima, ¿opcional?]
const CAMPOS_TEXTO = [
    'equipo'   => ['Nombre del equipo', 'text', EQUIPO_MAX, false],
    'jugador1' => ['Jugador 1', 'text', 40, false],
    'jugador2' => ['Jugador 2', 'text', 40, false],
    'email'    => ['Correo electrónico', 'email', 100, false],
    'telefono' => ['Teléfono', 'tel', 15, true],
];

// ---------------------------------------------------------------------------------------------
// Recepción de datos
// ---------------------------------------------------------------------------------------------

/** Devuelve todos los campos del formulario, sin espacios al principio ni al final. */
function recogerDatos(): array
{
    $datos = [];
    foreach ([...array_keys(CAMPOS_TEXTO), 'categoria'] as $campo) {
        $valor = $_POST[$campo] ?? '';
        $datos[$campo] = is_string($valor) ? trim($valor) : '';   // si envían un array, lo ignoramos
    }
    return $datos;
}

/** Datos vacíos (para el formulario de la primera visita). */
function datosVacios(): array
{
    return array_fill_keys([...array_keys(CAMPOS_TEXTO), 'categoria'], '');
}

// ---------------------------------------------------------------------------------------------
// Validación: cada función devuelve el mensaje de error, o null si el valor es correcto
// ---------------------------------------------------------------------------------------------

function mismoTexto(string $a, string $b): bool
{
    return mb_strtolower($a) === mb_strtolower($b);
}

function existeEquipo(string $nombre, array $equipos): bool
{
    foreach ($equipos as $equipo) {
        if (mismoTexto($equipo['nombre'], $nombre)) {
            return true;
        }
    }
    return false;
}

function validarEquipo(string $nombre, array $equipos): ?string
{
    return match (true) {
        $nombre === ''                                                => 'Escribe el nombre del equipo',
        mb_strlen($nombre) < EQUIPO_MIN || mb_strlen($nombre) > EQUIPO_MAX => 'Entre ' . EQUIPO_MIN . ' y ' . EQUIPO_MAX . ' caracteres',
        !preg_match(PATRON_EQUIPO, $nombre)                           => 'Solo letras, números y espacios',
        existeEquipo($nombre, $equipos)                               => 'Ya hay un equipo con ese nombre',
        default                                                       => null,
    };
}

function validarJugador(string $nombre): ?string
{
    return match (true) {
        $nombre === ''                       => 'Escribe el nombre del jugador',
        !preg_match(PATRON_JUGADOR, $nombre) => 'De 2 a 40 letras (se admiten espacios, apóstrofos y guiones)',
        default                              => null,
    };
}

function validarEmail(string $email): ?string
{
    return match (true) {
        $email === ''                                        => 'Escribe un correo electrónico',
        filter_var($email, FILTER_VALIDATE_EMAIL) === false  => 'El correo no es válido',
        default                                              => null,
    };
}

function normalizarTelefono(string $telefono): string
{
    return str_replace(' ', '', $telefono);
}

function validarTelefono(string $telefono): ?string
{
    if ($telefono === '') {
        return null;                                             // es opcional
    }
    return preg_match(PATRON_TELEFONO, normalizarTelefono($telefono)) ? null : '9 cifras, empezando por 6, 7, 8 o 9';
}

function validarCategoria(string $categoria): ?string
{
    return array_key_exists($categoria, CATEGORIAS) ? null : 'Elige una categoría';
}

/** Valida todo el formulario: devuelve un array campo => mensaje (vacío si es correcto). */
function validarInscripcion(array $datos, array $equipos): array
{
    $errores = [
        'equipo'    => validarEquipo($datos['equipo'], $equipos),
        'jugador1'  => validarJugador($datos['jugador1']),
        'jugador2'  => validarJugador($datos['jugador2']),
        'email'     => validarEmail($datos['email']),
        'telefono'  => validarTelefono($datos['telefono']),
        'categoria' => validarCategoria($datos['categoria']),
    ];
    if ($errores['jugador1'] === null && $errores['jugador2'] === null && mismoTexto($datos['jugador1'], $datos['jugador2'])) {
        $errores['jugador2'] = 'Los dos jugadores deben ser distintos';
    }
    if (count($equipos) >= MAX_EQUIPOS) {
        $errores['general'] = 'El torneo está completo';
    }
    return array_filter($errores, fn($error) => $error !== null);   // solo los campos con error
}

// ---------------------------------------------------------------------------------------------
// Procesamiento
// ---------------------------------------------------------------------------------------------

/** Convierte los datos del formulario (ya validados) en un equipo listo para guardar. */
function crearEquipo(array $datos): array
{
    return [
        'nombre'    => $datos['equipo'],
        'jugadores' => [$datos['jugador1'], $datos['jugador2']],
        'email'     => $datos['email'],
        'telefono'  => normalizarTelefono($datos['telefono']),
        'categoria' => $datos['categoria'],
        'fecha'     => date(DATE_ATOM),
    ];
}

// ---------------------------------------------------------------------------------------------
// Persistencia
// ---------------------------------------------------------------------------------------------

/** Lee la lista de equipos del fichero JSON (lista vacía si aún no existe o está mal). */
function cargarEquipos(): array
{
    if (!is_file(FICHERO_EQUIPOS)) {
        return [];
    }
    $equipos = json_decode(file_get_contents(FICHERO_EQUIPOS), true);
    return is_array($equipos) ? $equipos : [];
}

/** Guarda la lista de equipos. Devuelve false si no se ha podido escribir. */
function guardarEquipos(array $equipos): bool
{
    if (!is_dir(dirname(FICHERO_EQUIPOS))) {
        mkdir(dirname(FICHERO_EQUIPOS), 0777, true);
    }
    $json = json_encode($equipos, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    return file_put_contents(FICHERO_EQUIPOS, $json, LOCK_EX) !== false;
}

// ---------------------------------------------------------------------------------------------
// Salida
// ---------------------------------------------------------------------------------------------

/** Escapa un texto para mostrarlo en HTML (evita XSS). */
function e(string $texto): string
{
    return htmlspecialchars($texto, ENT_QUOTES, 'UTF-8');
}

/** Mensaje de error de un campo (o nada si no tiene error). */
function htmlError(string $campo, array $errores): string
{
    return isset($errores[$campo]) ? '<p class="error">' . e($errores[$campo]) . '</p>' : '';
}

/** HTML completo de un campo de texto del formulario, con su etiqueta, su valor y su error. */
function htmlCampoTexto(string $campo, array $datos, array $errores): string
{
    [$etiqueta, $tipo, $maximo, $opcional] = CAMPOS_TEXTO[$campo];
    $clase = isset($errores[$campo]) ? 'campo con-error' : 'campo';
    $textoOpcional = $opcional ? ' <span class="opcional">(opcional)</span>' : '';
    return '<div class="' . $clase . '">'
        . '<label for="' . $campo . '">' . e($etiqueta) . $textoOpcional . '</label>'
        . '<input type="' . $tipo . '" id="' . $campo . '" name="' . $campo . '" maxlength="' . $maximo . '" value="' . e($datos[$campo]) . '">'
        . htmlError($campo, $errores)
        . "</div>\n";
}

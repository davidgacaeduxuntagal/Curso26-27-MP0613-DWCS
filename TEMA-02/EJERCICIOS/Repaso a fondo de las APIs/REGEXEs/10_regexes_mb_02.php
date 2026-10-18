<?php
/*
 * 10_regexes_mb_02.php
 *  REGEXES - API MULTIBYTE (2): búsqueda paso a paso con mb_ereg_search y opciones
 *  Funciones: mb_regex_set_options, mb_ereg_search_init, mb_ereg_search, mb_ereg_search_pos,
 *             mb_ereg_search_regs, mb_ereg_search_getregs, mb_ereg_search_getpos, mb_ereg_search_setpos
 *
 *  Las funciones mb_ereg_search_* funcionan como un "cursor": primero se indica el texto y el
 *  patrón (mb_ereg_search_init) y después se van pidiendo coincidencias una a una; cada búsqueda
 *  continúa donde terminó la anterior. Es parecido al puntero interno de los arrays (current /
 *  next...). Hoy lo habitual es usar preg_match_all, pero así se ve muy bien el proceso.
 *
 *  Ejecuta en modo depuración paso a paso (F10) y observa cómo avanza la POSICIÓN
 *  (mb_ereg_search_getpos) después de cada búsqueda.
 *  NOTA: las posiciones de estas funciones se cuentan en BYTES, no en caracteres.
 */
require_once __DIR__ . '/utilidades.php';

mb_regex_encoding("UTF-8");

// ---------------------------------------------------------------------------------------------
// mb_regex_set_options — consulta o establece las opciones por defecto de las funciones mb_ereg
// ENLACE: https://www.php.net/manual/es/function.mb-regex-set-options.php
// ENUNCIADO EJEMPLO: ver las opciones actuales y activar "i" (sin distinguir mayúsculas) para
//   todas las búsquedas siguientes; después las restauramos.
// NOTA: opciones: i (ignorar mayúsculas), x (espacios y comentarios), m (el punto incluye \n),
//   s (^ y $ solo al principio y final)... Devuelve las opciones ANTERIORES.
// ---------------------------------------------------------------------------------------------
titulo('mb_regex_set_options');
$anteriores    = mb_regex_set_options();          // consulta, p.ej. "pr"
mb_regex_set_options("i");
ver('opciones anteriores', $anteriores);
ver('con "i": mb_ereg("PEÑA", "peña")', mb_ereg("PEÑA", "peña"));   // true
mb_regex_set_options($anteriores);                 // restauramos
ver('restauradas: mb_ereg("PEÑA", "peña")', mb_ereg("PEÑA", "peña"));   // false

// ---------------------------------------------------------------------------------------------
// mb_ereg_search_init — indica el texto (y opcionalmente el patrón) sobre el que buscar
// mb_ereg_search      — ¿hay otra coincidencia a partir de la posición actual? (true / false)
// mb_ereg_search_getpos — posición (en bytes) desde la que continuará la búsqueda
// ENLACE: https://www.php.net/manual/es/function.mb-ereg-search-init.php
// ENLACE: https://www.php.net/manual/es/function.mb-ereg-search.php
// ENLACE: https://www.php.net/manual/es/function.mb-ereg-search-getpos.php
// ENUNCIADO EJEMPLO: contar cuántas palabras de un texto en español empiezan por "ñ" o por
//   vocal con tilde, viendo cómo avanza la posición en cada búsqueda.
// ---------------------------------------------------------------------------------------------
titulo('mb_ereg_search_init / mb_ereg_search / mb_ereg_search_getpos');
$texto         = "Ñandú, ñu y águila: ánimo en África";
mb_ereg_search_init($texto, "\b[ñÑáéíóúÁÉÍÓÚ]\w*");
$cuantas       = 0;
$posiciones    = [];
while (mb_ereg_search()) {
    $cuantas++;
    $posiciones[] = mb_ereg_search_getpos();         // observa cómo crece en cada vuelta
}
ver('palabras encontradas', $cuantas);               // 5: Ñandú, ñu, águila, ánimo, África
ver('posición tras cada búsqueda (bytes)', $posiciones);

// ---------------------------------------------------------------------------------------------
// mb_ereg_search_pos — busca la siguiente coincidencia y devuelve [posición, longitud] (en bytes)
// ENLACE: https://www.php.net/manual/es/function.mb-ereg-search-pos.php
// ENUNCIADO EJEMPLO: localizar todos los números de un texto para resaltarlos, guardando dónde
//   empieza cada uno y cuánto mide.
// ---------------------------------------------------------------------------------------------
titulo('mb_ereg_search_pos');
$ticket        = "Café 2 x 1,50 = 3,00 €";
mb_ereg_search_init($ticket, "\d+(,\d+)?");
$encontrados   = [];
while (($pos = mb_ereg_search_pos()) !== false) {
    [$inicio, $longitud] = $pos;
    $encontrados[] = substr($ticket, $inicio, $longitud) . " (byte $inicio, $longitud bytes)";
}
ver('números', $encontrados);       // 2, 1,50, 3,00

// ---------------------------------------------------------------------------------------------
// mb_ereg_search_regs — busca la siguiente coincidencia y devuelve el array de coincidencias
// mb_ereg_search_getregs — devuelve el array de la ÚLTIMA coincidencia (sin buscar otra)
// ENLACE: https://www.php.net/manual/es/function.mb-ereg-search-regs.php
// ENLACE: https://www.php.net/manual/es/function.mb-ereg-search-getregs.php
// ENUNCIADO EJEMPLO: recorrer los pares "clave=valor" de una línea de configuración y construir
//   un array asociativo.
// ---------------------------------------------------------------------------------------------
titulo('mb_ereg_search_regs / mb_ereg_search_getregs');
$config        = "idioma=gl; tema=escuro; cidade=Ourense";
mb_ereg_search_init($config, "(\w+)=(\w+)");
$opciones      = [];
while (($m = mb_ereg_search_regs()) !== false) {
    $opciones[$m[1]] = $m[2];
}
ver('opciones', $opciones);             // ['idioma' => 'gl', 'tema' => 'escuro', 'cidade' => 'Ourense']

mb_ereg_search_init($config, "tema=(\w+)");
mb_ereg_search();                       // solo busca (true / false)
$ultima        = mb_ereg_search_getregs();   // recuperamos los grupos de esa búsqueda
ver('tema (con getregs)', $ultima[1]);  // "escuro"

// ---------------------------------------------------------------------------------------------
// mb_ereg_search_setpos — cambia la posición (en bytes) desde la que continuará la búsqueda
// ENLACE: https://www.php.net/manual/es/function.mb-ereg-search-setpos.php
// ENUNCIADO EJEMPLO: en un mensaje con cabecera "ASUNTO: ...\n" y cuerpo, buscar los emails solo
//   en el cuerpo, saltando la cabecera.
// ---------------------------------------------------------------------------------------------
titulo('mb_ereg_search_setpos');
$mensaje       = "ASUNTO: copia a jefe@empresa.es\nEscribe a ana@x.gal o a brais@x.gal";
mb_ereg_search_init($mensaje, "[\w.]+@[\w.]+");
mb_ereg_search_setpos(strpos($mensaje, "\n") + 1);     // saltamos la primera línea
$emails        = [];
while (($m = mb_ereg_search_regs()) !== false) {
    $emails[] = $m[0];
}
ver('emails del cuerpo', $emails);      // ["ana@x.gal", "brais@x.gal"]

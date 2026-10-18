# Actividades: expresiones regulares en PHP

Introducción paso a paso a las expresiones regulares y ejemplos de la API de PHP para trabajar con ellas,
siguiendo el *php Cheatsheet 3 (RegExes - Strings - Arrays)*: funciones `preg_*` (PCRE) y funciones `mb_ereg*`
(mbstring).

Probado con **PHP 8.2** (XAMPP en Windows, PCRE2 10.40).

## Cómo trabajar

1. Empieza por los ficheros **BÁSICAS** (01–06): introducen la sintaxis de las expresiones regulares usando
   solo `preg_match`. Después, los de la **API** (07–10).
2. Ejecuta cada fichero en **modo depuración** (F5 en VS Code con Xdebug) y avanza con **F10**. Despliega en
   el panel **VARIABLES** los arrays de coincidencias (`$m`): su estructura es la clave para entender los grupos
   de captura.
3. Comprueba y depura tus patrones en <https://regex101.com> (elige el *flavor* PCRE2).
4. Resuelve los problemas de `problemas/NN_regexes_*_problema.php` y compara con
   `problemas/NN_regexes_*_problema_solucion.php`.

## Índice

### BÁSICAS: sintaxis paso a paso

| Fichero | Contenido |
|---|---|
| `01_regexes_basicas_01.php` | Delimitadores y `preg_match`, literales, metacaracteres y escape, el punto, clases `[ ]`, rangos, negación, `\d \w \s`, anclas `^ $`, modificadores `i u x` |
| `02_regexes_basicas_02.php` | Cuantificadores `* + ? {n} {n,} {n,m}`, codiciosos y perezosos, alternancia `\|`, límites de palabra `\b \B`, modificador `m` |
| `03_regexes_basicas_03.php` | Validación del formato de datos habituales: entero, decimal, código postal, teléfono, DNI/NIE, email, fecha, hora, matrícula, URL (y comparación con `filter_var` / `checkdate`) |
| `04_regexes_basicas_04.php` | **Grupos de captura**: el array de coincidencias, grupos anidados y opcionales, `PREG_UNMATCHED_AS_NULL`, grupos no capturadores `(?: )`, referencias hacia atrás `\1` |
| `05_regexes_basicas_05.php` | **Grupos con nombre** `(?<nombre> )` y `(?P<nombre> )`, referencias `\k<nombre>`, grupos con nombre opcionales, nombres repetidos con `(?J)` |
| `06_regexes_basicas_06.php` | Aserciones *lookahead* y *lookbehind*, contraseñas fuertes, propiedades Unicode `\p{L}` `\p{Lu}` con `/u` |

### API

| Fichero | Funciones |
|---|---|
| `07_regexes_preg_01.php` | preg_match, preg_match_all, preg_split, preg_grep, preg_quote, preg_last_error, preg_last_error_msg |
| `08_regexes_preg_02.php` | preg_replace, preg_filter, preg_replace_callback, preg_replace_callback_array |
| `09_regexes_mb_01.php` | mb_regex_encoding, mb_ereg, mb_eregi, mb_ereg_match, mb_ereg_replace, mb_eregi_replace, mb_ereg_replace_callback, mb_split |
| `10_regexes_mb_02.php` | mb_regex_set_options, mb_ereg_search_init, mb_ereg_search, mb_ereg_search_pos, mb_ereg_search_regs, mb_ereg_search_getregs, mb_ereg_search_getpos, mb_ereg_search_setpos |

## Errores habituales que se trabajan

- Validar sin anclar el patrón con `^` y `$`.
- Olvidar el modificador `u` con textos que tienen tildes o eñes.
- Escribir patrones o reemplazos (`$1`) entre comillas **dobles**: PHP interpreta antes las `\` y los `$`.
- Meter texto del usuario en un patrón sin `preg_quote`.
- Confundir `0` (no coincide) con `false` (error) en el resultado de `preg_match`.
- Escribir `?>` dentro de un comentario `//`: PHP lo toma como el cierre de la etiqueta PHP (pasa con
  patrones perezosos como `.+?>`).
- Cuantificadores anidados como `(\d+)+`, que provocan un retroceso catastrófico.

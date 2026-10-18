# Actividades: API de STRINGS de PHP

Ejemplos y problemas para familiarizarse con las funciones de manejo de strings de la biblioteca estándar de
PHP, siguiendo la clasificación del *php Cheatsheet 3 (RegExes - Strings - Arrays)*: básicas, comparación,
búsqueda, formato, HTML y web, codificación e I18N. En I18N se incluyen también las funciones `mb_` (sección
*MB_STRINGS* del cheatsheet), imprescindibles para textos en español.

Probado con **PHP 8.2** (XAMPP en Windows).

## Cómo trabajar

1. Abre un fichero de ejemplos (`NN_strings_*.php`), pon un punto de ruptura en la primera línea de código y
   ejecútalo en **modo depuración** (F5 en VS Code con Xdebug).
2. Avanza línea a línea con **F10** (*Step Over*). Antes de ejecutar cada línea, piensa qué valor tendrá la
   variable y compruébalo en el panel **VARIABLES**.
3. Cada función tiene, antes de su ejemplo, el **enlace** a la documentación oficial, el **enunciado** del
   ejemplo y notas sobre errores habituales.
4. Después, resuelve los problemas de `problemas/NN_strings_*_problema.php` y compara con
   `problemas/NN_strings_*_problema_solucion.php`.

Los ficheros también se pueden ejecutar en la terminal (`php 01_strings_basicas_01.php`) o en el navegador.
La función `ver()` de [`utilidades.php`](utilidades.php) muestra cada resultado en ambos casos.

## Índice

| Fichero | Funciones |
|---|---|
| `01_strings_basicas_01.php` | strlen, strtolower, strtoupper, ucfirst, lcfirst, ucwords, strrev, str_repeat |
| `02_strings_basicas_02.php` | trim, ltrim, rtrim / chop, str_pad, substr, chunk_split, str_shuffle |
| `03_strings_basicas_03.php` | explode, implode / join, str_split, strtok, parse_str, str_word_count, count_chars |
| `04_strings_basicas_04.php` | ord, chr, quotemeta, echo, print |
| `05_strings_comparacion_01.php` | strcmp, strcasecmp, strncmp, strncasecmp, substr_compare, strcoll |
| `06_strings_comparacion_02.php` | strnatcmp, strnatcasecmp, strspn, strcspn, similar_text |
| `07_strings_busqueda_01.php` | str_contains, str_starts_with, str_ends_with, strpos, stripos, strrpos, strripos |
| `08_strings_busqueda_02.php` | strstr / strchr, stristr, strrchr, strpbrk, substr_count |
| `09_strings_busqueda_03.php` | str_replace, str_ireplace, substr_replace, strtr |
| `10_strings_formato_01.php` | printf, sprintf, vprintf, vsprintf, fprintf, vfprintf, sscanf, str_getcsv |
| `11_strings_html_web_01.php` | htmlspecialchars, htmlspecialchars_decode, htmlentities, html_entity_decode, get_html_translation_table, nl2br, strip_tags |
| `12_strings_html_web_02.php` | addslashes, stripslashes, addcslashes, stripcslashes |
| `13_strings_codificacion_01.php` | bin2hex, hex2bin, str_rot13, convert_uuencode, convert_uudecode, quoted_printable_encode, quoted_printable_decode |
| `14_strings_codificacion_02.php` | md5, sha1, crc32, md5_file, sha1_file, crypt |
| `15_strings_codificacion_03.php` | mb_check_encoding, mb_detect_encoding, mb_convert_encoding, utf8_decode, utf8_encode |
| `16_strings_i18n_01.php` | setlocale, localeconv, number_format, nl_langinfo, money_format (eliminada → NumberFormatter) |
| `17_strings_i18n_02.php` | mb_strlen, mb_substr, mb_strtoupper, mb_strtolower, mb_convert_case, mb_str_split, mb_strwidth, mb_strimwidth |
| `18_strings_i18n_03.php` | mb_internal_encoding, mb_strpos, mb_stripos, mb_strrpos, mb_stristr, mb_strrchr, mb_ord, mb_chr |

## Avisos que verás a propósito

- `15_strings_codificacion_03.php` muestra avisos **Deprecated**: `utf8_encode` y `utf8_decode` están obsoletas
  desde PHP 8.2, y el ejemplo explica qué usar en su lugar.
- `money_format` ya no existe en PHP 8 y `nl_langinfo` no existe en Windows: los ejemplos lo comprueban con
  `function_exists` antes de usarlas.
- Los resultados de `setlocale`, `strcoll` y `localeconv` dependen del sistema operativo (en Windows el locale
  español se llama `Spanish_Spain`).
- `16_strings_i18n_01.php` necesita la extensión **intl** activada en `php.ini` para el ejemplo de
  `NumberFormatter`; si no lo está, el ejemplo lo indica.

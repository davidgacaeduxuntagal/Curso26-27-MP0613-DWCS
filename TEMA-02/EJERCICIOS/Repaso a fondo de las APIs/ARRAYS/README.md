# Actividades: API de ARRAYS de PHP

Ejemplos y problemas para familiarizarse con las funciones de manejo de arrays de la biblioteca estándar de
PHP, siguiendo la clasificación del *php Cheatsheet 3 (RegExes - Strings - Arrays)*: básicas, recorrido,
búsqueda, ordenar, iterar, combinar y operaciones de conjuntos.

Probado con **PHP 8.2** (XAMPP en Windows).

## Cómo trabajar

1. Abre un fichero de ejemplos (`NN_arrays_*.php`), pon un punto de ruptura en la primera línea de código y
   ejecútalo en **modo depuración** (F5 en VS Code con Xdebug).
2. Avanza línea a línea con **F10** (*Step Over*) y despliega los arrays en el panel **VARIABLES**: fíjate en
   las **claves** además de en los valores. En los ficheros que usan funciones de comparación o *callbacks*
   (08, 09 y 12), usa **F11** (*Step Into*) para entrar en ellas y ver qué elementos reciben.
3. Antes de cada función tienes el **enlace** a la documentación oficial, el **enunciado** del ejemplo y notas
   sobre errores habituales.
4. Después, resuelve los problemas de `problemas/NN_arrays_*_problema.php` y compara con
   `problemas/NN_arrays_*_problema_solucion.php`.

La función `ver()` de [`utilidades.php`](utilidades.php) muestra cada resultado en la terminal, el depurador o
el navegador.

## Índice

| Fichero | Funciones |
|---|---|
| `01_arrays_basicas_01.php` | array, list, count / sizeof, range, array_fill, array_fill_keys, array_pad, compact, extract |
| `02_arrays_basicas_02.php` | array_push, array_pop, array_unshift, array_shift, unset, array_key_first, array_key_last |
| `03_arrays_basicas_03.php` | array_keys, array_flip, array_change_key_case, array_is_list, array_count_values, array_unique |
| `04_arrays_basicas_04.php` | array_slice, array_splice, array_chunk, array_rand, shuffle, array_sum, array_product |
| `05_arrays_recorrido_01.php` | current / pos, key, next, prev, reset, end, each (eliminada → foreach) |
| `06_arrays_busqueda_01.php` | in_array, array_search, array_key_exists, key_exists (y su diferencia con isset) |
| `07_arrays_ordenar_01.php` | sort, rsort, asort, arsort, ksort, krsort, array_reverse |
| `08_arrays_ordenar_02.php` | usort, uasort, uksort, natsort, natcasesort, array_multisort |
| `09_arrays_iterar_01.php` | array_map, array_filter, array_reduce, array_values, array_walk, array_walk_recursive |
| `10_arrays_combinar_01.php` | array_merge (y el operador +), array_merge_recursive, array_replace, array_replace_recursive, array_combine, array_column |
| `11_arrays_conjuntos_01.php` | array_diff, array_diff_assoc, array_diff_key, array_intersect, array_intersect_assoc, array_intersect_key |
| `12_arrays_conjuntos_02.php` | array_udiff, array_uintersect, array_diff_ukey, array_intersect_ukey, array_diff_uassoc, array_udiff_assoc |

## Notas

- `each()` ya no existe en PHP 8: el ejemplo lo comprueba con `function_exists` y muestra el equivalente con
  `foreach`.
- De las operaciones de conjuntos con funciones de comparación se trabajan las seis más útiles; el fichero 12
  menciona las variantes restantes (`array_uintersect_assoc`, `array_udiff_uassoc`, `array_uintersect_uassoc`,
  `array_intersect_uassoc`), que combinan las mismas ideas.
- Los ejemplos con `array_rand` y `shuffle` dan un resultado distinto en cada ejecución.

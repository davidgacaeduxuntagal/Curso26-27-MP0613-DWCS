<?php
/*
 * 02_regexes_basicas_02_problema.php
 *  Problemas de: cuantificadores * + ? {n} {n,} {n,m}, codiciosos y perezosos, alternancia |,
 *                límites de palabra \b, modificador m
 *  (repasa los ejemplos de ../02_regexes_basicas_02.php)
 *
 *  Resuelve cada problema donde se indica y ejecútalo en modo depuración para comprobarlo.
 */
require_once __DIR__ . '/../utilidades.php';

function probar(string $patron, array $textos): array
{
    $resultado = [];
    foreach ($textos as $texto) {
        $resultado[$texto] = preg_match($patron, $texto);
    }
    return $resultado;
}

// =============================================================================================
// PROBLEMA 1. Risas
//  Detecta si un mensaje contiene una risa: "ja" repetido 2 o más veces seguidas ("jaja",
//  "jajajaja"), sin distinguir mayúsculas. Debe fallar con "ja" solo y con "jaula".
// =============================================================================================
titulo('Problema 1');
$textos = ["jajaja qué bueno", "JAJA", "ja", "una jaula"];
// completa aquí tu solución: $patron1 = '...';

// =============================================================================================
// PROBLEMA 2. Nombre de usuario
//  Un nombre de usuario empieza por una letra, después tiene entre 3 y 15 letras, dígitos o
//  guiones bajos, y opcionalmente termina en un punto seguido de 2 dígitos (".26").
// =============================================================================================
titulo('Problema 2');
$textos = ["ana_perez", "ana_perez.26", "a1", "1ana", "ana_perez.2026", "ana-perez"];
// completa aquí tu solución: $patron2 = '...';

// =============================================================================================
// PROBLEMA 3. Texto entre comillas
//  Obtén con preg_match el PRIMER texto entre comillas dobles de la frase. ¿Qué pasa si usas un
//  cuantificador codicioso? Muestra los dos resultados.
// =============================================================================================
titulo('Problema 3');
$frase = 'Dijo "hola" y luego "adiós"';
// completa aquí tu solución

// =============================================================================================
// PROBLEMA 4. Palabra completa
//  Comprueba si cada frase contiene la palabra "mar" como palabra completa (no dentro de "marca",
//  "amar" o "marino"), sin distinguir mayúsculas.
// =============================================================================================
titulo('Problema 4');
$textos = ["Vivo cerca del mar", "Mar en calma", "Mi marca favorita", "Quiero amar", "El marino"];
// completa aquí tu solución: $patron4 = '...';

// =============================================================================================
// PROBLEMA 5. Líneas de un fichero de configuración
//  ¿Hay alguna línea del texto que sea un comentario (empieza por "#" o por ";", con posibles
//  espacios delante)? ¿Y alguna línea vacía? Usa el modificador m.
// =============================================================================================
titulo('Problema 5');
$ini = "host = localhost\n   # puerto por defecto\nport = 3306\n\nuser = root";
// completa aquí tu solución

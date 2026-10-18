<?php
/*
 * 15_strings_codificacion_03.php
 *  STRINGS - CODIFICACIÓN (3): codificación de caracteres (UTF-8, ISO-8859-1, Windows-1252)
 *  Funciones: utf8_encode, utf8_decode, mb_detect_encoding, mb_check_encoding, mb_convert_encoding
 *
 *  Un mismo carácter se guarda con bytes distintos según la codificación:
 *      "ñ"  en UTF-8 = c3 b1 (2 bytes)       en ISO-8859-1 / Windows-1252 = f1 (1 byte)
 *      "€"  en UTF-8 = e2 82 ac (3 bytes)    en Windows-1252 = 80          (no existe en ISO-8859-1)
 *  Si se leen con la codificación equivocada aparecen caracteres raros ("aÃ±o" en vez de "año").
 *  Es un problema real y frecuente con ficheros CSV exportados desde Excel en Windows.
 *
 *  Ejecuta en modo depuración paso a paso (F10). Usamos bin2hex para VER los bytes.
 */
require_once __DIR__ . '/utilidades.php';

// Simulamos un texto "antiguo" guardado en Windows-1252 (como un CSV de Excel en español)
$textoUtf8     = "Año 2026: Peña, 5€";
$textoWindows  = mb_convert_encoding($textoUtf8, "Windows-1252", "UTF-8");

// ---------------------------------------------------------------------------------------------
// mb_check_encoding — comprueba si un string es válido en una codificación
// ENLACE: https://www.php.net/manual/es/function.mb-check-encoding.php
// ENUNCIADO EJEMPLO: antes de guardar en la base de datos lo que llega de un formulario o de un
//   fichero subido, comprobar que es UTF-8 válido.
// ---------------------------------------------------------------------------------------------
titulo('mb_check_encoding');
$esUtf8        = mb_check_encoding($textoUtf8, "UTF-8");      // true
$windowsEsUtf8 = mb_check_encoding($textoWindows, "UTF-8");   // false
ver('¿el texto UTF-8 es UTF-8 válido?', $esUtf8);
ver('¿el texto Windows-1252 es UTF-8 válido?', $windowsEsUtf8);
ver('bytes en UTF-8', bin2hex($textoUtf8));
ver('bytes en Windows-1252', bin2hex($textoWindows));

// ---------------------------------------------------------------------------------------------
// mb_detect_encoding — intenta adivinar la codificación de un string
// ENLACE: https://www.php.net/manual/es/function.mb-detect-encoding.php
// ENUNCIADO EJEMPLO: al importar un CSV que han subido los usuarios, averiguar si viene en
//   UTF-8 o en Windows-1252 para convertirlo si hace falta.
// NOTA: es una ESTIMACIÓN: prueba las codificaciones de la lista en orden y devuelve la primera
//   que encaja. Con strict = true (3er parámetro) es más fiable. Las codificaciones de 1 byte
//   (ISO-8859-1, Windows-1252) "encajan" con casi todo, por eso UTF-8 debe ir primero.
// ---------------------------------------------------------------------------------------------
titulo('mb_detect_encoding');
$lista         = ["UTF-8", "Windows-1252"];
$detectado1    = mb_detect_encoding($textoUtf8, $lista, true);      // "UTF-8"
$detectado2    = mb_detect_encoding($textoWindows, $lista, true);   // "Windows-1252"
ver('detectado (texto UTF-8)', $detectado1);
ver('detectado (texto Windows)', $detectado2);

// ---------------------------------------------------------------------------------------------
// mb_convert_encoding — convierte un string de una codificación a otra
// ENLACE: https://www.php.net/manual/es/function.mb-convert-encoding.php
// ENUNCIADO EJEMPLO: convertir a UTF-8 el texto de un CSV de Excel (Windows-1252) para
//   mostrarlo correctamente en nuestra web.
// NOTA: parámetros: (texto, codificación DESTINO, codificación ORIGEN). Es la función
//   recomendada para todas las conversiones.
// ---------------------------------------------------------------------------------------------
titulo('mb_convert_encoding');
$convertido    = mb_convert_encoding($textoWindows, "UTF-8", "Windows-1252");
ver('convertido a UTF-8', $convertido);
ver('¿igual al original?', $convertido === $textoUtf8);           // true

$malConvertido = mb_convert_encoding($textoUtf8, "UTF-8", "Windows-1252");  // ¡origen equivocado!
ver('conversión con el origen equivocado', $malConvertido);        // "AÃ±o 2026: PeÃ±a, 5â‚¬"

// ---------------------------------------------------------------------------------------------
// utf8_decode / utf8_encode — convierten entre UTF-8 e ISO-8859-1 (Latin-1)
// ENLACE: https://www.php.net/manual/es/function.utf8-decode.php
// ENLACE: https://www.php.net/manual/es/function.utf8-encode.php
// ENUNCIADO EJEMPLO: ver por qué estas funciones están OBSOLETAS: solo saben de ISO-8859-1, que
//   NO tiene el símbolo "€", así que el euro se pierde y aparece "?".
// NOTA: DEPRECATED (obsoletas) desde PHP 8.2: al ejecutarlas verás un aviso "Deprecated".
//   Usa mb_convert_encoding($texto, "ISO-8859-1", "UTF-8") y mb_convert_encoding($texto, "UTF-8", "ISO-8859-1").
// ---------------------------------------------------------------------------------------------
titulo('utf8_decode / utf8_encode (obsoletas)');
$latin1        = utf8_decode($textoUtf8);         // Deprecated. El "€" se convierte en "?"
$deVuelta      = utf8_encode($latin1);            // Deprecated. "Año 2026: Peña, 5?"
ver('bytes en ISO-8859-1', bin2hex($latin1));
ver('ida y vuelta (se ha perdido el €)', $deVuelta);

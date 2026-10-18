<?php
/*
 * 02_strings_basicas_02.php
 *  STRINGS - BÁSICAS (2): limpiar, rellenar y extraer trozos
 *  Funciones: trim, ltrim, rtrim / chop, str_pad, substr, chunk_split, str_shuffle
 *
 *  Ejecuta en modo depuración paso a paso (F10) y observa en el panel VARIABLES cada valor.
 *  Fíjate especialmente en los ESPACIOS: en el depurador se ven entre las comillas.
 */
require_once __DIR__ . '/utilidades.php';

// ---------------------------------------------------------------------------------------------
// trim — quita espacios (y \n, \r, \t, \0, \v) al principio y al final
// ENLACE: https://www.php.net/manual/es/function.trim.php
// ENUNCIADO EJEMPLO: limpiar lo que el usuario escribe en un campo de formulario antes de
//   validarlo. Un nombre que solo tiene espacios debe considerarse vacío.
// NOTA: el segundo parámetro indica QUÉ caracteres quitar (admite rangos como "a..z").
// ---------------------------------------------------------------------------------------------
titulo('trim');
$entrada       = "   Ana Pérez \n";
$limpia        = trim($entrada);                 // "Ana Pérez"
ver('trim', $limpia);

$soloEspacios  = "     ";
$estaVacio     = trim($soloEspacios) === "";     // true
ver('¿vacío tras trim?', $estaVacio);

$precio        = "**25,90€**";
$sinAsteriscos = trim($precio, "*");             // "25,90€"
ver('trim con caracteres', $sinAsteriscos);

// ---------------------------------------------------------------------------------------------
// ltrim / rtrim (alias: chop) — quitan solo por la izquierda / por la derecha
// ENLACE: https://www.php.net/manual/es/function.ltrim.php
// ENLACE: https://www.php.net/manual/es/function.rtrim.php
// ENLACE: https://www.php.net/manual/es/function.chop.php
// ENUNCIADO EJEMPLO: quitar los ceros a la izquierda de un número de factura y la barra final
//   de una URL.
// ---------------------------------------------------------------------------------------------
titulo('ltrim / rtrim / chop');
$factura       = "000452";
$sinCeros      = ltrim($factura, "0");           // "452"
ver('ltrim', $sinCeros);

$url           = "https://www.iesrodeira.gal/";
$sinBarra      = rtrim($url, "/");               // "https://www.iesrodeira.gal"
ver('rtrim', $sinBarra);

$linea         = "dato;dato;\r\n";
$sinSalto      = chop($linea);                   // "dato;dato;"  (chop es un alias de rtrim)
ver('chop', $sinSalto);

// ---------------------------------------------------------------------------------------------
// str_pad — rellena un string hasta una longitud
// ENLACE: https://www.php.net/manual/es/function.str-pad.php
// ENUNCIADO EJEMPLO: generar números de pedido con ceros a la izquierda (siempre 6 cifras) y
//   alinear columnas de un ticket de compra.
// NOTA: STR_PAD_RIGHT (por defecto), STR_PAD_LEFT o STR_PAD_BOTH. Si el string ya es más largo,
//   no se recorta. Cuenta BYTES (cuidado con tildes al alinear).
// ---------------------------------------------------------------------------------------------
titulo('str_pad');
$pedido        = 37;
$codigo        = str_pad($pedido, 6, "0", STR_PAD_LEFT);    // "000037"
ver('str_pad izquierda', $codigo);

$lineaTicket   = str_pad("Pan", 15, ".") . str_pad("1,20", 8, " ", STR_PAD_LEFT);
ver('línea de ticket', $lineaTicket);                       // "Pan............    1,20"

$cabecera      = str_pad(" TICKET ", 23, "=", STR_PAD_BOTH); // "======= TICKET ========"
ver('str_pad ambos lados', $cabecera);

// ---------------------------------------------------------------------------------------------
// substr — extrae una parte de un string
// ENLACE: https://www.php.net/manual/es/function.substr.php
// ENUNCIADO EJEMPLO: de un DNI "12345678Z" obtener el número y la letra; de una fecha
//   "2026-10-16" obtener el año; obtener los 4 últimos dígitos de una cuenta.
// NOTA: substr($texto, inicio, longitud). Inicio negativo = contando desde el final.
//   Longitud negativa = deja fuera esos caracteres del final. Las posiciones empiezan en 0.
// ---------------------------------------------------------------------------------------------
titulo('substr');
$dni           = "12345678Z";
$numero        = substr($dni, 0, 8);         // "12345678"
$letra         = substr($dni, -1);           // "Z"
ver('número del DNI', $numero);
ver('letra del DNI', $letra);

$fecha         = "2026-10-16";
$anio          = substr($fecha, 0, 4);       // "2026"
$mes           = substr($fecha, 5, 2);       // "10"
ver('año', $anio);
ver('mes', $mes);

$cuenta        = "ES9121000418450200051332";
$ultimos4      = substr($cuenta, -4);        // "1332"
$sinPais       = substr($cuenta, 2, -4);     // desde la posición 2, sin los 4 últimos
ver('últimos 4', $ultimos4);
ver('substr con longitud negativa', $sinPais);

$fuera         = substr("hola", 10);         // "" (PHP 8: string vacío, ya no false)
ver('substr fuera de rango', $fuera);

// ---------------------------------------------------------------------------------------------
// chunk_split — parte un string en trozos de N caracteres, añadiendo un separador al final de cada uno
// ENLACE: https://www.php.net/manual/es/function.chunk-split.php
// ENUNCIADO EJEMPLO: mostrar un IBAN en bloques de 4 caracteres, como en los recibos del banco.
// NOTA: también añade el separador al FINAL, por eso suele combinarse con rtrim.
// ---------------------------------------------------------------------------------------------
titulo('chunk_split');
$iban          = "ES9121000418450200051332";
$enBloques     = chunk_split($iban, 4, " ");          // "ES91 2100 0418 4502 0005 1332 "
$ibanBonito    = rtrim($enBloques);                    // sin el espacio final
ver('chunk_split', $enBloques);
ver('IBAN formateado', $ibanBonito);

// ---------------------------------------------------------------------------------------------
// str_shuffle — desordena aleatoriamente los caracteres de un string
// ENLACE: https://www.php.net/manual/es/function.str-shuffle.php
// ENUNCIADO EJEMPLO: generar un código de 6 caracteres para un juego de "adivina la palabra" o
//   una pista desordenada.
// NOTA: NO es seguro para contraseñas ni tokens (usa random_bytes / random_int para eso).
//   Cada ejecución da un resultado distinto: ejecútalo varias veces.
// ---------------------------------------------------------------------------------------------
titulo('str_shuffle');
$palabraSecreta = "servidor";
$pista          = str_shuffle($palabraSecreta);           // p.ej. "rdsvieor"
ver('str_shuffle', $pista);

$caracteres     = "ABCDEFGHJKLMNPQRSTUVWXYZ23456789";     // sin 0/O ni 1/I para no confundir
$codigo         = substr(str_shuffle($caracteres), 0, 6);  // 6 caracteres sin repetir
ver('código de 6 caracteres', $codigo);

<?php
/*
 * 08_strings_busqueda_02_problema_solucion.php
 *  (FICHERO CON LAS SOLUCIONES)
 *  Problemas de: strstr / strchr, stristr, strrchr, strpbrk, substr_count
 *  (repasa los ejemplos de ../08_strings_busqueda_02.php)
 *
 *  Resuelve cada problema donde se indica y ejecútalo en modo depuración para comprobarlo.
 */
require_once __DIR__ . '/../utilidades.php';

// =============================================================================================
// PROBLEMA 1. Clave y valor de una línea de configuración
//  Las líneas de un fichero .ini tienen el formato "clave=valor". Con strstr obtén la clave
//  (lo que hay antes del "=") y el valor (lo que hay después, sin el "=").
// =============================================================================================
titulo('Problema 1');
$linea = "db_password=s3cr3t=o";
// completa aquí tu solución
// ---- SOLUCIÓN ----
$clave = strstr($linea, "=", true);          // "db_password"
$valor = substr(strstr($linea, "="), 1);     // "s3cr3t=o" (strstr se queda con el PRIMER "=")
ver('clave', $clave);
ver('valor', $valor);

// =============================================================================================
// PROBLEMA 2. Extensión de varios ficheros
//  Obtén la extensión (sin el punto y en minúsculas) de cada fichero con strrchr. Si un fichero
//  no tiene extensión, guarda "(sin extensión)".
// =============================================================================================
titulo('Problema 2');
$ficheros = ["foto.JPEG", "README", "copia.tar.gz"];
// completa aquí tu solución
// ---- SOLUCIÓN ----
$extensiones = [];
foreach ($ficheros as $fichero) {
    $conPunto = strrchr($fichero, ".");
    $extensiones[$fichero] = $conPunto === false ? "(sin extensión)" : strtolower(substr($conPunto, 1));
}
ver('extensiones', $extensiones);   // ['foto.JPEG' => 'jpeg', 'README' => '(sin extensión)', 'copia.tar.gz' => 'gz']

// =============================================================================================
// PROBLEMA 3. Asunto de un correo
//  Si el asunto contiene "re:" (sin distinguir mayúsculas), queremos el texto que va a
//  continuación, sin espacios iniciales. Si no lo contiene, el asunto completo.
// =============================================================================================
titulo('Problema 3');
$asuntos = ["RE: Reunión del lunes", "Factura de octubre", "Re: re: Notas"];
// completa aquí tu solución
// ---- SOLUCIÓN ----
$limpios = [];
foreach ($asuntos as $asunto) {
    $desde     = stristr($asunto, "re:");
    $limpios[] = $desde === false ? $asunto : trim(substr($desde, 3));
}
ver('asuntos', $limpios);     // ["Reunión del lunes", "Factura de octubre", "re: Notas"]

// =============================================================================================
// PROBLEMA 4. Nombre de fichero seguro
//  Un nombre de fichero no puede contener ninguno de estos caracteres: \ / : * ? " < > |
//  Usa strpbrk para decir qué nombres son válidos.
// =============================================================================================
titulo('Problema 4');
$nombres = ["informe.pdf", "notas:octubre.txt", "foto?.jpg", "tarea_2.php"];
// completa aquí tu solución
// ---- SOLUCIÓN ----
$validos = [];
foreach ($nombres as $nombre) {
    $validos[$nombre] = strpbrk($nombre, '\\/:*?"<>|') === false;
}
ver('¿válidos?', $validos);

// =============================================================================================
// PROBLEMA 5. Estadísticas de un texto
//  Cuenta en el texto: el número de frases (puntos "."), de comas y de veces que aparece la
//  palabra "web".
// =============================================================================================
titulo('Problema 5');
$texto = "La web es rápida. La web es segura, fiable y bonita. Me gusta esta web, mucho.";
// completa aquí tu solución
// ---- SOLUCIÓN ----
$estadisticas = [
    'frases' => substr_count($texto, "."),     // 3
    'comas'  => substr_count($texto, ","),     // 2
    'web'    => substr_count($texto, "web"),   // 3
];
ver('estadísticas', $estadisticas);

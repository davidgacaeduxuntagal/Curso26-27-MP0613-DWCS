<?php
/*
 * e02-ficheros_problema_solucion.php
 *  (FICHERO CON LAS SOLUCIONES)
 *  Problemas de: fopen (modos r, w, a, x, c), fwrite / fputs, fclose, error_get_last
 *  (repasa los ejemplos de ../e02-ficheros.php)
 *
 *  Resuelve cada problema donde se indica y ejecútalo en modo depuración para comprobarlo.
 */
require_once __DIR__ . '/../utilidades.php';

// =============================================================================================
// PROBLEMA 1. Tabla de multiplicar en un fichero
//  Con fopen (modo w), un bucle y fwrite, escribe en p02_tabla7.txt la tabla del 7 ("7 x 1 = 7",
//  una operación por línea) y cierra el fichero. Muestra el contenido.
// =============================================================================================
titulo('Problema 1');
$tabla = rutaDatos('p02_tabla7.txt');
// completa aquí tu solución
// ---- SOLUCIÓN ----
$fp = fopen($tabla, 'w');
for ($i = 1; $i <= 10; $i++) {
    fwrite($fp, "7 x $i = " . (7 * $i) . "\n");
}
fclose($fp);
ver('tabla', file_get_contents($tabla));

// =============================================================================================
// PROBLEMA 2. Función de registro (log)
//  Escribe una función registrar(string $mensaje, string $nivel = 'INFO'): void que añada al
//  fichero p02_app.log una línea "[AAAA-MM-DD HH:MM:SS] NIVEL mensaje". Úsala tres veces.
// =============================================================================================
titulo('Problema 2');
// completa aquí tu solución
// ---- SOLUCIÓN ----
function registrar(string $mensaje, string $nivel = 'INFO'): void
{
    $fp = fopen(rutaDatos('p02_app.log'), 'a');
    fwrite($fp, "[" . date('Y-m-d H:i:s') . "] $nivel $mensaje\n");
    fclose($fp);
}
registrar("Arranca la aplicación");
registrar("No se encuentra la imagen logo.png", 'WARNING');
registrar("Usuario ana conectado");
ver('últimas 3 líneas', array_slice(file(rutaDatos('p02_app.log'), FILE_IGNORE_NEW_LINES), -3));

// =============================================================================================
// PROBLEMA 3. Número de pedido único
//  Cada pedido se guarda en un fichero p02_pedido_N.txt. Escribe una función crearPedido(int $n,
//  string $texto): bool que cree el fichero SOLO si no existe (modo x) y devuelva true, o false si
//  ya existía, sin mostrar avisos. Llámala dos veces con el mismo número.
// =============================================================================================
titulo('Problema 3');
@unlink(rutaDatos('p02_pedido_5.txt'));
// completa aquí tu solución
// ---- SOLUCIÓN ----
function crearPedido(int $n, string $texto): bool
{
    $fp = @fopen(rutaDatos("p02_pedido_$n.txt"), 'x');
    if ($fp === false) {
        return false;
    }
    fwrite($fp, $texto);
    fclose($fp);
    return true;
}
ver('primera vez', crearPedido(5, "2 cafés"));    // true
ver('segunda vez', crearPedido(5, "3 tés"));      // false
ver('contenido', file_get_contents(rutaDatos('p02_pedido_5.txt')));   // "2 cafés" (no se ha sobrescrito)

// =============================================================================================
// PROBLEMA 4. ¿Qué modo usarías?
//  Para cada situación escribe en un comentario el modo de fopen que usarías y por qué:
//    a) leer la carta del bar          b) añadir una opinión al libro de visitas
//    c) regenerar cada noche el informe de ventas (borrando el anterior)
//    d) crear la factura 38 sin riesgo de pisar otra
//    e) abrir el contador de visitas para leerlo y modificarlo sin vaciarlo antes de bloquearlo
// =============================================================================================
titulo('Problema 4');
// ¿qué modo usarías en cada caso? (respóndelo en comentarios)
// ---- SOLUCIÓN ----
// a) 'r'  : solo lectura; si no existe, fopen devuelve false.
// b) 'a'  : añade al final sin borrar; si no existe, lo crea.
// c) 'w'  : vacía el fichero al abrirlo y escribe desde el principio.
// d) 'x'  : falla si el fichero ya existe.
// e) 'c+' : lectura y escritura, lo crea si no existe y NO lo vacía (lo vaciamos con ftruncate
//           cuando ya tenemos el bloqueo; lo verás en e08).
ver('respuestas', 'ver los comentarios del código');

// =============================================================================================
// PROBLEMA 5. Mensaje de error amigable
//  Intenta abrir para leer el fichero p02_no_existe.txt. Si falla, guarda el mensaje técnico
//  (error_get_last) en el log del problema 2 con nivel ERROR y muestra al usuario solo el texto
//  "No se ha podido cargar la información".
// =============================================================================================
titulo('Problema 5');
// completa aquí tu solución
// ---- SOLUCIÓN ----
$fp = @fopen(rutaDatos('p02_no_existe.txt'), 'r');
if ($fp === false) {
    registrar(error_get_last()['message'], 'ERROR');
    ver('mensaje para el usuario', "No se ha podido cargar la información");
} else {
    fclose($fp);
}
ver('última línea del log', array_slice(file(rutaDatos('p02_app.log'), FILE_IGNORE_NEW_LINES), -1)[0]);

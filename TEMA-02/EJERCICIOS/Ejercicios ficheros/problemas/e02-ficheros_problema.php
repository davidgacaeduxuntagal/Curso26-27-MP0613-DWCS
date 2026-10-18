<?php
/*
 * e02-ficheros_problema.php
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

// =============================================================================================
// PROBLEMA 2. Función de registro (log)
//  Escribe una función registrar(string $mensaje, string $nivel = 'INFO'): void que añada al
//  fichero p02_app.log una línea "[AAAA-MM-DD HH:MM:SS] NIVEL mensaje". Úsala tres veces.
// =============================================================================================
titulo('Problema 2');
// completa aquí tu solución

// =============================================================================================
// PROBLEMA 3. Número de pedido único
//  Cada pedido se guarda en un fichero p02_pedido_N.txt. Escribe una función crearPedido(int $n,
//  string $texto): bool que cree el fichero SOLO si no existe (modo x) y devuelva true, o false si
//  ya existía, sin mostrar avisos. Llámala dos veces con el mismo número.
// =============================================================================================
titulo('Problema 3');
@unlink(rutaDatos('p02_pedido_5.txt'));
// completa aquí tu solución

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

// =============================================================================================
// PROBLEMA 5. Mensaje de error amigable
//  Intenta abrir para leer el fichero p02_no_existe.txt. Si falla, guarda el mensaje técnico
//  (error_get_last) en el log del problema 2 con nivel ERROR y muestra al usuario solo el texto
//  "No se ha podido cargar la información".
// =============================================================================================
titulo('Problema 5');
// completa aquí tu solución

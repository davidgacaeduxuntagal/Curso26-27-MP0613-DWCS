<?php
/*
 * ===== CORRECCIONES (revisión de las soluciones del tema 2) =====
 * - Ejemplo 2: faltaba $i++ dentro del while: bucle infinito (se agotaba el tiempo de ejecución).
 */
// DWES-PHP-B03-05

for ($i = 1; $i <= 100; $i++) {
    // código ....
}

?>

<?php
// Ejemplo 1:

$i = 1; 
while ($i <= 100) {
    // código ....  
    $i++;
}
?>

<?php
// Ejemplo 2:
$i = 1; 
while ($i <= 100):?>

<!-- html o lo que sea -->

<?php
    $i++;   // CORREGIDO: faltaba incrementar $i: el bucle no terminaba nunca
endwhile; ?>



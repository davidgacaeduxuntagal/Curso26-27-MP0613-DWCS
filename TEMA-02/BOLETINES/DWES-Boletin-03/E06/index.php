<?php
/*
 * ===== CORRECCIONES (revisión de las soluciones del tema 2) =====
 * - $condicion no estaba definida: Warning "Undefined variable" al ejecutar los ejemplos.
 */
// DWES-PHP-B03-06
$condicion = false;   // CORREGIDO: no estaba definida (Warning: Undefined variable)
do {
    // código
} while ($condicion);

?>

<?php
// Ejemplo 1:
$inicial = true;
while ( $inicial || $condicion ) {
    $inicial = false;
    // Código
}
?>

<?php
// Ejemplo 2:
// Código A
while ( $condicion ) {
    // Código A
}
?>

<?php
// Ejemplo 3:
$inicial = true;
while ( $inicial || $condicion ):
    $inicial = false;?>

    <!-- html o lo que sea -->

<?php endwhile;?>
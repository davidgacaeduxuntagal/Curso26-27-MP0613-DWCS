<?php
/*
 * ===== CORRECCIONES (revisión de las soluciones del tema 2) =====
 * - Faltaba traducir la primera parte de la expresión de C: d += m < 3 ? y-- : y-2 (en PHP el y-- es igual:
 *   devuelve el año y después lo decrementa). Para 29/1/2023 salía bien por casualidad (2023 es múltiplo de 7),
 *   pero fallaba en el 86 % de las fechas de los años 1 a 3000 (por ejemplo 15/6/2024 daba domingo; es sábado).
 * - El comentario decía 7 = sábado (es 6).
 */
// DWES-PHP-B06-06

$cadenas = ["domingo", "lunes", "martes", "miércoles", "jueves", "viernes", "sábado"];

$dia = 29;
$mes = 1;
$anno = 2023;

// 0 = domingo; .... 6 = sábado   (CORREGIDO: ponía 7)
$dia += $mes < 3 ? $anno-- : $anno - 2;   // CORREGIDO: faltaba la primera parte de la expresión de C
$diaSemana = (int) (( (int) (23 * $mes / 9) + (int) ($dia + 4) +  (int) ($anno / 4) -  (int) ($anno / 100) +  (int) ($anno / 400) ) % 7 );

echo "El día de la semana es " . $cadenas[$diaSemana];


echo PHP_EOL . "FIN";
?>
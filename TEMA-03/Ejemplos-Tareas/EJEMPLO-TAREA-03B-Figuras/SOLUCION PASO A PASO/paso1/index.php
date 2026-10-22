<?php
/*
 * >>> PASO 1 de 4: descomposición del problema (solo TODOs, sin código PHP) <<<
 *
 * TAREA-03 BÁSICA: Figuras geométricas
 *  - genera figuras al azar (polimorfismo: Figura::generarAlAzar()) y una figura "a medida"
 *  - las ordena, las dibuja en SVG y muestra un resumen con los contadores estáticos
 */
// TODO: incluir las clases (require_once de Circulo, Rectangulo, Cuadrado y Triangulo)

// TODO: constantes TAMANO_DIBUJO (140 px) y MAX_FIGURAS (20)

// TODO: 1) entrada de datos (GET): número de figuras (0-20) y criterio de orden (lista blanca)

// TODO: 2) figura a medida (opcional): tipo, medidas (con coma decimal) y color
//          - validar que las medidas son números
//          - crear la figura con match y capturar InvalidArgumentException con try ... catch

// TODO: 3) crear las figuras al azar con Figura::generarAlAzar() (y añadir la figura a medida)

// TODO: 4) ordenar con usort según el criterio (área, perímetro o tipo de figura)

// TODO: 5) escala común para dibujar (a partir de la mayor dimensionMaxima()) y área total (array_reduce)

// Función auxiliar TODO: e(string $texto): string   -> atajo de htmlspecialchars
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Figuras geométricas</title>
    <link rel="stylesheet" href="estilos.css">
</head>

<body>
    <h1>Figuras geométricas</h1>

    <!-- TODO: formulario GET: número de figuras, ordenar por, y figura a medida (tipo, 3 medidas, color) -->

    <!-- TODO: lista de errores, si los hay -->

    <!-- TODO: una tarjeta por figura: dibujo SVG, __toString() y, si es un triángulo (instanceof), su tipo -->

    <!-- TODO: resumen: área total y número de objetos creados de cada clase (contadores estáticos) -->
</body>

</html>

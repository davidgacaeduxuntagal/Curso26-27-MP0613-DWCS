<?php
/*
 * >>> PASO 2 de 4: jerarquía de clases, constructores, getters/setters y listado <<<
 *
 * TAREA-03 BÁSICA: Figuras geométricas
 *  - genera figuras al azar (polimorfismo: Figura::generarAlAzar()) y una figura "a medida"
 *  - las ordena, las dibuja en SVG y muestra un resumen con los contadores estáticos
 */
require_once 'clases/Circulo.php';
require_once 'clases/Rectangulo.php';
require_once 'clases/Cuadrado.php';
require_once 'clases/Triangulo.php';

const TAMANO_DIBUJO = 140;     // píxeles de cada dibujo
const MAX_FIGURAS   = 20;

$errores = [];

// ---------------------------------------------------------------------- 1) Parámetros (GET)
$numero = filter_var($_GET['n'] ?? 8, FILTER_VALIDATE_INT, ['options' => ['min_range' => 0, 'max_range' => MAX_FIGURAS]]);
if ($numero === false) {
    $errores[] = "El número de figuras debe estar entre 0 y " . MAX_FIGURAS . ".";
    $numero    = 8;
}
$orden = in_array($_GET['orden'] ?? '', ['area', 'perimetro', 'nombre'], true) ? $_GET['orden'] : 'area';

// ---------------------------------------------------------------------- 2) Figura a medida (opcional)
$tipo        = $_GET['tipo'] ?? '';
$medidas     = [$_GET['m1'] ?? '', $_GET['m2'] ?? '', $_GET['m3'] ?? ''];
$colorElegido = $_GET['color'] ?? 'azul';
$aMedida     = null;

// TODO (paso 4): si se ha elegido un tipo, convertir y validar las medidas y crear la figura
//   con match + try ... catch (InvalidArgumentException); los errores van a $errores

// ---------------------------------------------------------------------- 3) Figuras al azar
$figuras = [];
for ($i = 0; $i < $numero; $i++) {
    $figuras[] = Figura::generarAlAzar();
}
if ($aMedida !== null) {
    $figuras[] = $aMedida;
}

// ---------------------------------------------------------------------- 4) Ordenación (polimórfica)
// TODO (paso 4): ordenar $figuras con usort según $orden (área, perímetro o tipo de figura)

// ---------------------------------------------------------------------- 5) Escala común y resumen
// TODO (paso 3): escala común (píxeles por cm) a partir de la mayor dimensionMaxima()
// TODO (paso 4): área total con array_reduce

function e(string $texto): string
{
    return htmlspecialchars($texto, ENT_QUOTES, 'UTF-8');
}
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

    <form method="get" class="panel">
        <fieldset>
            <legend>Figuras al azar</legend>
            <label>Número de figuras: <input type="number" name="n" min="0" max="<?= MAX_FIGURAS ?>" value="<?= $numero ?>"></label>
            <label>Ordenar por:
                <select name="orden">
                    <?php foreach (['area' => 'Área', 'perimetro' => 'Perímetro', 'nombre' => 'Tipo de figura'] as $valor => $texto): ?>
                        <option value="<?= $valor ?>" <?= $orden === $valor ? 'selected' : '' ?>><?= $texto ?></option>
                    <?php endforeach; ?>
                </select>
            </label>
        </fieldset>
        <fieldset>
            <legend>Añadir una figura a medida (opcional)</legend>
            <select name="tipo">
                <?php foreach (['' => '-- ninguna --', 'circulo' => 'Círculo (radio)', 'cuadrado' => 'Cuadrado (lado)', 'rectangulo' => 'Rectángulo (base, altura)', 'triangulo' => 'Triángulo (3 lados)'] as $valor => $texto): ?>
                    <option value="<?= $valor ?>" <?= $tipo === $valor ? 'selected' : '' ?>><?= $texto ?></option>
                <?php endforeach; ?>
            </select>
            <?php foreach ($medidas as $i => $valor): ?>
                <input type="text" name="m<?= $i + 1 ?>" size="4" placeholder="medida <?= $i + 1 ?>" value="<?= e($valor) ?>">
            <?php endforeach; ?>
            <select name="color">
                <?php foreach (array_keys(Figura::COLORES) as $color): ?>
                    <option <?= $colorElegido === $color ? 'selected' : '' ?>><?= $color ?></option>
                <?php endforeach; ?>
            </select>
        </fieldset>
        <button type="submit">Generar</button>
    </form>

    <?php if (count($errores) > 0): ?>
        <ul class="errores">
            <?php foreach ($errores as $error): ?>
                <li><?= e($error) ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

    <section class="figuras">
        <?php foreach ($figuras as $figura): ?>
            <article class="figura<?= $figura === $aMedida ? ' a-medida' : '' ?>">
                <!-- TODO (paso 3): dibujo SVG de la figura -->
                <p><?= e((string) $figura) ?></p>
                <!-- TODO (paso 3): para los triángulos (instanceof), mostrar su tipo -->
                <?php if ($figura === $aMedida): ?>
                    <p class="extra">(figura a medida)</p>
                <?php endif; ?>
            </article>
        <?php endforeach; ?>
    </section>

    <!-- TODO (paso 4): resumen: área total y número de objetos creados de cada clase (contadores estáticos) -->
</body>

</html>

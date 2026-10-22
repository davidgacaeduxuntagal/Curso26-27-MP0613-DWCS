<?php
/*
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

if ($tipo !== '') {
    // Convertimos las medidas (admitiendo coma decimal) y comprobamos que son números
    $valores = [];
    foreach ($medidas as $i => $texto) {
        $texto = str_replace(',', '.', trim($texto));
        $valores[$i] = is_numeric($texto) ? (float) $texto : null;
    }
    $necesarias = ['circulo' => 1, 'cuadrado' => 1, 'rectangulo' => 2, 'triangulo' => 3][$tipo] ?? 0;
    if ($necesarias === 0) {
        $errores[] = "Tipo de figura desconocido.";
    } elseif (in_array(null, array_slice($valores, 0, $necesarias), true)) {
        $errores[] = "Para esta figura debes escribir {$necesarias} medida(s) numérica(s).";
    } else {
        // Las validaciones de las propias clases lanzan InvalidArgumentException: la capturamos
        try {
            $aMedida = match ($tipo) {
                'circulo'    => new Circulo($valores[0], $colorElegido),
                'cuadrado'   => new Cuadrado($valores[0], $colorElegido),
                'rectangulo' => new Rectangulo($valores[0], $valores[1], $colorElegido),
                'triangulo'  => new Triangulo($valores[0], $valores[1], $valores[2], $colorElegido),
            };
        } catch (InvalidArgumentException $e) {
            $errores[] = $e->getMessage();
        }
    }
}

// ---------------------------------------------------------------------- 3) Figuras al azar
$figuras = [];
for ($i = 0; $i < $numero; $i++) {
    $figuras[] = Figura::generarAlAzar();
}
if ($aMedida !== null) {
    $figuras[] = $aMedida;
}

// ---------------------------------------------------------------------- 4) Ordenación (polimórfica)
switch ($orden) {
    case 'area':
        usort($figuras, [Figura::class, 'compararPorArea']);
        break;
    case 'perimetro':
        usort($figuras, fn(Figura $a, Figura $b) => $b->perimetro() <=> $a->perimetro());
        break;
    case 'nombre':
        usort($figuras, fn(Figura $a, Figura $b) => [$a->nombre(), $b->area()] <=> [$b->nombre(), $a->area()]);
        break;
}

// ---------------------------------------------------------------------- 5) Escala común y resumen
// La misma escala para todas: así los dibujos son proporcionales entre sí
$mayorDimension = 0;
foreach ($figuras as $figura) {
    $mayorDimension = max($mayorDimension, $figura->dimensionMaxima());
}
$escala    = $mayorDimension > 0 ? (TAMANO_DIBUJO - 10) / $mayorDimension : 1;
$areaTotal = array_reduce($figuras, fn(float $suma, Figura $f) => $suma + $f->area(), 0.0);

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
                <?= $figura->svg($escala, TAMANO_DIBUJO) ?>
                <p><?= e((string) $figura) ?></p>
                <?php if ($figura instanceof Triangulo): ?>
                    <p class="extra">Triángulo <?= $figura->tipo() ?></p>
                <?php endif; ?>
                <?php if ($figura === $aMedida): ?>
                    <p class="extra">(figura a medida)</p>
                <?php endif; ?>
            </article>
        <?php endforeach; ?>
    </section>

    <?php if (count($figuras) > 0): ?>
        <section class="panel resumen">
            <h2>Resumen</h2>
            <p>Área total: <strong><?= Figura::formato($areaTotal) ?> <?= Figura::UNIDAD ?>²</strong></p>
            <p>Objetos creados en esta petición:</p>
            <ul>
                <?php foreach ([Circulo::class, Rectangulo::class, Cuadrado::class, Triangulo::class] as $clase): ?>
                    <li><?= $clase ?>: <?= Figura::numeroCreadas($clase) ?></li>
                <?php endforeach; ?>
                <li><strong>Total: <?= Figura::numeroCreadas() ?></strong></li>
            </ul>
        </section>
    <?php endif; ?>
</body>

</html>

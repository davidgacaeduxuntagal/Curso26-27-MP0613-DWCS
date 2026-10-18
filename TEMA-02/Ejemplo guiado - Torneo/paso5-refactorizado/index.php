<?php
/*
 * index.php — Torneo de futbolín del Bar Baio
 * PASO 5 de 5 (ÚLTIMO): SOLUCIÓN COMPLETA (refactorizada)
 *              Mismo comportamiento que el paso 4 (pasan los mismos casos de prueba de
 *              00-analisis/entradas-salidas.txt), con el código más corto y sin repeticiones.
 */
require_once __DIR__ . '/funciones.php';

// 1. Inicializar
$equipos = cargarEquipos();
$datos = datosVacios();
$errores = [];
$mensaje = '';

// 2. Recepción y validación de datos
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $datos = recogerDatos();
    $errores = validarInscripcion($datos, $equipos);

    // 3. Procesar y persistir los datos
    if ($errores === []) {
        $equipos[] = crearEquipo($datos);
        if (guardarEquipos($equipos)) {
            header('Location: index.php?ok=1');      // POST/Redirect/GET: F5 no repite la inscripción
            exit;
        }
        $errores['general'] = 'No se ha podido guardar la inscripción. Inténtalo más tarde.';
    }
}

// 4. Mensajes informativos
if (isset($_GET['ok']) && $equipos !== []) {
    $mensaje = 'Inscripción guardada. ¡Mucha suerte, ' . end($equipos)['nombre'] . '!';
}
$plazas = MAX_EQUIPOS - count($equipos);

// 5. Enviar la página al cliente
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Torneo de futbolín — Bar Baio</title>
    <link rel="stylesheet" href="estilos.css">
</head>
<body>
<h1>Torneo de futbolín del Bar Baio</h1>

<?php if ($mensaje !== ''): ?>
    <p class="mensaje ok"><?= e($mensaje) ?></p>
<?php endif; ?>
<?php if (isset($errores['general'])): ?>
    <p class="mensaje ko"><?= e($errores['general']) ?></p>
<?php endif; ?>

<div class="columnas">
<section>
    <h2>Inscripción</h2>
    <?php if ($plazas <= 0): ?>
        <p class="mensaje ko">El torneo está completo. ¡Gracias por vuestro interés!</p>
    <?php else: ?>
    <form method="post" action="index.php" novalidate>
        <?php foreach (array_keys(CAMPOS_TEXTO) as $campo): ?>
            <?= htmlCampoTexto($campo, $datos, $errores) ?>
        <?php endforeach; ?>
        <div class="campo <?= isset($errores['categoria']) ? 'con-error' : '' ?>">
            <label for="categoria">Categoría</label>
            <select id="categoria" name="categoria">
                <option value="">— Elige —</option>
                <?php foreach (CATEGORIAS as $valor => $texto): ?>
                    <option value="<?= e($valor) ?>" <?= $datos['categoria'] === $valor ? 'selected' : '' ?>><?= e($texto) ?></option>
                <?php endforeach; ?>
            </select>
            <?= htmlError('categoria', $errores) ?>
        </div>
        <button type="submit">Inscribir equipo</button>
    </form>
    <?php endif; ?>
</section>

<section>
    <h2>Equipos inscritos (<?= count($equipos) ?>/<?= MAX_EQUIPOS ?>)</h2>
    <p>Plazas libres: <strong><?= $plazas ?></strong></p>
    <?php if ($equipos === []): ?>
        <p>Todavía no hay equipos. ¡Sé el primero!</p>
    <?php else: ?>
    <table>
        <thead><tr><th>Equipo</th><th>Jugadores</th><th>Categoría</th><th>Inscrito</th></tr></thead>
        <tbody>
        <?php foreach ($equipos as $equipo): ?>
            <tr>
                <td><?= e($equipo['nombre']) ?></td>
                <td><?= e(implode(' y ', $equipo['jugadores'])) ?></td>
                <td><?= e(CATEGORIAS[$equipo['categoria']] ?? $equipo['categoria']) ?></td>
                <td><?= e(date('d/m H:i', strtotime($equipo['fecha']))) ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    <?php endif; ?>
</section>
</div>
</body>
</html>

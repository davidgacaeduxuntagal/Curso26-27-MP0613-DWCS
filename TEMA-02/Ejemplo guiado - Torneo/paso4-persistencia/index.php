<?php
/*
 * index.php — Torneo de futbolín del Bar Baio
 * PASO 4 de 5: persistencia. Los equipos se guardan en datos/equipos.json, se muestra la lista
 *              y las plazas libres, y después de guardar se redirige (POST/Redirect/GET).
 *              Ya cumple todos los requisitos, pero el código tiene repeticiones: en el paso 5
 *              se refactoriza.
 */
require_once __DIR__ . '/funciones.php';

// 1. Inicializar
$equipos = cargarEquipos();
$datos = ['equipo' => '', 'jugador1' => '', 'jugador2' => '', 'email' => '', 'telefono' => '', 'categoria' => ''];
$errores = [];
$mensaje = '';

// 2. Recepción y validación de datos
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $datos = recogerDatos();
    $errores = validarInscripcion($datos, $equipos);
    if (count($equipos) >= MAX_EQUIPOS) {
        $errores['general'] = 'El torneo está completo';
    }

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
        <div class="campo <?= isset($errores['equipo']) ? 'con-error' : '' ?>">
            <label for="equipo">Nombre del equipo</label>
            <input type="text" id="equipo" name="equipo" maxlength="30" value="<?= e($datos['equipo']) ?>">
            <?php if (isset($errores['equipo'])): ?><p class="error"><?= e($errores['equipo']) ?></p><?php endif; ?>
        </div>
        <div class="campo <?= isset($errores['jugador1']) ? 'con-error' : '' ?>">
            <label for="jugador1">Jugador 1</label>
            <input type="text" id="jugador1" name="jugador1" maxlength="40" value="<?= e($datos['jugador1']) ?>">
            <?php if (isset($errores['jugador1'])): ?><p class="error"><?= e($errores['jugador1']) ?></p><?php endif; ?>
        </div>
        <div class="campo <?= isset($errores['jugador2']) ? 'con-error' : '' ?>">
            <label for="jugador2">Jugador 2</label>
            <input type="text" id="jugador2" name="jugador2" maxlength="40" value="<?= e($datos['jugador2']) ?>">
            <?php if (isset($errores['jugador2'])): ?><p class="error"><?= e($errores['jugador2']) ?></p><?php endif; ?>
        </div>
        <div class="campo <?= isset($errores['email']) ? 'con-error' : '' ?>">
            <label for="email">Correo electrónico</label>
            <input type="email" id="email" name="email" value="<?= e($datos['email']) ?>">
            <?php if (isset($errores['email'])): ?><p class="error"><?= e($errores['email']) ?></p><?php endif; ?>
        </div>
        <div class="campo <?= isset($errores['telefono']) ? 'con-error' : '' ?>">
            <label for="telefono">Teléfono <span class="opcional">(opcional)</span></label>
            <input type="tel" id="telefono" name="telefono" value="<?= e($datos['telefono']) ?>">
            <?php if (isset($errores['telefono'])): ?><p class="error"><?= e($errores['telefono']) ?></p><?php endif; ?>
        </div>
        <div class="campo <?= isset($errores['categoria']) ? 'con-error' : '' ?>">
            <label for="categoria">Categoría</label>
            <select id="categoria" name="categoria">
                <option value="">— Elige —</option>
                <?php foreach (CATEGORIAS as $valor => $texto): ?>
                    <option value="<?= e($valor) ?>" <?= $datos['categoria'] === $valor ? 'selected' : '' ?>><?= e($texto) ?></option>
                <?php endforeach; ?>
            </select>
            <?php if (isset($errores['categoria'])): ?><p class="error"><?= e($errores['categoria']) ?></p><?php endif; ?>
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

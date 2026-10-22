<?php
/*
 * >>> PASO 5 de 5 (ÚLTIMO): SOLUCIÓN COMPLETA (añade la partida firmada con HMAC y allowed_classes) <<<
 *
 * TAREA-03 AVANZADA: Combate por turnos
 *  index.php hace de controlador: crea o recupera el combate, aplica la jugada y muestra la vista.
 */
require_once 'cargar.php';
require_once 'vista.php';

$errores = [];
$combate = null;
$nombres = [];
$clases  = [];

if (isset($_POST['nueva'])) {
    // ---------------------------------------------------------------- NUEVA PARTIDA
    $nombres = array_map(fn($n) => trim((string) $n), (array) ($_POST['nombres'] ?? []));
    $clases  = (array) ($_POST['clases'] ?? []);
    try {
        $miembros = [];
        for ($i = 0; $i < 3; $i++) {
            $clase = $clases[$i] ?? '';
            if (!in_array($clase, Personaje::CLASES, true)) {       // lista blanca de clases
                throw new InvalidArgumentException("Clase de personaje no válida");
            }
            $nombre     = ($nombres[$i] ?? '') === '' ? null : $nombres[$i];
            $miembros[] = $clase::crearAlAzar($nombre);              // método estático con la clase en una variable
        }
        $combate = new Combate(new Equipo("Tu equipo", $miembros), Equipo::aleatorio("La horda", 3));
    } catch (InvalidArgumentException $e) {
        $errores[] = $e->getMessage();
    }
} elseif (isset($_POST['partida'])) {
    // ---------------------------------------------------------------- PARTIDA EN CURSO
    $combate = Partida::cargar((string) $_POST['partida']);
    if ($combate === null) {
        $errores[] = "La partida no es válida o ha sido manipulada. Empieza una nueva.";
    } elseif (isset($_POST['rendirse'])) {
        $combate = null;
    } elseif (isset($_POST['deshacer'])) {
        $combate = $combate->deshacer() ?? $combate;
    } elseif (isset($_POST['jugar'])) {
        $accion   = (string) ($_POST['accion'] ?? '');
        $objetivo = filter_var($_POST['objetivo'] ?? null, FILTER_VALIDATE_INT, FILTER_NULL_ON_FAILURE);
        try {
            $combate->jugar($accion, $objetivo);
        } catch (InvalidArgumentException | LogicException $e) {
            $errores[] = $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Combate por turnos</title>
    <link rel="stylesheet" href="estilos.css">
</head>

<body>
    <h1>⚔️ Combate por turnos</h1>

    <?php foreach ($errores as $error): ?>
        <p class="error"><?= e($error) ?></p>
    <?php endforeach; ?>

    <?php if ($combate === null): ?>
        <?= formularioNuevaPartida($nombres, $clases) ?>
        <p class="nota">Clases: 🛡️ Guerrero (resistente, Golpe brutal) · 🔮 Mago (Bola de fuego a todos, usa maná) ·
            🏹 Arquero (Disparo certero, ignora la defensa) · ✨ Sanador (cura a todo el equipo, usa maná).</p>
    <?php else: ?>
        <form method="post">
            <p class="ronda">Ronda <?= $combate->getRonda() ?></p>

            <?php if ($combate->estaTerminado()): ?>
                <p class="final <?= $combate->getResultado() ?>">
                    <?= $combate->getResultado() === Combate::VICTORIA ? '🏆 ¡Victoria!' : '☠️ Derrota' ?>
                </p>
            <?php else: ?>
                <?= formularioJugada($combate) ?>
            <?php endif; ?>

            <div class="tablero">
                <?= columnaEquipo($combate->getJugador(), $combate->estaTerminado() ? null : $combate->indiceActivo()) ?>
                <?= columnaEquipo($combate->getEnemigo()) ?>
            </div>

            <p class="botones">
                <?php if ($combate->puedeDeshacer()): ?>
                    <button type="submit" name="deshacer" value="1">↶ Deshacer última jugada</button>
                <?php endif; ?>
                <button type="submit" name="rendirse" value="1"><?= $combate->estaTerminado() ? 'Nueva partida' : 'Rendirse' ?></button>
            </p>

            <?= historial($combate) ?>

            <!-- ESTADO: el combate serializado y firmado -->
            <input type="hidden" name="partida" value="<?= e(Partida::guardar($combate)) ?>">
        </form>
    <?php endif; ?>

    <p class="nota">Objetos Personaje creados en esta petición: <?= Personaje::numeroCreados() ?>
        (unserialize no llama al constructor, así que los personajes recuperados de la partida no cuentan).</p>
</body>

</html>

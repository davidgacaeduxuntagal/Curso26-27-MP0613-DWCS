<?php
/*
 * index.php — Torneo de futbolín del Bar Baio
 * PASO 2 de 5: descomposición del problema en TODOs. La página es la maqueta del paso 1 con
 *              comentarios TODO en cada parte que hay que programar. Se puede ejecutar: las
 *              funciones de funciones.php devuelven valores provisionales.
 *              (Con la extensión Todo Tree de VSC verás todos los TODO en el panel lateral.)
 */
require_once __DIR__ . '/funciones.php';

// 1. Inicializar
// TODO (paso 3): $datos con los 6 campos vacíos, $errores vacío, $mensaje vacío
// TODO (paso 4): $equipos = cargarEquipos()
$equipos = [];
$errores = [];
$mensaje = '';

// 2. Recepción y validación de datos
// TODO (paso 3): si la petición es POST: $datos = recogerDatos(); $errores = validarInscripcion(...)
// TODO (paso 4): si ya hay MAX_EQUIPOS equipos, error general "El torneo está completo"

// 3. Procesar y persistir los datos
// TODO (paso 4): si no hay errores: crearEquipo, añadirlo a $equipos, guardarEquipos y redirigir
//                a index.php?ok=1 (POST/Redirect/GET); si no se puede guardar, error general

// 4. Mensajes informativos
// TODO (paso 4): si llega ?ok=1, mensaje "Inscripción guardada. ¡Mucha suerte, <equipo>!"
$plazas = MAX_EQUIPOS - count($equipos);

// 5. Enviar la página al cliente (el HTML de abajo)
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

<!-- TODO (paso 3): mostrar $mensaje (clase "mensaje ok") y $errores['general'] (clase "mensaje ko") -->

<div class="columnas">
<section>
    <h2>Inscripción</h2>
    <!-- TODO (paso 4): si no quedan plazas, mostrar "El torneo está completo" en lugar del formulario -->
    <form method="post" action="index.php" novalidate>
        <!-- TODO (paso 3): en cada campo, value con el dato escrito (escapado con e()),
                            clase "con-error" y el mensaje de error si lo tiene -->
        <div class="campo">
            <label for="equipo">Nombre del equipo</label>
            <input type="text" id="equipo" name="equipo" maxlength="30" value="">
        </div>
        <div class="campo">
            <label for="jugador1">Jugador 1</label>
            <input type="text" id="jugador1" name="jugador1" maxlength="40" value="">
        </div>
        <div class="campo">
            <label for="jugador2">Jugador 2</label>
            <input type="text" id="jugador2" name="jugador2" maxlength="40" value="">
        </div>
        <div class="campo">
            <label for="email">Correo electrónico</label>
            <input type="email" id="email" name="email" value="">
        </div>
        <div class="campo">
            <label for="telefono">Teléfono <span class="opcional">(opcional)</span></label>
            <input type="tel" id="telefono" name="telefono" value="">
        </div>
        <div class="campo">
            <label for="categoria">Categoría</label>
            <select id="categoria" name="categoria">
                <option value="">— Elige —</option>
                <!-- TODO (paso 3): generar las opciones con un foreach sobre CATEGORIAS (y "selected") -->
                <option value="mixta">Mixta</option>
                <option value="juvenil">Juvenil (menores de 18)</option>
                <option value="senior">Sénior</option>
            </select>
        </div>
        <button type="submit">Inscribir equipo</button>
    </form>
</section>

<section>
    <h2>Equipos inscritos (<?= count($equipos) ?>/<?= MAX_EQUIPOS ?>)</h2>
    <p>Plazas libres: <strong><?= $plazas ?></strong></p>
    <!-- TODO (paso 4): si no hay equipos, "Todavía no hay equipos"; si no, una fila por equipo
                        con un foreach sobre $equipos (todos los datos escapados con e()) -->
    <table>
        <thead><tr><th>Equipo</th><th>Jugadores</th><th>Categoría</th><th>Inscrito</th></tr></thead>
        <tbody>
            <tr><td>Los Pulpos</td><td>Ana Pérez y Brais Otero</td><td>Mixta</td><td>16/10 21:30</td></tr>
        </tbody>
    </table>
</section>
</div>
</body>
</html>

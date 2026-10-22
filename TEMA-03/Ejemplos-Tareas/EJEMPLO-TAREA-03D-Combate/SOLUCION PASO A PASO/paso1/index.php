<?php
/*
 * >>> PASO 1 de 5: descomposición del problema (solo TODOs, sin código PHP) <<<
 *
 * TAREA-03 AVANZADA: Combate por turnos
 *  index.php hace de controlador: crea o recupera el combate, aplica la jugada y muestra la vista.
 */
// TODO: incluir cargar.php (configuración y clases) y vista.php (funciones que generan HTML)

// TODO: ¿NUEVA PARTIDA? (botón "¡A luchar!")
//          - entrada de datos: 3 nombres (opcionales) y 3 clases
//          - validar las clases con una lista blanca (Personaje::CLASES)
//          - crear cada personaje con $clase::crearAlAzar($nombre)
//          - crear el equipo del jugador, un equipo enemigo aleatorio y el Combate

// TODO: ¿PARTIDA EN CURSO? (campo oculto "partida")
//          - comprobar estado: Partida::cargar(); si es null -> error "partida manipulada"
//          - rendirse  -> volver al formulario de nueva partida
//          - deshacer  -> volver a la copia anterior del combate
//          - jugar     -> acción (atacar / especial) y objetivo; capturar las excepciones de las jugadas no válidas
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

    <!-- TODO: errores, si los hay -->

    <!-- TODO: si no hay combate: formulario de nueva partida (formularioNuevaPartida) -->

    <!-- TODO: si hay combate, un formulario POST con:
               - la ronda y el resultado (si ha terminado) o el formulario de la jugada (formularioJugada)
               - las dos columnas de equipos (columnaEquipo), con el personaje activo resaltado
               - botones Deshacer (si se puede) y Rendirse / Nueva partida
               - el historial
               - guardar el estado en una variable hidden: Partida::guardar($combate) -->

    <!-- TODO: nota con Personaje::numeroCreados() -->
</body>

</html>

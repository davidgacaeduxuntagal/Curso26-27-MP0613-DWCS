<!--
  index.php — Torneo de futbolín del Bar Baio
  PASO 1 de 5: maqueta (mockup). Solo HTML y CSS, sin nada de PHP: sirve para enseñársela al
               cliente y decidir con él qué campos hay, qué mensajes salen y dónde, ANTES de
               programar. Los datos son inventados: un error de ejemplo en un campo, un mensaje
               de confirmación y un equipo en la lista.
-->
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

<p class="mensaje ok">Inscripción guardada. ¡Mucha suerte, Los Pulpos!</p>

<div class="columnas">
<section>
    <h2>Inscripción</h2>
    <form method="post" action="index.php" novalidate>
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
        <div class="campo con-error">
            <label for="email">Correo electrónico</label>
            <input type="email" id="email" name="email" value="ana@">
            <p class="error">El correo no es válido</p>
        </div>
        <div class="campo">
            <label for="telefono">Teléfono <span class="opcional">(opcional)</span></label>
            <input type="tel" id="telefono" name="telefono" value="">
        </div>
        <div class="campo">
            <label for="categoria">Categoría</label>
            <select id="categoria" name="categoria">
                <option value="">— Elige —</option>
                <option value="mixta">Mixta</option>
                <option value="juvenil">Juvenil (menores de 18)</option>
                <option value="senior">Sénior</option>
            </select>
        </div>
        <button type="submit">Inscribir equipo</button>
    </form>
</section>

<section>
    <h2>Equipos inscritos (1/16)</h2>
    <p>Plazas libres: <strong>15</strong></p>
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

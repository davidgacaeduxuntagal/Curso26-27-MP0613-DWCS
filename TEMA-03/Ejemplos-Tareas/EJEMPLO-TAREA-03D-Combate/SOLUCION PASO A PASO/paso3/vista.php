<?php
/*
 * vista.php
 *  Funciones que GENERAN HTML a partir de los objetos (devuelven strings; el echo lo hace index.php).
 */

function e(string $texto): string
{
    return htmlspecialchars($texto, ENT_QUOTES, 'UTF-8');
}

/**
 * Barra de progreso (vida o maná).
 */
function barra(int $valor, int $maximo, string $clase): string
{
    $porcentaje = $maximo > 0 ? (int) round($valor * 100 / $maximo) : 0;
    return "<div class='barra {$clase}'><div style='width: {$porcentaje}%'></div><span>{$valor}/{$maximo}</span></div>";
}

/**
 * Tarjeta de un personaje. Gracias a la interfaz UsaMana sabemos si hay que dibujar la barra de maná.
 */
function tarjetaPersonaje(Personaje $p, bool $activo = false): string
{
    $clases = 'personaje' . ($p->estaVivo() ? '' : ' caido') . ($activo ? ' activo' : '');
    $html   = "<div class='{$clases}'>";
    $html  .= "<div class='cabecera'><span class='icono'>{$p->icono()}</span> <strong>" . e($p->nombre) . "</strong>"
            . " <small>{$p->clase()}</small></div>";
    $html  .= barra($p->vida, $p->vidaMax, 'vida');
    if ($p instanceof UsaMana) {
        $html .= barra($p->getMana(), $p->getManaMax(), 'mana');
    }
    $html  .= "<small>⚔ {$p->ataque} · 🛡 {$p->defensa}";
    if ($p->enfriamiento > 0) {
        $html .= " · especial en {$p->enfriamiento} turno(s)";
    }
    $html  .= "</small></div>";
    return $html;
}

/**
 * Columna con todos los miembros de un equipo.
 */
function columnaEquipo(Equipo $equipo, ?int $indiceActivo = null): string
{
    $html = "<section class='equipo'><h2>" . e($equipo->getNombre()) . " <small>(" . count($equipo->vivos()) . "/" . count($equipo) . ")</small></h2>";
    foreach ($equipo->getMiembros() as $i => $personaje) {
        $html .= tarjetaPersonaje($personaje, $i === $indiceActivo);
    }
    return $html . "</section>";
}

/**
 * Formulario de nueva partida: nombre y clase de 3 personajes.
 */
function formularioNuevaPartida(array $nombres, array $clases): string
{
    $html = "<form method='post' class='nueva'><h2>Crea tu equipo</h2>";
    for ($i = 0; $i < 3; $i++) {
        $html .= "<p>Personaje " . ($i + 1) . ": <input type='text' name='nombres[{$i}]' maxlength='20' placeholder='(al azar)' value='" . e($nombres[$i] ?? '') . "'> ";
        $html .= "<select name='clases[{$i}]'>";
        foreach (Personaje::CLASES as $clase) {
            $seleccionada = ($clases[$i] ?? Personaje::CLASES[$i]) === $clase ? ' selected' : '';
            $html .= "<option{$seleccionada}>{$clase}</option>";
        }
        $html .= "</select></p>";
    }
    return $html . "<button type='submit' name='nueva' value='1'>¡A luchar!</button></form>";
}

/**
 * Formulario de la jugada del personaje activo.
 */
function formularioJugada(Combate $combate): string
{
    $activo = $combate->personajeActivo();
    $html   = "<div class='jugada'><h3>Turno de " . e((string) $activo) . "</h3>";
    $html  .= "<label><input type='radio' name='accion' value='atacar' checked> Atacar</label> ";
    $deshabilitado = $activo->puedeUsarEspecial() ? '' : ' disabled';
    $html  .= "<label><input type='radio' name='accion' value='especial'{$deshabilitado}> ✨ {$activo->nombreEspecial()}"
            . ($activo->especialNecesitaObjetivo() ? '' : ' (afecta a todos)') . "</label> ";
    $html  .= "<label>Objetivo: <select name='objetivo'>";
    foreach ($combate->getEnemigo()->vivos() as $i => $enemigo) {
        $html .= "<option value='{$i}'>" . e($enemigo->nombre) . " ({$enemigo->vida} ❤)</option>";
    }
    $html  .= "</select></label> ";
    $html  .= "<button type='submit' name='jugar' value='1'>Actuar</button>";
    return $html . "</div>";
}

function historial(Combate $combate): string
{
    $html = "<section class='historial'><h3>Historial</h3><ol>";
    foreach (array_reverse($combate->getHistorial()) as $linea) {
        $html .= "<li>" . e($linea) . "</li>";
    }
    return $html . "</ol></section>";
}

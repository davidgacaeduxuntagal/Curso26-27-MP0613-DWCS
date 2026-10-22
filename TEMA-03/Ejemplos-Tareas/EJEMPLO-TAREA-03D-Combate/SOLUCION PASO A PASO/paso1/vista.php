<?php
/*
 * vista.php
 *  Funciones que GENERAN HTML a partir de los objetos (devuelven strings; el echo lo hace index.php).
 */

// Función auxiliar TODO: e(string $texto): string
//      atajo de htmlspecialchars

// Función auxiliar TODO: barra(int $valor, int $maximo, string $clase): string
//      barra de progreso (vida o maná) con el texto "valor/máximo"

// Función auxiliar TODO: tarjetaPersonaje(Personaje $p, bool $activo = false): string
//      icono, nombre, clase, barra de vida, barra de maná SOLO si $p instanceof UsaMana, ataque,
//      defensa y turnos de espera de la especial; clases CSS "activo" y "caido"

// Función auxiliar TODO: columnaEquipo(Equipo $equipo, ?int $indiceActivo = null): string
//      título con el nombre y los vivos / total (count) y una tarjeta por miembro

// Función auxiliar TODO: formularioNuevaPartida(array $nombres, array $clases): string
//      3 x (nombre opcional + <select> con Personaje::CLASES) y botón "¡A luchar!"

// Función auxiliar TODO: formularioJugada(Combate $combate): string
//      radios atacar / especial (deshabilitada si !puedeUsarEspecial()), <select> con los enemigos vivos
//      (conservando su índice) y botón "Actuar"

// Función auxiliar TODO: historial(Combate $combate): string
//      lista con las líneas del historial, de la más reciente a la más antigua

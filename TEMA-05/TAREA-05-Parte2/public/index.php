<?php
error_reporting(E_ALL & ~E_DEPRECATED);

require '../vendor/autoload.php';

use Clases\Jugadores;

$jugador = new Jugadores();

if ($jugador->tieneDatos()) {
    $jugador = null;
    header('Location: jugadores.php');
} else {
    $jugador = null;
    header('Location: instalacion.php');
}

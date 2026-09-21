<?php
error_reporting(E_ALL & ~E_DEPRECATED);

session_start();
require '../vendor/autoload.php';

use Clases\Jugadores;
use Milon\Barcode\DNS1D;
use Philo\Blade\Blade;

$views = '../views';
$cache = '../cache';
$blade = new Blade($views, $cache);

$d          = new DNS1D();
$titulo     = 'Jugadores';
$encabezado = 'Listado de Jugadores';
$jugadores  = (new Jugadores())->recuperarJugadores();

$d->setStorPath($cache);

if (isset($_SESSION['mensaje'])) {
    $mensaje = $_SESSION['mensaje'];
    unset($_SESSION['mensaje']);       //para no volver a repetir el mensaje
    echo $blade
        ->view()
        ->make('vjugadores', compact('titulo', 'encabezado', 'jugadores', 'd', 'mensaje'))
        ->render();
} else {
    echo $blade
        ->view()
        ->make('vjugadores', compact('titulo', 'encabezado', 'jugadores', 'd'))
        ->render();
}

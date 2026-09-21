<?php
error_reporting(E_ALL & ~E_DEPRECATED);

session_start();
require '../vendor/autoload.php';

use Clases\Jugadores;
use Faker\Factory;

$faker   = Factory::create('es_Es');
$jugador = new Jugadores();

//generamos un codigo y comprobamos que No existe
while (true) {
    $code = $faker->ean13;
    if (!$jugador->existeBarcode($code)) {
        $jugador = null;
        break;
    }
}

$_SESSION['codigo'] = $code;
header('Location: fcrear.php');

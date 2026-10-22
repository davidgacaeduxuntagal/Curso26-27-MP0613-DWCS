<?php
/*
 * cargar.php
 *  Carga la configuración y todas las clases en el orden correcto: primero las interfaces y los
 *  traits, después la clase padre y por último las subclases.
 *  (En temas posteriores veremos la carga automática con spl_autoload_register y Composer.)
 */

// Clave secreta para firmar la partida. En una aplicación real NO se escribe en el código fuente:
// se lee de una variable de entorno o de un fichero fuera de la carpeta pública.
const CLAVE_SECRETA = 'cambia-esta-clave-larga-y-aleatoria-7c1e9f';

require_once __DIR__ . '/clases/UsaMana.php';
require_once __DIR__ . '/clases/ConMana.php';
require_once __DIR__ . '/clases/Personaje.php';
require_once __DIR__ . '/clases/Guerrero.php';
require_once __DIR__ . '/clases/Mago.php';
require_once __DIR__ . '/clases/Arquero.php';
require_once __DIR__ . '/clases/Sanador.php';
require_once __DIR__ . '/clases/Equipo.php';
require_once __DIR__ . '/clases/Combate.php';
require_once __DIR__ . '/clases/Partida.php';

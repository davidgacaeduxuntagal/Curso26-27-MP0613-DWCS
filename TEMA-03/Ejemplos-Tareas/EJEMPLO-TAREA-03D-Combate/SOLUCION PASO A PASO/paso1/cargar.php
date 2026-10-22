<?php
/*
 * cargar.php
 *  Carga la configuración y todas las clases en el orden correcto: primero las interfaces y los
 *  traits, después la clase padre y por último las subclases.
 *  (En temas posteriores veremos la carga automática con spl_autoload_register y Composer.)
 */

// TODO: constante CLAVE_SECRETA para firmar la partida (en una aplicación real, fuera del código)

// TODO: require_once, en este orden: UsaMana, ConMana, Personaje, Guerrero, Mago, Arquero, Sanador,
//       Equipo, Combate y Partida

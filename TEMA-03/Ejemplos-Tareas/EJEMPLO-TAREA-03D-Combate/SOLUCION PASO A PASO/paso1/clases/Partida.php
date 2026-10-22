<?php
/*
 * Partida.php
 *  Guarda y recupera el combate entre peticiones en un campo oculto (todavía sin sesiones).
 *
 *  - serialize() convierte el objeto Combate (con sus equipos y personajes) en texto.
 *  - Ese texto lo puede modificar el jugador (¡ponerse 9999 de vida!). Para detectarlo añadimos
 *    una FIRMA: hash_hmac('sha256', datos, CLAVE_SECRETA). Sin la clave, que solo está en el
 *    servidor, no se puede calcular una firma válida para unos datos modificados.
 *  - unserialize() solo puede crear objetos de las clases de la lista allowed_classes.
 */
// TODO: final class Partida
//   TODO: private const CLASES_PERMITIDAS = [Combate::class, Equipo::class, Guerrero::class, Mago::class, Arquero::class, Sanador::class];
//   TODO: private function __construct()
//   TODO: public static function guardar(Combate $combate): string
//   TODO: public static function cargar(string $texto): ?Combate   -> Devuelve el combate, o null si el texto no es válido o ha sido manipulado.
//   TODO: private static function firmar(string $datos): string

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
final class Partida
{
    private const CLASES_PERMITIDAS = [Combate::class, Equipo::class, Guerrero::class, Mago::class, Arquero::class, Sanador::class];

    private function __construct()
    {
    }

    public static function guardar(Combate $combate): string
    {
        $datos = base64_encode(serialize($combate));
        return $datos . '.' . self::firmar($datos);
    }

    /**
     * Devuelve el combate, o null si el texto no es válido o ha sido manipulado.
     */
    public static function cargar(string $texto): ?Combate
    {
        $partes = explode('.', $texto);
        if (count($partes) !== 2) {
            return null;
        }
        [$datos, $firma] = $partes;
        // hash_equals compara en tiempo constante (evita ataques que miden cuánto tarda la comparación)
        if (!hash_equals(self::firmar($datos), $firma)) {
            return null;
        }
        $combate = unserialize(base64_decode($datos), ['allowed_classes' => self::CLASES_PERMITIDAS]);
        return $combate instanceof Combate ? $combate : null;
    }

    private static function firmar(string $datos): string
    {
        return hash_hmac('sha256', $datos, CLAVE_SECRETA);
    }
}

<?php
// e10-src/Tienda/Modelo/Cliente.php
namespace Tienda\Modelo;

use DateTimeImmutable;      // las clases de PHP están en el espacio de nombres GLOBAL (\DateTimeImmutable)

class Cliente
{
    public readonly DateTimeImmutable $alta;

    public function __construct(public readonly string $nombre)
    {
        $this->alta = new DateTimeImmutable('2026-10-16');
    }
}

<?php
enum CargoDirectivo: string
{
    case Ninguno = 'Ninguno';
    case Direccion = 'Dirección';
    case Secretariado = 'Secretariado';
    case JefaturaEstudiosDiurno = 'Jefatura de Estudios Diurno';
    case JefaturaEstudiosAdultos = 'Jefatura de Estudios de Personas Adultas';
    case Vicedireccion = 'Vicedirección';
}
